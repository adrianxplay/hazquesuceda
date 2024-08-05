<?php
/**
 * Alpha WooCommerce plugin compatibility.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.2.0
 */

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die;

class Alpha_Core_WooCommerce extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_filter( 'alpha_dynamic_tags', array( $this, 'woo_add_tags' ) );
		add_filter( 'alpha_dynamic_field_object', array( $this, 'woo_add_object' ) );
		add_filter( 'alpha_dynamic_extra_fields_content', array( $this, 'woo_render' ), 10, 3 );
		add_action( 'alpha_dynamic_extra_fields', array( $this, 'woo_add_control' ), 10, 3 );
	}

	/**
	 * Render WOO Field
	 *
	 * @since 1.2.0
	 */
	public function woo_render( $result, $settings, $widget = 'field' ) {
		if ( 'woo' == $settings['dynamic_field_source'] ) {
			$widget = 'dynamic_woo_' . $widget;
			$key    = isset( $settings[ $widget ] ) ? $settings[ $widget ] : false;

			$product = wc_get_product();

			if ( ! $product ) {
				return $result;
			}

			if ( 'sales' == $key ) {
				$result = $product->get_total_sales();
			} elseif ( 'excerpt' == $key ) {
				$result = $product->get_short_description();
			} elseif ( 'sku' == $key ) {
				$result = esc_html( $product->get_sku() );
			} elseif ( 'stock' == $key ) {
				$result = $product->get_stock_quantity();
			}
		}

		return $result;
	}

	/**
	 * Add Dynamic Woo Tags
	 *
	 * @since 1.2.0
	 */
	public function woo_add_tags( $tags ) {
		if ( ! alpha_is_elementor_preview() || ( ALPHA_NAME . '_template' == get_post_type() && 'product_layout' == get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true ) ) ) {
			array_push( $tags, 'Alpha_Core_Custom_Field_Woo_Tag' );
		}
		return $tags;
	}

	/**
	 * Add Woo object to Dynamic Field
	 *
	 * @since 1.2.0
	 */
	public function woo_add_object( $objects ) {
		$objects['woo'] = esc_html__( 'WooCommerce', 'alpha-core' );
		return $objects;
	}

	/**
	 * Add control for WOO object
	 *
	 * @since 1.2.0
	 */
	public function woo_add_control( $object, $widget = 'field', $plugin = 'woo' ) {
		if ( 'woo' == $plugin ) {
			$control_key = 'dynamic_woo_' . $widget;
			$object->add_control(
				$control_key,
				array(
					'label'   => esc_html__( 'WOO Field', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'sku',
					'groups'  => $this->get_woo_fields( $widget ),
				)
			);
		}
	}

	/**
	 * Retrieve WOO fields for each group
	 *
	 * @since 1.2.0
	 */
	public function get_woo_fields( $widget ) {

		$fields = array(
			'excerpt' => esc_html__( 'Product Short Description', 'alpha-core' ),
			'sku'     => esc_html__( 'Product SKU', 'alpha-core' ),
			'sales'   => esc_html__( 'Product Sales', 'alpha-core' ),
			'stock'   => esc_html__( 'Product Stock', 'alpha-core' ),
		);

		return $fields;

	}

}

Alpha_Core_WooCommerce::get_instance();
