<?php
/**
 * Core Framework Addons
 *
 * 1. Load addons
 * 2. Addons List
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

add_action(
	'alpha_framework_addons',
	function( $request ) {
		// @start feature: fs_addon_walker
		if ( alpha_get_feature( 'fs_addon_walker' ) ) {
			if ( 'nav-menus.php' == $GLOBALS['pagenow'] || $request['customize_preview'] || $request['doing_ajax'] ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/walker/class-alpha-walker.php' );
			}
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/walker/class-alpha-walker-nav-menu.php' );
		}
		// @end feature: fs_addon_walker

		// @start feature: fs_addon_skeleton
		if ( alpha_get_feature( 'fs_addon_skeleton' ) && ( ! $request['doing_ajax'] && ! $request['customize_preview'] && ! $request['is_preview'] && function_exists( 'alpha_get_option' ) && alpha_get_option( 'skeleton_screen' ) && ! isset( $_REQUEST['only_posts'] ) ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/skeleton/class-alpha-skeleton.php' );
		}
		// @end feature: fs_addon_skeleton

		// @start feature: fs_addon_lazyload_image
		if ( alpha_get_feature( 'fs_addon_lazyload_image' ) ) {
			add_filter( 'wp_lazy_loading_enabled', 'alpha_disable_wp_lazyload_img', 10, 2 );
			function alpha_disable_wp_lazyload_img( $default, $tag_name ) {
				return 'img' == $tag_name ? false : $default;
			}
			if ( ! $request['is_admin'] && ! $request['customize_preview'] && ! $request['doing_ajax'] && function_exists( 'alpha_get_option' ) && alpha_get_option( 'lazyload' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/lazyload-images/class-alpha-lazyload-images.php' );
			}
		}
		// @end feature: fs_addon_lazyload_image

		// @start feature: fs_addon_lazyload_menu
		if ( alpha_get_feature( 'fs_addon_lazyload_menu' ) && $request['is_admin'] ) {
			if ( $request['customize_preview'] ) {
				add_action( 'customize_save_after', 'alpha_lazyload_menu_update' );
			}
			if ( 'post.php' == $GLOBALS['pagenow'] ) {
				add_action( 'save_post', 'alpha_lazyload_menu_update' );
			}
			add_action( 'wp_update_nav_menu_item', 'alpha_lazyload_menu_update', 10, 3 );

			if ( ! function_exists( 'alpha_lazyload_menu_update' ) ) {
				function alpha_lazyload_menu_update() {
					set_theme_mod( 'menu_last_time', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
				}
			}
		}
		// @end feature: fs_addon_lazyload_menu

		// @start feature: fs_addon_live_search
		if ( alpha_get_feature( 'fs_addon_live_search' ) && function_exists( 'alpha_get_option' ) && alpha_get_option( 'live_search' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/live-search/class-alpha-live-search.php' );
		}
		// @end feature: fs_addon_live_search

		// @start feature: fs_addon_share
		if ( alpha_get_feature( 'fs_addon_share' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/share/class-alpha-share.php' );
		}
		// @end feature: fs_addon_share
		// @start feature: fs_plugin_woocommerce
		if ( class_exists( 'WooCommerce' ) && alpha_get_feature( 'fs_plugin_woocommerce' ) ) {

			// @start feature: fs_addon_product_helpful_comments
			if ( alpha_get_feature( 'fs_addon_product_helpful_comments' ) && 'yes' == get_option( 'woocommerce_enable_reviews' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-helpful-comments/class-alpha-helpful-comments.php' );
			}
			// @end feature: fs_addon_product_helpful_comments

			// @start feature: fs_addon_product_ordering
			if ( alpha_get_feature( 'fs_addon_product_ordering' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-ordering/class-alpha-product-ordering.php' );
			}
			// @end feature: fs_addon_product_ordering

			// @start feature: fs_addon_product_brand
			if ( alpha_get_feature( 'fs_addon_product_brand' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-brand/class-alpha-product-brand.php' );
			}
			// @end feature: fs_addon_product_brand

			// @start feature: fs_addon_product_360_gallery
			if ( alpha_get_feature( 'fs_addon_product_360_gallery' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-360-gallery/class-alpha-product-360-gallery.php' );
			}
			// @end feature: fs_addon_product_360_gallery

			// @start feature: fs_addon_product_video_popup
			if ( alpha_get_feature( 'fs_addon_product_video_popup' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-video-popup/class-alpha-product-video-popup.php' );
			}
			// @end feature: fs_addon_product_video_popup

			// @start feature: fs_addon_product_image_comments
			if ( alpha_get_feature( 'fs_addon_product_image_comments' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-image-comments/class-alpha-product-image-comment.php' );
				if ( $request['can_manage'] ) {
					require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-image-comments/class-alpha-product-image-comment-admin.php' );
				}
			}
			// @end feature: fs_addon_product_image_comments

			// @start feature: fs_addon_product_compare
			if ( alpha_get_feature( 'fs_addon_product_compare' ) && ! defined( 'YITH_WOOCOMPARE_VERSION' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-compare/class-alpha-product-compare.php' );
			}
			// @end feature: fs_addon_product_compare

			// @start feature: fs_addon_product_attribute_guide
			if ( alpha_get_feature( 'fs_addon_product_attribute_guide' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-attribute-guide/class-product-attribute-guide.php' );
			}
			// @end feature: fs_addon_product_attribute_guide

			// @start feature: fs_addon_product_advanced_swatch
			if ( alpha_get_feature( 'fs_addon_product_advanced_swatch' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-advanced-swatch/class-alpha-advanced-swatch.php' );
				if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'advanced_swatch' ) && $request['is_admin'] && ( 'edit-tags.php' == $GLOBALS['pagenow'] || $request['doing_ajax'] || $request['product_edit_page'] ||
					! empty( $_POST['action'] ) && 'editpost' == $_POST['action'] ) ) {
					require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-advanced-swatch/class-alpha-advanced-swatch-tab.php' );
				}
			}
			// @end feature: fs_addon_product_advanced_swatch

			// @start feature: fs_addon_product_custom_tabs
			if ( alpha_get_feature( 'fs_addon_product_custom_tabs' ) ) {
				if ( $request['is_admin'] && ( $request['doing_ajax'] || $request['product_edit_page'] ) ) {
					require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-custom-tab/class-alpha-product-custom-tab-admin.php' );
				}
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-data-addons/class-alpha-product-data-addons.php' );
			}
			// @end feature: fs_addon_product_custom_tabs

			// @start feature: fs_addon_product_frequently_bought_together
			if ( alpha_get_feature( 'fs_addon_product_frequently_bought_together' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-frequently-bought-together/class-alpha-pfbt.php' );
				if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'product_fbt' ) && $request['is_admin'] && ( $request['doing_ajax'] || $request['product_edit_page'] ||
					! empty( $_POST['action'] ) && 'editpost' == $_POST['action'] ) ) {
					require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-frequently-bought-together/class-alpha-pfbt-admin.php' );
				}
			}
			// @end feature: fs_addon_product_frequently_bought_together

			// @start feature: fs_addon_product_buy_now
			if ( alpha_get_feature( 'fs_addon_product_buy_now' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/product-buy-now/class-alpha-product-buy-now.php' );
			}
			// @end feature: fs_addon_product_buy_now

		}
		// @end feature: fs_plugin_woocommerce

		// @start feature: fs_addon_studio
		if ( alpha_get_feature( 'fs_addon_studio' ) ) {
			if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) &&
			( $request['doing_ajax'] || $request['is_preview'] || 'edit.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ALPHA_NAME . '_template' == $_REQUEST['post_type'] || 'post.php' == $GLOBALS['pagenow'] && 'edit' == $_REQUEST['action'] || 'post-new.php' == $GLOBALS['pagenow'] ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/studio/class-alpha-studio.php' );
			}
		}
		// @end feature: fs_addon_studio

		// @start feature: fs_addon_comments_pagination
		if ( alpha_get_feature( 'fs_addon_comments_pagination' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/comments-pagination/class-alpha-comments-pagination.php' );
		}
		// @end feature: fs_addon_comments_pagination

		// @start feature: fs_addon_minicart_quantity_input
		if ( alpha_get_feature( 'fs_addon_minicart_quantity_input' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/minicart-quantity-input/class-alpha-minicart-quantity-input.php' );
		}
		// @end feature: fs_addon_minicart_quantity_input

		// @start feature: fs_addon_gdpr
		if ( alpha_get_feature( 'fs_addon_gdpr' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/gdpr/class-alpha-gdpr.php' );
		}
		// @end feature: fs_addon_gdpr

		// @start feature: fs_addon_custom_fonts
		if ( alpha_get_feature( 'fs_addon_custom_fonts' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/custom-fonts/class-alpha-custom-fonts.php' );
		}
		// @end feature: fs_addon_custom_fonts
		
		// @start feature: fs_addon_ai_generator
		if ( alpha_get_feature( 'fs_addon_ai_generator' ) ) {
			require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/ai-generator/class-alpha-content-generator.php' );
		}
		// @end feature: fs_addon_ai_generator

		require_once alpha_core_framework_path( ALPHA_CORE_ADDONS . '/breadcrumb/class-alpha-breadcrumb.php' );

	}
);
