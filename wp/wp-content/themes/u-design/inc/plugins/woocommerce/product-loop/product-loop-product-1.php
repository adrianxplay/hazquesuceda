<?php
/**
 * The configuration for product type 1
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

// Before Loop Start
add_action( 'alpha_before_shop_loop_start', 'alpha_product_1_before_shop_loop_start' );

/**
 * Before loop start.
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_1_before_shop_loop_start' ) ) {
	function alpha_product_1_before_shop_loop_start() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'product-1' == $product_type ) {
			$addtocart_pos = '';
			$quickview_pos = 'bottom';
			$wishlist_pos  = '';
			$content_align = 'center';
			wc_set_loop_prop( 'addtocart_pos', $addtocart_pos );
			wc_set_loop_prop( 'quickview_pos', $quickview_pos );
			wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
			alpha_wc_get_loop_prop( 'content_align' ) || wc_set_loop_prop( 'content_align', $content_align );
		}
	}
}
