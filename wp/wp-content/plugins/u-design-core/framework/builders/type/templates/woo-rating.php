<?php

global $product;
if ( empty( $product ) ) {
	return;
}

$wrap_cls = 'tb-woo-rating';
if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $atts['el_class'] );
}
if ( ! empty( $atts['className'] ) ) {
	$wrap_cls .= ' ' . trim( $atts['className'] );
}

echo '<div class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-rating' ) ) . '">';
if ( function_exists( 'woocommerce_template_loop_rating' ) ) {
	woocommerce_template_loop_rating();
}
echo '</div>';
