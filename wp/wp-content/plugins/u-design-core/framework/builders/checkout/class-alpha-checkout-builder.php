<?php
/**
 * Alpha Checkout Builder class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

define( 'ALPHA_CHECKOUT_BUILDER', ALPHA_BUILDERS . '/checkout' );

class Alpha_Checkout_Builder extends Alpha_Base {

	/**
	 * Widgets
	 *
	 * @access protected
	 * @var array[string] $widgets
	 * @since 1.2.0
	 */
	protected $widgets = array();

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->widgets = apply_filters(
			'alpha_checkout_widgets',
			array(
				'billing'  => true,
				'shipping' => true,
				'review'   => true,
				'payment'  => true,
			)
		);

		// setup builder
		add_action( 'init', array( $this, 'find_preview' ) );  // for editor preview
		add_action( 'wp', array( $this, 'find_preview' ), 1 ); // for template view
		add_filter( 'alpha_run_checkout_builder', array( $this, 'run_template' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 25 );

		add_filter( 'alpha_checkout_builder_set_preview', array( $this, 'set_preview' ) );
		add_action( 'alpha_checkout_builder_unset_preview', array( $this, 'unset_preview' ) );

		// Add controls
		add_filter( 'alpha_layout_get_controls', array( $this, 'add_layout_builder_control' ) );
		add_filter( 'alpha_layout_builder_display_parts', array( $this, 'add_layout_builder_display_parts' ) );
		add_filter( 'alpha_layout_builder_block_parts', array( $this, 'add_layout_builder_block_parts' ) );

		// @start feature: fs_pb_elementor
		if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
			add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
		}
		// @end feature: fs_pb_elementor
	}

	/**
	 * Get preview mode in editors and template view.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function find_preview() {
		global $post;
		if ( ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'elementor_ajax' == $_REQUEST['action'] ) ||
			( doing_action( 'wp' ) && ALPHA_NAME . '_template' == get_post_type() && 'checkout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ||
			doing_action( 'init' ) && ( alpha_is_elementor_preview() ) && is_admin() &&
			isset( $_GET['post'] ) && ALPHA_NAME . '_template' == get_post_type( (int) $_GET['post'] ) && 'checkout' == get_post_meta( (int) $_GET['post'], ALPHA_NAME . '_template_type', true ) ) ) {

		}
	}

	/**
	 * Run builder template
	 *
	 * @since 1.2.0
	 * @access public
	 * @param boolean $run
	 * @return boolean $run
	 */
	public function run_template( $run ) {

		global $post;
		if ( $post && ALPHA_NAME . '_template' == $post->post_type && 'checkout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			the_content();
			return true;

		} else {

			global $alpha_layout;
			if ( ! empty( $alpha_layout['checkout_block'] ) ) {

				if ( is_numeric( $alpha_layout['checkout_block'] ) ) {
					$template = (int) $alpha_layout['checkout_block'];
					do_action( 'alpha_before_checkout_template', $template );
					alpha_print_template( $template );
					do_action( 'alpha_after_checkout_template', $template );

					return true;
				} elseif ( 'hide' == $alpha_layout['checkout_block'] ) {
					return true;
				}
			}
		}

		return $run;
	}

	/**
	 * Set preview for editor and template view
	 *
	 * @since 1.2.0
	 * @access public
	 * @see alpha_checkout_builder_set_preview
	 */
	public function set_preview() {

		global $post;

		if ( alpha_is_elementor_preview() ) {
			wc_load_cart();

			return true;
		}

		return ( ALPHA_NAME . '_template' == $post->post_type && 'checkout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) || is_checkout();
	}

	/**
	 * Unset preview for editor and template view
	 *
	 * @since 1.2.0
	 * @access public
	 * @see alpha_checkout_builder_unset_preview
	 */
	public function unset_preview() {

		if ( alpha_is_elementor_preview() ) {
			wc_empty_cart();
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.2.0
	 */
	public function enqueue_scripts() {
		global $post;

		if ( ! empty( $post ) && ALPHA_NAME . '_template' == $post->post_type && 'checkout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			wp_enqueue_style( 'alpha-theme-shop-other' );
		}
	}

	/**
	 * Add checkout content template control for layout builder.
	 *
	 * @see alpha_layout_builder_controls
	 * @since 1.0.0
	 * @access public
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_control( $controls ) {

		$controls['content_checkout'] = array(
			'checkout_block' => array(
				'type'  => 'block_checkout',
				'label' => esc_html__( 'Checkout Layout', 'alpha-core' ),
			),
		);

		return $controls;
	}

	/**
	 * Add checkout content template display parts for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_display_parts
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_display_parts( $slugs ) {

		$slugs['checkout_block'] = array(
			'name'   => esc_html__( 'Checkout Layout', 'alpha-core' ),
			'parent' => 'content_checkout',
		);

		return $slugs;
	}

	/**
	 * Add checkout template block part for layout builder.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @see alpha_layout_builder_display_parts
	 * @param array $controls
	 * @return array $controls
	 */
	public function add_layout_builder_block_parts( $blocks ) {
		$blocks[] = 'checkout_block';
		return $blocks;
	}

	/**
	 * Register elementor category.
	 *
	 * @since 1.2.0
	 */
	public function register_elementor_category( $self ) {
		global $post;

		if ( $post && ALPHA_NAME . '_template' == $post->post_type && 'checkout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			$self->add_category(
				'alpha_checkout_widget',
				array(
					'title'  => ALPHA_DISPLAY_NAME . esc_html__( ' Checkout', 'alpha-core' ),
					'active' => true,
				)
			);
		}
	}

	/**
	 * Register elementor widgets.
	 *
	 * @since 1.2.0
	 */
	public function register_elementor_widgets( $self ) {
		global $post;

		$register = $post && ALPHA_NAME . '_template' == $post->post_type && 'checkout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true );

		if ( ! $register ) {
			global $alpha_layout;
			$register = ! empty( $alpha_layout['checkout_block'] ) && is_numeric( $alpha_layout['checkout_block'] );
		}

		if ( $register ) {
			foreach ( $this->widgets as $widget => $usable ) {
				if ( $usable ) {
					require_once alpha_core_framework_path( ALPHA_BUILDERS . '/checkout/widgets/' . str_replace( '_', '-', $widget ) . '/widget-checkout-' . str_replace( '_', '-', $widget ) . '-elementor.php' );
					$class_name = 'Alpha_Checkout_' . ucwords( $widget, '_' ) . '_Elementor_Widget';
					$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
				}
			}
		}
	}
}

Alpha_Checkout_Builder::get_instance();
