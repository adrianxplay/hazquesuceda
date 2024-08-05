<?php
/**
 * Woof Compatibility
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

if ( ! class_exists( 'Alpha_WOOF' ) ) {

	/**
	 * Alpha Woof Class
	 */
	class Alpha_WOOF extends Alpha_Base {

		protected $counter;

		/**
		 * Main Class construct
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50 );
		}

		/**
		 * Custom style for WooF
		 *
		 * @since 1.0
		 */
		function enqueue_scripts() {
			wp_enqueue_style( 'alpha-woof-style', alpha_framework_uri( '/plugins/woof/woof' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpha-style' ), ALPHA_VERSION );
		}
	}
}

Alpha_WOOF::get_instance();
