<?php
/**
 * Alpha Dynamic Tags class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * @version    1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Core_Dynamic_Tags' ) ) {
	class Alpha_Core_Dynamic_Tags extends Elementor\Modules\DynamicTags\Module {

		/**
		 * Base dynamic tag group.
		 *
		 * @since 1.0
		 */
		const ALPHA_CORE_GROUP = 'alpha';

		public function __construct() {
			parent::__construct();

			add_action( 'alpha_core_dynamic_before_render', array( $this, 'before_render' ), 10, 2 );
			add_action( 'alpha_core_dynamic_after_render', array( $this, 'after_render' ), 10, 2 );
		}

		public function get_tag_classes_names() {
			$tags = array(
				'Alpha_Core_Custom_Field_Post_User_Tag',
				'Alpha_Core_Custom_Link_Post_User_Tag',
				'Alpha_Core_Custom_Field_Taxonomies_Tag',
				'Alpha_Core_Custom_Field_Meta_Data_Tag',
				'Alpha_Core_Custom_Image_Post_User_Tag',
				'Alpha_Core_Custom_Image_Meta_Data_Tag',
				// 'Alpha_Core_Custom_Image_Tag',
				// 'Alpha_Core_Custom_Field_Tag',
			);

			$builders_array = json_decode( wp_unslash( function_exists( 'alpha_get_option' ) ? alpha_get_option( 'resource_template_builders' ) : '' ), true );
			if ( empty( $builders_array['popup'] ) ) {
				$tags[] = 'Alpha_Core_Custom_Field_Popup_Tag';
			}

			/**
			 * Filters the tags which added dynamically.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_dynamic_tags', $tags );

		}

		public function get_groups() {
			return array(
				self::ALPHA_CORE_GROUP => array(
					'title' => ALPHA_DISPLAY_NAME . esc_html__( ' Dynamic Tags', 'alpha-core' ),
				),
			);
		}

		/**
		 * Register tags.
		 *
		 * Add all the available dynamic tags.
		 *
		 * @since  2.0.0
		 * @access public
		 *
		 * @param Manager $dynamic_tags
		 */
		public function register_tags( $dynamic_tags ) {

			foreach ( $this->get_tag_classes_names() as $tag_class ) {
				$file     = str_replace( 'Alpha_Core_', '', $tag_class );
				$file     = str_replace( '_', '-', strtolower( $file ) ) . '.php';
				$filepath = alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/elementor/dynamic_tags/tags/' . $file );

				if ( file_exists( $filepath ) ) {
					require_once $filepath;
				}

				if ( class_exists( $tag_class ) ) {
					$dynamic_tags->register( new $tag_class );
				}
			}

			do_action( 'alpha_dynamic_tags_register', $dynamic_tags );
		}

		/**
		 * Set current post type
		 *
		 * @since 1.0.0
		 */
		public function before_render( $post_type = '', $id = '' ) {
			global $post;
			if ( ! $post_type ) {
				$post_type = get_post_type();
			}
			if ( ! $id && $post ) {
				$id = $post->ID;
			}
			if ( ALPHA_NAME . '_template' == $post_type && isset( $id ) ) {
				if ( 'single' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {
					/**
					 * Filters the preview for editor and template.
					 *
					 * @since 1.0
					 */
					$single = apply_filters( 'alpha_single_builder_set_preview', false );
				} elseif ( 'archive' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {
					/**
					 * Filters the preview for editor and template view.
					 *
					 * @since 1.0
					 */
					$archive = apply_filters( 'alpha_archive_builder_set_preview', false );
				} elseif ( 'product_layout' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {
					/**
					 * Filters post products in single product builder
					 *
					 * @since 1.0
					 */
					$product = apply_filters( 'alpha_single_product_builder_set_preview', false );
				}
			}
		}

		/**
		 * Reset current post type
		 *
		 * @since 1.0.0
		 */
		public function after_render( $post_type = '', $id = '' ) {
			global $post;
			if ( ! $post_type ) {
				$post_type = get_post_type();
			}
			if ( ! $id && $post ) {
				$id = $post->ID;
			}
			if ( ALPHA_NAME . '_template' == $post_type && isset( $id ) ) {
				if ( 'single' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {
					do_action( 'alpha_single_builder_unset_preview' );
				} elseif ( 'archive' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {
					do_action( 'alpha_archive_builder_unset_preview' );
				} elseif ( 'product_layout' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {
					do_action( 'alpha_single_product_builder_unset_preview' );
				}
			}
		}
	}
	new Alpha_Core_Dynamic_Tags;
}
