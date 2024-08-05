<?php
/**
 * Alpha Elementor Single Product Data_tab Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 * @since      1.2.0 Added style options
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Alpha_Single_Product_FBT_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_fbt';
	}

	public function get_title() {
		return esc_html__( 'Frequently Bought Together', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-upsell';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'frequently', 'product', 'woocommerce', 'shop', 'bought', 'together' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
			//          $depends[] = 'alpha-pfbt';
		}
		return $depends;
	}

	public function before_render() {
		// Add `elementor-widget-theme-post-content` class to avoid conflicts that figure gets zero margin.
		$this->add_render_attribute(
			array(
				'_wrapper' => array(
					'class' => 'elementor-widget-theme-post-content',
				),
			)
		);

		parent::before_render();
	}


	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_fbt_product_style',
			array(
				'label' => esc_html__( 'Product', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_fbt_product_typo',
					'selector' => '.elementor-element-{{ID}} .product-fbt .product-details',
				)
			);

			$this->add_control(
				'sp_fbt_product_width',
				array(
					'label'     => esc_html__( 'Width', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 180,
							'max'  => 350,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .product-fbt .product-wrap' => 'max-width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'sp_fbt_product_space',
				array(
					'label'     => esc_html__( 'Space Between', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'.elementor-element-{{ID}} .product-fbt .product-wrap:not(:first-child)' => 'margin-' . $left . ': {{SIZE}}{{UNIT}}',
						'.elementor-element-{{ID}} .product-fbt .product-media:before, .elementor-element-{{ID}} .product-fbt .product-media:after' => $left . ': calc(-{{SIZE}}{{UNIT}} / 2)',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_fbt_button_style',
			array(
				'label' => esc_html__( 'Add To Cart', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_btn_typo',
					'selector' => '.elementor-element-{{ID}} .fbt_cart button',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'sp_btn_border',
					'selector' => '.elementor-element-{{ID}} .fbt_cart button',
					'exclude'  => array( 'color' ),
				)
			);

			$this->add_control(
				'sp_btn_border_radius',
				array(
					'label'     => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'      => Controls_Manager::DIMENSIONS,
					'selectors' => array(
						'.elementor-element-{{ID}} .fbt_cart button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'.elementor-element-{{ID}} .fbt_cart button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'sp_btn_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .fbt_cart button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
								'.elementor-element-{{ID}} .fbt_cart button' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .fbt_cart button' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .fbt_cart button' => 'border-color: {{VALUE}}',
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
								'.elementor-element-{{ID}} .fbt_cart button:hover' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_bg_color_hover',
						array(
							'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .fbt_cart button:hover' => 'background-color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'sp_btn_border_color_hover',
						array(
							'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .fbt_cart button:hover' => 'border-color: {{VALUE}}',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {

		/**
		 * Filters post products in single product builder
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {

			if ( class_exists( 'Alpha_Product_Frequently_Bought_Together' ) ) {
				Alpha_Product_Frequently_Bought_Together::get_instance()->fbt_product( false );
			}

			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
