<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Products Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Products Selector
			'product_ids'                => '',
			'categories'                 => '',
			'brands'                     => '',
			'status'                     => '',
			'count'                      => array( 'size' => 10 ),
			'orderby'                    => '',
			'orderway'                   => 'ASC',
			'order_from'                 => '',
			'order_from_date'            => '',
			'order_to'                   => '',
			'order_to_date'              => '',
			'hide_out_date'              => '',
			'count_text'                 => esc_html__( 'Sold: %1$s/%2$s', 'alpha-core' ),

			// Products Layout
			'row_cnt'                    => 1,
			'col_cnt'                    => array( 'size' => 4 ),
			'col_sp'                     => '',
			'layout_type'                => 'grid',
			'creative_cols'              => '',
			'creative_cols_tablet'       => '',
			'creative_cols_mobile'       => '',
			'items_list'                 => '',
			'loadmore_type'              => '',
			'loadmore_label'             => '',
			'filter_cat_w'               => '',
			'filter_cat'                 => '',
			'show_all_filter'            => '',
			'thumbnail_size'             => 'woocommerce_thumbnail',
			'thumbnail_custom_dimension' => '',

			// Product Type
			'follow_theme_option'        => '',
			'show_labels'                => array( 'hot', 'sale', 'new', 'stock' ),
			'product_type'               => '',
			'content_align'              => '',
			'show_info'                  => array( 'label', 'price', 'rating', 'countdown', 'addtocart', 'quickview', 'wishlist', 'compare' ),
			'page_builder'               => '',
			'wrapper_id'                 => '',
		),
		$atts
	)
);

do_action( 'alpha_eqnueue_product_widget_related_scripts' );

// Get Count ////////////////////////////////////////////////////////////////////////////////////
if ( $count && ! is_array( $count ) ) {
	$count = json_decode( $count, true );
}
if ( $col_cnt && ! is_array( $col_cnt ) ) {
	$col_cnt = json_decode( $col_cnt, true );
}
if ( $show_labels && ! is_array( $show_labels ) ) {
	$show_labels = explode( ',', $show_labels );
}
if ( $show_info ) {
	if ( ! is_array( $show_info ) ) {
		$show_info = explode( ',', $show_info );
	}
} else {
	$show_info = array();
}
if ( $categories && ! is_array( $categories ) ) {
	$categories = explode( ',', $categories );
}
if ( $brands && ! is_array( $brands ) ) {
	$brands = explode( ',', $brands );
}
$count = (int) $count['size'];

$col_cnt = alpha_elementor_grid_col_cnt( $atts );

// Products Args ////////////////////////////////////////////////////////////////////////////////

$props         = array();
$args          = array(
	'columns'  => $col_cnt['lg'],
	'per_page' => $count,
);
$is_filter_cat = false;
// product status
if ( 'featured' == $status ) {
	$args['visibility'] = 'featured';
} elseif ( 'sale' == $status ) {
	$args['on_sale'] = 1;
} elseif ( 'viewed' == $status && ! $product_ids ) {
	$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed_' . get_current_blog_id() ] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed_' . get_current_blog_id() ] ) ) : array(); // @codingStandardsIgnoreLine
	$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

	if ( ! empty( $viewed_products ) && count( $viewed_products ) ) {
		$product_ids = $viewed_products;
	} else {
		esc_html_e( 'There\'s no item that you viewed recently.', 'alpha-core' );
		return;
	}
} elseif ( 'related' == $status || 'upsell' == $status ) {
	global $product;
	if ( ! empty( $product ) ) {
		if ( 'related' == $status ) {
			$product_ids = wc_get_related_products( $product->get_id(), $count, $product->get_upsell_ids() );
		} else {
			$product_ids = $product->get_upsell_ids();
		}
	}
} elseif ( 'crosssell' == $status && function_exists( 'alpha_is_elementor_preview' ) && ! alpha_is_elementor_preview() ) { // Cross-sells products

	$product_ids = is_object( WC()->cart ) ? WC()->cart->get_cross_sells() : array();
}

// If not empty linked products for single product
if ( ! ( ( 'related' == $status || 'upsell' == $status ) && is_array( $product_ids ) && count( $product_ids ) == 0 ) ) {
	if ( $product_ids ) {

		if ( is_string( $product_ids ) ) {
			// custom IDs
			$product_ids = explode( ',', str_replace( ' ', '', esc_attr( $product_ids ) ) );
			if ( defined( 'ALPHA_VERSION' ) ) {
				for ( $i = 0; isset( $product_ids[ $i ] );  ++ $i ) {
					if ( ! is_numeric( $product_ids[ $i ] ) ) {
						$product_ids[ $i ] = alpha_get_post_id_by_name( 'product', $product_ids[ $i ] );
					}
				}
			}
		}
		if ( is_array( $product_ids ) ) {
			$args['ids']     = implode( ',', $product_ids );
			$args['orderby'] = 'post__in';
		}
	} else {
		// custom ordering
		$args['order']   = esc_attr( $orderway );
		$args['orderby'] = esc_attr( $orderby );

		if ( $order_from ) {
			if ( 'custom' == $order_from && $order_from_date ) {
				set_query_var( 'order_from', esc_attr( $order_from_date ) );
			} elseif ( 'custom' !== $order_from ) {
				set_query_var( 'order_from', esc_attr( $order_from ) );
			}
		}
		if ( $order_to ) {
			if ( 'custom' == $order_to && $order_to_date ) {
				set_query_var( 'order_to', esc_attr( $order_to_date ) );
			} elseif ( 'custom' !== $order_to ) {
				set_query_var( 'order_to', esc_attr( $order_to ) );
			}
		}
		set_query_var( 'hide_out_date', $hide_out_date );
	}

	if ( ! empty( $atts['sp_id'] ) ) {
		$args['exclude'] = $atts['sp_id'];
	}

	if ( is_array( $categories ) && count( $categories ) ) {
		// custom categories
		$args['category'] = esc_attr( implode( ',', $categories ) );
	}

	if ( is_array( $brands ) && count( $brands ) ) {
		// custom brands
		$args['class'] = 'custom_brands';
		foreach ( $brands as $brand ) {
			$args['class'] .= ',' . get_term( $brand, 'product_brand' )->name;
		}
	}

	// Wrapper classes & attributes ///////////////////////////////////////////////////////////

	$wrapper_class = array();
	$wrapper_attrs = '';

	$grid_space_class = alpha_get_grid_space_class( $atts );
	if ( $grid_space_class ) {
		$wrapper_class[] = $grid_space_class;
	}

	// Slider
	if ( 'slider' == $layout_type ) {
		$wrapper_class[]  = alpha_get_slider_class( $atts );
		$wrapper_attrs   .= ' data-slider-options="' . esc_attr(
			json_encode(
				alpha_get_slider_attrs( $atts, $col_cnt )
			)
		) . '"';
		$props['row_cnt'] = $row_cnt;
	}

	// Creative Grid Style
	if ( 'creative' == $layout_type ) {
		$wrapper_class[] = 'creative-grid row';
		if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
			$wrapper_class[] = 'editor-mode';
		}
		if ( isset( $atts['creative_mode'] ) ) {
			$wrapper_class[]        = 'preset-grid grid-layout-' . $atts['creative_mode'];
			$props['creative_mode'] = $atts['creative_mode'];
		}
	}

	// Filter by Category ////////////////////////////////////////////////////////////////////////

	if ( $filter_cat_w ) {
		wc_set_loop_prop( 'filter_cat_w', true );
		$is_filter_cat = true;
	}

	if ( 'yes' == $filter_cat ) {
		$term_args = array(
			'taxonomy' => 'product_cat',
		);

		if ( is_array( $categories ) ) {
			if ( 1 < count( $categories ) ) {
				$term_args['include'] = implode( ',', $categories );
				$term_args['orderby'] = 'include';
			} else {
				$term_args['parent'] = count( $categories ) ? $categories[0] : 0;
			}
		}

		$terms = get_terms( $term_args );

		if ( count( $terms ) > 1 ) {
			$slugs         = array();
			$category_html = '';
			$idx           = 0;

			foreach ( $terms as $term_cat ) {
				$id             = $term_cat->term_id;
				$name           = $term_cat->name;
				$slug           = $term_cat->slug;
				$slugs[]        = $slug;
				$category_html .= '<li><a href="' . esc_url( get_term_link( $id, 'product_cat' ) ) . '" class="nav-filter' . ( 0 == $idx && 'yes' != $show_all_filter ? ' active' : '' ) . '" data-cat="' . $id . '">' . esc_html( $name ) . '</a></li>';
				++ $idx;
			}

			if ( $category_html ) {
				$category_html = '<ul class="nav-filters product-filters">' . ( 'yes' == $show_all_filter ? '<li class="nav-filter-clean"><a href="#" class="nav-filter active">' . esc_html__( 'All', 'alpha-core' ) . '</a></li>' : '' ) . $category_html . '</ul>';

				echo apply_filters( 'alpha_products_filter_cat_html', $category_html );

				wc_set_loop_prop( 'filter_cat', true );
				$is_filter_cat = true;
			}

			if ( 'yes' != $show_all_filter ) {
				$args['category'] = $terms[ array_key_first( $terms ) ]->term_taxonomy_id;
			}
		}
	}

	// Product Props ///////////////////////////////////////////////////////////////////////////////

	$props['follow_theme_option'] = $follow_theme_option;
	if ( 'yes' != $follow_theme_option ) {

		if ( $product_type ) {
			$props['product_type'] = $product_type;
		}
		if ( $content_align ) {
			$props['content_align'] = $content_align;
		}
		if ( is_array( $show_info ) ) {
			$props['show_info'] = $show_info;
		}
	}

	$props['show_labels'] = $show_labels;

	if ( $count_text ) {
		$props['count_text'] = $count_text;
	}

	// Product Layout Props ////////////////////////////////////////////////////////////////////////

	$props['widget'] = 'product-group';
	if ( ! empty( $atts['widget'] ) ) {
		$props['widget'] = $atts['widget'];
	}
	$props['layout_type']    = $layout_type;
	$props['col_sp']         = $col_sp;
	$props['thumbnail_size'] = $thumbnail_size;
	if ( 'custom' == $thumbnail_size && $thumbnail_custom_dimension ) {
		$props['thumbnail_custom_size'] = $thumbnail_custom_dimension;
	}
	$props['wrapper_class'] = $wrapper_class;
	wc_set_loop_prop( 'wrapper_attrs', $wrapper_attrs );

	$props['col_cnt'] = $col_cnt;

	// Props for loadmore

	if ( $loadmore_type || $is_filter_cat ) {
		$args['paginate']        = 1;
		$props['loadmore_type']  = $loadmore_type;
		$props['loadmore_label'] = $loadmore_label;

		if ( 'button' == $loadmore_type ) {
			$settings                    = shortcode_atts(
				array(
					'button_type'                => '',
					'button_size'                => '',
					'button_skin'                => 'btn-primary',
					'shadow'                     => '',
					'button_border'              => '',
					'link_hover_type'            => '',
					'link'                       => '',
					'show_icon'                  => '',
					'show_label'                 => 'yes',
					'icon'                       => '',
					'icon_pos'                   => 'after',
					'icon_hover_effect'          => '',
					'icon_hover_effect_infinite' => '',
				),
				$atts
			);
			$props['loadmore_btn_style'] = $settings;
		}

		wc_set_loop_prop( 'loadmore_props', $props );
		wc_set_loop_prop( 'loadmore_args', $args );
	}


	// Do Shortcode /////////////////////////////////////////////////////////////////////////////////

	foreach ( $props as $key => $value ) {
		wc_set_loop_prop( $key, $value );
	}

	if ( is_array( $items_list ) ) {
		$repeaters = array(
			'ids'          => array(),
			'images'       => array(),
			'product_type' => array(),
		);
		foreach ( $items_list as $item ) {
			$repeaters['ids'][ (int) $item['item_no'] ]          = 'elementor-repeater-item-' . $item['_id'];
			$repeaters['images'][ (int) $item['item_no'] ]       = $item['item_thumb_size'];
			$repeaters['product_type'][ (int) $item['item_no'] ] = $item['product_type'];
		}
		wc_set_loop_prop( 'repeaters', $repeaters );
	}
	$GLOBALS['alpha_current_product_id'] = 0;

	$args_str = '';
	foreach ( $args as $key => $value ) {
		$args_str .= ' ' . $key . '=' . json_encode( $value );
	}

	echo do_shortcode( '[products' . $args_str . ']' );

	if ( isset( $GLOBALS['alpha_current_product_id'] ) ) {
		unset( $GLOBALS['alpha_current_product_id'] );
	}
}
