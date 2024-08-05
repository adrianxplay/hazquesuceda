<?php
/**
 * Alpha Helpful Comments class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Helpful_Comments' ) ) {
	/**
	 * Alpha Helpful Comments class
	 *
	 * @since 1.0
	 */
	class Alpha_Helpful_Comments extends Alpha_Base {

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
		 */
		public function __construct() {

			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );

			// display comment filter select-box
			add_filter( 'alpha_single_product_review_filter_select_html', array( $this, 'single_product_review_filter_select_html' ) );
			add_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 11 ); // ?
			remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_rating', 15 ); // ?

			// display comment vote
			add_action( 'woocommerce_review_after_comment_text', array( $this, 'display_comment_vote' ), 20 );

			//display helpful recommend
			add_action( 'alpha_helpful_recommended', array( $this, 'display_recommend_value' ) );

			// vote comment
			add_action( 'wp_ajax_comment_vote', array( $this, 'ajax_vote_comment' ) );
			add_action( 'wp_ajax_nopriv_comment_vote', array( $this, 'ajax_vote_comment' ) );

			// get comments
			add_action( 'wp_ajax_alpha_get_comments', array( $this, 'ajax_get_comments' ) );
			add_action( 'wp_ajax_nopriv_alpha_get_comments', array( $this, 'ajax_get_comments' ) );
		}

		/**
		 * Custom style for comments pagination
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			global $post;
			if ( alpha_is_product() || ( $post && 'product_layout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) {
				wp_enqueue_style( 'alpha-product-helpful-comments', alpha_core_framework_uri( '/addons/product-helpful-comments/product-helpful-comments' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
				wp_enqueue_script( 'alpha-product-helpful-comments', alpha_core_framework_uri( '/addons/product-helpful-comments/product-helpful-comments' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
			}
		}

		/**
		 * Vote comment in ajax
		 *
		 * @since 1.0
		 */
		public function ajax_vote_comment() {
			if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'alpha-nonce' ) && isset( $_POST['commentvote'] ) ) {
				$comment_id    = intval( $_POST['comment_id'] );
				$comment_plus  = get_comment_meta( $comment_id, 'comment_plus', true );
				$comment_minus = get_comment_meta( $comment_id, 'comment_minus', true );

				$user_id = get_current_user_id();
				if ( 0 == $user_id ) {
					die( false );
				}

				$id_metas = get_comment_meta( $comment_id, 'help_comment_ids', true );

				if ( ! $id_metas ) {
					$id_metas = array();
				}

				if ( is_array( $id_metas ) && ! in_array( 'user_id-' . $user_id, array_keys( $id_metas ) ) ) {
					$id_metas[ 'user_id-' . $user_id ] = $_POST['commentvote'];
					if ( 'plus' == $_POST['commentvote'] ) {
						update_comment_meta( $comment_id, 'comment_plus', ++$comment_plus );
					} elseif ( 'minus' == $_POST['commentvote'] ) {
						update_comment_meta( $comment_id, 'comment_minus', ++$comment_minus );
					}
					update_comment_meta( $comment_id, 'help_comment_ids', $id_metas );

					$result = array(
						'plus'  => $comment_plus,
						'minus' => $comment_minus,
					);
					die( json_encode( $result ) );
				} elseif ( is_array( $id_metas ) ) {
					if ( $_POST['commentvote'] == $id_metas[ 'user_id-' . $user_id ] ) {
						unset( $id_metas[ 'user_id-' . $user_id ] );
						if ( 'plus' == $_POST['commentvote'] ) {
							update_comment_meta( $comment_id, 'comment_plus', --$comment_plus );
						} elseif ( 'minus' == $_POST['commentvote'] ) {
							update_comment_meta( $comment_id, 'comment_minus', --$comment_minus );
						}
						update_comment_meta( $comment_id, 'help_comment_ids', $id_metas );
					} elseif ( $_POST['commentvote'] != $id_metas[ 'user_id-' . $user_id ] ) {
						$id_metas[ 'user_id-' . $user_id ] = $_POST['commentvote'];
						if ( 'plus' == $_POST['commentvote'] ) {
							update_comment_meta( $comment_id, 'comment_plus', ++$comment_plus );
							update_comment_meta( $comment_id, 'comment_minus', --$comment_minus );
						} elseif ( 'minus' == $_POST['commentvote'] ) {
							update_comment_meta( $comment_id, 'comment_plus', --$comment_plus );
							update_comment_meta( $comment_id, 'comment_minus', ++$comment_minus );
						}
						update_comment_meta( $comment_id, 'help_comment_ids', $id_metas );
					}
					$result = array(
						'plus'  => $comment_plus,
						'minus' => $comment_minus,
					);
					die( json_encode( $result ) );
				}
			}
		}

		/**
		 * Html for comments filter select
		 *
		 * @since 1.0
		 */
		public function single_product_review_filter_select_html() {
			?>
				<select name="order" class="form-control" aria-label="Review Order" data-filter="order">
					<option value="default"><?php esc_html_e( 'Default', 'alpha-core' ); ?></option>
					<option value="newest"><?php esc_html_e( 'Newest Comment', 'alpha-core' ); ?></option>
					<option value="oldest"><?php esc_html_e( 'Oldest Comment', 'alpha-core' ); ?></option>
					<option value="highest"><?php esc_html_e( 'Highest Rating', 'alpha-core' ); ?></option>
					<option value="lowest"><?php esc_html_e( 'Lowest Rating', 'alpha-core' ); ?></option>
					<option value="helpful"><?php esc_html_e( 'Helpful Comment', 'alpha-core' ); ?></option>
					<option value="unhelpful"><?php esc_html_e( 'Unhelpful Comment', 'alpha-core' ); ?></option>
				</select>
			<?php
		}

		/**
		 * Get the Sorted Comments for ajax
		 *
		 * @since 1.0
		 */
		public function ajax_get_comments() {
			if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'alpha-nonce' ) ) {

				global $post;
				$post_id = absint( $_POST['post_id'] );
				$post    = get_post( $post_id );
				$page    = absint( $_POST['page'] );
				$order   = $_POST['order'];
				$type    = $_POST['type'];

				$req_posts = get_post( $post_id );

				if ( isset( $req_posts ) ) {
					global $wp_query;
					$wp_query->is_single   = true;
					$wp_query->is_singular = true;
					$wp_query->set( 'cpage', $page );
					$wp_query->set( 'comments_per_page', get_option( 'comments_per_page' ) );

					$comment_counts = wp_count_comments( $post_id );
					$comments       = array();

					ob_start();
					if ( empty( $comment_counts->approved ) ) {
						esc_html_e( 'No comments on this post.', 'alpha-core' );
					}

					$comments_temp = get_comments(
						array(
							'post_id' => $post_id,
							'status'  => 'approve',
							'orderby' => 'comment_date',
							'order'   => 'ASC',
						)
					);

					foreach ( $comments_temp as $key => $comment ) {
						$comment->comment_rating       = get_comment_meta( $comment->comment_ID, 'rating', true );
						$comment->comment_images       = get_comment_meta( $comment->comment_ID, $this->meta_key_image, true );
						$comment->comment_videos       = get_comment_meta( $comment->comment_ID, $this->meta_key_video, true );
						$comment->comment_help_count   = get_comment_meta( $comment->comment_ID, 'comment_plus', true );
						$comment->comment_unhelp_count = get_comment_meta( $comment->comment_ID, 'comment_minus', true );

						if ( 'images' == $type && 1 == count( explode( ',', $comment->comment_images ) ) && '' == $comment->comment_images ) {
							continue;
						}

						if ( 'videos' == $type && 1 == count( explode( ',', $comment->comment_videos ) ) && '' == $comment->comment_videos ) {
							continue;
						}

						if ( ( 'helpful' == $order && $comment->comment_rating <= 2.9 ) || ( 'unhelpful' == $order && $comment->comment_rating >= 2.1 ) ) {
							continue;
						}

						array_push( $comments, $comment );
					}

					if ( 'newest' == $order ) {
						usort( $comments, array( $this, 'order_newest' ) );
					} elseif ( 'oldest' == $order ) {
						usort( $comments, array( $this, 'order_oldest' ) );
					} elseif ( 'highest' == $order ) {
						usort( $comments, array( $this, 'order_highest' ) );
					} elseif ( 'lowest' == $order ) {
						usort( $comments, array( $this, 'order_lowest' ) );
					} elseif ( 'helpful' == $order ) {
						usort( $comments, array( $this, 'order_helpful' ) );
					} elseif ( 'unhelpful' == $order ) {
						usort( $comments, array( $this, 'order_unhelpful' ) );
					}

					wp_list_comments(
						apply_filters(
							'woocommerce_product_review_list_args',
							array(
								'callback'          => 'woocommerce_comments',
								'reverse_top_level' => 'default' == $order ? null : false,
							)
						),
						$comments
					);

					$html = ob_get_clean();

					$wp_query->comments = $comments;

					$pagination = alpha_get_review_pagination();

					wp_send_json(
						array(
							'html'       => $html,
							'pagination' => $pagination,
						)
					);
				}
			}
			die;
		}

		/**
		 * Sort comments by newest order
		 *
		 * @since 1.0
		 */
		public function order_newest( $comment1, $comment2 ) {
			return $comment1->comment_date > $comment2->comment_date ? -1 : 1;
		}

		/**
		 * Sort comments by oldest order
		 *
		 * @since 1.0
		 */
		public function order_oldest( $comment1, $comment2 ) {
			return $comment1->comment_date < $comment2->comment_date ? -1 : 1;
		}

		/**
		 * Sort comments by highest order
		 *
		 * @since 1.0
		 */
		public function order_highest( $comment1, $comment2 ) {
			return -intval( $comment1->comment_rating ) + intval( $comment2->comment_rating );
		}

		/**
		 * Sort comments by lowest order
		 *
		 * @since 1.0
		 */
		public function order_lowest( $comment1, $comment2 ) {
			return intval( $comment1->comment_rating ) - intval( $comment2->comment_rating );
		}

		/**
		 * Sort comments by helpful order
		 *
		 * @since 1.0
		 */
		public function order_helpful( $comment1, $comment2 ) {
			return -intval( $comment1->comment_help_count ) + intval( $comment2->comment_help_count );
		}

		/**
		 * Sort comments by unhelpful order
		 *
		 * @since 1.0
		 */
		public function order_unhelpful( $comment1, $comment2 ) {
			return -intval( $comment1->comment_unhelp_count ) + intval( $comment2->comment_unhelp_count );
		}

		/**
		 * Display helpful or unhelpful vote buttons in comment.
		 *
		 * @since 1.0
		 */
		public function display_comment_vote( $comment ) {
			$comment_id           = get_comment_ID();
			$comment_help_count   = get_comment_meta( $comment_id, 'comment_plus', true );
			$comment_unhelp_count = get_comment_meta( $comment_id, 'comment_minus', true );
			$id_metas             = get_comment_meta( $comment_id, 'help_comment_ids', true );
			$user_id              = get_current_user_id();
			$status               = '';
			if ( ! empty( $id_metas[ 'user_id-' . $user_id ] ) ) {
				$status = $id_metas[ 'user_id-' . $user_id ];
			}
			?>
			<div class="review-vote" id="alpha_review_vote-<?php echo absint( $comment_id ); ?>">
				<span class="comment_help btn btn-link <?php echo ( 'plus' == $status ? 'already-voted' : '' ); ?>" data-comment_id="<?php echo absint( $comment_id ); ?>" data-count="<?php echo absint( $comment_help_count ); ?>">
					<i class="far fa-thumbs-up"></i><?php esc_html_e( 'Helpful', 'alpha-core' ); ?> (<span id="commenthelp-count-<?php echo absint( $comment_id ); ?>"><?php echo intval( $comment_help_count ); ?></span>)
				</span>
				<span class="comment_unhelp btn btn-link <?php echo ( 'minus' == $status ? 'already-voted' : '' ); ?>" data-comment_id="<?php echo absint( $comment_id ); ?>" data-count="<?php echo absint( $comment_unhelp_count ); ?>">
					<i class="far fa-thumbs-down"></i><?php esc_html_e( 'Unhelpful', 'alpha-core' ); ?> (<span id="commentunhelp-count-<?php echo absint( $comment_id ); ?>"><?php echo intval( $comment_unhelp_count ); ?></span>)
				</span>
				<?php if ( 0 == get_current_user_id() ) : ?>
					<span class="comment_alert" style="display: none;"><?php esc_html_e( 'You have to login to vote comments.', 'alpha-core' ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Display recommended percentage on single product page
		 *
		 * @since 1.0
		 */
		public function display_recommend_value( $product ) {
			$post_id           = $product->get_id();
			$recommended_count = 0;
			$total_count       = $product->get_review_count();

			$comments_temp = get_comments(
				array(
					'post_id' => $post_id,
					'status'  => 'approve',
					'orderby' => 'comment_date',
					'order'   => 'DESC',
				)
			);

			foreach ( $comments_temp as $key => $comment ) {
				$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
				if ( absint( $rating ) >= 4 ) {
					++ $recommended_count;
				}
			}

			if ( $total_count > 0 ) {
				?>
				<h4 class="recommended-value">
					<mark>
						<?php
						if ( $total_count ) {
							$v = $recommended_count * 100 / $total_count;
							if ( 100 == $v ) {
								echo '100%';
							} else {
								printf( 10 <= $v ? '%.1f%%' : '%.2f%%', $v );
							}
						} else {
							echo '0.00%';
						}
						?>
					</mark>
					<?php esc_html_e( 'Recommended', 'alpha-core' ); ?>
					<span>
						<?php
						/* translators: %1$d represents recommended count, %2$s represents total count. */
						printf( esc_html__( '(%1$d of %2$d)', 'alpha-core' ), $recommended_count, $total_count );
						?>
					</span>
				</h4>
				<?php
			}
		}
	}
}

Alpha_Helpful_Comments::get_instance();
