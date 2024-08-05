<?php
/**
 * Alpha Template
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_Builders extends Alpha_Base {

	/**
	 * The builder Type e.g: header, footer, single product
	 *
	 * @since 1.0
	 * @var array[string]
	 */
	protected $template_types = array();

	/**
	 * The post id
	 *
	 * @since 1.0
	 * @var int
	 */
	protected $post_id = '';

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->_init_template_types();

		add_action( 'init', array( $this, 'init_builders' ) );

		// Print Alpha Template Builder Page's Header
		if ( current_user_can( 'edit_posts' ) && 'edit.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ALPHA_NAME . '_template' == $_REQUEST['post_type'] ) {
			add_action( 'all_admin_notices', array( $this, 'print_template_dashboard_header' ) );
			add_filter( 'views_edit-' . ALPHA_NAME . '_template', array( $this, 'print_template_category_tabs' ) );
		}

		// Add "template type" column to posts table.
		add_filter( 'manage_' . ALPHA_NAME . '_template_posts_columns', array( $this, 'admin_column_header' ) );
		add_action( 'manage_' . ALPHA_NAME . '_template_posts_custom_column', array( $this, 'admin_column_content' ), 10, 2 );

		// Ajax
		add_action( 'wp_ajax_alpha_save_template', array( $this, 'save_alpha_template' ) );
		add_action( 'wp_ajax_nopriv_alpha_save_template', array( $this, 'save_alpha_template' ) );

		// Delete post meta when post is delete
		add_action( 'delete_post', array( $this, 'delete_template' ) );

		// Change Admin Post Query with alpha template types
		add_action( 'parse_query', array( $this, 'filter_template_type' ) );

		// Resources
		if ( ( isset( $_REQUEST['page'] ) && 'alpha-sidebar' == $_REQUEST['page'] ) || ( isset( $_REQUEST['post_type'] ) && ALPHA_NAME . '_template' == $_REQUEST['post_type'] ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		}

		// Add template builder classes to body class
		add_filter( 'body_class', array( $this, 'add_body_class_for_preview' ) );

		if ( is_admin() ) {
			if ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] ) {
				$this->post_id = intval( $_REQUEST['post'] );

				if ( alpha_is_elementor_preview() ) {
					add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'load_assets' ), 30 );
					add_filter( 'alpha_core_admin_localize_vars', array( $this, 'add_addon_htmls' ) );
				}
			}
		}

	}

	/**
	 * Init template types
	 *
	 * @since 1.0
	 * @access private
	 */
	private function _init_template_types() {
		$this->template_types = self::get_template_types();
		$rc_template_builders = get_theme_mod( 'resource_template_builders' );
		$builders_array       = json_decode( wp_unslash( empty( $rc_template_builders ) ? '' : $rc_template_builders ), true );
		if ( ! empty( $builders_array ) && is_array( $builders_array ) ) {
			foreach ( $builders_array as $key => $value ) {
				unset( $this->template_types[ $key ] );
			}
		}
		/**
		 * Filters template builder types.
		 *
		 * @since 1.0
		 */
		$this->template_types = apply_filters( 'alpha_template_types', $this->template_types );
		$this->load_template_builders();
	}

	/**
	 * Load Template Builders
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function load_template_builders() {
		foreach ( $this->template_types as $key => $value ) {
			$file = $key;
			/**
			 * Filters the template builder when it's loading.
			 *
			 * @since 1.0
			 */
			if ( in_array( $file, apply_filters( 'alpha_exclude_builder_load', array( 'block', 'footer' ) ) ) ) {
				continue;
			}
			if ( 'product_layout' == $file ) {
				$file = 'single-product';
			}
			if ( 'shop_layout' == $file ) {
				$file = 'shop';
			}
			if ( file_exists( alpha_core_framework_path( ALPHA_BUILDERS . "/{$file}/class-alpha-{$file}-builder.php" ) ) ) {
				require_once alpha_core_framework_path( ALPHA_BUILDERS . "/{$file}/class-alpha-{$file}-builder.php" );
			}
		}
	}
	/**
	 * Get template builder types.
	 *
	 * @since 1.2.0
	 */
	public static function get_template_types() {
		$builders = array();
		// @start feature: fs_builder_block
		if ( alpha_get_feature( 'fs_builder_block' ) ) {
			$builders['block'] = esc_html__( 'Block Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_block

		// @start feature: fs_builder_header
		if ( alpha_get_feature( 'fs_builder_header' ) ) {
			$builders['header'] = esc_html__( 'Header Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_header

		// @start feature: fs_builder_footer
		if ( alpha_get_feature( 'fs_builder_footer' ) ) {
			$builders['footer'] = esc_html__( 'Footer Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_footer

		// @start feature: fs_builder_popup
		if ( alpha_get_feature( 'fs_builder_popup' ) ) {
			$builders['popup'] = esc_html__( 'Popup Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_popup

		// @start feature: fs_plugin_woocommerce
		if ( class_exists( 'WooCommerce' ) && alpha_get_feature( 'fs_plugin_woocommerce' ) ) {
			// @start feature: fs_builder_singleproduct
			if ( alpha_get_feature( 'fs_builder_singleproduct' ) ) {
				$builders['product_layout'] = esc_html__( 'Single Product Builder', 'alpha-core' );
			}
			// @end feature: fs_builder_singleproduct

			// @start feature: fs_builder_shop
			if ( alpha_get_feature( 'fs_builder_shop' ) ) {
				$builders['shop_layout'] = esc_html__( 'Shop Builder', 'alpha-core' );
			}
			// @end feature: fs_builder_shop

			// @start feature: fs_builder_cart
			if ( alpha_get_feature( 'fs_builder_cart' ) ) {
				$builders['cart'] = esc_html__( 'Cart Builder', 'alpha-core' );
			}
			// @end feature: fs_builder_cart

			// @start feature: fs_builder_checkout
			if ( alpha_get_feature( 'fs_builder_checkout' ) ) {
				$builders['checkout'] = esc_html__( 'Checkout Builder', 'alpha-core' );
			}
			// @end feature: fs_builder_checkout
		}
		// @end feature: fs_plugin_woocommerce

		// @start feature: fs_builder_single
		if ( alpha_get_feature( 'fs_builder_single' ) ) {
			$builders['single'] = esc_html__( 'Single Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_single

		// @start feature: fs_builder_archive
		if ( alpha_get_feature( 'fs_builder_archive' ) ) {
			$builders['archive'] = esc_html__( 'Archive Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_archive

		// @start feature: fs_builder_type
		if ( alpha_get_feature( 'fs_builder_type' ) ) {
			$builders['type'] = esc_html__( 'Type Builder', 'alpha-core' );
		}
		// @end feature: fs_builder_type

		return $builders;
	}
	/**
	 * Add addon html to admin's localize vars.
	 *
	 * @param array $vars
	 * @return array $vars
	 * @since 1.0
	 */
	public function add_addon_htmls( $vars ) {
		/**
		 * Filters the addon html (ex.: custom css and js) which are adding to admin's localize vars.
		 *
		 * @since 1.0
		 */
		$vars['builder_addons'] = apply_filters( 'alpha_builder_addon_html', array() );
		$vars['theme_url']      = esc_url( get_parent_theme_file_uri() );
		return $vars;
	}

	/**
	 * Enqueue style and script
	 *
	 * @since 1.0
	 */
	public function load_assets() {
		wp_enqueue_style( 'alpha-core-template-builder', alpha_core_framework_uri( '/builders/template-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		wp_enqueue_script( 'alpha-core-template-builder', alpha_core_framework_uri( '/builders/template-builder' . ALPHA_JS_SUFFIX ), array(), false, true );

		// Studio Import Functions
		if ( ! isset( $_REQUEST['page'] ) || 'alpha-sidebar' != $_REQUEST['page'] ) {
			wp_enqueue_script( 'alpha-studio', alpha_core_framework_uri( '/addons/studio/studio' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		}
	}

	/**
	 * Add body class for preview.
	 *
	 * @param array $classes The class list
	 * @return array The class List
	 * @since 1.0
	 */
	public function add_body_class_for_preview( $classes ) {
		if ( ALPHA_NAME . '_template' == get_post_type() ) {
			$template_category = get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true );

			if ( ! $template_category ) {
				$template_category = 'block';
			}

			$classes[] = 'alpha_' . $template_category . '_template';
		}
		return $classes;
	}

	/**
	 * Register new template type.
	 *
	 * @since 1.0
	 */
	public function init_builders() {
		add_filter(
			'alpha_core_admin_localize_vars',
			function( $vars ) {
				$vars['layout_save']                       = true;
				$vars['template_type']                     = $this->post_id ? get_post_meta( $this->post_id, ALPHA_NAME . '_template_type', true ) : 'layout';
				$vars['texts']['elementor_addon_settings'] = ALPHA_DISPLAY_NAME . esc_html__( ' Settings', 'alpha-core' );
				if ( isset( $this->template_types[ $vars['template_type'] ] ) ) {
					$vars['texts']['builder_tooltip'] = ALPHA_DISPLAY_NAME . ' ' . $this->template_types[ $vars['template_type'] ] . esc_html__( ' Settings', 'alpha-core' );
				}

				if ( defined( 'ALPHA_VERSION' ) && $vars['template_type'] && 'layout' != $vars['template_type'] ) {
					$layouts = json_encode( alpha_get_option( 'conditions' ) );

					if ( false !== strpos( $layouts, '"' . $this->post_id . '"' ) || false !== strpos( $layouts, ':' . $this->post_id . ',' ) || false !== strpos( $layouts, ':' . $this->post_id . '}' ) ) {
						$vars['layout_save'] = false;
					}
				} else {
					$vars['layout_save'] = false;
				}
				return $vars;
			}
		);

		register_post_type(
			ALPHA_NAME . '_template',
			array(
				'label'               => ALPHA_DISPLAY_NAME . esc_html__( ' Templates', 'alpha-core' ),
				'exclude_from_search' => true,
				'has_archive'         => false,
				'public'              => true,
				'supports'            => array( 'title', 'editor', 'alpha', 'alpha-core' ),
				'can_export'          => true,
				'show_in_rest'        => true,
				'show_in_menu'        => false,
			)
		);
	}

	/**
	 * Hide page.
	 *
	 * @since 1.0
	 */
	public function hide_page( $class ) {
		return $class . ' hidden';
	}

	/**
	 * Print template dashboard header.
	 *
	 * @since 1.0
	 */
	public function print_template_dashboard_header() {
		if ( class_exists( 'Alpha_Admin_Panel' ) ) {
			$this->load_assets();
			$title        = array(
				'title' => esc_html__( 'Templates Builder', 'alpha-core' ),
				'desc'  => sprintf( esc_html__( 'Build any part of your site with %1$s Template Builder. This provides an easy but powerful way to build a full site with hundreds of pre-built templates from %1$s Studio.', 'alpha-core' ), ALPHA_DISPLAY_NAME ),
			);
			$admin_config = Alpha_Admin::get_instance()->admin_config;
			Alpha_Admin_Panel::get_instance()->view_header( 'templates_builder', $admin_config, $title );
			?>
			<div class="alpha-admin-panel-body templates-builder">
				<div class="alpha-template-actions buttons">
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . ALPHA_NAME . '_template' ) ); ?>" class="page-title-action alpha-add-new-template button button-primary button-large"><?php esc_html_e( 'Add New Template', 'alpha-core' ); ?></a>
				</div>
			<?php
			add_action( 'admin_footer', array( $this, 'print_template_dashboard_footer' ) );
		}
	}

	/**
	 * Print template dashboard footer.
	 *
	 * @since 1.2
	 */
	public function print_template_dashboard_footer() {
		echo '</div><!-- End alpha-admin-panel-body -->';
		Alpha_Admin_Panel::get_instance()->view_footer( 'templates_builder' );
	}

	/**
	 * Print template category tabs.
	 *
	 * @since 1.0
	 */
	public function print_template_category_tabs( $views = array() ) {
		echo '<div class="nav-tab-wrapper" id="alpha-template-nav">';

		$curslug = '';

		if ( isset( $_GET ) && isset( $_GET['post_type'] ) && ALPHA_NAME . '_template' == $_GET['post_type'] && isset( $_GET[ ALPHA_NAME . '_template_type' ] ) ) {
			$curslug = $_GET[ ALPHA_NAME . '_template_type' ];
		}

		echo '<a class="nav-tab' . ( '' == $curslug ? ' nav-tab-active' : '' ) . '" href="' . admin_url( 'edit.php?post_type=' . ALPHA_NAME . '_template' ) . '">' . esc_html__( 'All Builder', 'alpha-core' ) . '</a>';

		foreach ( $this->template_types as $slug => $name ) {
			echo '<a class="nav-tab' . ( $slug == $curslug ? ' nav-tab-active' : '' ) . '" href="' . admin_url( 'edit.php?post_type=' . ALPHA_NAME . '_template&' . ALPHA_NAME . '_template_type=' . $slug ) . '">' . sprintf( esc_html__( '%s', 'alpha-core' ), $name ) . '</a>';
		}

		echo '</div>';

		wp_enqueue_style( 'alpha-magnific-popup' );
		wp_enqueue_script( 'alpha-magnific-popup' );

		?>

		<div class="alpha-modal-overlay"></div>
		<div id="alpha_new_template" class="alpha-modal alpha-new-template-modal">
			<button class="alpha-modal-close dashicons dashicons-no-alt"></button>
			<div class="alpha-modal-box">
				<div class="alpha-modal-header">
					<h2><span class="alpha-mini-logo"></span><?php esc_html_e( 'New Template', 'alpha-core' ); ?></h2>
				</div>
				<div class="alpha-modal-body">
					<div class="alpha-new-template-form">
						<?php if ( defined( 'ALPHA_VERSION' ) ) : ?>
							<?php if ( defined( 'ELEMENTOR_VERSION' ) && alpha_get_feature( 'fs_pb_elementor' ) ) : ?>
								<label for="alpha-elementor-studio" style="display: none">
									<input type="radio" id="alpha-elementor-studio" name="alpha-studio-type" value="elementor" checked="checked">
								</label>
							<?php endif; ?>
						<?php endif; ?>
						<div class="option">
							<label for="template-type"><?php esc_html_e( 'Select Template Type', 'alpha-core' ); ?></label>
							<select class="template-type" id="template-type">
							<?php
							foreach ( $this->template_types as $slug => $key ) {
								echo '<option value="' . esc_attr( $slug ) . '" ' . selected( $slug, $curslug ) . '>' . esc_html( $key ) . '</option>';
							}
							?>
							</select>
						</div>
						<div class="option">
							<label for="template-name"><?php esc_html_e( 'Name your template', 'alpha-core' ); ?></label>
							<input type="text" id="template-name" name="template-name" class="template-name" placeholder="<?php esc_attr_e( 'Enter your template name (required)', 'alpha-core' ); ?>" />
						</div>
						<button class="button" id="alpha-create-template-type"><?php esc_html_e( 'Create Template', 'alpha-core' ); ?></button>
					</div>
				</div>
			</div>
		</div>

		<?php
		return $views;
	}

	/**
	 * The admin column header.
	 *
	 * @since 1.0
	 */
	public function admin_column_header( $defaults ) {
		$date_post = array_search( 'date', $defaults );
		$changed   = array_merge(
			array_slice( $defaults, 0, $date_post - 1 ),
			array(
				'template_type' => esc_html__( 'Template Type', 'alpha-core' ),
				'active_in'     => esc_html__(
					'Active In',
					'alpha-core'
				),
			),
			array_slice( $defaults, $date_post )
		);
		return $changed;
	}

	/**
	 * The admin column content.
	 *
	 * @since 1.0
	 */
	public function admin_column_content( $column_name, $post_id ) {
		if ( 'template_type' === $column_name ) {
			$type = esc_attr( get_post_meta( $post_id, ALPHA_NAME . '_template_type', true ) );
			echo '<a href="' . esc_url( admin_url( 'edit.php?post_type=' . ALPHA_NAME . '_template&' . ALPHA_NAME . '_template_type=' . $type ) ) . '">' . str_replace( '_', ' ', $type ) . '</a>';
		} elseif ( 'active_in' == $column_name ) {
			$conditions     = alpha_get_option( 'conditions' );
			$active_layouts = array();
			foreach ( $conditions as $cat => $layouts ) {
				foreach ( $layouts as $layout ) {
					if ( ! empty( $layout['options'] ) && array_search( $post_id, $layout['options'] ) ) {
						$active_layouts[] = $layout['title'];
					}
				}
			}
			foreach ( $active_layouts as $title ) {
				echo '<a href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder' ) ) . '">' . esc_html( $title ) . '</a>';
			}
		}
	}

	/**
	 * Save template.
	 *
	 * @since 1.0
	 */
	public function save_alpha_template() {
		if ( ! check_ajax_referer( 'alpha-core-nonce', 'nonce', false ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		if ( ! isset( $_POST['name'] ) || ! isset( $_POST['type'] ) ) {
			wp_send_json_error( esc_html__( 'no template type or name', 'alpha-core' ) );
		}

		if ( ! empty( $_POST['page_builder'] ) ) {
			$cpts = get_option( 'elementor_cpt_support' );
			if ( ! $cpts || ( is_array( $cpts ) && ! in_array( ALPHA_NAME . '_template', $cpts ) ) ) {
				if ( ! $cpts ) {
					$cpts = array( 'post', 'page' );
				}
				$cpts[] = ALPHA_NAME . '_template';
				update_option( 'elementor_cpt_support', $cpts );
				flush_rewrite_rules();
			}
		}

		$post_id = wp_insert_post(
			array(
				'post_title'  => $_POST['name'],
				'post_type'   => ALPHA_NAME . '_template',
				'post_status' => 'publish',
			)
		);

		wp_save_post_revision( $post_id );
		update_post_meta( $post_id, ALPHA_NAME . '_template_type', $_POST['type'] );
		if ( isset( $_POST['template_id'] ) && (int) $_POST['template_id'] && isset( $_POST['template_type'] ) && $_POST['template_type'] && isset( $_POST['template_category'] ) && $_POST['template_category'] ) {

			$template_type     = $_POST['template_type'];
			$template_category = $_POST['template_category'];

			update_post_meta(
				$post_id,
				'alpha_start_template',
				array(
					'id'   => (int) $_POST['template_id'],
					'type' => $template_type,
				)
			);
		}

		wp_send_json_success( $post_id );
	}

	/**
	 * Delete template.
	 *
	 * @since 1.0
	 */
	public function delete_template( $post_id ) {
		if ( ALPHA_NAME . '_template' == get_post_type( $post_id ) ) {
			delete_post_meta( $post_id, ALPHA_NAME . '_template_type' );
		}
	}

	/**
	 * Fitler template type.
	 *
	 * @since 1.0
	 */
	public function filter_template_type( $query ) {
		if ( is_admin() ) {
			global $pagenow;

			if ( 'edit.php' == $pagenow && isset( $_GET ) && isset( $_GET['post_type'] ) && ALPHA_NAME . '_template' == $_GET['post_type'] && ALPHA_NAME . '_template' == $query->query['post_type'] ) {
				$template_type = '';
				if ( isset( $_GET[ ALPHA_NAME . '_template_type' ] ) && $_GET[ ALPHA_NAME . '_template_type' ] ) {
					$template_type = $_GET[ ALPHA_NAME . '_template_type' ];
				}

				$query->query_vars['meta_key']   = ALPHA_NAME . '_template_type';
				$query->query_vars['meta_value'] = $template_type;
			}
		}
	}
}

Alpha_Builders::get_instance();
