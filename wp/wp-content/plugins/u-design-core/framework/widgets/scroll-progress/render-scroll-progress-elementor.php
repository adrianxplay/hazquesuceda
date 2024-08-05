<?php
$output = '';
extract(
	shortcode_atts(
		array(
			'type'      => '',
			'position'  => '',
			'position1' => '',
			'icon_cls'  => '',
		),
		$atts
	)
);

if ( 'circle' === $type ) {
	$cls = 'scroll-progress scroll-progress-circle';
	if ( $position1 ) {
		$cls .= ' pos-' . $position1;
	}

	$output         .= '<a class="' . esc_attr( $cls ) . '" href="#" role="button">';
		$output     .= '<i class="' . esc_attr( $icon_cls['value'] ) . '"></i>';
		$output     .= '<svg  version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">';
			$output .= '<circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34"/>';
		$output     .= '</svg>';
	$output         .= '</a><style>#scroll-top{display:none !important}</style>';
} else {
	$cls = 'scroll-progress';
	if ( $position ) {
		$cls .= ' fixed-' . $position;
		if ( 'under-header' === $position ) {
			$cls .= ' fixed-top';
		}
	}
	$output .= '<progress class="' . esc_attr( $cls ) . '" max="100">';
	$output .= '</progress>';
}

echo alpha_escaped( $output );
