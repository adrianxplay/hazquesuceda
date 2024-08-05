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


class Alpha_Cart_Shipping_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_cart_shipping';
	}

	public function get_title() {
		return esc_html__( 'Cart Shipping', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-cart-shipping';
	}

	public function get_categories() {
		return array( 'alpha_cart_widget' );
	}

	public function get_keywords() {
		return array( 'woo', 'alpha', 'cart', 'shipping', 'checkout' );
	}

	public function get_script_depends() {
		return array( 'wc-country-select', 'selectWoo' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_coupons_content',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'notice_is_coupon',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'Please add shipping zone %1$shere%2$s.', 'alpha-core' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ) . '" target="_blank">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'button_type',
				array(
					'type'    => Controls_Manager::HIDDEN,
					'default' => '',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_coupons_style',
			array(
				'label' => esc_html__( 'Form Field', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'input_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .form-row .input-text, .elementor-element-{{ID}} .form-row select, .elementor-element-{{ID}} .form-row span',
				)
			);

			$this->add_control(
				'form_spacing',
				array(
					'label'       => esc_html__( 'Spacing', 'alpha-core' ),
					'description' => esc_html__( 'Control spacing between form and button.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .form-row:last-of-type' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
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
						'.elementor-element-{{ID}} .form-row:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
							'description' => esc_html__( 'Controls the text color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text, .elementor-element-{{ID}} .form-row .input-text::placeholder , .elementor-element-{{ID}} .form-row select, .elementor-element-{{ID}} .form-row .select2-container--default .select2-selection--single .select2-selection__rendered, .elementor-element-{{ID}} .form-row .select2-container--default .select2-selection--single .select2-selection__placeholder' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the background color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text, .elementor-element-{{ID}} .form-row select, .elementor-element-{{ID}} .form-row span, .elementor-element-{{ID}} .form-row .select2-container--default .select2-selection--single' => 'background-color: {{VALUE}} !important;',
							),
						)
					);
					$this->add_control(
						'input_bd_color',
						array(
							'label'       => esc_html__( 'Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the border color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text, .elementor-element-{{ID}} .form-row select, .elementor-element-{{ID}} .form-row span, .elementor-element-{{ID}} .form-row .select2-container--default .select2-selection--single' => 'border-color: {{VALUE}};transition: border-color .3s;',
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
							'description' => esc_html__( 'Controls the text color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text:focus, .elementor-element-{{ID}} .form-row .input-text:focus::placeholder, .elementor-element-{{ID}} .form-row select:focus' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_focus_bg_color',
						array(
							'label'       => esc_html__( 'Focus Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the background color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text:focus, .elementor-element-{{ID}} .form-row select:focus' => 'background-color: {{VALUE}} !important;',
							),
						)
					);
					$this->add_control(
						'input_focus_bd',
						array(
							'label'       => esc_html__( 'Focus Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the border color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text:focus, .elementor-element-{{ID}} .form-row select:focus' => 'border-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this, array(), '', '', false, true, true );

	}
	protected function render() {

		if ( ! is_object( WC()->cart ) || ( WC()->cart->is_empty() && ! alpha_is_elementor_preview() ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		wp_enqueue_script( 'wc-country-select' );
		wc_get_template( 'cart/shipping-calculator.php' );
	}
}
