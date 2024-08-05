<?php

if ( ! empty( $atts['st_icon_fs'] ) || ( isset( $atts['st_icon_spacing'] ) && ( $atts['st_icon_spacing'] || '0' == $atts['st_icon_spacing'] ) ) ) {

	echo alpha_escaped( $atts['selector'] ) . '{';

	if ( ! empty( $atts['st_icon_fs'] ) ) {
		echo 'font-size:' . esc_html( $atts['st_icon_fs'] ) . ';';
	}

	if ( isset( $atts['st_icon_spacing'] ) && ( $atts['st_icon_spacing'] || '0' == $atts['st_icon_spacing'] ) ) {
		$pos = empty( $atts['icon_pos'] ) ? 'right' : 'left';
		if ( is_rtl() ) {
			$pos = empty( $atts['icon_pos'] ) ? 'left' : 'right';
		}

		echo 'margin-' . $pos . ':' . intval( $atts['st_icon_spacing'] ) . 'px';
	}

	echo '}';
}
