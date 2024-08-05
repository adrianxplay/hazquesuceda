<?php

/**
 * Alpha Image Compare Widget Extend
 *
 * Alpha Widget to compare images
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_image_compare/section_images_style/before_section_end',
	function ( $self ) {

		$self->add_control(
			'image_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .icomp-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'handle_heading',
				),
			)
		);

		$self->update_control(
			'handle_heading',
			array(
				'separator' => 'before',
			)
		);

		$self->update_control(
			'handle_bg_color',
			array(
				'label'       => esc_html__( 'Handle Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the handle background color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .icomp-handle:before, {{WRAPPER}} .icomp-handle:after' => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'handle_type' => array( 'line', 'circle', 'rect' ),
				),
			)
		);
	}
);
