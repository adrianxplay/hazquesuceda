<?php
/**
 * Alpha FrameWork Assets Extend Class
 *
 * Enqueue framework assets including css, js and images.
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_Assets_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 10 );
		add_action( 'wp_footer', array( $this, 'enqueue_theme_js_css' ) );
		add_filter( 'alpha_exclude_style', array( $this, 'exclude_style' ) );
	}
	/**
	 * Register styles and scripts.
	 *
	 * @since 4.0
	 */
	public function register_scripts() {
		// Styles
		if ( alpha_get_option( 'legacy_icons' ) ) {
			wp_register_style( 'alpha-framework-icons', ALPHA_ASSETS . '/vendor/wpalpha-icons/legacy/css/icons.min.css', array(), ALPHA_VERSION );
		} else {
			wp_register_style( 'alpha-framework-icons', ALPHA_ASSETS . '/vendor/wpalpha-icons/' . alpha_get_option( 'icon_weight' ) . '/css/icons.min.css', array(), ALPHA_VERSION );
		}
	}

	/**
	 * Enqueue theme js at last.
	 *
	 * @since 4.1
	 */
	public function enqueue_theme_js_css() {
		// Framework Icon
		wp_enqueue_style( 'alpha-framework-icons' );

		// Alert Style
		if ( defined( 'ALPHA_CORE_INC_URI' ) ) {
			wp_enqueue_style( 'alpha-alert', ALPHA_CORE_INC_URI . '/widgets/alert/alert' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		}

		$layout = alpha_get_page_layout();
		if ( 'single_' == substr( $layout, 0, 7 ) && defined( 'ALPHA_CORE_INC_URI' ) ) {
			// Social Icon
			wp_enqueue_style( 'alpha-share', ALPHA_CORE_INC_URI . '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		}
	}

	/**
	 * Enqueue styles and scripts for admin.
	 *
	 * @since 4.2
	 */
	public function enqueue_admin_scripts() {
		// Framework Icon
		if ( alpha_get_option( 'legacy_icons' ) ) {
			wp_enqueue_style( 'alpha-framework-icons', ALPHA_ASSETS . '/vendor/wpalpha-icons/legacy/css/icons.min.css', array(), ALPHA_VERSION );
		} else {
			wp_enqueue_style( 'alpha-framework-icons', ALPHA_ASSETS . '/vendor/wpalpha-icons/' . alpha_get_option( 'icon_weight' ) . '/css/icons.min.css', array(), ALPHA_VERSION );
		}
	}

	public function exclude_style( $styles ) {
		$styles[] = 'alpha-framework-icons';
		return $styles;
	}
}


Alpha_Assets_Extend::get_instance();
