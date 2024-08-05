<?php
/**
 * Alpha Header Elementor Logo
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

class Alpha_Logo_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_header_site_logo';
	}

	public function get_title() {
		return esc_html__( 'Site Logo', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-logo';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'alpha', 'header', 'logo', 'site' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_logo_content',
			array(
				'label' => esc_html__( 'Site Logo', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'logo_align',
			array(
				'label'       => esc_html__( 'Alignment', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => 'left',
				'description' => esc_html__( 'Controls the horizontal alignment of site logo.', 'alpha-core' ),
				'selectors'   => array(
					'.elementor-element-{{ID}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_logo' );

			$this->start_controls_tab(
				'tab_logo_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

				$this->add_responsive_control(
					'logo_width',
					array(
						'label'       => esc_html__( 'Width', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array( 'px', 'rem' ),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 10,
								'max'  => 300,
							),
							'rem' => array(
								'step' => 0.5,
								'min'  => 1,
								'max'  => 30,
							),
						),
						'description' => esc_html__( 'Set the width of site logo.', 'alpha-core' ),
						'selectors'   => array(
							'.elementor-element-{{ID}} .logo img' => 'width: {{SIZE}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'logo_max_width',
					array(
						'label'       => esc_html__( 'Max Width', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array( 'px', 'rem' ),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 10,
								'max'  => 300,
							),
							'rem' => array(
								'step' => 0.5,
								'min'  => 1,
								'max'  => 30,
							),
						),
						'description' => esc_html__( 'Set the max-width of site logo.', 'alpha-core' ),
						'selectors'   => array(
							'.elementor-element-{{ID}} .logo img' => 'max-width: {{SIZE}}{{UNIT}};',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_logo_sticky',
				array(
					'label' => esc_html__( 'In Sticky', 'alpha-core' ),
				)
			);

				$this->add_responsive_control(
					'logo_width_sticky',
					array(
						'label'       => esc_html__( 'Width in Sticky', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array( 'px', 'rem' ),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 10,
								'max'  => 300,
							),
							'rem' => array(
								'step' => 0.5,
								'min'  => 1,
								'max'  => 30,
							),
						),
						'description' => esc_html__( 'Set the width of site logo on sticky section.', 'alpha-core' ),
						'selectors'   => array(
							'.fixed .elementor-element-{{ID}} .logo img' => 'width: {{SIZE}}{{UNIT}};',
						),
					)
				);

				$this->add_responsive_control(
					'logo_max_width_sticky',
					array(
						'label'       => esc_html__( 'Max Width in Sticky', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array( 'px', 'rem' ),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 10,
								'max'  => 300,
							),
							'rem' => array(
								'step' => 0.5,
								'min'  => 1,
								'max'  => 30,
							),
						),
						'description' => esc_html__( 'Set the max-width of site logo on sticky section.', 'alpha-core' ),
						'selectors'   => array(
							'.fixed .elementor-element-{{ID}} .logo img' => 'max-width: {{SIZE}}{{UNIT}};',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/logo/render-logo.php' );
	}
}
