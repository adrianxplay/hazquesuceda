<?php
/**
 * Tab Partial
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Alpha_Controls_Manager;

/**
 * Register elementor tab layout controls
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_tab_layout_controls' ) ) {
	function alpha_elementor_tab_layout_controls( $self, $condition_key = '' ) {

		$self->add_control(
			'tab_type',
			array_merge(
				array(
					'label'       => esc_html__( 'Tab Type', 'alpha-core' ),
					'description' => esc_html__( 'Choose from 4 tab types. Choose from Default, Underline, Solid and Border.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'default',
					'width'       => 1,
					'options'     => array(
						'default'   => esc_html__( 'Default', 'alpha-core' ),
						'underline' => esc_html__( 'Underline', 'alpha-core' ),
						'solid'     => esc_html__( 'Solid', 'alpha-core' ),
						'border'    => esc_html__( 'Border', 'alpha-core' ),
					),
				),
				$condition_key ? array(
					'condition' => array(
						$condition_key => 'tab',
					),
				) : array()
			)
		);

		$self->add_control(
			'tab_layout',
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
				),
				$condition_key ? array(
					'condition' => array(
						$condition_key => 'tab',
					),
				) : array()
			)
		);

		$self->add_control(
			'tab_justify',
			array_merge(
				array(
					'label'       => esc_html__( 'Justify Navs', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => '',
					'description' => esc_html__( 'Set to make tab navs have 100% full width.', 'alpha-core' ),
				),
				$condition_key ? array(
					'condition' => array(
						$condition_key => 'tab',
						'tab_layout'   => '',
					),
				) : array(
					'condition' => array(
						'tab_layout' => '',
					),
				)
			)
		);

		$self->add_responsive_control(
			'tab_v_nav_width',
			array_merge(
				array(
					'label'       => esc_html__( 'Nav width', 'alpha-core' ),
					'description' => esc_html__( 'Controls nav width of vertical tab.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'%',
					),
					'range'       => array(
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
					'selectors'   => array(
						'.elementor-element-{{ID}} .tab-vertical' => '--alpha-tab-nav-width: {{SIZE}}{{UNIT}};',
					),
				),
				$condition_key ? array(
					'condition' => array(
						$condition_key => 'tab',
						'tab_layout'   => 'vertical',
					),
				) : array(
					'condition' => array(
						'tab_layout' => 'vertical',
					),
				)
			)
		);

		$self->add_responsive_control(
			'tab_navs_pos',
			array_merge(
				array(
					'label'       => esc_html__( 'Navs Position', 'alpha-core' ),
					'description' => esc_html__( 'Controls alignment of tab titles. Choose from Start, Center, End.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						'flex-start' => array(
							'title' => esc_html__( 'Start', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'     => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'flex-end'   => array(
							'title' => esc_html__( 'End', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-tabs' => 'justify-content: {{VALUE}};',
						'.elementor-element-{{ID}} .tab-nav-fill .nav-link' => 'justify-content: {{VALUE}};',
					),
				),
				$condition_key ? array(
					'condition' => array(
						$condition_key => 'tab',
						'tab_layout'   => '',
						'tab_justify!' => 'yes',
					),
				) : array(
					'condition' => array(
						'tab_layout' => '',
						'tab_justify!' => 'yes',
					),
				)
			)
		);

		$self->add_responsive_control(
			'tab_navs_ver_pos',
			array_merge(
				array(
					'label'       => esc_html__( 'Navs Position', 'alpha-core' ),
					'description' => esc_html__( 'Controls alignment of tab titles. Choose from Start, Center, End.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
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
						'tab_layout'   => 'vertical',
					),
				) : array(
					'condition' => array(
						'tab_layout' => 'vertical',
					),
				)
			)
		);
	}
}

/**
 * Register elementor tab style controls
 */
if ( ! function_exists( 'alpha_elementor_tab_style_controls' ) ) {
	function alpha_elementor_tab_style_controls( $self, $condition_key = '' ) {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

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

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_nav_typography',
				'label'    => esc_html__( 'Nav Typography', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .nav-item',
			)
		);

		$self->add_control(
			'tab_nav_space',
			array(
				'label'       => esc_html__( 'Nav Space', 'alpha-core' ),
				'description' => esc_html__( 'Set the space between tab titles.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'rem', 'em' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .tab' => '--alpha-tab-item-spacing: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$self->add_control(
			'tab_nav_border_radius',
			array(
				'label'       => esc_html__( 'Nav Border Radius', 'alpha-core' ),
				'description' => esc_html__( 'Set the border radius of tab titles.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-title-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$self->add_control(
			'tab_nav_border_width',
			array(
				'label'       => esc_html__( 'Title Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Set the underline(overline) width of tab titles.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'rem', 'em' ),
				'range'       => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 1,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 1,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-nav-border-width: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'tab_type' => array( 'underline', 'border' ),
				),
			)
		);

		$self->add_control(
			'tab_border_width',
			array(
				'label'       => esc_html__( 'Tab Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Set the border width of tab.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'rem', 'em' ),
				'range'       => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 1,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 1,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-border-width: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'tab_type' => array( 'underline', 'border' ),
				),
			)
		);

		$self->add_responsive_control(
			'tab_nav_padding',
			array(
				'label'       => esc_html__( 'Nav Padding', 'alpha-core' ),
				'description' => esc_html__( 'Set the padding value of tab titles.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-title-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_control(
			'tab_border_color',
			array(
				'label'       => esc_html__( 'Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the border color skin of tab.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-border-color: {{VALUE}};',
				),
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
			'tab_color',
			array(
				'label'       => esc_html__( 'Nav Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the normal color skin of tab titles.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .nav-link' => 'color: {{VALUE}};',
					'.elementor-element-{{ID}} .nav-link svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'tab_bg_color',
			array(
				'label'       => esc_html__( 'Nav Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the background color of tab.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-background: {{VALUE}};',
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
			'tab_active_color',
			array(
				'label'       => esc_html__( 'Nav Active Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the active color skin of tab titles.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-active-color: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'tab_active_bg_color',
			array(
				'label'       => esc_html__( 'Nav Active Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the active background color of tab.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-active-background: {{VALUE}};',
				),
			)
		);

		$self->end_controls_tab();

		$self->end_controls_tabs();

		$self->add_responsive_control(
			'tab_content_padding',
			array(
				'label'       => esc_html__( 'Content Padding', 'alpha-core' ),
				'description' => esc_html__( 'Set the padding value of tab content.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .tab' => '--alpha-tab-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->end_controls_section();
	}
}
