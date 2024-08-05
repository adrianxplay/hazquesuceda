<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Posts Grid Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

$default_atts = array(
	'shortcode_type'       => '',
	'shortcode'            => '',
	'builder_id'           => '',
	'list_builder_id'      => '',
	'source'               => '',
	'post_type'            => '',
	'product_status'       => '',
	'post_tax'             => '',
	'post_terms'           => '',
	'tax'                  => '',
	'terms'                => '',
	'count'                => '',
	'hide_empty'           => '',
	'orderby'              => '',
	'orderby_term'         => '',
	'orderway'             => '',
	'cats'                 => '',

	'view'                 => 'grid',
	'row_cnt'              => 1,
	'col_cnt'              => 4,
	'col_cnt_xl'           => '',
	'col_cnt_tablet'       => '',
	'col_cnt_mobile'       => '',
	'col_cnt_min'          => '',
	'list_col_cnt'         => '',
	'col_sp'               => '',
	'creative_cols'        => '',
	'creative_cols_tablet' => '',
	'creative_cols_mobile' => '',
	'items_list'           => '',
	'loadmore_type'        => '',
	'loadmore_label'       => '',
	'filter_cat_w'         => '',
	'filter_cat'           => '',
	'filter_cat_tax'       => '',
	'show_all_filter'      => '',
	'thumbnail_size'       => '',
	'post_found_nothing'   => '',

	'posts_wrap_cls'       => '',
	'hover_full_image'     => '',

	'is_related'           => false,
);
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		$default_atts,
		$atts
	)
);

if ( 'archive' == $shortcode_type ) {
	global $wp_query;
	if ( isset( $wp_query->query_vars ) && ! empty( $wp_query->query_vars['post_type'] ) ) {
		$post_type = $wp_query->query_vars['post_type'];
	} else {
		$post_type = get_post_type();
	}
	if ( ! $post_type || is_array( $post_type ) ) {
		$post_type = 'post';
	}
	if ( 'product' == $post_type ) {
		if ( empty( $list_col_cnt ) ) {
			$list_col_cnt = 1;
		}
		if ( isset( $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) && 'list' == $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) {
			$builder_id_backup = $builder_id;
			$builder_id        = $list_builder_id;
			$list_builder_id   = $builder_id_backup;
			$toggle_cols       = array(
				'col_cnt_xl'     => $col_cnt_xl,
				'col_cnt'        => empty( $col_cnt ) ? 4 : (int) $col_cnt,
				'col_cnt_tablet' => $col_cnt_tablet,
				'col_cnt_mobile' => $col_cnt_mobile,
				'col_cnt_min'    => $col_cnt_min,
			);

			$view                = 'grid';
			$atts['col_cnt']     = (int) $list_col_cnt;
			$atts['col_cnt_min'] = 1;
			unset( $atts['col_cnt_xl'], $atts['col_cnt_tablet'], $atts['col_cnt_mobile'] );
		} else {
			$toggle_cols = array(
				'col_cnt'     => (int) $list_col_cnt,
				'col_cnt_min' => 1,
			);
		}
	}
}

$builder_post = false;
if ( $builder_id ) {
	$builder_post = get_post( (int) $builder_id );
}

if ( 'archive' != $shortcode_type && empty( $loadmore_type ) ) {
	$loadmore_type = 'no';
}

$posts         = array();
$is_filter_cat = false;
$props         = array(
	'widget'         => true,
	'posts_layout'   => $view,
	'cpt'            => $post_type ? ( false !== strpos( $post_type, ALPHA_NAME ) ? substr( $post_type, strlen( ALPHA_NAME ) + 1 ) : $post_type ) : '',
	'thumbnail_size' => $thumbnail_size,
);

// Filter by Category ////////////////////////////////////////////////////////////////////////
if ( 'yes' == $filter_cat && 'archive' == $shortcode_type && ! $filter_cat_tax && 'product' == $post_type ) {
	$filter_cat_tax = 'product_cat';
}

if ( empty( $source ) && 'yes' == $filter_cat && $filter_cat_tax ) {
	$term_args = array(
		'taxonomy' => sanitize_text_field( $filter_cat_tax ),
	);

	if ( $post_tax == $filter_cat_tax && ! empty( $post_terms ) ) {
		if ( ! is_array( $post_terms ) ) {
			$post_terms = array_map( 'absint', explode( ',', $post_terms ) );
		}
		if ( 1 < count( $post_terms ) ) {
			$term_args['include'] = $post_terms;
			$term_args['orderby'] = 'include';
		} else {
			$term_args['parent'] = count( $post_terms ) ? $post_terms[0] : 0;
		}
	}

	$filter_terms = get_terms( $term_args );

	if ( is_array( $filter_terms ) && count( $filter_terms ) > 1 ) {
		$slugs         = array();
		$category_html = '';
		$idx           = 0;
		$active_cat    = '';
		if ( ! empty( $atts['cats'] ) ) {
			$active_cat = sanitize_text_field( $atts['cats'] );
		} elseif ( ! empty( $_REQUEST['product_cat'] ) ) {
			$active_cat = sanitize_text_field( $_REQUEST['product_cat'] );
		} elseif ( is_archive() ) {
			$current_term = get_queried_object();
			if ( $current_term && isset( $current_term->slug ) ) {
				$active_cat = $current_term->slug;
			}
		}

		foreach ( $filter_terms as $term_cat ) {
			$id             = $term_cat->term_id;
			$name           = $term_cat->name;
			$slug           = $term_cat->slug;
			$slugs[]        = $slug;
			$category_html .= '<li><a href="' . esc_url( get_term_link( $term_cat ) ) . '" class="nav-filter' . ( ( 0 == $idx && 'yes' != $show_all_filter && ! $active_cat ) || ( $active_cat == $slug ) ? ' active' : '' ) . '" data-cat="' . esc_attr( $slug ) . '">' . esc_html( $name ) . '</a></li>';
			++ $idx;
		}

		if ( $category_html ) {
			$all_link = get_post_type_archive_link( $post_type );
			if ( ! $all_link && $post_type ) {
				$all_link = site_url() . '?post_type=' . $post_type;
			}
			$category_html = '<ul class="nav-filters post-filters">' . ( 'yes' == $show_all_filter ? '<li class="nav-filter-clean"><a href="' . esc_url( $all_link ) . '" class="nav-filter' . ( ! $active_cat ? ' active' : '' ) . '" data-cat="*">' . esc_html__( 'All', 'alpha-core' ) . '</a></li>' : '' ) . $category_html . '</ul>';

			$props['filter_cat'] = true;
			$is_filter_cat       = true;
		}
	}
}

if ( empty( $shortcode_type ) ) {
	if ( 'terms' == $source ) { // terms
		if ( $tax ) {
			$args = array(
				'taxonomy'   => sanitize_text_field( $tax ),
				'hide_empty' => empty( $hide_empty ) ? false : true,
			);
			if ( $count || '0' == $count ) {
				$args['number'] = (int) $count;
			}
			if ( $orderby_term ) {
				$args['orderby'] = sanitize_text_field( $orderby_term );
			}
			if ( $orderway ) {
				$args['order'] = sanitize_text_field( $orderway );
			}
			if ( ! empty( $terms ) ) {
				if ( ! is_array( $terms ) ) {
					$terms = explode( ',', $terms );
				}
				$args['orderby'] = 'include';
				$args['include'] = array_map( 'absint', $terms );
			}
			$posts = get_terms( $args );
		}
	} elseif ( empty( $source ) ) { // posts
		$args = array(
			'post_status' => 'publish',
		);
		if ( $post_type ) {
			$args['post_type'] = sanitize_text_field( $post_type );
		}

		// if ( is_front_page() ) {
		// 	$paged = get_query_var( 'page' );
		// } else {
		// 	$paged = get_query_var( 'paged' );
		// }
		// if ( $paged ) {
		// 	$args['paged'] = (int) $paged;
		// }

		if ( $count || '0' == $count ) {
			$args['posts_per_page'] = (int) $count;
		}

		if ( ! $post_tax && ! empty( $post_terms ) ) {
			if ( ! is_array( $post_terms ) ) {
				$post_terms = explode( ',', $post_terms );
			}
			$term = get_term( trim( $post_terms[0] ) );
			if ( $term && ! is_wp_error( $term ) ) {
				$post_tax = $term->taxonomy;
			} else {
				$post_terms = '';
			}
		}

		// query args to filter by category
		if ( $filter_cat_tax && ! empty( $atts['cats'] ) ) {
			$filter_cat_terms = $atts['cats'];
			if ( ! is_array( $filter_cat_terms ) ) {
				$filter_cat_terms = explode( ',', $filter_cat_terms );
			}
			$args['tax_query'] = array(
				array(
					'taxonomy' => sanitize_text_field( $filter_cat_tax ),
					'field'    => is_numeric( $filter_cat_terms[0] ) ? 'term_id' : 'slug',
					'terms'    => array_map( 'sanitize_text_field', $filter_cat_terms ),
				),
			);
		}

		if ( ! empty( $post_terms ) ) {
			if ( ! is_array( $post_terms ) ) {
				$post_terms = explode( ',', $post_terms );
			}
			$tax_name = $post_tax;
			if ( $tax_name ) {
				if ( ! isset( $args['tax_query'] ) ) {
					$args['tax_query'] = array();
				}
				$args['tax_query'][] = array(
					'taxonomy' => sanitize_text_field( $tax_name ),
					'field'    => is_numeric( $post_terms[0] ) ? 'term_id' : 'slug',
					'terms'    => array_map( 'sanitize_text_field', $post_terms ),
				);
			}
		}

		// update orderby and order for products
		if ( 'product' == $post_type && class_exists( 'WooCommerce' ) ) {
			$ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $orderway );
			$orderby       = $ordering_args['orderby'];
			$orderway      = $ordering_args['order'];

			if ( 'viewed' == $product_status ) {
				$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
				$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
				if ( empty( $viewed_products ) ) {
					return;
				}
				if ( is_array( $viewed_products ) ) {
					$atts['ids'] = implode( ',', $viewed_products );
				}
			} elseif ( 'on_sale' == $product_status ) {
				$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			} elseif ( 'featured' == $product_status ) {
				if ( ! isset( $args['tax_query'] ) ) {
					$args['tax_query'] = array();
				}
				$args['tax_query'] = array_merge( $args['tax_query'], WC()->query->get_tax_query() ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

				$args['tax_query'][] = array(
					'taxonomy'         => 'product_visibility',
					'terms'            => 'featured',
					'field'            => 'name',
					'operator'         => 'IN',
					'include_children' => false,
				);
			}
		}

		if ( $orderby ) {
			$args['orderby'] = sanitize_text_field( $orderby );
		}
		if ( $orderway ) {
			$args['order'] = sanitize_text_field( $orderway );
		}
		if ( ! empty( $atts['ids'] ) && is_array( $atts['ids'] ) ) {
			$args['post__in'] = array_map( 'sanitize_text_field', $atts['ids'] );
			$args['orderby']  = 'post__in';
		}

		if ( $is_related ) {
			$args['post__not_in']  = array( $is_related );
			$args['category__in']  = wp_get_post_categories( $is_related );
			$args['no_found_rows'] = true;
		}
		// show first filter
		if ( 'yes' != $show_all_filter && ! empty( $filter_terms ) ) {
			foreach ( $filter_terms as $term_cat ) {
				if ( isset( $term_cat->term_taxonomy_id ) ) {
					$args['category'] = $term_cat->term_taxonomy_id;
				}
				break;
			}
		}
		$posts_query = new WP_Query( $args );

		if ( 'product' == $post_type && class_exists( 'WooCommerce' ) ) {
			WC()->query->remove_ordering_args();
		}
	}
} elseif ( 'archive' == $shortcode_type ) { // shop builder
	global $wp_query;
	$posts_query = $wp_query;
}

$should_render_wrapper = 'archive' == $shortcode_type || ( empty( $source ) && $posts_query->have_posts() ) || ! empty( $category_html ) || ( 'terms' == $source && ! empty( $posts ) );

if ( $should_render_wrapper ) {
	// enqueue style
	if ( empty( $source ) && $is_filter_cat ) {
		wp_enqueue_style( 'alpha-tab', alpha_core_framework_uri( '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
	}

	$wrapper_id = 'alpha-posts-grid-' . rand( 1000, 9999 );
	$wrap_class = 'alpha-posts-grid';
	if ( 'yes' == $hover_full_image ) {
		$wrap_class .= ' featured-hover-full-image';
	}
	$container_class = 'alpha-posts-grid-container';
	$container_attrs = '';

	// grid / list toggle classes
	if ( ! empty( $toggle_cols ) ) {
		$container_attrs .= ' data-toggle_cls="' . esc_attr( trim( alpha_get_col_class( alpha_elementor_grid_col_cnt( $toggle_cols ) ) ) ) . '"';
	}

	$col_cnt          = alpha_elementor_grid_col_cnt( $atts );
	$props['col_cnt'] = $col_cnt;
	$grid_space_class = alpha_get_grid_space_class( $atts );
	if ( $grid_space_class ) {
		$container_class .= $grid_space_class;
	}

	if ( 'archive' == $shortcode_type ) { // shop builder
		if ( 'product' == $post_type ) {
			$wrap_class .= ' archive-products';
			if ( ! $builder_post ) {
				$container_class .= ' shop-default';
			}
		} else {
			$wrap_class .= ' archive-posts';
		}
	}

	if ( $posts_wrap_cls ) {
		$container_class .= ' ' . trim( $posts_wrap_cls );
	}

	echo '<div id="' . esc_attr( $wrapper_id ) . '" class="' . esc_attr( $wrap_class ) . '">';

	if ( $builder_post ) {
		$css  = get_post_meta( $builder_id, ALPHA_NAME . '_blocks_style_options_css', true );
		$css .= get_post_meta( $builder_id, '_' . ALPHA_NAME . '_builder_css', true );
		$css .= get_post_meta( $builder_id, 'page_css', true );

		if ( $list_builder_id ) {
			$css .= get_post_meta( $list_builder_id, ALPHA_NAME . '_blocks_style_options_css', true );
			$css .= get_post_meta( $list_builder_id, '_' . ALPHA_NAME . '_builder_css', true );
			$css .= get_post_meta( $list_builder_id, 'page_css', true );
		}
		if ( $css ) {
			if ( alpha_is_elementor_preview() ) {
				echo '<style scope="scope">';
				echo wp_strip_all_tags( $css );
				echo '</style>';
			} else {
				if ( alpha_doing_ajax() ) {
					echo '<style id="block_' . (int) $builder_id . '_css">';
					echo wp_strip_all_tags( $css );
					echo '</style>';
				} else {
					wp_add_inline_style( 'alpha-style', '/* Post Type Builder CSS */' . PHP_EOL . wp_strip_all_tags( $css ) );
				}
			}
		}

		$page_js = get_post_meta( $builder_id, 'page_js', true );
		if ( $page_js ) {
			if ( alpha_is_elementor_preview() ) {
				echo '<script>';
				echo alpha_strip_script_tags( $page_js );
				echo '</script>';
			} else {
				if ( alpha_doing_ajax() ) {
					echo '<script id="block_' . (int) $builder_id . '_js">';
					echo wp_strip_all_tags( $page_js );
					echo '</script>';
				} else {
					wp_add_inline_script( 'alpha-theme', '/* Post Type Builder JS */' . PHP_EOL . alpha_strip_script_tags( $page_js ) );
				}
			}
		}
	}

	// view
	if ( 'creative' == $view ) {
		$post_count = 0;

		$container_class .= ' creative-grid row';
		if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
			$container_class .= ' editor-mode';
		}
		if ( isset( $atts['creative_mode'] ) ) {
			$container_class       .= 'preset-grid grid-layout-' . $atts['creative_mode'];
			$props['creative_mode'] = $atts['creative_mode'];
		}

		if ( is_array( $items_list ) ) {
			$props['repeaters'] = array(
				'ids'    => array(),
				'images' => array(),
			);
			foreach ( $items_list as $item ) {
				if ( ! isset( $props['repeaters']['ids'][ (int) $item['item_no'] ] ) ) {
					$props['repeaters']['ids'][ (int) $item['item_no'] ] = '';
				}
				$props['repeaters']['ids'][ (int) $item['item_no'] ]   .= ' elementor-repeater-item-' . $item['_id'];
				$props['repeaters']['images'][ (int) $item['item_no'] ] = $item['item_thumb_size'];
			}
		}
	} elseif ( 'slider' == $view ) {
		$container_class .= ' ' . alpha_get_slider_class( $atts );
		$container_attrs .= ' data-slider-options="' . esc_attr(
			json_encode(
				alpha_get_slider_attrs( $atts, $col_cnt )
			)
		) . '"';
	} elseif ( 'masonry' == $view ) {
		wp_enqueue_script( 'isotope-pkgd' );

		$container_class .= ' grid masonry';
		$container_attrs .= " data-creative-breaks='" . json_encode(
			array(
				'md' => alpha_get_breakpoints( 'md' ),
				'lg' => alpha_get_breakpoints( 'lg' ),
			)
		) . "'";
		$container_attrs .= ' data-grid-options="' . esc_attr(
			json_encode(
				array(
					'itemSelector' => '.alpha-tb-item',
					'layoutMode'   => 'masonry',
					'isOriginLeft' => ! is_rtl() ? true : false,
				)
			)
		) . '"';
	}

	if ( $thumbnail_size ) {
		$GLOBALS['alpha_post_image_size'] = $thumbnail_size;
	}

	if ( 'yes' == $hover_full_image ) {
		$GLOBALS['alpha_full_image_size'] = 'full';
	}

	if ( ! empty( $category_html ) ) {
		echo apply_filters( 'alpha_products_filter_cat_html', $category_html );
	}

	// Load More Properties
	if ( empty( $source ) ) {
		$atts['shortcode']       = 'alpha-posts-grid';
		$props['loadmore_props'] = shortcode_atts(
			$default_atts,
			$atts
		);
		if ( isset( $args ) ) {
			$props['loadmore_args'] = $args;
		}
		$props['posts']          = $posts_query;
		$props['loadmore_type']  = $loadmore_type;
		$props['loadmore_label'] = $loadmore_label;
		$props['is_filter_cat']  = $is_filter_cat;
	}


	$props['wrapper_class'] = explode( ' ', $container_class );
	$props['wrapper_attrs'] = $container_attrs;

	// Tooltip to edit
	$edit_link      = '';
	$edit_link_html = '';
	if ( $builder_post && current_user_can( 'edit_pages' ) && ! is_customize_preview() &&
		( ! function_exists( 'alpha_is_elementor_preview' ) || ! alpha_is_elementor_preview() ) &&
		( ! function_exists( 'alpha_is_wpb_preview' ) || ! alpha_is_wpb_preview() ) &&
		apply_filters( 'alpha_show_templates_edit_link', true ) ) {
		$edit_link    = admin_url( 'post.php?post=' . absint( $builder_id ) . '&action=edit' );
		$builder_type = get_post_meta( $builder_id, ALPHA_NAME . '_template_type', true );
		if ( ! $builder_type ) {
			$builder_type = esc_html__( 'Template', 'alpha-core' );
		}
	}

	if ( $edit_link && $builder_post ) {
		/* translators: template name */
		$edit_link_html = '<div class="alpha-edit-link d-none" data-title="' . sprintf( esc_attr__( 'Edit %1$s: %2$s', 'alpha-core' ), esc_attr( str_replace( '_', ' ', $builder_type ) ), esc_attr( get_the_title( $builder_id ) ) ) . '" data-link="' . esc_url( $edit_link ) . '"></div>';
	}

	/**
	 * Fires before archive posts widget render.
	 *
	 * @since 1.0
	 */
	do_action( 'alpha_before_posts_loop', $props );

	if ( 'slider' == $view && $edit_link_html ) {
		echo alpha_strip_script_tags( $edit_link_html );
	}

	alpha_get_template_part( 'posts/post', 'loop-start' );


	// nothing found
	if ( isset( $posts_query ) && ! $posts_query->have_posts() && ! empty( $post_found_nothing ) && 'archive' == $shortcode_type ) {
		echo '<div class="nothing-found-message w-100">' . alpha_strip_script_tags( $post_found_nothing ) . '</div>';
	}

	$original_query          = $GLOBALS['wp_query'];
	$original_queried_object = $GLOBALS['wp_query']->queried_object;
	if ( empty( $source ) && $posts_query->have_posts() ) { // posts

		wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

		$original_post = $GLOBALS['post'];
		if ( 'product' == $post_type && ! class_exists( 'WooCommerce' ) ) {
			$post_type = 'post';
		}

		if ( 'product' == $post_type ) {
			if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'compare_available' ) ) {
				wp_enqueue_style( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_VERSION );
				wp_enqueue_script( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_VERSION, true );
			}
			if ( function_exists( 'alpha_quickview_add_scripts' ) ) {
				alpha_quickview_add_scripts();
				wp_enqueue_style( 'alpha-share', alpha_core_framework_uri( '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}
			wp_enqueue_style( 'alpha-product', alpha_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			if ( defined( 'ALPHA_VERSION' ) ) {
				wp_enqueue_style( 'alpha-theme-single-product', ALPHA_ASSETS . '/css/pages/single-product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_VERSION );
			}
			wp_enqueue_script( 'alpha-woocommerce' );

			if ( isset( $GLOBALS['product'] ) ) {
				$original_product = $GLOBALS['product'];
			}

			$GLOBALS['alpha_tb_catalog_mode'] = false;
			if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'catalog_mode' ) ) {
				$GLOBALS['alpha_tb_catalog_mode'] = true;
			}

			if ( ! $builder_post ) {
				wc_set_loop_prop( 'alpha_print_content_only', true );
				if ( 'archive' == $shortcode_type && isset( $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) && 'list' == $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) {
					wc_set_loop_prop( 'product_type', 'list' );

				} else {
					wc_set_loop_prop( 'product_type', alpha_get_option( 'product_type' ) );
				}
			}
			if ( $thumbnail_size ) {
				wc_set_loop_prop( 'thumbnail_size', $thumbnail_size );
			}

			// display product categories
			if ( 'archive' == $shortcode_type ) {
				$show_info      = alpha_wc_category_show_info();
				$category_class = array( alpha_get_category_classes() );
				wc_set_loop_prop( 'show_link', 'yes' == $show_info['link'] );
				wc_set_loop_prop( 'show_count', 'yes' == $show_info['count'] );
				wc_set_loop_prop( 'category_class', $category_class );
				$categories_html = woocommerce_maybe_show_product_subcategories();
				if ( $categories_html ) {
					wp_enqueue_style( 'alpha-product-category', alpha_core_framework_uri( '/widgets/categories/category' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
					echo apply_filters( 'alpha_posts_grid_product_subcategories_html', str_replace( array( '<li class="category-wrap', '</li>' ), array( '<div class="category-wrap', '</div>' ), $categories_html ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

				global $wp_query;
				$posts_query = $wp_query;
			}
		} elseif ( ALPHA_NAME . '_member' == $post_type ) {
			if ( ! $builder_post ) {
				wp_enqueue_style( 'alpha-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/member.min.css', array( 'alpha-post', 'alpha-share' ), ALPHA_CORE_VERSION );
			}
		} elseif ( ALPHA_NAME . '_portfolio' == $post_type ) {
			if ( ! $builder_post ) {
				wp_enqueue_style( 'alpha-portfolio', ALPHA_CORE_INC_URI . '/cpt/post_types/portfolio/assets/portfolio' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array( 'alpha-post' ), ALPHA_CORE_VERSION );
			}
		} else {
			wp_enqueue_style( 'alpha-post' );
		}

		if ( ! $builder_post && 'product' == $post_type ) {
			/**
			 * Hook: alpha_before_shop_loop_start.
			 *
			 * @hooked alpha_before_shop_loop_start - 10
			 */
			do_action( 'alpha_before_shop_loop_start', 'post-grid' );
		}

		while ( $posts_query->have_posts() ) {
			$posts_query->the_post();
			global $post;
			$GLOBALS['wp_query']->queried_object = $post;

			$item_cls = 'alpha-tb-item';

			$item_attrs = '';

			if ( $post_type ) {
				$item_cls .= ' ' . $post_type;
			}

			if ( 'product' == $post_type ) {
				$GLOBALS['product'] = wc_get_product( $post->ID );
				if ( ! $GLOBALS['product'] || ! $GLOBALS['product']->is_visible() ) {
					continue;
				}
				$item_cls .= ' product-wrap';

				// add product attributes to be used in add to cart popup
				$item_attrs .= ' data-title="' . esc_attr( get_the_title() ) . '" data-link="' . esc_url( get_permalink() ) . '"';
			}

			if ( 'creative' == $view && isset( $post_count ) ) {
				$post_count++;
				$repeaters = alpha_get_loop_prop( 'repeaters' );
				$item_cls .= ' grid-item';
				if ( ! empty( $repeaters ) ) {
					if ( isset( $repeaters['ids'][ $post_count ] ) ) {
						$item_cls .= $repeaters['ids'][ $post_count ];
					}
					if ( isset( $repeaters['ids'][0] ) ) {
						$item_cls .= $repeaters['ids'][0];
					}
					if ( isset( $repeaters['images'][ $post_count ] ) ) {
						$GLOBALS['alpha_post_image_size'] = $repeaters['images'][ $post_count ];
					}
				}
				$item_attrs = ' data-grid-idx="' . (int) $post_count . '"';
			}

			if ( $edit_link && $builder_post ) {
				$item_cls .= ' alpha-block';
				$edit_link = '';
				if ( 'slider' != $view ) {
					echo alpha_strip_script_tags( $edit_link_html );
				}
			}

			echo '<div ';
			if ( ! $builder_post && 'product' == $post_type ) {
				echo 'class="' . esc_attr( $item_cls ) . '"';
			} else {
				post_class( $item_cls );
			}
			echo '' . $item_attrs . '>';

			if ( $builder_post || 'product' != $post_type ) {
				/**
				 * Fires before rendering post loop item.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_post_loop_before_item' );
			}

			if ( $builder_post ) {
				echo do_blocks( $builder_post->post_content );
			} else {
				if ( 'product' == $post_type ) {
					wc_get_template_part( 'content', 'product' );
				} elseif ( 'post' == $post_type || false !== strpos( $post_type, ALPHA_NAME ) ) {
					alpha_get_template_part( 'posts/post', '', array( 'shortcode_type' => $shortcode_type ) );
				} else {
					alpha_get_template_part( 'posts/type/post', 'default' );
				}
			}

			if ( $builder_post || 'product' != $post_type ) {
				/**
				 * Fires after rendering post loop item.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_post_loop_after_item' );
			}

			do_action( 'alpha_posts_grid_item_rendered', $builder_post, $atts );
			echo '</div>';
		}

		if ( ! $builder_post && 'product' == $post_type ) {
			/**
			 * Hook: alpha_after_shop_loop_end.
			 *
			 * @hooked vendor_store_tab_end - 10
			 */
			do_action( 'alpha_after_shop_loop_end', 'post-grid' );
		}

		wp_reset_postdata();

		// Restore global data.
		$GLOBALS['post'] = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		if ( 'product' == $post_type ) {
			if ( isset( $original_product ) ) {
				$GLOBALS['product'] = $original_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}
	} elseif ( 'terms' == $source && ! empty( $posts ) ) { // terms
		global $alpha_page_layout;
		$alpha_page_layout = alpha_get_page_layout();

		$original_is_tax     = $GLOBALS['wp_query']->is_tax;
		$original_is_archive = $GLOBALS['wp_query']->is_archive;

		$GLOBALS['wp_query']->is_tax     = true;
		$GLOBALS['wp_query']->is_archive = true;

		if ( ! $builder_post ) {
			wp_enqueue_style( 'alpha-product-category', alpha_core_framework_uri( '/widgets/categories/category' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			if ( 'product_cat' == $tax ) {
				if ( class_exists( 'WooCommerce' ) ) {
					wc_set_loop_prop( 'category_type', alpha_get_option( 'category_type' ) );
					$show_info      = alpha_wc_category_show_info( alpha_get_option( 'category_type' ) );
					$category_class = array( alpha_get_category_classes( alpha_get_option( 'category_type' ) ) );
					wc_set_loop_prop( 'show_link', 'yes' == $show_info['link'] );
					wc_set_loop_prop( 'show_count', 'yes' == $show_info['count'] );
					wc_set_loop_prop( 'category_class', $category_class );
				} else {
					$tax = '';
				}
			}
		}

		foreach ( $posts as $term ) {
			$GLOBALS['wp_query']->queried_object = $term;

			$item_cls   = 'alpha-tb-item';
			$item_attrs = '';
			if ( 'creative' == $view && isset( $post_count ) ) {
				$post_count++;
				$repeaters = alpha_get_loop_prop( 'repeaters' );
				$item_cls .= ' grid-item';
				if ( ! empty( $repeaters ) ) {
					if ( isset( $repeaters['ids'][ $post_count ] ) ) {
						$item_cls .= $repeaters['ids'][ $post_count ];
					}
					if ( isset( $repeaters['ids'][0] ) ) {
						$item_cls .= $repeaters['ids'][0];
					}
					if ( isset( $repeaters['images'][ $post_count ] ) ) {
						$GLOBALS['alpha_post_image_size'] = $repeaters['images'][ $post_count ];
					}
				}
				$item_attrs = ' data-grid-idx="' . (int) $post_count . '"';
			}

			if ( $edit_link && $builder_post ) {
				$item_cls .= ' alpha-block';
				$edit_link = '';
				if ( 'slider' != $view ) {
					echo alpha_strip_script_tags( $edit_link_html );
				}
			}

			echo '<div class="' . esc_attr( $item_cls ) . '"' . $item_attrs . '>';

			/**
			 * Fires before rendering post loop item.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_post_loop_before_item' );

			if ( $builder_post ) {
				echo do_blocks( $builder_post->post_content );
			} elseif ( 'product_cat' == $tax ) {
				$category = $term;

				wc_get_template(
					'content-product-cat.php',
					array(
						'category' => $category,
						'html_tag' => 'div',
					)
				);
			} else {
				alpha_get_template_part( 'posts/type/category', 'default' );
			}

			/**
			 * Fires after rendering post loop item.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_post_loop_after_item' );

			do_action( 'alpha_posts_grid_item_rendered', $builder_post, $atts );
			echo '</div>';
		}

		$GLOBALS['wp_query']                 = $original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['wp_query']->queried_object = $original_queried_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['wp_query']->is_tax         = $original_is_tax; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['wp_query']->is_archive     = $original_is_archive; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$alpha_page_layout = '';
		unset( $GLOBALS['alpha_page_layout'] );
	}
	$GLOBALS['wp_query']                 = $original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$GLOBALS['wp_query']->queried_object = $original_queried_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

	unset( $GLOBALS['alpha_post_image_size'], $GLOBALS['woocommerce_loop'] );

	// set posts layout to grid to display pagination
	if ( 'creative' == $view || 'masonry' == $view ) {
		alpha_set_loop_prop( 'posts_layout', 'grid' );
	}
	alpha_get_template_part( 'posts/post', 'loop-end' );

	/**
	 * Fires after archive posts widget render.
	 *
	 * @since 1.0
	 */
	do_action( 'alpha_after_posts_loop' );

	if ( 'yes' == $hover_full_image ) {
		global $hover_full_images;
		echo alpha_strip_script_tags( $hover_full_images );
		unset( $GLOBALS['alpha_full_image_size'] );
		unset( $GLOBALS['hover_full_images'] );
	}
	echo '</div>';
}
