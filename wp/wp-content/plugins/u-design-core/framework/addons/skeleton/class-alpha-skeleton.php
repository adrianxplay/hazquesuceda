<?php
/**
 * Skeleton screen for lazyload.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
class Alpha_Skeleton extends Alpha_Base {

	/**
	 * The current skeleton part. e.g: sidebar, product, product_cat, post
	 *
	 * @var string
	 * @since 1.0
	 */
	public $is_doing = '';

	/**
	 * Constructor
	 *
	 * Add actions and filters for skeleton.
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );

		if ( ! defined( 'WOOF_VERSION' ) ) {
			// Sidebar skeleton
			add_action( 'alpha_sidebar_content_start', array( $this, 'sidebar_content_start' ) );
			add_action( 'alpha_sidebar_content_end', array( $this, 'sidebar_content_end' ) );
			add_filter( 'alpha_sidebar_classes', array( $this, 'sidebar_classes' ) );

			// Posts (archive + single) skeleton
			add_filter( 'alpha_post_loop_wrapper_classes', array( $this, 'post_loop_wrapper_class' ) );
			add_filter( 'alpha_post_single_class', array( $this, 'post_loop_wrapper_class' ) );
			add_action( 'alpha_post_loop_before_item', array( $this, 'post_loop_before_item' ) );
			add_action( 'alpha_post_loop_after_item', array( $this, 'post_loop_after_item' ) );

			// Archive products & categories skeleton
			add_filter( 'alpha_product_loop_wrapper_classes', array( $this, 'product_loop_wrapper_class' ) );
			add_action( 'alpha_product_loop_before_item', array( $this, 'product_loop_before_item' ) );
			add_action( 'alpha_product_loop_after_item', array( $this, 'product_loop_after_item' ) );
			add_action( 'alpha_product_loop_before_cat', array( $this, 'product_loop_before_cat' ) );
			add_action( 'alpha_product_loop_after_cat', array( $this, 'product_loop_after_cat' ) );
		}

		// Single product skeleton
		add_filter( 'alpha_single_product_classes', array( $this, 'single_product_classes' ) );
		add_action( 'alpha_before_product_gallery', array( $this, 'before_product_gallery' ), 20 );
		add_action( 'alpha_after_product_gallery', array( $this, 'after_product_gallery' ), 20 );

		if ( ! defined( 'ALPHA_FRAMEWORK_VENDORS' ) && ! class_exists( 'Uni_Cpo' ) ) {
			// We disable skeleton screen for single product page's summary and tabs,
			// because it has too many compatibility issues.
			add_action( 'alpha_before_product_summary', array( $this, 'before_product_summary' ), 20 );
			add_action( 'alpha_after_product_summary', array( $this, 'after_product_summary' ), 20 );
			add_action( 'alpha_wc_product_before_tabs', array( $this, 'before_product_tabs' ), 20 );
			add_action( 'woocommerce_product_after_tabs', array( $this, 'after_product_tabs' ), 20 );
		}
		// Menu lazyload skeleton
		add_filter( 'alpha_menu_lazyload_content', array( $this, 'menu_skeleton' ), 10, 4 );

		/**
		 * Fires after skeleton initializing.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_after_skeleton', $this );
	}

	/**
	 * Enqueue style and script.
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'alpha-skeleton', alpha_core_framework_uri( '/addons/skeleton/skeleton' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
		wp_enqueue_script( 'alpha-skeleton', alpha_core_framework_uri( '/addons/skeleton/skeleton' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
		wp_localize_script(
			'alpha-skeleton-js',
			'lib_skeleton',
			apply_filters(
				'alpha_lib_skeleton',
				array(
					'lazyload' => alpha_get_option( 'lazyload' ),
				)
			)
		);
	}

	/**
	 * The skeleton content start of each page ( post archive, single post, product archive ... )
	 *
	 * @since 1.0
	 */
	public function sidebar_content_start() {
		$layout_type = alpha_get_page_layout();

		if ( 'single_post' == $layout_type || 'single_product' == $layout_type || 0 === strpos( $layout_type, 'archive_' ) ) {
			ob_start();
			$this->is_doing = 'sidebar';
		}
	}

	/**
	 * The skeleton content end of each page ( post archive, single post, product archive ... )
	 *
	 * @since 1.0
	 */
	public function sidebar_content_end() {
		if ( 'sidebar' == $this->is_doing ) {
			echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
			echo '<div class="widget-2"></div>';
		}

		$this->is_doing = '';
	}

	/**
	 * Adds the sidebar classes.
	 *
	 * @param  array $class The class name
	 * @return array Return the class list.
	 * @since 1.0
	 */
	public function sidebar_classes( $class ) {
		$layout_type = alpha_get_page_layout();

		if ( ! in_array( 'top-sidebar', $class ) && ( 'single_post' == $layout_type || 'single_product' == $layout_type || 'archive_product' == $layout_type || 0 === strpos( $layout_type, 'archive_' ) ) ) {
			$class[] = 'skeleton-body';
		}
		return $class;
	}

	/**
	 * Adds the product loop classes.
	 *
	 * @param  array $classes The class list.
	 * @return array Return the class list.
	 * @since 1.0
	 */
	public function product_loop_wrapper_class( $classes ) {
		if ( ! $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'archive_product' == $layout_type || 'single_product' == $layout_type ) {
				$classes[] = 'skeleton-body';
			}
		}
		return $classes;
	}

	/**
	 * Product loop before item
	 *
	 * @since 1.0
	 */
	public function product_loop_before_item() {
		if ( ! $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'archive_product' == $layout_type || 'single_product' == $layout_type ) {
				ob_start();
				$this->is_doing = 'product';
			}
		}
	}

	/**
	 * Product loop after item
	 *
	 * @since 1.0
	 */
	public function product_loop_after_item( $product_type ) {
		if ( 'product' == $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'archive_product' == $layout_type || 'single_product' == $layout_type ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				echo '<div class="skel-pro' . ( 'list' == $product_type ? ' skel-pro-list' : '' ) . '"></div>';
				$this->is_doing = '';
			}
		}
	}

	/**
	 * Product loop before category.
	 *
	 * @since 1.0
	 */
	public function product_loop_before_cat() {
		if ( ! $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'archive_product' == $layout_type || 'single_product' == $layout_type ) {
				ob_start();
				$this->is_doing = 'product_cat';
			}
		}
	}

	/**
	 * Product loop after category.
	 *
	 * @param string $product_type The product type
	 * @since 1.0
	 */
	public function product_loop_after_cat( $product_type ) {
		if ( 'product_cat' == $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'archive_product' == $layout_type || 'single_product' == $layout_type ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				echo '<div class="skel-cat"></div>';
				$this->is_doing = '';
			}
		}
	}

	/**
	 * Post loop wrapper class
	 *
	 * @param array $classes The class list
	 * @since 1.0
	 */
	public function post_loop_wrapper_class( $classes ) {
		if ( ! $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'archive_post' == $layout_type || 'single_post' == $layout_type || ( 0 === strpos( $layout_type, 'archive_' ) && in_array( 'alpha-posts-grid-container', $classes ) ) ) {
				$classes[] = 'skeleton-body';
			}
		}
		return $classes;
	}

	/**
	 * The post loop before item
	 *
	 * @since 1.0
	 */
	public function post_loop_before_item() {
		if ( ! $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'single_post' == $layout_type || 0 === strpos( $layout_type, 'archive_' ) ) {
				ob_start();
				$this->is_doing = 'post';
			}
		}
	}

	/**
	 * The post loop after item
	 *
	 * @since 1.0
	 */
	public function post_loop_after_item() {
		if ( 'post' == $this->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 'single_post' == $layout_type || 0 === strpos( $layout_type, 'archive_' ) ) {
				echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
				$class = 'skel-post';
				echo '<div class="' . alpha_escaped( $class ) . '"></div>';
				$this->is_doing = '';
			}
		}
	}

	/**
	 * Add the classes on single product page.
	 *
	 * @param array $classes The class list
	 * @since 1.0
	 */
	public function single_product_classes( $classes ) {
		if ( ! $this->is_doing ) {
			$classes[] = 'skeleton-body';
		}
		return $classes;
	}

	/**
	 * Before product gallery.
	 *
	 * @since 1.0
	 */
	public function before_product_gallery() {
		if ( ! $this->is_doing ) {
			ob_start();
			$this->is_doing = 'product_gallery';
		}
	}

	/**
	 * After product gallery
	 *
	 * @since 1.0
	 */
	public function after_product_gallery() {
		if ( 'product_gallery' == $this->is_doing ) {
			echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
			echo '<div class="skel-pro-gallery"></div>';
			$this->is_doing = '';
		}
	}

	/**
	 * Before product summary
	 *
	 * @since 1.0
	 */
	public function before_product_summary() {
		if ( ! $this->is_doing ) {
			ob_start();
			$this->is_doing = 'product_summary';
		}
	}

	/**
	 * After product summary
	 *
	 * @since 1.0
	 */
	public function after_product_summary() {
		if ( 'product_summary' == $this->is_doing ) {
			echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
			echo '<div class="skel-pro-summary"></div>';
			$this->is_doing = '';
		}
	}

	/**
	 * Before product tabs
	 *
	 * @since 1.0
	 */
	public function before_product_tabs() {
		if ( ! $this->is_doing ) {
			ob_start();
			$this->is_doing = 'product_tabs';
		}
	}

	/**
	 * After product tabs
	 *
	 * @since 1.0
	 */
	public function after_product_tabs() {
		if ( 'product_tabs' == $this->is_doing ) {
			echo '<script type="text/template">' . json_encode( ob_get_clean() ) . '</script>';
			echo '<div class="skel-pro-tabs"></div>';
			$this->is_doing = '';
		}
	}

	/**
	 * The menu skeleton.
	 *
	 * @since 1.0
	 */
	public function menu_skeleton( $content, $megamenu, $megamenu_width, $megamenu_pos ) {
		if ( ! $this->is_doing && alpha_get_option( 'lazyload_menu' ) ) {
			if ( $megamenu ) {
				$class = '';
				$style = '';

				if ( $megamenu_width ) {
					$class .= ' mp-' . $megamenu_pos;
					$style .= ' style="width: ' . $megamenu_width . 'px;';

					if ( 'center' == $megamenu_pos ) {
						$style .= ' left: calc( 50% - ' . $megamenu_width / 2 . 'px );"';
					} else {
						$style .= '"';
					}
				} else {
					$class .= ' full-megamenu';
				}

				return '<ul class="megamenu' . $class . ' skel-megamenu"' . $style . '>';
			} else {
				return '<ul class="submenu skel-menu">';
			}
		}
		return $content;
	}

	/**
	 * Prevent skeleton
	 *
	 * @since 1.0
	 */
	static public function prevent_skeleton() {
		Alpha_Skeleton::get_instance()->is_doing = 'stop';
	}

	/**
	 * Stop to prevent skeleton
	 *
	 * @since 1.0
	 */
	static public function stop_prevent_skeleton() {
		Alpha_Skeleton::get_instance()->is_doing = '';
	}
}

Alpha_Skeleton::get_instance();
