<?php

use Elementor\Group_Control_Box_Shadow;
use Elementor\Controls_Manager;

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_timeline_horizontal' . '/timeline_media/after_section_end',
	function( $self, $args ) {
		$self->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_box_shadow',
				'selector' => '.elementor-element-{{ID}} .timeline-media img',
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'image_border_radius',
				),
			)
		);
	},
	10,
	2
);

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_timeline_horizontal' . '/timeline_content/after_section_end',
	function( $self, $args ) {
		$self->update_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .timeline-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	},
	10,
	2
);
