<?php
/**
 * The configuration for product type 3
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

add_action( 'woocommerce_after_shop_loop_item_title', 'alpha_product_3_hd_wrapper_start', 15 );
add_action( 'woocommerce_after_shop_loop_item_title', 'alpha_product_3_hd_wrapper_end', 50 );
add_action( 'alpha_before_shop_loop_start', 'alpha_product_3_before_shop_loop_start' );

/**
 * Hide details wrapper start
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_3_hd_wrapper_start' ) ) {
	function alpha_product_3_hd_wrapper_start() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'product-3' == $product_type ) {
			echo '<div class="product-hide-details">';
		}
	}
}

/**
 * Hide details wrapper end
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_3_hd_wrapper_end' ) ) {
	function alpha_product_3_hd_wrapper_end() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'product-3' == $product_type ) {
			echo '</div>';
		}
	}
}

/**
 * Before loop start
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_3_before_shop_loop_start' ) ) {
	function alpha_product_3_before_shop_loop_start() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'product-3' == $product_type ) {
			$addtocart_pos = '';
			$quickview_pos = '';
			$wishlist_pos  = '';
			$content_align = 'center';
			alpha_wc_get_loop_prop( 'content_align' ) || wc_set_loop_prop( 'content_align', $content_align );
			wc_set_loop_prop( 'addtocart_pos', $addtocart_pos );
			wc_set_loop_prop( 'quickview_pos', $quickview_pos );
			wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
		}
	}
}
