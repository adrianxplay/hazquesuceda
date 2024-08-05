<?php
/**
 * Alpha Single Product Elementor Compare
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Compare_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_compare';
	}

	public function get_title() {
		return esc_html__( 'Product Compare', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon ' . ALPHA_ICON_PREFIX . '-icon-compare';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'compare' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'compare_available' ) ) {
			wp_register_script( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
			$depends[] = 'alpha-product-compare';
		}
		return $depends;
	}

	public function get_style_depends() {
		$depends = array();
		if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'compare_available' ) ) {
			wp_register_style( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			$depends[] = 'alpha-product-compare';
		}
		return $depends;
	}
	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_compare_style',
			array(
				'label' => esc_html__( 'Compare', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'compare_icon',
				array(
					'label'      => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .compare' => 'font-size: {{SIZE}}px;',
					),
				)
			);

			$this->add_control(
				'compare_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .compare' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'compare_hover_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .compare:hover' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		/**
		 * Filters post products in single product builder
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {

			$settings = $this->get_settings_for_display();

			alpha_single_product_compare();

			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
