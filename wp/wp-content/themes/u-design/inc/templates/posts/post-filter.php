<?php
/**
 * Blog Filter
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

$cpt          = alpha_get_loop_prop( 'cpt' );
$cat_taxonomy = 'post' == $cpt ? 'category' : ALPHA_NAME . '_' . $cpt . '_category';
$cur_cat      = get_query_var( 'post' == $cpt ? 'category_name' : $cat_taxonomy );
$count_posts  = wp_count_posts();
$terms        = get_terms(
	array(
		'taxonomy'   => $cat_taxonomy,
		'hide_empty' => true,
		'count'      => true,
		'pad_counts' => true,
	)
);
if ( isset( $count_posts->publish ) ) {
	$count_posts = (int) $count_posts->publish;
}

// get blog url for all nav filter
if ( 'post' != $cpt ) {
	$blog_url = get_post_type_archive_link( ALPHA_NAME . '_' . $cpt );
} else {
	$blog_url = 'page' == get_option( 'show_on_front' ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url();
}

echo '<ul class="nav-filters post-filters" data-target=".posts">';
echo '<li><a href="' . esc_url( $blog_url ) . '" class="nav-filter post-filter' . ( $cur_cat ? '' : ' active' ) . '" data-filter="*">' . esc_html__( 'All', 'alpha' ) . '</a></li>';

foreach ( $terms as $term ) {
	echo '<li>';
	echo '<a href="' . esc_url( get_term_link( $term ) ) . '" class="nav-filter post-filter' . ( $cur_cat == $term->slug ? ' active' : '' ) . '" data-cat="' . (int) $term->term_id . '" data-filter=".' . esc_attr( $term->slug ) . '">';
	echo esc_html( $term->name ) . '</a>';
	echo '</li>';
}

echo '</ul>';
