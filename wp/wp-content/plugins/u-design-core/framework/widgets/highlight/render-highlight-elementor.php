<?php
/**
 * Alpha Highlight Widget Render
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'items' => '',
			'self'  => '',
		),
		$atts
	)
);

if ( ! empty( $items ) ) {

	$html       = '';
	$is_preview = alpha_is_elementor_preview();

	foreach ( $items as $key => $item ) {
		$svg = '';

		if ( ! empty( $self ) ) {
			$repeater_setting_key = $self->get_repeater_setting_key( 'text', 'items', $key );
			$self->add_render_attribute( $repeater_setting_key, 'class', trim( esc_attr( ( empty( $item['custom_class'] ) ? '' : $item['custom_class'] ) ) ) );

			if ( ! empty( $item['_id'] ) && 'yes' == $item['highlight'] ) {
				$self->add_render_attribute( $repeater_setting_key, 'class', 'highlight highlight-' . $item['highlight_type'] . ' elementor-repeater-item-' . $item['_id'] );
				if ( 'yes' == $item['a_loop'] ) {
					$self->add_render_attribute( $repeater_setting_key, 'class', 'highlight-infinite' );
				}
				$self->add_render_attribute( $repeater_setting_key, 'class', $is_preview ? 'animating' : '' );

				if ( 'fill' != $item['highlight_type'] ) {
					$svg .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none">';
					foreach ( $self->svgs[ $item['highlight_type'] ] as $value ) {
						$svg .= '<path d="' . $value . '"></path>';
					}
					$svg .= '</svg>';
				}
			}
			$self->add_inline_editing_attributes( $repeater_setting_key );
			$html .= '<span ' . ( $self ? $self->get_render_attribute_string( $repeater_setting_key ) : '' ) . '>';
			$html .= esc_html( $item['text'] ) . $svg;
			$html .= '</span>';
		}

		if ( 'yes' == $item['line_break'] ) {
			$html .= '<br />';
		}
	}

	if ( empty( $self ) ) {
		printf(
			'<%1$s class="highlight-text">%2$s</%1$s>',
			in_array( $atts['header_size'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' ) ) ? esc_html( $atts['header_size'] ) : 'div',
			alpha_escaped( $html )
		);
	} else {

		$self->add_render_attribute( 'title', 'class', 'elementor-heading-title highlight-text' );

		printf(
			'<%1$s %2$s>%3$s</%1$s>',
			in_array( $atts['header_size'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' ) ) ? esc_html( $atts['header_size'] ) : 'div',
			$self->get_render_attribute_string( 'title' ),
			alpha_escaped( $html )
		);
	}
}
