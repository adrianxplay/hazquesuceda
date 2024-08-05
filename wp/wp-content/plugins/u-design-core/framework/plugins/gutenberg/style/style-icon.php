<?php
if ( ! empty( $settings['st_fs'] ) || ! empty( $settings['st_pd'] ) || ! empty( $settings['st_icon_clr'] ) ) {
	echo esc_html( $selector ) . '{';
	if ( ! empty( $settings['st_fs'] ) ) {
		echo 'font-size:' . esc_html( $settings['st_fs'] ) . 'px;';
	}
	if ( ! empty( $settings['st_pd'] ) ) {
		echo 'padding:' . esc_html( $settings['st_pd'] ) . 'px;';
	}
	if ( ! empty( $settings['st_icon_clr'] ) ) {
		echo 'color:' . esc_html( $settings['st_icon_clr'] ) . ';';
	}
	echo '}';
}
if ( ! empty( $settings['st_icon_clr_hover'] ) ) {
	echo esc_html( $selector ) . ':hover,';
	echo esc_html( $selector ) . ':focus{';
	echo 'color:' . esc_html( $settings['st_icon_clr_hover'] ) . ';';
	echo '}';
}
