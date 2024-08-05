<?php
/**
 * Alpha Elementor Single Product Title Widget
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Title_Elementor_Widget extends Alpha_Heading_Elementor_Widget {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_title';
	}

	public function get_title() {
		return esc_html__( 'Product Title', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-title';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'name', 'title' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {
		parent::register_controls();

		$this->remove_control( 'title' );
		$this->add_control(
			'border_height',
			array(
				'label'     => esc_html__( 'Decoration Height (px)', 'alpha-core' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'.elementor-element-{{ID}} .title::before, .elementor-element-{{ID}} .title::after ' => 'height: {{SIZE}}px;',
				),
				'condition' => array(
					'decoration' => 'cross',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'decoration_spacing',
				),
			)
		);
		$this->add_control(
			'underline_width',
			array(
				'label'     => esc_html__( 'Underline Width (px)', 'alpha-core' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'.elementor-element-{{ID}} .title-underline .title::after' => 'height: {{SIZE}}px;',
				),
				'condition' => array(
					'decoration' => 'underline',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'decoration_spacing',
				),
			)
		);
		$this->add_control(
			'underline_color',
			array(
				'label'     => esc_html__( 'Underline Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .title-underline .title::after' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'decoration' => 'underline',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'decoration_spacing',
				),
			)
		);
	}

	protected function render() {
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
			global $product;

			$atts          = $this->get_settings_for_display();
			$atts['self']  = $this;
			$atts['title'] = $product->get_name();
			$atts['class'] = 'product_title entry-title';

			$this->add_inline_editing_attributes( 'link_label' );

			require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/heading/render-heading-elementor.php' );

			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
