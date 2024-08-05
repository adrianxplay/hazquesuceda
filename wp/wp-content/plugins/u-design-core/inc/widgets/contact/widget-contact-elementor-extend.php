<?php

use Elementor\Controls_Manager;

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_contact' . '/contact_icon_style/after_section_end',
	function( $self, $args ) {
		$self->update_responsive_control(
			'icon_padding',
			array(
				'label'       => esc_html__( 'Padding', 'alpha-core' ),
				'description' => esc_html__( 'Set padding of contact icon.​', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'rem' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .contact i, .elementor-element-{{ID}} .contact svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	},
	10,
	2
);
