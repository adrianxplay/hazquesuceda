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

$html = '';

if ( ! isset( $show_info ) || in_array( 'author', $show_info ) ) {
	$html .= '<span class="post-author">';
	$html .= get_the_author_posts_link();
	$html .= '</span>';
}

if ( ! isset( $show_info ) || in_array( 'like', $show_info ) ) {

	$like_btn_class = isset( $_COOKIE[ 'udesign_post_likes_' . $post->ID ] ) && ( json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $post->ID ] ), true )['action'] ) ? json_decode( wp_unslash( $_COOKIE[ 'udesign_post_likes_' . $post->ID ] ), true )['action'] : 'like';
	$likes_count    = get_post_meta( $post->ID, 'udesign_post_likes', true );

	ob_start();
	?>
	<a href="#" class="vote-link <?php echo esc_attr( $like_btn_class ); ?>" data-count="<?php echo absint( $likes_count ); ?>" data-id="<?php echo absint( $post->ID ); ?>"><span class="like-count"><?php echo absint( $likes_count ); ?></span><?php echo esc_html__( ' Like Post', 'alpha' ); ?></a>
	<?php
	$html .= ob_get_clean();
}

if ( ! isset( $show_info ) || in_array( 'comment', $show_info ) && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
	ob_start();

	// translators: %1$s and %2$s are opening and closing of mark tag.
	$zero = sprintf( esc_html__( '%1$s0%2$s Comment', 'alpha' ), '<mark>', '</mark>' ); //phpcs:ignore
	// translators: %1$s and %2$s are opening and closing of mark tag.
	$one = sprintf( esc_html__( '%1$s1%2$s Comment', 'alpha' ), '<mark>', '</mark>' ); //phpcs:ignore
	// translators: %1$s and %3$s are opening and closing of mark tag, %2$s is %.
	$more = sprintf( esc_html__( '%1$s%2$s%3$s Comments', 'alpha' ), '<mark>', '%', '</mark>' ); //phpcs:ignore

	comments_popup_link( $zero, $one, $more, 'comments-link scroll-to local' );

	$html .= ob_get_clean();
}

if ( $html ) {
	echo '<div class="post-meta">' . alpha_escaped( $html ) . '</div>';
}
