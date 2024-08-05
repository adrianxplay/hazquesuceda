<?php
/**
 * @author     Andon
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    4.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Unit' ) ) {

	/**
	 * Alpha Product Unit Feature Class
	 */
	class Alpha_Product_Unit extends Alpha_Base {

		public $show_info = array( 'title' );

		/**
		 * Main Class construct
		 *
		 * @since 4.0.0
		 */
		public function __construct() {
			add_filter( 'alpha_customize_sections', array( $this, 'add_product_unit_customize_section' ) );
			add_filter( 'alpha_customize_fields', array( $this, 'add_product_unit_customize_fields' ) );
			add_action( 'admin_menu', array( $this, 'create_unit_attribute' ) );
		}

		public function create_unit_attribute() {
			$attributes = wc_get_attribute_taxonomies();
			if ( alpha_get_option( 'product_unit' ) ) {
				// check_admin_referer( 'woocommerce-add-new_attribute' );

				if ( in_array( 'unit', array_column( $attributes, 'attribute_name' ) ) ) {
					return;
				}

				$args = array(
					'name'         => esc_html__( 'Unit', 'alpha-core' ),
					'slug'         => 'unit',
					'type'         => 'select',
					'order_by'     => 'menu_order',
					'has_archives' => '0',
				);

				$id = wc_create_attribute( $args );

				if ( is_wp_error( $id ) ) {
					echo '<div id="woocommerce_errors" class="error"><p>' . alpha_strip_script_tags( $id->get_error_message() ) . '</p></div>';
				}
			} else {
				$attr_names = array_column( $attributes, 'attribute_name' );
				if ( in_array( 'unit', $attr_names ) ) {
					$attribute_ids = array_column( $attributes, 'attribute_id' );
					$idx           = array_search( 'unit', $attr_names );
					$delete_id     = $attribute_ids[ $idx ];
					wc_delete_attribute( $delete_id );
				}
			}
		}

		/**
		 * Add product unit feature to custoimzer
		 *
		 * @param {Array} $sections
		 *
		 * @return {Array} $sections
		 *
		 * @since 4.0.0
		 */
		public function add_product_unit_customize_section( $sections ) {
			$sections['product_unit'] = array(
				'title'    => esc_html__( 'Product Unit', 'alpha-core' ),
				'panel'    => 'features',
				'priority' => 91,
			);

			return $sections;
		}

		/**
		 * Add fields to customizer related to product unit
		 *
		 * @param {Array} $fields
		 *
		 * @return {Array} $fields
		 *
		 * @since 4.0.0
		 */
		public function add_product_unit_customize_fields( $fields ) {
			$fields = array_merge(
				$fields,
				array(
					'cs_product_unit_about_title' => array(
						'section' => 'product_unit',
						'type'    => 'custom',
						'label'   => '',
						'default' => '<h3 class="options-custom-title option-feature-title">' . esc_html__( 'About This Feature', 'alpha-core' ) . '</h3>',
					),
					'cs_product_unit_about_desc'  => array(
						'section' => 'product_unit',
						'type'    => 'custom',
						'label'   => esc_html__( 'This feature creates unit attribute automatically for customers to set products\' sell unit.', 'alpha-core' ),
						'default' => '<p class="options-custom-description option-feature-description"><img class="description-image" src="' . ALPHA_ASSETS . '/images/admin/customizer/product-unit.jpg' . '" alt="' . esc_html__( 'Theme Option Descrpition Image', 'alpha-core' ) . '"></p>',
					),
					'cs_product_unit_title'       => array(
						'section' => 'product_unit',
						'type'    => 'custom',
						'label'   => '',
						'default' => '<h3 class="options-custom-title">' . esc_html__( 'Product Unit', 'alpha-core' ) . '</h3>',
					),
					'product_unit'                => array(
						'section' => 'product_unit',
						'type'    => 'toggle',
						'label'   => esc_html__( 'Enable Product Unit', 'alpha-core' ),
						'tooltip' => esc_html__( 'Product\'s unit will be shown just after price.', 'alpha-core' ) . sprintf( esc_html__( 'You can add product\'s unit attribues %1$shere%2$s.', 'alpha-core' ), '<a target="_blank" href="' . esc_url( admin_url( 'edit.php?post_type=product&page=product_attributes' ) ) . '">', '</a>' ),
					),
				)
			);

			return $fields;
		}
	}
}

Alpha_Product_Unit::get_instance();
