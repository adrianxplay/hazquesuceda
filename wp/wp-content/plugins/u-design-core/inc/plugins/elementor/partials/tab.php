<?php
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

/**
 * Register elementor tab layout controls
 */
function alpha_elementor_tab_layout_controls( $self, $condition_key = '' ) {

	$self->add_control(
		'tab_type',
		array_merge(
			array(
				'label'       => esc_html__( 'Nav Arrange', 'alpha-core' ),
				'description' => esc_html__( 'Determine whether to arrange tab navs horizontally or vertically.', 'alpha-core' ),
				'default'     => '',
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					''         => array(
						'title' => esc_html__( 'Horizontal', 'alpha-core' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'vertical' => array(
						'title' => esc_html__( 'Vertical', 'alpha-core' ),
						'icon'  => 'eicon-ellipsis-v',
					),
				),
				'toggle'      => false,
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
				),
			) : array()
		)
	);

	$self->add_responsive_control(
		'tab_nav_width',
		array_merge(
			array(
				'label'      => esc_html__( 'Nav width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 20,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .tab-vertical .nav' => '--alpha-tab-nav-width: {{SIZE}}{{UNIT}};',
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_type'     => 'vertical',
				),
			) : array(
				'condition' => array(
					'tab_type' => 'vertical',
				),
			)
		)
	);

	$self->add_responsive_control(
		'tab_navs_pos',
		array_merge(
			array(
				'label'     => esc_html__( 'Tab Navs Alignment', 'alpha-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .nav' => 'justify-content: {{VALUE}};',
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_type'     => '',
				),
			) : array(
				'condition' => array(
					'tab_type' => '',
				),
			)
		)
	);

	$self->add_responsive_control(
		'tab_navs_pos_vertical',
		array_merge(
			array(
				'label'     => esc_html__( 'Tab Navs Alignment', 'alpha-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'alpha-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'alpha-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'alpha-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .nav-tabs' => 'justify-content: {{VALUE}};',
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_type'     => 'vertical',
				),
			) : array(
				'condition' => array(
					'tab_type' => 'vertical',
				),
			)
		)
	);

	$self->add_control(
		'tab_h_type',
		array_merge(
			array(
				'label'   => esc_html__( 'Tab Type', 'alpha-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''           => esc_html__( 'Default', 'alpha-core' ),
					'solid'      => esc_html__( 'Solid', 'alpha-core' ),
					'simple'     => esc_html__( 'Simple', 'alpha-core' ),
					'underline1' => esc_html__( 'Underline 1', 'alpha-core' ),
					'underline2' => esc_html__( 'Underline 2', 'alpha-core' ),
					'underline3' => esc_html__( 'Underline 3', 'alpha-core' ),
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_type'     => '',
				),
			) : array(
				'condition' => array(
					'tab_type' => '',
				),
			)
		)
	);

	$self->add_control(
		'tab_v_type',
		array_merge(
			array(
				'label'   => esc_html__( 'Tab Type', 'alpha-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => esc_html__( 'Default', 'alpha-core' ),
					'solid'     => esc_html__( 'Solid', 'alpha-core' ),
					'underline' => esc_html__( 'Underline', 'alpha-core' ),
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_type'     => 'vertical',
				),
			) : array(
				'condition' => array(
					'tab_type' => 'vertical',
				),
			)
		)
	);

	$self->add_control(
		'underline_width',
		array(
			'label'      => esc_html__( 'Underline Thickness (px)', 'alpha-core' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array(
				'px',
			),
			'conditions' => array(
				'relation' => 'or',
				'terms'    => array(
					array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'tab_type',
								'operator' => '==',
								'value'    => '',
							),
							array(
								'name'     => 'tab_h_type',
								'operator' => 'in',
								'value'    => array( 'underline1', 'underline2', 'underline3' ),
							),
						),
					),
					array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'tab_type',
								'operator' => '==',
								'value'    => 'vertical',
							),
							array(
								'name'     => 'tab_v_type',
								'operator' => '==',
								'value'    => 'underline',
							),
						),
					),
				),
			),
			'selectors'  => array(
				'.elementor-element-{{ID}} .tab-nav-underline' => '--alpha-tab-nav-border-width: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$self->add_control(
		'show_separator',
		array(
			'type'      => Controls_Manager::SWITCHER,
			'label'     => esc_html__( 'Show Vertical Divider', 'alpha-core' ),
			'separator' => 'before',
			'condition' => array(
				'tab_type' => '',
			),
		)
	);
	$self->add_control(
		'separator_color',
		array(
			'label'     => esc_html__( 'Color', 'alpha-core' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => array(
				'tab_type'       => '',
				'show_separator' => 'yes',
			),
			'selectors' => array(
				'.elementor-element-{{ID}} .tab-nav-separated' => '--alpha-tab-separator-color: {{VALUE}};',
			),
		)
	);
}

/**
 * Register elementor tab style controls
 */
function alpha_elementor_tab_style_controls( $self, $condition_key = '' ) {
	$self->start_controls_section(
		'tab_style',
		array_merge(
			array(
				'label' => alpha_elementor_panel_heading( esc_html__( 'Tab', 'alpha-core' ) ),
				'tab'   => Controls_Manager::TAB_STYLE,
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
				),
			) : array()
		)
	);

	$self->add_control(
		'navs_wrapper_heading',
		array(
			'label' => esc_html__( 'Navs Wrapper', 'alpha-core' ),
			'type'  => Controls_Manager::HEADING,
		)
	);

	$self->add_responsive_control(
		'nav_margin',
		array(
			'label'      => esc_html__( 'Margin', 'alpha-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array(
				'px',
				'%',
			),
			'selectors'  => array(
				'.elementor-element-{{ID}} .nav.nav-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);
	$self->add_responsive_control(
		'nav_padding',
		array(
			'label'      => esc_html__( 'Padding', 'alpha-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array(
				'px',
				'%',
			),
			'selectors'  => array(
				'.elementor-element-{{ID}} .nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);
	$self->add_responsive_control(
		'nav_border',
		array(
			'label'      => esc_html__( 'Border Width', 'alpha-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array(
				'px',
				'%',
			),
			'selectors'  => array(
				'{{WRAPPER}} .nav' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
			),
		)
	);
	$self->add_control(
		'nav_box_color',
		array(
			'label'     => esc_html__( 'Border Color', 'alpha-core' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				'.elementor-element-{{ID}} .nav' => 'border-color: {{VALUE}};',
			),
		)
	);

	$self->add_control(
		'nav_item_heading',
		array(
			'label'     => esc_html__( 'Nav Item', 'alpha-core' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		)
	);

	$self->add_control(
		'tab_br',
		array_merge(
			array(
				'label'      => esc_html__( 'Border Radius (px)', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .nav .nav-link' => '--alpha-tab-title-radius: {{SIZE}}{{UNIT}};',
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_h_type'   => array(
						'',
						'solid',
						'simple',
					),
				),
			) : array()
		)
	);
	$self->add_responsive_control(
		'nav_item_spacing',
		array(
			'label'      => esc_html__( 'Spacing', 'alpha-core' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array(
				'px',
				'%',
				'rem',
			),
			'selectors'  => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-item-spacing: calc({{SIZE}}{{UNIT}} / 2);',
			),
			'condition'  => array(
				'tab_type' => '',
			),
		)
	);

	$self->add_responsive_control(
		'nav_item_spacing_vertical',
		array(
			'label'      => esc_html__( 'Spacing', 'alpha-core' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array(
				'px',
				'%',
				'rem',
			),
			'selectors'  => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-item-spacing: {{SIZE}}{{UNIT}};',
			),
			'condition'  => array(
				'tab_type' => 'vertical',
			),
		)
	);

	$self->add_responsive_control(
		'nav_item_padding',
		array(
			'label'      => esc_html__( 'Padding', 'alpha-core' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array(
				'px',
				'%',
			),
			'selectors'  => array(
				'.elementor-element-{{ID}} .nav .nav-link' => '--alpha-tab-title-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$self->add_group_control(
		Group_Control_Typography::get_type(),
		array(
			'name'     => 'tab_nav_typography',
			'label'    => esc_html__( 'Typography', 'alpha-core' ),
			'selector' => '.elementor-element-{{ID}} .nav .nav-item',
		)
	);

	$self->add_control(
		'nav_bd',
		array_merge(
			array(
				'label'     => esc_html__( 'Border Width (px)', 'alpha-core' ),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array(
					'.elementor-element-{{ID}} .nav-link' => 'border-width: {{VALUE}}px; border-style: solid',
				),
			),
			$condition_key ? array(
				'condition' => array(
					$condition_key => 'tab',
					'tab_h_type'   => array(
						'',
						'solid',
						'simple',
					),
				),
			) : array()
		)
	);

	$self->start_controls_tabs( 'tabs_bg_color' );

	$self->start_controls_tab(
		'tab_color_normal',
		array(
			'label' => esc_html__( 'Normal', 'alpha-core' ),
		)
	);

	$self->add_control(
		'color',
		array(
			'label'       => esc_html__( 'Color', 'alpha-core' ),
			'description' => esc_html__( 'Set the normal color skin of tab titles.', 'alpha-core' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-color: {{VALUE}};',
			),
		)
	);

	$self->add_control(
		'bg_color',
		array(
			'label'       => esc_html__( 'Background Color', 'alpha-core' ),
			'description' => esc_html__( 'Set the background color of tab.', 'alpha-core' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-background: {{VALUE}};',
			),
			'condition'   => array(
				'tab_h_type' => array(
					'',
					'solid',
					'simple',
				),
			),
		)
	);

	$self->add_control(
		'nav_bd_color',
		array(
			'label'       => esc_html__( 'Border Color', 'alpha-core' ),
			'description' => esc_html__( 'Set the border color of tab.', 'alpha-core' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-border-color: {{VALUE}};',
			),
			'conditions'  => array(
				'relation' => 'or',
				'terms'    => array(
					array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'tab_type',
								'operator' => '==',
								'value'    => '',
							),
							array(
								'name'     => 'tab_h_type',
								'operator' => '!in',
								'value'    => array( 'underline1', 'underline2', 'underline3' ),
							),
						),
					),
					array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'tab_type',
								'operator' => '==',
								'value'    => 'vertical',
							),
							array(
								'name'     => 'tab_v_type',
								'operator' => '!=',
								'value'    => 'underline',
							),
						),
					),
				),
			),
		)
	);

	$self->end_controls_tab();

	$self->start_controls_tab(
		'tab_color_active',
		array(
			'label' => esc_html__( 'Active', 'alpha-core' ),
		)
	);

	$self->add_control(
		'color_active',
		array(
			'label'       => esc_html__( 'Active Color', 'alpha-core' ),
			'description' => esc_html__( 'Set the active color skin of tab titles.', 'alpha-core' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-active-color: {{VALUE}};',
			),
		)
	);

	$self->add_control(
		'bg_color_active',
		array(
			'label'       => esc_html__( 'Active Background Color', 'alpha-core' ),
			'description' => esc_html__( 'Set the active background color of tab.', 'alpha-core' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-active-background: {{VALUE}};',
			),
			'condition'   => array(
				'tab_h_type' => array(
					'',
					'solid',
					'simple',
				),
			),
		)
	);

	$self->add_control(
		'nav_bd_active_color',
		array(
			'label'       => esc_html__( 'Active Border Color', 'alpha-core' ),
			'description' => esc_html__( 'Set the active border color of tab.', 'alpha-core' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => array(
				'.elementor-element-{{ID}} .tab' => '--alpha-tab-active-border-color: {{VALUE}};',
				'.elementor-element-{{ID}} .tab-nav-underline' => '--alpha-tab-border-color: {{VALUE}};',
			),
		)
	);

	$self->end_controls_tab();

	$self->end_controls_tabs();

	$self->end_controls_section();
}
