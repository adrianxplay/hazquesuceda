<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Enqueue styles and scripts for product category.
 *
 * @since 1.2.0
 */
if ( defined( 'ALPHA_CORE_VERSION' ) ) {
	wp_enqueue_style( 'alpha-product-category', alpha_core_framework_uri( '/widgets/categories/category' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
}

$layout_type    = alpha_wc_get_loop_prop( 'layout_type' );
$hover_effect   = alpha_wc_get_loop_prop( 'hover_effect' );
$overlay        = alpha_wc_get_loop_prop( 'overlay' );
$content_align  = alpha_wc_get_loop_prop( 'content_align' );
$content_origin = alpha_wc_get_loop_prop( 'content_origin' );
$row_cnt        = alpha_wc_get_loop_prop( 'row_cnt' );
$category_class = alpha_wc_get_loop_prop( 'category_class' );
$category_attr  = '';
$html_tag       = empty( $html_tag ) ? 'li' : esc_html( $html_tag );

$category_wrapper_class = '';

if ( 'creative' == $layout_type ) {
	$mode = alpha_wc_get_loop_prop( 'creative_mode', -1 );
	$idx  = (int) alpha_wc_get_loop_prop( 'cat_index' );

	if ( -1 != $mode ) {
		$thumb_size = alpha_get_creative_image_sizes( $mode, $idx );
		if ( $thumb_size ) {
			$GLOBALS['alpha_current_cat_img_size'] = $thumb_size;
		}
	}

	wc_set_loop_prop( 'cat_index', $idx + 1 );
	$category_wrapper_class .= ' grid-item-' . ( $idx + 1 );

	$repeaters = alpha_wc_get_loop_prop( 'repeaters' );
	if ( isset( $repeaters['ids'][ $idx + 1 ] ) ) {
		$category_wrapper_class               .= ' ' . $repeaters['ids'][ $idx + 1 ];
		$GLOBALS['alpha_current_cat_img_size'] = $repeaters['images'][ $idx + 1 ];
	}

	if ( isset( $repeaters['ids'][0] ) ) {
		$category_wrapper_class .= ' ' . $repeaters['ids'][0];
	}
	$wrapper_attr = ' data-grid-idx="' . ( $idx + 1 ) . '"';
} elseif ( 'slider' == $layout_type && $row_cnt && 1 != $row_cnt ) {
	$idx = (int) alpha_wc_get_loop_prop( 'cat_index' ) + 1;
	wc_set_loop_prop( 'cat_index', $idx );
	if ( 1 == $idx % $row_cnt ) {
		echo '<li class="product-col"><ul>';
	}
}

if ( 'creative' == $layout_type ) {
	echo '<' . $html_tag . ' class="' . esc_attr( $category_wrapper_class ) . '"' . esc_attr( $wrapper_attr ) . '>';
} else {
	echo '<' . $html_tag . ' class="category-wrap">';
}

if ( empty( $category_class ) ) {
	$category_class = array();
}

$category_class[] = 'category-' . $category->slug;

// // Run as shop filter
// if ( is_product_category( $category->term_id ) ) {
// 	$category_class[] = 'active';
// }

// Content Align
if ( $content_align ) {
	$category_class[] = $content_align;
}

// Overlay
$overlay = alpha_wc_get_loop_prop( 'overlay' );
if ( $overlay ) {
	$category_class[] = alpha_get_overlay_class( $overlay );
}

/**
 * Fires before rendering product category loop item.
 *
 * @since 1.0
 */
do_action( 'alpha_product_loop_before_cat' );
?>

<div <?php wc_product_cat_class( $category_class, $category ); ?>>
	<?php
	/**
	 * The woocommerce_before_subcategory hook.
	 *
	 * @removed woocommerce_template_loop_category_link_open - 10
	 */
	do_action( 'woocommerce_before_subcategory', $category );

	/**
	 * The woocommerce_before_subcategory_title hook.
	 *
	 * @removed woocommerce_subcategory_thumbnail - 10
	 *
	 * @hooked alpha_before_subcategory_thumbnail - 5
	 * @hooked alpha_wc_subcategory_thumbnail - 10
	 * @hooked alpha_after_subcategory_thumbnail - 15
	 */
	do_action( 'woocommerce_before_subcategory_title', $category );

	/**
	 * The woocommerce_shop_loop_subcategory_title hook.
	 *
	 * @removed woocommerce_template_loop_category_title - 10
	 * @hooked alpha_wc_template_loop_category_title - 10
	 */

	do_action( 'woocommerce_shop_loop_subcategory_title', $category );

	/**
	 * The woocommerce_after_subcategory_title hook.
	 *
	 * @hooked alpha_wc_after_subcategory_title - 10
	 */
	do_action( 'woocommerce_after_subcategory_title', $category );

	/**
	 * The woocommerce_after_subcategory hook.
	 *
	 * @removed woocommerce_template_loop_category_link_close - 10
	 */
	do_action( 'woocommerce_after_subcategory', $category );
	?>
</div>

<?php
/**
 * Fires after rendering product category loop item.
 *
 * @since 1.0
 */
do_action( 'alpha_product_loop_after_cat' );

echo '</' . $html_tag . '>';

if ( 'slider' == $layout_type && $row_cnt && 1 != $row_cnt ) {
	if ( 0 == (int) alpha_wc_get_loop_prop( 'cat_index' ) % $row_cnt ) {
		echo '</ul></li>';
	}
}
