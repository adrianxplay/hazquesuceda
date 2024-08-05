<?php
/**
 * Alpha WPForm Addon Class
 *
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 * @version    4.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Core_WPForms extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {

		// Wpforms default options
		add_filter( 'wpforms_setting', array( $this, 'default_wpform_settings' ), 10, 4 );
		add_filter( 'wpforms_get_render_engine', array( $this, 'default_wpform_render_engine' ) );

		// Elementor controls
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'elementor/element/wpforms/section_display/after_section_end', array( $this, 'extend_elementor_widget_controls' ) );
			add_action( 'elementor/frontend/widget/before_render', array( $this, 'extend_elementor_widget_render' ) );
			if ( alpha_is_elementor_preview() ) {
				add_action( 'elementor/widget/before_render_content', array( $this, 'extend_elementor_widget_preview' ) );
			}
		}
	}

	/**
	 * Customize wpforms default settings
	 *
	 * @since 4.0
	 */
	public function default_wpform_settings( $value, $key, $default, $option ) {
		$options = get_option( $option, false );
		if ( 'modern-markup-is-set' == $key ) {
			$value = is_array( $options ) && ! empty( $options[ $key ] ) ? wp_unslash( $options[ $key ] ) : false;
		} elseif ( 'modern-markup' == $key ) {
			$value = is_array( $options ) && ! empty( $options[ $key ] ) ? wp_unslash( $options[ $key ] ) : false;
		}

		return $value;
	}

	public function default_wpform_render_engine() {
		return 'classic';
	}

	/**
	 * Add controls for wpforms elementor widget
	 *
	 * @since 4.0
	 */
	public function extend_elementor_widget_controls( $self ) {

		$self->start_controls_section(
			'section_alpha_form_fields',
			array(
				/* translators: %s represents theme name.*/
				'label' => sprintf( esc_html__( 'Form Fields', 'alpha-core' ), ALPHA_DISPLAY_NAME ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$self->add_control(
				'control_size',
				array(
					'label'   => esc_html__( 'Size', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'xs' => esc_html__( 'Extra Small', 'alpha-core' ),
						'sm' => esc_html__( 'Small', 'alpha-core' ),
						''   => esc_html__( 'Medium', 'alpha-core' ),
						'lg' => esc_html__( 'Large', 'alpha-core' ),
					),
				)
			);

			$self->add_control(
				'control_rounded',
				array(
					'label'   => esc_html__( 'Border Style', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						''        => esc_html__( 'Square', 'alpha-core' ),
						'rounded' => esc_html__( 'Rounded', 'alpha-core' ),
						'ellipse' => esc_html__( 'Ellipse', 'alpha-core' ),
						'custom'  => esc_html__( 'Custom', 'alpha-core' ),
					),
				)
			);

			$self->add_responsive_control(
				'control_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'label_floating'  => '',
						'control_rounded' => 'custom',
					),
				)
			);

			$self->add_responsive_control(
				'control_textarea_border_radius',
				array(
					'label'      => esc_html__( 'Textarea Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'label_floating'  => '',
						'control_rounded' => 'custom',
					),
				)
			);

			$self->add_responsive_control(
				'control_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'label_floating' => '',
					),
				)
			);

			$self->add_responsive_control(
				'control_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'label_floating' => '',
					),
				)
			);

			$self->add_control(
				'textarea_height',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => esc_html__( 'The Height of Textarea', 'alpha-core' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 400,
						),
						'em'  => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 40,
						),
						'rem' => array(
							'step' => 0.1,
							'min'  => 0,
							'max'  => 40,
						),
					),
					'default'    => array(
						'unit' => 'px',
					),
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'control_typography',
					'selector' => '{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea',
				)
			);

			$self->start_controls_tabs( 'form_tabs_form' );

			$self->start_controls_tab(
				'form_tab_form_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

				$self->add_control(
					'control_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'control_bg',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							'label_floating' => '',
						),
					)
				);

				$self->add_control(
					'control_bd',
					array(
						'label'     => esc_html__( 'Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea' => 'border-color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'form_tab_form_focus',
				array(
					'label' => esc_html__( 'Focus', 'alpha-core' ),
				)
			);

				$self->add_control(
					'control_active_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input:focus, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select:focus, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea:focus' => 'color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'control_active_bg',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input:focus, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select:focus, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea:focus' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							'label_floating' => '',
						),
					)
				);

				$self->add_control(
					'control_active_bd',
					array(
						'label'     => esc_html__( 'Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field input:focus, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field select:focus, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field textarea:focus' => 'border-color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

			$self->end_controls_tabs();

		$self->end_controls_section();

		$self->start_controls_section(
			'section_alpha_form_fields_label',
			array(
				/* translators: %s represents theme name.*/
				'label' => sprintf( esc_html__( 'Form Fields Label', 'alpha-core' ), ALPHA_DISPLAY_NAME ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$self->add_control(
				'label_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .wpforms-field-label' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'label_typography',
					'selector' => '{{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field-label, {{WRAPPER}} form.wpforms-form .wpforms-field-container .wpforms-field-label-inline',
				)
			);

			$self->add_control(
				'label_floating',
				array(
					'label'       => esc_html__( 'Floating Label', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => '',
					'description' => esc_html__( 'Input and textarea form control\'s labels are floating when they are focused. Do not use placeholders for this option.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'label_floating_color',
				array(
					'label'     => esc_html__( 'Floating Active Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .field-float .wpforms-field-label' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wpforms-field.field-float input, {{WRAPPER}} .wpforms-field.field-float textarea' => 'border-bottom-color: {{VALUE}}; box-shadow: 0 1px {{VALUE}};',
					),
					'condition' => array(
						'label_floating' => 'yes',
					),
				)
			);

		$self->end_controls_section();

		$self->start_controls_section(
			'section_alpha_form_button',
			array(
				/* translators: %s represents theme name.*/
				'label' => sprintf( esc_html__( 'Submit Button', 'alpha-core' ), ALPHA_DISPLAY_NAME ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$self->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'btn_typography',
					'selector' => '.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit',
				)
			);

			$self->add_responsive_control(
				'btn_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->add_responsive_control(
				'btn_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->add_responsive_control(
				'btn_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid',
					),
				)
			);

			$self->add_responsive_control(
				'btn_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->start_controls_tabs( 'form_tabs_btn_cat' );

			$self->start_controls_tab(
				'form_tab_btn_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$self->add_control(
				'form_btn_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'form_btn_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'form_btn_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'form_btn_box_shadow',
					'selector' => '.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit',
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'form_tab_btn_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$self->add_control(
				'form_btn_color_hover',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:hover, .elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:focus' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'form_btn_back_color_hover',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:hover, .elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:focus' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'form_btn_border_color_hover',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:hover, .elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:focus' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'form_btn_box_shadow_hover',
					'selector' => '.elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:hover, .elementor-element-{{ID}} .wpforms-container form.wpforms-form .wpforms-submit-container button.wpforms-submit:focus',
				)
			);

			$self->end_controls_tab();

			$self->end_controls_tabs();

		$self->end_controls_section();

		$self->start_controls_section(
			'section_alpha_form_comfirm',
			array(
				'label' => esc_html__( 'Confirmation', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$self->start_controls_tabs( 'form_tabs_confirm' );

			$self->start_controls_tab(
				'form_tab_confirm_error',
				array(
					'label' => esc_html__( 'Error', 'alpha-core' ),
				)
			);

				$self->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'error_typography',
						'selector' => '.elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container label.wpforms-error',
					)
				);

				$self->add_control(
					'error_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container label.wpforms-error' => 'color: {{VALUE}};',
							'.elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field .wpforms-has-error .choices__inner, .elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field input.user-invalid, .elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field input.wpforms-error, .elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field select.user-invalid, .elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field select.wpforms-error, .elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field textarea.user-invalid, .elementor-element-{{ID}} div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-field textarea.wpforms-error' => 'border-color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'form_tab_confirm_success',
				array(
					'label' => esc_html__( 'Success', 'alpha-core' ),
				)
			);

				$self->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'     => 'success_typography',
						'selector' => '.elementor-element-{{ID}} .wpforms-confirmation-container-full',
					)
				);

				$self->add_control(
					'success_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .wpforms-confirmation-container-full' => 'color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'success_bg_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .wpforms-confirmation-container-full' => 'background-color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'success_border_color',
					array(
						'label'     => esc_html__( 'Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .wpforms-confirmation-container-full' => 'border-color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

			$self->end_controls_tabs();

		$self->end_controls_section();

	}

	/**
	 * Add controls classes for frontend
	 *
	 * @since 4.0
	 */
	public function extend_elementor_widget_render( $self ) {

		if ( 'wpforms' == $self->get_name() ) {

			$settings = $self->get_settings_for_display();

			if ( ! empty( $settings['control_rounded'] ) ) {
				$self->add_render_attribute(
					array(
						'_wrapper' => array(
							'class' => 'controls-' . $settings['control_rounded'],
						),
					)
				);
			}

			if ( ! empty( $settings['control_size'] ) ) {
				$self->add_render_attribute(
					array(
						'_wrapper' => array(
							'class' => 'controls-' . $settings['control_size'],
						),
					)
				);
			}

			if ( ! empty( $settings['label_floating'] ) ) {
				$self->add_render_attribute(
					array(
						'_wrapper' => array(
							'class' => 'label-floating',
						),
					)
				);
			}
		}
	}

	/**
	 * Add controls classes for preview.
	 *
	 * @since 4.0
	 */
	public function extend_elementor_widget_preview( $self ) {
		if ( 'wpforms' == $self->get_name() ) {
			$settings = $self->get_settings_for_display();

			if ( 'elementor/widget/before_render_content' ) {
				echo '<div class="d-none alpha-elementor-widget-options" data-options="' . esc_attr(
					json_encode(
						array(
							'rounded'        => $settings['control_rounded'],
							'size'           => $settings['control_size'],
							'label_floating' => $settings['label_floating'],
						)
					)
				) . '"></div>';
			}
		}
	}
}

/**
 * Create instance
 */
Alpha_Core_WPForms::get_instance();
