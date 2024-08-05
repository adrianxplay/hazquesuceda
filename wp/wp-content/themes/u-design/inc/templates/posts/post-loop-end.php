<?php
/**
 * Post Archive
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

$posts_layout = alpha_get_loop_prop( 'posts_layout' );

if ( 'timeline' == $posts_layout ) {
	echo '</section>';
}
// Close multiple slider
if ( 'slider' == $posts_layout && isset( $GLOBALS['alpha_post_idx'] ) && alpha_get_loop_prop( 'row_cnt' ) >= 2 && 0 != $GLOBALS['alpha_post_idx'] % alpha_get_loop_prop( 'row_cnt' ) ) {
	echo '</div>';
}

echo '</div>';

$posts_query = alpha_get_loop_prop( 'posts' );

if ( empty( $posts_query ) && ! alpha_get_loop_prop( 'widget' ) ) {
	$posts_query = $GLOBALS['wp_query'];
}

if ( $posts_query instanceof WP_Query && ( 1 < $posts_query->max_num_pages && 'slider' != alpha_get_loop_prop( 'posts_layout' ) ) ) {
	alpha_loadmore_html(
		$posts_query,
		alpha_get_loop_prop( 'loadmore_type' ),
		alpha_get_loop_prop( 'loadmore_label', esc_html( 'Load More', 'alpha' ) )
	);
}
