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

$date_link     = '';
$category_link = '';

if ( ! isset( $show_info ) ) {
	$show_info = array( 'date', 'category' );
}
if ( in_array( 'date', $show_info ) ) {
	$date_link = '<a href="' . esc_url( get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ) ) . '">'
		. esc_html( get_the_date() ) . '</a>';
}
if ( in_array( 'category', $show_info ) ) {
	$tax       = 'category';
	$post_type = get_post_type();
	if ( 'product' == $post_type ) {
		$tax = 'product_cat';
	} elseif ( ALPHA_NAME == substr( $post_type, 0, strlen( ALPHA_NAME ) ) ) {
		$tax = $post_type . '_category';
	}

	if ( 'category' == $tax ) {
		$cats = get_the_category_list( ', ' );
	} elseif ( taxonomy_exists( $tax ) ) {
		$cats = get_the_term_list( 0, $tax, '', ', ' );
	} else {
		$cats = '';
	}
	if ( $cats ) {
		$category_link = alpha_strip_script_tags( $cats );
	}
}

if ( $date_link || $category_link ) : ?>
	<div class="post-cats-date">
		<?php
		if ( $date_link && $category_link ) {
			// translators: %1$s represents post date in link format, %2$s represents post categories in link format.
			printf( esc_html__( '%1$s in %2$s', 'alpha' ), $date_link, $category_link );
		} else {
			echo alpha_escaped( $date_link ? $date_link : $category_link );
		}
		?>
	</div>
	<?php
endif;
