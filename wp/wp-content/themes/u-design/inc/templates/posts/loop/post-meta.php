<?php
/**
 * Post Meta
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

global $post;

$likes_count    = intval( get_post_meta( $post->ID, 'udesign_post_likes', true ) );
$like_btn_class = isset( $_COOKIE[ 'udesign_post_likes_' . $post->ID ] ) && ( json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $post->ID ] ), true )['action'] ) ? json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $post->ID ] ), true )['action'] : 'like';
?>
<div class="post-meta">
	<div class="post-author">
		<?php
		if ( 'bordered' == alpha_get_loop_prop( 'type' ) ) {
			echo '<figure>' . apply_filters( 'post_thumbnail_html', get_avatar( get_the_author_meta( 'ID' ), 30 ) ) . '</figure>';
		}
		// translators: %s represents author link tag.
		printf( esc_html__( 'by %s', 'alpha' ), get_the_author_posts_link() );
		?>
	</div>
	<a href="#" class="vote-link <?php echo esc_attr( $like_btn_class ); ?>" data-count="<?php echo esc_attr( $likes_count ); ?>" data-id="<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html( $likes_count ); ?></a>
	<?php
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		comments_popup_link( '0', '1', '%', 'comments-link scroll-to local' );
	}
	?>
</div>
