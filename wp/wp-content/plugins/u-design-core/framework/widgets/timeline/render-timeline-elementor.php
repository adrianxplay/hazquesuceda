<?php
/**
 * The timeline widget render.
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Group_Control_Image_Size;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'timeline_list'  => array(),
			'h_align'        => 'center',
			'v_align'        => 'middle',
			'content_shadow' => '',
		),
		$atts
	)
);

$html = '';

$wrapper_class = 'timeline timeline-vertical';

if ( $content_shadow ) {
	$wrapper_class .= ' timeline-with-shadow';
}
$wrapper_class .= ' timeline-h-align-' . $h_align;
$wrapper_class .= ' timeline-v-align-' . $v_align;

$html .= '<div class="' . esc_attr( $wrapper_class ) . '">';
$html .= '<div class="timeline-line"><div class="timeline-progress"></div></div>';

$html .= '<div class="timeline-list">';
foreach ( $timeline_list as $key => $list ) {
	$img         = '';
	$content_img = '';
	$pos         = -1;
	if ( 'yes' == $list['show_image'] ) {
		$pos          = $list['image_position'];
		$img          = Group_Control_Image_Size::get_attachment_image_html( $list, 'thumbnail', 'image' );
		$content_img  = '<figure class="timeline-media' . ( 'center' == $h_align && '' === $pos ? ' timeline-lg-none' : '' ) . '">' . $img;
		$img          = '<figure class="timeline-media">' . $img;
		$content_img .= '</figure>';
		$img         .= '</figure>';
	} elseif ( 'yes' == $list['show_icon'] ) {
		$pos = $list['icon_position'];
		if ( isset( $list['timeline_icon']['library'] ) && 'svg' == $list['timeline_icon']['library'] ) {
			ob_start();
			\ELEMENTOR\Icons_Manager::render_icon(
				array(
					'library' => 'svg',
					'value'   => array( 'id' => absint( isset( $list['timeline_icon']['value']['id'] ) ? $list['timeline_icon']['value']['id'] : 0 ) ),
				),
				array( 'aria-hidden' => 'true' )
			);
			$img .= ob_get_clean();
		} else {
			$img .= '<i class="' . esc_attr( $list['timeline_icon']['value'] ) . '"></i>';
		}

		$content_img  = '<div class="timeline-media' . ( 'center' == $h_align && '' === $pos ? ' timeline-lg-none' : '' ) . '">' . $img;
		$img          = '<div class="timeline-media">' . $img;
		$content_img .= '</div>';
		$img         .= '</div>';
	}

	$meta_wrap = '<div class="timeline-meta-wrap">';

	$repeater_setting_key = $this->get_repeater_setting_key( 'meta', 'timeline_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-meta' );
	$this->add_inline_editing_attributes( $repeater_setting_key );

	$meta = '<div ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>';
	if ( 'center' == $h_align && ( ! $img || '' !== $pos ) ) {
		$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-lg-none' );
	}
	$content_meta = '<div ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>';

	$meta_html = alpha_strip_script_tags( $list['meta'] );

	$meta         .= $meta_html . '</div>';
	$content_meta .= $meta_html . '</div>';

	if ( 'center' == $h_align && '' === $pos ) {
		$meta_wrap .= $img;
	} else {
		$meta_wrap .= $meta;
	}

	$meta_wrap .= '</div>';

	$content = '<div class="timeline-content">';

	if ( ( 'center' != $h_align && ( '' === $pos || 'before_title' == $pos ) ) || ( 'center' == $h_align && 'before_title' == $pos ) ) {
		$content .= $img;
	}

	if ( 'center' == $h_align && '' === $pos ) {
		$content .= $content_img;
	}

	$repeater_setting_key = $this->get_repeater_setting_key( 'timeline_item_title', 'timeline_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-title' );
	$this->add_inline_editing_attributes( $repeater_setting_key );
	$content .= '<h4 ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $list['timeline_item_title'] ) . '</h4>';

	$content .= $content_meta;

	$repeater_setting_key = $this->get_repeater_setting_key( 'desc', 'timeline_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-desc' );
	$this->add_inline_editing_attributes( $repeater_setting_key );
	$content .= '<p ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $list['desc'] ) . '</p>';

	if ( 'after_desc' == $pos ) {
		$content .= $img;
	}

	$content .= '</div>';

	$point = '<div class="timeline-point">';
	if ( 'icon' == $list['breakpoint_type'] ) {
		if ( isset( $list['breakpoint_icon']['library'] ) && 'svg' == $list['breakpoint_icon']['library'] ) {
			ob_start();
			\ELEMENTOR\Icons_Manager::render_icon(
				array(
					'library' => 'svg',
					'value'   => array( 'id' => absint( isset( $list['breakpoint_icon']['value']['id'] ) ? $list['breakpoint_icon']['value']['id'] : 0 ) ),
				),
				array( 'aria-hidden' => 'true' )
			);
			$point .= ob_get_clean();
		} else {
			$point .= '<i class="' . esc_attr( $list['breakpoint_icon']['value'] ) . '"></i>';
		}
	} else {
		$point .= alpha_strip_script_tags( $list['breakpoint_text'] );
	}
	$point .= '</div>';

	$html .= '<div class="timeline-item">';
	$html .= $content;
	$html .= $point;
	if ( 'center' == $h_align ) {
		$html .= $meta_wrap;
	}
	$html .= '</div>';
}
$html .= '</div>';
$html .= '</div>';
echo alpha_escaped( $html );
