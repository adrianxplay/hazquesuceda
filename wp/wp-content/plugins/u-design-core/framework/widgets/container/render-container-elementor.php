<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Gutenberg Container Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'flex_box'         => '',
			'flex_wrap'        => '',
			'horizontal_align' => 'start',
			'vertical_align'   => 'start',
			'text_align'       => 'center',
			'wrap_class'       => '',
		),
		$atts
	)
);

if ( $flex_box ) {
	$wrap_class .= ' d-flex';
	if ( $flex_wrap ) {
		$wrap_class .= ' flex-wrap';
	}
	if ( 'start' != $horizontal_align ) {
		$wrap_class .= ' justify-content-' . $horizontal_align;
	}
	if ( 'start' != $vertical_align ) {
		$wrap_class .= ' align-items-' . $vertical_align;
	}
} else {
	$wrap_class .= ' text-' . $text_align;
}
$html = '<div class="' . esc_attr( $wrap_class ) . '">' . $content . '</div>';

echo alpha_escaped( $html );
