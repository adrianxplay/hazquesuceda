<?php
if ( ! empty( $settings['decoration_spacing_selector'] ) ) {
	echo $selector . '.title-cross .title:after{ margin-left: ' . esc_html( $settings['decoration_spacing_selector'] ) . '; }' . $selector . ' .title-cross .title:before{ margin-right:' . $settings['decoration_spacing_selector'] . '; }';
}

if ( ! empty( $settings['border_color_selector'] ) ) {
	echo $selector . '.title-cross .title:before,' . $selector . '.title-cross .title:after{ background-color: ' . esc_html( $settings['border_color_selector'] ) . '; }';
}
