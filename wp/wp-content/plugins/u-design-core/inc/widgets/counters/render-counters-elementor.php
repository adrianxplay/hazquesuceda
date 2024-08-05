<?php
/**
 * Counters Shortcode Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Icons_Manager;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'layout_type'    => 'grid',
			'counters_list'  => array(),
			'count_position' => 'top',
			'time'           => 3,
			'show_in_box'    => '',
			'show_dividers'  => '',
			'title_html_tag' => 'h3',
		),
		$atts
	)
);

$html = '';

$wrapper_cls   = 'counters ';
$wrapper_attrs = '';

if ( 'yes' == $show_dividers ) {
	$wrapper_cls .= 'counters-separated ';
}

$grid_space_class = alpha_get_grid_space_class( $atts );
$col_cnt          = alpha_elementor_grid_col_cnt( $atts );

if ( 'slider' == $layout_type ) {
	$wrapper_cls   .= alpha_get_slider_class( $atts );
	$wrapper_attrs .= ' data-slider-options="' . esc_attr(
		json_encode(
			alpha_get_slider_attrs( $atts, $col_cnt )
		)
	) . '"';
}

if ( $grid_space_class ) {
	$wrapper_cls .= ' ' . $grid_space_class;
}
if ( $col_cnt ) {
	$wrapper_cls .= ' ' . alpha_get_col_class( $col_cnt );
}

$counter_cls = 'counter';
if ( 'top' != $count_position ) {
	$counter_cls .= ' counter-side position-' . $count_position;
}

$html = '<div class="' . esc_attr( $wrapper_cls ) . '" ' . $wrapper_attrs . '>';

foreach ( $counters_list as $key => $item ) {
	$html .= '<div class="grid-col">';
	$html .= '<div class="' . esc_attr( $counter_cls ) . '">';
	$html .= '<div class="counter-number">';

	if ( 'svg' == $item['icon']['library'] ) {
		ob_start();
		Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );
		$html .= ob_get_clean();
	} elseif ( $item['icon']['value'] ) {
		$html .= '<i class="' . esc_attr( $item['icon']['value'] ) . '"></i>';
	}

	if ( $item['prefix'] ) {
		$html .= '<span class="counter-number-prefix">' . esc_html( $item['prefix'] ) . '</span>';
	}
	$html .= preg_replace(
		'/([\d|\.]+)/',
		'<span class="count-to" data-speed="' . (int) ( $time ? $time : 1 ) * 1000 . '" data-to="$1">0</span>',
		isset( $item['target'] ) ? $item['target'] : '99'
	);
	if ( $item['suffix'] ) {
		$html .= '<span class="counter-number-suffix">' . esc_html( $item['suffix'] ) . '</span>';
	}

	$html .= '</div>';
	if ( $item['title'] || $item['desc'] ) {
		$html .= '<div class="counter-content">';
		if ( $item['title'] ) {
			$repeater_setting_key = $this->get_repeater_setting_key( 'title', 'counters_list', $key );

			$this->add_render_attribute( $repeater_setting_key, 'class', 'count-title' );
			$this->add_inline_editing_attributes( $repeater_setting_key );
			$html .= '<' . $title_html_tag . ' ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $item['title'] ) . '</' . $title_html_tag . '>';
		}
		if ( $item['desc'] ) {
			$repeater_setting_key = $this->get_repeater_setting_key( 'desc', 'counters_list', $key );
			$this->add_render_attribute( $repeater_setting_key, 'class', 'count-desc' );
			$this->add_inline_editing_attributes( $repeater_setting_key );
			$html .= '<p ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $item['desc'] ) . '</p>';
		}
		$html .= '</div>';
	}
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div>';

echo alpha_escaped( $html );
