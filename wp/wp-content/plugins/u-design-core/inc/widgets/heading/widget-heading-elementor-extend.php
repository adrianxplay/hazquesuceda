<?php

/**
 * Alpha Heading Widget Extend
 *
 * Alpha Widget to display heading.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Background;

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_heading' . '/section_heading_title/after_section_end',
	function( $self, $args ) {
		$self->update_control(
			'tag',
			array(
				'label'       => esc_html__( 'HTML Tag', 'alpha-core' ),
				'description' => esc_html__( 'Select the HTML Heading tag from H1 to H6 and P tag too.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'p'   => 'p',
					'div' => 'div',
				),
				'default'     => 'h2',
			)
		);

		$self->update_control(
			'decoration',
			array(
				'type'    => Alpha_Controls_Manager::IMAGE_CHOOSE,
				'options' => array(
					''                           => 'assets/images/heading/heading-1.jpg',
					'cross'                      => 'assets/images/heading/heading-2.jpg',
					'underline'                  => 'assets/images/heading/heading-3.jpg',
					'underline title-underline2' => 'assets/images/heading/heading-4.jpg',
				),
				'width'   => 2,
			)
		);

		$self->add_control(
			'decoration_height',
			array(
				'label'      => esc_html__( 'Decoration Height', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 10,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .title::before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .title::after'  => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'decoration' => 'cross',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'decoration_spacing',
				),
			)
		);

		$self->add_control(
			'underline_height',
			array(
				'label'      => esc_html__( 'Underline Height', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 10,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .title:after'           => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .title-underline:after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'decoration' => array( 'underline', 'underline title-underline2' ),
				),
			),
			array(
				'position' => array(
					'of' => 'decoration',
				),
			)
		);

		$self->add_control(
			'underline_width',
			array(
				'label'      => esc_html__( 'Underline Width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 100,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .title:after' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'decoration' => 'underline title-underline2',
				),
			),
			array(
				'position' => array(
					'of' => 'decoration',
				),
			)
		);
	},
	10,
	2
);

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_heading' . '/section_heading_link/after_section_end',
	function( $self, $args ) {
		$self->update_control(
			'link_label',
			array(
				'label'       => esc_html__( 'Link Label', 'alpha-core' ),
				'description' => esc_html__( 'Type a certain label of your heading link.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => 'link',
			)
		);
	},
	10,
	2
);

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_heading' . '/section_heading_title_style/after_section_end',
	function( $self, $args ) {
		$self->update_control(
			'title_spacing',
			array(
				'selectors' => array(
					'{{WRAPPER}} .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	},
	10,
	2
);

add_action(
	'elementor/element/heading/section_title_style/before_section_end',
	function( $self ) {

		$self->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'heading_gradient_background',
				'types'     => array( 'gradient' ),
				'selector'  => '.elementor-element-{{ID}} .elementor-heading-title',
				'condition' => array(
					'gradient_type' => 'yes',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title_color',
				),
			)
		);

		$self->add_control(
			'gradient_type',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Gradient Background', 'alpha-core' ),
				'description' => esc_html__( 'Defines whether to be gradient heading type.', 'alpha-core' ),
				'condition'   => array( 'background_type!' => 'yes' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .elementor-heading-title' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title_color',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'heading_background_image',
				'types'     => array( 'classic' ),
				'selector'  => '.elementor-element-{{ID}} .elementor-heading-title',
				'condition' => array(
					'background_type' => 'yes',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title_color',
				),
			)
		);

		$self->add_control(
			'background_type',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Image Background', 'alpha-core' ),
				'description' => esc_html__( 'Defines whether to be background image heading type.', 'alpha-core' ),
				'condition'   => array( 'gradient_type!' => 'yes' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .elementor-heading-title' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title_color',
				),
			)
		);
	}
);
