<?php
/**
 * InfoBox Shortcode Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Icons_Manager;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'icon_position'        => 'top',
			'selected_icon'        => array( 'value' => 'fas fa-star' ),
			'title_text'           => esc_html__( 'This is the heading', 'alpha-core' ),
			'description_text'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpha-core' ),
			'show_button'          => '',
			'button_label'         => esc_html__( 'Read More', 'alpha-core' ),
			'link'                 => '',
			'info_box_icon_type'   => '',
			'info_box_icon_hover'  => '',
			'info_box_hover'       => '',
			'info_box_icon_shape'  => '',
			'info_box_icon_shadow' => '',
			'title_html_tag'       => 'h3',
		),
		$atts
	)
);

$wrapper_cls = array( 'icon-box' );

if ( 'top' != $icon_position ) {
	$wrapper_cls[] = 'icon-box-side';
}
$wrapper_cls[] = 'position-' . $icon_position;

$wrapper_cls[] = 'icon-' . $info_box_icon_type;
if ( 'yes' == $info_box_icon_shadow ) {
	$wrapper_cls[] = 'icon-box-icon-shadow';
}
if ( $info_box_icon_shape ) {
	$wrapper_cls[] = 'shape-' . $info_box_icon_shape;
}

if ( 'default' != $info_box_icon_type ) {
	if ( $info_box_icon_hover ) {
		$wrapper_cls[] = 'hover-overlay';
		$wrapper_cls[] = 'hover-' . $info_box_icon_type;
	}
}
if ( $info_box_hover ) {
	$wrapper_cls[] = $info_box_hover;
}

$link_attr_ary           = [];
$link_attr_ary['href']   = ! empty( $link['url'] ) ? $link['url'] : '#';
$link_attr_ary['target'] = ! empty( $link['is_external'] ) ? '_blank' : '';
$link_attr_ary['rel']    = ! empty( $link['nofollow'] ) ? 'nofollow' : '';
if ( ! empty( $link['custom_attributes'] ) ) {
	foreach ( explode( ',', $link['custom_attributes'] ) as $attr ) {
		$key   = explode( '|', $attr )[0];
		$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
		if ( isset( $link_attr_ary[ $key ] ) ) {
			$link_attr_ary[ $key ] .= ' ' . $value;
		} else {
			$link_attr_ary[ $key ] = $value;
		}
	}
}
$link_attr = '';
foreach ( $link_attr_ary as $key => $value ) {
	if ( ! empty( $value ) ) {
		$link_attr .= $key . '="' . esc_attr( $value ) . '" ';
	}
}

$link_open  = empty( $link['url'] ) ? '' : '<a class="link" ' . $link_attr . '>';
$link_close = empty( $link['url'] ) ? '' : '</a>';

echo '<div class="' . esc_attr( implode( ' ', $wrapper_cls ) ) . '">';

if ( $link['url'] ) {
	echo alpha_escaped( $link_open . $link_close );
}
	echo '<div class="icon-box-feature">';

if ( 'svg' == $selected_icon['library'] ) {
	Icons_Manager::render_icon( $selected_icon, array( 'aria-hidden' => 'true' ) );
} else {
	echo '<i class="' . esc_attr( $selected_icon['value'] ) . '"></i>';
}

	echo '</div>';

	echo '<div class="icon-box-content">';

if ( $title_text ) {
	$this->add_render_attribute( 'title_text', 'class', 'icon-box-title' );
	echo '<' . $title_html_tag . ' ' . $this->get_render_attribute_string( 'title_text' ) . '>' . $link_open . alpha_strip_script_tags( $title_text ) . $link_close . '</' . $title_html_tag . '>';
}
if ( $description_text ) {
	$this->add_render_attribute( 'description_text', 'class', 'icon-box-desc' );
	echo '<p ' . $this->get_render_attribute_string( 'description_text' ) . '>' . alpha_strip_script_tags( $description_text ) . '</p>';
}
if ( 'yes' == $show_button && $button_label ) {

	$button_label = alpha_widget_button_get_label( $atts, $this, $button_label, 'button_label' );
	$class[]      = 'btn';
	$class[]      = implode( ' ', alpha_widget_button_get_class( $atts ) );

	$this->add_inline_editing_attributes( 'button_label' );

	echo sprintf( '<a class="' . esc_attr( implode( ' ', $class ) ) . '" href="' . ( empty( $link['url'] ) ? '#' : esc_url( $link['url'] ) ) . '" ' . ( ! empty( $link['is_external'] ) ? ' target="nofollow"' : '' ) . ( ! empty( $link['nofollow'] ) ? ' rel="_blank"' : '' ) . '>%1$s</a>', alpha_strip_script_tags( $button_label ) );
}

	echo '</div>';


echo '</div>';
