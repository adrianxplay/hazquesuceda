<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Breadcrumb Widget
 *
 * Alpha Widget to display WC breadcrumb.
 *
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 * @author     D-THEMES
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

class Alpha_Breadcrumb_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_breadcrumb';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumb', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-breadcrumb';
	}

	public function get_keywords() {
		return array( 'breadcrumb', 'alpha' );
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_breadcrumb',
			array(
				'label' => esc_html__( 'Breadcrumb', 'alpha-core' ),
			)
		);

			$this->add_control(
				'delimiter',
				array(
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Delimiter Text', 'alpha-core' ),
					'description' => esc_html__( 'Input breadcrumb delimiter.', 'alpha-core' ),
					'placeholder' => esc_html__( '/', 'alpha-core' ),
					'condition'   => array(
						'delimiter_icon[value]' => '',
					),
				)
			);

			$this->add_control(
				'delimiter_icon',
				array(
					'label'       => esc_html__( 'Delimiter Icon', 'alpha-core' ),
					'description' => esc_html__( 'Show breadcrumb delimiter as icon or svg.', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
					'condition'   => array(
						'delimiter' => '',
					),
				)
			);

			$this->add_control(
				'home_icon',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Show Home Icon', 'alpha-core' ),
					'description' => esc_html__( 'Shows home as icon.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_breadcrumb_style',
			array(
				'label'       => esc_html__( 'Breadcrumb Style', 'alpha-core' ),
				'description' => esc_html__( 'Controls typography of breadcrumb.', 'alpha-core' ),
				'tab'         => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'breadcrumb_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .breadcrumb',
				)
			);

			$this->add_responsive_control(
				'align',
				array(
					'label'                => esc_html__( 'Align', 'alpha-core' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => array(
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
					'selectors_dictionary' => array(
						'flex-start' => 'justify-content: flex-start; text-align: ' . $left . ';',
						'center'     => 'justify-content: center; text-align: center',
						'flex-end'   => 'justify-content: flex-end; text-align: ' . $right . ';',
					),
					'selectors'            => array(
						'.elementor-element-{{ID}} .breadcrumb' => '{{VALUE}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_link_col' );
				$this->start_controls_tab(
					'tab_link_col',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'link_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the color of breadcrumb.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .breadcrumb' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .breadcrumb .breadcrumb-comma' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .breadcrumb a' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .breadcrumb .delimiter' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .breadcrumb .delimiter svg' => 'fill: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_link_col_active',
					array(
						'label' => esc_html__( 'Active', 'alpha-core' ),
					)
				);

					$this->add_control(
						'link_color_active',
						array(
							'label'       => esc_html__( 'Active Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the active color of breadcrumb.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .breadcrumb' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .breadcrumb a' => 'opacity: 1;',
								'.elementor-element-{{ID}} .breadcrumb a:hover' => 'color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_control(
				'delimiter_size',
				array(
					'label'       => esc_html__( 'Delimiter Size', 'alpha-core' ),
					'description' => esc_html__( 'Input delimiter size.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units'  => array(
						'px',
						'%',
						'rem',
					),
					'separator'   => 'before',
					'selectors'   => array(
						'{{WRAPPER}} .delimiter' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'delimiter_space',
				array(
					'label'       => esc_html__( 'Delimiter Space', 'alpha-core' ),
					'description' => esc_html__( 'Controls delimiters` front and back spaces.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units'  => array(
						'px',
						'rem',
					),
					'selectors'   => array(
						'{{WRAPPER}} .delimiter' => 'margin: 0 {{SIZE}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/breadcrumb/render-breadcrumb.php' );
	}

	protected function content_template() {}
}
