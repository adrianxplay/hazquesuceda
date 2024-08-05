<?php
/**
 * Alpha Image Gallery Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'layout_type'         => '',
			'images'              => '',
			'overlay'             => '',
			'caption_type'        => '',
			'gallery_image_size'  => 'full',
			'slider_image_expand' => '',
			'items_list'          => '',
			'row_cnt'             => 1,
			'col_sp'              => 'md',
			'image_popup'         => 'yes',
		),
		$atts
	)
);

// Layout
$extra_class = 'image-gallery';
$extra_attrs = '';
if ( 'yes' == $image_popup ) {
	$extra_class .= ' use_lightbox';
}

$overlay_wrapper = false;
if ( $overlay && 'effect-' === substr( $overlay, 0, -1 ) ) {
	$overlay_wrapper = true;
}

if ( 'creative' != $layout_type ) {
	$col_cnt      = alpha_elementor_grid_col_cnt( $atts );
	$extra_class .= alpha_get_col_class( $col_cnt );
}
$extra_class .= alpha_get_grid_space_class( $atts );

if ( 'creative' == $layout_type ) {
	$extra_class .= ' row creative-grid grid-gallery';
	if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
		$extra_class .= ' editor-mode';
	}

	if ( is_array( $items_list ) ) {
		$repeaters = array(
			'ids'    => array(),
			'images' => array(),
		);
		foreach ( $items_list as $item ) {
			$repeaters['ids'][ (int) $item['item_no'] ]    = 'elementor-repeater-item-' . $item['_id'];
			$repeaters['images'][ (int) $item['item_no'] ] = $item['item_thumb_size'];
		}
	}
} elseif ( 'slider' == $layout_type ) {
	$extra_class .= ' slider-image-gallery';

	if ( '' == $slider_image_expand ) {
		$extra_class .= ' slider-image-org';
	}

	$extra_class .= ' ' . alpha_get_grid_space_class( $atts );
	$extra_class .= ' ' . alpha_get_slider_class( $atts );
	$extra_attrs .= ' data-slider-options="' . esc_attr(
		json_encode(
			alpha_get_slider_attrs( $atts, $col_cnt )
		)
	) . '"';
}
?>

<ul class="<?php echo esc_attr( $extra_class ); ?>"<?php echo alpha_escaped( $extra_attrs ); ?>>
	<?php
	foreach ( $images as $index => $attachment ) :
		$img_class       = 'grid' == $layout_type ? 'grid-item image-wrap' : 'image-wrap';
		$item_thumb_size = $gallery_image_size;
		$img_wrap_class  = '';
		$wrap_attrs      = '';
		if ( 'creative' == $layout_type ) {
			$img_wrap_class = 'grid-item';
			$img_wrap_attr  = '';
			if ( isset( $repeaters ) ) {
				if ( isset( $repeaters['ids'][ $index + 1 ] ) ) {
					$img_wrap_class .= ' ' . $repeaters['ids'][ $index + 1 ];
				}

				if ( isset( $repeaters['ids'][0] ) ) {
					$img_wrap_class .= ' ' . $repeaters['ids'][0];
				}

				if ( isset( $repeaters['images'][ $index + 1 ] ) ) {
					$item_thumb_size = $repeaters['images'][ $index + 1 ];
				}
			}
			$wrap_attrs = ' data-grid-idx="' . (int) ( $index + 1 ) . '"';
		} elseif ( 'slider' == $layout_type && 1 != $row_cnt ) {
			if ( 1 == ( $index + 1 ) % (int) $row_cnt ) {
				echo '<li class="gallery-col"><ul>';
			}
		}

		echo '<li class="' . esc_attr( $img_wrap_class ) . '"' . alpha_escaped( $wrap_attrs ) . '>';
		?>
		<div class="image-gallery-item<?php echo ( ! $overlay || $overlay_wrapper ) ? '' : ' ' . esc_attr( alpha_get_overlay_class( $overlay ) ); ?>">
		<?php
		$full_src = wp_get_attachment_image_src( $attachment['id'], 'full' );
		if ( ! $full_src && ! empty( $attachment['url'] ) ) {
			$full_src = array( $attachment['url'] );
		}
		if ( $overlay_wrapper ) {
			echo '<div class="overlay-wrapper">';
			if ( 'yes' == $image_popup ) {
				echo '<a href="' . esc_url( ! empty( $full_src[0] ) ? $full_src[0] : '' ) . '"></a>';
			}
			echo '<div class="overlay-effect overlay-' . $overlay . '"></div>';
		} else {
			if ( 'yes' == $image_popup ) {
				echo '<a href="' . esc_url( ! empty( $full_src[0] ) ? $full_src[0] : '' ) . '"></a>';
			}
		}
		?>
		<figure class="<?php echo esc_attr( $img_class ); ?>">
			<?php
			$image_html = wp_get_attachment_image( $attachment['id'], $item_thumb_size );

			if ( ! $image_html && ! empty( $attachment['url'] ) ) {
				echo '<img src="' . esc_url( $attachment['url'] ) . '" alt="' . esc_html__( 'Image Gallery Item', 'alpha-core' ) . '">';
			} else {
				echo $image_html;
			}

			$image_caption = '';
			if ( $caption_type ) {
				$attachment_post = get_post( $attachment['id'] );
				if ( 'icon' == $caption_type ) {
					if ( $atts['gallery_icon'] ) {
						$image_caption = '<i class="' . $atts['gallery_icon']['value'] . '"></i>';
					}
				} elseif ( 'caption' == $caption_type ) {
					$image_caption = $attachment_post->post_excerpt;
				} elseif ( 'title' == $caption_type ) {
					$image_caption = $attachment_post->post_title;
				} else {
					$image_caption = $attachment_post->post_content;
				}
			}

			if ( ! empty( $image_caption ) ) {
				echo '<figcaption class="elementor-image-carousel-caption">' . alpha_strip_script_tags( $image_caption ) . '</figcaption>';
			}
			?>
		</figure>
		</div>
		<?php
		if ( $overlay_wrapper ) {
			echo '</div>';
		}
		echo '</li>';
		if ( 'slider' == $layout_type && 1 != $row_cnt ) {
			if ( 0 == ( $index + 1 ) % (int) $row_cnt ) {
				echo '</ul></li>';
			}
		}
	endforeach;
	?>
</ul>
