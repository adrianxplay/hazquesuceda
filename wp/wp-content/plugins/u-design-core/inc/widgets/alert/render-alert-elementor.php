<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Alert Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'alert_skin'    => 'accent',
			'alert_type'    => '',
			'content_align' => '',
			'title'         => '',
			'description'   => '',
			'alert_icon'    => '',
			'show_dismiss'  => 'yes',
			'dismiss_icon'  => '',
			'alert_align'   => 'left',
		),
		$atts
	)
);

$wrapper_class = 'alert alert-' . $alert_skin;
if ( $alert_type ) {
	$wrapper_class .= ' alert-' . $alert_type;
}

$html = '<div class="' . esc_attr( $wrapper_class ) . '">';

if ( $alert_icon['value'] ) {
	$html .= '<div class="alert-icon ' . esc_attr( $alert_icon['value'] ) . '">';
	$html .= '</div>';
}

if ( $alert_icon && $title && $description ) {
	$html .= '<div class="alert-content">';
}

if ( $title ) {
	$this->add_render_attribute( 'title', 'class', 'alert-title' );
	$html .= '<div ' . $this->get_render_attribute_string( 'title' ) . '>';
	$html .= alpha_strip_script_tags( $title );
	$html .= '</div>';
}

if ( $description ) {
	$this->add_render_attribute( 'description', 'class', 'alert-desc' );
	$html .= '<div ' . $this->get_render_attribute_string( 'description' ) . '>';
	$html .= alpha_strip_script_tags( $description );
	$html .= '</div>';
}

if ( $alert_icon && $title && $description ) {
	$html .= '</div>';
}

if ( 'yes' == $show_dismiss ) {
	$html .= '<button class="btn btn-link btn-close ' . ( $dismiss_icon['value'] ? esc_attr( $dismiss_icon['value'] ) : ( ALPHA_ICON_PREFIX . '-icon-times-solid' ) ) . '" type="button"></button>';
}

$html .= '</div>';

echo alpha_escaped( $html );
