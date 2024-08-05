<?php
/**
 * The testimonial widget render.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'testimonial_type'    => 'simple',
			'name'                => esc_html__( 'John Doe', 'alpha-core' ),
			'role'                => esc_html__( 'Customer', 'alpha-core' ),
			'link'                => '',
			'comment_title'       => '',
			'content'             => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpha-core' ),
			'avatar'              => array( 'url' => '' ),
			'rating'              => '',
			'star_icon'           => '',
			'avatar_pos'          => 'top',
			'commenter_pos'       => 'after',
			'aside_commenter_pos' => 'after_avatar',
			'rating_pos'          => 'before_title',
			'rating_sp'           => array( 'size' => 0 ),
		),
		$atts
	)
);

$html        = '';
$rating_html = '';

if ( defined( 'ELEMENTOR_VERSION' ) ) {
	$image = Elementor\Group_Control_Image_Size::get_attachment_image_html( $atts, 'avatar' );
} else {
	$image = '';
	if ( is_numeric( $avatar ) ) {
		$img_data = wp_get_attachment_image_src( $avatar, 'full' );
		$img_alt  = get_post_meta( $avatar, '_wp_attachment_image_alt', true );
		$img_alt  = $img_alt ? esc_attr( trim( $img_alt ) ) : esc_attr__( 'Testimonial Image', 'alpha-core' );

		if ( is_array( $img_data ) ) {
			$image = '<img src="' . esc_url( $img_data[0] ) . '" alt="' . $img_alt . '" width="' . esc_attr( $img_data[1] ) . '" height="' . esc_attr( $img_data[2] ) . '">';
		}
	}
}

if ( isset( $link['url'] ) && $link['url'] ) {
	$attrs           = [];
	$attrs['href']   = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#';
	$attrs['target'] = ! empty( $link['is_external'] ) ? '_blank' : '';
	$attrs['rel']    = ! empty( $link['nofollow'] ) ? 'nofollow' : '';
	if ( ! empty( $link['custom_attributes'] ) ) {
		foreach ( explode( ',', $link['custom_attributes'] ) as $attr ) {
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
	$image = '<a ' . $link_attrs . '>' . $image . '</a>';
}

$image = '<div class="avatar">' . $image . '</div>';

$title_escaped = trim( esc_html( $comment_title ) );
$content       = '<p class="comment">' . esc_textarea( $content ) . '</p>';

if ( $rating && 'simple' != $testimonial_type ) {
	$rating            = floatval( $rating );
	$rating_sp['size'] = floatval( $rating_sp['size'] );
	$rating_cls        = '';
	if ( $star_icon ) {
		$rating_cls .= ' ' . $star_icon;
	}
	$rating_w     = 'calc(' . 20 * floatval( $rating ) . '% - ' . $rating_sp['size'] * ( $rating - floor( $rating ) ) . 'px)'; // get rating width
	$rating_html .= '<div class="ratings-container"><div class="ratings-full' . esc_attr( $rating_cls ) . '" style="letter-spacing: ' . $rating_sp['size'] . 'px;" aria-label="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'alpha-core' ), $rating ) ) . '"><span class="ratings" style="width: ' . $rating_w . '; letter-spacing: ' . $rating_sp['size'] . 'px;"></span></div></div>';
}
$commenter = '<cite><span class="name">' . esc_html( $name ) . '</span><span class="role">' . esc_html( $role ) . '</span></cite>';

if ( ! empty( $testimonial_type ) ) {
	require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . "/widgets/testimonial/templates/render-{$testimonial_type}.php" );
}

echo alpha_escaped( $html );
