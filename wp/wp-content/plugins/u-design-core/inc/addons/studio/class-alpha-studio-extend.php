<?php
/**
 * Alpha Studio Extend
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
* @since      4.1
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Studio_Extend' ) ) :

	/**
	 * The Alpha Studio class
	 *
	 * @since 1.0
	 */
	class Alpha_Studio_Extend {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// Extend Block Categories
			add_filter( 'alpha_studio_category', array( $this, 'extend_studio_categories' ) );
			add_filter( 'alpha_studio_big_category', array( $this, 'extend_studio_big_categories' ) );
			add_filter( 'alpha_studio_has_children', array( $this, 'extend_studio_has_children_categories' ) );
			add_filter( 'alpha_studio_block_category', array( $this, 'extend_studio_block_categories' ) );
			add_filter( 'alpha_studio_blocks_args', array( $this, 'extend_studio_blocks_args' ) );

			if ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) {
				if ( defined( 'ELEMENTOR_VERSION' ) && function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
					add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ), 30 );
				} elseif ( defined( 'WPB_VC_VERSION' ) && function_exists( 'alpha_is_wpb_preview' ) && alpha_is_wpb_preview() ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
				} elseif ( 'post.php' != $GLOBALS['pagenow'] || 'edit' == $_REQUEST['action'] ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
				}
			} elseif ( ! wp_doing_ajax() || ! isset( $_POST['type'] ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
			}
		}

		public function enqueue() {
			wp_enqueue_style( 'alpha-studio-extend', ALPHA_CORE_URI . '/inc/addons/studio/studio-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
			wp_enqueue_script( 'alpha-studio-extend', ALPHA_CORE_URI . '/inc/addons/studio/studio-extend' . ALPHA_JS_SUFFIX, array( 'alpha-studio' ), ALPHA_CORE_VERSION, true );
		}

		public function extend_studio_big_categories( $categories ) {
			return array( 'featured', 'header', 'page_title_bar', 'block', 'footer', 'popup', 'template', 'favourites', 'my-templates' );
		}

		public function extend_studio_categories( $categories ) {
			$categories = array_merge(
				$categories,
				array(
					'page_title_bar' => esc_html__( 'Page Title Bar', 'alpha-core' ),
					'products'       => esc_html__( 'Products', 'alpha-core' ),
					'posts'          => esc_html__( 'Posts', 'alpha-core' ),
					'projects'       => esc_html__( 'Projects', 'alpha-core' ),
					'team'           => esc_html__( 'Team', 'alpha-core' ),
					'featured'       => esc_html__( 'Featured', 'alpha-core' ),
					'300_250'        => esc_html__( '300 * 250', 'alpha-core' ),
					'300_600'        => esc_html__( '300 * 600', 'alpha-core' ),
					'320_100'        => esc_html__( '320 * 100', 'alpha-core' ),
					'336_280'        => esc_html__( '336 * 280', 'alpha-core' ),
					'728_90'         => esc_html__( '728 * 90', 'alpha-core' ),
				)
			);
			return $categories;
		}

		public function extend_studio_has_children_categories( $categories ) {
			$categories = array_merge(
				$categories,
				array(
					'featured',
				)
			);
			return $categories;
		}

		public function extend_studio_block_categories( $categories ) {
			$categories = array_merge(
				$categories,
				array(
					'products',
					'posts',
					'projects',
					'team',
				)
			);
			return $categories;
		}

		public function extend_studio_blocks_args( $args ) {
			$args = array_merge(
				$args,
				array(
					'featured_categories' => array( '300_250', '300_600', '320_100', '336_280', '728_90' ),
				)
			);
			return $args;
		}
	}

	new Alpha_Studio_Extend;

endif;
