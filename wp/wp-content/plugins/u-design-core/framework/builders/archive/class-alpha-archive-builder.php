<?php
/**
 * Alpha_Template_Archive_Builder class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPHA_ARCHIVE_BUILDER', ALPHA_BUILDERS . '/archive' );

use Elementor\Controls_Manager;

class Alpha_Template_Archive_Builder extends Alpha_Base {

	/**
	 * Widgets
	 *
	 * @access protected
	 * @var array[string] $widgets
	 * @since 1.0
	 */
	protected $widgets = array();

	/**
	 * The post
	 *
	 * @access protected
	 * @var object $post
	 * @since 1.0
	 */
	protected $post;

	/**
	 * Preview Mode
	 *
	 * @access public
	 * @var string $preview_mode
	 * @since 1.0
	 */
	public $preview_mode = '';

	/**
	 * Original
	 *
	 * @access protected
	 * @var array $original
	 * @since 1.0
	 */
	public $original;

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->widgets = apply_filters(
			'alpha_archive_widgets',
			array(
				'posts_grid' => true,
			)
		);

		// setup or unset preview
		add_action( 'init', array( $this, 'find_preview' ) );  // for editor preview
		add_action( 'wp', array( $this, 'find_preview' ), 1 ); // for template view
		add_filter( 'alpha_run_archive_builder', array( $this, 'run_template' ) );
		add_filter( 'alpha_archive_builder_set_preview', array( $this, 'set_preview' ) );
		add_action( 'alpha_archive_builder_unset_preview', array( $this, 'unset_preview' ) );

		// apply preview
		add_action( 'wp_ajax_alpha_archive_builder_preview_apply', array( $this, 'apply_preview' ) );

		// Add controls
		add_filter( 'alpha_layout_get_controls', array( $this, 'add_layout_builder_control' ) );
		add_filter( 'alpha_layout_builder_display_parts', array( $this, 'add_layout_builder_display_parts' ) );
		add_filter( 'alpha_layout_builder_block_parts', array( $this, 'add_layout_builder_block_parts' ) );
		add_action( 'elementor/documents/register_controls', array( $this, 'register_elementor_preview_controls' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );

		// Enqueue style
		add_action( 'alpha_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue archive styles
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		global $post;
		if ( ! empty( $post ) && ALPHA_NAME . '_template' == $post->post_type && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			wp_enqueue_style( 'alpha-theme-blog' );
		}
	}

	/**
	 * Get preview mode in editors and template view.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function find_preview() {
		global $post;

		if ( ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'elementor_ajax' == $_REQUEST['action'] ) || ( doing_action( 'wp' ) && ALPHA_NAME . '_template' == get_post_type() && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ||
			doing_action( 'init' ) && ( alpha_is_elementor_preview() || alpha_is_wpb_preview() ) && is_admin() &&
			isset( $_GET['post'] ) && ALPHA_NAME . '_template' == get_post_type( (int) $_GET['post'] ) && 'archive' == get_post_meta( (int) $_GET['post'], ALPHA_NAME . '_template_type', true ) ) ) {

			$post_id = 0;

			if ( ! empty( $_REQUEST['post'] ) ) {
				$post_id = (int) $_REQUEST['post'];
			}
			if ( defined( 'ELEMENTOR_VERSION' ) && ! empty( $_REQUEST['editor_post_id'] ) ) {
				$post_id = (int) $_REQUEST['editor_post_id'];
			}
			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}

			if ( 'archive' != get_post_meta( $post_id, ALPHA_NAME . '_template_type', true ) ) {
				return;
			}

			$preview_mode = get_post_meta( $post_id, 'preview', true );

			$this->preview_mode = $preview_mode ? $preview_mode : 'post';
		}
	}

	/**
	 * Apply preview mode in ajax
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function apply_preview() {
		if ( check_ajax_referer( 'alpha-core-nonce', 'nonce' ) ) {
			update_post_meta( (int) $_REQUEST['post_id'], 'preview', sanitize_title( $_REQUEST['mode'] ) );
		}
		die;
	}

	/**
	 * Run builder template
	 *
	 * @since 1.0
	 * @access public
	 * @param boolean $run
	 * @return boolean $run
	 */
	public function run_template( $run ) {

		global $post;
		if ( $post && ALPHA_NAME . '_template' == $post->post_type && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {

			the_content();
			return true;

		} else {

			global $alpha_layout;

			if ( ! empty( $alpha_layout['archive_block'] ) && is_numeric( $alpha_layout['archive_block'] ) ) {

				$template = (int) $alpha_layout['archive_block'];
				do_action( 'alpha_before_archive_template', $template );

				alpha_print_template( $template );
				do_action( 'alpha_after_archive_template', $template );

				return true;
			} elseif ( ! empty( $alpha_layout['archive_block'] ) && 'hide' == $alpha_layout['archive_block'] ) {
				// Hide

				return true;
			}
		}

		return $run;
	}

	/**
	 * Add archive content template control for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_controls
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_control( $controls ) {

		$archive_value = array(
			'type'  => 'block_archive',
			'label' => esc_html__( 'Archive Layout', 'alpha-core' ),
		);
		/**
		 * Filters the exclude post type.
		 *
		 * @param array The post type array.
		 * @since 1.0
		 */
		$post_types_exclude   = apply_filters( 'alpha_condition_exclude_post_types', array( ALPHA_NAME . '_template', 'attachment', 'elementor_library' ) );
		$available_post_types = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			),
			'objects'
		);

		foreach ( $available_post_types as $post_type => $post_type_data ) {
			/**
			 * Filters the layout builder which is avaiable archive.
			 *
			 * @since 1.0
			 */
			if ( ! in_array( $post_type, $post_types_exclude ) && apply_filters( 'alpha_layout_builder_is_available_archive', true, $post_type ) && ! in_array( $post_type, array( 'page', 'product' ) ) ) {

				// Add archive content template
				if ( empty( $controls[ 'content_archive_' . $post_type ] ) || ! is_array( $controls[ 'content_archive_' . $post_type ] ) ) {
					$controls[ 'content_archive_' . $post_type ] = array();
				}
				$controls[ 'content_archive_' . $post_type ] = array_merge(
					array( 'archive_block' => $archive_value ),
					$controls[ 'content_archive_' . $post_type ]
				);
			}
		}

		return $controls;
	}

	/**
	 * Add archive content template display parts for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_display_parts
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_display_parts( $slugs ) {
		$slugs['archive_block'] = array(
			'name'   => esc_html__( 'Archive Layout', 'alpha-core' ),
			'parent' => 'content_archive_' . get_post_type(),
		);
		return $slugs;
	}

	/**
	 * Add archive content template block part for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_display_parts
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_block_parts( $blocks ) {
		$blocks[] = 'archive_block';
		return $blocks;
	}

	/**
	 * Get current template's id for preview or currently applied layout.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return number|boolean template id
	 */
	public function get_template() {
		global $post;
		if ( ALPHA_NAME . '_template' == $post->post_type && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			return $post->ID;
		} else {
			global $alpha_layout;
			if ( isset( $alpha_layout['archive_block'] ) && is_numeric( $alpha_layout['archive_block'] ) && $alpha_layout['archive_block'] ) {
				return $alpha_layout['archive_block'];
			}
		}

		return false;
	}

	/**
	 * Set preview for editor and template view
	 *
	 * @since 1.0.0
	 * @access public
	 * @see alpha_archive_builder_set_preview
	 */
	public function set_preview() {

		if ( ! $this->preview_mode ) {
			return get_post_type();
		}

		if ( alpha_is_elementor_preview() ) {
			global $wp_query, $post, $alpha_layout;

			$this->original = array(
				'layout'   => $alpha_layout,
				'wp_query' => $wp_query,
				'post'     => $post,
			);

			// Get current options
			$alpha_layout = Alpha_Layout_Builder::get_instance()->get_layout( 'archive_' . $this->preview_mode );
			$posts        = new WP_Query;

			$posts->query(
				array(
					'post_type'           => $this->preview_mode,
					'post_status'         => 'publish',
					'posts_per_page'      => (int) get_option( 'posts_per_page' ) + 20,
					'ignore_sticky_posts' => true,
				)
			);

			$wp_query = $posts;
		}

		return $this->preview_mode;
	}

	/**
	 * Unset preview for editor and template view
	 *
	 * @since 1.0.0
	 * @access public
	 * @see alpha_archive_builder_unset_preview
	 */
	public function unset_preview() {
		if ( $this->preview_mode ) {
			global $wp_query, $post, $alpha_layout;

			$alpha_layout = $this->original['layout'];
			$wp_query     = $this->original['wp_query'];
			$post         = $this->original['post'];
		}
	}

	/**
	 * Add archive template's preview controls for elementor
	 *
	 * @since 1.0
	 * @access public
	 * @param object $document
	 */
	public function register_elementor_preview_controls( $document ) {
		if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		// Add Template Builder Controls
		$id = (int) $document->get_main_id();

		if ( $id && ALPHA_NAME . '_template' == get_post_type( $id ) && 'archive' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {

			$options = array();
			/**
			 * Filters the exclude post type.
			 *
			 * @param array The post type array.
			 * @since 1.0
			 */
			$post_types_exclude   = apply_filters( 'alpha_condition_exclude_post_types', array( ALPHA_NAME . '_template', 'attachment', 'elementor_library' ) );
			$available_post_types = get_post_types(
				array(
					'public'            => true,
					'show_in_nav_menus' => true,
				),
				'objects'
			);

			foreach ( $available_post_types as $post_type => $post_type_data ) {
				/**
				 * Filters the layout builder which is avaiable archive.
				 *
				 * @since 1.0
				 */
				if ( ! in_array( $post_type, $post_types_exclude ) && apply_filters( 'alpha_layout_builder_is_available_archive', true, $post_type ) && ! in_array( $post_type, array( 'page', 'product' ) ) ) {
					// translators: %s represents name of post type.
					$options[ $post_type ] = sprintf( esc_html__( '%s Archive', 'alpha-core' ), $post_type_data->label );
				}
			}

			$document->start_controls_section(
				'alpha_archive_settings',
				array(
					'label' => alpha_elementor_panel_heading( esc_html__( 'Preview Settings', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_SETTINGS,
				)
			);

			$document->add_control(
				'archive_preview_type',
				array(
					'label'       => esc_html__( 'Preview Dynamic Content as', 'alpha-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'default'     => 'post',
					'groups'      => array(
						'archive' => array(
							'label'   => esc_html__( 'Archive', 'alpha-core' ),
							'options' => $options,
						),
					),
					'export'      => false,
				)
			);

			$document->add_control(
				'archive_preview_apply',
				array(
					'type'        => Controls_Manager::BUTTON,
					'label'       => esc_html__( 'Apply & Preview', 'alpha-core' ),
					'label_block' => true,
					'show_label'  => false,
					'text'        => esc_html__( 'Apply & Preview', 'alpha-core' ),
					'separator'   => 'none',
				)
			);

			$document->end_controls_section();
		}
	}

	/**
	 * Register elementor category.
	 *
	 * @since 1.0
	 */
	public function register_elementor_category( $self ) {
		global $post;

		if ( $post && ALPHA_NAME . '_template' == $post->post_type && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			$self->add_category(
				'alpha_archive_widget',
				array(
					'title'  => ALPHA_DISPLAY_NAME . esc_html__( ' Archive', 'alpha-core' ),
					'active' => true,
				)
			);
		}
	}

	/**
	 * Register elementor widgets.
	 *
	 * @since 1.0
	 */
	public function register_elementor_widgets( $self ) {
		global $post;

		$register = $post && ALPHA_NAME . '_template' == $post->post_type && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true );

		if ( ! $register ) {
			global $alpha_layout;
			$register = ! empty( $alpha_layout['archive_block'] ) && is_numeric( $alpha_layout['archive_block'] );
			// $this->preview_mode = true;
		}

		if ( $register ) {
			foreach ( $this->widgets as $widget => $usable ) {
				if ( $usable ) {
					require_once alpha_core_framework_path( ALPHA_BUILDERS . '/archive/widgets/' . str_replace( '_', '-', $widget ) . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor.php' );
					$class_name = 'Alpha_Archive_' . ucwords( $widget, '_' ) . '_Elementor_Widget';
					$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
				}
			}
		}
	}
}

Alpha_Template_Archive_Builder::get_instance();
