<?php
/**
 * Entrypoint of Core
 *
 * Here, proper features of theme are added or removed.
 * If framework has unnecessary features, you can remove features
 * using alpha_remove_feature.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

/**************************************/
/* Define Constants                */
/**************************************/

define( 'ALPHA_CORE_INC', ALPHA_CORE_PATH . '/inc' );
define( 'ALPHA_CORE_INC_URI', ALPHA_CORE_URI . '/inc' );
define( 'ALPHA_CORE_BUILDERS', ALPHA_CORE_INC . '/builders' );


class Alpha_Core_Setup extends Alpha_Base {

	/**
	 * The Constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {

		/**************************************/
		/* 1. Load the core general            */
		/**************************************/
		require_once ALPHA_CORE_INC . '/core-functions.php';
		require_once ALPHA_CORE_INC . '/core-actions.php';
		$this->config_extend();

		/**************************************/
		/* 2. Load plugin functions */
		/**************************************/
		add_action( 'alpha_after_core_framework_init', array( $this, 'init_plugins' ) );

		/**************************************/
		/* 3. Load builders                   */
		/**************************************/
		require_once ALPHA_CORE_BUILDERS . '/class-alpha-builders-extend.php';
		require_once ALPHA_CORE_BUILDERS . '/sidebar/class-alpha-sidebar-builder-extend.php';

		/**************************************/
		/* 4. Load addons and shortcodes      */
		/**************************************/
		add_action( 'alpha_framework_addons', array( $this, 'extend_addons' ) );
		add_action( 'alpha_framework_addons', array( $this, 'extend_framework_addons' ), 20 );

	}

	/**
	 * Manage features configuration
	 *
	 * @since 4.0
	 * @access public
	 */
	public function config_extend() {

		alpha_add_feature( 'fs_plugin_wpforms' );
		alpha_add_feature( 'fs_addon_product_unit' );

		alpha_remove_feature( 'fs_pt_7' );
		alpha_remove_feature( 'fs_pt_8' );
		alpha_remove_feature(
			array(
				// List type Feature
				'fs_bt_list-xs',
				// Mast type Feature
				'fs_bt_mask',
				// Single Product Feature
				'fs_spt_grid',
				'fs_spt_masonry',
				'fs_spt_sticky-info',
				'fs_spt_sticky-thumbs',
				'fs_spt_sticky-both',
			)
		);
		alpha_remove_feature( 'fs_widget_vendor' );
	}

	/**
	 * Init plugins
	 *
	 * @since 4.0
	 * @access public
	 */
	public function init_plugins() {
		// Custom Post Types
		require_once ALPHA_CORE_INC . '/cpt/class-alpha-cpts.php';

		if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) {
			require_once ALPHA_CORE_INC . '/plugins/elementor/class-alpha-core-elementor-extend.php';
		}
		if ( alpha_get_feature( 'fs_plugin_wpforms' ) && class_exists( 'WPForms' ) ) {
			require_once ALPHA_CORE_INC . '/plugins/wpforms/class-alpha-core-wpforms.php';
		}

		if ( class_exists( 'LearnPress' ) && version_compare( LEARNPRESS_VERSION, '4.0.0', '>' ) ) {
			require_once ALPHA_CORE_INC . '/plugins/learnpress/class-alpha-core-learnpress.php';
		}
		if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
			require_once ALPHA_CORE_INC . '/plugins/tribe_events/class-alpha-core-tribe_events.php';
		}
		if ( defined( 'EG_PLUGIN_PATH' ) ) {
			require_once ALPHA_CORE_INC . '/plugins/essential-grid/class-alpha-core-essential-grid.php';
		}
		if ( defined( 'ALPUS_FLEXBOX_VERSION' ) ) {
			require_once ALPHA_CORE_INC . '/plugins/alpus-flexbox/class-alpha-core-alpus-flexbox.php';
		}
	}

	/**
	 * Extend addons
	 *
	 * @since 4.0
	 * @access public
	 */
	public function extend_addons( $request ) {
		// Post like addon
		require_once ALPHA_CORE_INC . '/addons/post-like/class-alpha-post-like.php';
		require_once ALPHA_CORE_INC . '/addons/product-catalog/class-alpha-product-catalog.php';

		// Product unit addon
		if ( class_exists( 'WooCommerce' ) ) {
			require_once ALPHA_CORE_INC . '/addons/product-unit/class-alpha-product-unit.php';
		}

		// @start feature: fs_addon_studio
		if ( alpha_get_feature( 'fs_addon_studio' ) ) {
			if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) &&
			( $request['doing_ajax'] || $request['is_preview'] || 'edit.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ALPHA_NAME . '_template' == $_REQUEST['post_type'] || 'post.php' == $GLOBALS['pagenow'] && 'edit' == $_REQUEST['action'] || 'post-new.php' == $GLOBALS['pagenow'] ) ) {
				require_once ALPHA_CORE_INC . '/addons/studio/class-alpha-studio-extend.php';
			}
		}
		// @end feature: fs_addon_studio

	}

	/**
	 * Extend existing addon classes
	 *
	 * @since 4.0
	 * @access public
	 */
	public function extend_framework_addons( $request ) {
		// @start feature: fs_addon_skeleton
		if ( alpha_get_feature( 'fs_addon_skeleton' ) && ( ! $request['doing_ajax'] && ! $request['customize_preview'] && ! $request['is_preview'] && function_exists( 'alpha_get_option' ) && alpha_get_option( 'skeleton_screen' ) && ! isset( $_REQUEST['only_posts'] ) ) ) {
			require_once ALPHA_CORE_INC . '/addons/skeleton/class-alpha-skeleton-extend.php';
		}
		// @end feature: fs_addon_skeleton
		require_once ALPHA_CORE_INC . '/addons/ai-generator/class-alpha-content-generator-extend.php';
	}
}

Alpha_Core_Setup::get_instance();
