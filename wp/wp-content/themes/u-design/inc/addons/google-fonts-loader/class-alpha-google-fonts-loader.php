<?php
/**
 * Handles Fonts Load
 *
 * @package Alpha
 * @since 4.0.0
 */

// Do not allow direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


/**
 * Alpha Fonts Loader
 *
 * @since 4.0.0
 */

class Alpha_Fonts_Loader {

	/**
	 * The singleton instance
	 *
	 * @since 4.0.0
	 * @static
	 * @access private
	 */
	private static $instance = null;


	/**
	 * All of google fonts to be used in theme
	 *
	 * @since 4.0.0
	 * @access private
	 * @var array
	 */
	private $google_fonts = [];


	/**
	 * Google Font API Link
	 *
	 * @since 4.0.0
	 * @access private
	 * @var string
	 */
	private $external_link = '';



	/**
	 * Get a single instance of the object (singleton).
	 *
	 * @since 4.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Alpha_Fonts_Loader();
		}
		return self::$instance;
	}



	/**
	 * Constructor
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp', array( $this, 'init' ) );

		// Enqueue google font
		add_action( 'wp_enqueue_script', array( $this, 'enqueue_google_fonts' ) );
	}


	public function init() {
		// Get google fonts to be loaded
		$this->get_google_fonts();

		// For later extended.
		$this->google_fonts = apply_filters( 'alpha_google_fonts', $this->google_fonts );

		// Create external link for google font load
		$this->generate_external_link();
	}


	/**
	 * Enqueue google fonts
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function enqueue_google_fonts() {
		wp_enqueue_style( 'alpha-google-fonts', $this->external_link );
	}


	/**
	 * Get typo settings from theme options
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function get_google_fonts() {

		$typos          = array(
			'typo_default',
			'typo_heading',
			'typo_custom1',
			'typo_custom2',
			'typo_custom3',
			'typo_ptb_title',
			'typo_ptb_subtitle',
			'typo_ptb_breadcrumb',
		);
		$theme_op_fonts = [];
		$fonts          = [];

		foreach ( $typos as $typo ) {

			$family = isset( alpha_get_option( $typo )['font-family'] ) ? alpha_get_option( $typo )['font-family'] : '';

			if ( 'inherit' == $family || 'initial' == $family || '' == $family ) {
				continue;
			}

			$t = alpha_get_option( $typo );

			if ( ! isset( $t['variant'] ) ) {
				$weight = '400';
			} elseif ( 'normal' == $t['variant'] || 'regular' == $t['variant'] ) {
				$weight = '400';
			} elseif ( 'italic' == $t['variant'] ) {
				$weight = '400italic';
			} else {
				$weight = $t['variant'];
			}

			if ( ! array_key_exists( $family, $theme_op_fonts ) ) {
				$theme_op_fonts[ $family ] = array( '300', '400', '500', '600', '700' );
			}

			if ( ! in_array( $weight, $theme_op_fonts[ $family ] ) ) {
				$theme_op_fonts[ $family ][] = $weight;
			}
		}

		$fonts = $theme_op_fonts;

		// Get block-used fonts
		global $alpha_layout;
		if ( ! empty( $alpha_layout['used_blocks'] ) ) {
			foreach ( $alpha_layout['used_blocks'] as $block_id => $block_content ) {
				$block_fonts = json_decode( rawurldecode( get_post_meta( $block_id, 'alpha_vc_google_fonts', true ) ), true );
				if ( ! empty( $block_fonts ) ) {
					$fonts = array_merge_recursive( $theme_op_fonts, $block_fonts );
				}
			}
		}

		// Get singular-used fonts
		if ( is_singular() ) {
			$page_id    = get_the_ID();
			$page_fonts = json_decode( rawurldecode( get_post_meta( $page_id, 'alpha_vc_google_fonts', true ) ), true );

			if ( ! empty( $page_fonts ) ) {
				$fonts = array_merge_recursive( $fonts, $page_fonts );
			}
		}

		foreach ( $fonts as $family => $weight ) {
			$weight               = array_unique( $weight );
			$this->google_fonts[] = str_replace( ' ', '+', $family ) . ':' . implode( ',', $weight );
		}
	}


	/**
	 * Get external link to load google fonts
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function generate_external_link() {

		$this->external_link = 'https://fonts.googleapis.com/css?family=' . implode( '%7C', $this->google_fonts );

		if ( 'block' !== alpha_get_option( 'font_face_display' ) ) {
			$this->external_link .= '&display=swap';
		}
	}


	/**
	 * Get fonts to be preloaded
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function get_preload_fonts_tags() {

	}
}

Alpha_Fonts_Loader::get_instance();
