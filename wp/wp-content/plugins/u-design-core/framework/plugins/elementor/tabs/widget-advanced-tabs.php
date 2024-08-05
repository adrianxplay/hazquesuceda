<?php
/**
 * Alpha Elementor Custom Advanced Tab
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpha_Widget_Advanced_Tabs' ) ) {
	/**
	 * Advanced Alpha Advanced Tab
	 *
	 * @since 1.0
	 */
	class Alpha_Widget_Advanced_Tabs extends Alpha_Base {
		const TAB_CUSTOM = 'alpha_custom_tab';

		private $custom_tabs;

		/**
		 * The Constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// Init Custom Tabs
			$this->init_custom_tabs();
			$this->register_custom_tabs();
			$this->add_addon_sections();
		}

		/**
		 * Init custom tab.
		 *
		 * @since 1.0
		 */
		private function init_custom_tabs() {
			$this->custom_tabs = array();

			$this->custom_tabs[ $this::TAB_CUSTOM ] = ALPHA_DISPLAY_NAME;

			/**
			 * Filters custom tabs.
			 *
			 * @since 1.0
			 */
			$this->custom_tabs = apply_filters( 'alpha_init_custom_tabs', $this->custom_tabs );
		}

		/**
		 * Register custom tab.
		 *
		 * @since 1.0
		 */
		public function register_custom_tabs() {
			foreach ( $this->custom_tabs as $key => $value ) {
				Elementor\Controls_Manager::add_tab( $key, $value );
			}
		}

		/**
		 * Add addon sections.
		 *
		 * @since 1.2.0
		 */
		public function add_addon_sections() {
			/**
			 * Filters sections which added on in elementor.
			 *
			 * @since 1.2.0
			 */
			$sections = apply_filters( 'alpha_elementor_addon_sections', array( 'floating', 'transform', 'duplex', 'ribbon', 'mask', 'custom' ) );
			foreach ( $sections as $section ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/tabs/' . $section . '/class-alpha-' . $section . '-elementor.php' );
			}
		}
	}
	Alpha_Widget_Advanced_Tabs::get_instance();
}
