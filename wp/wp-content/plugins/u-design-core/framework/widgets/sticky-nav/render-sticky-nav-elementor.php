<?php //@codingStandardsIgnoreLine
$result = $el_class = ''; //@codingStandardsIgnoreLine
extract( //@codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'container'            => false,
			'min_width'            => 991,
			'show_divider'         => '',
			'full_width'           => 'no',
			'sticky_nav_item_list' => '',
		),
		$atts
	)
);


if ( $show_divider ) {
	$el_class .= ' with-divider';
}

$options             = array();
$options['minWidth'] = (int) $min_width;
$options             = json_encode( $options );
$el_class           .= ' sticky-nav-container';


$result .= '<div class="sticky-content fix-top ' . esc_attr( $el_class ) . '"><div class="nav-secondary" data-plugin-options="' . esc_attr( $options ) . '">';

if ( $container ) {
	$result .= '<div class="container">';
}

$result .= '<ul class="nav sticky-navs">';

if ( ! empty( $sticky_nav_item_list ) ) {
	ob_start();
	foreach ( $sticky_nav_item_list as $key => $atts ) {
		if ( isset( $atts['link'] ) && isset( $atts['link']['url'] ) ) {
			$atts['link'] = $atts['link']['url'];
		}

		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/sticky-nav/render-sticky-nav-link.php' );
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
