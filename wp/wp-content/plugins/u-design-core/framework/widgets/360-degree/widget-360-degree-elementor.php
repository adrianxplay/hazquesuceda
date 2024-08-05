<?php
/**
 * Alpha 360 degree elementor widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Alpha_360_Degree_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_360_degree';
	}

	public function get_title() {
		return esc_html__( '360 Degree View', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-degree';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( '360', 'degree', 'gallery', 'view' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-three-sixty', alpha_core_framework_uri( '/widgets/360-degree/360-degree' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-three-sixty' );
	}

	/**
	 * Get the script depends.
	 *
	 * @since 1.2.0
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-three-sixty', alpha_core_framework_uri( '/widgets/360-degree/360-degree' . ALPHA_JS_SUFFIX ), array( 'three-sixty' ), ALPHA_CORE_VERSION, true );
		return array( 'three-sixty', 'alpha-three-sixty' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_360_degree',
			array(
				'label' => esc_html__( '360 Degree', 'alpha-core' ),
			)
		);

			$this->add_control(
				'images',
				array(
					'label'       => esc_html__( 'Upload Images', 'alpha-core' ),
					'type'        => Controls_Manager::GALLERY,
					'default'     => array(),
					'show_label'  => false,
					'description' => esc_html__( 'Upload bundle of images that you want to show in 360 degree gallery.', 'alpha-core' ),
				)
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'        => 'thumbnail',
					'separator'   => 'none',
					'description' => esc_html__( 'Select fit image size that are suitable for rendering area.', 'alpha-core' ),
					'exclude'     => [ 'custom' ],
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_360_degree_style',
			array(
				'label' => esc_html__( 'Button Settings', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'prev_icon',
				array(
					'label'       => esc_html__( 'Prev / Next Button Size (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls size of prev frame button.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav_bar .nav_bar_previous:before' => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .nav_bar .nav_bar_next:before' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'play_icon',
				array(
					'label'       => esc_html__( 'Play/Pause Button Size (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls size of play/pause button.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav_bar .nav_bar_play:before' => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .nav_bar .nav_bar_stop:before' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'button_responsive_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Control the buttons` padding.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'rem',
						'%',
					),
					'selectors'   => array(
						'{{WRAPPER}} .nav_bar>a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_btn_colors' );

				$this->start_controls_tab(
					'tab_btn_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'btn_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the buttons color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .alpha-360-gallery-wrapper .nav_bar a' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'btn_bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the buttons background color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .alpha-360-gallery-wrapper .nav_bar a' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'btn_bd_color',
						array(
							'label'       => esc_html__( 'Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the buttons border color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .alpha-360-gallery-wrapper .nav_bar a' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_btn_hover',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

					$this->add_control(
						'btn_hover_color',
						array(
							'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the buttons hover color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .alpha-360-gallery-wrapper .nav_bar a:hover' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'btn_hover_bg_color',
						array(
							'label'       => esc_html__( 'Hover Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the buttons hover background color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .alpha-360-gallery-wrapper .nav_bar a:hover' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'btn_hover_bd_color',
						array(
							'label'       => esc_html__( 'Hover Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the buttons hover border color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .alpha-360-gallery-wrapper .nav_bar a:hover' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/360-degree/render-360-degree-elementor.php' );
	}

	protected function content_template() {}
}
