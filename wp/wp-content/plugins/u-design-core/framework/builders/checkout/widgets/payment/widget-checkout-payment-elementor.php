<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Payment Widget
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

class Alpha_Checkout_Payment_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_checkout_widget_payment';
	}

	public function get_title() {
		return esc_html__( 'Checkout Payment', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_checkout_widget' );
	}

	public function get_keywords() {
		return array( 'payment', 'checkout', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-checkout-payment';
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
			'section_payment_heading',
			array(
				'label' => esc_html( 'Heading', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'button_type',
				array(
					'type'    => Controls_Manager::HIDDEN,
					'default' => '',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'heading_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .woocommerce-checkout-payment > h4',
				)
			);
			$this->add_control(
				'heading_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-checkout-payment > h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_heading',
			array(
				'label' => esc_html( 'Content', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'content_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .payment_methods',
				)
			);

			$this->add_control(
				'cell_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .payment_methods' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'spacing_between',
				array(
					'label'       => esc_html__( 'Space Between', 'alpha-core' ),
					'description' => esc_html__( 'Control spacing between payment methods.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem' ),
					'selectors'   => array(
						'{{WRAPPER}} .payment_methods li:not(:last-child)' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'content_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .payment_methods' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this );
	}

	protected function render() {

		$atts = $this->get_settings_for_display();

		if ( apply_filters( 'alpha_checkout_builder_set_preview', false ) ) {
			woocommerce_checkout_payment();
		}
		do_action( 'alpha_checkout_builder_unset_preview' );
	}

	protected function content_template() {}
}
