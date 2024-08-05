<?php
/**
 * Alpha Yith Wishlist
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
if ( ! class_exists( 'Alpha_Core_Compare' ) ) {

	class Alpha_Core_Compare extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wp_footer', array( $this, 'enqueue_style' ), 19 );
			add_filter( 'alpha_critical_css', array( $this, 'remove_critical_css' ) );
		}

		/**
		 * Enqueue styles
		 *
		 * @since 1.0
		 */
		public function enqueue_style() {
			wp_enqueue_style( 'alpha-yith-compare-style', alpha_core_framework_uri( '/plugins/yith-compare/yith-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpha-theme' ), ALPHA_CORE_VERSION );
		}

		/**
		 * Remove critical css when compare popup loading
		 *
		 * @since 1.0
		 */
		public function remove_critical_css( $css ) {
			if ( isset( $_REQUEST['action'] ) && 'yith-woocompare-view-table' == sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) ) {
				$css = '';
			}
			return $css;
		}
	}
}

Alpha_Core_Compare::get_instance();
