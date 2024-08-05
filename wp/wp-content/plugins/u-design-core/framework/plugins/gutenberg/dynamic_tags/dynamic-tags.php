<?php
/**
 * Alpha Dynamic Tags Content class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Gutenberg_Dynamic_Tags_Content' ) ) :

	class Alpha_Gutenberg_Dynamic_Tags_Content extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'alpha_dynamic_tags_content', array( $this, 'get_dynamic_content_result' ), 10, 5 );

			add_action( 'wp_ajax_alpha_dynamic_tags_get_value', array( $this, 'get_value' ) );
			add_action( 'wp_ajax_alpha_dynamic_tags_acf_fields', array( $this, 'get_acf_fields' ) );
		}


		/**
		 * Retrive final dynamic content according to its type
		 *
		 * @since 1.2.0
		 */
		public function get_dynamic_content_result( $default = false, $object = null, $settings = array(), $data_type = '', $image_size = 'full' ) {
			$field_name = '';
			$data       = '';
			if ( isset( $settings['source'] ) && 'post' == $settings['source'] ) {
				if ( isset( $settings['post_info'] ) ) {
					$field_name = $settings['post_info'];
				}
			} else {
				if ( isset( $settings[ $settings['source'] ] ) ) {
					$field_name = $settings[ $settings['source'] ];
				}
			}
			if ( $field_name ) {
				$data = $this->get_dynamic_content( $default, $object, empty( $settings['source'] ) ? 'post' : $settings['source'], $field_name );
				if ( $data ) {
					if ( 'image' == $data_type ) {
						if ( is_numeric( $data ) ) {
							$img_id = (int) $data;
							$image  = wp_get_attachment_image_src( $img_id, $image_size );
							if ( $image ) {
								$data = array(
									'alt_text' => trim( wp_strip_all_tags( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) ),
									'url'      => esc_url( $image[0] ),
									'sizes'    => array(
										esc_js( $image_size ) => array(
											'url'    => esc_url( $image[0] ),
											'width'  => (int) $image[1],
											'height' => (int) $image[2],
										),
									),
								);
							}
						}
					}
				}
			}

			if ( ! $data && ! empty( $settings['fallback'] ) ) {
				$data = alpha_strip_script_tags( $settings['fallback'] );

				if ( 'image' == $data_type ) {
					return array(
						'alt_text' => '',
						'url'      => esc_url( $data ),
						'sizes'    => array(
							esc_js( $image_size ) => array(
								'url'    => esc_url( $data ),
								'width'  => '',
								'height' => '',
							),
						),
					);
				}
			}

			if ( ! empty( $settings['before'] ) ) {
				$data = alpha_strip_script_tags( $settings['before'] ) . $data;
			}
			if ( ! empty( $settings['after'] ) ) {
				$data .= alpha_strip_script_tags( $settings['after'] );
			}

			return $data ? $data : $default;
		}

		/**
		 * Retrive dynamic tags content according to its type
		 *
		 * @since 1.2.0
		 */
		private function get_dynamic_content( $default = false, $object = null, $type = 'post', $field = '' ) {
			if ( ! $object ) {
				if ( 'post' == $type ) {
					global $post;
					$object = $post;
				} else {
					if ( ( $current_object = get_queried_object() ) && $current_object->term_id ) {
						$object = $current_object;
					} else {
						global $post;
						$object = $post;
					}
				}
			}
			if ( ! $object ) {
				return $default;
			}
			if ( 'post' == $type ) {
				if ( 'content' == $field ) {
					return do_shortcode( $object->post_content );
				} elseif ( 'like_count' == $field ) {
					return (int) get_post_meta( $object->ID, 'like_count', true );
				} elseif ( $field && isset( $object->{ 'post_' . $field } ) ) {
					return $object->{ 'post_' . $field };
				} elseif ( 'thumbnail' == $field ) {
					return get_post_thumbnail_id( $object );
				} elseif ( 'thumbnail_url' == $field ) {
					return esc_url( get_the_post_thumbnail_url( $object, 'full' ) );
				} elseif ( 'author_img' == $field ) {
					return esc_url( get_avatar_url( get_the_author_meta( 'email' ) ) );
				} elseif ( 'permalink' == $field ) {
					return esc_url( get_permalink( $object ) );
				} elseif ( 'author_posts_url' == $field ) {
					global $authordata;
					if ( is_object( $authordata ) ) {
						return esc_url( get_author_posts_url( $authordata->ID, $authordata->user_nicename ) );
					}
				} else {
					return (int) $object->ID;
				}
			} elseif ( 'metabox' == $type ) {
				if ( ! $field ) {
					$field = 'page_sub_title';
				}
				if ( $object->ID ) {
					return get_post_meta( $object->ID, $field, true );
				} else {
					$result = get_term_meta( $object->term_id, $field, true );
					if ( $result ) {
						return $result;
					}
					return get_metadata( $object->taxonomy, $object->term_id, $field, true );
				}
			} elseif ( 'acf' == $type && $field ) {
				$field_arr = explode( '-', $field );
				if ( 2 === count( $field_arr ) ) {
					if ( isset( $object->term_id ) ) {
						return get_term_meta( $object->term_id, $field_arr[1], true );
					}
					return get_post_meta( $object->ID, $field_arr[1], true );
				}
			} elseif ( 'meta' == $type ) {
				if ( $object->ID ) {
					return get_post_meta( $object->ID, $field, true );
				} else {
					$result = get_term_meta( $object->term_id, $field, true );
					if ( $result ) {
						return $result;
					}
					return get_metadata( $object->taxonomy, $object->term_id, $field, true );
				}
			} elseif ( 'tax' == $type ) {
				if ( $object->term_id ) {
					if ( 'id' == $field ) {
						return (int) $object->term_id;
					} elseif ( 'title' == $field ) {
						return esc_html( $object->name );
					} elseif ( 'desc' == $field ) {
						return $object->description;
					} elseif ( 'count' == $field ) {
						return (int) $object->count;
					} elseif ( 'term_link' == $field ) {
						return esc_url( get_term_link( $object ) );
					}
				}
			}

			return $default;
		}

		/**
		 * Retrieve dynamic tags content from editor
		 *
		 * @since 1.2.0
		 */
		public function get_value() {
			check_ajax_referer( 'alpha-core-nonce', 'nonce' );
			if ( isset( $_POST['content_type'] ) && isset( $_POST['content_type_value'] ) && ! empty( $_POST['source'] ) && ! empty( $_POST['field_name'] ) ) {
				$atts   = array(
					'content_type'       => $_POST['content_type'],
					'content_type_value' => $_POST['content_type_value'],
				);
				$object = apply_filters( 'alpha_builder_get_current_object', false, $atts );
				if ( $object ) {
					if ( 'term' == $atts['content_type'] && 'post' == $_POST['source'] ) {
					} elseif ( 'term' != $atts['content_type'] && $atts['content_type'] && 'tax' == $_POST['source'] ) {
					} else {
						$result = $this->get_dynamic_content( false, $object, $_POST['source'], $_POST['field_name'] );
						if ( false === $result ) {
							wp_send_json_error();
						}
						if ( isset( $_POST['type'] ) && 'image' == $_POST['type'] ) {
							$image_size = isset( $_POST['img_size'] ) ? $_POST['img_size'] : 'full';
							if ( is_numeric( $result ) ) {
								$img_id = (int) $result;
								$image  = wp_get_attachment_image_src( $img_id, $image_size );
								if ( $image ) {
									$result = array(
										'alt_text' => trim( wp_strip_all_tags( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) ),
										'url'      => esc_url( $image[0] ),
										'sizes'    => array(
											esc_js( $image_size ) => array(
												'url'    => esc_url( $image[0] ),
												'width'  => (int) $image[1],
												'height' => (int) $image[2],
											),
										),
									);
								}
							}
						}
						wp_send_json_success( $result );
					}
				}
			}
			wp_send_json_error();
		}

		/**
		 * Retrive acf fields from selected content type
		 *
		 * @since 1.2.0
		 */
		public function get_acf_fields() {
			check_ajax_referer( 'alpha-core-nonce', 'nonce' );
			if ( class_exists( 'ACF' ) && isset( $_POST['content_type'] ) && isset( $_POST['content_type_value'] ) ) {
				$atts = array(
					'content_type'       => $_POST['content_type'],
					'content_type_value' => $_POST['content_type_value'],
				);

				// get current post object
				$object = apply_filters( 'alpha_builder_get_current_object', false, $atts );
				if ( $object ) {
					$fields = apply_filters( 'alpha_gutenberg_editor_vars', array(), $object );
					if ( isset( $fields['acf'] ) ) {
						$fields = $fields['acf'];
					}
					wp_send_json_success( $fields );
				}
			}
			wp_send_json_error();
		}
	}
endif;

Alpha_Gutenberg_Dynamic_Tags_Content::get_instance();
