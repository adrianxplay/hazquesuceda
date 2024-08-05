<?php
/**
 * pc_default_after_subcategory_thumbnail
 *
 * Render html after subcategory thumbnail.
 *
 * @param string $category
 * @since 4.1
 */
function alpha_pc_default_after_subcategory_thumbnail( $category ) {
	if ( '' != alpha_wc_get_loop_prop( 'category_type' ) ) {
		return;
	}
	$content_origin = alpha_wc_get_loop_prop( 'content_origin' );
	echo '</figure>';
	if ( $content_origin ) {
		echo '<div class="category-content ' . $content_origin . '">';
	} else {
		echo '<div class="category-content">';
	}
}

/**
 * pc_default_after_subcategory_title
 *
 * Render html after subcategory title.
 *
 * @since 4.1
 */
function alpha_pc_default_after_subcategory_title() {
	if ( '' == alpha_wc_get_loop_prop( 'category_type' ) ) {
		echo '</div>';
		echo '</a>';
	}
}

/**
 * pc_card_template_loop_category_title
 *
 * Render product category title.
 *
 * @param array $category
 * @since 4.1
 */
function alpha_pc_default_template_loop_category_title( $category ) {
	if ( '' != alpha_wc_get_loop_prop( 'category_type' ) ) {
		return;
	}

	// Title
	echo '<h3 class="woocommerce-loop-category__title">' . esc_html( $category->name ) . '</h3>';

	// Count
	if ( alpha_wc_get_loop_prop( 'show_count', true ) ) {
		echo apply_filters( 'woocommerce_subcategory_count_html', '<mark>' . esc_html( $category->count ) . ' ' . esc_html__( 'Products', 'alpha' ) . '</mark>', $category );
	}
	// Link
	if ( alpha_wc_get_loop_prop( 'show_link', true ) ) {
		$link_text  = alpha_wc_get_loop_prop( 'link_text' );
		$link_class = 'btn btn-underline btn-link';
		echo '<a class="' . esc_html( $link_class ) . '"' .
			( alpha_wc_get_loop_prop( 'run_as_filter' ) ? ' data-cat="' . $category->term_id . '"' : '' ) .
			' href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '">' .
			( $link_text ? esc_html( $link_text ) : esc_html__( 'Shop Now', 'alpha' ) ) .
			'</a>';
	}
}
