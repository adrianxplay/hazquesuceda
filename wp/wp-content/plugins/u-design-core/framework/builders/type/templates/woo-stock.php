<?php

global $product;
if ( empty( $product ) ) {
	return;
}

$wrap_cls = 'tb-woo-stock';
if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $atts['el_class'] );
}
if ( ! empty( $atts['className'] ) ) {
	$wrap_cls .= ' ' . trim( $atts['className'] );
}

echo '<div class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-stock' ) ) . '">';

if ( function_exists( 'wc_get_stock_html' ) ) {
	if ( $product->is_type( 'simple' ) ) {
		remove_filter( 'woocommerce_get_stock_html', 'alpha_woocommerce_stock_html', 10, 2 );
	}

	$stock_html = wc_get_stock_html( $product );
	if ( $stock_html  ) {
		echo alpha_escaped( $stock_html );
	} elseif ( wp_is_json_request() ) {
		echo '<p class="stock in-stock">';
		esc_html_e( 'In stock', 'woocommerce' );
		echo '</p>';
	}

	if ( $product->is_type( 'simple' ) ) {
		add_filter( 'woocommerce_get_stock_html', 'alpha_woocommerce_stock_html', 10, 2 );
	}
}
echo '</div>';
