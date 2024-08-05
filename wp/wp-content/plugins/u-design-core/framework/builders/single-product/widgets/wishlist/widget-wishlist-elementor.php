<?php
/**
 * Alpha Single Product Elementor Wishlist
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Wishlist_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return  ALPHA_NAME . '_sproduct_wishlist';
	}

	public function get_title() {
		return esc_html__( 'Product Wishlist', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon ' . ALPHA_ICON_PREFIX . '-icon-heart';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'wishlist' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_wishlist_style',
			array(
				'label' => esc_html__( 'Wishlist', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'wishlist_icon',
				array(
					'label'      => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a::before' => 'font-size: {{SIZE}}px;',
					),
				)
			);

			$this->add_control(
				'wishlist_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'wishlist_hover_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .yith-wcwl-add-to-wishlist a:hover' => 'color: {{VALUE}};',
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

			echo do_shortcode( '[yith_wcwl_add_to_wishlist container_classes="btn-product-icon"]' );

			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
