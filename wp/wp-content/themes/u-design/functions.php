<?php
/**
 * Theme Functions
 *
 * To use a child theme:
 *   @see http://codex.wordpress.org/Theme_Development
 *   @see http://codex.wordpress.org/Child_Themes
 *
 * To override certain functions (wrapped in a function_exists call):
 *   define them in child theme's functions.php file.
 *
 * For more information on hooks, actions, and filters:
 *   @see http://codex.wordpress.org/Plugin_API
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

// Direct load is not allowed
defined( 'ABSPATH' ) || die;

// Theme Name, Version and icon prefix
defined( 'ALPHA_NAME' ) || define( 'ALPHA_NAME', 'udesign' );
defined( 'ALPHA_DISPLAY_NAME' ) || define( 'ALPHA_DISPLAY_NAME', 'UDesign' );
defined( 'ALPHA_ICON_PREFIX' ) || define( 'ALPHA_ICON_PREFIX', 'a' );
defined( 'THEME_ICON_PREFIX' ) || define( 'THEME_ICON_PREFIX', 'u' );
define( 'ALPHA_VERSION', ( is_child_theme() ? wp_get_theme( wp_get_theme()->template ) : wp_get_theme() )->version );
define( 'ALPHA_THEME_URL', wp_get_theme()->get( 'ThemeURI' ) );
defined( 'ALPHA_ENVATO_CODE' ) || define( 'ALPHA_ENVATO_CODE', '253220' );
defined( 'ALPHA_ADMIN_SKIN' ) || define( 'ALPHA_ADMIN_SKIN', 'light' );
define( 'ALPHA_GAP', '15px' );
// Define script debug
defined( 'ALPHA_JS_SUFFIX' ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? define( 'ALPHA_JS_SUFFIX', '.js' ) : define( 'ALPHA_JS_SUFFIX', '.min.js' ) );
// Defines core name and slug if not defined.
defined( 'ALPHA_CORE_NAME' ) || define( 'ALPHA_CORE_NAME', 'UDesign Core' );
defined( 'ALPHA_CORE_SLUG' ) || define( 'ALPHA_CORE_SLUG', 'u-design-core' );
defined( 'ALPHA_CORE_PLUGIN_URI' ) || define( 'ALPHA_CORE_PLUGIN_URI', 'u-design-core/u-design-core.php' );

// Define Constants
define( 'ALPHA_PATH', get_parent_theme_file_path() );                      // Template directory path
define( 'ALPHA_URI', get_parent_theme_file_uri() );                        // Template directory uri
defined( 'ALPHA_SERVER_URI' ) || define( 'ALPHA_SERVER_URI', 'https://dythemes.com/wordpress/' );               // Server uri
define( 'ALPHA_ASSETS', ALPHA_URI . '/assets' );                           // Template assets directory uri
define( 'ALPHA_CSS', ALPHA_ASSETS . '/css' );                              // Template css uri
define( 'ALPHA_JS', ALPHA_ASSETS . '/js' );                                // Template javascript uri
define( 'ALPHA_PART', 'templates' );                                       // Template parts

if ( ! class_exists( 'Alpha_Base' ) ) {
	require_once ALPHA_PATH . '/framework/class-alpha-base.php';
}

// FrameWork Config
require_once ALPHA_PATH . '/framework/config.php';
// Theme EntryPoint
require_once ALPHA_PATH . '/inc/theme-setup.php';
// FrameWork EntryPoint
require_once alpha_framework_path( ALPHA_FRAMEWORK_PATH . '/init.php' );
