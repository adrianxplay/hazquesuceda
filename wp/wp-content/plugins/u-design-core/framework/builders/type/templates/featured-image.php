<?php

global $alpha_post_image_size, $product, $post, $alpha_full_image_size;

if ( $alpha_post_image_size ) {
	$image_size = $alpha_post_image_size;
} else {
	$image_size = isset( $atts['image_size'] ) ? $atts['image_size'] : 'full';
}

$image_id    = false;
$image_link  = '';
$post_title  = '';
$link_target = '';

if ( isset( $atts['add_link'] ) && 'custom' == $atts['add_link'] && ! empty( $atts['custom_url'] ) ) {
	$image_link = $atts['custom_url'];
	if ( isset( $atts['link_target'] ) ) {
		$link_target = $atts['link_target'];
	}
}

if ( ( $current_object = get_queried_object() ) && $current_object->term_id ) {
	$image_id = get_term_meta( $current_object->term_id, 'thumbnail_id', true );
	if ( ! $image_link && ( ! isset( $atts['add_link'] ) || 'no' != $atts['add_link'] ) ) {
		$image_link = get_term_link( $current_object );
	}
	$post_title = $current_object->label;
} else {
	$image_id = get_post_thumbnail_id();
	if ( ! $image_link && ( ! isset( $atts['add_link'] ) || 'no' != $atts['add_link'] ) ) {
		$image_link = get_permalink();
	}
	$post_title = get_the_title();
}

if ( ! $image_id ) {
	return;
}

$image_type = isset( $atts['image_type'] ) ? $atts['image_type'] : '';
$wrap_cls   = 'alpha-tb-featured-image' . ( $image_type ? ' tb-image-type-' . $image_type : '' );
$wrap_attrs = ' data-title="' . esc_attr( $post_title ) . '"';
$video_html = '';

// image types
$attachment_ids = array();

if ( ! empty( $image_type ) && ( 'hover' == $image_type || 'slider' == $image_type || 'gallery' == $image_type ) ) {
	if ( $product ) {
		$attachment_ids = $product->get_gallery_image_ids();
	} elseif ( $post ) {
		$attachment_ids = get_post_meta( $post->ID, 'supported_images' );
	}
	array_unshift( $attachment_ids, $image_id );
} elseif ( 'video' == $image_type ) {

	if ( $product ) {
		$video_thumbnail = get_post_meta( get_the_ID(), 'alpha_product_video_thumbnail', true );
		if ( $video_thumbnail ) {
			$url    = wp_get_attachment_url( $video_thumbnail );
			$poster = get_the_post_thumbnail_url( $video_thumbnail );
			if ( ! $poster ) {
				$poster = wp_get_attachment_image_url( $image_id, 'full' );
			}
			$video_html .= do_shortcode( '[video src="' . esc_url( $url ) . '" poster="' . esc_url( $poster ) . '"]' );
		} else {
			// with video url
			$video_url = get_post_meta( get_the_ID(), 'alpha_product_video_popup_url', true );
			if ( false !== strpos( $video_url, '.mp4' ) || false !== strpos( $video_url, '.webm' ) || false !== strpos( $video_url, '.ogv' ) ) {
				$poster      = wp_get_attachment_image_url( $image_id, 'full' );
				$video_html .= do_shortcode( '[video src="' . esc_url( $video_url ) . '" poster="' . esc_url( $poster ) . '"]' );
			} else {
				$youtube_id = preg_match( '/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/', $video_url, $matches );
				if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
					$youtube_id = $matches[1];
				} else {
					$youtube_id = '';
				}
				if ( $youtube_id ) {
					$video_html .= '<div id="ytplayer_' . rand( 1000, 9999 ) . '" class="alpha-video-social video-youtube" data-video="' . esc_attr( $youtube_id ) . '" data-loop="0" data-audio="0" data-controls="1"></div>';
				} else {
					$vimeo_id = preg_match( '/^(?:https?:\/\/)?(?:www|player\.)?(?:vimeo\.com\/)?(?:video\/|external\/)?(\d+)([^.?&#"\'>]?)/', $video_url, $matches );
					if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
						$vimeo_id = $matches[1];
					} else {
						$vimeo_id = '';
					}
					if ( $vimeo_id ) {
						$video_html .= '<div id="vmplayer_' . rand( 1000, 9999 ) . '" class="alpha-video-social video-vimeo" data-video="' . esc_attr( $vimeo_id ) . '" data-loop="0" data-audio="0" data-controls="1"></div>';
					}
				}
			}
		}
	} else {
		$video_html .= do_shortcode( get_post_meta( get_the_ID(), 'featured_video', true ) );
	}
}

if ( ! empty( $product ) ) {
	$wrap_cls .= ' product-image';
}

$hover_effect = false;
if ( ! empty( $atts['hover_effect'] ) && ( empty( $image_type ) || 'slider' == $image_type || 'gallery' == $image_type ) ) {
	$wrap_cls    .= ' alpha-img-' . $atts['hover_effect'];
	$hover_effect = true;
}

if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $atts['el_class'] );
}
if ( ! empty( $atts['className'] ) ) {
	$wrap_cls .= ' ' . trim( $atts['className'] );
}

echo '<div class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-featured-image' ) ) . '"' . $wrap_attrs . '>';

if ( ! empty( $atts['show_badges'] ) && ! empty( $product ) ) {
	woocommerce_show_product_loop_sale_flash();
}

if ( count( $attachment_ids ) > 1 && ( 'slider' == $image_type || 'gallery' == $image_type ) ) {

	if ( 'slider' == $image_type ) {
		$col_cnt          = array(
			'xl'  => 1,
			'lg'  => 1,
			'md'  => 1,
			'sm'  => 1,
			'min' => 1,
		);
		$atts['show_nav'] = true;
		echo '<div class="' . alpha_get_slider_class( $atts ) . '" data-slider-options="' . esc_attr(
			json_encode(
				alpha_get_slider_attrs( $atts, $col_cnt )
			)
		) . '">';
	} else {
		$col_cnt = array(
			'xl'  => 0,
			'lg'  => 0,
			'md'  => 3,
			'sm'  => 0,
			'min' => 2,
		);
		echo '<div class="image-gallery use_lightbox' . alpha_get_col_class( $col_cnt ) . '">';
	}

	foreach ( $attachment_ids as $img_id ) {
		$attachment = wp_get_attachment_image_src( $img_id, 'full' );
		if ( ! $attachment ) {
			continue;
		}

		if ( 'gallery' == $image_type ) {
			echo '<a href="' . esc_url_raw( $attachment[0] ) . '" class="image-gallery-item">';
		} elseif ( $image_link ) {
			echo '<a href="' . esc_url_raw( $image_link ) . '"' . ( $link_target ? ' target="' . esc_attr( $link_target ) . '"' : '' ) . '>';
		}
		echo '<div class="img-thumbnail">';
		echo wp_get_attachment_image( $img_id, $image_size, false );
		echo '</div>';
		if ( 'gallery' == $image_type || $image_link ) {
			echo '</a>';
		}
	}

	echo '</div>';
} else {

	if ( $image_link && ! $video_html ) {
		echo '<a href="' . esc_url_raw( $image_link ) . '"' . ( $link_target ? ' target="' . esc_attr( $link_target ) . '"' : '' ) . '>';
	}

	if ( ! empty( $video_html ) ) {
		wp_enqueue_script( 'jquery-fitvids' );
		echo '<div class="img-thumbnail fit-video">';
		echo alpha_escaped( $video_html );
		echo '</div>';
	} else {
		if ( $hover_effect ) {
			echo '<div class="img-thumbnail">';
		}
		echo wp_get_attachment_image( $image_id, $image_size, false );
        if( $alpha_full_image_size ) {
			global $hover_full_images;
			ob_start();
			echo '<div class="featured-hover-image" style="background-image: url(' . esc_url( wp_get_attachment_image_url( $image_id, $alpha_full_image_size, false ) ) . ');"></div>';
			$hover_full_images .= ob_get_clean();
        }
		if ( $hover_effect ) {
			echo '</div>';
		}
	}

	if ( 'hover' == $image_type && count( $attachment_ids ) > 1 ) {
		echo wp_get_attachment_image( $attachment_ids[1], $image_size, false, array( 'class' => 'hover-image' ) );
	}

	if ( $image_link && ! $video_html ) {
		echo '</a>';
	}
}

if ( ! empty( $atts['show_content_hover'] ) && $content ) {
	echo '<div class="tb-hover-content' . ( empty( $atts['hover_start_effect'] ) ? '' : ' hover-start-' . esc_attr( $atts['hover_start_effect'] ) ) . '">';
	if ( $image_link ) {
		echo '<a href="' . esc_url_raw( $image_link ) . '" class="alpha-tb-link"' . ( $link_target ? ' target="' . esc_attr( $link_target ) . '"' : '' ) . '></a>';
	}
		echo do_blocks( $content );
	echo '</div>';
}

echo '</div>';
