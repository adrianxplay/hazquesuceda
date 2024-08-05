<?php
/**
 * Text Marquee Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.9
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'marquee_type'    => 'text',
			'text_content'    => '',
			'marquee_images'  => '',
			'content_repeat'  => '4',
			'marquee_layout'  => 'horizontal',
			'anim_direction'  => 'ltr',
			'anim_direction2' => 'bottom',
			'text_type'       => 'default',
		),
		$atts
	)
);

$content_html = '';
$extra_class  = '';

$extra_class = ' mq-type-' . $marquee_type;
if ( 'text' == $marquee_type ) {
	$extra_class .= ' mq-anim-direction-' . $anim_direction;
	$extra_class .= ' mq-text-type-' . $text_type;
} else {
	$extra_class .= ' mq-layout-' . $marquee_layout;
	if ( 'horizontal' == $marquee_layout ) {
		$extra_class .= ' mq-anim-direction-' . $anim_direction;
	} else {
		$extra_class .= ' mq-anim-direction-' . $anim_direction2;
	}
}

$content_html .= '<div class="marquee' . $extra_class . '">';

$inner_content = '';

$content_repeat_int = (int) $content_repeat;

// Text Repeats in Marquee.
for ( $i = 0; $i < $content_repeat_int; $i++ ) {
	if ( 'text' === $marquee_type ) {
		$inner_content .= '<div class="marquee-inner-content text-marquee">' . $text_content . '</div>';
	}
	if ( 'image' === $marquee_type ) {
		$inner_content .= '<div class="marquee-inner-content">';
		foreach ( $marquee_images as $index => $attachment ) {
			if ( ! empty( $attachment['id'] ) ) {
				$inner_content .= wp_get_attachment_image( $attachment['id'], 'full', false );
			} elseif ( ! empty( $attachment['url'] ) ) {
				$inner_content .= '<img src="' . esc_url( $attachment['url'] ) . '" alt="' . esc_html__( 'Marquee Image', 'alpha-core' ) . '">';
			}
		}
		$inner_content .= '</div>';
	}
}

$content_html .= $inner_content;

$content_html .= '</div>';

echo alpha_strip_script_tags( $content_html );
