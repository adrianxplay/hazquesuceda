<?php

/**
 * Alpha Product Catalog class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @version    4.1
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Catalog' ) ) {

	/**
	 * Alpha Product Catalog Class
	 *
	 * @since 4.0
	 */
	class Alpha_Product_Catalog extends Alpha_Base {

		public $show_info = array( 'title', 'wishlist', 'quickview', 'compare' );

		/**
		 * Main Class constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			add_filter( 'alpha_customize_fields', array( $this, 'add_catalog_customize_fields' ) );
			if ( function_exists( 'alpha_set_default_option' ) ) {
				alpha_set_default_option( 'catalog_mode', false );
				alpha_set_default_option( 'catalog_price', true );
				alpha_set_default_option( 'catalog_cart', false );
				alpha_set_default_option( 'catalog_review', false );
			}
			add_action(
				'wp',
				function() {
					if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'catalog_mode' ) ) {
						$this->set_catalog_mode();
					}
				}
			);

		}

		/**
		 * Add fields for shop catalog mode
		 *
		 * @param {Array} $fields
		 *
		 * @param {Array} $fields
		 *
		 * @since 4.0
		 */
		public function add_catalog_customize_fields( $fields ) {
			$fields['cs_catalog_mode'] = array(
				'section'  => 'woocommerce_product_catalog',
				'type'     => 'custom',
				'label'    => '',
				'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Theme', 'alpha-core' ) . '</h3>',
				'priority' => '0',
			);

			$fields['catalog_mode'] = array(
				'section'     => 'woocommerce_product_catalog',
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Catalog Mode', 'alpha-core' ),
				'description' => esc_html__( 'Catalog mode is generally used to hide some product fields such as product price and add to cart button on shop and product detail page.', 'alpha-core' ),
				'priority'    => '0',
			);

			$fields['catalog_price'] = array(
				'section'         => 'woocommerce_product_catalog',
				'type'            => 'toggle',
				'label'           => esc_html__( 'Show Price', 'alpha-core' ),
				'active_callback' => array(
					array(
						'setting'  => 'catalog_mode',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority'        => '0',
			);

			$fields['catalog_cart'] = array(
				'section'         => 'woocommerce_product_catalog',
				'type'            => 'toggle',
				'label'           => esc_html__( 'Show Add to Cart Button', 'alpha-core' ),
				'active_callback' => array(
					array(
						'setting'  => 'catalog_mode',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority'        => '0',
			);

			$fields['catalog_review'] = array(
				'section'         => 'woocommerce_product_catalog',
				'type'            => 'toggle',
				'label'           => esc_html__( 'Show Product Review', 'alpha-core' ),
				'active_callback' => array(
					array(
						'setting'  => 'catalog_mode',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority'        => '0',
			);

			$fields['cs_woocommerce_catalog_mode'] = array(
				'section'  => 'woocommerce_product_catalog',
				'type'     => 'custom',
				'label'    => '',
				'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Woocommerce', 'alpha-core' ) . '</h3>',
				'priority' => '0',
			);

			return $fields;
		}

		/**
		 * Set Woocommerce Loop Props
		 *
		 * @since 4.0
		 */
		public function set_catalog_mode() {

			// Products Archive
			if ( alpha_get_option( 'catalog_price' ) ) {
				array_push( $this->show_info, 'price' );
			}
			if ( alpha_get_option( 'catalog_cart' ) ) {
				array_push( $this->show_info, 'addtocart' );
			}
			if ( alpha_get_option( 'catalog_review' ) ) {
				array_push( $this->show_info, 'rating' );
			}

			// Single Product
			if ( ! alpha_get_option( 'catalog_cart' ) ) {
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}
			if ( ! alpha_get_option( 'catalog_price' ) ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 9 );
			}
			if ( ! alpha_get_option( 'catalog_review' ) ) {
				add_filter( 'woocommerce_product_tabs', array( $this, 'remove_woocommerce_review_tabs' ), 98 );
				add_filter( 'pre_option_woocommerce_enable_review_rating', array( $this, 'disable_woocommerce_rating' ) );
			}

			// Set show info of widget products
			add_filter( 'alpha_get_widget_products_show_info', array( $this, 'get_widget_products_prop' ) );
			add_filter(
				'alpha_get_shop_products_show_info',
				function( $show_info ) {
					return array_intersect( $this->show_info, $show_info );
				}
			);
		}


		/**
		 * Set widget products show info
		 *
		 * @param {Array} $show_info
		 *
		 * @return {Array} $show_info
		 *
		 * @since 4.0
		 */
		public function get_widget_products_prop( $show_info ) {

			if ( alpha_wc_get_loop_prop( 'widget' ) || 'widget' == alpha_wc_get_loop_prop( 'product_type' ) ) {
				$show_info = array_intersect(
					$this->show_info,
					$show_info
				);
			}

			return $show_info;
		}


		/**
		 * Remove review tab from single product page
		 *
		 * @param {array} $tabs
		 *
		 * @return {array} $tabs
		 *
		 * @since 4.0
		 */
		public function remove_woocommerce_review_tabs( $tabs ) {
			unset( $tabs['reviews'] );
			return $tabs;
		}


		/**
		 * Disable feature to leave review for product by user
		 *
		 * @since 4.0
		 */
		public function disable_woocommerce_rating( $false ) {
			return 'no';
		}

	}
}

Alpha_Product_Catalog::get_instance();
