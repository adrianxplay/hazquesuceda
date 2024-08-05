<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Button Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'label'                      => '',
			'button_expand'              => '',
			'button_text_hover_effect'   => '',
			'button_bg_hover_effect'     => '',
			'button_bg_hover_color'      => '',
			'svg_hover_effect'           => '',
			'button_hover_outline_color' => '',
			'button_type'                => '',
			'button_size'                => '',
			'button_skin'                => 'btn-primary',
			'button_gradient_skin'       => '',
			'shadow'                     => '',
			'button_border'              => '',
			'link_hover_type'            => '',
			'show_underline'             => '',
			'link'                       => '',
			'show_icon'                  => '',
			'icon'                       => '',
			'icon_pos'                   => 'after',
			'icon_hover_effect'          => '',
			'icon_hover_effect_infinite' => '',
			'btn_class'                  => '',
			'show_icon'                  => '',
			'play_btn'                   => '',
			'video_btn'                  => '',
			'video_url'                  => array( 'url' => '#' ),
			'vtype'                      => 'youtube',
			'class'                      => '',
			'wrap_class'                 => '',

			// For elementor inline editing
			'self'                       => '',

			// dynamic content
			'dynamic_content'            => '',
		),
		$atts
	)
);

// dynamic content
if ( ! empty( $atts['link_source'] ) && isset( $dynamic_content ) && ! empty( $dynamic_content['source'] ) ) {
	$btn_link = apply_filters( 'alpha_dynamic_tags_content', '', null, $dynamic_content );
	if ( $btn_link ) {
		if ( $self ) { // for Elementor
			if ( ! is_array( $link ) ) {
				$link = array();
			}
			$link['url'] = $btn_link;
		} else { // for Gutenberg
			$link = $btn_link;
		}
	}
}

$class .= 'btn';

if ( $button_expand ) {
	$class .= ' btn-block';
}

if ( $self ) { // For Elementor
	$label = alpha_widget_button_get_label( $atts, $self, $label, 'label' );
} else { // For Gutenberg
	if ( $label ) {
		$label_span = '<span class="button-label"' . ( $button_text_hover_effect ? ' data-text="' . esc_attr( $label ) . '"' : '' ) . '>' . esc_html( $label ) . '</span>';
	} else {
		$label_span = '';
	}

	$gb_label = $label_span;

	if ( $show_icon && 'before' === $icon_pos ) {
		$gb_label = sprintf( '<i class="' . $icon . '"></i>' . $label_span );
	}
	if ( $show_icon && 'after' === $icon_pos ) {
		$gb_label = sprintf( $label_span . '<i class="' . $icon . '"></i>' );
	}
}

$class .= ' ' . implode( ' ', alpha_widget_button_get_class( $atts ) );

global $alpha_section;

if ( isset( $play_btn ) && $play_btn ) {
	$alpha_section['video_btn'] = true;
	$class                     .= ' btn-video elementor-custom-embed-image-overlay';
	$options                    = array();
	if ( isset( $alpha_section['lightbox'] ) ) {
		$options = $alpha_section['lightbox'];
	}
	echo '<div class="' . esc_attr( $class ) . '" role="button"' . ( $options ? ( ' data-elementor-open-lightbox="yes" data-elementor-lightbox="' . esc_attr( json_encode( $options ) ) . '"' ) : '' ) . '>' . alpha_strip_script_tags( $label ) . '</div>';
} elseif ( $video_btn ) {
	wp_enqueue_style( 'alpha-magnific-popup' );
	wp_enqueue_script( 'alpha-magnific-popup' );

	$class .= ' btn-video-iframe';
	printf( '<a class="' . esc_attr( $class ) . '" href="' . esc_url( ! empty( $video_url['url'] ) ? $video_url['url'] : '#' ) . '">%1$s</a>', alpha_strip_script_tags( $label ) );
} else {
	$attrs = array();
	if ( $self ) {
		$attrs['href']   = ! empty( $link['url'] ) ? alpha_strip_script_tags( $link['url'] ) : '#';
		$attrs['target'] = ! empty( $link['is_external'] ) ? '_blank' : '';
		$attrs['rel']    = ! empty( $link['nofollow'] ) ? 'nofollow' : '';
		if ( ! empty( $link['custom_attributes'] ) ) {
			foreach ( explode( ',', $link['custom_attributes'] ) as $attr ) {
				$key   = explode( '|', $attr )[0];
				$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
				if ( isset( $attrs[ $key ] ) ) {
					$attrs[ $key ] .= ' ' . $value;
				} else {
					$attrs[ $key ] = $value;
				}
			}
		}
	} else {
		$attrs['href']   = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#';
		$attrs['target'] = ! empty( $link['target'] ) ? esc_attr( $link['target'] ) : '';
		$attrs['rel']    = ! empty( $link ['rel'] ) ? esc_attr( $link['rel'] ) : '';
	}
	$link_attrs = '';
	foreach ( $attrs as $key => $value ) {
		if ( ! empty( $value ) ) {
			$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
		}
	}

	if ( $self ) { //For Elementor
		printf( '<a class="' . esc_attr( $class ) . '" ' . $link_attrs . '>%1$s</a>', alpha_strip_script_tags( $label ) );
	} else { //For Gutenberg
		if ( $button_text_hover_effect ) {
			$class .= ' btn-text-hover-effect ' . $button_text_hover_effect;
		}
		if ( '' == $button_type || 'btn-outline' == $button_type ) {
			if ( $button_bg_hover_effect || $button_bg_hover_color ) {
				$class .= ' btn-bg-hover-effect ' . $button_bg_hover_effect;
				if ( $button_bg_hover_color ) {
					$class .= ' ' . $button_bg_hover_color;
				}
				if ( $button_hover_outline_color ) {
					$class .= ' ' . $button_hover_outline_color;
				}
			}
		}
		$link = $link ? $link : '#';
		printf( '<div class="btn-wrapper ' . esc_attr( $wrap_class ) . '"><a class="' . esc_attr( $class ) . '" ' . 'href="' . $link . '">%1$s</a></div>', alpha_strip_script_tags( $gb_label ) );
	}
}
