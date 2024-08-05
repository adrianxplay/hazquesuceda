<?php
/**
 * Alpha Cart Shipping Elementor Widget
 *
 * @author     Andon
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;


class Alpha_Cart_Totals_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_cart_totals';
	}

	public function get_title() {
		return esc_html__( 'Cart Totals', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-cart-totals';
	}

	public function get_categories() {
		return array( 'alpha_cart_widget' );
	}

	public function get_keywords() {
		return array( 'woo', 'alpha', 'cart', 'total', 'totals', 'checkout' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_order_shipping',
			array(
				'label' => esc_html( 'Shipping', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'order_shipping',
				array(
					'label'       => esc_html__( 'Show Shipping', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => 'yes',
					'description' => esc_html__( 'You could disable this option when you use Cart Shipping widget.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_heading',
			array(
				'label' => esc_html( 'Heading', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'order_heading_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .cart-information > h3',
				)
			);
			$this->add_responsive_control(
				'order_heading_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .cart-information > h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_table_heading',
			array(
				'label' => esc_html( 'Table Heading', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'order_table_heading_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} tr th',
				)
			);
			$this->add_control(
				'order_table_heading_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} tr th' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_responsive_control(
				'order_table_heading_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_order_table_cell',
			array(
				'label' => esc_html( 'Table Cell', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'oreder_table_cell_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} tr td bdi, .elementor-element-{{ID}} tr td',
				)
			);
			$this->add_control(
				'order_table_cell_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} tr td bdi' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_responsive_control(
				'order_table_cell_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

	}
	protected function render() {

		if ( ! is_object( WC()->cart ) || WC()->cart->is_empty() ) {
			return '';
		}

		$settings = $this->get_settings_for_display();

		WC()->cart->calculate_totals();

		add_filter( 'woocommerce_shipping_show_shipping_calculator', array( $this, 'show_shipping_args' ), 10, 3 );

		echo '<div class="cart_totals_wrap' . esc_attr( 'yes' == $settings['order_shipping'] ? '' : ' shipping-hidden' ) . '">';

		wc_get_template( 'cart/cart-totals.php', array( 'show_shipping_calculator' => 'yes' == $settings['order_shipping'] ) );

		echo '</div>';

		remove_filter( 'woocommerce_shipping_show_shipping_calculator', array( $this, 'show_shipping_args' ), 10 );
	}

	public function show_shipping_args( $first, $i, $package ) {
		$settings = $this->get_settings_for_display();
		return 'yes' == $settings['order_shipping'];
	}
}
