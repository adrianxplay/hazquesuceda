<?php
/**
 * Post Navigation
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
$post_type = get_post_type();

if ( 'attachment' != $post_type ) {
	$post_type_object = get_post_type_object( $post_type );
	$navigation       = get_the_post_navigation(
		array(
			'prev_text' => '<i class="' . ( ! empty( $prev_icon ) ? $prev_icon : ( ALPHA_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'right' : 'left' ) ) ) . '"></i>' . ( ( isset( $show_texts ) && ! $show_texts ) ? '' : ( '<span class="label">' . esc_html__( 'Previous ', 'alpha' ) . $post_type_object->labels->singular_name . '</span>' . '<span class="pager-link-title">%title</span>' ) ),
			'next_text' => '<i class="' . ( ! empty( $next_icon ) ? $next_icon : ( ALPHA_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) ) ) . '"></i>' . ( ( isset( $show_texts ) && ! $show_texts ) ? '' : ( '<span class="label">' . esc_html__( 'Next ', 'alpha' ) . $post_type_object->labels->singular_name . '</span>' . '<span class="pager-link-title">%title</span>' ) ),
			'class'     => ( isset( $show_texts ) && ! $show_texts ) ? 'navigation-with-icon' : 'post-navigation',
		)
	);

	echo alpha_escaped( $navigation );
}
