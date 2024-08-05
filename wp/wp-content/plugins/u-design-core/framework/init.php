<?php
/**
 * Core Framework
 *
 * 1. Load the plugin base
 * 2. Load the other plugin functions
 * 3. Load builders
 * 4. Load addons and shortcodes
 * 5. Critical CSS
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPHA_CORE_PLUGINS', ALPHA_CORE_FRAMEWORK_PATH . '/plugins' );
define( 'ALPHA_CORE_PLUGINS_URI', ALPHA_CORE_FRAMEWORK_URI . '/plugins' );
define( 'ALPHA_BUILDERS', ALPHA_CORE_FRAMEWORK_PATH . '/builders' );
define( 'ALPHA_BUILDERS_URI', ALPHA_CORE_FRAMEWORK_URI . '/builders' );
define( 'ALPHA_CORE_ADDONS', ALPHA_CORE_FRAMEWORK_PATH . '/addons' );
define( 'ALPHA_CORE_ADDONS_URI', ALPHA_CORE_FRAMEWORK_URI . '/addons' );

global $pagenow;
$alpha_pages = array( 'post-new.php', 'post.php', 'index.php', 'admin-ajax.php', 'edit.php', 'admin.php', 'widgets.php' );
/**************************************/
/* 1. Load the plugin base            */
/**************************************/

require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/common-functions.php' );
require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/plugin-functions.php' );
require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/plugin-actions.php' );

/**
 * Fires after framework init
 *
 * @since 1.0
 */
do_action( 'alpha_after_core_framework_init' );

/**************************************/
/* 2. Load the other plugin functions */
/**************************************/
if ( in_array( $pagenow, $alpha_pages ) ) {

	// @start feature: fs_pb_elementor
	if ( alpha_get_feature( 'fs_pb_elementor' ) ) {
		require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/elementor/class-alpha-core-elementor.php' );   // Elementor
	}
	// @end feature: fs_pb_elementor

}

require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/gutenberg/class-alpha-gutenberg.php' );   //Gutenberg Blocks

// @start feature: fs_plugin_acf
if ( alpha_get_feature( 'fs_plugin_acf' ) && class_exists( 'ACF' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/acf/class-alpha-core-acf.php' );                     // ACF
}
// @end feature: fs_plugin_acf


// @start feature: fs_plugin_woof
if ( alpha_get_feature( 'fs_plugin_woof' ) && class_exists( 'WOOF' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/woof/class-alpha-core-woof.php' );
}
// @end feature: fs_plugin_woof

// @start feature: fs_plugin_uni_cpo
if ( alpha_get_feature( 'fs_plugin_uni_cpo' ) && class_exists( 'Uni_Cpo' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/unicpo/class-alpha-core-unicpo.php' );
}
// @end feature: fs_plugin_uni_cpo

// @start feature: fs_plugin_yith_featured_video
if ( alpha_get_feature( 'fs_plugin_yith_featured_video' ) && class_exists( 'YITH_WC_Audio_Video' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/yith-featured-video/class-alpha-core-yith-featured-video.php' );
}
// @end feature: fs_plugin_yith_featured_video

// @start feature: fs_plugin_yith_gift_card
if ( alpha_get_feature( 'fs_plugin_yith_gift_card' ) && class_exists( 'YITH_YWGC_Gift_Card' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/yith-gift-card/class-alpha-core-yith-gift-card.php' );
}
// @end feature: fs_plugin_yith_gift_card

// @start feature: fs_plugin_yith_wishlist
if ( alpha_get_feature( 'fs_plugin_yith_wishlist' ) && defined( 'YITH_WCWL' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/yith-wishlist/class-alpha-core-yith-wishlist.php' );
}
// @end feature: fs_plugin_yith_wishlist

// @start feature: fs_plugin_yith_compare
if ( alpha_get_feature( 'fs_plugin_yith_compare' ) && defined( 'YITH_WOOCOMPARE_VERSION' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/yith-compare/class-alpha-core-yith-compare.php' );
}
// @end feature: fs_plugin_yith_compare


// @start feature: fs_plugin_wpforms
if ( alpha_get_feature( 'fs_plugin_wpforms' ) && class_exists( 'WPForms' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/wpforms/class-alpha-core-wpforms.php' );
}
// @end feature: fs_plugin_wpforms

require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/class-alpha-admin-meta-boxes.php' );             // Meta Box

if ( class_exists( 'WooCommerce' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/woocommerce/class-alpha-core-woocommerce.php' );             // WooCommerce
}

/**
 * Fires after loading framework plugin compatibility.
 *
 * @since 1.0
 */
do_action( 'alpha_after_core_framework_plugins' );

/**************************************/
/* 3. Load builders                   */
/**************************************/
if ( ! isset( $_POST['action'] ) || 'alpha_quickview' != $_POST['action'] ) {
	require_once alpha_core_framework_path( ALPHA_BUILDERS . '/class-alpha-builders.php' );
	// @start feature: fs_builder_sidebar
	if ( alpha_get_feature( 'fs_builder_sidebar' ) ) {
		require_once alpha_core_framework_path( ALPHA_BUILDERS . '/sidebar/class-alpha-sidebar-builder.php' );
	}
	// @end feature: fs_builder_sidebar
}

/**
 * Fires after loading framework template builder.
 *
 * @since 1.0
 */
do_action( 'alpha_after_core_framework_builders' );

/**************************************/
/* 4. Load addons and shortcodes      */
/**************************************/

require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/addons/init.php' );
require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/shortcode.php' );

/**************************************/
/* 5. Critical CSS                    */
/**************************************/
if ( alpha_get_feature( 'fs_critical_css_js' ) ) {
	require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/critical/class-alpha-critical.php' );
}

/**
 * Fires after loading framework init.
 *
 * @since 1.0
 */
do_action( 'alpha_after_core_framework_shortcodes' );
