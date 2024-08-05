<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post Type Builder
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

defined( 'ABSPATH' ) || die;
define( 'ALPHA_TYPE_BUILDER', ALPHA_BUILDERS . '/type' );

class Alpha_Type_Builder extends Alpha_Base {

	public $post_id = '';
	/**
	 * Meta fields
	 *
	 * @since 1.2.0
	 */
	private $meta_fields;

	/**
	 * Builder Type
	 *
	 * @since 1.2.0
	 */
	private $editor_builder_type;

	/**
	 * Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {

		define( 'ALPHA_GUTENBERG_BLOCK_CLASS_FILTER', 'alpha_gutenberg_block_class_filter' );

		if ( is_admin() && ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) ) {
			add_action( 'current_screen', array( $this, 'init' ) );

			if ( defined( 'WPB_VC_VERSION' ) ) {
				// enable gutenberg editor in wpbakery
				add_filter( 'classic_editor_enabled_editors_for_post', array( $this, 'enable_gutenberg_regular' ), 10, 2 );
				add_filter( 'use_block_editor_for_post_type', array( $this, 'enable_gutenberg' ), 10, 2 );
			}
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 40 );
		}

		if ( is_admin() && ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] || wp_doing_ajax() ) ) {
			add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_box' ), 90 );
		}

		add_filter( 'alpha_builder_get_current_object', array( $this, 'get_dynamic_content_data' ), 10, 2 );

		add_filter( 'alpha_gutenberg_block_class_filter', array( $this, 'elements_wrap_class_filter' ), 10, 3 );

		add_action( 'pre_get_posts', array( $this, 'filter_search_loop' ) );

		$this->add_elements();

		// fix compatibility issues with Yith Wishlist
		if ( wp_doing_ajax() ) {
			add_filter( 'yith_wcwl_ajax_add_return_params', array( $this, 'yith_ajax_add_cart_add_alpha_classes' ) );
		}
	}

	/**
	 * Init functions
	 *
	 * @since 1.2.0
	 */
	public function init() {

		$screen = get_current_screen();
		if ( $screen && 'post' == $screen->base && ALPHA_NAME . '_template' == $screen->id ) {

			if ( ! $this->editor_builder_type ) {
				$this->post_id = is_singular() ? get_the_ID() : ( isset( $_GET['post'] ) ? (int) $_GET['post'] : ( isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : false ) );
				if ( ! $this->post_id ) {
					return;
				}
				$this->editor_builder_type = get_post_meta( $this->post_id, ALPHA_NAME . '_template_type', true );
			}
			if ( ! $this->editor_builder_type || 'type' != $this->editor_builder_type ) {
				return;
			}

			if ( $screen->is_block_editor() ) {

				$preview_width = get_post_meta( $this->post_id, 'preview_width', true );
				if ( ! $preview_width ) {
					$preview_width = 360;
				}
				add_action(
					'admin_enqueue_scripts',
					function () use ( $preview_width ) {
						$css_escaped  = '.post-type-' . ALPHA_NAME . '_template #elementor-switch-mode, .post-type-' . ALPHA_NAME . '_template .composer-switch { display: none }';
						$css_escaped .= 'body .edit-post-visual-editor { max-width: none; padding: 0 }';
						$css_escaped .= '.editor-styles-wrapper { margin: 30px auto; padding: 0 10px 20px; width:' . floatval( $preview_width ) . 'px !important; }';
						$css_escaped .= '.edit-post-visual-editor__content-area > div { background: #333 !important }';
						$css_escaped .= '.components-placeholder.components-placeholder { min-height: auto }';
						$css_escaped .= '.editor-styles-wrapper .wp-block { margin: 0; min-height: 10px }';
						wp_add_inline_style( 'alpha-admin', $css_escaped );
					},
					1002
				);

				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor' ), 99 );

				// add elements
				add_filter(
					'block_categories_all',
					function ( $categories ) {
						return array_merge(
							$categories,
							array(
								array(
									'slug'  => ALPHA_NAME . '-tb',
									'title' => __( 'Post Type Builder', 'alpha-core' ),
									'icon'  => '',
								),
							)
						);
					},
					11,
					1
				);

				add_filter( 'alpha_gutenberg_editor_vars', array( $this, 'add_dynamic_field_vars' ) );
			} else {
				add_filter( 'alpha_gutenberg_block_style', array( $this, 'output_block_styles' ), 10, 4 );
			}
		}
	}

	/**
	 * Add meta box to set post type, dynamic content as, preview width
	 *
	 * @since 1.2.0
	 */
	public function add_meta_box( $meta_boxes ) {
		if ( ! wp_doing_ajax() ) {
			if ( ! $this->editor_builder_type ) {
				$this->post_id = is_singular() ? get_the_ID() : ( isset( $_GET['post'] ) ? (int) $_GET['post'] : ( isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : false ) );
				if ( ! $this->post_id ) {
					return $meta_boxes;
				}
				$this->editor_builder_type = get_post_meta( $this->post_id, ALPHA_NAME . '_template_type', true );
			}
			if ( ! $this->editor_builder_type || 'type' != $this->editor_builder_type ) {
				return $meta_boxes;
			}
		} elseif ( ! isset( $_REQUEST['action'] ) || 'rwmb_get_posts' != $_REQUEST['action'] ) {
			return $meta_boxes;
		}

		$old_tabs = array();

		if ( is_array( $meta_boxes ) && isset( $meta_boxes[0] ) && isset( $meta_boxes[0]['tabs'] ) ) {
			$old_tabs = $meta_boxes[0]['tabs'];
		} else {
			$meta_boxes = array(
				array(
					'fields' => array(),
				),
			);
		}

		$new_tabs = array(
			'tb' => array(
				'label' => __( 'Type Builder Options', 'alpha-core' ),
				'icon'  => 'dashicons-admin-post',
			),
		);

		if ( ! empty( $old_tabs ) && is_array( $old_tabs ) ) {
			foreach ( $old_tabs as $key => $tab ) {
				if ( 'titles' == $key ) {
					continue;
				}
				$new_tabs[ $key ] = $tab;
			}
		}

		$meta_boxes[0]['tabs']   = $new_tabs;
		$meta_boxes[0]['fields'] = array_merge( $this->get_meta_box_fields(), $meta_boxes[0]['fields'] );

		return $meta_boxes;
	}

	/**
	 * Generate block internal styles
	 *
	 * @since 1.2.0
	 */
	public function output_block_styles( $saved_css, $block_name, $atts, $selector ) {
		if ( empty( $atts ) || empty( $selector ) ) {
			return $saved_css;
		}

		$style_name = '';
		if ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-meta' == $block_name ) {
			$style_name = 'style-meta.php';
			$selector  .= ' .alpha-tb-icon';
		} elseif ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-featured-image' == $block_name ) {
			if ( ! empty( $atts['show_content_hover'] ) ) {
				$style_name = 'style-featured-image.php';
				$selector  .= ' .tb-hover-content';
			}
		} elseif ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons' == $block_name ) {
			$style_name = 'style-woo-buttons.php';
			$selector  .= ' i';
		}

		if ( $style_name ) {
			$atts['selector'] = $selector;
			ob_start();
			include alpha_core_framework_path( ALPHA_TYPE_BUILDER . '/styles/' . $style_name );
			$css_part = ob_get_clean();
			if ( $css_part && false === strpos( $saved_css, $css_part ) ) {
				$saved_css .= $css_part;
			}
		}

		return $saved_css;
	}

	/**
	 * Generate meta box fields
	 *
	 * @since 1.2.0
	 */
	private function get_meta_box_fields() {
		if ( $this->meta_fields ) {
			return $this->meta_fields;
		}
		$choices = array(
			''     => __( 'Default', 'alpha-core' ),
			'term' => __( 'Term', 'alpha-core' ),
		);

		$post_types          = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			),
			'objects',
			'and'
		);
		$post_taxonomies     = array();
		$sub_fields_types    = array();
		$disabled_post_types = array( 'attachment', ALPHA_NAME . '_template', 'page', 'e-landing-page' );

		foreach ( $disabled_post_types as $disabled ) {
			unset( $post_types[ $disabled ] );
		}
		foreach ( $post_types as $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type->name, 'objects' );
			foreach ( $taxonomies as $new_taxonomy ) {
				$post_taxonomies[ $new_taxonomy->name ] = ucwords( esc_html( $new_taxonomy->label ) );
			}

			$sub_fields_types[] = array(
				'id'         => 'content_type_' . $post_type->name,
				/* translators: The post name. */
				'name'       => sprintf( __( 'Select %s', 'alpha-core' ), $post_type->labels->singular_name ),
				/* translators: The post name. */
				'desc'       => sprintf( __( 'Choose to view dynamic content as %s. Leave Empty for random selection.', 'alpha-core' ), $post_type->labels->singular_name ),
				'type'       => 'post',
				'post_type'  => $post_type->name,
				'tab'        => 'tb',
				'attributes' => array(
					'class'          => 'alpha-metabox-condition',
					'data-condition' => 'content_type=' . esc_attr( $post_type->name ),
				),
			);

			$choices[ $post_type->name ] = $post_type->labels->singular_name;

			if ( ! empty( $post_type->has_archive ) ) {
				$archive_choices[ $post_type->name ] = $post_type->labels->singular_name;
			}
		}

		unset( $post_taxonomies['post_format'] );
		unset( $post_taxonomies['product_visibility'] );

		$sub_fields_types[] = array(
			'id'         => 'content_type_term',
			'name'       => __( 'Select Taxonomy', 'alpha-core' ),
			'desc'       => __( 'Select a taxonomy to pull a term from. The most recent term in the taxonomy will be used.', 'alpha-core' ),
			'type'       => 'select',
			'std'        => '',
			'options'    => $post_taxonomies,
			'tab'        => 'tb',
			'attributes' => array(
				'class'          => 'alpha-metabox-condition',
				'data-condition' => 'content_type=term',
			),
		);

		$this->meta_fields = array_merge(
			array(
				'content_type' => array(
					'id'      => 'content_type',
					'name'    => __( 'Content Type', 'alpha-core' ),
					'type'    => 'select',
					'std'     => '',
					'tab'     => 'tb',
					'options' => $choices,
				),
			),
			$sub_fields_types
		);

		$this->meta_fields[] = array(
			'id'   => 'preview_width',
			'name' => __( 'Preview Width (px)', 'alpha-core' ),
			'desc' => __( 'Note: this is only used for previewing purposes.', 'alpha-core' ),
			'type' => 'text',
			'tab'  => 'tb',
			'std'  => '360',
		);

		return $this->meta_fields;
	}

	/**
	 * Enqueue styles
	 *
	 * @since 1.2.0
	 */
	public function enqueue() {
		if ( is_singular( ALPHA_NAME . '_template' ) && 'type' == get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true ) ) { // single template page
			wp_enqueue_style( 'alpha-type-builder', alpha_core_framework_uri( '/builders/type/type-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

			$preview_width = get_post_meta( get_the_ID(), 'preview_width', true );
			if ( ! $preview_width ) {
				$preview_width = 360;
			}

			$css  = '.main { width: ' . (int) $preview_width . 'px; max-width: ' . (int) $preview_width . 'px !important; margin-left: auto; margin-right: auto }';
			$css .= get_post_meta( get_the_ID(), ALPHA_NAME . '_blocks_style_options_css', true );
			$css .= get_post_meta( get_the_ID(), '_' . ALPHA_NAME . '_builder_css', true );
			$css .= get_post_meta( get_the_ID(), 'page_css', true );
			wp_add_inline_style( 'alpha-type-builder', wp_strip_all_tags( $css ) );
		}
	}

	/**
	 * Enqueue styles in Gutenberg editor
	 *
	 * @since 1.2.0
	 */
	public function enqueue_editor() {
		wp_enqueue_style( 'alpha-type-builder', alpha_core_framework_uri( '/builders/type/type-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpha-blocks-style-editor' ), ALPHA_CORE_VERSION );
		wp_enqueue_script( 'alpha-tb-blocks', alpha_core_framework_uri( '/builders/type/blocks/blocks.min.js' ), array( 'alpha-admin' ), ALPHA_CORE_VERSION, true );

		if ( defined( 'ALPHA_VERSION' ) && ! wp_script_is( 'alpha-core-template-builder' ) ) {
			wp_enqueue_script( 'alpha-core-template-builder', alpha_core_framework_uri( '/builders/template-builder' . ALPHA_JS_SUFFIX ), array(), false, true );
		}
	}

	/**
	 * Add dynamic field vars
	 *
	 * @since 1.2.0
	 */
	public function add_dynamic_field_vars( $block_vars = array() ) {

		$meta_fields    = Alpha_Admin_Meta_Boxes::get_instance()->metabox_fields( false );
		$arr            = array();
		$all_post_types = get_post_types();

		foreach ( $meta_fields as $key => $field ) {
			$post_types = $field['post_types'];
			if ( empty( array_diff( $all_post_types, $post_types ) ) ) {
				$post_types = array( 'global' );
			}
			foreach ( $field['options'] as $name => $o ) {
				if ( isset( $o['tab'] ) && in_array( $o['tab'], array( 'tb' ) ) ) { // disabled meta boxes
					continue;
				}
				if ( isset( $o['tab'] ) && in_array( $o['tab'], $all_post_types ) ) {
					if ( ! isset( $arr[ $o['tab'] ] ) ) {
						$arr[ $o['tab'] ] = array();
					}
					$arr[ $o['tab'] ][ $name ] = array( $o['name'], isset( $o['field_type'] ) ? $o['field_type'] : array() );
				} else {
					foreach ( $post_types as $post_type ) {
						if ( ! isset( $arr[ $post_type ] ) ) {
							$arr[ $post_type ] = array();
						}
						$arr[ $post_type ][ $name ] = array( $o['name'], isset( $o['field_type'] ) ? $o['field_type'] : array() );
					}
				}
			}
		}

		$block_vars['meta_fields'] = $arr;
		return $block_vars;
	}

	/**
	 * Load post type builder blocks
	 *
	 * @since 1.2.0
	 */
	private function add_elements() {

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-featured-image',
			array(
				'attributes'      => array(
					'image_type'         => array(
						'type' => 'string',
					),
					'hover_effect'       => array(
						'type' => 'string',
					),
					'show_content_hover' => array(
						'type' => 'boolean',
					),
					'show_badges'        => array(
						'type' => 'boolean',
					),
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'add_link'           => array(
						'type' => 'string',
					),
					'custom_url'         => array(
						'type' => 'string',
					),
					'link_target'        => array(
						'type' => 'string',
					),
					'image_size'         => array(
						'type' => 'string',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
					'style_options'      => array(
						'type' => 'object',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts, $content = null ) {
					return $this->render_block( $atts, 'featured-image', $content );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-content',
			array(
				'attributes'      => array(
					'content_display'    => array(
						'type' => 'string',
					),
					'excerpt_length'     => array(
						'type' => 'integer',
					),
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'alignment'          => array(
						'type' => 'string',
					),
					'font_settings'      => array(
						'type' => 'object',
					),
					'style_options'      => array(
						'type' => 'object',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'content' );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-price',
			array(
				'attributes'      => array(
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'alignment'          => array(
						'type' => 'string',
					),
					'font_settings'      => array(
						'type' => 'object',
					),
					'style_options'      => array(
						'type' => 'object',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'woo-price' );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-rating',
			array(
				'attributes'      => array(
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'alignment'          => array(
						'type' => 'string',
					),
					'font_settings'      => array(
						'type' => 'object',
					),
					'style_options'      => array(
						'type' => 'object',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'woo-rating' );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-stock',
			array(
				'attributes'      => array(
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'alignment'          => array(
						'type' => 'string',
					),
					'font_settings'      => array(
						'type' => 'object',
					),
					'style_options'      => array(
						'type' => 'object',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'woo-stock' );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-desc',
			array(
				'attributes'      => array(
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'alignment'          => array(
						'type' => 'string',
					),
					'font_settings'      => array(
						'type' => 'object',
					),
					'style_options'      => array(
						'type' => 'object',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'woo-desc' );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons',
			array(
				'attributes'      => array(
					'content_type'        => array(
						'type' => 'string',
					),
					'content_type_value'  => array(
						'type' => 'string',
					),
					'link_source'         => array(
						'type' => 'string',
					),
					'show_quantity_input' => array(
						'type' => 'boolean',
					),
					'hide_title'          => array(
						'type' => 'boolean',
					),
					'icon_cls'            => array(
						'type' => 'string',
					),
					'icon_pos'            => array(
						'type' => 'string',
					),
					'st_icon_fs'          => array(
						'type' => 'string',
					),
					'st_spacing'          => array(
						'type' => 'string',
					),
					'alignment'           => array(
						'type' => 'string',
					),
					'font_settings'       => array(
						'type' => 'object',
					),
					'style_options'       => array(
						'type' => 'object',
					),
					'el_class'            => array(
						'type' => 'string',
					),
					'className'           => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'woo-buttons' );
				},
			)
		);

		register_block_type(
			ALPHA_NAME . '-tb/' . ALPHA_NAME . '-meta',
			array(
				'attributes'      => array(
					'content_type'       => array(
						'type' => 'string',
					),
					'content_type_value' => array(
						'type' => 'string',
					),
					'field'              => array(
						'type' => 'string',
					),
					'date_format'        => array(
						'type' => 'string',
					),
					'icon_cls'           => array(
						'type' => 'string',
					),
					'icon_pos'           => array(
						'type' => 'string',
					),
					'st_icon_fs'         => array(
						'type' => 'string',
					),
					'spacing'            => array(
						'type' => 'integer',
					),
					'alignment'          => array(
						'type' => 'string',
					),
					'font_settings'      => array(
						'type' => 'object',
					),
					'style_options'      => array(
						'type' => 'object',
					),
					'el_class'           => array(
						'type' => 'string',
					),
					'className'          => array(
						'type' => 'string',
					),
				),
				'editor_script'   => 'alpha-tb-blocks',
				'render_callback' => function( $atts ) {
					return $this->render_block( $atts, 'meta' );
				},
			)
		);

		add_action(
			'init',
			function() {
				$custom_blocks = apply_filters( 'alpha_core_type_builder_custom_blocks', array() );
				if ( ! empty( $custom_blocks ) ) {
					foreach ( $custom_blocks as $name => $custom_block ) {
						register_block_type(
							ALPHA_NAME . '-tb/' . ALPHA_NAME . '-' . $name,
							array(
								'attributes'      => isset( $custom_block['attributes'] ) ? $custom_block['attributes'] : array(),
								'editor_script'   => 'alpha-tb-blocks',
								'render_callback' => function( $atts ) use ( $custom_block, $name ) {
									return $this->render_block( $atts, $name, null, isset( $custom_block['path'] ) ? $custom_block['path'] : '' );
								},
							)
						);
					}
				}
			}
		);
	}

	/**
	 * Render block
	 *
	 * @since 1.2.0
	 */
	protected function render_block( $atts, $block_name, $content = null, $template_path = '' ) {
		ob_start();
		$should_save_global = false;
		if ( wp_is_json_request() && ( empty( $_POST['action'] ) || 'elementor_ajax' != $_POST['action'] ) ) { // in block editor
			$post = $this->get_dynamic_content_data( false, $atts );
			if ( ! $post ) {
				return;
			}

			// backup global data
			$should_save_global      = isset( $atts['content_type'] ) ? $atts['content_type'] : 'post';
			$original_query          = $GLOBALS['wp_query'];
			$original_queried_object = $GLOBALS['wp_query']->queried_object;
			if ( 'term' == $should_save_global ) {
				$original_is_tax     = $GLOBALS['wp_query']->is_tax;
				$original_is_archive = $GLOBALS['wp_query']->is_archive;

				$GLOBALS['wp_query']->queried_object = $post;
				$GLOBALS['wp_query']->is_tax         = true;
				$GLOBALS['wp_query']->is_archive     = true;
			} else {
				$original_post = $GLOBALS['post'];

				$GLOBALS['post'] = $post;
				setup_postdata( $GLOBALS['post'] );
				$GLOBALS['wp_query']->queried_object = $GLOBALS['post'];

				if ( 'product' == $should_save_global && class_exists( 'Woocommerce' ) ) {
					$GLOBALS['product'] = wc_get_product( $post->ID );
				}
			}
		}

		include apply_filters( 'alpha_core_type_builder_template_path', $template_path ? $template_path : alpha_core_framework_path( ALPHA_TYPE_BUILDER . '/templates/' . $block_name . '.php' ), $block_name, $atts );

		// Restore global data
		if ( 'term' == $should_save_global ) {
			$GLOBALS['wp_query']                 = $original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$GLOBALS['wp_query']->queried_object = $original_queried_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$GLOBALS['wp_query']->is_tax         = $original_is_tax; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$GLOBALS['wp_query']->is_archive     = $original_is_archive; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		} elseif ( $should_save_global ) {
			$GLOBALS['post']                     = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$GLOBALS['wp_query']                 = $original_query; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$GLOBALS['wp_query']->queried_object = $original_queried_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			if ( 'product' == $should_save_global ) {
				unset( $GLOBALS['product'] );
			}
		}

		return ob_get_clean();
	}

	/**
	 * Returns the dynamic content data
	 *
	 * @since 1.2.0
	 */
	public function get_dynamic_content_data( $builder_id = false, $atts = array() ) {
		$content_type       = false;
		$content_type_value = false;

		if ( isset( $atts['content_type'] ) ) {
			$content_type = $atts['content_type'];
		}
		if ( isset( $atts['content_type_value'] ) ) {
			$content_type_value = $atts['content_type_value'];
		}

		if ( $builder_id ) {
			if ( ! $content_type ) {
				$content_type = get_post_meta( $builder_id, 'content_type', true );
			}
			if ( ! $content_type_value ) {
				if ( $content_type ) {
					$content_type_value = get_post_meta( $builder_id, 'content_type_' . $content_type, true );
				}
			}
		}
		$result = false;

		if ( 'term' == $content_type ) {
			$args = array(
				'hide_empty' => true,
				'number'     => 1,
			);
			if ( $content_type_value ) {
				$args['taxonomy'] = $content_type_value;
			}
			$terms = get_terms( $args );

			if ( is_array( $terms ) && ! empty( $terms ) ) {
				$terms = array_values( $terms );
				return $terms[0];
			}
		} elseif ( $content_type && $content_type_value ) {
			$result = get_post( $content_type_value );
		} else {
			$args = array( 'numberposts' => 1 );
			if ( $content_type ) {
				$args['post_type'] = $content_type;
			}

			$result = get_posts( $args );

			if ( is_array( $result ) && isset( $result[0] ) ) {
				return $result[0];
			}
		}

		return $result;
	}

	/**
	 * add style options css class
	 *
	 * @since 1.2.0
	 */
	public function elements_wrap_class_filter( $class_string, $atts, $name ) {
		if ( is_array( $atts ) ) {
			$style_font_selector_options = array();
			foreach ( $atts as $key => $value ) {
				if ( 'font_settings' == $key || 'style_options' == $key || 'spacing' == $key || false !== strpos( $key, '_selector' ) || 0 === strpos( $key, 'st_' ) || ( 0 === strpos( $key, 'hover_' ) && ALPHA_NAME . '-tb/' . ALPHA_NAME . '-featured-image' == $name ) ) {
					$style_font_selector_options[ $key ] = $value;
				}
			}
			if ( ! empty( $atts['alignment'] ) ) {
				if ( ! isset( $style_font_selector_options['font_settings'] ) ) {
					$style_font_selector_options['font_settings'] = array();
				}
				$style_font_selector_options['font_settings']['alignment'] = $atts['alignment'];
			}

			if ( ! empty( $style_font_selector_options ) ) {
				$class_string .= ' alpha-gb-' . Alpha_Gutenberg::get_global_hashcode( $style_font_selector_options, $name );
			}

			// Responsive classes
			if ( ! empty( $atts['style_options'] ) ) {
				if ( ! empty( $atts['style_options']['position'] ) && ! empty( $atts['style_options']['position']['halign'] ) ) {
					$class_string .= ' m' . $atts['style_options']['position']['halign'] . '-auto';
				}

				if ( ! empty( $atts['style_options']['hideXl'] ) ) {
					$class_string .= ' hide-on-xl';
				}
				if ( ! empty( $atts['style_options']['hideLg'] ) ) {
					$class_string .= ' hide-on-lg';
				}
				if ( ! empty( $atts['style_options']['hideMd'] ) ) {
					$class_string .= ' hide-on-md';
				}
				if ( ! empty( $atts['style_options']['hideSm'] ) ) {
					$class_string .= ' hide-on-sm';
				}
			}
		}

		return $class_string;
	}

	/**
	 * Add alpha classes to wishlist wrapper
	 *
	 * @since 1.2.0
	 */
	public function yith_ajax_add_cart_add_alpha_classes( $params ) {
		if ( ! empty( $params['fragments'] ) ) {
			$fragments = isset( $_REQUEST['fragments'] ) ? wc_clean( $_REQUEST['fragments'] ) : false;
			if ( $fragments ) {
				foreach ( $fragments as $id => $options ) {
					if ( false === strpos( $id, 'alpha-tb-wishlist' ) ) {
						continue;
					}
					if ( isset( $params['fragments'][ $id ] ) ) {
						$fragment_content = $params['fragments'][ $id ];
						$pure_cls         = array_filter(
							explode( apply_filters( 'yith_wcwl_fragments_index_glue', '.' ), $id ),
							function( $c ) {
								if ( 0 === strpos( $c, 'alpha-' ) || 0 === strpos( $c, 'ms-' ) || 0 === strpos( $c, 'me-' ) || 0 === strpos( $c, 'mx-' ) || 'exists' == $c || 'with-count' == $c ) {
									return false;
								}
								return true;
							}
						);
						$pure_cls         = implode( ' ', $pure_cls );
						if ( false !== strpos( str_replace( array( ' exists', ' with-count', ' btn-product-icon' ), '', $fragment_content ), $pure_cls ) ) {
							$alpha_cls                  = array_filter(
								explode( apply_filters( 'yith_wcwl_fragments_index_glue', '.' ), $id ),
								function( $c ) {
									if ( 0 === strpos( $c, 'alpha-' ) || 0 === strpos( $c, 'ms-' ) || 0 === strpos( $c, 'me-' ) || 0 === strpos( $c, 'mx-' ) ) {
										return true;
									}
									return false;
								}
							);

							$alpha_cls                  = implode( ' ', $alpha_cls );
							$fragment_content           = str_replace( ' btn-product-icon', '', $fragment_content );
							$params['fragments'][ $id ] = str_replace( 'class="yith-wcwl-add-to-wishlist ', 'class="yith-wcwl-add-to-wishlist ' . esc_attr( $alpha_cls ) . ' ', $fragment_content );
						}
					}
				}
			}
		}
		return $params;
	}

	/**
	 * Enable Gutenberg editor only in WPBakery editor
	 *
	 * @since 1.2.0
	 */
	public function enable_gutenberg_regular( $editors, $post_type ) {
		if ( -1 === $this->enable_gutenberg( -1, $post_type ) ) {
			return $editors;
		}
		if ( is_array( $editors ) ) {
			$editors['gutenberg_editor'] = true;
			$editors['classic_editor']   = false;
		}

		return $editors;
	}
	public function enable_gutenberg( $result, $post_type ) {
		if ( ALPHA_NAME . '_template' != $post_type ) {
			return $result;
		}
		if ( ! $this->editor_builder_type ) {
			$this->post_id = is_singular() ? get_the_ID() : ( isset( $_GET['post'] ) ? (int) $_GET['post'] : ( isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : false ) );
			if ( ! $this->post_id ) {
				return $result;
			}
			$this->editor_builder_type = get_post_meta( $this->post_id, ALPHA_NAME . '_template_type', true );
		}
		if ( ! $this->editor_builder_type || 'type' != $this->editor_builder_type ) {
			return $result;
		}

		return true;
	}

	/**
	 * Filter main query to update posts per page, order by, order and pagination
	 *
	 * @since 1.2.0
	 */
	public function filter_search_loop( $query ) {
		if ( ! is_admin() && $query->is_main_query() && ( $query->is_home() || $query->is_search() || $query->is_archive() ) && defined( 'ALPHA_VERSION' ) ) {
			$post_type = isset( $query->query_vars ) && ! empty( $query->query_vars['post_type'] ) ? $query->query_vars['post_type'] : '';
			if ( ! $post_type ) {
				$post_types_exclude   = apply_filters( 'alpha_condition_exclude_post_types', array( ALPHA_NAME . '_template', 'attachment', 'elementor_library', 'page' ) );
				$available_post_types = get_post_types( array( 'public' => true ) );
				foreach ( $available_post_types as $p_type ) {
					if ( ! in_array( $p_type, $post_types_exclude ) && ( $query->is_post_type_archive( $p_type ) || $query->is_tax( get_object_taxonomies( $p_type ) ) ) ) {
						$post_type = $p_type;
						break;
					}
				}
			}
			if ( ! $post_type ) {
				$post_type = 'post';
			}
			if ( is_array( $post_type ) ) { // The Events Calendar Compatibility
				$post_type = $post_type[0];
			}
			// get template id
			$template = Alpha_Layout_Builder::get_instance()->get_layout( 'archive_' . $post_type );
			if ( ! $template ) {
				return;
			}
			$template_key = 'product' == $post_type ? 'shop_block' : 'archive_block';
			if ( empty( $template[ $template_key ] ) ) {
				return;
			}
			$template_id                                     = $template[ $template_key ];
			$GLOBALS[ 'alpha_layout_archive_' . $post_type ] = $template;

			// check if has post type builder archive widget
			$query_vars = false;
			if ( defined( 'ELEMENTOR_VERSION' ) && ( get_post_meta( $template_id, '_elementor_edit_mode', true ) && ( $elements_data = get_post_meta( $template_id, '_elementor_data', true ) ) ) ) {
				$elements_data = json_decode( $elements_data, true );
				if ( ! $elements_data ) {
					return;
				}

				$query_vars = $this->parse_query_vars_elements( $elements_data );
			}

			if ( empty( $query_vars ) ) {
				return;
			}

			// update query vars
			if ( empty( $_GET['count'] ) && ! empty( $query_vars['count'] ) && -1 !== (int) $query_vars['count'] ) {
				$query->set( 'posts_per_page', $query_vars['count'] );
			}
			if ( ! empty( $query_vars['orderby'] ) ) {
				$query->set( 'orderby', $query_vars['orderby'] );
			}
			if ( ! empty( $query_vars['orderway'] ) ) {
				$query->set( 'order', $query_vars['orderway'] );
			}

			remove_action( 'pre_get_posts', array( $this, 'filter_search_loop' ) );
		}
	}

	/**
	 * Get query vars from posts grid element in Elementor elements data
	 *
	 * @since 1.2.0
	 */
	private function parse_query_vars_elements( $elements_data ) {
		foreach ( $elements_data as $element_data ) {
			if ( ! empty( $element_data['elements'] ) ) {
				$call_result = $this->parse_query_vars_elements( $element_data['elements'] );
				if ( false !== $call_result ) {
					return $call_result;
				}
			} else {
				if ( isset( $element_data['widgetType'] ) && ALPHA_NAME . '_widget_archive_posts_grid' == $element_data['widgetType'] ) {
					$settings = $element_data['settings'];
					$result   = array();
					if ( ! empty( $settings['count'] ) && ! empty( $settings['count']['size'] ) ) {
						$result['count'] = (int) $settings['count']['size'];
					}
					if ( ! empty( $settings['orderby'] ) ) {
						$result['orderby'] = sanitize_text_field( $settings['orderby'] );
					}
					if ( ! empty( $settings['orderway'] ) ) {
						$result['orderway'] = sanitize_text_field( $settings['orderway'] );
					}
					if ( ! empty( $settings['loadmore_type'] ) ) {
						$result['loadmore_type'] = sanitize_text_field( $settings['loadmore_type'] );
					}

					return $result;
				}
			}
		}
		return false;
	}
}

Alpha_Type_Builder::get_instance();
