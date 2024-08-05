<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Order Review Widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

class Alpha_Checkout_Review_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_checkout_widget_review';
	}

	public function get_title() {
		return esc_html__( 'Order Review', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_checkout_widget' );
	}

	public function get_keywords() {
		return array( 'order-review', 'checkout', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-checkout-review';
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
			'section_order_heading',
			array(
				'label' => esc_html( 'Table Heading', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'heading_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .woocommerce-checkout-review-order-table thead th',
				)
			);
			$this->add_control(
				'heading_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-checkout-review-order-table thead th' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'heading_padding',
				array(
					'label'     => esc_html__( 'Padding (px)', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-checkout-review-order-table thead th' => 'padding: {{SIZE}}{{UNIT}} 0;',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_cell',
			array(
				'label' => esc_html( 'Table Cell', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'cell_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .woocommerce-checkout-review-order-table .cart_item td',
				)
			);
			$this->add_control(
				'cell_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-checkout-review-order-table .cart_item td' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'cell_padding',
				array(
					'label'     => esc_html__( 'Padding (px)', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-checkout-review-order-table .cart_item td' => 'padding: {{SIZE}}{{UNIT}} 0;',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_prices',
			array(
				'label' => esc_html( 'Total / Subtotal', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'total_price_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .woocommerce-checkout-review-order-table tfoot th, .elementor-element-{{ID}} .woocommerce-checkout-review-order-table tfoot .woocommerce-Price-amount.amount',
				)
			);
			$this->add_control(
				'total_price_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-checkout-review-order-table tfoot th, .elementor-element-{{ID}} .woocommerce-checkout-review-order-table tfoot .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'total_price_padding',
				array(
					'label'     => esc_html__( 'Padding (px)', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .woocommerce-checkout-review-order-table tfoot th, .elementor-element-{{ID}} .woocommerce-checkout-review-order-table tfoot .woocommerce-Price-amount.amount' => 'padding: {{SIZE}}{{UNIT}} 0;',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {

		$atts = $this->get_settings_for_display();

		if ( apply_filters( 'alpha_checkout_builder_set_preview', false ) ) {
			woocommerce_order_review();
		}
		do_action( 'alpha_checkout_builder_unset_preview' );
	}

	protected function content_template() {}
}
