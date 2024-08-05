<?php
/**
 * The configuration for product type 4
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

add_action( 'alpha_before_shop_loop_start', 'alpha_product_4_before_shop_loop_start' );

/**
 * Before loop start
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_4_before_shop_loop_start' ) ) {
	function alpha_product_4_before_shop_loop_start() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'product-4' == $product_type ) {
			$addtocart_pos = '';
			$quickview_pos = '';
			$wishlist_pos  = '';
			wc_set_loop_prop( 'addtocart_pos', $addtocart_pos );
			wc_set_loop_prop( 'quickview_pos', $quickview_pos );
			wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
		}
	}
}
