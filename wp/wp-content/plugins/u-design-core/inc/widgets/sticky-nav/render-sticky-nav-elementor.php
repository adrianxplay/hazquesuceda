<?php //@codingStandardsIgnoreLine
$result = $container = $min_width = $bg_color = $skin = $link_color = $link_bg_color = $link_acolor = $link_abg_color = $animation_type = $animation_duration = $animation_delay = $el_class = ''; //@codingStandardsIgnoreLine
extract( //@codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'container'            => false,
			'min_width'            => 991,
			'show_divider'         => '',
			'full_width'           => 'no',
			'bg_color'             => '',
			'link_color'           => '',
			'link_bg_color'        => '',
			'link_acolor'          => '',
			'link_abg_color'       => '',
			'animation_type'       => '',
			'animation_duration'   => 1000,
			'animation_delay'      => 0,
			'sticky_nav_item_list' => '',
			'el_class'             => '',
		),
		$atts
	)
);

$style = '';
if ( $bg_color ) {
	$style = 'background-color:' . esc_attr( $bg_color ) . ';';
}

if ( 'yes' == $full_width ) {
	$sc_class_escaped = 'alpha-sticky-nav' . rand();
	$el_class        .= ' ' . $sc_class_escaped;
	?>
	<style>
		.<?php echo alpha_escaped( $sc_class_escaped ); ?>.sticky-content.fixed { width: 100%; left: 0 !important; right: 0; }
	</style>
	<?php
}

if ( $show_divider ) {
	$el_class .= ' with-divider';
}

$options             = array();
$options['minWidth'] = (int) $min_width;
$options             = json_encode( $options );
$el_class           .= ' sticky-nav-container';


$result .= '<div class="sticky-content fix-top ' . esc_attr( $el_class ) . '"><div class="nav-secondary" data-plugin-options="' . esc_attr( $options ) . '"';
if ( $style ) {
	$result .= ' style="' . $style . '"';
}
if ( $animation_type ) {
	$result .= ' data-appear-animation="' . esc_attr( $animation_type ) . '"';
	if ( $animation_delay ) {
		$result .= ' data-appear-animation-delay="' . esc_attr( $animation_delay ) . '"';
	}
	if ( $animation_duration && 1000 != $animation_duration ) {
		$result .= ' data-appear-animation-duration="' . esc_attr( $animation_duration ) . '"';
	}
}
	$result .= '>';

if ( $container ) {
	$result .= '<div class="container">';
}

	$result .= '<ul class="nav sticky-navs">';

if ( ! empty( $sticky_nav_item_list ) ) {
	ob_start();
	foreach ( $sticky_nav_item_list as $key => $atts ) {
		if ( is_array( $atts['icon_image'] ) && ! empty( $atts['icon_image']['id'] ) ) {
			$atts['icon_image'] = (int) $atts['icon_image']['id'];
		}
		if ( isset( $atts['icon_cl'] ) && isset( $atts['icon_cl']['value'] ) ) {
			if ( isset( $atts['icon_cl']['library'] ) && isset( $atts['icon_cl']['value']['id'] ) ) {
				$atts['icon_type'] = $atts['icon_cl']['library'];
				$atts['icon']      = $atts['icon_cl']['value']['id'];
			} else {
				$atts['icon'] = $atts['icon_cl']['value'];
			}
		}
		if ( isset( $atts['link'] ) && isset( $atts['link']['url'] ) ) {
			$atts['link'] = $atts['link']['url'];
		}

		require ALPHA_CORE_INC . '/widgets/sticky-nav/render-sticky-nav-link.php';
	}
	$result .= ob_get_clean();
} elseif ( ! empty( $content ) ) {
	$result .= do_shortcode( $content );
}

	$result .= '</ul>';

if ( $container ) {
	$result .= '</div>';
}

$result .= '</div></div>';

echo alpha_escaped( $result );
