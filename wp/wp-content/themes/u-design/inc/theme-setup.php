<?php
/**
 * Entrypoint of theme
 *
 * Here, proper features of theme are added or removed.
 * If framework has unnecessary features, you can remove features
 * using alpha_remove_feature.
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPHA_INC', ALPHA_PATH . '/inc' );
define( 'ALPHA_INC_URI', ALPHA_URI . '/inc' );

class Alpha_Theme {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 * @access public
	 */
	public function __construct() {
		require_once ALPHA_INC . '/general-functions.php';
		require_once ALPHA_INC . '/general-actions.php';
		require_once ALPHA_INC . '/class-alpha-assets-extend.php';

		add_action( 'alpha_after_framework_init', array( $this, 'extend_admin' ) );
		add_action( 'alpha_after_framework', array( $this, 'extend_layout' ) );
		add_action( 'alpha_after_framework_init', array( $this, 'extend_plugins' ) );
		// add_action( 'alpha_framework_addons', array( $this, 'extend_addons' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 30 );
		// add_action( 'wp_enqueue_scripts', array( $this, 'alpha_css' ), 1000 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 30 );

		add_filter( 'alpha_vars', array( $this, 'additional_alpha_vars' ) );

		add_filter( 'alpha_image_sizes', array( $this, 'additional_image_sizes' ) );

		$this->compatibility();

		// Revslider compatibility
		add_action( 'admin_init', array( $this, 'disable_revslider_redirection' ), 8 );
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 4.0
	 */
	public function enqueue_scripts() {
		// Scripts for page transition effect
		wp_enqueue_script( 'alpha-magnific-popup' );
		wp_enqueue_style( 'alpha-magnific-popup' );
		if ( alpha_get_option( 'page_transition' ) ) {
			wp_enqueue_script( 'jquery-transit', ALPHA_ASSETS . '/vendor/jquery.transit/transit.min.js', array(), '0.9.9', true );
		}
		wp_deregister_style( 'alpha-flag' );
	}

	/**
	 * Enqueue styles and scripts for admin.
	 *
	 * @since 4.0
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'alpha-admin-extend', ALPHA_INC_URI . '/admin/admin-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_VERSION );
	}

	/**
	 * Extends admin
	 *
	 * @param array $request
	 * @since 4.0
	 */
	public function extend_admin( $request ) {
		if ( alpha_get_feature( 'fs_admin_customize' ) && $request['customize_preview'] ) {
			require_once ALPHA_INC . '/admin/customizer/class-alpha-customizer-extend.php';
		}

		if ( $request['can_manage'] && ! $request['customize_preview'] && $request['is_admin'] ) {
			require_once ALPHA_INC . '/admin/setup-wizard/setup-wizard-extend.php';
		}
	}

	/**
	 * Extends Layout
	 *
	 * @since 4.1
	 */
	public function extend_layout() {
		require_once ALPHA_INC . '/admin/layout-builder/class-alpha-layout-builder-extend.php';

		if ( current_user_can( 'manage_options' ) && is_admin() ) {
			require_once ALPHA_INC . '/admin/layout-builder/class-alpha-layout-builder-admin-extend.php';
		}
	}

	/**
	 * Extends plugins
	 *
	 * @since 4.0
	 */
	public function extend_plugins() {
		if ( class_exists( 'WooCommerce' ) ) {
			require_once ALPHA_INC . '/plugins/woocommerce/class-alpha-woocommerce-extend.php';
		}
		if ( class_exists( 'MEC' ) ) {
			require_once ALPHA_INC . '/plugins/mec/class-alpha-mec.php';
		}
	}

	/**
	 * Extends addons
	 *
	 * @since 4.0
	 */
	public function extend_addons() {
		require_once ALPHA_INC . '/addons/google-fonts-loader/class-alpha-google-fonts-loader.php';
	}

	/**
	 * Add more localize vars
	 *
	 * @since 4.0
	 */
	public function additional_alpha_vars( $vars ) {
		$vars['placeholder_img']    = ALPHA_ASSETS . '/images/placeholders/post-placeholder.jpg';
		$vars['placeholder_alt']    = esc_html__( 'UDesign Placeholder', 'alpha' );
		$vars['placeholder_width']  = 730;
		$vars['placeholder_height'] = 570;
		return $vars;
	}

	/**
	 * Add more image sizes
	 *
	 * @since 4.0
	 */
	public function additional_image_sizes( $image_sizes ) {
		$image_sizes = array_merge(
			$image_sizes,
			array(
				'alpha-thumb-custom-1' => array(
					'width'  => 780,
					'height' => 408,
					'crop'   => true,
				),
				'alpha-thumb-custom-2' => array(
					'width'  => 780,
					'height' => 440,
					'crop'   => true,
				),
				'alpha-thumb-custom-3' => array(
					'width'  => 780,
					'height' => 588,
					'crop'   => true,
				),
				'alpha-thumb-custom-4' => array(
					'width'  => 570,
					'height' => 360,
					'crop'   => true,
				),
				'alpha-thumb-custom-5' => array(
					'width'  => 570,
					'height' => 400,
					'crop'   => true,
				),
			)
		);
		return $image_sizes;
	}

	/**
	 * Compatibility with older versions
	 *
	 * @since 4.1.0
	 */
	public function compatibility() {

		// 4.0 - 4.1.0 Version compatibility
		$option = get_option( 'alpha_registered', -1 );
		if ( -1 != $option ) {
			update_option( ALPHA_NAME . '_registered', $option );
			delete_option( 'alpha_registered' );
		}

		// Layout buidler compatibility
		add_action(
			'wp',
			function() {
				if ( version_compare( get_theme_mod( 'theme_version' ), ALPHA_VERSION, '>=' ) ) {
					return;
				}
				$conditions = get_theme_mod( 'conditions' );

				if ( is_array( $conditions ) ) {
					foreach ( $conditions as $key => $layout_group ) {
						foreach ( $layout_group as &$layout ) {
							if ( empty( $layout['options']['archive_block'] ) && ! empty( $layout['options']['archive_content'] ) ) {
								$layout['options']['archive_block'] = $layout['options']['archive_content'];
								unset( $layout['options']['archive_content'] );
							}
							if ( empty( $layout['options']['single_block'] ) && ! empty( $layout['options']['single_content'] ) ) {
								$layout['options']['single_block'] = $layout['options']['single_content'];
								unset( $layout['options']['single_content'] );
							}
							if ( empty( $layout['options']['single_product_block'] ) && ! empty( $layout['options']['single_product_template'] ) ) {
								$layout['options']['single_product_block'] = $layout['options']['single_product_template'];
								unset( $layout['options']['single_product_template'] );
							}
							if ( empty( $layout['options']['shop_block'] ) && ! empty( $layout['options']['shop_layout_template'] ) ) {
								$layout['options']['shop_block'] = $layout['options']['shop_layout_template'];
								unset( $layout['options']['shop_layout_template'] );
							}
						}
						$conditions[ $key ] = $layout_group;
					}
					set_theme_mod( 'conditions', $conditions );
				}

				set_theme_mod( 'theme_version', ALPHA_VERSION );
			}
		);
	}

	/**
	 * Compatibility with slider revolution
	 *
	 * @since 4.7.0
	 */
	public function disable_revslider_redirection() {
		if ( alpha_doing_ajax() && class_exists( 'RevSlider' ) && get_transient('_revslider_welcome_screen_activation_redirect') ) {
			delete_transient('_revslider_welcome_screen_activation_redirect');
		}
	}

}

new Alpha_Theme();
