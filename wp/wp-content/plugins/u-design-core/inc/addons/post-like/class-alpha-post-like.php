<?php
/**
 * Alpha Post Like Addon
 *
 * @since 4.0.0
 */

if ( ! class_exists( 'Alpha_Post_Like' ) ) {

	class Alpha_Post_Like {


		/**
		 * Constructor
		 *
		 * @since 4.0.0
		 */
		public function __construct() {

			add_filter( 'alpha_customize_fields', array( $this, 'extend_customizer_fields' ) );
			add_filter( 'alpha_theme_option_default_values', array( $this, 'extend_theme_options_default_values' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );
			add_action( 'publish_post', array( $this, 'setup_likes' ) );
			add_action( 'wp_ajax_alpha_like_post', array( $this, 'like_post' ) );
			add_action( 'wp_ajax_nopriv_alpha_like_post', array( $this, 'like_post' ) );

			add_action( 'enqueue_block_editor_assets', array( $this, 'add_editor_assets' ), 999 );

			// register blocks
			add_filter(
				'alpha_core_type_builder_custom_blocks',
				function( $blocks = array() ) {
					$blocks['post-like'] = array(
						'attributes' => array(
							'content_type'       => array(
								'type' => 'string',
							),
							'content_type_value' => array(
								'type' => 'string',
							),
							'disable_action'     => array(
								'type' => 'boolean',
							),
							'icon_cls'           => array(
								'type' => 'string',
							),
							'dislike_icon_cls'   => array(
								'type' => 'string',
							),
							'icon_pos'           => array(
								'type' => 'string',
							),
							'st_icon_fs'         => array(
								'type' => 'string',
							),
							'st_icon_spacing'    => array(
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
						'path'       => ALPHA_CORE_INC . '/addons/post-like/post-like-render.php',
					);

					return $blocks;
				}
			);

			add_filter( 'alpha_gutenberg_block_style', array( $this, 'output_block_styles' ), 10, 4 );
		}


		/**
		 * Extends customizer fields
		 *
		 * @since 4.0.0
		 */
		public function extend_customizer_fields( $fields ) {

			$fields['post_show_info']['choices'] = array(
				'image'         => esc_html__( 'Media', 'alpha-core' ),
				'author'        => esc_html__( 'Meta Author', 'alpha-core' ),
				'date'          => esc_html__( 'Meta Date', 'alpha-core' ),
				'comment'       => esc_html__( 'Meta Comments Count', 'alpha-core' ),
				'like'          => esc_html__( 'Meta Like', 'alpha-core' ),
				'category'      => esc_html__( 'Category', 'alpha-core' ),
				'tag'           => esc_html__( 'Tags', 'alpha-core' ),
				'author_info'   => esc_html__( 'Author Information', 'alpha-core' ),
				'share'         => esc_html__( 'Share', 'alpha-core' ),
				'navigation'    => esc_html__( 'Prev and Next', 'alpha-core' ),
				'related'       => esc_html__( 'Related Posts', 'alpha-core' ),
				'comments_list' => esc_html__( 'Comments', 'alpha-core' ),
			);

			return $fields;
		}


		/**
		 * Extend default theme options
		 *
		 * @since 4.0.0
		 */
		public function extend_theme_options_default_values( $options ) {

			$options['post_show_info'] = array(
				'image',
				'author',
				'date',
				'category',
				'comment',
				'like',
				'tag',
				'author_info',
				'share',
				'navigation',
				'comments_list',
			);

			return $options;
		}


		/**
		 * Enqueue Script
		 *
		 * @since 4.0.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'alpha-post-like', ALPHA_CORE_INC_URI . '/addons/post-like/post-like' . ALPHA_JS_SUFFIX, array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
		}

		/**
		 * Enqueue Post Like Gutenberg block
		 *
		 * @since 1.0
		 */
		public function add_editor_assets() {
			$screen = get_current_screen();
			if ( $screen && $screen->is_block_editor() && 'post' == $screen->base && ALPHA_NAME . '_template' == $screen->id ) {
				wp_enqueue_script( 'alpha-block-post-like', ALPHA_CORE_INC_URI . '/addons/post-like/block.min.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data' ), ALPHA_CORE_VERSION, true );
			}
		}

		/**
		 * Set up liks in post meta
		 *
		 * @since 4.0.0
		 */
		public function setup_likes( $post_id ) {
			if ( ! is_numeric( $post_id ) ) {
				return;
			}

			add_post_meta( $post_id, 'udesign_post_likes', 0, true );
		}


		/**
		 * Get cookie data for like
		 *
		 * @since 4.0.0
		 * @param $id : Post ID
		 */
		public function get_cookies( $id ) {
			return isset( $_COOKIE[ 'udesign_post_likes_' . $id ] ) ? json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $id ] ), true ) : array();
		}


		/**
		 * Like or dislike post
		 *
		 * @since 4.0.0
		 */
		public function like_post() {

			$post_id = $_POST['post_id'];

			if ( ! isset( $post_id ) || ! is_numeric( $post_id ) ) {
				return;
			}

			$likes   = get_post_meta( $post_id, 'udesign_post_likes', true );
			$cookies = $this->get_cookies( $post_id );

			if ( $cookies['action'] && 'dislike' == $cookies['action'] ) {
				$likes  = intval( $likes ) - 1;
				$action = 'like';
				if ( $likes < 0 ) {
					$likes = 0;
				}
			} else { // like
				$likes  = intval( $likes ) + 1;
				$action = 'dislike';
			}
			update_post_meta( $post_id, 'udesign_post_likes', $likes );

			$data = array(
				'likes'  => $likes,
				'action' => $action,
			);
			setcookie( 'udesign_post_likes_' . $post_id, json_encode( $data ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			echo json_encode( $data );
			die;
		}

		/**
		 * Generate block internal styles
		 *
		 * @since 1.0
		 */
		public function output_block_styles( $saved_css, $block_name, $atts, $selector ) {
			if ( empty( $atts ) || empty( $selector ) ) {
				return $saved_css;
			}

			$style_name = '';
			if ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-post-like' == $block_name ) {
				$style_name = 'style.php';
				$selector  .= ' .alpha-tb-icon';
			}

			if ( $style_name ) {
				$atts['selector'] = $selector;
				ob_start();
				include ALPHA_CORE_INC . '/addons/post-like/' . $style_name;
				$css_part = ob_get_clean();
				if ( $css_part && false === strpos( $saved_css, $css_part ) ) {
					$saved_css .= $css_part;
				}
			}

			return $saved_css;
		}
	}
}


new Alpha_Post_Like();
