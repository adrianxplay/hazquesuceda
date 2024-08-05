<?php
/**
 * Post Media
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

global $post, $alpha_post_image_size;

$post_type  = get_post_type();
$image_size = $alpha_post_image_size ? $alpha_post_image_size : alpha_get_loop_prop( 'image_size' );

if ( 'video' == get_post_format() && get_post_meta( $post->ID, 'featured_video' ) && ! in_array( alpha_get_loop_prop( 'type' ), array( 'widget', 'mask' ) ) ) {

	$video_code = get_post_meta( $post->ID, 'featured_video', true );
	wp_enqueue_script( 'jquery-fitvids' );

	if ( false === strpos( $video_code, '[video src="' ) ) {
		$video_code = '[video src="' . $video_code . '"]';
	}
	if ( false !== strpos( $video_code, '[video src="' ) && has_post_thumbnail() ) {
		$video_code = str_replace( '[video src="', '[video poster="' . esc_url( get_the_post_thumbnail_url( null, 'full' ) ) . '" src="', $video_code );
	}
	?>
	<figure class="post-media fit-video">
		<?php echo do_shortcode( $video_code ); ?>
	</figure>
	<?php

} else {

	$featured_id = get_post_thumbnail_id();
	// get supported images of the post
	$image_ids = get_post_meta( $post->ID, 'supported_images' );
	if ( empty( $image_ids ) ) {
		$image_ids = array();
	}
	if ( $featured_id ) {
		$image_ids = array_merge( array( $featured_id ), $image_ids );
	}

	if ( is_array( $image_ids ) && count( $image_ids ) ) {
		if ( count( $image_ids ) > 1 && 'large' == $image_size ) {
			$col_cnt = alpha_get_responsive_cols( array( 'lg' => 1 ) );

			$attrs = array( 'col_sp' => 'no' );

			if ( in_array( alpha_get_loop_prop( 'type' ), array( 'mask' ) ) ) {
				$attrs['show_dots'] = '';
			}
			?>
			<div class="post-media-carousel slider-dots-white 
				<?php
				echo alpha_get_col_class( $col_cnt ) . ' ' . alpha_get_slider_class(
					array(
						'dots_pos' => 'inner',
					)
				);
				?>
				" data-slider-options="<?php echo esc_attr( json_encode( alpha_get_slider_attrs( $attrs, $col_cnt ) ) ); ?>">
			<?php
		} else {
			$image_ids = array( $image_ids[0] );
		}

		foreach ( $image_ids as $thumbnail_id ) {
			?>
			<figure class="post-media">
				<a href="<?php the_permalink(); ?>">
					<?php
					$size = apply_filters( 'post_thumbnail_size', $image_size, $post->ID );

					if ( $thumbnail_id ) {
						do_action( 'begin_fetch_post_thumbnail_html', $post->ID, $thumbnail_id, $size );
						if ( in_the_loop() ) {
							update_post_thumbnail_cache();
						}
						$html = wp_get_attachment_image( $thumbnail_id, $size, false );
						do_action( 'end_fetch_post_thumbnail_html', $post->ID, $thumbnail_id, $size );
					} else {
						$html = '';
					}

					echo apply_filters( 'post_thumbnail_html', $html, $post->ID, $thumbnail_id, $size, '' );

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
			</figure>
			<?php
		}

		if ( count( $image_ids ) > 1 ) {
			?>
			</div>
			<?php
		}
	} else {
		?>
		<figure class="post-media">
			<a href="<?php the_permalink(); ?>">
				<img src="<?php echo esc_url( ALPHA_ASSETS . '/images/placeholders/' . ( ( false !== strpos( $post_type, ALPHA_NAME ) || 'post' == $post_type || 'product' == $post_type ) ? $post_type : 'post' ) . '-placeholder.jpg' ); ?>" alt="<?php esc_attr_e( 'Post placeholder image', 'alpha' ); ?>">
			</a>
		</figure>
		<?php
	}
}
