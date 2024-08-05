<?php
/**
 * The base configuration for Alpha Core FrameWork
 *
 * The framework/config.php defines framework_path and
 * adds all framework feature.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

/**
 * Defines Alpha Core FrameWork Version
 * Defines Alpha Core FrameWork Path
 */
define( 'ALPHA_CORE_FRAMEWORK_VERSION', '1.3.0' );
define( 'ALPHA_CORE_FRAMEWORK_PATH', ALPHA_CORE_PATH . '/framework' );
define( 'ALPHA_CORE_FRAMEWORK_URI', ALPHA_CORE_URI . '/framework' );

/**
 * For theme developers
 *
 * You can override framework file by helping this function.
 * If you want to override framework/init.php, create 'inc' directory just inside of theme
 * and here you create init.php too. As a result inc/init.php is called by below function.
 *
 *
 * @param  string $path Full path of php, js, css file which is required.
 * @return string Returns filtered path if $path exists in inc directory, raw path otherwise.
 */
if ( ! function_exists( 'alpha_core_framework_path' ) ) {
	function alpha_core_framework_path( $path ) {
		return file_exists( str_replace( '/framework/', '/inc/', $path ) ) ? str_replace( '/framework/', '/inc/', $path ) : $path;
	}
}

/**
 * For theme developers
 *
 * You can override framework file by helping this function.
 * If you want to override framework/admin/admin.css, create 'inc' directory just inside of theme
 * and here you create admin/admin.css too. As a result inc/admin/admin.css is called by below function.
 *
 *
 * @param  string $short_path  Path in framework folder.
 * @return string Returns filtered uri if path exists in inc directory, raw uri otherwise.
 */
if ( ! function_exists( 'alpha_core_framework_uri' ) ) {
	function alpha_core_framework_uri( $short_path ) {
		return file_exists( ALPHA_CORE_PATH . '/inc' . $short_path ) ? ALPHA_CORE_URI . '/inc' . $short_path : ALPHA_CORE_FRAMEWORK_URI . $short_path;
	}
}

/**
 * Registers framework support for a given feature.
 *
 * Framework consists of features. If woocommerce feature isn't registered in theme,
 * framework doesn't require feature related file.
 *
 * @param array|string $features Features for framework. Likely core values include:
 *                      framework_support_pb_elementor
 *                      framework_support_pb_wpb
 *                      framework_support_plugin_woocommerce
 *                      framework_support_plugin_dokan
 *                      framework_support_admin_setup_wizard
 *                      ...
 */
if ( ! function_exists( 'alpha_add_feature' ) ) {
	function alpha_add_feature( $features ) {
		if ( empty( $features ) ) {
			return false;
		}
		if ( is_array( $features ) ) {
			foreach ( $features as $feature ) {
				add_theme_support( $feature );
			}
		} else {
			add_theme_support( $features );
		}
	}
}

/**
 * Allows a framework to de-register its support of a certain feature.
 *
 * Framework consists of features. If woocommerce feature isn't registered in theme,
 * framework doesn't require feature related file.
 *
 * @see alpha_add_feature()
 * @param array|string $features Features for framework.
 */
if ( ! function_exists( 'alpha_remove_feature' ) ) {
	function alpha_remove_feature( $features ) {
		if ( empty( $features ) ) {
			return false;
		}
		if ( is_array( $features ) ) {
			foreach ( $features as $feature ) {
				remove_theme_support( $feature );
			}
		} else {
			remove_theme_support( $features );
		}
	}
}

/**
 * Gets the framework support arguments passed when registering that support.
 *
 * Framework consists of features. If woocommerce feature isn't registered in theme,
 * framework doesn't require feature related file.
 *
 * @see alpha_add_feature()
 * @param string $feature The feature to check.
 */
if ( ! function_exists( 'alpha_get_feature' ) ) {
	function alpha_get_feature( $feature ) {
		if ( ! empty( $feature ) ) {
			return get_theme_support( $feature );
		}
	}
}

/**
 * Alpha FrameWork Setup Configuration
 *
 * Adds all framework supports. As you can see below, features are splitted by service.
 * You can filter features or remove framework supports. fs stands for framework support.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_setup' ) ) {
	function alpha_setup() {
		$addon_features   = apply_filters(
			'alpha_addon_features',
			array(
				'fs_addon_walker',
				'fs_addon_skeleton',
				'fs_addon_lazyload_image',
				'fs_addon_lazyload_menu',
				'fs_addon_live_search',
				'fs_addon_studio',
				'fs_addon_product_advanced_swatch',
				'fs_addon_product_custom_tabs',
				'fs_addon_product_frequently_bought_together',
				'fs_addon_share',
				'fs_addon_vendors',
				'fs_addon_product_helpful_comments',
				'fs_addon_product_ordering',
				'fs_addon_product_brand',
				'fs_addon_product_360_gallery',
				'fs_addon_product_video_popup',
				'fs_addon_product_image_comments',
				'fs_addon_product_compare',
				'fs_addon_product_attribute_guide',
				'fs_addon_comments_pagination',
				'fs_addon_product_buy_now',
				'fs_addon_minicart_quantity_input',
				'fs_addon_gdpr',
				'fs_addon_custom_fonts',
				'fs_addon_ai_generator',
			)
		);
		$pb_features      = apply_filters(
			'alpha_pb_features',
			array(
				'fs_pb_elementor',
				'fs_pb_gutenberg',
			)
		);
		$plugin_features  = apply_filters(
			'alpha_plugin_features',
			array(
				'fs_plugin_woocommerce',
				'fs_plugin_acf', // Dynamic Tags for Elementor + ACF + MetaBox
				'fs_plugin_woof',
				'fs_plugin_uni_cpo',
				'fs_plugin_yith_featured_video',
				'fs_plugin_yith_gift_card',
				'fs_plugin_yith_wishlist',
				'fs_plugin_yith_compare',
				'fs_plugin_rev',
				'fs_plugin_wpforms',
			)
		);
		$builder_features = apply_filters(
			'alpha_builder_features',
			array(
				'fs_builder_block',
				'fs_builder_header',
				'fs_builder_footer',
				'fs_builder_popup',
				'fs_builder_sidebar',
				'fs_builder_singleproduct',
				'fs_builder_shop',
				'fs_builder_cart',
				'fs_builder_checkout',
				'fs_builder_single',
				'fs_builder_archive',
				'fs_builder_type',
			)
		);
		$admin_features   = apply_filters(
			'alpha_admin_features',
			array(
				'fs_admin_customize',
				'fs_critical_css_js',
			)
		);
		/**
		 * For developers
		 *
		 * Filters extra features.
		 *
		 * @since 1.0
		*/
		$extra_features     = apply_filters( 'alpha_extra_features', array() );
		$framework_features = array_merge(
			$addon_features,
			$pb_features,
			$plugin_features,
			$admin_features,
			$builder_features,
			$extra_features
		);
		alpha_add_feature( $framework_features );
	}
}

alpha_setup();
/**
 * Fires after setup Alpha Core FrameWork configuration.
 *
 * @since 1.0
 */
do_action( 'alpha_after_core_framework_config' );
