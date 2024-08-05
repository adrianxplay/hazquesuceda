<?php
/**
 * Post Media
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

global $post;

$post_type = get_post_type();

$overlay_html = '';

$type = alpha_get_loop_prop( 'type' );
if ( 'info' != $type ) {

	$overlay_html = '';

	ob_start();

	echo '<div class="member-hidden-social">';
	alpha_get_template_part( 'posts/loop/member', 'share' );
	echo '</div>';

	if ( 'gallery' == $type ) {
		echo '<div class="member-hidden-info">';
		alpha_get_template_part( 'posts/loop/post', 'title' );
		alpha_get_template_part( 'posts/loop/post', 'category' );
		echo '</div>';
	}

	$overlay_html .= ob_get_clean();
}

$featured_id = get_post_thumbnail_id();

if ( $featured_id ) {
	?>
	<figure class="post-media">
		<a href="<?php the_permalink(); ?>">
			<?php
			$size = apply_filters( 'post_thumbnail_size', alpha_get_loop_prop( 'image_size' ), $post->ID );

			if ( $featured_id ) {
				do_action( 'begin_fetch_post_thumbnail_html', $post->ID, $featured_id, $size );
				if ( in_the_loop() ) {
					update_post_thumbnail_cache();
				}
				$html = wp_get_attachment_image( $featured_id, $size, false );
				do_action( 'end_fetch_post_thumbnail_html', $post->ID, $featured_id, $size );
			} else {
				$html = '';
			}

			echo apply_filters( 'post_thumbnail_html', $html, $post->ID, $featured_id, $size, '' );

			// Caption
			$caption = get_the_post_thumbnail_caption();
			if ( $caption ) {
				?>
				<figcaption class="thumbnail-caption">
					<?php echo alpha_strip_script_tags( $caption ); ?>
				</figcaption>
				<?php
			}
			?>
		</a>
		<?php echo alpha_strip_script_tags( $overlay_html ); ?>
	</figure>
	<?php
} else {
	?>
	<figure class="post-media">
		<a href="<?php the_permalink(); ?>">
			<img src="<?php echo ALPHA_ASSETS . '/images/placeholders/' . $post_type . '-placeholder.jpg'; ?>" alt="<?php esc_attr_e( 'Member placeholder', 'alpha-core' ); ?>">
		</a>
		<?php echo alpha_strip_script_tags( $overlay_html ); ?>
	</figure>
	<?php
}
