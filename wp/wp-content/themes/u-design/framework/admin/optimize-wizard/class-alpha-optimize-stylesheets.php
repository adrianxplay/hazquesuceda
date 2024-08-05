<?php
/**
 * Alpha Optimize Stylesheets
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

/**
 *  Alpha Optimize Stylesheets Class
 *  1. Merge Stylesheets
 *  2. Critical Css
 *  3. Optmize Scripts
 *
 * @since 1.2.0
 */
class Alpha_Optimize_Stylesheets extends Alpha_Base {

	/**
	 * Belongs to plugin we bundle.
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public $including_plugins = array(
		'woocommerce',
		'wc',
		'prettyPhoto',
		'jquery-blockui', // woocommerce
		'js-cookie', // woocommerce
		'jquery-cookie', // woocommerce
		'zoom', // woocommerce
		'contact-form-7',
		'revslider',
		'tp-tools', // revolution slider
		'revmin',
		'rs-plugin-settings', // revolution slider
		'alpha',
		'jquery-countdown', // alpha framework
		'jquery-skrollr', // alpha framework
		'jquery-autocomplete', // alpha framework
		'wc',
		'elementor',
		'e-animations',
		'jquery-selectBox',
		'wpforms',
		'wp-block-library',
		'gglcptch', // google captcha
		'mec-',  // modern events calendar
		'featherlight',
	);
	/**
	 * Exclude Javascript.
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public $exclude_javascript = array(
		'elementor-common-modules', // if login elementor
		'elementor-dialog',
		'elementor-common',
		'elementor-app-loader',
		'elementor-waypoints',
		'elementor-admin-bar',
		'elementor-web-cli',
		'alpha-layout-builder',
	);
	/**
	 * Exclude Style.
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public $exclude_style = array(
		'alpha-layout-builder',
		'alpha-google-fonts',
		'alpha-icons',
		'elementor-icons',
		'elementor-common', // if login elementor
		// 'alpha-dynamic-vars',
		'mec-font-icons',  // modern events calendar
		'mec-google-fonts',
	);

	/**
	 * The google fonts and elementor-icons for elementor
	 *
	 * @since 1.2.0
	 */
	public $defer_elementor_style = array();
	/**
	 * Removed resources because of merge
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public $removed_resources = array();

	/**
	 * Css var
	 *
	 * @access private
	 * @since 1.2.0
	 */
	private $css_vars = array();

	/**
	 * The existing of merged css.
	 *
	 * @var bool
	 * @since 1.2.1
	 */
	public $is_merged_style;

	/**
	 * is merged?
	 */
	public $is_merged = false;

	/**
	 * The Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'optimize_scripts' ), PHP_INT_MAX );
		if ( ! defined( 'WPSEO_VERSION' ) ) {
			add_action( 'wp_head', array( $this, 'add_og_meta' ), 4 );
		}
		add_filter( 'language_attributes', array( $this, 'add_og_doctype' ) );

		if ( alpha_get_option( 'resource_disable_emojis' ) ) {
			add_action( 'init', array( $this, 'disable_emojis' ), 1 );
		}
		if ( alpha_get_option( 'resource_disable_jq_migrate' ) ) {
			add_action( 'wp_default_scripts', array( $this, 'disable_jq_migrate' ) );
		}
		if ( ! is_admin() ) {
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				add_action(
					'template_redirect',
					function() {
						add_action( 'wp_head', array( $this, 'elementor_google_fonts' ), 7 );
					},
					11
				);
			}
		}

		if ( ! empty( $_REQUEST['mobile_url'] ) || ! empty( $_REQUEST['desktop_url'] ) ) {
			return;
		}
		if ( isset( $_REQUEST['action'] ) && 'yith-woocompare-view-table' == $_REQUEST['action'] ) {
			return;
		}
		// Merge css and js => Only Frontend and except elementor preview.
		if ( function_exists( 'alpha_is_elementor_preview' ) && ! is_customize_preview() && ( ! is_admin() || ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'alpha_load_menu' == $_REQUEST['action'] ) ) && ! alpha_is_elementor_preview() && get_theme_mod( 'resource_merge_stylesheets' ) ) {
			$this->is_merged = true;
			global $alpha_body_merged_css;
			$alpha_body_merged_css = '';
			/**
			 * Filters the included plugins.
			 *
			 * @since 1.0
			 */
			$this->including_plugins = apply_filters( 'alpha_include_plugins', $this->including_plugins );
			/**
			 * Filters the excluded style.
			 *
			 * @since 1.0
			 */
			$this->exclude_style = apply_filters( 'alpha_exclude_style', $this->exclude_style );
			/**
			 * Filters the excluded js.
			 *
			 * @since 1.0
			 */
			$this->exclude_javascript = apply_filters( 'alpha_exclude_javascript', $this->exclude_javascript );
			add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_resources' ), PHP_INT_MAX );
			add_action( 'wp_print_footer_scripts', array( $this, 'dequeue_resources' ), 9 );
			add_action( 'wp_print_footer_scripts', array( $this, 'merge_js_css' ), 9 );
			add_filter( 'alpha_dynamic_vars', array( $this, 'get_css_vars' ), 20 );
			ob_start();
			include_once alpha_framework_path( ALPHA_FRAMEWORK_PATH . '/admin/customizer/customizer-function.php' );
			include_once alpha_framework_path( ALPHA_FRAMEWORK_PATH . '/admin/customizer/dynamic/dynamic_vars.php' );
			ob_get_clean();
		}
	}

	/**
	 * Adding the Open Graph in language attributes.
	 *
	 * @since 1.3.0
	 */
	public function add_og_doctype( $doctype ) {
		return $doctype . ' prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#"';
	}

	/**
	 * Add Open Graph Meta Fields.
	 *
	 * @since 1.3.0
	 */
	public function add_og_meta() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;

		$image = '';
		if ( ! has_post_thumbnail( $post->ID ) ) {
			if ( ! empty( get_theme_mod( 'custom_logo' ) ) ) {
				$image = get_theme_mod( 'custom_logo' );
			}
		} else {
			$image = get_post_thumbnail_id( $post->ID );
		}
		$thumbnail_src = wp_get_attachment_image_src( $image, 'full' );
		if ( ! empty( $thumbnail_src[0] ) ) {
			$image = esc_attr( $thumbnail_src[0] );
		}

		if ( is_array( $image ) ) {
			$image = ( isset( $image['url'] ) && ! empty( $image['url'] ) ) ? $image['url'] : '';
		}

		$meta_title = strip_tags( str_replace( [ '"', "'" ], [ '&quot;', '&#39;' ], $post->post_title ) );
		?>
		<meta name="twitter:card" content="summary_large_image">
		<meta property="twitter:title" content="<?php echo esc_attr( $meta_title ); ?>"/>
		<meta property="og:title" content="<?php echo esc_attr( $meta_title ); ?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:url" content="<?php echo esc_url_raw( get_permalink() ); ?>"/>
		<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
		<meta property="og:description" content="<?php echo wp_strip_all_tags( alpha_trim_description( $post->post_content ) ); ?>"/>

		<?php if ( '' != $image ) : // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison ?>
			<?php if ( is_array( $image ) ) : ?>
				<?php if ( isset( $image['url'] ) ) : ?>
					<meta property="og:image" content="<?php echo esc_url_raw( $image['url'] ); ?>"/>
				<?php endif; ?>
			<?php else : ?>
				<meta property="og:image" content="<?php echo esc_url_raw( $image ); ?>"/>
			<?php endif; ?>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get Alpha Framework Css Variables.
	 *
	 * @since 1.2.0
	 */
	public function get_css_vars( $css_vars ) {
		foreach ( $css_vars as $selector => $value_array ) {
			foreach ( $value_array as $var => $value ) {
				$this->css_vars[ $var ] = $value;
			}
		}
		return $css_vars;
	}

	/**
	 * Combine all javascript files and stylesheets.
	 * This function combines all resources including javascripts and stylesheets.
	 *
	 * @since 1.2.0
	 */
	public function merge_js_css() {
		$merged_css = $this->get_uri( 'css', 'path' );
		$merged_js  = $this->get_uri( 'js', 'path' );
		if ( ! file_exists( $merged_css ) ) {
			$this->merge_rc( 'css', $this->exclude_style );
		}
		if ( ! file_exists( $merged_js ) ) {
			$this->merge_rc( 'js', $this->exclude_javascript );
		}
		wp_enqueue_style( 'alpha-merged' );
		wp_enqueue_script( 'alpha-merged' );
	}

	/**
	 * Dequeue resources which are merged in a file.
	 *
	 * @since 1.2.0
	 */
	public function dequeue_resources() {
		if ( doing_action( 'wp_enqueue_scripts' ) ) {
			wp_register_style( 'alpha-merged', $this->get_uri( 'css', 'uri' ), array(), ALPHA_VERSION );
			wp_register_script( 'alpha-merged', $this->get_uri( 'js', 'uri' ), array(), ALPHA_VERSION );
		}
		$this->remove_resources( 'css' );
		$this->remove_resources( 'js' );
	}

	/**
	 * Dequeue and deregister scripts
	 *
	 * @param string $rc_type The resource type: css, js
	 * @since 1.2.0
	 */
	public function remove_resources( $rc_type = 'css' ) {
		global $wp_styles, $wp_scripts;
		$wp_resources = ( 'css' == $rc_type ? $wp_styles : $wp_scripts );
		$wp_resources->all_deps( $wp_resources->queue );
		foreach ( $wp_resources->to_do as $enqueued_index => $file ) {
			// Don't use print stylesheets
			if ( 'print' == $wp_resources->registered[ $file ]->args || empty( $wp_resources->registered[ $file ]->src ) ) {
				continue;
			}
			if ( str_replace( $this->including_plugins, '', $file ) != $file && ! in_array( $file, 'css' == $rc_type ? $this->exclude_style : $this->exclude_javascript ) ) {
				$this->removed_resources[ $file . '-' . $rc_type ] = array(
					'src'  => $wp_resources->registered[ $file ]->src,
					'type' => $rc_type,
				);
				$add_inline                                        = array( 'before', 'after' );
				foreach ( $add_inline as $pos ) {
					if ( ! empty( $wp_resources->registered[ $file ]->extra[ $pos ] ) ) {
						$res = &$wp_resources->registered[ $file ]->extra[ $pos ];
						if ( is_array( $res ) ) {
							$res = implode( PHP_EOL, $res );
						}
						$this->removed_resources[ $file . '-' . $rc_type ][ 'data' == $pos ? 'before' : $pos ] = $res;
						$res = '';
					}
				}
				$wp_resources->registered[ $file ]->src = '';
			}
		}
		$wp_resources->to_do = array();
	}

	/**
	 * Merge Resources: javascript and stylesheets.
	 *
	 * @param string $rc_type The resource type which you are going to merge.
	 * @since 1.2.0
	 */
	public function merge_rc( $rc_type = 'css', $exclude_rc = array() ) {
		global $wp_styles, $wp_scripts;
		$wp_resources = ( 'css' == $rc_type ? $wp_styles : $wp_scripts );

		// Combine all stylesheets.
		$resources = '';
		foreach ( $this->removed_resources as $index => $file ) {
			if ( $rc_type == $file['type'] ) {
				$contents = '';
				if ( ! empty( $file['before'] ) ) {
					$contents .= $file['before'];
				}
				$contents .= $this->get_file_uri_contents( $file['src'] );
				if ( ! empty( $file['after'] ) ) {
					$contents .= $file['after'];
				}
				if ( 'css' == $rc_type ) {
					if ( 'alpha-flag-css' == $index || 'alpha-theme-css' == $index ) {
						$contents = str_replace( 'url(..', 'url(' . get_parent_theme_file_uri() . '/assets', $contents );
					}
					if ( 'alpha-theme-shop-css' == $index ) {
						$contents = str_replace( 'url(../..', 'url(' . get_parent_theme_file_uri() . '/assets', $contents );
					}

					if ( false !== strpos( $file['src'], 'woocommerce' ) && false !== strpos( $file['src'], 'default-skin' ) && defined( 'WC_PLUGIN_FILE' ) ) {
						$contents = str_replace( 'url(', 'url(' . plugins_url( '/', WC_PLUGIN_FILE ) . 'assets/css/photoswipe/default-skin/', $contents );
					}

					if ( false !== strpos( $file['src'], 'contact-form-7' ) && function_exists( 'wpcf7_plugin_url' ) ) {
						$contents = str_replace( '../../assets/ajax-loader.gif', wpcf7_plugin_url( 'assets/ajax-loader.gif' ), $contents );
					}

					if ( false !== strpos( $file['src'], 'revslider' ) && function_exists( 'get_rs_plugin_url' ) ) {
						$contents = str_replace( "url('..", "url('" . get_rs_plugin_url() . 'public/assets', $contents );
						$contents = str_replace( 'url(..', 'url(' . get_rs_plugin_url() . 'public/assets', $contents );
						$contents = str_replace( array( 'url(openhand.cur)' ), 'url(' . get_rs_plugin_url() . 'public/assets/css/openhand.cur)', $contents );
						$contents = str_replace( array( 'url(closedhand.cur)' ), 'url(' . get_rs_plugin_url() . 'public/assets/css/closedhand.cur)', $contents );
					}

					// modern events calendar
					if ( false !== strpos( $file['src'], 'modern-events-calendar' ) && defined( 'MEC_ABSPATH' ) ) {
						$contents = str_replace( 'url(..', 'url(' . plugins_url( '/', MEC_ABSPATH . MEC_FILENAME ) . 'assets', $contents );
					}
				}
				$resources .= apply_filters( 'alpha_replace_merged_css', $contents, $index ) . PHP_EOL;
			}
		}
		if ( 'css' == $rc_type ) {
			// Because of --alpha-primary-color-hover and --alpha-primary-color
			$var_names = array_map( 'strlen', array_keys( $this->css_vars ) );
			array_multisort( $var_names, SORT_DESC, $this->css_vars );
			foreach ( $this->css_vars as $var => $value ) {
				if ( is_string( $value ) ) {
					$resources = $this->css_var_to_static( $var, $value, $resources );
				}
			}

			global $alpha_body_merged_css;
			if ( ! empty( $alpha_body_merged_css ) ) {
				$resources .= $alpha_body_merged_css . PHP_EOL;
			}
		}

		global $wp_filesystem;
		// Initialize the WordPress filesystem, no more using file_put_contents function
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		try {
			ob_start();
			print( alpha_escaped( $resources ) );
			$upload_rc_file = $this->get_uri( $rc_type, 'path' );
			$upload_path    = dirname( $upload_rc_file );
			if ( ! file_exists( $upload_path ) ) {
				wp_mkdir_p( $upload_path );
			}
			// check file mode and make it writable.
			if ( is_writable( $upload_path ) == false ) {
				@chmod( get_theme_file_path( $upload_rc_file ), 0755 );
			}
			if ( file_exists( $upload_rc_file ) ) {
				if ( is_writable( $upload_rc_file ) == false ) {
					@chmod( $upload_rc_file, 0755 );
				}
				@unlink( $upload_rc_file );
			}

			$wp_filesystem->put_contents( $upload_rc_file, ob_get_clean(), FS_CHMOD_FILE );
		} catch ( Exception $e ) {
			var_dump( $e );
		}
	}

	/**
	 * Replace all css vars to static.
	 *
	 * @param string $var   The name of css var.
	 * @param string $value The Value of css var.
	 * @param string $css   The Page Style.
	 * @return string       The Static Style
	 * @since 1.2.0
	 */
	public function css_var_to_static( $var, $value, $css ) {
		$css = str_replace( "var($var)", $value, $css ); // color: var(--alpha-primary-color);

		// Check if we have var(--alpha-primary-color,#08c) and replace them accordingly.
		/**
		 * if $css => html {
		 *              color: var(--alpha-primary-color,#08c);
		 *            }
		 *            body {
		 *              color: var(--alpha-primary-color,#08c);
		 *            }
		 *  The Result is $matches
		 *          array(1) {
		 *              [0]=>
		 *              array(2) {
		 *                  [0]=>
		 *                  string(31) "var(--alpha-primary-color,#08c)"
		 *                  [1]=>
		 *                  string(31) "var(--alpha-primary-color,#08c)"
		 *              }
		 *          }
		 */
		if ( preg_match_all( "/var\($var.*\)/U", $css, $matches ) ) {
			$matches = array_unique( $matches[0] );
			// foreach var variables.
			foreach ( $matches as $match ) {
				// $match is var(--alpha-primary-color,#08c)
				$replacement = $value;
				// like var(--alpha-primary-color-op-80, rgba(0,136,20)) because of the regex.
				$match = str_pad( $match, strlen( $match ) + substr_count( $match, '(' ) - substr_count( $match, ')' ), ')' );
				if ( '' === $value ) {
					$default = explode( "var($var,", $match );
					// Remove the last trailing ) that is there because of the regex.
					$default     = substr( $default[1], 0, -1 );
					$replacement = $default;
				}
				$css = str_replace( $match, $replacement, $css );
			}
		}
		return $css;
	}

	/**
	 * Get the url of resources
	 *
	 * @since 1.2.0
	 */
	public function get_uri( $file_type = 'css', $path = 'uri' ) {

		$blog_id = '';
		if ( is_multisite() ) {
			$current_site = get_blog_details();
			if ( $current_site->blog_id > 1 ) {
				$blog_id = ALPHA_NAME . "_site-{$current_site->blog_id}";
			}
		}

		$id        = md5( $this->get_current_page_id() );
		$file_name = "{$id}";
		if ( $blog_id ) {
			$file_name = "{$blog_id}-{$id}";
		}

		$upload_dir = wp_upload_dir();
		if ( is_ssl() ) {
			$upload_dir['baseurl'] = str_replace( 'http://', 'https://', $upload_dir['baseurl'] );
		}
		if ( 'uri' == $path ) {
			return $upload_dir['baseurl'] . '/' . ALPHA_NAME . '_merged_resources/' . $file_name . '.' . $file_type;
		} else {
			return $upload_dir['basedir'] . '/' . ALPHA_NAME . '_merged_resources/' . $file_name . '.' . $file_type;
		}
	}

	/**
	 * Gets the current page ID.
	 *
	 * @return bool|int
	 * @since 1.2.0
	 */
	public function get_current_page_id() {

		global $wp_query;
		if ( is_404() ) { // 404 page
			return '404-page';
		}
		if ( is_search() ) { // search page
			if ( ! empty( $_REQUEST['post_type'] ) ) {
				return 'search-page-' . $_REQUEST['post_type'];
			}
			return 'search-page';
		}
		if ( get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) && is_home() ) {
			return get_option( 'page_for_posts' );
		}
		if ( ! $wp_query ) {
			return false;
		}
		$page_id = get_queried_object_id();

		// Shop page.
		if ( ! is_admin() && class_exists( 'WooCommerce' ) && is_shop() ) {
			return (int) get_option( 'woocommerce_shop_page_id' );
		}
		// Product Taxonomy Page - Category and Tag and Brand.
		if ( ! is_admin() && class_exists( 'WooCommerce' ) && ( ! is_shop() && ( is_tax( 'product_brand' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) ) {
			return $page_id . '-archive';
		}
		// Homepage.
		if ( 'posts' === get_option( 'show_on_front' ) && is_home() ) {
			return $page_id;
		}
		if ( ! is_singular() && is_archive() ) {
			if ( empty( $page_id ) ) {
				$page_id = get_post_type();
				if ( is_tax() ) {
					$queried = get_queried_object();
					if ( isset( $queried ) && ! empty( $queried->slug ) && ! empty( $queried->taxonomy ) ) {
						$page_id .= '-' . $queried->slug . '-' . $queried->taxonomy;
					}
				}
			}
			return $page_id . '-archive';
		}
		if ( ! is_singular() ) {
			return false;
		}
		return $page_id;
	}

	/**
	 * Get file data.
	 *
	 * @param string $uri Import demo file path.
	 * @since 1.2.0
	 */
	public function get_file_uri_contents( $uri ) {
		if ( false === strstr( $uri, 'http' ) ) { // no http or https
			$uri = dirname( dirname( get_theme_root_uri() ) ) . $uri;
		}
		// $response = wp_remote_get( str_replace( 'https', 'http', $uri ) );
		$response = wp_remote_get( $uri );
		$data     = '';
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$data = wp_remote_retrieve_body( $response );
		}
		return $data;
	}

	/**
	 * Returns the existing of merged css.
	 *
	 * @return bool
	 * @since 1.2.1
	 */
	public function has_merged_css() {
		if ( empty( $this->is_merged_style ) ) {
			$merged_css = $this->get_uri( 'css', 'path' );
			if ( file_exists( $merged_css ) ) {
				$this->is_merged_style = 'yes';
			} else {
				$this->is_merged_style = 'no';
			}
		}
		return $this->is_merged_style;
	}

	/**
	 * Optimize Scripts
	 *
	 * Remove yith scripts
	 * Remove woocommerce scripts
	 * Remove gutenberg and wc gutenberg block style
	 * Defer loading jquery-core.
	 *
	 * @since 1.2.0
	 */
	public function optimize_scripts() {
		// @start feature: fs_plugin_rev
		global $post;

		if ( class_exists( 'RevSlider' ) && alpha_get_option( 'resource_disable_rev' ) ) {
			$use_revslider = false;

			if ( ! $use_revslider && alpha_get_option( 'resource_disable_rev_pages' ) ) {
				$rev_pages = alpha_get_option( 'resource_disable_rev_pages' );

				if ( $rev_pages && ! empty( $rev_pages ) ) {
					if ( ! is_search() && ! is_404() && isset( $post->ID ) && in_array( $post->ID, $rev_pages ) ) {
						$use_revslider = true;
					}
				}
			}

			if ( ! $use_revslider ) {
				wp_dequeue_style( 'rs-plugin-settings' );
				wp_dequeue_script( 'tp-tools' );
				wp_dequeue_script( 'revmin' );
			}
		}
		// @end feature: fs_plugin_rev

		// Optimize disable
		if ( alpha_get_option( 'resource_disable_gutenberg' ) ) {
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'wp-block-library' );
		}
		if ( alpha_get_option( 'resource_disable_wc_blocks' ) ) {
			wp_dequeue_style( 'wc-block-style' );
			wp_deregister_style( 'wc-block-style' );
			wp_dequeue_style( 'wc-block-vendors-style' );
			wp_deregister_style( 'wc-block-vendors-style' );
		}

		if ( ! is_admin_bar_showing() ) {
			wp_dequeue_style( 'dashicons' );
		}

		// load jquery-core and migrate in footer.
		if ( alpha_get_option( 'resource_jquery_footer' ) ) {
			wp_scripts()->add_data( 'jquery', 'group', 1 );
			wp_scripts()->add_data( 'jquery-core', 'group', 1 );
			wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
		}
		// swiper defer loading
		wp_scripts()->add_data( 'swiper', 'group', 1 );

	}

	/**
	 * Remove elementor-icons in <head> tag
	 *
	 * @since 1.2.0
	 */
	public function elementor_google_fonts() {
		global $wp_styles;
		foreach ( $wp_styles->queue as $style ) {
			if ( false !== strpos( $style, 'google-fonts' ) ) {
				$this->defer_elementor_style[ $style ] = $wp_styles->registered[ $style ];
				wp_dequeue_style( $style );
			}
		}
		if ( ! empty( $wp_styles->registered['elementor-icons'] ) ) {
			$this->defer_elementor_style['elementor-icons'] = $wp_styles->registered['elementor-icons'];
			unset( $wp_styles->registered['elementor-icons'] );
			wp_dequeue_style( 'elementor-icons' );
		}

		// modern events calendar
		if ( defined( 'MEC_ABSPATH' ) ) {
			if ( ! empty( $wp_styles->registered['mec-font-icons'] ) ) {
				$this->defer_elementor_style['mec-font-icons'] = $wp_styles->registered['mec-font-icons'];
				unset( $wp_styles->registered['mec-font-icons'] );
				wp_dequeue_style( 'mec-font-icons' );
			}
			if ( ! empty( $wp_styles->registered['mec-google-fonts'] ) ) {
				$this->defer_elementor_style['mec-google-fonts'] = $wp_styles->registered['mec-google-fonts'];
				unset( $wp_styles->registered['mec-google-fonts'] );
				wp_dequeue_style( 'mec-google-fonts' );
			}
		}

		if ( ! empty( count( $this->defer_elementor_style ) ) ) {
			add_action( 'wp_footer', array( $this, 'defer_load_elementor_icons_font' ), 9 );
		}
	}
	/**
	 * Defer load the elementor-icons and google font.
	 *
	 * @since 1.2.0
	 */
	public function defer_load_elementor_icons_font() {
		if ( ! empty( count( $this->defer_elementor_style ) ) ) {
			foreach ( $this->defer_elementor_style as $font => $value ) {
				wp_enqueue_style( $font, $value->src, $value->deps, $value->ver );
			}
		}
	}
	/**
	 * Disable jquery migrate
	 *
	 * @since 1.2.0
	 */
	public function disable_jq_migrate( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];

			if ( $script->deps ) {
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}

	/**
	 * Disable emojis.
	 *
	 * @since 1.2.0
	 */
	public function disable_emojis() {

		// Remove all default emojis script.
		add_filter(
			'wp_resource_hints',
			function( $urls, $relation_type ) {
				if ( 'dns-prefetch' === $relation_type ) {
					$urls = array_diff( $urls, array( apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/11/svg/' ) ) );
				}
				return $urls;
			},
			10,
			2
		);
		add_filter(
			'tiny_mce_plugins',
			function( $plugins ) {
				if ( is_array( $plugins ) ) {
					return array_diff( $plugins, array( 'wpemoji' ) );
				}
				return array();
			}
		);

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		if ( function_exists( 'alpha_clean_filter' ) ) {
			alpha_clean_filter( 'the_content_feed', 'wp_staticize_emoji' );
			alpha_clean_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			alpha_clean_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		}
	}
}
Alpha_Optimize_Stylesheets::get_instance();
