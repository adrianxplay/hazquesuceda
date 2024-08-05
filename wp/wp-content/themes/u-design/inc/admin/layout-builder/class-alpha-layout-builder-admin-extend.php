<?php
/**
 * Alpha Layout Builder Admin Extend
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */

// Direct access is denied
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Layout_Builder_Admin_Extend' ) ) {
	class Alpha_Layout_Builder_Admin_Extend extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			if ( isset( $_REQUEST['page'] ) && 'alpha-layout-builder' == $_REQUEST['page'] ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			}
		}
		/**
		 * Enqueue scripts for layout builder.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'alpha-layout-builder-admin-extend', ALPHA_INC_URI . '/admin/layout-builder/layout-builder-admin-extend' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_VERSION, true );
		}
	}
}

Alpha_Layout_Builder_Admin_Extend::get_instance();
