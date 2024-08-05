<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Count Per Page Widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

class Alpha_Shop_Count_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_shop_widget_count';
	}

	public function get_title() {
		return esc_html__( 'Count Per Page', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_shop_widget' );
	}

	public function get_keywords() {
		return array( 'per-page', 'shop', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-pencil';
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_count',
			array(
				'label' => esc_html__( 'Count Per Page', 'alpha-core' ),
			)
		);

			$this->add_control(
				'count_select',
				array(
					'label'       => esc_html__( 'Count Select', 'alpha-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'e.g: 9, _12, 24, 36', 'alpha-core' ),
					'description' => esc_html__( 'Please input comma separated integers. Every integers will be shown as option of select box in product archive page. Integer with prefix "_" will be default count. e.g: 9, _12, 24, 36', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_style',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'select_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .form-control' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'select_typography',
					'selector' => '.elementor-element-{{ID}} .form-control',
				)
			);

			$this->add_responsive_control(
				'select_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_shop_builder_set_preview', false ) ) {
			alpha_set_loop_prop( 'products_count_select', ! empty( $atts['count_select'] ) ? $atts['count_select'] : '9, _12, 24, 36' );
			alpha_wc_count_per_page();
		}
		do_action( 'alpha_shop_builder_unset_preview' );
	}

	protected function content_template() {}
}
