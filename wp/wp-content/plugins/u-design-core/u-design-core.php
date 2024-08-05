<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeforest.net/user/andondesign
 * @since             4.0
 * @package           U_Design_Core
 *
 * @wordpress-plugin
 * Plugin Name:       UDesign Core
 * Plugin URI:        https://themeforest.net/item/alpha-responsive-wordpress-theme/253220
 * Description:       Adds functionality such as Shortcodes, Post Types, Widgets and Page Builders to UDesign Theme
 * Version:           4.10.2
 * Author:            AndonDesign
 * Author URI:        https://themeforest.net/user/andondesign
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alpha-core
 */

// Direct load is not allowed
defined( 'ABSPATH' ) || die;

/**************************************/
/* Define Constants                   */
/**************************************/
defined( 'ALPHA_NAME' ) || define( 'ALPHA_NAME', 'udesign' );
defined( 'ALPHA_ICON_PREFIX' ) || define( 'ALPHA_ICON_PREFIX', 'a' );                      // Framework Icon Prefix
defined( 'THEME_ICON_PREFIX' ) || define( 'THEME_ICON_PREFIX', 'u' );                      // Theme Icon Prefix
defined( 'ALPHA_DISPLAY_NAME' ) || define( 'ALPHA_DISPLAY_NAME', 'UDesign' );              // Theme Display Name
define( 'ALPHA_CORE_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) );               // Plugin directory uri
define( 'ALPHA_CORE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );             // Plugin directory path
define( 'ALPHA_CORE_FILE', __FILE__ );                                                     // Plugin file path
define( 'ALPHA_CORE_VERSION', '4.10.2' );                                                  // Plugin Version

// Define script debug
if ( ! defined( 'ALPHA_JS_SUFFIX' ) ) {
	defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? define( 'ALPHA_JS_SUFFIX', '.js' ) : define( 'ALPHA_JS_SUFFIX', '.min.js' );
}

if ( ! class_exists( 'Alpha_Base' ) ) {
	require_once ALPHA_CORE_PATH . '/framework/class-alpha-base.php';
}

require_once ALPHA_CORE_PATH . '/framework/config.php';

/**
 * Alpha Core Plugin Class
 *
 * @since 4.0
 */
class U_Design_Core {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {
		// Load plugin
		add_action( 'plugins_loaded', array( $this, 'load' ) );
	}

	/**
	 * Load required files
	 *
	 * @since 4.0
	 *
	 * @return void
	 */
	public function load() {
		// Load text domain
		load_plugin_textdomain( 'alpha-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		require_once ALPHA_CORE_PATH . '/inc/core-setup.php';
		require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/init.php' );
	}
}

new U_Design_Core();

require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'learnpress/learnpress.php' ) ) {
	include ALPHA_CORE_PATH . '/inc/plugins/learnpress/abstract-shortcode-courses.php';
}
