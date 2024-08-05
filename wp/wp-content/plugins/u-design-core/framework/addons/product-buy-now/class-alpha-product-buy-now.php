<?php
/**
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Buy_Now' ) ) {

	/**
	 * Alpha Product Buy Now Feature Class
	 */
	class Alpha_Product_Buy_Now extends Alpha_Base {

		/**
		 * Main Class construct
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'alpha_customize_sections', array( $this, 'add_buy_now_customize_section' ) );
			add_filter( 'alpha_customize_fields', array( $this, 'add_buy_now_customize_fields' ) );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_buy_now_btn' ), 10 );
			add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'redirect_checkout_for_buy_now' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ), 35 );
		}


		/**
		 * Add buy now feature to custoimzer
		 *
		 * @param {Array} $sections
		 *
		 * @return {Array} $sections
		 *
		 * @since 1.0.0
		 */
		public function add_buy_now_customize_section( $sections ) {
			$sections['product_buy_now'] = array(
				'title'    => esc_html__( 'Product Buy Now', 'alpha-core' ),
				'panel'    => 'features',
				'priority' => 35,
			);

			return $sections;
		}


		/**
		 * Add buy now related fields to customizer
		 *
		 * @param {Array} $fields
		 *
		 * @return {Array} $fields
		 *
		 * @since 1.0.0
		 */
		public function add_buy_now_customize_fields( $fields ) {
			$fields['cs_product_buy_now'] = array(
				'section'  => 'product_buy_now',
				'type'     => 'custom',
				'label'    => '',
				'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Product Buy Now', 'alpha-core' ) . '</h3>',
				'priority' => 0,
			);

			$fields['show_buy_now_btn'] = array(
				'section'  => 'product_buy_now',
				'type'     => 'toggle',
				'label'    => esc_html__( 'Show Buy Now Button', 'alpha-core' ),
				'priority' => 0,
			);

			$fields['buy_now_text'] = array(
				'section'         => 'product_buy_now',
				'type'            => 'text',
				'label'           => esc_html__( 'Buy Now Text', 'alpha-core' ),
				'priority'        => 10,
				'active_callback' => array(
					array(
						'setting'  => 'show_buy_now_btn',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			$fields['buy_now_link'] = array(
				'section'         => 'product_buy_now',
				'type'            => 'text',
				'label'           => esc_html__( 'Buy Now Link', 'alpha-core' ),
				'priority'        => 20,
				'active_callback' => array(
					array(
						'setting'  => 'show_buy_now_btn',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			if ( function_exists( 'alpha_set_default_option' ) ) {
				alpha_set_default_option( 'show_buy_now_btn', false );
			}

			return $fields;
		}


		/**
		 * Add buy now button after cart button
		 *
		 * @since 1.0.0
		 */
		public function add_buy_now_btn() {
			global $product;

			if ( ! alpha_get_option( 'show_buy_now_btn' ) || 'external' == $product->get_type() || alpha_doing_quickview() ) {
				return;
			}

			echo '<button class="single_buy_now_button btn btn-outline btn-primary">' . esc_html( alpha_get_option( 'buy_now_text' ) ) . '</button>';
		}


		/**
		 * Enqueue Script
		 *
		 * @since 1.0.0
		 */
		public function enqueue_script() {
			if ( ! alpha_is_product() ) {
				return;
			}
			if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'show_buy_now_btn' ) ) {
				wp_enqueue_style( 'alpha-product-buy-now', alpha_core_framework_uri( '/addons/product-buy-now/product-buy-now' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), null, ALPHA_CORE_VERSION, 'all' );
				wp_enqueue_script( 'alpha-product-buy-now', alpha_core_framework_uri( '/addons/product-buy-now/product-buy-now' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
			}
		}


		/**
		 * Redirect to checkout after click buy now button
		 *
		 * @param {String} url
		 *
		 * @return {boolean}
		 *
		 * @since 1.0.0
		 */
		public function redirect_checkout_for_buy_now( $url ) {

			if ( ! isset( $_REQUEST['buy_now'] ) || false == $_REQUEST['buy_now'] ) {
				return $url;
			}

			if ( empty( $_REQUEST['quantity'] ) ) {
				return $url;
			}

			if ( is_array( $_REQUEST['quantity'] ) ) {
				$quantity_set = false;
				foreach ( $_REQUEST['quantity'] as $item => $quantity ) {
					if ( $quantity <= 0 ) {
						continue;
					}
					$quantity_set = true;
				}

				if ( ! $quantity_set ) {
					return $url;
				}
			}

			$redirect = alpha_get_option( 'buy_now_link' );
			if ( empty( $redirect ) ) {
				return wc_get_checkout_url();
			} else {
				wp_safe_redirect( $redirect );
				exit;
			}
		}
	}
}

Alpha_Product_Buy_Now::get_instance();
