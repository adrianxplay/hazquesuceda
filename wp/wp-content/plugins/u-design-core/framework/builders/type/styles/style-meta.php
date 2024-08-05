<?php

if ( ! empty( $atts['st_icon_fs'] ) || ( isset( $atts['spacing'] ) && ( $atts['spacing'] || '0' == $atts['spacing'] ) ) ) {

	echo alpha_escaped( $atts['selector'] ) . '{';

	if ( ! empty( $atts['st_icon_fs'] ) ) {
		echo 'font-size:' . esc_html( $atts['st_icon_fs'] ) . ';';
	}

	if ( isset( $atts['spacing'] ) && ( $atts['spacing'] || '0' == $atts['spacing'] ) ) {
		$pos = empty( $atts['icon_pos'] ) ? 'right' : 'left';
		if ( is_rtl() ) {
			$pos = empty( $atts['icon_pos'] ) ? 'left' : 'right';
		}

		echo 'margin-' . $pos . ':' . intval( $atts['spacing'] ) . 'px';
	}

	echo '}';
}
