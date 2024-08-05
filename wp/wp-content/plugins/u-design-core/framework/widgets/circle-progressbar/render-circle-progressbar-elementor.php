<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $value
 * @var $units
 *
 * Extra Params
 * @var $view
 * @var $icon
 * @var $icon_color
 * @var $size
 * @var $trackcolor
 * @var $barcolor
 * @var $speed
 * @var $line
 * @var $linecap
 *
 */

use Elementor\Icons_Manager;

$title  = '';
$output = '';
extract( $atts );

if ( empty( $barcolor ) ) {
	$barcolor = alpha_get_option( 'primary_color' );
}

$options                        = array();
$options['trackColor']          = $trackcolor;
$options['barColor']            = $barcolor;
$options['lineCap']             = $linecap;
$options['lineWidth']           = $line;
$options['size']                = $size;
$options['animate']['duration'] = $speed;
$options                        = json_encode( $options );

$css_class = 'circular-bar center';

if ( $view ) {
	$css_class .= ' ' . $view;
}

$output  = '<div class= "' . esc_attr( $css_class ) . '">';
$output .= '<div class="circular-bar-chart" data-percent="' . esc_attr( $value ) . '" data-plugin-options="' . esc_attr( $options ) . '" style="height:' . esc_attr( $size ) . 'px">';

$output .= '<div class="bar-content">';

$this->add_render_attribute( 'title', 'class', 'bar-title' );
$this->add_inline_editing_attributes( 'title' );

if ( 'only-icon' === $view && $icon ) {
	if ( 'svg' === $icon_cl['library'] ) {
		ob_start();
		Icons_Manager::render_icon( $icon_cl, array( 'aria-hidden' => 'true' ) );
		$output .= ob_get_clean();
	} else {
		$output .= '<i class="' . esc_attr( $icon ) . '"' . ( $icon_color ? ' style="color:' . esc_attr( $icon_color ) . '"' : '' ) . '></i>';
	}
} elseif ( 'only-title' === $view ) {
	if ( $title ) {
		$output .= '<strong ' . $this->get_render_attribute_string( 'title' ) . '>' . alpha_strip_script_tags( $title ) . '</strong>';
	}
} else {
	if ( $title && 'only-value' !== $view ) {
		$output .= '<strong ' . $this->get_render_attribute_string( 'title' ) . '>' . alpha_strip_script_tags( $title ) . '</strong>';
	}
	$output .= '<label><span class="percent">0</span>' . esc_html( $units ) . '</label>';
}
$output .= '</div>';

$output .= '</div>';

$output .= '</div>';
echo alpha_escaped( $output );
