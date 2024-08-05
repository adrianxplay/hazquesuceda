<?php
/**
 * Portfolio Media
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

global $post;

$video_code = apply_filters( 'alpha_featured_video_meta_value', get_post_meta( $post->ID, 'featured_video', true ) );

if ( $video_code ) {

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

	$image_ids   = array();
	$featured_id = get_post_thumbnail_id();

	if ( $featured_id ) {
		$image_ids = array_merge( array( $featured_id ), $image_ids );
	}

	if ( count( $image_ids ) ) {
		if ( count( $image_ids ) > 1 ) {

			$col_cnt      = alpha_get_responsive_cols( array( 'lg' => 1 ) );
			$slider_attrs = array(
				'col_sp' => 'no',
			);
			$media_class  = 'post-media-carousel slider-dot-white slider-nav-inner' . alpha_get_col_class( $col_cnt ) . ' ' . alpha_get_slider_class();
			$media_attr   = esc_attr(
				json_encode(
					alpha_get_slider_attrs(
						$slider_attrs,
						$col_cnt
					)
				)
			);
			?>
			<div class="<?php echo esc_attr( $media_class ); ?>" data-slider-options="<?php echo esc_attr( $media_attr ); ?>">
			<?php
		} else {
			$image_ids = array( $image_ids[0] );
		}

		foreach ( $image_ids as $thumbnail_id ) {
			?>
			<figure class="post-media">
				<?php
				if ( ! alpha_get_loop_prop( 'rollover' ) ) {
					echo '<a href="' . get_the_permalink() . '">';
				}

				$size = apply_filters( 'post_thumbnail_size', alpha_get_loop_prop( 'image_size' ), $post->ID );

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

				if ( ! alpha_get_loop_prop( 'rollover' ) ) {
					echo '</a>';
				}

				if ( alpha_get_loop_prop( 'rollover' ) ) {
					echo '<div class="rollover">';
					echo '<a href="' . get_the_permalink() . '" aria-label="' . esc_attr( 'Visit', 'alpha-core' ) . '"></a>';
						echo '<div class="rollover-content">';
							echo '<a href="' . wp_get_attachment_url( $thumbnail_id ) . '" class="rollover-button rollover-gallery gallery-popup-item" aria-label="' . esc_attr( 'Quick View', 'alpha-core' ) . '"></a>';
							echo '<a class="rollover-button rollover-visit" href="' . get_the_permalink() . '" aria-label="' . esc_attr( 'Visit', 'alpha-core' ) . '"></a>';
					if ( 'gallery' == alpha_get_loop_prop( 'type' ) ) {
							echo '<h3 class="rollover-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
					}
						echo '<a href="' . get_the_permalink() . '" class="rollover-link-wrapper" aria-label="' . esc_attr( 'Rollover Link', 'alpha-core' ) . '"></a>';
						echo '</div>';
					echo '</div>';
				}
				?>
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
				<img src="<?php echo ALPHA_ASSETS . '/images/placeholders/udesign_portfolio-placeholder.jpg'; ?>" alt="<?php esc_attr_e( 'Portfolio placeholder', 'alpha-core' ); ?>">
			</a>
		</figure>
		<?php
	}
}
