<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce/Templates
 * @version     3.3.0
 */

defined( 'ABSPATH' ) || die;
if ( defined( 'ALPHA_CORE_VERSION' ) ) {
	wp_enqueue_style( 'alpha-share', alpha_core_framework_uri( '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
}

global $alpha_layout;

$col_cnt     = array();
$layout_type = 'grid';
// Set product type as theme option
if ( ! alpha_wc_get_loop_prop( 'widget' ) || 'yes' == alpha_wc_get_loop_prop( 'follow_theme_option' ) ) {
	alpha_wc_set_loop_prop();
}

$wrapper_class   = alpha_wc_get_loop_prop( 'wrapper_class', array() );
$wrapper_attrs   = alpha_wc_get_loop_prop( 'wrapper_attrs', '' );
$wrapper_class[] = 'products';

// Set default show info for alpha shop
$show_info = apply_filters( 'alpha_get_shop_products_show_info', alpha_wc_get_loop_prop( 'show_info', array() ) );
wc_set_loop_prop( 'show_info', $show_info );

if ( apply_filters( 'alpha_is_vendor_store', false ) && ! alpha_wc_get_loop_prop( 'widget' ) ) {

	/**
	 * Vendor Store
	 */
	if ( isset( $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) && 'list' == $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) { // if list view mode
		$col_cnt = array(
			'sm'  => 1,
			'min' => 2,
		);
		wc_set_loop_prop( 'product_type', 'list' );
		wc_set_loop_prop( 'content_align', is_rtl() ? 'right' : 'left' );

		if ( ! alpha_get_option( 'catalog_mode' ) ) {
			$show_info   = alpha_wc_get_loop_prop( 'show_info' );
			$show_info[] = 'short_desc';
			wc_set_loop_prop( 'show_info', $show_info );
		}
	} else {
		$col_cnt = alpha_wc_get_loop_prop( 'col_cnt', alpha_get_responsive_cols( array( 'lg' => alpha_get_option( 'vendor_products_column' ) ) ) );
	}
} elseif ( alpha_is_shop() && ! ( alpha_wc_get_loop_prop( 'widget' ) && 'product_category_group' != alpha_wc_get_loop_prop( 'widget' ) ) ) {

	/**
	 * Product Archive (Shop)
	 */

	$list_col = array(
		'sm'  => 1,
		'min' => 2,
	);

	if ( empty( alpha_wc_get_loop_prop( 'col_cnt' ) ) ) {
		$grid_col = alpha_get_responsive_cols( array( 'lg' => empty( $alpha_layout['products_column'] ) ? 3 : $alpha_layout['products_column'] ) );
	} else {
		$grid_col = alpha_wc_get_loop_prop( 'col_cnt', alpha_get_responsive_cols( array( 'lg' => 3 ) ) );
	}

	if ( isset( $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) && 'list' == $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) { // if list view mode

		wc_set_loop_prop( 'product_type', 'list' );
		wc_set_loop_prop( 'content_align', is_rtl() ? 'right' : 'left' );

		if ( ! alpha_get_option( 'catalog_mode' ) ) {
			$show_info   = alpha_wc_get_loop_prop( 'show_info' );
			$show_info[] = 'short_desc';
			wc_set_loop_prop( 'show_info', $show_info );
		}
		$col_cnt      = $list_col;
		$toggle_class = alpha_get_col_class( $grid_col );
	} else {
		$col_cnt      = $grid_col;
		$toggle_class = alpha_get_col_class( $list_col );
	}

	$wrapper_attrs .= ' data-toggle_cls="' . esc_attr( $toggle_class ) . '"';

	if ( ! alpha_wc_get_loop_prop( 'widget' ) ) {
		$loadmore_label = alpha_get_option( 'products_load_label' );
		wc_set_loop_prop( 'loadmore_type', ! empty( $alpha_layout['loadmore_type'] ) ? $alpha_layout['loadmore_type'] : ( alpha_get_option( 'products_load' ) ? alpha_get_option( 'products_load' ) : 'page' ) );
		wc_set_loop_prop( 'loadmore_label', $loadmore_label ? $loadmore_label : esc_html__( 'Load More', 'alpha' ) );
		wc_set_loop_prop( 'loadmore_args', array( 'shop' => true ) );

		// Add classes to wrapper class
		$gap = alpha_get_option( 'products_gap' );
		if ( $gap ) {
			$wrapper_class[] = 'gutter-' . $gap;
		}

		echo '<div class="product-archive">';
	}
} elseif ( alpha_wc_get_loop_prop( 'name' ) && ! alpha_wc_get_loop_prop( 'widget' ) ) {

	// Related Products in Single Product Page : related or up-sells or more seller products
	$columns     = alpha_get_option( 'product_related_column' );
	$layout_type = 'slider';

	// If sidebar is shown, show 3 columns
	if ( ! $columns &&
		( ! empty( $alpha_layout['left_sidebar'] ) && 'hide' != $alpha_layout['left_sidebar'] ||
		! empty( $alpha_layout['right_sidebar'] ) && 'hide' != $alpha_layout['right_sidebar'] ) ) {

		$columns = 3;
	}

	$col_cnt = alpha_wc_get_loop_prop( 'col_cnt' );
	if ( ! $col_cnt ) {
		$col_cnt = alpha_get_responsive_cols( array( 'lg' => $columns ) );
	}

	$wrapper_class[] = alpha_get_slider_class();
	$slider_attrs    = alpha_get_slider_attrs(
		array(
			'show_dots'    => true,
			'status_class' => 'slider-shadow',
		),
		$col_cnt
	);
	if ( empty( $slider_attrs['breakpoints'][0] ) ) {
		$slider_attrs['breakpoints'][0] = array( 'spaceBetween' => 20 );
	} else {
		$slider_attrs['breakpoints'][0]['spaceBetween'] = 20;
	}
	$wrapper_attrs .= ' data-slider-options="' . esc_attr(
		json_encode( $slider_attrs )
	) . '"';
} else {

	/**
	 * Shortcode Products
	 */
	$col_cnt = alpha_wc_get_loop_prop( 'col_cnt', alpha_get_responsive_cols( array( 'lg' => 3 ) ) );

	$show_info = apply_filters( 'alpha_get_widget_products_show_info', alpha_wc_get_loop_prop( 'show_info', array() ) );

	wc_set_loop_prop( 'show_info', $show_info );
}

// For Category widget
$show_info      = alpha_wc_category_show_info( alpha_wc_get_loop_prop( 'category_type' ) );
$category_class = array( alpha_get_category_classes() );
wc_set_loop_prop( 'show_link', 'yes' == $show_info['link'] );
wc_set_loop_prop( 'show_count', 'yes' == $show_info['count'] );
wc_set_loop_prop( 'category_class', $category_class );


if ( alpha_wc_get_loop_prop( 'run_as_filter' ) ) {
	$wrapper_class[] = 'filter-categories';
}

// If loadmore or ajax category filter, add only pages count.
if ( alpha_wc_get_loop_prop( 'alpha_ajax_load' ) ) {

	$wrapper_attrs .= ' data-load-max="' . alpha_wc_get_loop_prop( 'total_pages' ) . '"';

} else {

	// Load more
	$loadmore_type = alpha_wc_get_loop_prop( 'loadmore_type' );

	if ( alpha_wc_get_loop_prop( 'filter_cat' ) ) {
		wp_enqueue_style( 'alpha-tab', ALPHA_CORE_INC_URI . '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
	}

	if ( ( $loadmore_type || alpha_wc_get_loop_prop( 'filter_cat' ) || alpha_wc_get_loop_prop( 'filter_cat_w' ) ) ) {

		$wrapper_attrs .= ' ' . alpha_loadmore_attributes(
			'product',
			alpha_wc_get_loop_prop( 'loadmore_props' ),   // Props
			alpha_wc_get_loop_prop( 'loadmore_args' ),    // Args
			$loadmore_type,                         // Type
			alpha_wc_get_loop_prop( 'total_pages' ),      // Total Pages
			alpha_wc_get_loop_prop( 'filter_cat' )        // Filter Category
		);

		if ( alpha_wc_get_loop_prop( 'filter_cat_w' ) ) {
			$wrapper_class[] = 'filter-products';
		}

		if ( 'scroll' == $loadmore_type ) {
			$wrapper_class[] = 'load-scroll';

			if ( alpha_is_shop() ) {
				$wrapper_attrs .= ' data-load-to=".main-content .products"';
			}
		}
		wp_enqueue_script( 'alpha-ajax' );
	}
}


if ( 'list' == alpha_wc_get_loop_prop( 'product_type' ) ) {
	$wrapper_class[] = 'list-type-products';
}

if ( 'creative' != alpha_wc_get_loop_prop( 'layout_type' ) ) {
	$wrapper_class[] = alpha_get_col_class( $col_cnt );
}

$wrapper_class = apply_filters( 'alpha_product_loop_wrapper_classes', $wrapper_class );


/**
 * Hook: alpha_before_shop_loop_start.
 *
 * @hooked alpha_before_shop_loop_start - 10
 */
do_action( 'alpha_before_shop_loop_start' );

echo '<ul class="' . esc_attr( implode( ' ', $wrapper_class ) ) . '"' . alpha_escaped( $wrapper_attrs ) . '>';

if ( false !== array_search( 'filter-categories', $wrapper_class ) ) {

	if ( alpha_wc_get_loop_prop( 'show_all_filter' ) && isset( $category_class ) ) {
		echo '<li class="category-wrap nav-filter-clean"><div class="product-category ' . esc_attr( implode( ' ', $category_class ) ) . '"><a href="#" class="active"><h3 class="woocommerce-loop-category__title">' . esc_html__( 'All', 'alpha' ) . '</h3></a></div></li>';
	}
}
