<?php
/**
 * Alpha Image Comment Addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Image_Comment' ) ) {

	class Alpha_Product_Image_Comment extends Alpha_Base {

		/**
		 * Field name to be uploaded
		 *
		 * @since 1.0
		 * @access public
		 */
		public $field_name = 'alpha_comment_images';

		/**
		 * Meta key for comment image
		 *
		 * @since 1.0
		 * @access public
		 */
		public $meta_key_image = '_alpha_comment_image';

		/**
		 * Meta key for comment video
		 *
		 * @since 1.0
		 * @access public
		 */
		public $meta_key_video = '_alpha_comment_video';

		/**
		 * Constructor
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'init' ) );
		}

		/**
		 * Constructor
		 *
		 * @since 1.0
		 * @access public
		 */
		public function init() {
			add_filter( 'alpha_customize_fields', array( $this, 'add_customize_fields' ) );
			if ( function_exists( 'alpha_set_default_option' ) ) {
				// Products Comment Image
				alpha_set_default_option( 'product_review_image_size', 1 );
				alpha_set_default_option( 'product_review_image_count', 2 );
				alpha_set_default_option( 'product_review_video_count', 2 );
			}
			if ( function_exists( 'alpha_get_option' ) && ( alpha_get_option( 'product_review_image_count' ) ||
				alpha_get_option( 'product_review_video_count' ) ) && 0 != alpha_get_option( 'product_review_image_count' ) + alpha_get_option( 'product_review_video_count' ) ) {

				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );
				add_action( 'comment_form_submit_field', array( $this, 'add_file_input' ), 10 );
				add_action( 'delete_comment', array( $this, 'delete_image' ), 10 );
				add_action( 'woocommerce_review_after_comment_text', array( $this, 'display_images' ), 30 );
			}

			if ( count( $_FILES ) ) {
				add_filter( 'preprocess_comment', array( $this, 'validate_images' ), 10 );
				add_action( 'comment_post', array( $this, 'save_comment_images' ), 10, 3 );
			}

			// display filter select box
			add_filter( 'alpha_single_product_review_filter_select_html', array( $this, 'single_product_review_filter_select_html' ), 5 );
		}

		/**
		 * Add fields for image comment
		 *
		 * @param {Array} $fields
		 *
		 * @param {Array} $fields
		 *
		 * @since 1.0
		 */
		public function add_customize_fields( $fields ) {
			$fields['cs_product_reviews']         = array(
				'section'  => 'product_detail',
				'type'     => 'custom',
				'label'    => '',
				'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Product Review Images', 'alpha-core' ) . '</h3>',
				'priority' => 30,
			);
			$fields['cs_product_reviews_alert']   = array(
				'section'  => 'product_detail',
				'type'     => 'custom',
				'label'    => '<p class="options-description"><span>Warning: </span>' . sprintf( esc_html__( 'Currently your server allows you to upload files up to %s. You can change this option in settings panel', 'alpha-core' ), size_format( wp_max_upload_size() ) ) . '</p>',
				'priority' => 30,
			);
			$fields['product_review_image_count'] = array(
				'section'  => 'product_detail',
				'type'     => 'number',
				'label'    => esc_html__( 'Maximum Image Count per Review', 'alpha-core' ),
				'choices'  => array(
					'min' => 0,
					'max' => 10,
				),
				'priority' => 30,
			);
			$fields['product_review_video_count'] = array(
				'section'  => 'product_detail',
				'type'     => 'number',
				'label'    => esc_html__( 'Maximum Video Count per Review', 'alpha-core' ),
				'choices'  => array(
					'min' => 0,
					'max' => 10,
				),
				'priority' => 30,
			);
			$fields['product_review_image_size']  = array(
				'section'  => 'product_detail',
				'type'     => 'number',
				'label'    => esc_html__( 'Maximum Upload File Size (MB)', 'alpha-core' ),
				'choices'  => array(
					'min' => 0,
					'max' => size_format( wp_max_upload_size() ),
				),
				'priority' => 30,
			);
			$fields['cs_product_reviews_form']    = array(
				'section'  => 'product_detail',
				'type'     => 'custom',
				'label'    => '',
				'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Product Review Form', 'alpha-core' ) . '</h3>',
				'priority' => 35,
			);
			$fields['product_review_offcanvas']   = array(
				'section'  => 'product_detail',
				'type'     => 'toggle',
				'label'    => esc_html__( 'Off Canvas Review Form', 'alpha-core' ),
				'tooltip'  => esc_html__( 'Display review form in off-canvas.', 'alpha-core' ),
				'priority' => 35,
			);
			return $fields;
		}

		/**
		 * Get image mimetypes of comment attachments.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function get_image_mimetypes() {
			return apply_filters(
				'alpha_product_comment_images_mimetypes',
				array(
					'jpg|jpeg' => 'image/jpeg',
					'png'      => 'image/png',
					'avi'      => 'video/x-msvideo',
					'mp4'      => 'video/mp4',
					'mpeg'     => 'video/mpeg',
					'ogv'      => 'video/ogg',
					'ts'       => 'video/mp2t',
					'webm'     => 'video/webm',
					'3gp'      => 'video/3gpp',
					'3g2'      => 'video/3gpp2',
				)
			);
		}

		/**
		 * Enqueue script
		 *
		 * @since 1.0
		 * @access public
		 */
		public function enqueue_scripts() {
			global $post;
			if ( alpha_is_product() || ( $post && 'product_layout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) {
				wp_enqueue_style( 'alpha-product-image-comments', alpha_core_framework_uri( '/addons/product-image-comments/image-comments' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
				wp_enqueue_script( 'alpha-product-image-comments', alpha_core_framework_uri( '/addons/product-image-comments/product-image-comments' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
				wp_localize_script(
					'alpha-product-image-comments',
					'alpha_product_image_comments',
					apply_filters(
						'alpha_product_image_comments',
						array(
							/**
							 * Filters image types in product comment.
							 *
							 * @since 1.0
							 */
							'mime_types'       => apply_filters( 'alpha_product_comment_images_mimetypes', array( 'image/jpeg', 'image/png', 'video/x-msvideo', 'video/mp4', 'video/mepg', 'video/ogg', 'video/mp2t', 'video/webm', 'video/3gpp', 'video/3gpp2' ) ),
							'max_image_count'  => alpha_get_option( 'product_review_image_count', 2 ),
							'max_video_count'  => alpha_get_option( 'product_review_video_count', 2 ),
							'max_size'         => $this->get_max_size(),
							// translators: %s represents count of images.
							'added_count_text' => esc_html__( 'Added %s images', 'alpha-core' ),
							'error_msg'        => array(
								// translators: %s represents maximum file size.
								'size_error'      => sprintf( esc_html__( 'Maximum file size is %s', 'alpha-core' ), $this->get_max_size( true ) ),
								// translators: %s represents image file formats.
								/**
								 * Filters image types in product comment.
								 *
								 * @since 1.0
								 */
								'mime_type_error' => sprintf( esc_html__( 'You are allowed to upload images only in %s formats.', 'alpha-core' ), apply_filters( 'alpha_product_comment_images_mimetypes', 'jpeg, png, jpg, avi, mp4, mpeg, ogv, ts, webm, 3gp, 3g2' ) ), //phpcs:ignore
							),
						)
					)
				);
			}
		}


		/**
		 * Get Maximun size of file to be uploaded
		 *
		 * @since 1.0
		 * @access public
		 */
		public function get_max_size( $formatted = false ) {
			$max_size = (int) alpha_get_option( 'product_review_image_size', 1 ) * MB_IN_BYTES;
			return $formatted ? size_format( $max_size ) : $max_size;
		}

		/**
		 * Get size of image to be attached to comment
		 *
		 * @since 1.0
		 * @access public
		 * @return array
		 */
		public function get_image_sizes() {
			$sizes = array( 'thumbnail' );

			return $sizes;
		}

		/**
		 * Get attached image ids as array
		 *
		 * @since 1.0
		 * @access public
		 * @param integer comment_id
		 */
		public function get_attached_image_ids_arr( $comment_id = 0 ) {
			if ( ! $comment_id ) {
				$comment    = get_comment();
				$comment_id = $comment ? $comment->comment_ID : '';
			}
			$img_str = get_comment_meta( $comment_id, $this->meta_key_image, true );
			if ( empty( $img_str ) ) {
				return array();
			}
			return explode( ',', $img_str );
		}

		/**
		 * Get attached video ids as array
		 *
		 * @since 1.0
		 * @access public
		 * @param integer comment_id
		 */
		public function get_attached_video_ids_arr( $comment_id = 0 ) {
			if ( ! $comment_id ) {
				$comment    = get_comment();
				$comment_id = $comment ? $comment->comment_ID : '';
			}
			$img_str = get_comment_meta( $comment_id, $this->meta_key_video, true );
			if ( empty( $img_str ) ) {
				return array();
			}
			return explode( ',', $img_str );
		}


		/**
		 * Check whether comment has images or not
		 *
		 * @since 1.0
		 * @access public
		 */
		public function has_attached_images( $comment_id = 0 ) {
			if ( ! $comment_id ) {
				$comment    = get_comment();
				$comment_id = $comment ? $comment->comment_ID : '';
			}

			$attached_image_ids_arr = $this->get_attached_image_ids_arr( $comment_id );

			if ( 1 == count( $attached_image_ids_arr ) && '' == $attached_image_ids_arr[0] ) {
				return false;
			}
			return true;
		}

		/**
		 * Check whether comment has images or not
		 *
		 * @since 1.0
		 * @access public
		 */
		public function has_attached_videos( $comment_id = 0 ) {
			if ( ! $comment_id ) {
				$comment    = get_comment();
				$comment_id = $comment ? $comment->comment_ID : '';
			}

			$attached_video_ids_arr = $this->get_attached_video_ids_arr( $comment_id );

			if ( 1 == count( $attached_video_ids_arr ) && '' == $attached_video_ids_arr[0] ) {
				return false;
			}
			return true;
		}

		/**
		 * Add Input field with file type
		 *
		 * @since 1.0
		 * @access public
		 */
		public function add_file_input( $fields ) {
			// For review modal
			if ( alpha_is_product() || ( alpha_doing_ajax() && isset( $_REQUEST['action'] ) && 'alpha_reviews' == $_REQUEST['action'] ) ) {
				if ( is_user_logged_in() ) {
					$field_name = $this->field_name;
					$max_size   = $this->get_max_size();

					$image_template = '<div class="file-input form-control image-input" style="background-size: contain; background-image: url(' . wc_placeholder_img_src( array( 80, 80 ) ) . ');">
						<div class="file-input-wrapper"></div>
						<label class="btn-action btn-upload" title="Upload Media">
							<input type="file" accept=".jpeg, .jpg, .png" name="' . $field_name . '[]">
						</label>
						<label class="btn-action btn-remove" title="Remove Media">
						</label>
					</div>';

					$video_template = '<div class="file-input form-control video-input" style="background-image: url(' . wc_placeholder_img_src( array( 80, 80 ) ) . ');">
						<video class="file-input-wrapper" controls></video>
						<label class="btn-action btn-upload" title="Upload Media">
							<input type="file" accept=".avi, .mp4, .mpeg, .ogv, .ts, .webm, .3gp, .3g2" name="' . $field_name . '[]">
						</label>
						<label class="btn-action btn-remove" title="Remove Media">
						</label>
					</div>';

					$upload_media  = '<div class="review-form-section">';
					$upload_media .= str_repeat( $image_template, alpha_get_option( 'product_review_image_count', 2 ) );
					$upload_media .= str_repeat( $video_template, alpha_get_option( 'product_review_video_count', 2 ) );

					// translators: Notification of image and media.
					$upload_media .= '<p class="comment-image-notice">' . sprintf( esc_html__( 'Upload images and videos. Maximum count: %1$s, size: %2$sMB', 'alpha-core' ), alpha_get_option( 'product_review_image_count', 2 ) + alpha_get_option( 'product_review_video_count', 2 ), alpha_get_option( 'product_review_image_size', 1 ) ) . '</p>';
					$upload_media .= '</div>';

					$fields = $upload_media . $fields;

				} else {
					$fields = '<p class="comment-image-notice">' . esc_html__( 'You have to login to add images.', 'alpha-core' ) . '</p>' . $fields;
				}
			}

			return $fields;
		}

		/**
		 * Check whether images are valid or not
		 *
		 * @since 1.0
		 * @access public
		 */
		public function validate_images( $comment_meta ) {

			$field_name = $this->field_name;
			if ( empty( $_FILES[ $field_name ] ) ) {
				return $comment_meta;
			}

			$max_count  = alpha_get_option( 'product_review_image_count', 2 ) + alpha_get_option( 'product_review_video_count', 2 );
			$max_size   = $this->get_max_size();
			$files      = $_FILES[ $field_name ]; // phpcs:ignore
			$file_names = $files['name'];
			$file_sizes = $files['size'];

			if ( is_array( $file_names ) && count( $file_names ) > $max_count ) {
				// translators: maximum count of images.
				wp_die( sprintf( esc_html__( 'You can upload up to % s images to review', 'alpha-core' ), $max_count ) );
			}

			foreach ( $file_sizes as $size ) {
				if ( $size > $max_size ) {
					// translators: maximum file size.
					wp_die( sprintf( esc_html__( 'Maximum file size is % s MB', 'alpha-core' ), size_format( $max_size ) ) ); //phpcs:ignore
				}
			}

			add_filter( 'upload_mimes', array( $this, 'get_image_mimetypes' ), 50 );
			foreach ( $file_names as $name ) {
				if ( $name ) {
					$filetype = wp_check_filetype( $name );

					if ( ! $filetype['ext'] ) {
						// translators: allowed image file format.
						/**
						 * Filters image types in product comment.
						 *
						 * @since 1.0
						 */
						wp_die( sprintf( esc_html__( 'You are allowed to upload images only in %s formats .', 'alpha-core' ), apply_filters( 'alpha_product_comment_images_mimetypes', 'jpeg, png, jpg, avi, mepg, ogv, ts, webm, 3gp, 3g2' ) ) );
					}
				}
			}
			remove_filter( 'upload_mimes', array( $this, 'get_image_mimetypes' ), 50 );

			return $comment_meta;
		}


		/**
		 * Upload images
		 *
		 * @since 1.0
		 * @access public
		 */
		public function save_comment_images( $comment_id, $comment_approved, $comment ) {
			$files     = $_FILES[ $this->field_name ];
			$post_id   = $comment['comment_post_ID'];
			$post      = get_post( $post_id );
			$image_ids = array();
			$video_ids = array();

			if ( ! function_exists( 'media_handle_upload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
			}

			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file    = array(
						'error'    => $files['error'][ $key ],
						'name'     => $files['name'][ $key ],
						'size'     => $files['size'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'type'     => $files['type'][ $key ],
					);
					$_FILES  = array( $this->field_name => $file );
					$title   = $file['name'];
					$dot_pos = strrpos( $title, '.' );
					if ( $dot_pos ) {
						$title = substr( $title, 0, $dot_pos );
					}

					// Upload image
					add_filter( 'intermediate_image_sizes', array( $this, 'get_image_sizes' ), 10 );
					$attachment_id = media_handle_upload(
						$this->field_name,
						$post_id,
						array(
							'post_title' => $title,
						)
					);
					remove_filter( 'intermediate_image_sizes', array( $this, 'get_image_sizes' ), 10 );

					// Check error
					if ( ! is_wp_error( $attachment_id ) ) {
						if ( 0 === strpos( $file['type'], 'video' ) ) {
							$video_ids[] = $attachment_id;
						} else {
							$image_ids[] = $attachment_id;
						}
					}

					// Add alt text for attachement
					// translators: %1$s represents author name, %2$s represents product title.
					add_post_meta( $attachment_id, '_wp_attachment_image_alt', sprintf( esc_html__( 'Attachment media of %1$s\'s review on %2$s', 'alpha-core' ), $comment['comment_author'], $post->post_title ), true );
				}
			}

			update_comment_meta( $comment_id, $this->meta_key_image, implode( ',', $image_ids ) );
			update_comment_meta( $comment_id, $this->meta_key_video, implode( ',', $video_ids ) );
		}


		/**
		 * Display attached images on comment
		 *
		 * @since 1.0
		 * @access public
		 */
		public function display_images( $comment_content ) {

			if ( ! ( $this->has_attached_images() || $this->has_attached_videos() ) || ( ! alpha_is_product() && ! alpha_doing_ajax() ) ) {
				return;
			}

			$image_ids = $this->get_attached_image_ids_arr();
			$video_ids = $this->get_attached_video_ids_arr();
			?>
			<div class="review-images">
				<?php
				foreach ( $image_ids as $image_id ) {
					if ( $image_id ) {
						$full_image = wp_get_attachment_image_src( $image_id, 'full' );
						if ( is_array( $full_image ) ) {
							echo wp_get_attachment_image(
								$image_id,
								'thumbnail',
								false,
								array(
									'data-img-src'    => esc_url( $full_image[0] ),
									'data-img-width'  => (int) $full_image[1],
									'data-img-height' => (int) $full_image[2],
								)
							);
						}
					}
				}
				foreach ( $video_ids as $video_id ) {
					if ( $video_id ) {
						$type = get_post_mime_type( $video_id );

						if ( 0 === strpos( $type, 'video' ) ) {
							$url = wp_get_attachment_url( $video_id );

							echo '<figure class="video-attachment"><video class="" src="' . esc_url( $url ) . '" preload="metadata"></video></figure>';
						}
					}
				}
				?>
			</div>
			<?php
		}


		/**
		 * Delete Images from comment
		 *
		 * @since 1.0
		 * @access public
		 */
		public function delete_image( $comment_id ) {
			if ( ! $this->has_attached_images( $comment_id ) && ! $this->has_attached_videos( $comment_id ) ) {
				return;
			}

			$image_ids = $this->get_attached_image_ids_arr( $comment_id );
			$video_ids = $this->get_attached_video_ids_arr( $comment_id );
			$media_ids = array_merge( $image_ids, $video_ids );

			foreach ( $media_ids as $id ) {
				wp_delete_attachment( $id, true );
			}

			delete_comment_meta( $comment_id, $this->meta_key_image );
			delete_comment_meta( $comment_id, $this->meta_key_video );
		}

		/**
		 * Html for comments filter select
		 *
		 * @since 1.0
		 */
		public function single_product_review_filter_select_html() {
			?>
			<select name="type" class="form-control" aria-label="Review Type" data-filter="type">
				<option value="all"><?php esc_html_e( 'All Reviews', 'alpha-core' ); ?></option>
				<option value="images"><?php esc_html_e( 'With Images', 'alpha-core' ); ?></option>
				<option value="videos"><?php esc_html_e( 'With Videos', 'alpha-core' ); ?></option>
			</select>
			<?php
		}
	}
}

Alpha_Product_Image_Comment::get_instance();
