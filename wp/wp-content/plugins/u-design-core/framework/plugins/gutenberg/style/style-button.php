<?php
if ( ! empty( $settings['button_align_selector'] ) ) {
	echo $selector . '{ text-align:' . esc_html( $settings['button_align_selector'] ) . '; }';
}
if ( isset( $settings['icon_space_selector'] ) ) {
	echo $selector . '.btn-wrapper i{ margin-' . ( is_rtl() ? 'right' : 'left' ) . ':' . esc_html( $settings['icon_space_selector'] ) . 'px; }';
}
if ( ! empty( $settings['icon_size_selector'] ) ) {
	echo $selector . ' i{ font-size:' . esc_html( $settings['icon_size_selector'] ) . '; }';
}
