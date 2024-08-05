<?php
/**
 * The configuration for product type list
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

add_action( 'woocommerce_before_shop_loop_item_title', 'alpha_product_list_loop_vertical_action', 20 ); // Vertical action
add_action( 'woocommerce_after_shop_loop_item_title', 'alpha_product_list_loop_action', 30 );
add_action( 'woocommerce_after_shop_loop_item_title', 'alpha_product_list_loop_description', 25 );
add_action( 'alpha_before_shop_loop_start', 'alpha_product_list_before_shop_loop_start' );

/**
 * The vertical action
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_list_loop_vertical_action' ) ) {
	function alpha_product_list_loop_vertical_action() {
		// if product type is not default, do not print vertical action buttons.
		global $product;
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'list' == $product_type ) {
			$html      = '';
			$show_info = alpha_wc_get_loop_prop( 'show_info', false );
			if ( ( ! is_array( $show_info ) || in_array( 'quickview', $show_info ) ) &&
				'' == alpha_wc_get_loop_prop( 'quickview_pos' ) ) {
				$html .= '<button class="btn-product-icon btn-quickview" data-mfp-src="' . esc_url( alpha_get_product_featured_image_src( $product ) ) . '" data-product="' . $product->get_id() . '" title="' . esc_html__( 'Quick View', 'alpha' ) . '">' . esc_html__( 'Quick View', 'alpha' ) . '</button>';
			}
			if ( $html ) {
				echo '<div class="product-action-vertical">' . alpha_escaped( $html ) . '</div>';
			}
		}
	}
}

/**
 *The product list loop action
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_list_loop_action' ) ) {
	function alpha_product_list_loop_action() {
		global $product;
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'list' == $product_type ) {
			$content_align = alpha_wc_get_loop_prop( 'content_align' );
			$show_info     = alpha_wc_get_loop_prop( 'show_info', false );

			if ( defined( 'YITH_WCWL' ) && ( ! is_array( $show_info ) || in_array( 'wishlist', $show_info ) ) ) {
				$wishlist = do_shortcode( '[yith_wcwl_add_to_wishlist container_classes="btn-product-icon"]' );
			} else {
				$wishlist = '';
			}

			if ( alpha_get_option( 'compare_available' ) && ( ! is_array( $show_info ) || in_array( 'compare', $show_info ) ) ) {
				ob_start();
				alpha_product_compare( ' btn-product-icon' );
				$compare = ob_get_clean();
			} else {
				$compare = '';
			}

			echo '<div class="product-action">';

			if ( 'center' == $content_align || ( ( ! is_rtl() && 'right' == $content_align ) || ( is_rtl() && 'left' == $content_align ) ) ) {
				echo alpha_escaped( $wishlist );
			}
			if ( ( ! is_rtl() && 'right' == $content_align ) || ( is_rtl() && 'left' == $content_align ) ) {
				echo alpha_escaped( $compare );
			}
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

			if ( ( ! is_rtl() && 'left' == $content_align ) || ( is_rtl() && 'right' == $content_align ) ) {
				echo alpha_escaped( $wishlist );
			}
			if ( ( ! is_rtl() && 'right' !== $content_align ) || ( is_rtl() && 'left' !== $content_align ) ) {
				echo alpha_escaped( $compare );
			}

			echo '</div>';
		}
	}
}

/**
 * The description
 *
 * @since 4.1
 *
 */
if ( ! function_exists( 'alpha_product_list_loop_description' ) ) {
	function alpha_product_list_loop_description() {
		$show_info = alpha_wc_get_loop_prop( 'show_info', false );
		if ( 'list' == alpha_wc_get_loop_prop( 'product_type' ) && ( ! is_array( $show_info ) || in_array( 'short_desc', $show_info ) ) ) {
			global $product;

			$excerpt_type   = alpha_get_option( 'prod_excerpt_type' );
			$excerpt_length = alpha_get_option( 'prod_excerpt_length' );
			// echo '<div class="short-desc">' . alpha_trim_description( $product->get_short_description(), 30, 'words', 'product-short-desc' ) . '</div>';
			echo '<div class="short-desc">' . alpha_trim_description( $product->get_short_description(), $excerpt_length, $excerpt_type ) . '</div>';
		}
	}
}

/**
 * Before loop start
 *
 * @since 4.1
 */
if ( ! function_exists( 'alpha_product_list_before_shop_loop_start' ) ) {
	function alpha_product_list_before_shop_loop_start() {
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		if ( 'list' == $product_type ) {
			$addtocart_pos = '';
			$quickview_pos = '';
			$wishlist_pos  = '';
			$compare_pos   = '';
			$content_align = 'left';
			alpha_wc_get_loop_prop( 'content_align' ) || wc_set_loop_prop( 'content_align', $content_align );
			wc_set_loop_prop( 'addtocart_pos', $addtocart_pos );
			wc_set_loop_prop( 'quickview_pos', $quickview_pos );
			wc_set_loop_prop( 'wishlist_pos', $wishlist_pos );
			wc_set_loop_prop( 'compare_pos', $compare_pos );
			$show_info = array( 'label', 'price', 'short_desc', 'rating', 'countdown', 'addtocart', 'quickview', 'wishlist' );
			if ( alpha_get_option( 'compare_available' ) ) {
				$show_info[] = 'compare';
			}
			wc_set_loop_prop( 'show_info', $show_info );
		}
	}
}
