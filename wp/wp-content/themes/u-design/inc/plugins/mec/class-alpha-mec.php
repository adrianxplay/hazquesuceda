<?php
/**
 * Alpha Mordern Event Calendar
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_MEC' ) ) {

	/**
	 * Alpha_MEC
	 */
	class Alpha_MEC extends Alpha_Base {

		/**
		 * post type
		 */
		public $post_type = 'mec-events';

		/**
		 * Constructor
		 *
		 * @since 4.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			// Mec layout compatible with theme
			add_action( 'mec_before_main_content', array( $this, 'mec_before_main_content' ) );
			add_action( 'mec_after_main_content', array( $this, 'mec_after_main_content' ) );
			add_filter( 'mec_do_redirection_after_activation', '__return_false' );
		}

		/**
		 * Enqueue styles
		 *
		 * @since 4.0
		 * @access public
		 */
		public function enqueue_styles() {

			// enqueue theme style
			wp_enqueue_style( 'alpha-mec-frontend-style', ALPHA_INC_URI . '/plugins/mec/mec' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
			wp_enqueue_script( 'alpha-sticky-lib' );
		}

		/**
		 * Print theme's layout before main
		 *
		 * @since 4.0
		 * @access public
		 */
		public function mec_before_main_content() {

			do_action( 'alpha_before_content' );
			?>
			<div class="page-content">
				<?php do_action( 'alpha_print_before_page_layout' ); ?>
			<?php
		}

		/**
		 * Print theme's layout after main
		 *
		 * @since 4.0
		 * @access public
		 */
		public function mec_after_main_content() {

			do_action( 'alpha_after_content' );
			?>
			</div>
			<?php do_action( 'alpha_print_after_page_layout' ); ?>
			<?php
		}
	}
}

Alpha_MEC::get_instance();
