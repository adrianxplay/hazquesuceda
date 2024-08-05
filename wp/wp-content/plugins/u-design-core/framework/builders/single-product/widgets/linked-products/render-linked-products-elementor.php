<?php

defined( 'ABSPATH' ) || die;

/**
 * Alpha Posts Grid Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'shortcode_type' => 'product',
			'builder_id'     => '',
			'post_type'      => '',
			'count'          => '',
			'orderby'        => '',
			'orderway'       => '',
			'view'           => 'grid',
			'thumbnail_size' => 'woocommerce_thumbnail',

			'row_cnt'        => 1,
			'col_cnt'        => 4,
			'col_sp'         => '',
			// 'creative_cols'        => '',
			// 'creative_cols_tablet' => '',
			// 'creative_cols_mobile' => '',
			'items_list'     => '',
		),
		$atts
	)
);

if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'compare_available' ) ) {
	wp_enqueue_style( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
	wp_enqueue_script( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
}
if ( function_exists( 'alpha_quickview_add_scripts' ) ) {
	alpha_quickview_add_scripts();
}
wp_enqueue_style( 'alpha-product', alpha_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
if ( defined( 'ALPHA_VERSION' ) ) {
	wp_enqueue_style( 'alpha-theme-single-product', ALPHA_ASSETS . '/css/pages/single-product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_VERSION );
}
wp_enqueue_script( 'alpha-woocommerce' );

$builder_post = false;

if ( $builder_id ) {
	$builder_post = get_post( (int) $builder_id );
}

if ( 'creative' == $view ) {
	wp_enqueue_script( 'isotope-pkgd' );
}

$props = array(
	'widget'       => true,
	'posts_layout' => $view,
	'cpt'          => 'product',
);

$args = array(
	'post_status' => 'publish',
	'post_type'   => 'product',
);
if ( $orderby ) {
	$args['orderby'] = sanitize_text_field( $orderby );
}
if ( $orderway ) {
	$args['order'] = sanitize_text_field( $orderway );
}
if ( $count && -1 !== (int) $count ) {
	$args['posts_per_page'] = (int) $count;
}
global $product;
if ( 'product' == $shortcode_type && ! empty( $product ) ) {
	if ( 'related' == $post_type ) { // Related products
		$product_ids = wc_get_related_products( $product->get_id(), $count, $product->get_upsell_ids() );
	} elseif ( 'upsell' == $post_type ) { // Upsell products
		$product_ids = $product->get_upsell_ids();
	}
	$args['post__in'] = $product_ids;
} elseif ( 'cart' == $shortcode_type ) {
	if ( 'crosssell' == $post_type && function_exists( 'alpha_is_elementor_preview' ) && ! alpha_is_elementor_preview() ) { // Cross-sells products

		$product_ids      = is_object( WC()->cart ) ? WC()->cart->get_cross_sells() : array();
		$args['post__in'] = $product_ids;
	}
}
if ( ! empty( $product_ids ) || ( empty( $product_ids ) && function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) ) {
	$posts_query = new WP_Query( $args );
	if ( $posts_query->have_posts() ) {
		$wrapper_id      = 'alpha-posts-grid-' . rand( 1000, 9999 );
		$wrap_class      = 'alpha-posts-grid';
		$container_class = 'alpha-posts-grid-container';
		$container_attrs = '';

		$col_cnt          = alpha_elementor_grid_col_cnt( $atts );
		$props['col_cnt'] = $col_cnt;
		$grid_space_class = alpha_get_grid_space_class( $atts );
		if ( $grid_space_class ) {
			$container_class .= $grid_space_class;
		}

		echo '<div id="' . esc_attr( $wrapper_id ) . '" class="' . esc_attr( $wrap_class ) . '">';

		if ( $builder_post ) {
			$css  = get_post_meta( $builder_id, ALPHA_NAME . '_blocks_style_options_css', true );
			$css .= get_post_meta( $builder_id, '_' . ALPHA_NAME . '_builder_css', true );
			$css .= get_post_meta( $builder_id, 'page_css', true );
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
					?>
					<script>
					<?php echo alpha_strip_script_tags( $page_js ); ?>
					</script>
					<?php
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

			if ( ! empty( $items_list ) && is_array( $items_list ) ) {
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
		}


		if ( $thumbnail_size ) {
			$GLOBALS['alpha_post_image_size'] = $thumbnail_size;
		}

		$props['wrapper_class'] = explode( ' ', $container_class );
		$props['wrapper_attrs'] = $container_attrs;

		// Tooltip to edit
		$edit_link = '';
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
		/**
		 * Fires before archive posts widget render.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_before_posts_loop', $props );

		alpha_get_template_part( 'posts/post', 'loop-start' );

		$original_query          = $GLOBALS['wp_query'];
		$original_queried_object = $GLOBALS['wp_query']->queried_object;

		if ( class_exists( 'Woocommerce' ) ) {
			if ( isset( $GLOBALS['product'] ) ) {
				$original_product = $GLOBALS['product'];
			}

			if ( $thumbnail_size ) {
				wc_set_loop_prop( 'thumbnail_size', $thumbnail_size );
			}
		}

		while ( $posts_query->have_posts() ) {

			$posts_query->the_post();
			global $post;
			$GLOBALS['wp_query']->queried_object = $post;

			$item_cls   = 'alpha-tb-item product product-wrap';
			$item_attrs = '';
			// Default post type
			if ( '' === $builder_id ) {
				$item_cls .= ' product-default';
			}
			$GLOBALS['product'] = wc_get_product( $post->ID );
			if ( ! $GLOBALS['product'] || ! $GLOBALS['product']->is_visible() ) {
				continue;
			}

			// add product attributes to be used in add to cart popup
			$item_attrs .= ' data-title="' . esc_attr( get_the_title() ) . '" data-link="' . esc_url( get_permalink() ) . '"';

			if ( 'creative' == $view && isset( $post_count ) ) {
				$post_count++;
				$repeaters = alpha_get_loop_prop( 'repeaters' );
				$item_cls .= 'grid-item';
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

			if ( $edit_link ) {
				/* translators: template name */
				echo '<div class="alpha-edit-link d-none" data-title="' . sprintf( esc_html__( 'Edit %1$s: %2$s', 'alpha-core' ), esc_attr( str_replace( '_', ' ', $builder_type ) ), esc_attr( get_the_title( $builder_id ) ) ) . '" data-link="' . esc_url( $edit_link ) . '"></div>';
				$item_cls .= ' alpha-block';
				$edit_link = '';
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
			} else {
				wc_get_template_part( 'content', 'product' );
			}

			/**
			 * Fires after rendering post loop item.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_post_loop_after_item' );
			echo '</div>';
		}

		wp_reset_postdata();

		if ( isset( $original_product ) ) {
			$GLOBALS['product'] = $original_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
		$GLOBALS['wp_query']                 = $original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['wp_query']->queried_object = $original_queried_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		unset( $GLOBALS['alpha_post_image_size'] );

		alpha_get_template_part( 'posts/post', 'loop-end' );

		echo '</div>';
	}
}

