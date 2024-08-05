<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Heading Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Icons_Manager;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'dynamic_content'      => 'title',
			'title'                => '',
			'link_dynamic_content' => '',
			'tag'                  => 'h2',
			'decoration'           => '',
			'show_link'            => '',
			'link_url'             => '',
			'link_label'           => '',
			'title_align'          => '',
			'title_align_tablet'   => '',
			'title_align_mobile'   => '',
			'link_align'           => '',
			'icon_pos'             => 'after',
			'icon'                 => '',
			'show_divider'         => '',
			'wrap_class'           => '',

			// For elementor inline editing
			'self'                 => '',
		),
		$atts
	)
);

$html = '';

$class = ! empty( $class ) ? $class . ' title elementor-heading-title' : 'title elementor-heading-title';

if ( $decoration ) {
	$wrap_class .= ' title-' . $decoration;
}

if ( $title_align ) {
	$wrap_class .= ' ' . $title_align;
}

if ( $title_align_tablet ) {
	$wrap_class .= ' ' . str_replace( '-', '-lg-', $title_align_tablet );
}

if ( $title_align_mobile ) {
	$wrap_class .= ' ' . str_replace( '-', '-md-', $title_align_mobile );
}

if ( $link_align ) {
	$wrap_class .= ' ' . $link_align;
}


$link_label = '<span ' . ( $self ? $self->get_render_attribute_string( 'link_label' ) : '' ) . '>' . alpha_strip_script_tags( $link_label ) . '</span>';

if ( is_array( $icon ) && $icon['value'] ) {
	if ( isset( $atts['icon']['library'] ) && 'svg' == $atts['icon']['library'] ) {
		ob_start();
		Icons_Manager::render_icon( $atts['icon'], array( 'aria-hidden' => 'true' ) );
		$icon = ob_get_clean();
	} else {
		$icon = '<i class="' . esc_attr( $icon['value'] ) . '"></i>';
	}

	if ( 'before' == $icon_pos ) {
		$wrap_class .= ' icon-before';
		$link_label  = $icon . $link_label;
	} else {
		$wrap_class .= ' icon-after';
		$link_label .= $icon;
	}
}

$html .= '<div class="title-wrapper ' . esc_attr( $wrap_class ) . '">';

if ( $self ) {
	$self->add_render_attribute( 'title', 'class', $class );
}

if ( $title ) {
	// dynamic content
	if ( ! empty( $atts['text_source'] ) && isset( $atts['dynamic_content'] ) && ! empty( $atts['dynamic_content']['source'] ) ) {
		$title = apply_filters( 'alpha_dynamic_tags_content', '', null, $atts['dynamic_content'] );
	}
	if ( $self ) {
		$html .= sprintf( '<%1$s ' . $self->get_render_attribute_string( 'title' ) . '>%2$s</%1$s>', esc_html( $tag ), alpha_strip_script_tags( $title ) );
	} else {
		$heading_link = '';
		if ( ! empty( $atts['add_link'] ) && ! empty( $link_dynamic_content ) && ! empty( $link_dynamic_content['source'] ) ) {
			$heading_link = apply_filters( 'alpha_dynamic_tags_content', '', null, $link_dynamic_content );
		}

		if ( $heading_link ) {
			$show_link = false;
			$html     .= sprintf( '<a class="w-100" href="%1$s"><%2$s class="' . $class . '">%3$s</%2$s></a>', esc_url( $heading_link ), esc_html( $tag ), alpha_strip_script_tags( $title ) );
		} else {
			$html .= sprintf( '<%1$s class="' . $class . '">%2$s</%1$s>', esc_html( $tag ), alpha_strip_script_tags( $title ) );
		}
	}
}

if ( 'yes' == $show_link ) { // If Link is allowed
	if ( 'yes' == $show_divider ) {
		$html .= '<span class="divider"></span>';
	}
	$attrs           = array();
	$attrs['href']   = ! empty( $link_url['url'] ) ? esc_url( $link_url['url'] ) : '#';
	$attrs['target'] = ! empty( $link_url['is_external'] ) ? '_blank' : '';
	$attrs['rel']    = ! empty( $link_url['nofollow'] ) ? 'nofollow' : '';
	if ( ! empty( $link_url['custom_attributes'] ) ) {
		foreach ( explode( ',', $link_url['custom_attributes'] ) as $attr ) {
			$key   = explode( '|', $attr )[0];
			$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
			if ( isset( $attrs[ $key ] ) ) {
				$attrs[ $key ] .= ' ' . $value;
			} else {
				$attrs[ $key ] = $value;
			}
		}
	}
	$link_attrs = '';
	foreach ( $attrs as $key => $value ) {
		if ( ! empty( $value ) ) {
			$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
		}
	}
	if ( $self ) {
		$html .= sprintf( '<a class="link" %1$s>%2$s</a>', $link_attrs, $link_label );
	} else {
		$html .= sprintf( '<a class="link" href="%1$s">%2$s</a>', esc_url( $link_url ), $link_label );
	}
}
$html .= '</div>';

echo do_shortcode( $html );
