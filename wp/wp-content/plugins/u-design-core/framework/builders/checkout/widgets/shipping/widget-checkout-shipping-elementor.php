<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shipping Widget
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

class Alpha_Checkout_Shipping_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_checkout_widget_shipping';
	}

	public function get_title() {
		return esc_html__( 'Checkout Shipping', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_checkout_widget' );
	}

	public function get_keywords() {
		return array( 'shipping', 'checkout', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-checkout-shipping';
	}

	public function get_script_depends() {
		$depends = array( 'selectWoo', 'wc-country-select' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_billing_heading',
			array(
				'label' => esc_html( 'Heading', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'heading_shipping',
				array(
					'label' => esc_html__( 'Shipping', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'shipping_heading_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .woocommerce-shipping-fields > h3',
				)
			);
			$this->add_responsive_control(
				'shipping_heading_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-shipping-fields > h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'heading_additional',
				array(
					'label'     => esc_html__( 'Additional', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'additional_heading_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .woocommerce-additional-fields > h3',
				)
			);
			$this->add_responsive_control(
				'additional_heading_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .woocommerce-additional-fields > h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_billing_label',
			array(
				'label' => esc_html( 'Label', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'label_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .form-row label',
				)
			);
			$this->add_control(
				'label_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_coupons_style',
			array(
				'label' => esc_html__( 'Form Field', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'input_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .input-text, {{WRAPPER}} .select2 .select2-selection__rendered',
				)
			);

			$this->add_control(
				'form_spacing_between',
				array(
					'label'       => esc_html__( 'Space Between', 'alpha-core' ),
					'description' => esc_html__( 'Control spacing between form fields.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem' ),
					'selectors'   => array(
						'{{WRAPPER}} p.form-row:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'field_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Control the padding of form fields.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'{{WRAPPER}} .input-text, {{WRAPPER}} .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'input_tab_color' );
				$this->start_controls_tab(
					'tab_input_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'input_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the text color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'{{WRAPPER}} .input-text, {{WRAPPER}} .select2 .select2-selection__rendered' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the background color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'{{WRAPPER}} .input-text, {{WRAPPER}} .select2 .select2-selection--single' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_bd_color',
						array(
							'label'       => esc_html__( 'Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the border color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'{{WRAPPER}} .input-text, {{WRAPPER}} .select2 .select2-selection--single' => 'border-color: {{VALUE}};transition: border-color .3s;',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_input_focus',
					array(
						'label' => esc_html__( 'Focus', 'alpha-core' ),
					)
				);
					$this->add_control(
						'input_focus_color',
						array(
							'label'       => esc_html__( 'Focus Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the text color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'{{WRAPPER}} .input-text:focus, {{WRAPPER}} .select2 .select2-selection--single:focus' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_focus_bg_color',
						array(
							'label'       => esc_html__( 'Focus Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the background color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'{{WRAPPER}} .input-text:focus, {{WRAPPER}} .select2 .select2-selection--single:focus' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_focus_bd',
						array(
							'label'       => esc_html__( 'Focus Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Control the border color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'{{WRAPPER}} .input-text:focus, {{WRAPPER}} .select2 .select2-selection--single:focus' => 'border-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {

		$atts = $this->get_settings_for_display();

		if ( apply_filters( 'alpha_checkout_builder_set_preview', false ) ) {
			$checkout = WC()->checkout();
			$checkout->checkout_form_shipping();
		}
		do_action( 'alpha_checkout_builder_unset_preview' );
	}

	protected function content_template() {}
}
