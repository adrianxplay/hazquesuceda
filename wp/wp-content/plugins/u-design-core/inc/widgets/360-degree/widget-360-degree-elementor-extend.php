<?php

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_360_degree' . '/section_360_degree_style/after_section_end',
	function( $self ) { 

		$self->add_control(
			'border_width',
			array(
				'label'       => esc_html__( 'Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Controls the button border width.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} .alpha-360-gallery-wrapper .nav_bar a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'button_responsive_padding',
				),
			)
		);
		$self->add_control(
			'border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
				'description' => esc_html__( 'Controls the button border radius.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} .alpha-360-gallery-wrapper .nav_bar a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'button_responsive_padding',
				),
			)
		);
	}
);