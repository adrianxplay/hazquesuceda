<?php
/**
 * Render template for block widget.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'img_source'            => '',
			'img_size'              => '',
			'show_caption_selector' => '',
			'custom_caption'        => '',
			'link'                  => '',
			'lightbox'              => '',
			'link_url'              => '',
			'wrap_class'            => '',
		),
		$atts
	)
);

if ( 'yes' == $lightbox && 'media' == $link ) {
	wp_enqueue_style( 'alpha-magnific-popup' );
	wp_enqueue_script( 'alpha-magnific-popup' );
	wp_enqueue_script( 'alpha-image-popup', alpha_core_framework_uri( '/widgets/image/image' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
	$wrap_class .= ' alpha_img_popup ';

}

// dynamic content
if ( ! empty( $atts['image_source'] ) && isset( $atts['dynamic_content'] ) && ! empty( $atts['dynamic_content']['source'] ) ) {
	$img_source = apply_filters( 'alpha_dynamic_tags_content', '', null, $atts['dynamic_content'], 'image', $img_size );
}
if ( empty( $img_source ) ) {
	return;
}
$html = '<div class="' . esc_attr( $wrap_class ) . '">';
if ( $show_caption_selector ) {
	// Begin figure for lightbox
	$html .= '<figure>';
}
if ( 'custom' == $link && ! empty( $link_url ) ) {
	$html     .= '<a href="' . ( esc_url( $link_url ) ) . '">';
		$html .= '<img' .
		' src="' . ( ! empty( $img_source['sizes'][ $img_size ]['url'] ) ? esc_url( $img_source['sizes'][ $img_size ]['url'] ) : ( ! empty( $img_source['url'] ) ? esc_url( $img_source['url'] ) : '' ) ) . '"' .
		' alt="' . ( ! empty( $img_source['alt_text'] ) ? esc_attr( $img_source['alt_text'] ) : '' ) . '"' .
		' width="' . ( ! empty( $img_source['sizes'][ $img_size ]['width'] ) ? intval( $img_source['sizes'][ $img_size ]['width'] ) : '' ) . '"' .
		' height="' . ( ! empty( $img_source['sizes'][ $img_size ]['height'] ) ? intval( $img_source['sizes'][ $img_size ]['height'] ) : '' ) . '"' .
		'/>';
	$html     .= '</a>';
} else {
		$html .= '<img' .
			' src="' . ( ! empty( $img_source['sizes'][ $img_size ]['url'] ) ? esc_url( $img_source['sizes'][ $img_size ]['url'] ) : ( ! empty( $img_source['url'] ) ? esc_url( $img_source['url'] ) : '' ) ) . '" ' .
			' alt="' . ( ! empty( $img_source['alt_text'] ) ? esc_attr( $img_source['alt_text'] ) : '' ) . '" ' .
			' width="' . ( ! empty( $img_source['sizes'][ $img_size ]['width'] ) ? intval( $img_source['sizes'][ $img_size ]['width'] ) : '' ) . '" ' .
			' height="' . ( ! empty( $img_source['sizes'][ $img_size ]['height'] ) ? intval( $img_source['sizes'][ $img_size ]['height'] ) : '' ) . '"' .
		'/>';
}
if ( $show_caption_selector ) {
	// End figure for lightbox
		$html .= '<figcaption class="alpha-gb-caption-text">' . ( 'attachment' == $show_caption_selector ? esc_html( $img_source['caption'] ) : esc_html( $custom_caption ) ) . '</figcaption>' .
		'</figure>';
}
$html .= '</div>';
echo alpha_escaped( $html );
