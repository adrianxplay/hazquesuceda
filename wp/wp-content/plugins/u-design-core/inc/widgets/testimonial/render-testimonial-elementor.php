<?php
/**
 * The testimonial widget render.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Group_Control_Image_Size;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'testimonial_type' => 'boxed',
			'name'             => esc_html__( 'John Doe', 'alpha-core' ),
			'role'             => esc_html__( 'Healthcare Social Worker', 'alpha-core' ),
			'link'             => '',
			'content'          => esc_html__( 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.', 'alpha-core' ),
			'hide_role'        => '',
			'hide_rating'      => '',
			'avatar'           => array( 'url' => '' ),
			'avatar_pos'       => 'top',
			'commenter_pos'    => 'after',
			'rating_pos'       => 'before_comment',
			'rating'           => '',
			'star_icon'        => '',
			'rating_sp'        => array( 'size' => 3 ),
		),
		$atts
	)
);

$html        = '';
$rating_html = '';

if ( 'image' == $atts['avatar_type'] ) {
	$avatar_html = Group_Control_Image_Size::get_attachment_image_html( $atts, 'avatar' );
} else {
	$avatar_html = '<i class="' . $atts['avatar_icon']['value'] . '"></i>';
}

if ( $avatar_html && isset( $link['url'] ) && $link['url'] ) {
	$avatar_html = '<a href="' . esc_url( $link['url'] ) . '">' . $avatar_html . '</a>';
}
if ( $avatar_html ) {
	$avatar_html = '<div class="avatar' . ( 'image' == $atts['avatar_type'] ? ' img-avatar' : '' ) . '">' . $avatar_html . '</div>';
}

$repeater_setting_key = $this->get_repeater_setting_key( 'content', 'testimonial_group_list', $key );
$this->add_render_attribute( $repeater_setting_key, 'class', 'comment' );
$this->add_inline_editing_attributes( $repeater_setting_key );
$content = '<p ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $content ) . '</p>';

if ( 'yes' != $hide_rating && $rating ) {
	$rating            = floatval( $rating );
	$rating_sp['size'] = floatval( '' === $rating_sp['size'] ? 3 : $rating_sp['size'] );
	$rating_cls        = '';
	if ( $star_icon ) {
		$rating_cls .= ' ' . $star_icon;
	}
	$rating_w     = 'calc(' . 20 * floatval( $rating ) . '% - ' . $rating_sp['size'] * ( $rating - floor( $rating ) ) . 'px)'; // get rating width
	$rating_html .= '<div class="ratings-container"><div class="ratings-full star-rating' . $rating_cls . '" style="letter-spacing: ' . $rating_sp['size'] . 'px;"><span class="ratings" style="width: ' . $rating_w . '; letter-spacing: ' . $rating_sp['size'] . 'px;"></span></div></div>';
}

$repeater_setting_key = $this->get_repeater_setting_key( 'name', 'testimonial_group_list', $key );
$this->add_render_attribute( $repeater_setting_key, 'class', 'name' );
$this->add_inline_editing_attributes( $repeater_setting_key );

$commenter = '<cite><span ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . esc_html( $name ) . '</span>';

if ( 'yes' == $hide_role ) {
	$commenter .= '</cite>';
} else {
	$repeater_setting_key = $this->get_repeater_setting_key( 'role', 'testimonial_group_list', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'role' );
	$this->add_inline_editing_attributes( $repeater_setting_key );

	if ( $role ) {
		$commenter .= '<span ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . esc_html( $role ) . '</span></cite>';
	} else {
		$commenter .= '</cite>';
	}
}

if ( ! empty( $testimonial_type ) ) {
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . "/widgets/testimonial/templates/render-{$testimonial_type}.php" );
}

echo alpha_escaped( $html );
