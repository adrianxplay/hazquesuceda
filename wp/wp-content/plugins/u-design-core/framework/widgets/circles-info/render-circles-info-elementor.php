<?php
/**
 * Circles Info Shortcode Render
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Circle Info Items
			'items'          => array(),
			'title_html_tag' => 'h3',
			'link_anim'      => 'bounce',
			'active_on'      => 'mouseenter',
			'auto_rotate'    => 'yes',
			'rotate_time'    => 5,
			'pause'          => '',
		),
		$atts
	)
);

if ( count( $items ) ) {

	$wrapper_cls  = 'ci-wrapper';
	$icons_html   = '';
	$content_html = '';
	
	$wrapper_attrs = array(
		'animation' => $link_anim,
		'event'     => $active_on,
		'rotate'    => 'yes' == $auto_rotate ? true : false,
		'pause'     => 'yes' == $pause ? true : false,
		'delay'     => $rotate_time,
	);

	echo '<div class="' . esc_attr( $wrapper_cls ) . '" data-plugin-options="' . esc_attr( json_encode( $wrapper_attrs ) ) . '">';
	foreach ( $items as $key => $item ) {
		$icons_html .= '<div class="ci-icon-link" data-id="' . (int)( $key + 1 ) . '"><span>';
		if ( 'image' == $item['link_type'] ) {
			$icons_html .= Group_Control_Image_Size::get_attachment_image_html( $item, 'thumbnail', 'image' );
		} elseif ( 'icon' == $item['link_type'] ) {
			if ( 'svg' == $item['icon']['library'] ) {
				ob_start();
				Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
				$icons_html .= ob_get_clean();
			} else {
				$icons_html .= '<i class="' . esc_attr( $item['icon']['value'] ) . '"></i>';
			}
		} else {
			$html_key = $this->get_repeater_setting_key( 'html', 'items', $key );
			$this->add_inline_editing_attributes( $html_key );
			$icons_html .= '<span ' . $this->get_render_attribute_string( $html_key ) . '>' . alpha_strip_script_tags( $item['html'] ) . '</span>';
		}
		$icons_html .= '</span></div>';

		$content_html .= '<div class="ci-content" data-id="' . (int)( $key + 1 ) . '">';
		
		if ( ! empty( $item['title'] ) ) {
			$attrs = array();
			$attrs['href']   = ! empty( $item['link']['url'] ) ? esc_url( $item['link']['url'] ) : '#';
			$attrs['target'] = ! empty( $item['link']['is_external'] ) ? '_blank' : '';
			$attrs['rel']    = ! empty( $item['link']['nofollow'] ) ? 'nofollow' : '';
			if ( ! empty( $item['link']['custom_attributes'] ) ) {
				foreach ( explode( ',', $item['link']['custom_attributes'] ) as $attr ) {
					$attr   = explode( '|', $attr )[0];
					$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
					if ( isset( $attrs[ $attr ] ) ) {
						$attrs[ $attr ] .= ' ' . $value;
					} else {
						$attrs[ $attr ] = $value;
					}
				}
			}
			$link_attrs = '';
			foreach ( $attrs as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$link_attrs .= $attr . '="' . esc_attr( $value ) . '" ';
				}
			}

			$title_key = $this->get_repeater_setting_key( 'title', 'items', $key );
			$this->add_inline_editing_attributes( $title_key );
			$content_html .=  '<' . Utils::validate_html_tag( $title_html_tag ) . ' class="ci-title">' . ( $item['link']['url'] ? '<a ' . $link_attrs . '>' : '' ) . '<span ' . $this->get_render_attribute_string( $title_key ) . '>' . esc_html( $item['title'] ) . '</span>' . ( $item['link']['url'] ? '</a>' : '' )  . '</' . Utils::validate_html_tag( $title_html_tag ) . '>';
		}
		
		if ( ! empty( $item['description'] ) ) {
			$desc_key = $this->get_repeater_setting_key( 'description', 'items', $key );
			$this->add_inline_editing_attributes( $desc_key );
			$content_html .=  '<p class="ci-desc">' . '<span ' . $this->get_render_attribute_string( $desc_key ) . '>' . esc_html( $item['description'] ) . '</span>' . '</p>';
		}

		$content_html .= '</div>';
	}
	
	echo '<div class="ci-icons-wrapper">' .  alpha_strip_script_tags( $icons_html ) . '</div>';
	echo '<div class="ci-contents-wrapper">' . alpha_strip_script_tags( $content_html ) . '</div>';

	echo '</div>';
}
