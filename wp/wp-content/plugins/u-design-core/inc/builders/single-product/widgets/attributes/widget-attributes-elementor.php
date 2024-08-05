<?php
/**
 * Alpha Elementor Single Product Attributes Widget
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.3
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Attributes_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_attributes';
	}

	public function get_title() {
		return esc_html__( 'Product Attributes', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-meta-data';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'attributes' );
	}

	public function get_script_depends() {
		$depends = array();
		wp_register_script( 'alpha-product-attribute', ALPHA_CORE_INC_URI . '/builders/single-product/widgets/attributes/product-attribute' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		$depends[] = 'alpha-product-attribute';
		return $depends;
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_product_attributes',
			array(
				'label' => esc_html__( 'Attributes', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			)
		);

			$this->add_control(
				'count',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Attribute Count', 'alpha-core' ),
					'description' => esc_html__( 'Controls number of attributes to display or view more.', 'alpha-core' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
				)
			);

			$this->add_control(
				'viewmore_label',
				array(
					'label'       => esc_html__( 'View More Label', 'alpha-core' ),
					'description' => esc_html__( 'input label of view more button that makes attribute tab active in product data tab.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => esc_html__( 'View All Attributes', 'alpha-core' ),
					'condition'   => array(
						'count!' => '',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_attr_text',
			array(
				'label' => esc_html__( 'Text', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_attr_typo',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} li',
				)
			);

			$this->add_control(
				'sp_attr_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} li' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_attr_icon',
			array(
				'label' => esc_html__( 'Icon', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sp_attr_icon_size',
				array(
					'label'       => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} li i' => 'font-size: {{SIZE}}px;',
					),
					'description' => esc_html__( 'Control the size of icon.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'sp_attr_icon_lineheight',
				array(
					'label'       => esc_html__( 'Icon Line Height', 'alpha-core' ),
					'description' => esc_html__( 'Controls the icon line height.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'em' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .product-attributes li i' => 'line-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sp_attr_icon_space',
				array(
					'label'       => esc_html__( 'Icon Space (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .product-attributes li i' => 'margin-' . $right . ': {{SIZE}}px;',
					),
					'description' => esc_html__( 'Controls the space between icon and text.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'sp_attr_icon_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} li i' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_attr_link',
			array(
				'label' => esc_html__( 'Link', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'sp_attr_link',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .more-attributes',
				)
			);

			$this->start_controls_tabs( 'tabs_attr_link' );

			$this->start_controls_tab(
				'tab_attr_link_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

				$this->add_control(
					'sp_attr_link_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .more-attributes' => 'color: {{VALUE}};',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_attr_link_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

				$this->add_control(
					'sp_attr_link_hover_color',
					array(
						'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .more-attributes:hover' => 'color: {{VALUE}};',
						),
					)
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
			global $product;
			$product_attributes = $product->get_attributes();
			$index              = 1;
			echo '<ul class="product-attributes">';
			foreach ( $product_attributes as $key => $attribute ) {
				if ( $index > $atts['count']['size'] ) {
					break;
				}

				$values = array();

				if ( $attribute->is_taxonomy() ) {
					$attribute_taxonomy = $attribute->get_taxonomy_object();
					$attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

					foreach ( $attribute_values as $attribute_value ) {
						$value_name = esc_html( $attribute_value->name );

						if ( $attribute_taxonomy->attribute_public ) {
							$values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
						} else {
							$values[] = $value_name;
						}
					}
				} else {
					$values = $attribute->get_options();

					foreach ( $values as &$value ) {
						$value = make_clickable( esc_html( $value ) );
					}
				}

				echo '<li><i class="' . THEME_ICON_PREFIX . '-icon-check"></i>' . wc_attribute_label( $attribute->get_name() ) . ': ' . wpautop( wptexturize( implode( ', ', $values ) ) ) . '</li>';

				$index ++;
			}
			echo '</ul>';
			if ( $atts['count']['size'] < count( $product_attributes ) ) {
				echo '<a href="#tab-additional_information" class="btn btn-link more-attributes scroll-to">' . ( $atts['viewmore_label'] ? $atts['viewmore_label'] : esc_html__( 'View All Attributes', 'alpha-core' ) ) . '</a>';
			}
			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
