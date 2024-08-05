<?php
/**
 * Alpha Elementor Single Product Brands Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Brands_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'alpha_sproduct_brands';
	}

	public function get_title() {
		return esc_html__( 'Product Brands', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-brand';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'meta', 'brand', 'brands' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_product_brands',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sp_align',
				array(
					'label'     => esc_html__( 'Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => 'left',
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .product-brands' => 'text-align: {{VALUE}}',
					),
					'toggle'    => false,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'link_typography',
					'selector' => '.elementor-element-{{ID}} .product-brands > a',
				)
			);

			$this->add_control(
				'link_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .product-brands > a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'link_color_hover',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .product-brands > a:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'brand_image_size',
				array(
					'label'      => esc_html__( 'Image Size (px)', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 150,
						),
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .product-brands img' => 'width: {{SIZE}}px;',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		/**
		 * Filters post products in single product builder.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
			global $product;
			if ( function_exists( 'alpha_single_product_brands' ) ) {
				$brands = alpha_single_product_brands( false );
				if ( ! empty( $brands['html'] ) ) {
					echo '<div class="product-brands">';
					if ( ! $brands['has_image'] ) {
						esc_html_e( 'Brands: ', 'alpha-core' );
					}
					echo alpha_escaped( $brands['html'] );
					echo '</div>';
				}
			}
			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
