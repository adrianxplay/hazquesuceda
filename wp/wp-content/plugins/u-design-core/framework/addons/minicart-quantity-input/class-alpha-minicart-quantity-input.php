<?php
/**
 * Alpha MiniCart Quantity Input
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_MiniCart_Quantity_Input' ) ) {
	class Alpha_MiniCart_Quantity_Input extends Alpha_Base {

		/**
		 * Main Class construct
		 *
		 * @since 1.0
		 */
		public function __construct() {

			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );

			add_action( 'wp_ajax_alpha_update_cart_item', array( $this, 'update_cart_item' ) );
			add_action( 'wp_ajax_nopriv_alpha_update_cart_item', array( $this, 'update_cart_item' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			wp_register_style( 'alpha-minicart-quantity-input', alpha_core_framework_uri( '/addons/minicart-quantity-input/minicart-quantity-input.min.css' ), null, ALPHA_CORE_VERSION, 'all' );
			wp_register_script( 'alpha-minicart-quantity-input', alpha_core_framework_uri( '/addons/minicart-quantity-input/minicart-quantity-input' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
		}

		/**
		 * Change quantity of cart item
		 *
		 * @since 1.0
		 */
		public function update_cart_item() {
			$cart_item_key = $_REQUEST['cart_item_key'];
			$quantity      = $_REQUEST['quantity'];

			WC()->cart->set_quantity( $cart_item_key, $quantity );
		}
	}
}

Alpha_MiniCart_Quantity_Input::get_instance();
