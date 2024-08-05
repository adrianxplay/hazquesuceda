<?php
/**
 * Alpha WooCommerce Product Category Functions
 *
 * Functions used to display product category.
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open' );
remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close' );

// Category Thumbnail
add_filter( 'subcategory_archive_thumbnail_size', 'alpha_wc_category_thumbnail_size' );
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail' );

// Category Content
remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title' );

/**
 * Alpha Category Thumbnail Functions
 */
/**
 * wc_category_thumbnail_size
 *
 * Get category thumbnail size.
 *
 * @param string $size
 * @return string
 * @since 4.0
 */
if ( ! function_exists( 'alpha_wc_category_thumbnail_size' ) ) {
	function alpha_wc_category_thumbnail_size( $size ) {
		$size = alpha_wc_get_loop_prop( 'thumbnail_size', $size );
		if ( 'custom' == $size ) {
			return alpha_wc_get_loop_prop( 'thumbnail_custom_size', 'woocommerce_thumbnail' );
		}
		return $size;
	}
}

/**
 * wc_category_show_info
 *
 * Get cateogry show info from category type.
 *
 * @param string $type
 * @return array
 * @since 4.0
 */
if ( ! function_exists( 'alpha_wc_category_show_info' ) ) {
	function alpha_wc_category_show_info( $type = '' ) {
		$cat_options = apply_filters(
			'alpha_wc_category_show_infos',
			array(
				''          => array(
					'link'  => '',
					'count' => '',
				),
				'frame'     => array(
					'link'  => 'yes',
					'count' => '',
				),
				'banner'    => array(
					'link'  => 'yes',
					'count' => 'yes',
				),
				'label'     => array(
					'link'  => '',
					'count' => '',
				),
				'icon'      => array(
					'link'  => '',
					'count' => '',
				),
				'classic'   => array(
					'link'  => '',
					'count' => 'yes',
				),
				'classic-2' => array(
					'link'  => '',
					'count' => '',
				),
				'ellipse'   => array(
					'link'  => '',
					'count' => 'yes',
				),
				'ellipse-2' => array(
					'link'  => '',
					'count' => '',
				),
				'group'     => array(
					'link'  => '',
					'count' => '',
				),
				'group-2'   => array(
					'link'  => '',
					'count' => '',
				),
				'simple'    => array(
					'link'  => '',
					'count' => 'yes',
				),
			)
		);
		return $cat_options[ $type ];
	}
}

/**
 * get_category_classes
 *
 * Get category classes from category type.
 *
 * @return array
 * @since 4.0
 */
if ( ! function_exists( 'alpha_get_category_classes' ) ) {
	function alpha_get_category_classes() {

		$category_type  = alpha_wc_get_loop_prop( 'category_type' );
		$category_class = '';

		if ( 'frame' == $category_type ) {
			$category_class = 'cat-type-frame cat-type-absolute';
		} elseif ( 'banner' == $category_type ) {
			$category_class = 'cat-type-banner cat-type-absolute';
		} elseif ( 'simple' === $category_type ) {
			$category_class = 'cat-type-simple';
		} elseif ( 'label' == $category_type ) {
			$category_class = 'cat-type-block';
		} elseif ( 'icon' == $category_type ) {
			$category_class = 'cat-type-icon';
		} elseif ( 'classic' == $category_type ) {
			$category_class = 'cat-type-classic cat-type-absolute';
		} elseif ( 'classic-2' == $category_type ) {
			$category_class = 'cat-type-classic cat-type-classic-2 cat-type-absolute';
		} elseif ( 'ellipse' == $category_type ) {
			$category_class = 'cat-type-ellipse';
		} elseif ( 'ellipse-2' === $category_type ) {
			$category_class = 'cat-type-ellipse2';
		} elseif ( 'group' == $category_type ) {
			$category_class = 'cat-type-group';
		} elseif ( 'group-2' == $category_type ) {
			$category_class = 'cat-type-group2';
		} else {
			$category_class = 'cat-type-default cat-type-absolute';
		}

		return apply_filters( 'alpha_get_category_classes', $category_class, $category_type );
	}
}

/* Product Category Types */
foreach ( apply_filters(
	'alpha_pc_types',
	array(
		'default'   => true,
		'frame'     => true,
		'banner'    => true,
		'simple'    => true,
		'icon'      => true,
		'classic'   => true,
		'classic-2' => true,
		'ellipse'   => true,
		'ellipse-2' => true,
		'group'     => true,
		'group-2'   => true,
		'label'     => true,
	),
	'hooks'
) as $key => $value ) {
	if ( $key && $value ) {
		require_once alpha_framework_path( ALPHA_FRAMEWORK_PLUGINS . "/woocommerce/product-category/product-category-{$key}.php" );
	}
}

/**
 * Fires after setting product category actions and filters.
 *
 * Here you can remove and add more actions and filters.
 *
 * @since 4.0
 */
do_action( 'alpha_after_pc_hooks' );
