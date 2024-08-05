<?php
/**
 * Alpha Core Elementor
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * @version    1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
	return;
}

define( 'ALPHA_CORE_ELEMENTOR', ALPHA_CORE_PLUGINS . '/elementor' );
define( 'ALPHA_CORE_ELEMENTOR_URI', ALPHA_CORE_PLUGINS_URI . '/elementor' );

use Elementor\Frontend;
use Elementor\Plugin;
use Elementor\Core\Files\CSS\Global_CSS;
use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
use Elementor\Core\Settings\Manager as SettingsManager;

class Alpha_Core_Elementor extends Alpha_Base {

	/**
	 * Check if dom is optimized
	 *
	 * @since 1.0
	 *
	 * @var boolean $is_dom_optimized
	 */
	public static $is_dom_optimized = false;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		// Include Partials
		$partials = apply_filters(
			'alpha_elementor_partials',
			array(
				'addon'       => true,
				'hotspot'     => true,
				'banner'      => true,
				'creative'    => true,
				'grid'        => true,
				'chart'       => true,
				'slider'      => true,
				'button'      => true,
				'tab'         => true,
				'testimonial' => true,
			)
		);
		foreach ( $partials as $partial => $active ) {
			if ( $active ) {
				include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/partials/' . $partial . '.php' );
			}
		}

		/**
		 * Fires after default partials for extending.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_extend_elementor_partials' );

		if ( apply_filters( 'alpha_use_conditional_rendering', true ) ) {
			include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/conditional-rendering/class-alpha-conditional-rendering.php' );
		}

		// Register controls, widgets, elements, icons
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/controls/register', array( $this, 'register_control' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
		add_action( 'elementor/elements/elements_registered', array( $this, 'register_element' ) );
		add_action( 'elementor/elements/elements_registered', array( $this, 'register_element_addons' ) );
		add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'alpha_add_icon_library' ) );
		add_filter( 'elementor/controls/animations/additional_animations', array( $this, 'add_appear_animations' ), 10, 1 );

		// Load Elementor CSS and JS
		if ( alpha_is_elementor_preview() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_preview_scripts' ) );
		}

		// Disable elementor resource.
		if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'resource_disable_elementor' ) && ! current_user_can( 'edit_pages' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'resource_disable_elementor' ), 99 );
			add_action( 'elementor/widget/before_render_content', array( $this, 'enqueue_theme_alternative_scripts' ) );

			// Do not update dynamic css for visitors.
			add_action( 'init', array( $this, 'remove_dynamic_css_update' ) );
		} else {
			/**
			 * Register runtime widgets for elementor extended widgets
			 *
			 * @since 4.6
			 */
			add_filter(
				'elementor/widget/render_content',
				function( $widget_content, $self ) {
					if ( in_array( $self->get_name(), array( ALPHA_NAME . '_widget_iconlist', ALPHA_NAME . '_widget_heading' ) ) && ! in_array( $self->get_name(), $self::$registered_runtime_widgets ) ) {
						$self->register_runtime_widget( $self->get_name() );
					}
					return $widget_content;
				},
				10,
				2
			);
		}

		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_admin_styles' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'remove_fontawesome' ), 11 );

		// Include Elementor Admin JS
		add_action(
			'elementor/editor/after_enqueue_scripts',
			function() {
				if ( defined( 'ALPHA_VERSION' ) ) {
					if ( alpha_get_option( 'legacy_icons' ) ) {
						wp_enqueue_style( 'alpha-icons', ALPHA_ASSETS . '/vendor/icons/legacy/css/icons.min.css', array(), ALPHA_VERSION );
					} else {
						wp_enqueue_style( 'alpha-icons', ALPHA_ASSETS . '/vendor/icons/' . alpha_get_option( 'icon_weight' ) . '/css/icons.min.css', array(), ALPHA_VERSION );
					}
					wp_enqueue_style( 'alpha-admin-dynamic', ALPHA_CSS . '/dynamic_vars.min.css', array(), ALPHA_VERSION );
					wp_enqueue_script( 'alpha-admin', alpha_framework_uri( '/admin/admin/admin' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_VERSION, true );
				}

				$ui_theme_selected = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

				$ui_themes = [
					'light',
					'dark',
				];

				if ( 'auto' === $ui_theme_selected || ! in_array( $ui_theme_selected, $ui_themes, true ) ) {
					$ui_light_theme_media_queries = '(prefers-color-scheme: light)';
					$ui_dark_theme_media_queries  = '(prefers-color-scheme: dark)';
				} else {
					$ui_light_theme_media_queries = 'none';
					$ui_dark_theme_media_queries  = 'none';

					if ( 'light' === $ui_theme_selected ) {
						$ui_light_theme_media_queries = 'all';
					} elseif ( 'dark' === $ui_theme_selected ) {
						$ui_dark_theme_media_queries = 'all';
					}
				}

				wp_enqueue_style( 'alpha-elementor-admin-skin-light', alpha_core_framework_uri( '/plugins/elementor/assets/elementor-admin-skin-light.min.css' ), array(), ALPHA_VERSION, $ui_light_theme_media_queries );
				wp_enqueue_style( 'alpha-elementor-admin-skin-dark', alpha_core_framework_uri( '/plugins/elementor/assets/elementor-admin-skin-dark.min.css' ), array(), ALPHA_VERSION, $ui_dark_theme_media_queries );

				wp_enqueue_script( 'alpha-elementor-admin', alpha_core_framework_uri( '/plugins/elementor/assets/elementor-admin' . ALPHA_JS_SUFFIX ), array( 'elementor-editor' ), ALPHA_CORE_VERSION, true );
			}
		);

		add_action(
			'elementor/editor/footer',
			function() {
				ob_start();
				?>
			<script type="text/template" id="tmpl-alpha-elementor-studio-notice">
				<a href="#" id="alpha-panel-studio" onclick="window.parent.runStudio(this);" class="elementor-button elementor-button-default"><i class="alpha-icon-studio" aria-hidden="true"></i><?php echo ALPHA_DISPLAY_NAME . esc_html__( ' Studio', 'alpha-core' ); ?></a>
			</script>
				<?php
				echo ob_get_clean();
			}
		);

		// Force Loading Elementor Preloaded Modules js because of section video.
		add_action(
			'wp_footer',
			function() {
				if ( class_exists( 'Alpha_Optimize_Stylesheets' ) ) {
					$cur_page_id  = (int) Alpha_Optimize_Stylesheets::get_instance()->get_current_page_id();
					$the_document = Plugin::$instance->documents->get( $cur_page_id );
					if ( $the_document && $the_document->is_built_with_elementor() ) {
						wp_enqueue_script( 'preloaded-modules', ELEMENTOR_URL . 'assets/js/preloaded-modules' . ALPHA_JS_SUFFIX, array( 'elementor-frontend' ), ELEMENTOR_VERSION, true );
					}
				}
			}
		);

		// Add Elementor Page Custom CSS
		if ( wp_doing_ajax() ) {
			add_action( 'elementor/document/before_save', array( $this, 'save_page_custom_css_js' ), 10, 2 );
			add_action( 'elementor/document/after_save', array( $this, 'save_elementor_page_css_js' ), 10, 2 );
		}

		// Init Elementor Document Config
		add_filter( 'elementor/document/config', array( $this, 'init_elementor_config' ), 10, 2 );

		// Register Document Controls
		add_action( 'elementor/documents/register_controls', array( $this, 'register_document_controls' ) );

		// Add Custom CSS & JS to Alpha Elementor Addons
		add_filter( 'alpha_builder_addon_html', array( $this, 'add_custom_css_js_addon_html' ) );

		// Because elementor removes all callbacks, add it again
		add_action( 'elementor/editor/after_enqueue_scripts', 'alpha_print_footer_scripts' );

		// Add Template Builder Classes
		add_filter( 'body_class', array( $this, 'add_body_class' ) );

		// Add shape divider
		add_action( 'elementor/shapes/additional_shapes', array( $this, 'add_shape_dividers' ) );
		add_action( 'elementor/element/section/section_shape_divider/after_section_end', array( $this, 'add_custom_shape_divider' ), 10, 2 );
		add_action( 'elementor/element/container/section_shape_divider/after_section_end', array( $this, 'add_custom_shape_divider' ), 10, 2 );

		// Add reveal effect color
		add_action( 'elementor/element/before_section_end', array( $this, 'add_motion_effect_controls' ), 20, 3 );

		// Compatabilities
		add_filter( 'elementor/widgets/wordpress/widget_args', array( $this, 'add_wp_widget_args' ), 10, 2 );

		// Is dom optimized?
		// if ( version_compare( ELEMENTOR_VERSION, '3.1.0', '>=' ) ) {
		// 	alpha_elementor_if_dom_optimization() = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		// } elseif ( version_compare( ELEMENTOR_VERSION, '3.0', '>=' ) ) {
		// 	alpha_elementor_if_dom_optimization() = ( ! \Elementor\Plugin::instance()->get_legacy_mode( 'elementWrappers' ) );
		// }
		// Load Used Block CSS
		/*
		 * Get Dependent Elementor Styles
		 * Includes Kit style and post style
		 */
		add_action( 'elementor/css-file/post/enqueue', array( $this, 'get_dependent_elementor_styles' ) );
		add_action( 'alpha_before_enqueue_theme_style', array( $this, 'add_global_css' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_elementor_css' ), 30 );
		add_action( 'alpha_before_enqueue_custom_css', array( $this, 'add_elementor_page_css' ), 20 );
		add_action( 'alpha_before_enqueue_custom_css', array( $this, 'add_block_css' ) );

		// Force generate alpha elementor block css
		if ( wp_doing_ajax() && empty( $_REQUEST['demo'] ) ) {
			add_action( 'save_post', array( $this, 'generate_block_temp_css_onsave' ), 99, 2 );
			add_action( 'elementor/document/after_save', array( $this, 'rename_block_temp_css_onsave' ), 99, 2 );
			add_action( 'elementor/core/files/clear_cache', array( $this, 'generate_blocks_css_after_clear_cache' ) );
		}

		// Elementor Custom Control Manager
		require_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/restapi/select2.php' );
		require_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/controls_manager/controls.php' );

		// Add parallax addon controls
		require_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/tabs/parallax/class-alpha-parallax-elementor.php' );

		// Dynamic Tags
		add_action( 'elementor/init', array( $this, 'init_module' ) );

		// Gutter spacing option
		add_action( 'elementor/element/kit/section_settings-layout/before_section_end', array( $this, 'add_kit_options' ), 10, 2 );

		// Banner srcset
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'attachment_image_attributes' ), 10, 3 );
	}

	/**
	 * Initialize Elementor Module
	 *
	 * @since 1.0
	 */
	public function init_module() {
		// @start feature: fs_plugin_acf
		require_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/dynamic_tags/dynamic_tags.php' );
		// @end feature: fs_plugin_acf
	}

	/**
	 * Add gutter options to kit
	 *
	 * @since 1.0
	 */
	public function add_kit_options( $self, $args ) {
		if ( alpha_elementor_if_container_active() ) {
			$self->remove_control( 'container_padding' );
		} else {
			$self->add_responsive_control(
				'gutter_space',
				array(
					'label'       => esc_html__( 'Gutter Spacing', 'alpha-core' ),
					'description' => esc_html__( 'Sets the default space inside the section.' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 50,
						),
					),
					'selectors'   => array(
						'html body' => '--alpha-gap: calc({{SIZE}}{{UNIT}} / 2);',
					),
				),
				array(
					'position' => array(
						'at' => 'after',
						'of' => 'container_width',
					),
				)
			);
		}
	}

	// Register new Category
	public function register_category( $self ) {
		$self->add_category(
			'alpha_widget',
			array(
				'title'  => ALPHA_DISPLAY_NAME . esc_html__( ' Widgets', 'alpha-core' ),
				'active' => true,
			)
		);
	}

	// Register new Control
	public function register_control( $self ) {

		$controls = apply_filters(
			'alpha_elementor_register_control',
			array(
				'ajaxselect2',
				'description',
				'image_choose',
				'origin_position',
			)
		);

		foreach ( $controls as $control ) {
			include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/controls/' . $control . '.php' );
			$class_name = 'Alpha_Control_' . ucfirst( $control );
			$self->register( new $class_name() );
		}
	}


	public function register_element() {
		// Elementor Custom Advanced Tab Sections
		include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/tabs/widget-advanced-tabs.php' );

		if ( alpha_elementor_if_container_active() ) {
			include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/elements/container.php' );
			Elementor\Plugin::$instance->elements_manager->unregister_element_type( 'container' );
			Elementor\Plugin::$instance->elements_manager->register_element_type( new Alpha_Element_Container() );
		}

		include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/elements/section.php' );
		Elementor\Plugin::$instance->elements_manager->unregister_element_type( 'section' );
		Elementor\Plugin::$instance->elements_manager->register_element_type( new Alpha_Element_Section() );

		include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/elements/column.php' );
		Elementor\Plugin::$instance->elements_manager->unregister_element_type( 'column' );
		Elementor\Plugin::$instance->elements_manager->register_element_type( new Alpha_Element_Column() );
	}


	// Register new Widget
	public function register_widget( $self ) {
		/* Remove elementor default common widget and register ours */
		include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/tabs/widget-advanced-tabs.php' );
		$self->unregister( 'common' );
		include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/elements/widget-common.php' );
		$self->register( new Alpha_Common_Elementor_Widget( array(), array( 'widget_name' => 'common' ) ) );

		$widgets = array(
			'heading'             => true,
			'block'               => true,
			'banner'              => true,
			'breadcrumb'          => true,
			'countdown'           => true,
			'button'              => true,
			'image-gallery'       => true,
			'search'              => true,
			'testimonial-group'   => true,
			'image-box'           => true,
			'share'               => true,
			'menu'                => true,
			'subcategories'       => true,
			'hotspot'             => true,
			'logo'                => true,
			'iconlist'            => true,
			'svg-floating'        => true,
			'animated-text'       => true,
			'bar-chart'           => true,
			'line-chart'          => true,
			'highlight'           => true,
			'pie-doughnut-chart'  => true,
			'polar-chart'         => true,
			'radar-chart'         => true,
			'flipbox'             => true,
			'image-compare'       => true,
			'price-tables'        => true,
			'table'               => true,
			'progressbars'        => true,
			'timeline'            => true,
			'timeline-horizontal' => true,
			'contact'             => true,
			'360-degree'          => true,
			'posts-grid'          => true,
			'scroll-progress'     => true,
			'sticky-nav'          => true,
			'circle-progressbar'  => true,
			'filter'              => true,
			'circles-info'        => true,
		);

		if ( class_exists( 'WooCommerce' ) ) {

			// @start feature: fs_widget_vendor
			if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'WCMp' ) || class_exists( 'WCFM' ) || class_exists( 'WC_Vendors' ) ) {
				$widgets['vendor'] = true;
			}
			// @end feature: fs_widget_vendor
			// @start feature: fs_widget_brands
			if ( ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'brand' ) ) ) {
				$widgets['brands'] = true;
			}
			// @end feature: fs_widget_brands
		}

		if ( defined( 'WPCF7_VERSION' ) ) {
			$widgets['contact-form'] = true;
		}

		/**
		 * Filters the widgets which provide by elementor.
		 *
		 * @since 1.0
		 */
		$widgets = apply_filters( 'alpha_elementor_widgets', $widgets );
		array_multisort( array_keys( $widgets ), SORT_ASC, $widgets );

		foreach ( $widgets as $widget => $usable ) {
			if ( $usable ) {
				$prefix = $widget;
				if ( 'testimonial' == substr( $widget, 0, 11 ) ) {
					$prefix = 'testimonial';
				}
				require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/' . $prefix . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor.php' );
				$class_name = 'Alpha_' . ucwords( str_replace( '-', '_', $widget ), '_' ) . '_Elementor_Widget';
				$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
			}
		}
	}

	public function register_element_addons() {
		$addons = array(
			'accordion'      => true,
			'tab'            => true,
			'creative_grid'  => true,
			'slider'         => true,
			'section_banner' => true,
			'scroll_section' => true,
			'custom_cursor'  => true,
		);

		if ( alpha_elementor_if_container_active() ) {
			$addons['sticky_container']  = true;
			$addons['stretch_container'] = true;
		}

		/**
		 * Filters the widget which add on by theme.
		 *
		 * @since 1.0
		 */
		$addons = apply_filters( 'alpha_elementor_widget_addons', $addons );

		foreach ( $addons as $addon => $usable ) {
			if ( $usable ) {
				$name = str_replace( '_', '-', $addon );
				require_once alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/' . $name . '/widget-' . $name . '-elementor.php' );
			}
		}
	}

	public function load_admin_styles() {
		if ( defined( 'ALPHA_ASSETS' ) ) {
			wp_enqueue_style( 'fontawesome-free', ALPHA_ASSETS . '/vendor/fontawesome-free/css/all.min.css', array(), '5.14.0' );
			wp_enqueue_style( 'alpha-admin', alpha_framework_uri( '/admin/admin/admin' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_VERSION );
		}
		wp_enqueue_style( 'alpha-elementor-admin-style', alpha_core_framework_uri( '/plugins/elementor/assets/elementor-admin' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
		wp_dequeue_style( 'elementor-editor-dark-mode' ); // Disable Dark mode
	}

	public function remove_fontawesome() {
		wp_deregister_style( 'elementor-icons-shared-0' );
		wp_deregister_style( 'elementor-icons-fa-regular' );
		wp_deregister_style( 'elementor-icons-fa-solid' );
		wp_deregister_style( 'elementor-icons-fa-brands' );
	}

	public function load_preview_scripts() {
		// load needed style file in elementor preview
		wp_enqueue_style( 'alpha-elementor-preview', alpha_core_framework_uri( '/plugins/elementor/assets/elementor-preview' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
		wp_enqueue_script( 'alpha-elementor-js', alpha_core_framework_uri( '/plugins/elementor/assets/elementor' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		wp_localize_script(
			'alpha-elementor-js',
			'alpha_elementor',
			array(
				'ajax_url'           => esc_js( admin_url( 'admin-ajax.php' ) ),
				'wpnonce'            => wp_create_nonce( 'alpha-elementor-nonce' ),
				'core_framework_url' => ALPHA_CORE_FRAMEWORK_URI,
				'text_untitled'      => esc_html__( 'Untitled', 'alpha-core' ),
			)
		);
	}

	/**
	 * Disable elementor resource for high performance
	 *
	 * @since 1.0
	 */
	public function resource_disable_elementor() {
		wp_dequeue_style( 'e-animations' );
		wp_dequeue_script( 'elementor-frontend' );
		wp_dequeue_script( 'elementor-frontend-modules' );
		wp_dequeue_script( 'elementor-waypoints' );
		wp_dequeue_script( 'elementor-webpack-runtime' );
		wp_deregister_script( 'elementor-frontend' );
		wp_deregister_script( 'elementor-frontend-modules' );
		wp_deregister_script( 'elementor-waypoints' );
		wp_deregister_script( 'elementor-webpack-runtime' );
	}

	/**
	 * Enqueue alternative scripts for disable elementor resource mode.
	 *
	 * @param $widget
	 * @since 1.0
	 */
	public function enqueue_theme_alternative_scripts( $widget ) {
		if ( 'counter' == $widget->get_name() ) {
			wp_enqueue_script( 'jquery-count-to' );
		}
	}

	public function alpha_add_icon_library( $icons ) {
		if ( defined( 'ALPHA_VERSION' ) ) {
			$icons['alpha-icons'] = array(
				'name'          => 'alpha-icons',
				'label'         => ALPHA_DISPLAY_NAME . esc_html__( ' Icons', 'alpha-core' ),
				'prefix'        => ALPHA_ICON_PREFIX . '-icon-',
				'displayPrefix' => ' ',
				'labelIcon'     => ALPHA_ICON_PREFIX . '-icon-gift',
				'fetchJson'     => alpha_core_framework_uri( '/plugins/elementor/assets/icons' . ( alpha_get_option( 'legacy_icons' ) ? '-legacy' : '' ) . '.js' ),
				'ver'           => ALPHA_CORE_VERSION,
				'native'        => false,
			);
		}
		return $icons;
	}

	public function save_page_custom_css_js( $self, $data ) {
		if ( empty( $data['settings'] ) || empty( $_REQUEST['editor_post_id'] ) ) {
			return;
		}
		$post_id = absint( $_REQUEST['editor_post_id'] );

		// save Alpha elementor page CSS
		if ( ! empty( $data['settings']['page_css'] ) ) {
			update_post_meta( $post_id, 'page_css', wp_slash( $data['settings']['page_css'] ) );
		} else {
			delete_post_meta( $post_id, 'page_css' );
		}

		if ( current_user_can( 'unfiltered_html' ) ) {
			// save Alpha elementor page JS
			if ( ! empty( $data['settings']['page_js'] ) ) {
				update_post_meta( $post_id, 'page_js', trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $data['settings']['page_js'] ) ) );
			} else {
				delete_post_meta( $post_id, 'page_js' );
			}
		}

		// Clear megamenu cache in menu lazy load
		$menus = get_posts(
			array(
				'post_type'   => 'nav_menu_item',
				'meta_key'    => '_menu_item_block',
				'meta_value'  => $post_id,
				'numberposts' => -1,
			)
		);

		if ( $menus && count( $menus ) ) {
			set_theme_mod( 'menu_last_time', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
		}
	}

	public function save_elementor_page_css_js( $self, $data ) {
		if ( current_user_can( 'unfiltered_html' ) || empty( $data['settings'] ) || empty( $_REQUEST['editor_post_id'] ) ) {
			return;
		}
		$post_id = absint( $_REQUEST['editor_post_id'] );
		if ( ! empty( $data['settings']['page_css'] ) ) {
			$elementor_settings = get_post_meta( $post_id, '_elementor_page_settings', true );
			if ( is_array( $elementor_settings ) ) {
				$elementor_settings['page_css'] = alpha_strip_script_tags( get_post_meta( $post_id, 'page_css', true ) );
				update_post_meta( $post_id, '_elementor_page_settings', $elementor_settings );
			}
		}
		if ( ! empty( $data['settings']['page_js'] ) ) {
			$elementor_settings = get_post_meta( $post_id, '_elementor_page_settings', true );
			if ( is_array( $elementor_settings ) ) {
				$elementor_settings['page_js'] = alpha_strip_script_tags( get_post_meta( $post_id, 'page_js', true ) );
				update_post_meta( $post_id, '_elementor_page_settings', $elementor_settings );
			}
		}
	}

	public function init_elementor_config( $config = array(), $post_id = 0 ) {

		if ( ! isset( $config['settings'] ) ) {
			$config['settings'] = array();
		}
		if ( ! isset( $config['settings']['settings'] ) ) {
			$config['settings']['settings'] = array();
		}

		$config['settings']['settings']['page_css'] = get_post_meta( $post_id, 'page_css', true );
		$config['settings']['settings']['page_js']  = get_post_meta( $post_id, 'page_js', true );
		return $config;
	}

	/**
	 * Add custom css, js addon html to bottom of elementor editor panel.
	 *
	 * @since 1.0
	 * @param array $html
	 * @return array $html
	 */
	public function add_custom_css_js_addon_html( $html ) {
		$html[] = array(
			'elementor' => '<li id="alpha-custom-css"><i class="fab fa-css3"></i>' . esc_html__( 'Page CSS', 'alpha-core' ) . '</li>',
		);
		$html[] = array(
			'elementor' => '<li id="alpha-custom-js"><i class="fab fa-js"></i>' . esc_html__( 'Page JS', 'alpha-core' ) . '</li>',
		);
		return $html;
	}

	public function register_document_controls( $document ) {
		if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		$document->start_controls_section(
			'alpha_blank_styles',
			array(
				'label' => ALPHA_DISPLAY_NAME . esc_html__( ' Blank Styles', 'alpha-core' ),
				'tab'   => Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$document->end_controls_section();

		$document->start_controls_section(
			'alpha_custom_css_settings',
			array(
				'label' => esc_html__( 'Custom Page CSS', 'alpha-core' ),
				'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
			)
		);

			$document->add_control(
				'page_css',
				array(
					'type' => Elementor\Controls_Manager::TEXTAREA,
					'rows' => 20,
				)
			);

		$document->end_controls_section();

		if ( current_user_can( 'unfiltered_html' ) ) {

			$document->start_controls_section(
				'alpha_custom_js_settings',
				array(
					'label' => esc_html__( 'Custom Page JS', 'alpha-core' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$document->add_control(
				'page_js',
				array(
					'type' => Elementor\Controls_Manager::TEXTAREA,
					'rows' => 20,
				)
			);

			$document->end_controls_section();
		}
	}

	public function add_body_class( $classes ) {
		if ( alpha_is_elementor_preview() && ALPHA_NAME . '_template' == get_post_type() ) {
			$template_category = get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true );

			if ( ! $template_category ) {
				$template_category = 'block';
			}

			$classes[] = 'alpha_' . $template_category . '_template';
		}
		return $classes;
	}

	public function add_appear_animations() {
		return alpha_get_animations( 'appear' );
	}

	public function add_wp_widget_args( $args, $self ) {
		$args['before_widget'] = '<div class="widget ' . $self->get_widget_instance()->widget_options['classname'] . ' widget-collapsible">';
		$args['after_widget']  = '</div>';
		$args['before_title']  = '<h3 class="widget-title">';
		$args['after_title']   = '</h3>';

		return $args;
	}

	public function get_dependent_elementor_styles( $self ) {
		if ( 'file' == $self->get_meta()['status'] ) { // Re-check if it's not empty after CSS update.
			preg_match( '/post-(\d+).css/', $self->get_url(), $id );
			if ( count( $id ) == 2 ) {
				global $e_post_ids;

				wp_dequeue_style( 'elementor-post-' . $id[1] );

				wp_register_style( 'elementor-post-' . $id[1], $self->get_url(), array( 'elementor-frontend' ), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion

				if ( ! isset( $e_post_ids ) ) {
					$e_post_ids = array();
				}
				$e_post_ids[] = $id[1];
			}
		}
	}

	public function add_global_css() {
		global $alpha_layout;
		$alpha_layout['used_blocks'] = alpha_get_page_blocks();

		if ( ! empty( $alpha_layout['used_blocks'] ) ) {
			foreach ( $alpha_layout['used_blocks'] as $block_id => $enqueued ) {
				if ( $this->is_elementor_block( $block_id ) ) {
					wp_enqueue_style( 'elementor-icons' );

					// enqueue kit css for internal style print method
					if ( isset( \Elementor\Plugin::$instance ) && 'internal' == get_option( 'elementor_css_print_method' ) ) {
						\Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
					}

					wp_enqueue_style( 'elementor-frontend' );

					if ( isset( \Elementor\Plugin::$instance ) ) {

						if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
							$kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();
							if ( $kit_id ) {
								wp_enqueue_style( 'elementor-post-' . $kit_id, wp_upload_dir()['baseurl'] . '/elementor/css/post-' . $kit_id . '.css' );
							}
						}

						add_action(
							'wp_print_footer_scripts',
							function() {
								try {
									$wp_scripts = wp_scripts();
									if ( ! in_array( 'elementor-frontend', $wp_scripts->queue ) ) {
										wp_enqueue_script( 'elementor-frontend' );
										$settings = \Elementor\Plugin::$instance->frontend->get_settings();
										\Elementor\Utils::print_js_config( 'elementor-frontend', 'elementorFrontendConfig', $settings );
									}
								} catch ( Exception $e ) {
									var_dump( $e );
								}
							},
							8
						);
					}

					$scheme_css_file = Global_CSS::create( 'global.css' );
					$scheme_css_file->enqueue();

					break;
				}
			}
		}

		global $e_post_ids;
		if ( is_array( $e_post_ids ) ) {
			foreach ( $e_post_ids as $id ) {
				if ( get_the_ID() != $id ) {
					wp_enqueue_style( 'elementor-post-' . $id );
				}
			}
		}
	}

	public function is_elementor_block( $id ) {
		$elements_data = get_post_meta( $id, '_elementor_data', true );
		return $elements_data && get_post_meta( $id, '_elementor_edit_mode', true );
	}

	public function add_elementor_css() {
		// Add Alpha elementor style
		wp_enqueue_style( 'alpha-elementor-style', alpha_core_framework_uri( '/plugins/elementor/assets/elementor' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'elementor-frontend' ) );
	}

	public function add_elementor_page_css() {
		// Add page css
		wp_enqueue_style( 'elementor-post-' . get_the_ID() );

		// Add inline styles for heading element responsive control
		$breakpoints = alpha_get_breakpoints();
		if ( $breakpoints ) :
			ob_start();
			?>
		<style>
			@media (max-width: <?php echo esc_html( $breakpoints['xxl'] - 1 . 'px' ); ?>) {
				div.title-xxl-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-xxl-left .title {
					margin-right: auto;
					margin-left: 0;
					text-align: left;
				}
				div.title-xxl-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
			@media (max-width: <?php echo esc_html( $breakpoints['xlg'] - 1 . 'px' ); ?>) {
				div.title-xlg-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-xlg-left .title {
					margin-right: auto;
					margin-left: 0;
					text-align: left;
				}
				div.title-xlg-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
			@media (max-width: <?php echo esc_html( $breakpoints['xl'] - 1 . 'px' ); ?>) {
				div.title-xl-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-xl-left .title {
					margin-right: auto;
					margin-left: 0;
					text-align: left;
				}
				div.title-xl-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
			@media (max-width: <?php echo esc_html( $breakpoints['lg'] - 1 . 'px' ); ?>) {
				div.title-lg-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-lg-left .title {
					margin-right: auto;
					margin-left: 0;
					text-align: left;
				}
				div.title-lg-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
			@media (max-width: <?php echo esc_html( $breakpoints['md'] - 1 . 'px' ); ?>) {
				div.title-md-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-md-left .title {
					margin-right: auto;
					text-align: left;
					margin-left: 0;
				}
				div.title-md-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
			@media (max-width: <?php echo esc_html( $breakpoints['sm'] - 1 . 'px' ); ?>) {
				div.title-sm-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-sm-left .title {
					margin-right: auto;
					text-align: left;
					margin-left: 0;
				}
				div.title-sm-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
		</style>
			<?php
			alpha_filter_inline_css( ob_get_clean() );
		endif;
	}

	public function generate_block_temp_css_onsave( $post_id, $post, $use_temp = true ) {
		if ( ! isset( $_REQUEST['editor_post_id'] ) ) {
			return;
		}

		if ( ALPHA_NAME . '_template' == $post->post_type ) {
			if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
				$initial_responsive_controls_duplication_mode = Plugin::$instance->breakpoints->get_responsive_control_duplication_mode();
				Plugin::$instance->breakpoints->set_responsive_control_duplication_mode( 'on' );

				$upload        = wp_upload_dir();
				$upload_dir    = $upload['basedir'];
				$post_css_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post_id . ( $use_temp ? '-temp' : '' ) . '.css' );

				$css_file = new Elementor\Core\Files\CSS\Post( $post_id );
				/**
				 * Filters the style for elementor block.
				 *
				 * @since 1.0
				 */
				$block_css = $css_file->get_content();

				// Save block css as elementor post css.
				// filesystem
				global $wp_filesystem;
				// Initialize the WordPress filesystem, no more using file_put_contents function
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}

				// Fix elementor's "max-width: auto" error.
				$block_css = str_replace( 'max-width:auto', 'max-width:none', $block_css );

				$wp_filesystem->put_contents( $post_css_path, $block_css, FS_CHMOD_FILE );

				Plugin::$instance->breakpoints->set_responsive_control_duplication_mode( $initial_responsive_controls_duplication_mode );
			}
		}
	}

	/**
	 * Generate blocks' css after clear cache on Elementor -> Tools
	 *
	 * @since 1.1
	 */
	public function generate_blocks_css_after_clear_cache() {
		$posts = get_posts(
			array(
				'post_type'   => ALPHA_NAME . '_template',
				'post_status' => 'publish',
				'numberposts' => 100,
			)
		);
		if ( ! empty( $posts ) && is_array( $posts ) ) {
			$mode = get_option( 'elementor_css_print_method' );
			foreach ( $posts as $post ) {
				$this->generate_block_temp_css_onsave( $post->ID, $post, false );
				if ( 'internal' !== $mode ) {
					$css_file = new Elementor\Core\Files\CSS\Post( $post->ID );
					$css_file->update();
				}
			}
		}
	}

	public function rename_block_temp_css_onsave( $obj, $data ) {
		$post = $obj->get_post();

		if ( ALPHA_NAME . '_template' == $post->post_type ) {
			if ( 'internal' !== get_option( 'elementor_css_print_method' ) ) {
				$upload      = wp_upload_dir();
				$upload_dir  = $upload['basedir'];
				$origin_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post->ID . '-temp.css' );
				$dest_path   = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $post->ID . '.css' );

				$css_file = new Elementor\Core\Files\CSS\Post( $post->ID );
				$css_file->update();

				// Save block css as elementor post css.
				// filesystem
				global $wp_filesystem;
				// Initialize the WordPress filesystem, no more using file_put_contents function
				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}

				$wp_filesystem->move( $origin_path, $dest_path, true );
			}
		}
	}

	public function add_block_css() {
		global $alpha_layout;

		if ( ! empty( $alpha_layout['used_blocks'] ) ) {
			$upload     = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_url = $upload['baseurl'];

			foreach ( $alpha_layout['used_blocks'] as $block_id => $enqueued ) {
				if ( 'internal' !== get_option( 'elementor_css_print_method' ) && ( ! alpha_is_elementor_preview() || ! isset( $_REQUEST['elementor-preview'] ) || $_REQUEST['elementor-preview'] != $block_id ) && $this->is_elementor_block( $block_id ) ) { // Check if current elementor block is editing

					$block_css = get_post_meta( (int) $block_id, 'page_css', true );
					if ( $block_css ) {
						$block_css = function_exists( 'alpha_minify_css' ) ? alpha_minify_css( $block_css ) : $block_css;
					}

					$post_css_path = wp_normalize_path( $upload_dir . '/elementor/css/post-' . $block_id . '.css' );
					if ( file_exists( $post_css_path ) ) {
						wp_enqueue_style( 'elementor-post-' . $block_id, $upload_url . '/elementor/css/post-' . $block_id . '.css' );
						/**
						 * Filters the style for elementor block.
						 *
						 * @since 1.0
						 */
						wp_add_inline_style( 'elementor-post-' . $block_id, apply_filters( 'alpha_elementor_block_style', $block_css ) );

						$alpha_layout['used_blocks'][ $block_id ]['css'] = true;
					} else {
						$css_file = new Elementor\Core\Files\CSS\Post( $block_id );
						/**
						 * Filters the style for elementor block.
						 *
						 * @since 1.0
						 */
						$block_css = $css_file->get_content() . apply_filters( 'alpha_elementor_block_style', $block_css );

						// Save block css as elementor post css.
						// filesystem
						global $wp_filesystem;
						// Initialize the WordPress filesystem, no more using file_put_contents function
						if ( empty( $wp_filesystem ) ) {
							require_once ABSPATH . '/wp-admin/includes/file.php';
							WP_Filesystem();
						}
						$wp_filesystem->put_contents( $post_css_path, $block_css, FS_CHMOD_FILE );

						// Fix elementor's "max-width: auto" error.
						$block_css = str_replace( 'max-width:auto', 'max-width:none', $block_css );
						wp_add_inline_style( 'alpha-style', $block_css );
					}

					$alpha_layout['used_blocks'][ $block_id ]['css'] = true;
				}
			}
		}
	}

	/**
	 * Remove elementor action to update dynamic post css.
	 */
	public function remove_dynamic_css_update() {
		remove_action( 'elementor/css-file/post/enqueue', array( Elementor\Plugin::$instance->dynamic_tags, 'after_enqueue_post_css' ) );
	}

	/**
	 * Add theme shape dividers.
	 *
	 * @since 1.0
	 *
	 * @param array $shapes Additional Elementor shapes.
	 * @return array $shapes
	 */
	public function add_shape_dividers( $shapes ) {

		$shapes['alpha-shape1'] = array(
			'title'        => esc_html__( 'Shape 1', 'alpha-core' ),
			'has_negative' => false,
		);
		$shapes['alpha-shape2'] = array(
			'title'        => esc_html__( 'Shape 2', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-shape3'] = array(
			'title'        => esc_html__( 'Shape 3', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-shape4'] = array(
			'title'        => esc_html__( 'Shape 4', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-shape5'] = array(
			'title'        => esc_html__( 'Shape 5', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-wave1']  = array(
			'title'        => esc_html__( 'Wave 1', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-wave2']  = array(
			'title'        => esc_html__( 'Wave 2', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-wave3']  = array(
			'title'        => esc_html__( 'Wave 3', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-wave4']  = array(
			'title'        => esc_html__( 'Wave 4', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['alpha-wave5']  = array(
			'title'        => esc_html__( 'Wave 5', 'alpha-core' ),
			'has_negative' => true,
		);
		$shapes['custom']       = array(
			'title'        => esc_html__( 'Custom', 'alpha-core' ),
			'has_negative' => true,
		);

		return $shapes;
	}

	/**
	 * Add Shape divider option to elementor section.
	 *
	 * @since 1.0
	 *
	 * @param object $self Object of elementor section
	 * @param array  $args
	 */
	public function add_custom_shape_divider( $self, $args ) {

		// $shapes_options = array(
		// 	'' => __( 'None', 'alpha-core' ),
		// );
		// foreach ( Elementor\Shapes::get_shapes() as $shape_name => $shape_props ) {
		// 	$shapes_options[ $shape_name ] = $shape_props['title'];
		// }
		// $shapes_options['custom'] = __( 'Custom', 'alpha-core' );

		// $self->update_control(
		// 	'shape_divider_top',
		// 	array(
		// 		'label'              => __( 'Type', 'alpha-core' ),
		// 		'type'               => Controls_Manager::SELECT,
		// 		'options'            => $shapes_options,
		// 		'render_type'        => 'none',
		// 		'frontend_available' => true,
		// 	),
		// 	array(
		// 		'overwrite' => true,
		// 	)
		// );
		// $self->update_control(
		// 	'shape_divider_bottom',
		// 	array(
		// 		'label'              => __( 'Type', 'alpha-core' ),
		// 		'type'               => Controls_Manager::SELECT,
		// 		'options'            => $shapes_options,
		// 		'render_type'        => 'none',
		// 		'frontend_available' => true,
		// 	),
		// 	array(
		// 		'overwrite' => true,
		// 	)
		// );

		$self->update_control(
			'shape_divider_top_color',
			array(
				'label'     => __( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'shape_divider_top!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-shape-top .elementor-shape-fill' => 'fill: {{VALUE}};',
					'{{WRAPPER}} > .elementor-shape-top svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} > .e-con-inner > .elementor-shape-top .elementor-shape-fill' => 'fill: {{VALUE}};',
					'{{WRAPPER}} > .e-con-inner > .elementor-shape-top svg' => 'fill: {{VALUE}};',
				),
			),
			array(
				'overwrite' => true,
			)
		);
		$self->update_control(
			'shape_divider_bottom_color',
			array(
				'label'     => __( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'shape_divider_bottom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-shape-bottom .elementor-shape-fill' => 'fill: {{VALUE}};',
					'{{WRAPPER}} > .elementor-shape-bottom svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} > .e-con-inner > .elementor-shape-bottom .elementor-shape-fill' => 'fill: {{VALUE}};',
					'{{WRAPPER}} > .e-con-inner > .elementor-shape-bottom svg' => 'fill: {{VALUE}};',
				),
			),
			array(
				'overwrite' => true,
			)
		);

		$self->add_control(
			'shape_divider_top_custom',
			array(
				'label'                  => __( 'Custom SVG', 'alpha-core' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'icon' ),
				'render_type'            => 'none',
				'frontend_available'     => true,
				'condition'              => array(
					'shape_divider_top' => 'custom',
				),
			),
			array(
				'position' => array(
					'of' => 'shape_divider_top',
				),
			)
		);
		$self->add_control(
			'shape_divider_bottom_custom',
			array(
				'label'                  => __( 'Custom SVG', 'alpha-core' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'icon' ),
				'render_type'            => 'none',
				'frontend_available'     => true,
				'condition'              => array(
					'shape_divider_bottom' => 'custom',
				),
			),
			array(
				'position' => array(
					'of' => 'shape_divider_bottom',
				),
			)
		);
	}

	/**
	 * Add Reveal effect color option to elementor section, column, container.
	 *
	 * @since 1.2.0
	 *
	 * @param object $self Object of elementor section, column, container
	 * @param array  $args
	 */
	public function add_motion_effect_controls( $self, $section_id, $args ) {
		if ( 'section_effects' == $section_id ) {
			$stack_name = $self->get_name();
			if ( 'container' == $stack_name || 'section' == $stack_name || 'column' == $stack_name ) {
				$self->add_control(
					'reveal_effect_color',
					array(
						'label'       => esc_html__( 'Animation Color', 'alpha-core' ),
						'description' => esc_html__( 'Controls the color of the reveal amination.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => array(
							'animation' => array( 'revealInDown', 'revealInLeft', 'revealInRight', 'revealInUp' ),
						),
						'selectors'   => array(
							'{{WRAPPER}}' => '--alpha-reveal-animation-color: {{VALUE}};',
						),
					),
					array(
						'position' => array(
							'at' => 'after',
							'of' => 'animation',
						),
					)
				);

				$self->add_control(
					'mask_reveal',
					array(
						'label'       => esc_html__( 'Reveal Mask Entrance', 'alpha-core' ),
						'description' => esc_html__( 'Motion effect only works for mask not for it\'s inner content.', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'condition'   => array(
							'animation'          => array(
								'zoomIn',
								'fadeInDown',
								'fadeInLeft',
								'fadeInRight',
								'fadeInUp',
								'fadeInDownShorter',
								'fadeInLeftShorter',
								'fadeInRightShorter',
								'fadeInUpShorter',
								'slideInDown',
								'slideInLeft',
								'slideInRight',
								'slideInUp',
								'rotateIn',
								'rotateInDownLeft',
								'rotateInDownRight',
								'rotateInUpLeft',
								'rotateInUpRight',
							),
							'_alpha_mask_switch' => 'yes',
						),
					),
					array(
						'position' => array(
							'at' => 'after',
							'of' => 'animation',
						),
					)
				);
			} else {
				$self->add_control(
					'reveal_effect_color',
					array(
						'label'       => esc_html__( 'Animation Color', 'alpha-core' ),
						'description' => esc_html__( 'Controls the color of the reveal amination.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => array(
							'_animation' => array( 'revealInDown', 'revealInLeft', 'revealInRight', 'revealInUp' ),
						),
						'selectors'   => array(
							'{{WRAPPER}}' => '--alpha-reveal-animation-color: {{VALUE}};',
						),
					),
					array(
						'position' => array(
							'at' => 'after',
							'of' => '_animation',
						),
					)
				);
			}
		}
	}

	public function attachment_image_attributes( $attr, $attachment, $size ) {
		if ( isset( $attr['srcset'] ) && 'alpha_banner_empty_srcset' == $attr['srcset'] ) {
			unset( $attr['srcset'] );
		}
		return $attr;
	}
}

/**
 * Create instance
 *
 * @since 1.0
 */
Alpha_Core_Elementor::get_instance();
