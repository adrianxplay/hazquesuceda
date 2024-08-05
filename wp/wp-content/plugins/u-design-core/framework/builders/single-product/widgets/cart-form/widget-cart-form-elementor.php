<?php
/**
 * Alpha Single Product Elementor Cart Form
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

class Alpha_Single_Product_Cart_Form_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_cart_form';
	}

	public function get_title() {
		return esc_html__( 'Add To Cart', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-add-to-cart';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'cart_form', 'variation' );
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
			'section_cf_content',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

			$this->add_control(
				'sp_sticky',
				array(
					'label'       => esc_html__( 'Sticky Add To Cart', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'Make a selection to show or hide the bottom sticky add to cart.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cf_button_style',
			array(
				'label' => esc_html__( 'Button', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_btn_typo',
					'selector' => '.elementor-element-{{ID}} .cart .button',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'sp_btn_border',
					'selector' => '.elementor-element-{{ID}} .cart .button',
					'exclude'  => array( 'color' ),
				)
			);

			$this->add_control(
				'sp_btn_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'      => Controls_Manager::DIMENSIONS,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'sp_btn_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'sp_btn_style_tabs' );

				$this->start_controls_tab(
					'sp_btn_style_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'sp_btn_text_color',
						array(
							'label'     => esc_html__( 'Text Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .cart .button' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .cart .button' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .cart .button' => 'border-color: {{VALUE}}',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'sp_btn_style_hover',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

					$this->add_control(
						'sp_btn_text_color_hover',
						array(
							'label'     => esc_html__( 'Hover Text Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .cart .button:hover' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_bg_color_hover',
						array(
							'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .cart .button:hover' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_border_color_hover',
						array(
							'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .cart .button:hover' => 'border-color: {{VALUE}}',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cf_quantity_style',
			array(
				'label' => esc_html__( 'Quantity', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'spacing',
				array(
					'label'      => esc_html__( 'Spacing', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'body:not(.rtl) .elementor-element-{{ID}} .quantity' => 'margin-right: {{SIZE}}{{UNIT}}',
						'body.rtl .elementor-element-{{ID}} .quantity' => 'margin-left: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_qty_typo',
					'selector' => '.elementor-element-{{ID}} .quantity',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'sp_qty_border',
					'selector' => '.elementor-element-{{ID}} .quantity .qty',
					'exclude'  => array( 'color' ),
				)
			);

			$this->add_control(
				'sp_qty_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'      => Controls_Manager::DIMENSIONS,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'sp_qty_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'sp_qty_style_tabs' );

			$this->start_controls_tab(
				'sp_qty_style_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$this->add_control(
				'sp_qty_text_color',
				array(
					'label'     => esc_html__( 'Text Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_qty_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_qty_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sp_qty_style_focus',
				array(
					'label' => esc_html__( 'Focus', 'alpha-core' ),
				)
			);

			$this->add_control(
				'sp_qty_text_color_focus',
				array(
					'label'     => esc_html__( 'Focus Text Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty:focus' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_qty_bg_color_focus',
				array(
					'label'     => esc_html__( 'Focus Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty:focus' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_qty_border_color_focus',
				array(
					'label'     => esc_html__( 'Focus Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .quantity .qty:focus' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cf_variations_style',
			array(
				'label' => esc_html__( 'Variations', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sp_variations_spacing',
				array(
					'label'     => esc_html__( 'Spacing', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .variations' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'sp_variations_spacing_between',
				array(
					'label'     => esc_html__( 'Space Between', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .variations>:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'heading_variations_label_style',
				array(
					'label'     => esc_html__( 'Label', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'sp_variations_label_color_focus',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart label' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_variations_label_typo',
					'selector' => '.elementor-element-{{ID}} .cart label',
				)
			);
			$this->add_control(
				'heading_variations_select_style',
				array(
					'label'     => esc_html__( 'Select field', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'sp_variations_select_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart select' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_variations_select_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart select' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_variations_select_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart select' => 'border: 1px solid {{VALUE}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_variations_select_typo',
					'selector' => '.elementor-element-{{ID}} .cart select',
				)
			);

			$this->add_control(
				'sp_variations_select_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart select' => 'border-radius: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'heading_variations_list_style',
				array(
					'label'     => esc_html__( 'Label Swatch Field', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->start_controls_tabs( 'sp_variations_style_tabs' );

				$this->start_controls_tab(
					'sp_variations_style_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'sp_variations_list_color',
						array(
							'label'     => esc_html__( 'Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .variations button.label' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_variations_list_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .variations button.label' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_variations_list_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .variations button.label' => 'border: 1px solid {{VALUE}}',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'sp_variations_style_hover',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'sp_variations_list_color_hover',
						array(
							'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .variations button.label:hover, .elementor-element-{{ID}} .variations button.label.active' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_variations_list_bg_color_hover',
						array(
							'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .variations button.label:hover, .elementor-element-{{ID}} .variations button.label.active' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_variations_list_border_color_hover',
						array(
							'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .variations button.label:hover, .elementor-element-{{ID}} .variations button.label.active' => 'border: 1px solid {{VALUE}}',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_variations_list_typo',
					'selector' => '.elementor-element-{{ID}} .variations button.label',
				)
			);

			$this->add_control(
				'sp_variations_list_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .variations button.label' => 'border-radius: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'heading_variations_swatch_style',
				array(
					'label'     => esc_html__( 'Color Swatch Field', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'sp_variations_swatch_size',
				array(
					'label'     => esc_html__( 'Size', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .variations button:not(.label)' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'sp_variations_swatch_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .variations button:not(.label)' => 'border-radius: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'sp_variations_swatch_icon_size',
				array(
					'label'     => esc_html__( 'Active Icon Size', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .variations button:not(.label):before' => 'font-size: {{SIZE}}{{UNIT}}',
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
			add_filter( 'alpha_single_product_sticky_cart_enabled', 'yes' == $settings['sp_sticky'] ? '__return_true' : '__return_false' );
			add_action(
				'woocommerce_single_variation',
				function() {
					echo '<hr class="product-divider">';
				}
			);
			woocommerce_template_single_add_to_cart();
			remove_filter( 'alpha_single_product_sticky_cart_enabled', 'yes' == $settings['sp_sticky'] ? '__return_true' : '__return_false' );
			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
