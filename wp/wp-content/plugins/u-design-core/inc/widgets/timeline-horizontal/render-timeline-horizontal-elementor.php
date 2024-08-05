<?php
/**
 * The timeline widget render.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'timeline_list'        => array(),
			'layout'               => '',
			'h_align'              => 'center',
			'v_align'              => 'middle',
			'content_shadow'       => '',
			'timeline_custom_line' => '',
			'custom_line'          => '',
		),
		$atts
	)
);

$html = '';

$wrapper_class    = 'timeline timeline-horizontal';
$grid_space_class = alpha_get_grid_space_class( $atts );
if ( $grid_space_class ) {
	$wrapper_class .= ' ' . $grid_space_class;
}

if ( $content_shadow ) {
	$wrapper_class .= ' timeline-with-shadow';
}
$wrapper_class .= ' timeline-h-align-' . $h_align;
$wrapper_class .= ' timeline-v-align-' . $v_align;

$html .= '<div class="' . esc_attr( $wrapper_class ) . '">';

$html .= '<div class="timeline-list">';

$top    = '';
$bottom = '';
$point  = '';

$idx = 0;
foreach ( $timeline_list as $key => $list ) {
	$img = '';
	$pos = -1;
	if ( 'yes' == $list['show_image'] ) {
		$img  = '<figure class="timeline-media">';
		$img .= Group_Control_Image_Size::get_attachment_image_html( $list, 'thumbnail', 'image' );
		$img .= '</figure>';
		$pos  = $list['image_position'];
	} elseif ( 'yes' == $list['show_icon'] ) {
		$img = '<div class="timeline-media">';
		if ( 'svg' == $list['timeline_icon']['library'] ) {
			ob_start();
			Icons_Manager::render_icon( $list['timeline_icon'], [ 'aria-hidden' => 'true' ] );
			$img .= ob_get_clean();
		} else {
			$img .= '<i class="' . esc_attr( $list['timeline_icon']['value'] ) . '"></i>';
		}
		$img .= '</div>';
		$pos  = $list['icon_position'];
	}

	$active_class = '';
	if ( 'yes' == $list['active_item'] ) {
		$active_class = ' active';
	}

	$meta_wrap = '<div class="timeline-meta-wrap' . $active_class . '">';

	$repeater_setting_key = $this->get_repeater_setting_key( 'meta', 'timeline_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-meta' );
	$this->add_inline_editing_attributes( $repeater_setting_key );
	$meta  = '<div ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>';
	$meta .= alpha_strip_script_tags( $list['meta'] );
	$meta .= '</div>';

	if ( '' === $pos ) {
		$meta_wrap .= $img;
	} else {
		$meta_wrap .= $meta;
	}

	$meta_wrap .= '</div>';

	$content = '<div class="timeline-content' . $active_class . '">';

	if ( 'before_title' == $pos ) {
		$content .= $img;
	}

	$repeater_setting_key = $this->get_repeater_setting_key( 'title', 'timeline_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-title' );
	$this->add_inline_editing_attributes( $repeater_setting_key );
	$content .= '<h4 ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $list['title'] ) . '</h4>';

	if ( '' === $pos ) {
		$content .= $meta;
	}

	$repeater_setting_key = $this->get_repeater_setting_key( 'desc', 'timeline_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'timeline-desc' );
	$this->add_inline_editing_attributes( $repeater_setting_key );
	$content .= '<p ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $list['desc'] ) . '</p>';

	if ( 'after_desc' == $pos ) {
		$content .= $img;
	}

	$content .= '</div>';

	$point .= '<div class="timeline-point-wrap' . $active_class . '"><div class="timeline-point">';
	if ( 'icon' == $list['breakpoint_type'] ) {
		if ( 'svg' == $list['breakpoint_icon']['library'] ) {
			ob_start();
			Icons_Manager::render_icon( $list['breakpoint_icon'], [ 'aria-hidden' => 'true' ] );
			$point .= ob_get_clean();
		} else {
			$point .= '<i class="' . esc_attr( $list['breakpoint_icon']['value'] ) . '"></i>';
		}
	} else {
		$point .= alpha_strip_script_tags( $list['breakpoint_text'] );
	}
	$point .= '</div>';
	if ( 'yes' == $timeline_custom_line && $idx < count( $timeline_list ) - 1 ) {
		$point .= wp_get_attachment_image( $custom_line['id'], 'full' );
	}

	$point .= '</div>';

	if ( 'top' == $v_align ) {
		$top    .= $content;
		$bottom .= $meta_wrap;
	} elseif ( 'middle' == $v_align ) {
		if ( $idx % 2 == 0 ) {
			$top    .= $content;
			$bottom .= $meta_wrap;
		} else {
			$top    .= $meta_wrap;
			$bottom .= $content;
		}
	} else {
		$top    .= $meta_wrap;
		$bottom .= $content;
	}

	$idx ++;
}

$line = 'yes' == $timeline_custom_line ? '' : '<div class="timeline-line"><div class="timeline-progress"></div></div>';

$extra_class  = '';
$col_cnt      = alpha_elementor_grid_col_cnt( $atts );
$extra_class .= alpha_get_col_class( $col_cnt );
$extra_class .= $grid_space_class;

$html .= '<div class="timeline-item timeline-item-top ' . esc_attr( $extra_class ) . '">' . $top . '</div>';
$html .= '<div class="timeline-item timeline-item-middle ' . esc_attr( $extra_class ) . '">' . $line . $point . '</div>';
$html .= '<div class="timeline-item timeline-item-bottom ' . esc_attr( $extra_class ) . '">' . $bottom . '</div>';

$html .= '</div>';

$html .= '</div>';

echo alpha_strip_script_tags( $html );
