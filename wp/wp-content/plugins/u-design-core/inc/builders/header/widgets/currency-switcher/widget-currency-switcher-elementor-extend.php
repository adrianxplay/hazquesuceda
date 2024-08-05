<?php
/**
 * Alpha Header Elementor Currency Switcher
 *
 * @author     Andon
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;

// Update content option
add_action(
	'elementor/element/' . ALPHA_NAME . '_header_currency_switcher/section_toggle_style/before_section_end',
	function( $self, $args ) {
		$self->update_control(
			'toggle_padding',
			array(
				'selectors' => array(
					'.elementor-element-{{ID}} .switcher .switcher-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$self->remove_control( 'toggle_border' );
		$self->remove_control( 'toggle_border_radius' );
		$self->remove_control( 'toggle_border_color' );
		$self->remove_control( 'toggle_hover_border_color' );
	},
	10,
	2
);
