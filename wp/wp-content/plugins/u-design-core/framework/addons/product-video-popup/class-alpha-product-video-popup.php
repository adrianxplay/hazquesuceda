<?php
/**
 * Alpha Product Video Popup
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Video_Popup' ) ) {

	class Alpha_Product_Video_Popup extends Alpha_Base {

		/**
		 * Video
		 *
		 * @since 1.0
		 * @access public
		 */
		public $video_code = '';


		/**
		 * Constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {

			// Enqueue admin styles
			if ( is_admin() && ( ( isset( $_REQUEST['post'] ) && 'product' == get_post_type( $_REQUEST['post'] ) ) || ( 'post-new.php' == $GLOBALS['pagenow'] && ! empty( $_REQUEST['post_type'] ) && 'product' == $_REQUEST['post_type'] ) ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
			}

			add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'template_redirect', array( $this, 'add_front_end_actions' ) );
		}

		/**
		 * Enqueue admin scripts
		 *
		 * @since 1.0
		 * @access public
		 */
		public function enqueue_admin_scripts() {
			wp_enqueue_style( 'alpha-product-video-popup-admin', alpha_core_framework_uri( '/addons/product-video-popup/product-video-popup-admin.min.css' ), null, ALPHA_CORE_VERSION, 'all' );
		}

		/**
		 * Add metaboxes to add video
		 *
		 * @since 1.0
		 * @access public
		 */
		public function add_meta_boxes( $meta_boxes ) {
			$meta_boxes[] = array(
				'id'         => 'alpha-product-videos',
				'title'      => esc_html__( 'Product Video', 'alpha-core' ),
				'post_types' => array( 'product' ),
				'context'    => 'side',
				'priority'   => 'low',
				'fields'     => array(
					array(
						'name' => esc_html__( 'Product Video', 'alpha-core' ),
						'id'   => 'alpha_product_video_thumbnail',
						'type' => 'video',
						'std'  => false,
					),
					array(
						'name' => esc_html__( 'Product Video Url', 'alpha-core' ),
						'id'   => 'alpha_product_video_popup_url',
						'type' => 'input',
						'std'  => false,
						'desc' => esc_html__( 'Enter URL of Youtube or Vimeo or specific filetypes such as mp4, webm, ogv.', 'alpha-core' ),
					),
				),
			);

			return $meta_boxes;
		}


		/**
		 * Hooks to render video popup in frontend
		 *
		 * @since 1.0
		 * @access public
		 */
		public function add_front_end_actions() {
			$video_url       = get_post_meta( get_the_ID(), 'alpha_product_video_popup_url', true );
			$video_thumbnail = get_post_meta( get_the_ID(), 'alpha_product_video_thumbnail', true );

			if ( $video_url && filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
				// To use default browser's video player.
				$this->video_code = '<video src="' . esc_url( $video_url ) . '" autoplay loop controls>';
			}
			if ( ! empty( $video_thumbnail ) && wp_get_attachment_url( $video_thumbnail ) ) {
				$this->video_code = '<video src="' . esc_url( wp_get_attachment_url( $video_thumbnail ) ) . '" autoplay loop controls>';
			}
			if ( ! empty( $this->video_code ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );
				add_action( 'alpha_single_product_gallery_buttons', array( $this, 'get_video_viewer_btn' ), 20 );
				add_filter( 'alpha_vars', array( $this, 'add_video_var' ) );
			}
		}


		/**
		 * Load product video popup script.
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'jquery-fitvids' );
			wp_enqueue_script( 'alpha-product-video-popup', alpha_core_framework_uri( '/addons/product-video-popup/product-video-popup' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
		}


		/**
		 * Pass degree viewer images to js.
		 *
		 * @since 1.0
		 */
		public function add_video_var( $vars ) {
			$vars['wvideo_data'] = '<div class="d-flex">' . $this->video_code . '</div>';
			return $vars;
		}

		/**
		 * Print Video view button in product image.
		 *
		 * @since 1.0
		 */
		public function get_video_viewer_btn( $buttons ) {
			return $buttons . '<button class="product-gallery-btn open-product-video-viewer ' . ALPHA_ICON_PREFIX . '-icon-movie" aria-label="' . esc_html__( 'Product Video Thumbnail', 'alpha-core' ) . '" title="' . esc_html__( 'Product Video Thumbnail', 'alpha-core' ) . '"></button>';
		}
	}
}

Alpha_Product_Video_Popup::get_instance();
