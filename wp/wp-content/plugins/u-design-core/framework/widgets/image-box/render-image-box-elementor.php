<?php
/**
 * The image box elementor render.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'title'     => __( 'Input Title Here', 'alpha-core' ),
			'subtitle'  => __( 'Input Subtitle Here', 'alpha-core' ),
			'content'   => '<div class="social-icons">
								<a href="#" class="social-icon framed social-facebook"><i class="fab fa-facebook-f"></i></a>
								<a href="#" class="social-icon framed social-twitter"><i class="fab fa-twitter"></i></a>
								<a href="#" class="social-icon framed social-linkedin"><i class="fab fa-linkedin-in"></i></a>
							</div>',
			'image'     => array( 'url' => '' ),
			'thumbnail' => 'full',
			'type'      => '',
			'link'      => '',
		),
		$atts
	)
);

$html  = '';
$image = '';

if ( defined( 'ELEMENTOR_VERSION' ) ) {
	$image = Elementor\Group_Control_Image_Size::get_attachment_image_html( $atts, 'image' );
}

$attrs           = [];
$attrs['href']   = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#';
$attrs['target'] = ! empty( $link['is_external'] ) ? '_blank' : '';
$attrs['rel']    = ! empty( $link['nofollow'] ) ? 'nofollow' : '';

if ( ! empty( $link['custom_attributes'] ) ) {
	foreach ( explode( ',', $link['custom_attributes'] ) as $attr ) {
		$key   = explode( '|', $attr )[0];
		$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
		if ( isset( $attrs[ $key ] ) ) {
			$attrs[ $key ] .= ' ' . $value;
		} else {
			$attrs[ $key ] = $value;
		}
	}
}

$link_attrs = '';
foreach ( $attrs as $key => $value ) {
	if ( ! empty( $value ) ) {
		$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
	}
}

$link_open  = empty( $link_attrs ) ? '' : '<a ' . $link_attrs . '>';
$link_close = empty( $link_attrs ) ? '' : '</a>';

if ( $link && $title ) {
	$title = $link_open . esc_html( $title ) . $link_close;
}

$title_html    = $title ? '<h3 class="title">' . alpha_strip_script_tags( $title ) . '</h3>' : '';
$subtitle_html = $subtitle ? '<h4 class="subtitle">' . esc_html( $subtitle ) . '</h4>' : '';
$content_html  = $content ? '<div class="content">' . alpha_strip_script_tags( $content ) . '</div>' : '';

$html = '<div class="image-box ' . esc_attr( $type ) . '">';

if ( ! $type ) {
	$html .= $link_open . '<figure>' . $image . '</figure>' . $link_close . $title_html . $subtitle_html . $content_html;
} elseif ( 'inner' == $type ) {
	$html .= '<figure>' . $image . '<div class="overlay-visible">' . $title_html . $subtitle_html . '</div>' . '<div class="overlay overlay-transparent">' . $content_html . '</div>' . '</figure>';
} elseif ( 'outer' == $type ) {
	$html .= '<figure>' . $image . '<div class="overlay">' . $content_html . '</div>' . '</figure>' . $title_html . $subtitle_html;
}

$html .= '</div>';

echo alpha_escaped( $html );
