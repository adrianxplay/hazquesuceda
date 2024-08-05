<?php

/**
 * Extend WooCommerce Functions
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.1
 */

add_filter( 'woocommerce_shortcode_products_query', 'alpha_woo_products_in_custom_tax', 20, 2 );

if ( ! function_exists( 'alpha_woo_products_in_custom_tax' ) ) {
	/**
	 * Products in specified brands
	 */
	function alpha_woo_products_in_custom_tax( $args, $attributes ) {
		if ( ! empty( $attributes['class'] ) ) {
			$classes = explode( ',', $attributes['class'] );

			if ( ! in_array( 'custom_brands', $classes ) ) {
				return $args;
			}

			$args['tax_query'][] = array(
				'taxonomy' => 'product_brand',
				'terms'    => array_map( 'sanitize_title', $classes ),
				'field'    => 'slug',
				'operator' => 'IN',
			);

		}

		return $args;
	}
}

if ( ! function_exists( 'alpha_before_shop_loop_start' ) ) {
	/**
	 * Before shop loop start.
	 *
	 * @since 1.2.0
	 */
	function alpha_before_shop_loop_start( $src ) {
		wp_enqueue_script( 'alpha-woocommerce' );

		if ( 'post-grid' == $src ) {
			wc_set_loop_prop( 'widget', 'post-grid' );
		}
	}
}
