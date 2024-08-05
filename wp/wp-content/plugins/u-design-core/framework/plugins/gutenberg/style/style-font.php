<?php

if ( $settings ) {
	echo alpha_escaped( $settings['selector'] ) . '{';
	if ( ! empty( $settings['fontFamily'] ) ) {
		echo 'font-family:' . esc_html( $settings['fontFamily'] ) . ';';
	}
	if ( ! empty( $settings['fontSize'] ) ) {
		$unit = trim( preg_replace( '/[0-9.]/', '', $settings['fontSize'] ) );
		if ( ! $unit ) {
			$settings['fontSize'] .= 'px';
		}
		echo 'font-size:' . esc_html( $settings['fontSize'] ) . ';';
		echo '--alpha-title-size:' . esc_html( $settings['fontSize'] ) . ';';
	}
	if ( ! empty( $settings['fontWeight'] ) ) {
		echo 'font-weight:' . esc_html( $settings['fontWeight'] ) . ';';
	}
	if ( ! empty( $settings['textTransform'] ) ) {
		echo 'text-transform:' . esc_html( $settings['textTransform'] ) . ';';
	}
	if ( ! empty( $settings['lineHeight'] ) ) {
		$unit = trim( preg_replace( '/[0-9.]/', '', $settings['lineHeight'] ) );
		if ( ! $unit && (int) $settings['lineHeight'] > 3 ) {
			$settings['lineHeight'] .= 'px';
		}
		echo 'line-height:' . esc_attr( $settings['lineHeight'] ) . ';';
	}
	if ( ! empty( $settings['letterSpacing'] ) ) {
		$unit = trim( preg_replace( '/[0-9.-]/', '', $settings['letterSpacing'] ) );
		if ( ! $unit ) {
			$settings['letterSpacing'] .= 'px';
		}
		echo 'letter-spacing:' . esc_html( $settings['letterSpacing'] ) . ';';
	}
	if ( ! empty( $settings['alignment'] ) ) {
		echo 'text-align:' . esc_html( $settings['alignment'] ) . ';';
	}
	if ( ! empty( $settings['color'] ) ) {
		echo 'color:' . esc_html( $settings['color'] );
	}
	echo '}';

	if ( ! empty( $settings['h_color'] ) ) {
		echo alpha_escaped( $settings['selector'] ) . ':hover,' . alpha_escaped( $settings['selector'] ) . ' a:hover{';
		echo 'color:' . esc_html( $settings['h_color'] );
		echo '}';
	}
}
