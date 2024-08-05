<?php //@codingStandardsIgnoreLine
use Elementor\Icons_Manager;

$output = $el_class = ''; //@codingStandardsIgnoreLine
extract( //@codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'_id'        => '',
			'label'      => '',
			'tooltip'    => '',
			'link'       => '',
			'show_icon'  => false,
			'icon_type'  => 'icon',
			'icon_image' => array( 'url' => '' ),
			'icon_cl'    => '',
		),
		$atts
	)
);


if ( ! empty( $_id ) ) {
	$el_class = trim( $el_class . ' ' . 'elementor-repeater-item-' . $_id );
}

$output = '<li class="' . esc_attr( $el_class ) . '" title="' . esc_attr( $tooltip ) . '">';

if ( $link ) {
	$output .= '<a href="' . esc_url( $link ) . '">';
} else {
	$output .= '<span>';
}

if ( $show_icon ) {
	if ( 'image' == $icon_type ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$output .= Elementor\Group_Control_Image_Size::get_attachment_image_html( $atts, 'icon_image' );
		}
	} else {
		if ( isset( $atts['icon_cl']['library'] ) && 'svg' == $atts['icon_cl']['library'] ) {
			ob_start();
			\ELEMENTOR\Icons_Manager::render_icon(
				array(
					'library' => 'svg',
					'value'   => array( 'id' => absint( isset( $atts['icon_cl']['value']['id'] ) ? $atts['icon_cl']['value']['id'] : 0 ) ),
				),
				array( 'aria-hidden' => 'true' )
			);
			$output .= ob_get_clean();
		} else {
			$output .= '<i class="' . esc_attr( $atts['icon_cl']['value'] ) . '"></i>';
		}
	}
}

	$output .= $label;

if ( $link ) {
	$output .= '</a>';
} else {
	$output .= '</span>';
}

$output .= '</li>';

echo alpha_escaped( $output );
