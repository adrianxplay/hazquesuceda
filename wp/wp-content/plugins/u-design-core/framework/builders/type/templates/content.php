<?php

$wrap_cls = 'tb-content';
if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $atts['el_class'] );
}
if ( ! empty( $atts['className'] ) ) {
	$wrap_cls .= ' ' . trim( $atts['className'] );
}

echo '<div class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-content' ) ) . '">';
global $current_screen;
if ( ( $current_object = get_queried_object() ) && $current_object->term_id ) {
	if ( $current_object->description ) {
		echo do_shortcode( $current_object->description );
	}
} else {
	if ( empty( $atts['content_display'] ) || 'content' != $atts['content_display'] ) {
		global $post;
		echo alpha_get_excerpt( $post, isset( $atts['excerpt_length'] ) ? (int) $atts['excerpt_length'] : alpha_get_loop_prop( 'excerpt_length', 15 ) );
	} else {
		if ( $current_screen && $current_screen->is_block_editor() ) {
			echo do_shortcode( get_the_content() );
		} else {
			the_content();
		}
	}
}
echo '</div>';
