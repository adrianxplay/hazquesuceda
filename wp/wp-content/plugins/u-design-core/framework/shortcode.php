<?php
/**
 * Core Framework Shortcodes
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.0
 */

add_shortcode( ALPHA_NAME . '_year', 'alpha_shortcode_year' );
add_shortcode( ALPHA_NAME . '_products', 'alpha_shortcode_product' );
add_shortcode( ALPHA_NAME . '_product_category', 'alpha_shortcode_product_category' );
add_shortcode( ALPHA_NAME . '_posts', 'alpha_shortcode_posts' );
add_shortcode( ALPHA_NAME . '_block', 'alpha_shortcode_block' );
add_shortcode( ALPHA_NAME . '_menu', 'alpha_shortcode_menu' );
add_shortcode( ALPHA_NAME . '_linked_products', 'alpha_shortcode_linked_product' );
add_shortcode( ALPHA_NAME . '_breadcrumb', 'alpha_shortcode_breadcrumb' );
add_shortcode( ALPHA_NAME . '_filter', 'alpha_shortcode_filter' );
add_shortcode( ALPHA_NAME . '_vendors', 'alpha_shorcode_vendors' );

function alpha_shortcode_year() {
	return date( 'Y' );
}

function alpha_shortcode_product( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/products/render-products.php', $atts );
	return ob_get_clean();
}

function alpha_shortcode_product_category( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/categories/render-categories.php', $atts );
	return ob_get_clean();
}

function alpha_shortcode_posts( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/posts/render-posts.php', $atts );
	return ob_get_clean();
}


function alpha_shortcode_block( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/block/render-block.php', $atts );
	return ob_get_clean();
}


function alpha_shortcode_menu( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/menu/render-menu.php', $atts );
	return ob_get_clean();
}


function alpha_shortcode_linked_product( $atts, $content = null ) {
	ob_start();
	/**
	 * Filters post products in single product builder.
	 *
	 * @since 1.0
	 */
	if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/products/render-products.php', $atts );
		do_action( 'alpha_single_product_builder_unset_preview' );
	}
	return ob_get_clean();
}

function alpha_shortcode_breadcrumb( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/breadcrumb/render-breadcrumb.php', $atts );
	return ob_get_clean();
}


function alpha_shortcode_filter( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/filter/render-filter.php', $atts );
	return ob_get_clean();
}

function alpha_shortcode_vendors( $atts, $content = null ) {
	ob_start();
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/vendor/render-vendor.php', $atts );
	return ob_get_clean();
}
