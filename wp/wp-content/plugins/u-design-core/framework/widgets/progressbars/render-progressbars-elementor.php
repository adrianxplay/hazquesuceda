<?php
/**
 * Progressbars Shortcode Render
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Progress bars
			'text_pos'           => 'outer',
			'progressbars_list'  => array(),
			'display_percentage' => 'yes',
			'percentage_pos'     => '',
			'effect'             => '',
		),
		$atts
	)
);

if ( count( $progressbars_list ) ) {

	$wrapper_cls = 'progress-bars';
	if ( $effect ) {
		$wrapper_cls .= ' progress-' . $effect;
	}
	if ( 'inner' == $text_pos ) {
		$wrapper_cls .= ' progress-inner-text';
	}
	if ( 'percent' == $percentage_pos ) {
		$wrapper_cls .= ' percent-end-progress';
	} elseif ( 'after_title' == $percentage_pos ) {
		$wrapper_cls .= ' percent-end-title';
	} else {
		$wrapper_cls .= ' percent-end-bar';
	}

	echo '<div class="' . esc_attr( $wrapper_cls ) . '">';
	foreach ( $progressbars_list as $key => $bar ) {
		$percent = 100 < $bar['percent']['size'] ? 100 : $bar['percent']['size'];

		$title_html   = '';
		$percent_html = '';

		if ( ! empty( $bar['title'] ) ) {
			$title_key = $this->get_repeater_setting_key( 'title', 'progressbars_list', $key );
			$this->add_render_attribute( $title_key, 'class', 'title' );
			$this->add_inline_editing_attributes( $title_key );
			$title_html = '<span ' . $this->get_render_attribute_string( $title_key ) . '>' . esc_html( $bar['title'] ) . '</span>';
		}

		if ( 'yes' == $display_percentage ) {
			$percent_html = '<span class="progress-percentage">' . floatval( $percent ) . '%</span>';
		}

		if ( 'outer' == $text_pos ) {
			if ( 'yes' == $display_percentage ) {
				echo '<div class="title-wrapper">';
			}
			echo alpha_escaped( $title_html );

			if ( 'yes' == $display_percentage ) {
				echo alpha_escaped( $percent_html ) . '</div>';
			}
		}

		$progress_class = 'progress-wrapper';

		echo '<div class="' . esc_attr( $progress_class ) . '" data-value="' . floatval( $percent ) . '"><div class="progress-bar bg-' . $bar['progress_skin'] . '">';

		if ( 'inner' == $text_pos ) {
			echo alpha_escaped( $title_html );
		}
		if ( 'yes' == $display_percentage && 'inner' == $text_pos ) {
			echo alpha_escaped( $percent_html );
		}
		echo '</div></div>';
	}
	echo '</div>';
}
