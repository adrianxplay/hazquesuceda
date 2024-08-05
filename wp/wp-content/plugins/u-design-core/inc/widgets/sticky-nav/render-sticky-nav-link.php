<?php //@codingStandardsIgnoreLine
use Elementor\Icons_Manager;

$output = $label = $link = $icon = $skin = $link_color = $link_bg_color = $link_acolor = $link_abg_color = $el_class = ''; //@codingStandardsIgnoreLine
extract( //@codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'_id'             => '',
			'label'           => '',
			'tooltip'         => '',
			'link'            => '',
			'show_icon'       => false,
			'icon_type'       => 'fontawesome',
			'icon'            => '',
			'icon_image'      => '',
			'icon_simpleline' => '',
			'link_color'      => '',
			'link_bg_color'   => '',
			'link_acolor'     => '',
			'link_abg_color'  => '',
			'el_class'        => '',
		),
		$atts
	)
);


switch ( $icon_type ) {
	case 'simpleline':
		$icon_class = $icon_simpleline;
		break;
	case 'image':
		$icon_class = 'icon-image';
		break;
	default:
		$icon_class = $icon;
}

if ( ! $show_icon ) {
	$icon_class = '';
}

// if ( $label ) {

if ( ! empty( $_id ) ) {
	$el_class = trim( $el_class . ' ' . 'elementor-repeater-item-' . $_id );
}

	$output = '<li class="' . esc_attr( $el_class ) . '" title="' . esc_attr( $tooltip ) . '">';

if ( $link ) {
	$output .= '<a href="' . esc_url( $link ) . '">';
} else {
	$output .= '<span>';
}

if ( 'svg' == $atts['icon_type'] ) {
	Icons_Manager::render_icon( $atts['icon_cl'], [ 'aria-hidden' => 'true' ] );
} else {
	if ( $icon_class ) {
		$output .= '<i class="' . esc_attr( $icon_class ) . '">';
		if ( 'icon-image' == $icon_class && $icon_image ) {
			$icon_image = preg_replace( '/[^\d]/', '', $icon_image );
			$image_url  = wp_get_attachment_url( $icon_image );
			$image_url  = str_replace( array( 'http:', 'https:' ), '', $image_url );
			if ( $image_url ) {
				$alt_text = get_post_meta( $icon_image, '_wp_attachment_image_alt', true );
				$output  .= '<img alt="' . esc_attr( $alt_text ) . '" src="' . esc_url( $image_url ) . '">';
			}
		}
		$output .= '</i>';
	}
}

	$output .= $label;

if ( $link ) {
	$output .= '</a>';
} else {
	$output .= '</span>';
}

	$output .= '</li>';
// }

echo alpha_escaped( $output );
