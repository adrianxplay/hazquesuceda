<?php
/**
 * Alpha Scroll Navigation widget
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.4.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Alpha_Controls_Manager;

class Alpha_Scroll_Nav_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_scroll_nav';
	}

	public function get_title() {
		return esc_html__( 'Scroll Navigation', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-slider-full-screen';
	}

	public function get_style_depends() {
		wp_register_style( 'alpha-scroll-nav', alpha_core_framework_uri( '/widgets/scroll-nav/scroll-nav' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-scroll-nav' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-scroll-nav', alpha_core_framework_uri( '/widgets/scroll-nav/scroll-nav' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		$depends = array( 'swiper-lib', 'alpha-scroll-nav' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'scroll_navigation_content',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

			$repeater = new Repeater();

			$repeater->add_control(
				'navigator_block',
				array(
					'label'       => esc_html__( 'Select a Block', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'options'     => 'block',
					'label_block' => true,
				)
			);

			$repeater->add_control(
				'navigator_tooltip',
				array(
					'label' => esc_html__( 'Tooltip Text', 'alpha-core' ),
					'type'  => Controls_Manager::TEXT,
				)
			);

			$this->add_control(
				'navigator_content_list',
				[
					'label'       => esc_html__( 'Navigation Items', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '<# print( navigator_tooltip ? navigator_tooltip : "Block Template" ) #>',
				]
			);

		$this->end_controls_section(); // Content Section End

		// Slider Options Section Start
		$this->start_controls_section(
			'scroll_navigation_slider_options',
			array(
				'label' => esc_html__( 'Layout', 'alpha-core' ),
			)
		);

			$this->add_control(
				'slider_direction',
				array(
					'label'   => esc_html__( 'Direction', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'vertical',
					'options' => array(
						'horizontal' => esc_html__( 'Horizontal', 'alpha-core' ),
						'vertical'   => esc_html__( 'Vertical', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'slider_height',
				array(
					'label'   => esc_html__( 'Height', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'full_screen',
					'options' => array(
						'full_screen'   => esc_html__( 'Full Screen', 'alpha-core' ),
						'custom_height' => esc_html__( 'Custom', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'slider_container_height',
				array(
					'label'      => esc_html__( 'Custom Height', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'vh' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 10000,
							'step' => 1,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 300,
					),
					'selectors'  => array(
						'{{WRAPPER}} .scroll-nav-wrapper' => 'height: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'slider_height' => 'custom_height',
					),
				)
			);

			$this->add_control(
				'slider_speed',
				array(
					'label'   => esc_html__( 'Speed', 'alpha-core' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 300,
				)
			);

			$this->add_control(
				'slider_item',
				array(
					'label'   => esc_html__( 'Visible Items Count', 'alpha-core' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 1,
				)
			);

		$this->end_controls_section(); // Slider Options Section End

		// Style tab section

		// Style Navigator style start

		$this->start_controls_section(
			'scroll_navigator_tooltip_style',
			array(
				'label' => esc_html__( 'Tooltip', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'tooltip_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet:before',
				)
			);

			$this->add_responsive_control(
				'tooltip_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Controls padding value of tooltip.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'tooltip_bg',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the background color of the tooltip.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet:before' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'tooltip_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of the tooltip.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet:before' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'scroll_navigator_style',
			array(
				'label' => esc_html__( 'Navigator', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'scroll_navigator_pos',
				array(
					'label'      => esc_html__( 'Position', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 20,
					),
					'selectors'  => array(
						'{{WRAPPER}} .scroll-nav-wrapper.slider-container-vertical + .slider-pagination' => 'right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .scroll-nav-wrapper.slider-container-horizontal + .slider-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'scroll_navigator_width',
				array(
					'label'      => esc_html__( 'Width', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 20,
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'scroll_navigator_active_width',
				array(
					'label'      => esc_html__( 'Active Width', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 20,
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet.active' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'slider_direction' => 'horizontal',
					),
				)
			);

			$this->add_control(
				'scroll_navigator_height',
				array(
					'label'      => esc_html__( 'Height', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 20,
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'scroll_navigator_active_height',
				array(
					'label'      => esc_html__( 'Active Height', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						),
						'%'  => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 20,
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet.active' => 'height: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'slider_direction' => 'vertical',
					),
				)
			);

			$this->add_control(
				'navigator_items_gap',
				array(
					'label'       => esc_html__( 'Gap Spacing (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the spacing between navigator items.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 30,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .scroll-nav-wrapper.slider-container-vertical + .slider-pagination .slider-pagination-bullet' => 'margin: {{SIZE}}{{UNIT}} 0;',
						'{{WRAPPER}} .scroll-nav-wrapper.slider-container-horizontal + .slider-pagination .slider-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'scroll_navigator_style_tabs' );

				// Normal tab Start
				$this->start_controls_tab(
					'scroll_navigator_style_normal_tab',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'     => 'scroll_navigator_background',
							'label'    => esc_html__( 'Background', 'alpha-core' ),
							'types'    => array( 'classic', 'gradient' ),
							'selector' => '.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet',
						)
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						array(
							'name'     => 'scroll_navigator_border',
							'label'    => esc_html__( 'Border', 'alpha-core' ),
							'selector' => '.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet',
						)
					);

					$this->add_responsive_control(
						'scroll_navigator_border_radius',
						array(
							'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
							'type'      => Controls_Manager::DIMENSIONS,
							'selectors' => array(
								'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							),
						)
					);

				$this->end_controls_tab(); // Normal tab end

				// Hover tab Start
				$this->start_controls_tab(
					'scroll_navigator_style_hover_tab',
					array(
						'label' => esc_html__( 'Active', 'alpha-core' ),
					)
				);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						array(
							'name'     => 'scroll_navigator_hover_background',
							'label'    => esc_html__( 'Background', 'alpha-core' ),
							'types'    => array( 'classic', 'gradient' ),
							'selector' => '.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet.active',
						)
					);

					$this->add_group_control(
						Group_Control_Border::get_type(),
						array(
							'name'     => 'scroll_navigator_hover_border',
							'label'    => esc_html__( 'Border', 'alpha-core' ),
							'selector' => '.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet.active',
						)
					);

					$this->add_responsive_control(
						'scroll_navigator_hover_border_radius',
						array(
							'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
							'type'      => Controls_Manager::DIMENSIONS,
							'selectors' => array(
								'.elementor-element-{{ID}} .scroll-nav-wrapper + .slider-pagination .slider-pagination-bullet.active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
							),
						)
					);

				$this->end_controls_tab(); // Hover tab end

			$this->end_controls_tabs();

		$this->end_controls_section(); // Style scroll navigator end

	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/scroll-nav/render-scroll-nav-elementor.php' );
	}
}
