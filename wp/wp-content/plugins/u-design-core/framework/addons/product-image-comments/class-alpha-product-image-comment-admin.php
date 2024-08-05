<?php
/**
 * Alpha Image Comment Admin addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

// Direct access is denied
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Image_Comment_Admin' ) ) {
	class Alpha_Product_Image_Comment_Admin extends Alpha_Base {

		/**
		 * Meta key for comment image
		 *
		 * @since 1.0
		 * @access public
		 */
		public $meta_key_image = '_alpha_comment_image';
		public $meta_key_video = '_alpha_comment_video';


		/**
		 * Meta keys for comment media
		 *
		 * @since 1.0
		 */
		public $limit_count = 2;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			if ( 'comment.php' == $GLOBALS['pagenow'] || ( 'edit.php' == $GLOBALS['pagenow'] && isset( $_GET['page'] ) && 'product-reviews' == $_GET['page'] ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			}
			add_action( 'add_meta_boxes_comment', array( $this, 'add_meta_boxes' ) );
			add_action( 'edit_comment', array( $this, 'save_comment_meta' ), 10, 2 );

			//add_filter( 'manage_edit-comments_columns', array( $this, 'add_comment_list_columns' ) );
			//add_action( 'manage_comments_custom_column', array( $this, 'add_comment_list_column' ), 10, 2 );

			add_filter( 'woocommerce_product_reviews_table_columns', array( $this, 'add_comment_list_columns' ) );
			add_filter( 'woocommerce_product_reviews_table_column_attachments_content', array( $this, 'add_comment_list_column' ), 10, 2 );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since 1.0
		 * @access public
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'alpha-product-image-comments-admin', alpha_core_framework_uri( '/addons/product-image-comments/product-image-comments-admin.min.css' ), null, ALPHA_CORE_VERSION, 'all' );
			wp_enqueue_script( 'alpha-product-image-comments-admin', alpha_core_framework_uri( '/addons/product-image-comments/product-image-comments-admin' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		}

		/**
		 * Get the comment ID
		 *
		 * @since 1.0
		 * @access public
		 */
		public function get_the_comment_id() {
			$comment = get_comment();

			if ( ! $comment ) {
				return '';
			}

			return $comment->comment_ID;
		}


		/**
		 * Add comment metaboxes
		 *
		 * @since 1.0
		 * @access public
		 */
		public function add_meta_boxes() {
			add_meta_box( 'alpha-comment-images-metabox', ALPHA_DISPLAY_NAME . esc_html__( ' Comment Images', 'alpha-core' ), array( $this, 'render' ), null, 'normal', 'low' );
		}


		/**
		 * Save comment meta
		 *
		 * @since 1.0
		 * @access public
		 */
		public function save_comment_meta( $comment_id, $data ) {
			if ( isset( $_POST[ $this->meta_key_image ] ) ) {
				$value = sanitize_text_field( $_POST[ $this->meta_key_image ] );
				update_comment_meta( $comment_id, $this->meta_key_image, $value );
			}
			if ( isset( $_POST[ $this->meta_key_video ] ) ) {
				$value = sanitize_text_field( $_POST[ $this->meta_key_video ] );
				update_comment_meta( $comment_id, $this->meta_key_video, $value );
			}
		}


		/**
		 * Render meta field layout to add metaboxes
		 *
		 * @since 1.0
		 * @access public
		 */
		public function render() {
			$comment_id    = $this->get_the_comment_id();
			$img_ids       = get_comment_meta( $comment_id, $this->meta_key_image, true );
			$img_ids_arr   = explode( ',', $img_ids );
			$video_ids     = get_comment_meta( $comment_id, $this->meta_key_video, true );
			$video_ids_arr = explode( ',', $video_ids );

			?>
			<div class="alpha-comment-meta-box-layout">
				<div class="alpha-comment-img-preview-area">
					<?php
					$i = 0;
					while ( $i < count( $img_ids_arr ) ) :
						$image_data = wp_get_attachment_image_src( $img_ids_arr[ $i ], 'full' );
						$link       = is_array( $image_data ) && $image_data[0];
						if ( '' != $img_ids_arr[ $i ] ) :
							?>
						<div class="comment-img-wrapper" data-attachment_id="<?php echo esc_attr( $img_ids_arr[ $i ] ); ?>">
							<?php
							echo wp_get_attachment_image(
								$img_ids_arr[ $i ],
								'thumbnail',
								array(
									'class' => 'alpha-gallery-image',
								)
							);
							?>
							<a href="#" class="button-image-remove"><span class="dashicons dashicons-dismiss"></span></a>
						</div>
							<?php
						endif;
						$i ++;
					endwhile;
							$i = 0;
					while ( $i < count( $video_ids_arr ) ) :
						$video_id = $video_ids_arr[ $i ];

						if ( '' != $video_id ) :
							$url = wp_get_attachment_url( $video_id );
							?>
									<div class="comment-img-wrapper" data-type="video" data-attachment_id="<?php echo esc_attr( $video_ids_arr[ $i ] ); ?>">
										<a href="<?php echo esc_url( $url ); ?>" target="__blank"><video src="<?php echo esc_url( $url ); ?>" preload="metadata"></video></a>
								<a href="#" class="button-image-remove"><span class="dashicons dashicons-dismiss"></span></a>
						</div>
							<?php
						endif;
						$i ++;
						endwhile;
					?>
				</div>

				<div class="alpha-comment-action-wrapper">
					<button id="alpha-comment-image-upload-btn" class="button-image-upload button button-primary"><?php esc_html_e( 'Upload', 'alpha-core' ); ?></button>
					<input type="hidden" class="alpha-upload-input" name="<?php echo esc_attr( $this->meta_key_image ); ?>" value="<?php echo esc_attr( $img_ids ); ?>"/>
					<input type="hidden" class="alpha-upload-input" name="<?php echo esc_attr( $this->meta_key_video ); ?>" value="<?php echo esc_attr( $video_ids ); ?>"/>
				</div>
			</div>
			<?php
		}

		/**
		 * Add attachments column header to list table
		 *
		 * @since 1.0
		 * @access public
		 */
		public function add_comment_list_columns( $columns ) {
			return array_merge(
				array_slice( $columns, 0, 5 ),
				array(
					'attachments' => esc_html__( 'Attachments', 'alpha-core' ),
				),
				array_slice( $columns, 5 )
			);
		}


		/**
		 * Add attachments column data to list table
		 *
		 * @since 1.0
		 * @access public
		 */
		public function add_comment_list_column( $output, $item ) {
			$id        = $item->comment_ID;
			$image_ids = get_comment_meta( $id, $this->meta_key_image, true );
			if ( $image_ids ) {
				$image_ids = explode( ',', $image_ids );
			} else {
				$image_ids = array();
			}
			$video_ids = get_comment_meta( $id, $this->meta_key_video, true );
			if ( $video_ids ) {
				$video_ids = explode( ',', $video_ids );
			} else {
				$video_ids = array();
			}

			if ( empty( $image_ids ) && empty( $video_ids ) ) {
				$output .= '<p>' . esc_html__( 'No Attachments', 'alpha-core' ) . '</p>';
			} else {
				$cnt = $this->limit_count;

				foreach ( $image_ids as $image_id ) {
					if ( $image_id ) {
						$type = get_post_mime_type( $image_id );

						if ( 0 === strpos( $type, 'image' ) ) {
							$output .= wp_get_attachment_image(
								$image_id,
								'thumbnail',
								false
							);
						}

						-- $cnt;
					}

					if ( 0 == $cnt ) {
						break;
					}
				}

				if ( $cnt > 0 ) {
					foreach ( $video_ids as $video_id ) {
						if ( $video_id ) {
							$type = get_post_mime_type( $video_id );

							if ( 0 === strpos( $type, 'video' ) ) {
								$url = wp_get_attachment_url( $video_id );
								$output .= '<video class="" src="' . esc_url( $url ) . '" preload="metadata"></video>';
							}

							-- $cnt;
						}

						if ( 0 == $cnt ) {
							break;
						}
					}
				}

				if ( count( $image_ids ) + count( $video_ids ) > $this->limit_count ) {
					$output .= '<span class="count-badge">' . ( count( $image_ids ) + count( $video_ids ) - $this->limit_count ) . '+</span>';
				}
			}

			return $output;
		}
	}
}

Alpha_Product_Image_Comment_Admin::get_instance();
