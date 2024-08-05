<?php
/**
 * Alpha WooCommerce Product Loop Extension
 *
 * Functions used to display product loop.
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

if ( ! function_exists( 'alpha_get_rating_link_html' ) ) {
	function alpha_get_rating_link_html( $product ) {
		return '<a href="' . esc_url( get_the_permalink( $product->get_id() ) ) . '#reviews" class="woocommerce-review-link scroll-to" rel="nofollow">(' . $product->get_review_count() . ')</a>';
	}
}

/**
 * Alpha product compare function
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_product_compare' ) ) {
	function alpha_product_compare( $extra_class = '' ) {
		if ( ! class_exists( 'Alpha_Product_Compare' ) || ! alpha_get_option( 'compare_available' ) ) {
			return;
		}

		global $product;

		$css_class  = 'compare' . $extra_class;
		$product_id = $product->get_id();
		$url        = '#';

		if ( Alpha_Product_Compare::get_instance()->is_compared_product( $product_id ) ) {
			$url         = get_permalink( wc_get_page_id( 'compare' ) );
			$css_class  .= ' added';
			$button_text = apply_filters( 'alpha_woocompare_added_label', esc_html__( 'Added', 'alpha' ) );
		} else {
			$button_text = apply_filters( 'alpha_woocompare_add_label', esc_html__( 'Compare', 'alpha' ) );
		}

		printf( '<a href="%s" class="%s" title="%s" data-product_id="%d" data-added-text="%s">%s</a>', esc_url( $url ), esc_attr( $css_class ), esc_html( $button_text ), $product_id, esc_html( apply_filters( 'alpha_woocompare_added_label', esc_html__( 'Added', 'alpha' ) ) ), esc_html( $button_text ) );
	}
}

if ( ! function_exists( 'alpha_product_loop_vertical_action' ) ) {
	function alpha_product_loop_vertical_action() {
		// if product type is not default, do not print vertical action buttons.
		global $product;
		$product_type = alpha_wc_get_loop_prop( 'product_type' );
		$exclude_type = apply_filters( 'alpha_product_loop_vertical_action', array( 'product-5', 'product-6', 'list', 'widget' ) );
		$html         = '';
		if ( ! in_array( $product_type, $exclude_type ) ) {
			$show_info = alpha_wc_get_loop_prop( 'show_info', false );

			if ( '' == alpha_wc_get_loop_prop( 'addtocart_pos' ) ) {
				ob_start();
				woocommerce_template_loop_add_to_cart(
					array(
						'class' => implode(
							' ',
							array_filter(
								array(
									'btn-product-icon',
									'product_type_' . $product->get_type(),
									$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
									$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
								)
							)
						),
					)
				);

				$html .= ob_get_clean();
			}

			if ( ( ! is_array( $show_info ) || in_array( 'wishlist', $show_info ) ) &&
				'' == alpha_wc_get_loop_prop( 'wishlist_pos' ) && defined( 'YITH_WCWL' ) ) {
				$html .= do_shortcode( '[yith_wcwl_add_to_wishlist container_classes="btn-product-icon"]' );
			}

			if ( alpha_get_option( 'compare_available' ) && ( ! isset( $show_info ) || ! is_array( $show_info ) || in_array( 'compare', $show_info ) ) ) {
				ob_start();
				alpha_product_compare( ' btn-product-icon' );
				$html .= ob_get_clean();
			}
			if ( ( ! is_array( $show_info ) || in_array( 'quickview', $show_info ) ) &&
			'' == alpha_wc_get_loop_prop( 'quickview_pos' ) ) {
				$html .= '<button class="btn-product-icon btn-quickview" data-product="' . $product->get_id() . '" title="' . esc_html__( 'Quick View', 'alpha' ) . '">' . esc_html__( 'Quick View', 'alpha' ) . '</button>';
			}
		}
		if ( in_array( $product_type, array( 'product-5', 'product-6' ) ) ) {
			if ( alpha_get_option( 'compare_available' ) && ( ! isset( $show_info ) || ! is_array( $show_info ) || in_array( 'compare', $show_info ) ) ) {
				ob_start();
				alpha_product_compare( ' btn-product-icon' );
				$html .= ob_get_clean();
			}
		}

		if ( $html ) {
			echo '<div class="product-action-vertical">' . alpha_escaped( $html ) . '</div>';
		}
	}
}

/**
 * The shop loop item categories.
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_shop_loop_item_categories' ) ) {
	function alpha_shop_loop_item_categories() {
		$show_info = alpha_wc_get_loop_prop( 'show_info', false );
		$name      = alpha_wc_get_loop_prop( 'name' );

		if ( is_array( $show_info ) && in_array( 'category', $show_info ) && ( 'related' != $name ) ) {
			global $product;
			echo '<div class="product-cat">' . wc_get_product_category_list( $product->get_id(), ', ', '' ) . '</div>';
		}
	}
}

// Add product types

add_filter(
	'alpha_product_loop_types',
	function( $types, $src ) {
		return 'theme' == $src ? array(
			'product-1' => ALPHA_CORE_URI . '/assets/images/products/product-1.jpg',
			'product-2' => ALPHA_CORE_URI . '/assets/images/products/product-2.jpg',
			'product-3' => ALPHA_CORE_URI . '/assets/images/products/product-3.jpg',
			'product-4' => ALPHA_CORE_URI . '/assets/images/products/product-4.jpg',
			'product-5' => ALPHA_CORE_URI . '/assets/images/products/product-5.jpg',
			'product-6' => ALPHA_CORE_URI . '/assets/images/products/product-6.jpg',
			'widget'    => ALPHA_CORE_URI . '/assets/images/products/product-widget.jpg',
			'list'      => ALPHA_CORE_URI . '/assets/images/products/product-list.jpg',
		) : array(
			'product-1' => '/assets/images/products/product-1.jpg',
			'product-2' => '/assets/images/products/product-2.jpg',
			'product-3' => '/assets/images/products/product-3.jpg',
			'product-4' => '/assets/images/products/product-4.jpg',
			'product-5' => '/assets/images/products/product-5.jpg',
			'product-6' => '/assets/images/products/product-6.jpg',
			'widget'    => '/assets/images/products/product-widget.jpg',
			'list'      => '/assets/images/products/product-list.jpg',
		);
	},
	10,
	2
);
