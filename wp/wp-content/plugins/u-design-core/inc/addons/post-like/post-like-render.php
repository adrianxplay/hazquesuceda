<?php

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'disable_action'   => '',
			'icon_cls'         => '',
			'dislike_icon_cls' => '',
			'icon_pos'         => '',
			'el_class'         => '',
			'className'        => '',
		),
		$atts
	)
);

$post_id = get_the_ID();
if ( empty( $post_id ) ) {
	return;
}

$wrap_cls = 'alpha-tb-post-like';
if ( $el_class && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $el_class );
}
if ( $className ) {
	$wrap_cls .= ' ' . trim( $className );
}

$icon_html = '';

$like_btn_class = isset( $_COOKIE[ 'udesign_post_likes_' . $post_id ] ) && ( json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $post_id ] ), true )['action'] ) ? json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $post_id ] ), true )['action'] : 'like';
$likes_count    = get_post_meta( $post_id, 'udesign_post_likes', true );
$tag            = 'a';
$wrap_cls      .= ' ' . $like_btn_class;
$wrap_attrs     = '';

if ( $icon_cls ) {
	$icon_html .= '<i class="alpha-tb-icon ' . esc_attr( 'like' != $like_btn_class && $dislike_icon_cls ? $dislike_icon_cls : $icon_cls ) . '"></i>';
	$wrap_cls  .= ' alpha-tb-icon-' . ( $icon_pos ? $icon_pos : 'left' );
	if ( $dislike_icon_cls ) {
		$wrap_attrs .= ' data-other_cls="alpha-tb-icon ' . esc_attr( 'like' == $like_btn_class ? $dislike_icon_cls : $icon_cls ) . '"';
	}
}

if ( $disable_action ) {
	$tag = 'span';
} else {
	$wrap_cls   .= ' vote-link';
	$wrap_attrs .= ' href="#" data-count="' . absint( $likes_count ) . '" data-id="' . absint( $post_id ) . '"';
}

echo '<' . $tag . ' class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-post-like' ) ) . '"' . $wrap_attrs . '>';

if ( $icon_html && ! $icon_pos ) {
	echo alpha_escaped( $icon_html );
}

echo '<span class="like-count">' . absint( $likes_count ) . '</span>';

if ( $icon_html && $icon_pos ) {
	echo alpha_escaped( $icon_html );
}

echo '</' . $tag . '>';
