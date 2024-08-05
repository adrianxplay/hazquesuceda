<?php
/**
 * Post Category
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

$tax       = 'category';
$post_type = get_post_type();
if ( 'product' == $post_type ) {
	$tax = 'product_cat';
} elseif ( ALPHA_NAME == substr( $post_type, 0, strlen( ALPHA_NAME ) ) ) {
	$tax = $post_type . '_category';
}

if ( 'category' == $tax ) {
	if ( 'list' == alpha_get_loop_prop( 'type' ) || 'categorized' == alpha_get_loop_prop( 'type' ) ) {
		$cats = get_the_category_list( ', ' );
	} else {
		$cats = get_the_category_list( ' ' );
	}
} elseif ( taxonomy_exists( $tax ) ) {
	$cats = get_the_term_list( 0, $tax, '', ', ' );
} else {
	return;
}

if ( $cats ) {
	echo '<div class="post-cats">' . alpha_strip_script_tags( $cats ) . '</div>';
}
