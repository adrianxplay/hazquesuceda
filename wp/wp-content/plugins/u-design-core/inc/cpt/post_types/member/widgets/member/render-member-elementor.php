<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Members Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Posts Selector
			'member_ids'                 => '',
			'categories'                 => '',
			'skills'                     => '',
			'count'                      => array( 'size' => 4 ),
			'orderby'                    => '',
			'orderway'                   => '',

			// Member Layout
			'layout_type'                => 'grid',
			'row_cnt'                    => 1,
			'col_cnt'                    => array( 'size' => 4 ),
			'thumbnail_size'             => 'medium_large',
			'thumbnail_custom_dimension' => '',
			'creative_cols'              => '',
			'creative_cols_tablet'       => '',
			'creative_cols_mobile'       => '',
			'items_list'                 => '',
			'loadmore_type'              => '',
			'loadmore_label'             => esc_html__( 'Load More', 'alpha-core' ),

			// Member Type
			'follow_theme_option'        => '',
			'member_type'                => 'default',
			'overlay'                    => '',
			'content_align'              => '',
			'excerpt_custom'             => '',
			'excerpt_length'             => 15,
			'excerpt_type'               => 'word',
		),
		$atts
	)
);

if ( ! is_array( $count ) ) {
	$count = json_decode( $count, true );
}
if ( ! is_array( $col_cnt ) ) {
	$col_cnt = json_decode( $col_cnt, true );
}
if ( ! is_array( $excerpt_length ) ) {
	$excerpt_length = json_decode( $excerpt_length, true );
}

$excerpt_limit = isset( $excerpt_length['size'] ) ? $excerpt_length['size'] : $excerpt_length;

// Generate a Query ////////////////////////////////////////////////////////////

$per_page = $count['size'];

$args = array(
	'post_type'      => ALPHA_NAME . '_member',
	'posts_per_page' => $per_page,
);

if ( $member_ids ) {
	$args['post__in'] = $member_ids;
	$orderby          = 'post__in';
}

$tax_query = array();

if ( $categories ) {
	$tax_query[] = array(
		'taxonomy' => ALPHA_NAME . '_member_category',
		'field'    => 'term_id',
		'terms'    => $categories,
	);
}

if ( $skills ) {
	$tax_query[] = array(
		'taxonomy' => ALPHA_NAME . '_member_skill',
		'field'    => 'term_id',
		'terms'    => $skills,
	);
}

if ( $orderby ) {
	$args['orderby'] = $orderby;
}
if ( $orderway ) {
	$args['order'] = $orderway;
}
if ( $tax_query ) {
	$args['tax_query'] = $tax_query;
}

if ( isset( $atts['status'] ) && 'related' == $atts['status'] ) {
	// Single builder's related posts
	$args = wp_parse_args( $args, Alpha_CPTS::get_instance()->related_posts( get_the_ID(), $atts['count']['size'], 'member' ) );
}

$members = new WP_Query( $args );

// Process Posts /////////////////////////////////////////////////////////////////

if ( $members->have_posts() ) {

	$wrapper_class = array( 'posts', 'members' );
	$wrapper_attrs = '';

	// Props

	$props = array(
		'cpt'    => 'member',
		'widget' => true,
		'layout' => $layout_type,
	);

	if ( ! $follow_theme_option ) {
		$props['type']          = $member_type;
		$props['overlay']       = $overlay;
		$props['content_align'] = $content_align;
		if ( 'yes' == $excerpt_custom && $excerpt_limit ) {
			$props['excerpt_length'] = $excerpt_limit;
			$props['excerpt_type']   = $excerpt_type;
		}
	} else {
		$props['follow_theme_option'] = 'yes';
	}

	$props['thumbnail_size']             = $thumbnail_size;
	$props['thumbnail_custom_dimension'] = $thumbnail_custom_dimension;

	// Layout
	$col_cnt          = alpha_elementor_grid_col_cnt( $atts );
	$props['col_cnt'] = $col_cnt;
	$grid_space_class = alpha_get_grid_space_class( $atts );

	if ( $grid_space_class ) {
		$wrapper_class[] = $grid_space_class;
	}

	if ( 'creative' == $layout_type ) {
		if ( is_array( $items_list ) ) {
			$wrapper_class[] = 'row creative-grid';
			if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
				$wrapper_class[] = 'editor-mode';
			}
			$props['repeaters'] = array(
				'ids'    => array(),
				'images' => array(),
			);
			foreach ( $items_list as $item ) {
				$props['repeaters']['ids'][ (int) $item['item_no'] ]    = 'elementor-repeater-item-' . $item['_id'];
				$props['repeaters']['images'][ (int) $item['item_no'] ] = $item['item_thumb_size'];
			}
		}
	}

	if ( 'slider' == $layout_type ) {
		$wrapper_class[] = alpha_get_slider_class( $atts );
		$wrapper_attrs  .= ' data-slider-options="' . esc_attr(
			json_encode(
				alpha_get_slider_attrs( $atts, $col_cnt )
			)
		) . '"';

		$props['row_cnt'] = $row_cnt;
	}
	if ( in_array( $content_align, array( 'left', 'center', 'right' ) ) ) {
		$wrapper_class[] = 'text-' . $content_align;
	}

	// Load More Properties
	if ( $loadmore_type ) {
		$props['loadmore_props'] = $props; // this should be done before adding loadmore props.
		$args['cpt']             = 'post';
		$props['loadmore_args']  = $args;
		$props['posts']          = $members;
		$props['loadmore_type']  = $loadmore_type;
		$props['loadmore_label'] = $loadmore_label;
	} else {
		$props['posts'] = 'no';
	}

	$props['wrapper_class'] = $wrapper_class;
	$props['wrapper_attrs'] = $wrapper_attrs;

	do_action( 'alpha_before_posts_loop', $props );

	alpha_get_template_part( 'posts/post', 'loop-start' );

	while ( $members->have_posts() ) :
		$members->the_post();
		alpha_get_template_part( 'posts/post' );
	endwhile;

	alpha_get_template_part( 'posts/post', 'loop-end' );

	do_action( 'alpha_after_posts_loop' );
}

wp_reset_postdata();
