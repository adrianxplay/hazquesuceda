<?php
/**
 * Alpha WooCommerce Product Single Extension
 *
 * Functions used to display single product.
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;


add_action(
	'alpha_after_framework',
	function() {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 6 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 80 );

		remove_action( 'woocommerce_single_product_summary', 'alpha_single_product_divider', 8 );
		remove_action( 'woocommerce_before_add_to_cart_quantity', 'alpha_single_product_divider', 10 );

		remove_action( 'woocommerce_single_product_summary', 'alpha_single_product_links_wrap_start', 45 );
		remove_action( 'woocommerce_single_product_summary', 'alpha_single_product_links_wrap_end', 55 );
	}
);

/**
 * get_single_product_layout
 *
 * Get single product layout type
 *
 * @return string
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_get_single_product_layout' ) ) {
	function alpha_get_single_product_layout() {
		global $alpha_layout;

		if ( alpha_doing_ajax() ) {
			$layout = '';
			if ( 'offcanvas' != alpha_get_option( 'quickview_type' ) ) {
				$layout = alpha_get_option( 'quickview_thumbs' );
			}
			if ( ! $layout ) {
				$layout = 'horizontal';
			}
		} else {
			$layout = empty( $alpha_layout['single_product_type'] ) ? 'horizontal' : $alpha_layout['single_product_type'];
		}
		return apply_filters( 'alpha_single_product_layout', $layout );
	}
}
/**
 * single_prev_next_product
 *
 * Render single product navigation.
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_single_prev_next_product' ) ) {
	function alpha_single_prev_next_product( $args ) {
		global $post, $alpha_layout;
		if ( ( 'single_product' == alpha_get_page_layout() && empty( $alpha_layout['is_page_header'] ) ) || ( isset( $post ) && ALPHA_NAME . '_template' == get_post_type() && 'product_layout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) {
			$args['wrap_before'] = '<div class="product-navigation">' . $args['wrap_before'];
			$args['wrap_after'] .= alpha_single_product_navigation() . '</div>';
		}
		return apply_filters( 'alpha_filter_single_prev_next_product', $args );
	}
}

/**
 * Alpha Single Product - Related Products Functions
 *
 * @since 4.0
 * @param array $args
 * @return array $args
 */
if ( ! function_exists( 'alpha_related_products_args' ) ) {
	function alpha_related_products_args( $args = array() ) {
		$count    = (int) alpha_get_option( 'product_related_count' ) ? (int) alpha_get_option( 'product_related_count' ) : 4;
		$orderby  = alpha_get_option( 'product_related_order' ) ? alpha_get_option( 'product_related_order' ) : '';
		$orderway = alpha_get_option( 'product_related_orderway' ) ? alpha_get_option( 'product_related_orderway' ) : 'asc';
		if ( $count ) {
			$args['posts_per_page'] = $count;
		}
		if ( $orderby ) {
			$args['orderby'] = $orderby;
		}
		if ( $orderway ) {
			$args['order'] = $orderway ? $orderway : 'desc';
		}
		return $args;
	}
}

if ( ! function_exists( 'alpha_single_product_thumbs_slider_attrs' ) ) {
	function alpha_single_product_thumbs_slider_attrs( $attr = '' ) {
		$options         = array(
			'navigation'            => true,
			'pagination'            => false,
			'spaceBetween'          => 10,
			'normalizeSlideIndex'   => false,
			'freeMode'              => true,
			'watchSlidesVisibility' => true,
			'watchSlidesProgress'   => true,
		);
		$max_breakpoints = alpha_get_breakpoints();
		if ( 'vertical' == alpha_get_single_product_layout() ) {
			$col_cnt = alpha_get_responsive_cols( array( 'lg' => 5 ) );
			foreach ( $col_cnt as $w => $c ) {
				if ( 'xl' == $w || 'lg' == $w ) {
					continue;
				}
				$options['breakpoints'][ $max_breakpoints[ $w ] ] = array(
					'slidesPerView' => $c,
					'direction'     => 'horizontal',
				);
			}
			$options['direction']                         = 'vertical';
			$options['breakpoints'][992]['slidesPerView'] = 'auto';
			$options['breakpoints'][992]['direction']     = 'vertical';
		} else {
			$col_cnt = alpha_get_responsive_cols(
				array(
					'lg' => 4,
					'sm' => 3,
				)
			);
			foreach ( $col_cnt as $w => $c ) {
				$options['breakpoints'][ $max_breakpoints[ $w ] ] = array(
					'slidesPerView' => $c,
				);
			}
		}
		/**
		 * Filters the slider attrs of single product thumbs.
		 *
		 * @since 1.0
		 */
		$options = apply_filters( 'alpha_single_product_thumbs_slider_attrs', $options );
		return $attr . ' data-slider-options="' . esc_attr( json_encode( $options ) ) . '"';
	}
}
