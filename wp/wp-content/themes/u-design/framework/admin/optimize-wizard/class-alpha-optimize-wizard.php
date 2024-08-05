<?php
/**
 * Alpha Optimize Wizard
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPHA_OPTIMIZE_WIZARD', ALPHA_FRAMEWORK_ADMIN . '/optimize-wizard' );

if ( ! class_exists( 'Alpha_Optimize_Wizard' ) ) :
	/**
	* Alpha Theme Optimize Wizard
	*
	* @since 1.0
	*/
	class Alpha_Optimize_Wizard extends Alpha_Base {

		/**
		 * The Optimize Wizard Version
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $version = '1.0';

		/**
		 * The current theme name.
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $theme_name = '';

		/**
		 * The current step
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $step = '';

		/**
		 * The wizard steps
		 *
		 * @var array
		 * @since 1.0
		 */
		protected $steps = array();

		/**
		 * The current page slug in optimize wizard.
		 *
		 * @var string
		 * @since 1.0
		 */
		public $page_slug;

		/**
		 * The TGM plugin instance.
		 *
		 * @var mixed
		 * @since 1.0
		 */
		protected $tgmpa_instance;

		/**
		 * The TGM plugin menu slug.
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

		/**
		 * The TGM plugin uri.
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

		/**
		 * The page uri.
		 *
		 * @var string
		 * @since 1.0
		 */
		protected $page_url;

		/**
		 * The files.
		 *
		 * @since 1.0
		 */
		protected $files;

		/**
		 * The constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->current_theme_meta();
			$this->init_actions();
		}

		/**
		 * Current theme meta
		 *
		 * @since 1.0
		 */
		public function current_theme_meta() {
			$current_theme    = wp_get_theme();
			$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
			$this->page_slug  = 'alpha-optimize-wizard';
			$this->page_url   = 'admin.php?page=' . $this->page_slug;
		}

		/**
		 * Init actions
		 *
		 * @since 1.0
		 */
		public function init_actions() {
			add_action( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 2 );

			if ( apply_filters( $this->theme_name . '_enable_optimize_wizard', false ) ) {
				return;
			}

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
				add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
			}

			add_action( 'wp_ajax_alpha_optimize_wizard_resources_optimize', array( $this, 'ajax_optimize_resources' ) );
			add_action( 'wp_ajax_alpha_optimize_wizard_plugins', array( $this, 'ajax_plugins' ) );
			add_action( 'wp_ajax_alpha_optimize_wizard_plugins_deactivate', array( $this, 'ajax_deactivate_plugins' ) );

			if ( isset( $_GET['page'] ) && $this->page_slug === $_GET['page'] ) {
				add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action( 'admin_init', array( $this, 'init_wizard_steps' ), 30 );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 30 );
			}
		}

		/**
		 * Upgrade post install
		 *
		 * @param bool  $return  Installation response.
		 * @param array $theme
		 * @return bool          Installation response.
		 * @since 1.0
		 */
		public function upgrader_post_install( $return, $theme ) {
			if ( is_wp_error( $return ) ) {
				return $return;
			}
			if ( get_stylesheet() != $theme ) {
				return $return;
			}
			update_option( 'alpha_optimize_complete', false );

			return $return;
		}

		/**
		 * Admin redirect.
		 *
		 * @since 1.0
		 */
		public function admin_redirects() {
			ob_start();

			if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'alpha_optimize_complete', false ) ) {
				return;
			}

			delete_transient( '_' . $this->theme_name . '_activation_redirect' );
			wp_safe_redirect( admin_url( $this->page_url ) );
			exit;
		}

		/**
		 * Enqueue optimize wizard assets: css and js.
		 *
		 * @since 1.0
		 */
		public function enqueue() {

			if ( empty( $_GET['page'] ) || $this->page_slug !== $_GET['page'] ) {
				return;
			}

			$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

			// Style
			wp_enqueue_style( 'wp-admin' );
			wp_enqueue_media();

			wp_enqueue_style( 'fontawesome-free' );

			// Script
			wp_enqueue_script( 'alpha-admin-wizard', alpha_framework_uri( '/admin/panel/wizard' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), true, 50 );
			wp_enqueue_script( 'media' );

			require_once alpha_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
			$importer_api = new Alpha_Importer_API();

			$empty_template_types = array();
			if ( class_exists( 'Alpha_Builders' ) ) {
				$template_types = array_keys( Alpha_Builders::get_template_types() );
				foreach ( $template_types as $template_type ) {
					$posts = get_posts(
						array(
							'post_type'   => ALPHA_NAME . '_template',
							'meta_key'    => ALPHA_NAME . '_template_type',
							'meta_value'  => $template_type,
							'numberposts' => 1,
						)
					);
					if ( ! count( $posts ) ) {
						$empty_template_types[] = $template_type;
					}
				}
			}

			wp_localize_script(
				'alpha-admin-wizard',
				'alpha_optimize_wizard_params',
				array(
					'recommendations'  => array(
						'resources'   => array(
							'resource_disable_elementor' => true,
							'resource_disable_emojis'    => true,
							'resource_jquery_footer'     => true,
							'resource_merge_stylesheets' => true,
							'resource_critical_css'      => true,
							'resource_template_builders' => $empty_template_types,
						),
						'lazyload'    => array(
							'lazyload'      => true,
							'lazyload_menu' => true,
							'webfont'       => true,
						),
						'performance' => array(
							'preload_fonts'        => array( 'alpha', 'fas' ),
							'resource_async_js'    => true,
							'resource_split_tasks' => true,
							'resource_after_load'  => true,
						),
					),
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'plugin_list_add'  => $importer_api->get_url( 'plugin_list_add' ),
					'tgm_bulk_url'     => esc_url( admin_url( $this->tgmpa_url ) ),
					'wpnonce'          => wp_create_nonce( 'alpha_optimize_wizard_nonce' ),
					'texts'            => array(
						'loading_failed' => esc_html__( 'Loading Failed', 'alpha' ),
						'failed'         => esc_html__( 'Failed', 'alpha' ),
						'ajax_error'     => esc_html__( 'Ajax error', 'alpha' ),
					),
				)
			);

			ob_start();

		}

		/**
		 * Display optimize wizard
		 *
		 * @since 1.0
		 */
		public function view_optimize_wizard() {
			if ( ! Alpha_Admin::get_instance()->is_registered() ) {
				set_transient( '_alpha_register_redirect', admin_url( 'admin.php?page=alpha-optimize-wizard' ) );
				wp_redirect( admin_url( 'admin.php?page=alpha' ) );
				die();
			}

			$title        = array(
				'title' => esc_html__( 'Optimize Wizard', 'alpha' ),
				'desc'  => ALPHA_DISPLAY_NAME . esc_html__( ' optimize wizard will help you configure a proper website with optimum resources and peak efficiency.', 'alpha' ),
			);
			$admin_config = Alpha_Admin::get_instance()->admin_config;
			Alpha_Admin_Panel::get_instance()->view_header( 'optimize_wizard', $admin_config, $title );
			include alpha_framework_path( ALPHA_OPTIMIZE_WIZARD . '/views/index.php' );
			Alpha_Admin_Panel::get_instance()->view_footer( 'optimize_wizard', $admin_config );
		}

		/**
		 * Evaluate step callback for current.
		 *
		 * @since 1.0
		 */
		public function view_step() {
			$show_content = true;
			if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
				$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
			}
			if ( $show_content && isset( $this->steps[ $this->step ] ) ) {
				call_user_func( $this->steps[ $this->step ]['view'] );
			} else {
				$this->view_resources();
			}
		}

		/**
		 * Get TGM plugin instance
		 *
		 * @since 1.0
		 */
		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		/**
		 * Set TGM plugin url.
		 *
		 * @since 1.0
		 */
		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_optimize_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && 'themes.php' !== $this->tgmpa_instance->parent_slug ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_optimize_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
		}

		/**
		 * Install plugins
		 *
		 * @since 1.0
		 */
		public function tgmpa_load( $status ) {
			return is_admin() || current_user_can( 'install_themes' );
		}

		/**
		 * Get plugins for optimize wizard.
		 *
		 * @since 1.0
		 */
		private function _get_plugins() {
			$instance         = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
			$plugin_func_name = 'is_plugin_active';
			$plugins          = array(
				'all'               => array(), // Meaning: all plugins which still have open actions.
				'install'           => array(),
				'update'            => array(),
				'activate'          => array(),
				'installed'         => array(), // all plugins that installed.
				'network_activated' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( 'optimize_wizard' != $plugin['visibility'] || $instance->$plugin_func_name( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}

			$current = get_option( 'active_plugins', array() );
			if ( is_multisite() ) {
				$network_current = get_site_option( 'active_sitewide_plugins', array() );
			}
			foreach ( $current as $plugin ) {
				$plugins['installed'][ $plugin ] = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			}

			if ( isset( $network_current ) ) {
				$plugins['network_activated'] = $network_current;

				foreach ( $network_current as $slug => $plugin ) {
					$plugins['network_activated'][ $slug ] = get_plugin_data( WP_PLUGIN_DIR . '/' . $slug );
				}
			}

			return $plugins;
		}

		/**
		 * Get used shortcodes
		 *
		 * @since 1.2.0
		 */
		private function get_used_shortcodes( $shortcodes = array(), $return_ids = false, $attrs = array() ) {
			if ( empty( $shortcodes ) ) {
				$shortcodes = $this->get_all_shortcodes();
			}
			global $wpdb, $alpha_settings;
			$post_contents = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_content, post_excerpt FROM $wpdb->posts WHERE post_type not in (%s, %s) AND post_status = 'publish' AND ( post_content != '' or post_excerpt != '')", 'revision', 'attachment' ) );

			$sidebars_array = get_option( 'sidebars_widgets' );
			if ( empty( $post_contents ) || ! is_array( $post_contents ) ) {
				$post_contents = array();
			}
			foreach ( $sidebars_array as $sidebar => $widgets ) {
				if ( ! empty( $widgets ) && is_array( $widgets ) ) {
					foreach ( $widgets as $sidebar_widget ) {
						$widget_type = trim( substr( $sidebar_widget, 0, strrpos( $sidebar_widget, '-' ) ) );
						if ( ! array_key_exists( $widget_type, $post_contents ) ) {
							$post_contents[ $widget_type ] = get_option( 'widget_' . $widget_type );
						}
					}
				}
			}

			$used = array();

			if ( $return_ids ) {
				foreach ( $post_contents as $post_content ) {
					if ( isset( $post_content->ID ) ) {
						$content = $post_content->post_content;
						foreach ( $shortcodes as $shortcode ) {
							if ( false === strpos( $content, '[' ) ) {
								continue;
							}
							if ( empty( $attrs ) && ! in_array( $post_content->ID, $used ) && ( stripos( $content, '[' . $shortcode . ' ' ) !== false ) ) {
								$used[] = $post_content->ID;
							} elseif ( ! empty( $attrs ) && ! in_array( $post_content->ID, $used ) ) {
								$attr_text  = '';
								$attr_text1 = '';
								foreach ( $attrs as $key => $value ) {
									$attr_text = $key . '="' . $value . '"';
									if ( 'yes' == $value ) {
										$attr_text1 = '"' . $key . '":true';
									} else {
										$attr_text1 = '"' . $key . '":"' . $value . '"';
									}
								}
								if ( preg_match( '/\[' . $shortcode . '\s[^]]*' . $attr_text . '[^]]*\]/', $content ) ) {
									$used[] = $post_content->ID;
								}
							}
						}
					}
				}
			} else {
				$excerpt_arr = array(
					'post_content',
					'post_excerpt',
				);
				foreach ( $post_contents as $post_content ) {
					foreach ( $excerpt_arr as $excerpt_key ) {
						if ( is_string( $post_content ) && 'post_excerpt' == $excerpt_key ) {
							break;
						}
						if ( ! is_string( $post_content ) && 'post_excerpt' == $excerpt_key && ! isset( $post_content->post_excerpt ) ) {
							break;
						}
						$content = is_string( $post_content ) ? $post_content : ( isset( $post_content->{$excerpt_key} ) ? $post_content->{$excerpt_key} : '' );

						foreach ( $shortcodes as $shortcode ) {
							if ( false === strpos( $content, '[' ) ) {
								continue;
							}
							if ( ! in_array( $shortcode, $used ) && ( stripos( $content, '[' . $shortcode ) !== false ) ) {
								$used[] = $shortcode;
							}
						}
					}
				}
			}
			/**
			 * Filters the used shortcodes.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_get_used_shortcodes', $used );
		}

		/**
		 * Ajax plugins.
		 *
		 * @since 1.0
		 */
		public function ajax_plugins() {
			if ( ! check_ajax_referer( 'alpha_optimize_wizard_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'No Slug Found',
							'alpha'
						),
					)
				);
			}
			$json = array();
			// send back some json we use to hit up TGM
			$plugins = $this->_get_plugins();
			// what are we doing with this plugin?
			foreach ( $plugins['activate'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => esc_url( admin_url( $this->tgmpa_url ) ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => -1,
						'message'       => esc_html__( 'Activating', 'alpha' ),
					);
					break;
				}
			}
			foreach ( $plugins['update'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => esc_url( admin_url( $this->tgmpa_url ) ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => -1,
						'message'       => esc_html__( 'Updating', 'alpha' ),
					);
					break;
				}
			}
			foreach ( $plugins['install'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => esc_url( admin_url( $this->tgmpa_url ) ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => -1,
						'message'       => esc_html__( 'Installing', 'alpha' ),
					);
					break;
				}
			}

			if ( $json ) {
				$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
				wp_send_json( $json );
			} else {
				wp_send_json(
					array(
						'done'    => 1,
						'message' => esc_html__(
							'Success',
							'alpha'
						),
					)
				);
			}
			exit;
		}

		/**
		 * Deactive plugins.
		 *
		 * @since 1.0
		 */
		public function ajax_deactivate_plugins() {
			if ( ! check_ajax_referer( 'alpha_optimize_wizard_nonce', 'wpnonce' ) || empty( $_POST['url'] ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'No Slug Found',
							'alpha'
						),
					)
				);
			}

			if ( ! current_user_can( 'deactivate_plugin', $plugin ) ) {
				wp_die( esc_html__( 'Sorry, you are not allowed to deactivate this plugin.', 'alpha' ) );
			}

			deactivate_plugins( array( $_POST['url'] ), true, false );
			die();
		}

		/**
		 * Step links
		 *
		 * @since 1.0
		 */
		public function get_step_link( $step ) {
			return add_query_arg( 'step', $step, admin_url( 'admin.php?page=' . $this->page_slug ) );
		}

		/**
		 * Get next step link
		 *
		 * @since 1.0
		 */
		public function get_next_step_link() {
			$keys = array_keys( $this->steps );
			return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ], remove_query_arg( 'translation_updated' ) );
		}

		/**
		 * Init wizard steps.
		 *
		 * @since 1.0
		 */
		public function init_wizard_steps() {
			$this->steps = array(
				'resources'   => array(
					'name'    => esc_html__( 'Resources', 'alpha' ),
					'view'    => array( $this, 'view_resources' ),
					'handler' => '',
				),
				'lazyload'    => array(
					'name'    => esc_html__( 'Lazyload', 'alpha' ),
					'view'    => array( $this, 'view_lazyload' ),
					'handler' => array( $this, 'view_lazyload_save' ),
				),
				'performance' => array(
					'name'    => esc_html__( 'Performance', 'alpha' ),
					'view'    => array( $this, 'view_performance' ),
					'handler' => array( $this, 'view_performance_save' ),
				),
			);

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				$this->steps['plugins'] = array(
					'name'    => esc_html__( 'Plugins', 'alpha' ),
					'view'    => array( $this, 'view_plugins' ),
					'handler' => '',
				);
			};

			$this->steps['ready'] = array(
				'name'    => esc_html__( 'Ready!', 'alpha' ),
				'view'    => array( $this, 'view_ready' ),
				'handler' => '',
			);

			/**
			 * Filters the steps of optimize wizard.
			 *
			 * @since 1.0
			 */
			$this->steps = apply_filters( 'alpha_optimize_wizard_steps', $this->steps );
		}

		/**
		 * View resources step.
		 *
		 * @since 1.0
		 */
		public function view_resources() {
			include alpha_framework_path( ALPHA_OPTIMIZE_WIZARD . '/views/resources.php' );
		}

		public function ajax_optimize_resources() {
			if ( ! check_ajax_referer( 'alpha_optimize_wizard_nonce', 'wpnonce' ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'Nonce Error',
							'alpha'
						),
					)
				);
			}

			// @start feature: fs_plugin_rev
			if ( alpha_get_feature( 'fs_plugin_rev' ) && defined( 'RS_REVISION' ) ) {
				if ( isset( $_POST['resource_disable_rev'] ) && 'true' == $_POST['resource_disable_rev'] && isset( $_POST['rev_pages'] ) ) {
					$resource_disable_rev = true;
					if ( $_POST['rev_pages'] ) {
						$resource_disable_rev_pages = explode( ',', sanitize_text_field( $_POST['rev_pages'] ) );
					}
				} else {
					unset( $resource_disable_rev_pages );
					$resource_disable_rev = false;
				}

				set_theme_mod( 'resource_disable_rev', $resource_disable_rev );
				set_theme_mod( 'resource_disable_rev_pages', $resource_disable_rev_pages );
			}
			// @end feature: fs_plugin_rev

			set_theme_mod( 'resource_disable_gutenberg', isset( $_POST['resource_disable_gutenberg'] ) && 'true' == $_POST['resource_disable_gutenberg'] );
			set_theme_mod( 'resource_disable_wc_blocks', isset( $_POST['resource_disable_wc_blocks'] ) && 'true' == $_POST['resource_disable_wc_blocks'] );
			set_theme_mod( 'resource_disable_elementor', isset( $_POST['resource_disable_elementor'] ) && 'true' == $_POST['resource_disable_elementor'] );

			set_theme_mod( 'resource_disable_emojis', isset( $_POST['resource_disable_emojis'] ) && 'true' == $_POST['resource_disable_emojis'] );
			set_theme_mod( 'resource_disable_jq_migrate', isset( $_POST['resource_disable_jq_migrate'] ) && 'true' == $_POST['resource_disable_jq_migrate'] );
			set_theme_mod( 'resource_jquery_footer', isset( $_POST['resource_jquery_footer'] ) && 'true' == $_POST['resource_jquery_footer'] );
			set_theme_mod( 'resource_merge_stylesheets', isset( $_POST['resource_merge_stylesheets'] ) && 'true' == $_POST['resource_merge_stylesheets'] );
			set_theme_mod( 'resource_critical_css', isset( $_POST['resource_critical_css'] ) && 'true' == $_POST['resource_critical_css'] );
			set_theme_mod( 'resource_template_builders', empty( $_POST['resource_template_builders'] ) ? '' : $_POST['resource_template_builders'] );
			echo 'success';
			die;
		}

		/**
		 * View lazyload step.
		 *
		 * @since 1.0
		 */
		public function view_lazyload() {
			include alpha_framework_path( ALPHA_OPTIMIZE_WIZARD . '/views/lazyload.php' );
		}

		/**
		 * View lazyload save
		 *
		 * @since 1.0
		 */
		public function view_lazyload_save() {
			check_admin_referer( 'alpha-setup-wizard' );

			set_theme_mod( 'mobile_disable_animation', isset( $_POST['mobile_disable_animation'] ) );
			set_theme_mod( 'mobile_disable_slider', isset( $_POST['mobile_disable_slider'] ) );
			set_theme_mod( 'lazyload', isset( $_POST['lazyload'] ) );
			set_theme_mod( 'lazyload_menu', isset( $_POST['lazyload_menu'] ) );
			set_theme_mod( 'skeleton_screen', isset( $_POST['skeleton'] ) );
			set_theme_mod( 'google_webfont', isset( $_POST['webfont'] ) );
			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			die;
		}

		/**
		 * View performance step.
		 *
		 * @since 1.0
		 */
		public function view_performance() {
			include alpha_framework_path( ALPHA_OPTIMIZE_WIZARD . '/views/performance.php' );
		}

		/**
		 * View performance save.
		 *
		 * @since 1.0
		 */
		public function view_performance_save() {
			check_admin_referer( 'alpha-setup-wizard' );

			set_theme_mod( 'mobile_disable_animation', isset( $_POST['mobile_disable_animation'] ) );
			set_theme_mod( 'mobile_disable_slider', isset( $_POST['mobile_disable_slider'] ) );

			$preload_fonts = alpha_get_option( 'preload_fonts' );
			if ( empty( $preload_fonts ) ) {
				$preload_fonts = array();
			}
			if ( isset( $_POST['preload_fonts'] ) ) {
				$preload_fonts = array_map( 'sanitize_text_field', $_POST['preload_fonts'] );
			} else {
				$preload_fonts = array();
			}
			if ( isset( $_POST['preload_fonts_custom'] ) ) {
				$preload_fonts['custom'] = sanitize_textarea_field( $_POST['preload_fonts_custom'] );
			}
			set_theme_mod( 'preload_fonts', $preload_fonts );

			set_theme_mod( 'font_face_display', isset( $_POST['font_face_display'] ) );

			set_theme_mod( 'resource_async_js', isset( $_POST['resource_async_js'] ) );
			set_theme_mod( 'resource_split_tasks', isset( $_POST['resource_split_tasks'] ) );
			set_theme_mod( 'resource_after_load', isset( $_POST['resource_after_load'] ) );

			// Compile Theme Style
			if ( alpha_get_option( 'typo_user_custom' ) ) {
				require_once alpha_framework_path( ALPHA_FRAMEWORK_ADMIN . '/customizer/class-alpha-customizer.php' );
				require_once alpha_framework_path( ALPHA_FRAMEWORK_ADMIN . '/customizer/dynamic/dynamic-color-lib.php' );
				require_once alpha_framework_path( ALPHA_FRAMEWORK_ADMIN . '/customizer/customizer-function.php' );

				Alpha_Customizer::get_instance()->save_theme_options();
			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			die();
		}

		/**
		 * View plugins step.
		 *
		 * @since 1.0
		 */
		public function view_plugins() {
			include alpha_framework_path( ALPHA_OPTIMIZE_WIZARD . '/views/plugins.php' );
		}

		/**
		 * View ready step.
		 *
		 * @since 1.0
		 */
		public function view_ready() {
			include alpha_framework_path( ALPHA_OPTIMIZE_WIZARD . '/views/ready.php' );
		}
	}
endif;

add_action( 'after_setup_theme', 'alpha_theme_optimize_optimize_wizard', 10 );

if ( ! function_exists( 'alpha_theme_optimize_optimize_wizard' ) ) :
	function alpha_theme_optimize_optimize_wizard() {
		Alpha_Optimize_Wizard::get_instance();
	}
endif;
