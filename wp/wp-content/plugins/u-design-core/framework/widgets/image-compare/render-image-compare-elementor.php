<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Image Compare Widget Render
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'handle_type'    => '',
			'direction'      => '',
			'show_label'     => 'yes',
			'labels_pos'     => 'center',
			'text_before'    => __( 'Before', 'alpha-core' ),
			'text_after'     => __( 'After', 'alpha-core' ),
			'image_before'   => '',
			'image_after'    => '',
			'image_size'     => '',
			'handle_control' => 'drag_click',
			'handle_offset'  => '',
		),
		$atts
	)
);

$args = array(
	'before_label' => esc_html( $text_before ),
	'after_label'  => esc_html( $text_after ),
	'orientation'  => 'vertical' == $direction ? 'vertical' : 'horizontal',
	'no_overlay'   => 'yes' == $show_label ? 0 : 1,
);

if ( 'drag_click' == $handle_control ) {
	$args['click_to_move'] = true;
} elseif ( 'drag' == $handle_control ) {
	$args['move_with_handle_only'] = true;
	$args['click_to_move']         = false;
} else {
	$args['move_slider_on_hover'] = true;
	$args['click_to_move']        = false;
}

if ( '' !== $handle_offset['size'] ) {
	$args['default_offset_pct'] = $handle_offset['size'] / 100;
}

$extra_cls = '';
if ( $image_before['url'] ) {
	$extra_cls .= ' icomp-image-before';
}
if ( $image_after['url'] ) {
	$extra_cls .= ' icomp-image-after';
}

echo '<div class="icomp-container' . esc_attr( $extra_cls . ' icomp-' . $direction ) . ' icomp-' . esc_attr( ( 'circle' == $handle_type || 'rect' == $handle_type ) ? ( $handle_type . ' icomp-arrow icomp-has-bg' ) : $handle_type ) . ' icomp-labels-' . esc_attr( $labels_pos ) . '" data-icomp-options="' . esc_attr( json_encode( $args ) ) . '">';

if ( $image_before['url'] ) {
	if ( $image_before['id'] ) {
		echo wp_get_attachment_image( (int) $image_before['id'], $image_size );
	} else {
		echo '<img src="' . esc_url( $image_before['url'] ) . '" alt="' . esc_html__( 'Before Image', 'alpha-core' ) . '">';
	}
}
if ( $image_after['url'] ) {
	if ( $image_after['id'] ) {
		echo wp_get_attachment_image( (int) $image_after['id'], $image_size );
	} else {
		echo '<img src="' . esc_url( $image_after['url'] ) . '" alt="' . esc_html__( 'After Image', 'alpha-core' ) . '">';
	}
}

if ( ! $image_before['url'] && ! $image_after['url'] ) {
	echo '<img class="default-img" src="' . esc_url( alpha_get_placeholder_img() ) . '" alt="' . esc_html__( 'Default Image', 'alpha-core' ) . '">';
}

echo '</div>';
