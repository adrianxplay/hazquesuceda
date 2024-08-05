<?php
function alpha_get_filter_style( $style ) {
	$html                         = '';
	$style['opacity'] && ( $html .= 'opacity:' . $style['opacity'] . ';' );

	if ( $style['blur'] || $style['contrast'] || $style['brightness'] || $style['saturation'] || $style['hue'] ) {
		$html                     .= 'filter:';
		$style['blur'] && ( $html .= 'blur(' . $style['blur'] . 'px)' );
		$style['contrast'] && ( $html   .= ' contrast(' . $style['contrast'] . '%)' );
		$style['brightness'] && ( $html .= ' brightness(' . $style['brightness'] . '%)' );
		$style['saturation'] && ( $html .= ' saturate(' . $style['saturation'] . '%)' );
		$style['hue'] && ( $html        .= ' hue-rotate(' . $style['hue'] . 'deg)' );
		$html .= ';';
	}
	return $html;
}
if ( ! empty( $settings['img_align_selector'] ) ) {
	echo alpha_escaped( $settings['selector'] . '{ text-align: ' . esc_html( $settings['img_align_selector'] ) . '; }' );
}

if ( ! empty( $settings['img_filter_selector'] ) ) {
	echo alpha_escaped( $settings['selector'] . ' img {' . alpha_get_filter_style( $settings['img_filter_selector'] ) . '}' );
}

if ( ! empty( $settings['img_hover_filter_selector'] ) ) {
	echo alpha_escaped( $settings['selector'] . ':hover img {' . alpha_get_filter_style( $settings['img_hover_filter_selector'] ) . '}' );
}
if ( ! empty( $settings['show_caption_selector'] ) && '' != $settings['show_caption_selector'] && ! empty( $settings['img_style_selector'] ) ) {
	$html = $settings['selector'] . ' img {';
	foreach ( $settings['img_style_selector'] as $key => $setting ) {
		if ( 'width' !== $key && 'height' !== $key ) {
			if ( 'borderStyle' == $key ) {
				$html .= 'border-style:' . $setting . ';';
			} elseif ( 'borderTopWidth' == $key ) {
				$html .= 'border-top-width:' . $setting . ';';
			} elseif ( 'borderRightWidth' == $key ) {
				$html .= 'border-right-width:' . $setting . ';';
			} elseif ( 'borderBottomWidth' == $key ) {
				$html .= 'border-bottom-width:' . $setting . ';';
			} elseif ( 'borderLeftWidth' == $key ) {
				$html .= 'border-left-width:' . $setting . ';';
			} elseif ( 'maxWidth' == $key ) {
				$html .= 'max-width:' . $setting . ';';
			} elseif ( 'borderColor' == $key ) {
				$html .= 'border-color:' . $setting . ';';
			} elseif ( 'borderTopLeftRadius' == $key ) {
				$html .= 'border-top-left-radius:' . $setting . ';';
			} elseif ( 'borderTopRightRadius' == $key ) {
				$html .= 'border-top-right-radius:' . $setting . ';';
			} elseif ( 'borderBottomLeftRadius' == $key ) {
				$html .= 'border-bottom-left-radius:' . $setting . ';';
			} elseif ( 'borderBottomRightRadius' == $key ) {
				$html .= 'border-bottom-right-width:' . $setting . ';';
			}
		} else {
			$html .= $key . ':' . $setting . ';';
		}
	}
	$html .= '}';
	echo alpha_escaped( $html );
}
if ( ! empty( $settings['show_caption_selector'] ) && '' != $settings['show_caption_selector'] && ! empty( $settings['caption_style_selector'] ) ) {
	echo alpha_escaped( $settings['selector'] . ' figcaption {' );
	if ( ! empty( $settings['caption_style_selector']['textAlign'] ) ) {
		echo 'text-align:' . $settings['caption_style_selector']['textAlign'] . ';';
	}
	if ( ! empty( $settings['caption_style_selector']['backgroundColor'] ) ) {
		echo 'background-color:' . $settings['caption_style_selector']['backgroundColor'] . ';';
	}
	if ( ! empty( $settings['caption_style_selector']['marginTop'] ) ) {
		echo 'margin-top:' . $settings['caption_style_selector']['marginTop'] . 'px;';
	}
	echo '}';
}
