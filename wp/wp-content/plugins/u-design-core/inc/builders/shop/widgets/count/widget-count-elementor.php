<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Count Per Page Widget
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
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
		$right = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'section_count',
			array(
				'label' => esc_html__( 'Count Per Page', 'alpha-core' ),
			)
		);

			$this->add_control(
				'notice_count',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( esc_html__( 'You can customize showing number of products in %1$sCustomize Panel/Woocommerce/Shop%2$s', 'alpha-core' ), '<a href="' . wp_customize_url() . '#products_archive" data-target="products_archive" data-type="section" target="_blank">', '</a>.' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_label',
			array(
				'label' => esc_html( 'Label', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'label_color',
				array(
					'label'       => esc_html__( 'Label Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls color of label.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} label' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'label_typography',
					'label'    => esc_html__( 'Label Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} label',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_style',
			array(
				'label' => esc_html__( 'Select', 'alpha-core' ),
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

			$this->add_control(
				'item_space',
				array(
					'label'       => esc_html__( 'Space', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => array(
						'size' => 10,
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .toolbox-show-count label' => "margin-{$right}: {{SIZE}}px",
					),
					'description' => esc_html__( 'Controls space between label and select box.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		if ( apply_filters( 'alpha_shop_builder_set_preview', false ) ) {
			alpha_wc_count_per_page();
		}
		do_action( 'alpha_shop_builder_unset_preview' );
	}

	protected function content_template() {}
}
