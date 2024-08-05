<?php
/**
 * The configuration for product widget
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

add_action( 'woocommerce_before_shop_loop_item_title', 'alpha_product_widget_loop_vertical_action', 20 ); // Vertical action
// add_action( 'woocommerce_after_shop_loop_item_title', 'alpha_product_widget_loop_action', 30 );
add_action( 'alpha_before_shop_loop_start', 'alpha_product_widget_before_shop_loop_start' );
add_action( 'woocommerce_after_shop_loop_item_title', 'alpha_product_widget_loop_rating', 12 );

/**
 * The vertical action
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_widget_loop_vertical_action' ) ) {
	function alpha_product_widget_loop_vertical_action() {
		// if product type is not default, do not print vertical action buttons.
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'widget' == $product_type ) {
			return;
		}
	}
}

/**
 * The loop action
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_widget_loop_action' ) ) {
	function alpha_product_widget_loop_action() {
		global $product;
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'widget' == $product_type ) {
			woocommerce_template_loop_add_to_cart(
				array(
					'class' => implode(
						' ',
						array_filter(
							array(
								'btn-product',
								'product_type_' . $product->get_type(),
								$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
								$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
							)
						)
					),
				)
			);
		}
	}
}

/**
 * The loop rating
 */
if ( ! function_exists( 'alpha_product_widget_loop_rating' ) ) {
	function alpha_product_widget_loop_rating() {
		wc_get_template(
			'loop/rating.php',
			array(
				'is_widget_type' => true,
			)
		);
	}
}

/**
 * Before loop start
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_widget_before_shop_loop_start' ) ) {
	function alpha_product_widget_before_shop_loop_start() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'widget' == $product_type ) {
			$addtocart_pos = '';
			$quickview_pos = '';
			$wishlist_pos  = '';
			wc_set_loop_prop( 'addtocart_pos', $addtocart_pos );
			wc_set_loop_prop( 'quickview_pos', $quickview_pos );
			wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
			wc_set_loop_prop( 'show_info', array( 'label', 'price' ) );
		}
	}
}
