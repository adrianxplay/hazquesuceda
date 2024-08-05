<?php

if ( ! empty( $atts['st_icon_fs'] ) || ! empty( $atts['st_spacing'] ) ) {

    echo alpha_escaped( $atts['selector'] ) . '{';

    if ( ! empty( $atts['st_icon_fs'] ) ) {
        echo 'font-size:' . esc_html( $atts['st_icon_fs'] ) . ';';
    }

    if ( ! empty( $atts['st_spacing'] ) ) {
        $pos = empty( $atts['icon_pos'] ) ? 'right' : 'left';
        if ( is_rtl() ) {
            $pos = empty( $atts['icon_pos'] ) ? 'left' : 'right';
        }

        echo 'margin-' . $pos . ':' . esc_html( $atts['st_spacing'] );
    }

    echo '}';
}
