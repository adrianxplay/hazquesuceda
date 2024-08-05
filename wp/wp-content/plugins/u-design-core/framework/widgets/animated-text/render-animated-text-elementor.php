<?php
/**
 * Alpha Animated Text Widget Render
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'title_before'     => '',
			'title_after'      => '',
			'animation_type'   => '',
			'each_duration'    => '',
			'each_delay'       => '',
			'animation_delay'  => '',
			'split_type'       => '',
			'items'            => '',
			'self'             => '',
			'animate_infinite' => 'yes',
			'reveal_effect'    => '',
		),
		$atts
	)
);

if ( ! empty( $items ) ) {

	$html  = '';
	$first = true;

	foreach ( $items as $key => $item ) {
		if ( ! empty( $item['_id'] ) ) {
			if ( ! empty( $self ) ) {
				$repeater_setting_key = $self->get_repeater_setting_key( 'text', 'items', $key );
				$self->add_render_attribute( $repeater_setting_key, 'class', ( $first ? 'active visible ' : '' ) . 'animating-item ' . trim( esc_attr( ( empty( $item['custom_class'] ) ? '' : $item['custom_class'] ) . ' elementor-repeater-item-' . $item['_id'] ) ) );
				$first = false;
			}
			$text = esc_html( $item['text'] );

			if ( $split_type ) {

				$letters_array = array();
				$spanned_array = array();
				$glue          = '';

				$base_words = explode( ' ', $text );

				$temp_txt = '';

				foreach ( $base_words as $idx => $base_word ) {
					if ( '' == $base_word ) {
						$temp_txt .= '&nbsp;';
					} else {
						$temp_txt .= (string) $base_word;
						if ( count( $base_words ) !== ( $idx + 1 ) ) {
							$temp_txt .= '&nbsp;|';
						}
					}
				}

				$letters_array = explode( '|', $temp_txt );

				if ( 'letter' == $split_type ) {
					foreach ( $letters_array as $idx => $letter ) {

						$letter  = str_replace( '&nbsp;', ' ', $letter );
						$strlen  = mb_strlen( $letter );
						$letters = '';

						while ( $strlen ) {
							$tmp  = mb_substr( $letter, 0, 1, 'UTF-8' );

							if ( ' ' === $letter ) {
								$letters .= '<span class="a-text">&nbsp;</span>';
							} else {
								$letters .= sprintf( '<span class="a-text">%s</span>', $tmp );
							}

							$letter    = mb_substr( $letter, 1, $strlen, 'UTF-8' );
							$strlen    = mb_strlen( $letter );
						}

						$spanned_array[] = sprintf( '<span class="a-text-wrap">%s</span>', $letters );
					}
				} else {
					foreach ( $letters_array as $idx => $letter ) {

						if ( ' ' === $letter ) {
							$letter = '&nbsp;';
						}

						$spanned_array[] = sprintf( '<span class="a-text-wrap"><span class="a-text">%s</span></span>', $letter );
					}
				}

				$text = implode( $glue, $spanned_array );
			}

			$html .= '<span ' . ( $self ? $self->get_render_attribute_string( $repeater_setting_key ) : '' ) . '>' . $text . '</span> ';
		} else {
			$html .= esc_html( $item['text'] ) . ' ';
		}
	}

	$extra_class = 'yes' == $animate_infinite ? '' : 'animating-once';
	$extra_class .= 'yes' != $reveal_effect ? '' : ' animating-reveal';

	if ( $html ) {
		$html = '<span class="animating-text animating-text-' . esc_attr( $animation_type ) . ' ' . esc_attr( $extra_class ) . '" data-settings="' . esc_attr(
			json_encode(
				array(
					'effect'          => esc_js( $animation_type ),
					'delay'           => $animation_delay,
					'innerDuration'   => $each_duration,
					'innerDelay'      => $each_delay,
				)
			)
		) . '">' . $html . '</span>';
	}

	if ( ! empty( $self ) ) {
		$self->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

		$is_preview = alpha_is_elementor_preview();

		$title_before = esc_html( $title_before );
		$title_after  = esc_html( $title_after );

		if ( $is_preview ) {
			$self->add_inline_editing_attributes( 'title_before' );
			$self->add_inline_editing_attributes( 'title_after' );

			$title_before = '<span ' . $self->get_render_attribute_string( 'title_before' ) . '>' . $title_before . '</span>';
			$title_after  = '<span ' . $self->get_render_attribute_string( 'title_after' ) . '>' . $title_after . '</span>';
		}

		printf(
			'<%1$s %2$s>%3$s</%1$s>',
			in_array( $atts['header_size'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' ) ) ? $atts['header_size'] : 'div',
			$self->get_render_attribute_string( 'title' ),
			$title_before . ' ' . $html . ' ' . $title_after
		);
	}
}
