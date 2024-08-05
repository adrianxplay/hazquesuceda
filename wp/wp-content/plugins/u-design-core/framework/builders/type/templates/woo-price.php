<?php

global $product;
if ( empty( $product ) ) {
	return;
}

$wrap_cls = 'tb-woo-price';
if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $atts['el_class'] );
}
if ( ! empty( $atts['className'] ) ) {
	$wrap_cls .= ' ' . trim( $atts['className'] );
}

echo '<div class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-price' ) ) . '">';
if ( function_exists( 'woocommerce_template_loop_price' ) ) {
	woocommerce_template_loop_price();
}

if ( function_exists( 'wc_get_stock_html' ) && ! empty( $atts['show_stock'] ) ) {
	echo wc_get_stock_html( $product );
}
echo '</div>';
