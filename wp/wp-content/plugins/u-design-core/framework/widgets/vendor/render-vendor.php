<?php
/**
 * Alpha Elementor Vendors Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Select Vendors
			'vendor_select_type' => 'individual',
			'vendor_ids'         => '',
			'vendor_category'    => '',
			'vendor_count'       => '',
			'vendor_period'      => '',
			'thumbnail_size'     => '',
			// Select Vendors Layout
			'col_cnt'            => array( 'size' => 4 ),
			'row_cnt'            => 1,
			'col_sp'             => '',
			'layout_type'        => 'grid',

			// Select Vendor Display Type
			'vendor_type'        => 'vendor-1',
			'vendor_show_info'   => array( 'name', 'avatar', 'rating', 'product_count', 'products' ),
			'show_vendor_link'   => '',
			'show_total_sale'    => '',
			'vendor_link_text'   => esc_html__( 'Browse This Vendor', 'alpha-core' ),
		),
		$atts
	)
);

$vendors          = array();
$wrapper_class    = array();
$wrapper_attrs    = '';
$grid_space_class = alpha_get_grid_space_class( $atts );
$col_cnt          = alpha_elementor_grid_col_cnt( $atts );

$visible = array(
	'products'      => in_array( 'products', $vendor_show_info ),
	'avatar'        => in_array( 'avatar', $vendor_show_info ),
	'name'          => in_array( 'name', $vendor_show_info ),
	'product_count' => in_array( 'product_count', $vendor_show_info ),
	'rating'        => in_array( 'rating', $vendor_show_info ),
);

if ( 'group' == $vendor_select_type ) {
	if ( 'sale' == $vendor_category ) {
		$vendors = Alpha_Vendors::get_top_selling_vendors( $vendor_count, $vendor_period );
	}

	if ( 'rating' == $vendor_category ) {
		$vendors = Alpha_Vendors::get_top_rating_vendors( $vendor_count );
	}

	if ( 'recent' == $vendor_category ) {
		$vendors = function_exists( 'alpha_get_vendors' ) ? alpha_get_vendors( array(), 'registered', $vendor_count ) : array();
	}

	if ( '' == $vendor_category ) {
		$vendors = function_exists( 'alpha_get_vendors' ) ? alpha_get_vendors( array(), '', $vendor_count ) : array();
	}
} else {
	if ( ! is_array( $vendor_ids ) || 0 == count( $vendor_ids ) ) {
		$vendor_ids = function_exists( 'alpha_get_vendors' ) ? alpha_get_vendors() : array();
		foreach ( $vendor_ids as $vid ) {
			$vendor['id'] = $vid['id'];
			$vendors[]    = $vendor;
		}
	} else {
		foreach ( $vendor_ids as $id ) {
			$vendor['id'] = $id;
			$vendors[]    = $vendor;
		}
	}
}


if ( $grid_space_class ) {
	$wrapper_class[] = $grid_space_class;
}

if ( $col_cnt ) {
	$wrapper_class[] = alpha_get_col_class( $col_cnt );
}

if ( 'slider' == $layout_type ) {
	$wrapper_class[] = alpha_get_slider_class( $atts );

	$wrapper_class = implode( ' ', $wrapper_class );

	$wrapper_attrs = ' data-slider-options="' . esc_attr(
		json_encode(
			alpha_get_slider_attrs( $atts, $col_cnt )
		)
	) . '"';

	echo '<div ' . $wrapper_attrs . ' class="alpha-vendor-group ' . esc_attr( $wrapper_class ) . '">';
} else {
	$wrapper_class[] = alpha_get_col_class( $col_cnt );

	$wrapper_class = implode( ' ', $wrapper_class );

	echo '<div class="alpha-vendor-group ' . esc_attr( $wrapper_class ) . '">';
}

if ( 0 == count( $vendors ) ) {
	esc_html_e( 'There are no vendors matched', 'alpha-core' );
}

foreach ( $vendors as $vendor_no => $vendor ) {
	if ( class_exists( 'WeDevs_Dokan' ) ) {
		$vendor_info = alpha_get_dokan_vendor_info( $vendor );
	} elseif ( class_exists( 'WCFM' ) ) {
		$vendor_info = alpha_get_wcfm_vendor_info( $vendor );
	} elseif ( class_exists( 'WC_Vendors' ) ) {
		$vendor_info = alpha_get_wc_vendor_info( $vendor );
	} elseif ( class_exists( 'WCMp' ) ) {
		$vendor_info = alpha_get_wcmp_vendor_info( $vendor );
	}

	if ( $vendor_info ) {
		$query = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'showposts'           => 3,
			'meta_key'            => 'total_sales',
			'orderby'             => 'meta_value_num',
			'author'              => $vendor_info['id'],
		);

		$list = new WP_Query( $query );

		if ( 'slider' == $layout_type && 1 < $row_cnt && 1 == ( $vendor_no + 1 ) % (int) $row_cnt ) {
			echo '<div>';
		}

		echo '<div class="vendor-widget-wrap">';
		if ( ! empty( $vendor_type ) ) {
			require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . "/widgets/vendor/templates/render-{$vendor_type}.php" );
		}
		echo '</div>';

		if ( 'slider' == $layout_type && 1 < $row_cnt && 0 == ( $vendor_no + 1 ) % (int) $row_cnt ) {
			echo '</div>';
		}
	}
}
echo '</div>';
