<?php
/**
 * Slider Partial
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

/**
 * Register elementor layout controls for slider.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_slider_layout_controls' ) ) {
	function alpha_elementor_slider_layout_controls( $self, $condition_key ) {

		$self->add_control(
			'slider_vertical_align',
			array(
				'label'       => esc_html__( 'Vertical Align', 'alpha-core' ),
				'description' => esc_html__( 'Choose vertical alignment of items. Choose from Top, Middle, Bottom, Stretch.', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'top'         => array(
						'title' => esc_html__( 'Top', 'alpha-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle'      => array(
						'title' => esc_html__( 'Middle', 'alpha-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom'      => array(
						'title' => esc_html__( 'Bottom', 'alpha-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'same-height' => array(
						'title' => esc_html__( 'Stretch', 'alpha-core' ),
						'icon'  => 'eicon-v-align-stretch',
					),
				),
				'condition'   => array(
					$condition_key => 'slider',
				),
			)
		);
	}
}

/**
 * Register elementor style controls for slider.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_slider_style_controls' ) ) {
	function alpha_elementor_slider_style_controls( $self, $condition_key = '', $widget = true ) {
		$left  = is_rtl() ? 'right' : 'left';
		$right = is_rtl() ? 'left' : 'right';

		if ( empty( $condition_key ) ) {
			$self->start_controls_section(
				'slider_style',
				array(
					'label' => $widget ? esc_html__( 'Slider', 'alpha-core' ) : alpha_elementor_panel_heading( esc_html__( 'Slider', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
		} else {
			$self->start_controls_section(
				'slider_style',
				array(
					'label'     => $widget ? esc_html__( 'Slider', 'alpha-core' ) : alpha_elementor_panel_heading( esc_html__( 'Slider', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						$condition_key => 'slider',
					),
				)
			);
		}
		$self->add_control(
			'style_heading_slider_options',
			array(
				'label' => esc_html__( 'Slider Options', 'alpha-core' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

			$self->add_control(
				'autoplay',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Autoplay', 'alpha-core' ),
					'description' => esc_html__( 'Enables each slides play sliding automatically.', 'alpha-core' ),
					'condition'   => array(
						'loop' => 'yes',
					),
				)
			);

			$self->add_control(
				'autoplay_timeout',
				array(
					'type'        => Controls_Manager::NUMBER,
					'label'       => esc_html__( 'Autoplay Timeout', 'alpha-core' ),
					'description' => esc_html__( 'Controls how long each slides should be shown.', 'alpha-core' ),
					'default'     => 5000,
					'condition'   => array(
						'autoplay' => 'yes',
						'loop'     => 'yes',
					),
				)
			);

			$self->add_control(
				'loop',
				array(
					'label'       => esc_html__( 'Infinite Loop', 'alpha-core' ),
					'description' => esc_html__( 'Makes slides of slider play sliding infinitely.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
				)
			);

			$self->add_control(
				'centered',
				array(
					'label'       => esc_html__( 'Center Mode', 'alpha-core' ),
					'description' => esc_html__( 'Displays a slide at center of your slider container.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
				)
			);

			$self->add_control(
				'focus_on_active',
				array(
					'label'       => esc_html__( 'Focus On Center Item', 'alpha-core' ),
					'description' => esc_html__( 'Focus on active slide with zoom or highlight effect.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'centered' => 'yes',
					),
				)
			);

			$self->add_control(
				'focus_effect',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Focus Effect', 'alpha-core' ),
					'description' => esc_html__( 'Focus Effect for active slide.', 'alpha-core' ),
					'default'     => 'scale',
					'options'     => array(
						'scale'         => esc_html__( 'Scale', 'alpha-core' ),
						'opacity'       => esc_html__( 'Opacity', 'alpha-core' ),
						'scale_opacity' => esc_html__( 'Scale & Opacity', 'alpha-core' ),
					),
					'condition'   => array(
						'centered'        => 'yes',
						'focus_on_active' => 'yes',
					),
				)
			);

			$self->add_responsive_control(
				'scale_size',
				array(
					'label'       => esc_html__( 'Scale Size', 'alpha-core' ),
					'description' => esc_html__( 'Input the scale size you want for active slide.', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => 0,
					'max'         => 3,
					'step'        => 0.1,
					'default'     => 1.3,
					'selectors'   => array(
						'.elementor-element-{{ID}} .slider-zoom-in-active-slide .slider-slide-active' => 'transform: scale({{SIZE}});',
					),
					'condition'   => array(
						'centered'        => 'yes',
						'focus_on_active' => 'yes',
						'focus_effect'    => array( 'scale', 'scale_opacity' ),
					),
				)
			);

			$self->add_responsive_control(
				'viewport_spacing',
				array(
					'label'       => esc_html__( 'Viewport Spacing (px)', 'alpha-core' ),
					'description' => esc_html__( 'You have to set padding top and bottom for zoom in effect.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem', 'em', '%' ),
					'default'     => array(
						'size' => 50,
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .slider-zoom-in-active-slide .slider-wrapper' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'centered'        => 'yes',
						'focus_on_active' => 'yes',
						'focus_effect'    => array( 'scale', 'scale_opacity' ),
					),
				)
			);

			$self->add_control(
				'transparency',
				array(
					'label'       => esc_html__( 'Transparency', 'alpha-core' ),
					'description' => esc_html__( 'Input transparency amount you want for slides except active one.', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => 0,
					'step'        => 0.1,
					'default'     => 0.5,
					'selectors'   => array(
						'.elementor-element-{{ID}} .slider-active-slide-opacity .slider-slide' => 'opacity: {{SIZE}};',
					),
					'condition'   => array(
						'centered'        => 'yes',
						'focus_on_active' => 'yes',
						'focus_effect'    => array( 'opacity', 'scale_opacity' ),
					),
				)
			);

			$self->add_control(
				'autoheight',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Auto Height', 'alpha-core' ),
					'description' => esc_html__( 'Makes each slides have their own height. Slides could have different height.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'disable_mouse_drag',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Disable Mouse Drag', 'alpha-core' ),
					'description' => esc_html__( 'Disable ability move slider by grabbing it with mouse or by touching it with finger.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'scale_drag',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Scale When Dragging', 'alpha-core' ),
					'description' => esc_html__( 'This will cause your slider items to shrink when dragging.', 'alpha-core' ),
					'condition'   => array(
						'disable_mouse_drag!' => 'yes',
					),
				)
			);

			$self->add_control(
				'effect',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Transition Effect', 'alpha-core' ),
					'description' => esc_html__( 'Transition Effect when slide changes.', 'alpha-core' ),
					'default'     => 'slide',
					'options'     => array(
						'slide'     => esc_html__( 'Slide', 'alpha-core' ),
						'fade'      => esc_html__( 'Fade', 'alpha-core' ),
						'cube'      => esc_html__( 'Cube', 'alpha-core' ),
						'coverflow' => esc_html__( 'Coverflow', 'alpha-core' ),
						'flip'      => esc_html__( 'Flip', 'alpha-core' ),
					),
				)
			);

			$self->add_control(
				'speed',
				array(
					'type'        => Controls_Manager::NUMBER,
					'label'       => esc_html__( 'Transition Speed', 'alpha-core' ),
					'description' => esc_html__( 'Controls how long it takes to change to the next slide.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'style_heading_nav',
				array(
					'label'     => esc_html__( 'Navs', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$self->add_control(
				'show_nav',
				array(
					'label'       => esc_html__( 'Show Nav', 'alpha-core' ),
					'description' => esc_html__( 'Determine whether to show/hide slider navigations.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
				)
			);

			$self->add_control(
				'nav_hide',
				array(
					'label'       => esc_html__( 'Nav Auto Hide', 'alpha-core' ),
					'description' => esc_html__( 'Hides slider navs automatically and show them only if mouse is over.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => '',
					'condition'   => array(
						'show_nav' => 'yes',
					),
				)
			);

			$self->add_control(
				'nav_type',
				array(
					'label'       => esc_html__( 'Nav Type', 'alpha-core' ),
					'description' => esc_html__( 'Choose from icon presets of slider nav. Choose from Simple, Circle, Full.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'simple',
					'options'     => array(
						'simple' => esc_html__( 'Simple', 'alpha-core' ),
						'circle' => esc_html__( 'Circle', 'alpha-core' ),
						'full'   => esc_html__( 'Full', 'alpha-core' ),
					),
					'condition'   => array(
						'show_nav' => 'yes',
					),
				)
			);

		if ( 'section' == $self->get_name() ) {
			$self->add_control(
				'nav_pos',
				array(
					'label'       => esc_html__( 'Nav Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose position of slider navs. Choose from Inner, Bottom.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'inner',
					'options'     => array(
						'inner'  => esc_html__( 'Inner', 'alpha-core' ),
						'bottom' => esc_html__( 'Bottom', 'alpha-core' ),
					),
					'condition'   => array(
						'nav_type!' => 'full',
						'show_nav'  => 'yes',
					),
				)
			);
		} else {
			$self->add_control(
				'nav_pos',
				array(
					'label'       => esc_html__( 'Nav Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose position of slider navs. Choose from Inner, Outer, Top, Bottom.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						'inner'  => esc_html__( 'Inner', 'alpha-core' ),
						''       => esc_html__( 'Outer', 'alpha-core' ),
						'top'    => esc_html__( 'Top', 'alpha-core' ),
						'bottom' => esc_html__( 'Bottom', 'alpha-core' ),
					),
					'condition'   => array(
						'nav_type!' => 'full',
						'show_nav'  => 'yes',
					),
				)
			);
		}

		$self->add_responsive_control(
			'nav_h_position',
			array(
				'label'       => esc_html__( 'Nav Horizontal Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls horizontal position of slider navs.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-container .slider-button-prev' => ( is_rtl() ? 'right' : 'left' ) . ': {{SIZE}}{{UNIT}}',
					'.elementor-element-{{ID}} .slider-container .slider-button-next' => ( is_rtl() ? 'left' : 'right' ) . ': {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'nav_type!' => 'full',
					'nav_pos'   => 'inner',
					'show_nav'  => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'nav_outer_h_position',
			array(
				'label'       => esc_html__( 'Nav Horizontal Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls horizontal position of slider navs.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-nav-outer' => '--alpha-nav-outer-pos: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'nav_type!' => 'full',
					'nav_pos'   => '',
					'show_nav'  => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'nav_top_h_position',
			array(
				'label'       => esc_html__( 'Nav Horizontal Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls horizontal position of slider navs.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-container .slider-button' => ( is_rtl() ? 'left' : 'right' ) . ': {{SIZE}}{{UNIT}}',
					'.elementor-element-{{ID}} .slider-nav-circle.slider-nav-bottom .slider-button-prev, .elementor-element-{{ID}} .slider-nav-circle.slider-nav-top .slider-button-prev' => ( is_rtl() ? 'left' : 'right' ) . ': calc({{SIZE}}{{UNIT}} + .2em)',
				),
				'condition'   => array(
					'nav_type!' => 'full',
					'nav_pos'   => array( 'top', 'bottom' ),
					'show_nav'  => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'nav_v_position_top',
			array(
				'label'       => esc_html__( 'Nav Vertical Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls vertical position of slider navs.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-container .slider-button' => 'top: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'nav_type!' => 'full',
					'nav_pos'   => 'top',
					'show_nav'  => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'nav_v_position_bottom',
			array(
				'label'       => esc_html__( 'Nav Vertical Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls vertical position of slider navs.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-container .slider-button' => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'nav_type!' => 'full',
					'nav_pos'   => 'bottom',
					'show_nav'  => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'nav_v_position',
			array(
				'label'       => esc_html__( 'Nav Vertical Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls vertical position of slider navs.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-button' => 'top: {{SIZE}}{{UNIT}}; transform: none;',
				),
				'condition'   => array(
					'nav_type!' => 'full',
					'nav_pos!'  => array( 'top', 'bottom' ),
					'show_nav'  => 'yes',
				),
			)
		);
		$self->add_responsive_control(
			'slider_nav_size',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Nav Size', 'alpha-core' ),
				'description' => esc_html__( 'Controls the nav size.', 'alpha-core' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 10,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-button' => 'font-size: {{SIZE}}px',
				),
				'condition'   => array(
					'show_nav' => 'yes',
				),
			)
		);

		$self->start_controls_tabs(
			'tabs_nav_style',
			array(
				'condition' => array(
					'show_nav' => 'yes',
				),
			)
		);

			$self->start_controls_tab(
				'tab_nav_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$self->add_control(
				'nav_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-container .slider-button' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'nav_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-container .slider-button' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'nav_type!' => 'simple',
					),
				)
			);

			$self->add_control(
				'nav_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-container .slider-button' => 'border-color: {{VALUE}};',
					),
					'condition' => array(
						'nav_type' => 'circle',
					),
				)
			);
			$self->end_controls_tab();

			$self->start_controls_tab(
				'tab_nav_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$self->add_control(
				'nav_color_hover',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-button:not(.disabled):hover' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'nav_back_color_hover',
				array(
					'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-button:not(.disabled):hover' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'nav_type!' => 'simple',
					),
				)
			);

			$self->add_control(
				'nav_border_color_hover',
				array(
					'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-container .slider-button:not(.disabled):hover' => 'border-color: {{VALUE}};',
					),
					'condition' => array(
						'nav_type' => 'circle',
					),
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'tab_nav_disabled',
				array(
					'label'     => esc_html__( 'Disabled', 'alpha-core' ),
					'condition' => array(
						'nav_type!' => 'full',
					),
				)
			);

			$self->add_control(
				'nav_color_disabled',
				array(
					'label'     => esc_html__( 'Disabled Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-button.disabled' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'nav_back_color_disabled',
				array(
					'label'     => esc_html__( 'Disabled Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-button.disabled' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'nav_type!' => 'simple',
					),
				)
			);

			$self->add_control(
				'nav_border_color_disabled',
				array(
					'label'     => esc_html__( 'Disabled Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .slider-container .slider-button.disabled' => 'border-color: {{VALUE}};',
					),
					'condition' => array(
						'nav_type' => 'circle',
					),
				)
			);

			$self->end_controls_tab();

		$self->end_controls_tabs();

		$self->add_control(
			'style_heading_dot',
			array(
				'label'     => esc_html__( 'Dots', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$self->add_control(
			'show_dots',
			array(
				'label'       => esc_html__( 'Show Dots', 'alpha-core' ),
				'description' => esc_html__( 'Determine whether to show/hide slider dots.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
			)
		);

		$dot_default = array(
			'show_dots' => 'yes',
		);
		if ( 'use_as' == $condition_key ) {

			$self->add_control(
				'enable_thumb',
				array(
					'label'       => esc_html__( 'Enable Dot Thumbnail Image', 'alpha-core' ),
					'description' => esc_html__( 'Enable to use thumbnail dots image.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'show_dots' => 'yes',
						'col_cnt'   => 1,
					),
				)
			);

			$self->add_control(
				'thumbs',
				array(
					'label'       => esc_html__( 'Add Thumbnails', 'alpha-core' ),
					'description' => esc_html__( 'Choose thumbnail images which represent each slides.', 'alpha-core' ),
					'type'        => Controls_Manager::GALLERY,
					'default'     => array(),
					'show_label'  => false,
					'condition'   => array(
						'enable_thumb' => 'yes',
						'show_dots'    => 'yes',
					),
				)
			);

			$self->add_responsive_control(
				'dots_thumb_spacing',
				array(
					'label'      => esc_html__( 'Dots Spacing', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'default'    => array(
						'unit' => 'px',
						'size' => '25',
					),
					'size_units' => array(
						'px',
						'%',
					),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => -200,
							'max'  => 200,
						),
						'%'  => array(
							'step' => 1,
							'min'  => -100,
							'max'  => 100,
						),
					),
					'condition'  => array(
						'enable_thumb' => 'yes',
						'show_dots'    => 'yes',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .slider-thumb-dots .slider-pagination-bullet' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
				)
			);

			$dot_default = array(
				'enable_thumb!' => 'yes',
				'show_dots'     => 'yes',
			);
		}

		$self->add_control(
			'dots_skin',
			array(
				'label'       => esc_html__( 'Dots Skin', 'alpha-core' ),
				'description' => esc_html__( 'Controls the dots skin.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''      => esc_html__( 'Default', 'alpha-core' ),
					'white' => esc_html__( 'White', 'alpha-core' ),
					'grey'  => esc_html__( 'Grey', 'alpha-core' ),
					'dark'  => esc_html__( 'Dark', 'alpha-core' ),
				),
				'condition'   => $dot_default,
			)
		);

		if ( 'section' == $self->get_name() ) {
			$self->add_control(
				'dots_pos',
				array(
					'label'       => esc_html__( 'Dots Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose position of slider dots and image dots.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'inner',
					'options'     => array(
						'inner'  => esc_html__( 'Inner', 'alpha-core' ),
						'custom' => esc_html__( 'Custom', 'alpha-core' ),
					),
					'condition'   => array(
						'show_dots' => 'yes',
					),
				)
			);
		} else {
			$self->add_control(
				'dots_pos',
				array(
					'label'       => esc_html__( 'Dots Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose position of slider dots and image dots.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						'inner'  => esc_html__( 'Inner', 'alpha-core' ),
						''       => esc_html__( 'Close', 'alpha-core' ),
						'outer'  => esc_html__( 'Outer', 'alpha-core' ),
						'custom' => esc_html__( 'Custom', 'alpha-core' ),
					),
					'condition'   => array(
						'show_dots' => 'yes',
					),
				)
			);
		}

		$self->add_responsive_control(
			'dots_h_position',
			array(
				'label'      => esc_html__( 'Dot Vertical Position', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '25',
				),
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => -200,
						'max'  => 200,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .slider-pagination' => 'display: flex; position: absolute; bottom: {{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} .slider-thumb-dots' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'dots_pos'  => 'custom',
					'show_dots' => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'dots_v_position',
			array(
				'label'      => esc_html__( 'Dot Horizontal Position', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => '%',
					'size' => '50',
				),
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => -200,
						'max'  => 200,
					),
					'%'  => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .slider-pagination' => 'display: flex; position: absolute; left: {{SIZE}}{{UNIT}}; transform: translateX(-50%);',
					'.elementor-element-{{ID}} .slider-thumb-dots' => "margin-{$left}: {{SIZE}}{{UNIT}};",
				),
				'condition'  => array(
					'dots_pos'  => 'custom',
					'show_dots' => 'yes',
				),
			)
		);

		$self->add_responsive_control(
			'slider_dots_size',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Dots Size', 'alpha-core' ),
				'description' => esc_html__( 'Controls the size of slider dots.', 'alpha-core' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 5,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-pagination .slider-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'.elementor-element-{{ID}} .slider-pagination .slider-pagination-bullet.active' => 'width: calc({{SIZE}}{{UNIT}} * 2.25); height: {{SIZE}}{{UNIT}}',
					'.elementor-element-{{ID}} .slider-thumb-dots .slider-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'.elementor-element-{{ID}} .slider-pagination ~ .slider-thumb-dots' => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2)',
				),
				'condition'   => array(
					'show_dots' => 'yes',
				),
			)
		);

		$self->add_control(
			'dot_back_color',
			array(
				'label'     => esc_html__( 'Background Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .slider-container .slider-pagination .slider-pagination-bullet' => 'background-color: {{VALUE}};',
				),
				'condition' => $dot_default,
			)
		);

		$self->add_control(
			'dot_back_color_hover',
			array(
				'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .slider-container .slider-pagination .slider-pagination-bullet:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => $dot_default,
			)
		);

		$self->end_controls_section();
	}
}

/**
 * Elementor content-template for slider.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_slider_template' ) ) {
	function alpha_elementor_slider_template() {
		$max_breakpoints = alpha_get_breakpoints();
		$kit             = get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 );
		if ( $kit ) {
			$site_settings = get_post_meta( get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 ), '_elementor_page_settings', true );
			$gutters       = array(
				'min' => ! empty( $site_settings['gutter_space_mobile']['size'] ) ? $site_settings['gutter_space_mobile']['size'] : '',
				'sm'  => ! empty( $site_settings['gutter_space_mobile_extra']['size'] ) ? $site_settings['gutter_space_mobile_extra']['size'] : '',
				'md'  => ! empty( $site_settings['gutter_space_tablet']['size'] ) ? $site_settings['gutter_space_tablet']['size'] : '',
				'lg'  => ! empty( $site_settings['gutter_space_tablet_extra']['size'] ) ? $site_settings['gutter_space_tablet_extra']['size'] : '',
				'xl'  => ! empty( $site_settings['gutter_space_laptop']['size'] ) ? $site_settings['gutter_space_laptop']['size'] : '',
				'xlg' => ! empty( $site_settings['gutter_space']['size'] ) ? $site_settings['gutter_space']['size'] : '',
				'xxl' => ! empty( $site_settings['gutter_space_widescreen']['size'] ) ? $site_settings['gutter_space_widescreen']['size'] : '',
			);
		}
		wp_enqueue_script( 'swiper' );
		?>
		var breakpoints = <?php echo json_encode( $max_breakpoints ); ?>;
		var gutters = <?php echo json_encode( $gutters ); ?>;
		var extra_options = {};

		extra_class += ' slider-wrapper';

		// Layout
		if ( 'lg' == settings.col_sp || 'xs' == settings.col_sp || 'sm' == settings.col_sp || 'no' == settings.col_sp ) {
			extra_class += ' gutter-' + settings.col_sp;
		}

		var col_cnt = 'function' == typeof alpha_get_responsive_cols ? alpha_get_responsive_cols({
			xxl: typeof settings.col_cnt_widescreen != 'undefined' ? settings.col_cnt_widescreen : 0, 
			xlg: typeof settings.col_cnt != 'undefined' ? settings.col_cnt : 0,
			xl: typeof settings.col_cnt_laptop != 'undefined' ? settings.col_cnt_laptop : 0,
			lg: typeof settings.col_cnt_tablet_extra != 'undefined' ? settings.col_cnt_tablet_extra : 0,
			md: typeof settings.col_cnt_tablet != 'undefined' ? settings.col_cnt_tablet : 0,
			sm: typeof settings.col_cnt_mobile_extra != 'undefined' ? settings.col_cnt_mobile_extra : 0,
			min: typeof settings.col_cnt_mobile != 'undefined' ? settings.col_cnt_mobile : 0,
		}) : {
			xxl: typeof settings.col_cnt_widescreen != 'undefined' ? settings.col_cnt_widescreen : 0,
			xlg: typeof settings.col_cnt != 'undefined' ? settings.col_cnt : 0,
			xl: typeof settings.col_cnt_laptop != 'undefined' ? settings.col_cnt_laptop : 0,
			lg: typeof settings.col_cnt_tablet_extra != 'undefined' ? settings.col_cnt_tablet_extra : 0,
			md: typeof settings.col_cnt_tablet != 'undefined' ? settings.col_cnt_tablet : 0,
			sm: typeof settings.col_cnt_mobile_extra != 'undefined' ? settings.col_cnt_mobile_extra : 0,
			min: typeof settings.col_cnt_mobile != 'undefined' ? settings.col_cnt_mobile : 0,
		};
		extra_class += ' ' + alpha_get_col_class( col_cnt );

		// Nav & Dot

		var statusClass = '';

		if ( 'full' == settings.nav_type ) {
			statusClass += ' slider-nav-full';
		} else {
			if ( 'circle' == settings.nav_type ) {
				statusClass += ' slider-nav-circle';
			}
			if ( 'top' == settings.nav_pos ) {
				statusClass += ' slider-nav-top';
			} else if ( 'bottom' == settings.nav_pos ) {
				statusClass += ' slider-nav-bottom';
			} else if ( 'inner' != settings.nav_pos ) {
				statusClass += ' slider-nav-outer';
			}
		}
		if ( 'yes' == settings.nav_hide ) {
			statusClass += ' slider-nav-fade';
		}
		if ( settings.dots_type ) {
			statusClass += ' slider-dots-' + settings.dots_type;
		}
		if ( settings.dots_skin ) {
			statusClass += ' slider-dots-' + settings.dots_skin;
		}
		if ( 'inner' == settings.dots_pos ) {
			statusClass += ' slider-dots-inner';
		}
		if ( 'outer' == settings.dots_pos ) {
			statusClass += ' slider-dots-outer';
		}
		if ( 'yes' == settings.fullheight ) {
			statusClass += ' slider-full-height';
		}
		if ( 'yes' == settings.box_shadow_slider ) {
			statusClass += ' slider-shadow';
		}
		if ( 'yes' == settings.scale_drag && 'yes' != settings.disable_mouse_drag ) {
			statusClass += ' slider-scale-shrink';
		}
		if ( 'yes' == settings.focus_on_active ) {
			if ( 'scale' == settings.focus_effect ) {
				statusClass += ' slider-zoom-in-active-slide';
			} else if ( 'opacity' == settings.focus_effect ) {
				statusClass += ' slider-active-slide-opacity';
			} else if ( 'scale_opacity' == settings.focus_effect ) {
				statusClass += ' slider-zoom-in-active-slide slider-active-slide-opacity';
			}
		}

		if ( 'top' == settings.slider_vertical_align ||
			'middle' == settings.slider_vertical_align ||
			'bottom' == settings.slider_vertical_align ||
			'same-height' == settings.slider_vertical_align ) {
			statusClass += ' slider-' + settings.slider_vertical_align;
		}

		extra_options['navigation'] = 'yes' == settings.show_nav;
		extra_options['pagination'] = 'yes' == settings.show_dots;
		if ( settings.col_sp ) {
			if ( 'sm' == settings.col_sp ) {
				extra_options['spaceBetween'] = 10;
			}
			else if ( 'lg' == settings.col_sp ) {
				extra_options['spaceBetween'] = 30;
			}
			else if ( 'xs' == settings.col_sp ) {
				extra_options['spaceBetween'] = 2;
			}
			else if ( ( 'md' == settings.col_sp ) || ( '' == settings.col_sp && '' == settings.col_sp_custom.size ) ) {
				extra_options['spaceBetween'] = 20;
			} else {
				extra_options['spaceBetween'] = 0;
			}
		} else if ('undefined' != typeof settings.col_sp_custom && settings.col_sp_custom.size !== '') {
			extra_options['spaceBetween'] = settings.col_sp_custom.size;
		} else if ('undefined' != typeof settings.col_sp_custom_laptop && settings.col_sp_custom_laptop.size !== '') {
			extra_options['spaceBetween'] = settings.col_sp_custom_laptop.size;
		} else if ('undefined' != typeof settings.col_sp_custom_tablet_extra && settings.col_sp_custom_tablet_extra.size !== '') {
			extra_options['spaceBetween'] = settings.col_sp_custom_tablet_extra.size;
		} else if ('undefined' != typeof gutters.xlg && gutters.xlg !== '') {
			extra_options['spaceBetween'] = gutters.xlg;
		} else if ('undefined' != typeof gutters.xl && gutters.xl !== '') {
			extra_options['spaceBetween'] = gutters.xl;
		} else if ('undefined' != typeof gutters.lg && gutters.lg !== '') {
			extra_options['spaceBetween'] = gutters.lg;
		} else {
			extra_options['spaceBetween'] = 30;
		}
		extra_options['spaceBetween'] = elementorFrontend.hooks.applyFilters('alpha_slider_gap', extra_options['spaceBetween'], settings.col_sp);

		if ( 'yes' == settings.centered ) {
			extra_options['centeredSlides'] = true;
		}
		if( 'yes' == settings.loop ) {
			extra_options['loop'] = true;
		}
		if ( 'yes' == settings.autoplay ) {
			extra_options['autoplay'] = true;
			extra_options['autoplayHoverPause'] = true;
		}
		if ( 5000 != settings.autoplay_timeout ) {
			extra_options['autoplayTimeout'] = settings.autoplay_timeout;
		}
		if ( 'yes' == settings.autoheight) {
			extra_options['autoHeight'] = true;
		}
		if ( 'yes' == settings.autoheight) {
			extra_options['autoHeight'] = true;
		}
		if( 'yes' == settings.disable_mouse_drag ) {
			extra_options['allowTouchMove'] = false;
		}
		if ( settings.effect ) {
			extra_options['effect'] = settings.effect;
		}
		if ( settings.speed ) {
			extra_options['speed'] = settings.speed;
		}

		if ( 'yes' == settings.enable_thumb ) {
			extra_options['dotsContainer'] = 'preview';
		}

		var responsive = {};
		var w = {
			min: 'mobile',
			sm: 'mobile_extra',
			md: 'tablet',
			lg: 'tablet_extra',
			xl: 'laptop',
			xlg: '',
			xxl: 'widescreen',
		};

		for ( var key in w ) {
			if ('undefined' != typeof col_cnt[key] && 'undefined' != typeof breakpoints[ key ]) {
				responsive[ breakpoints[ key ] ] = {
					slidesPerView: col_cnt[key]
				}
			}
			var device = w[key];
			if ( w[key] ) {
				device = '_' + device;
			}
			if ( !settings.col_sp ) {
				if ( 'undefined' != typeof settings['col_sp_custom' + device] && settings['col_sp_custom' + device].size !== '' ) {
					if ( 'undefined' == typeof responsive[ breakpoints[ key ] ] ) {
						responsive[ breakpoints[ key ] ] = {spaceBetween: ''};
					}
					responsive[ breakpoints[ key ] ]['spaceBetween'] = settings['col_sp_custom' + device].size;
				} else if ( 'undefined' != typeof gutters[ device ] && gutters[ device ] !== '' ) {
					if ( 'undefined' == typeof responsive[ breakpoints[ key ] ] ) {
						responsive[ breakpoints[ key ] ] = {spaceBetween: ''};
					}
					responsive[ breakpoints[ key ] ]['spaceBetween'] = gutters[ device ];
				}
			}
		}
		extra_options['statusClass'] = statusClass;

		if ( col_cnt.xlg ) {
			extra_options['slidesPerView'] = col_cnt.xlg;
		} else if ( col_cnt.xl ) {
			extra_options['slidesPerView'] = col_cnt.xl;
		} else if ( col_cnt.lg ) {
			extra_options['slidesPerView'] = col_cnt.lg;
		}
		extra_options.breakpoints = responsive;

		extra_attrs += ' data-slider-options="' + JSON.stringify( extra_options ).replaceAll('"', '\'') + '"';
		<?php
	}
}
