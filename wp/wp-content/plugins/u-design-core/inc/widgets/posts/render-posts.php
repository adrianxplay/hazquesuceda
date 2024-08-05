<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Posts Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Posts Selector
			'post_ids'                   => '',
			'categories'                 => '',
			'count'                      => array( 'size' => 4 ),
			'orderby'                    => '',
			'orderway'                   => '',

			// Posts Layout
			'layout_type'                => 'grid',
			'row_cnt'                    => 1,
			'col_cnt'                    => array( 'size' => 4 ),
			'thumbnail_size'             => 'alpha-post-small',
			'thumbnail_custom_dimension' => '',
			'creative_cols'              => '',
			'creative_cols_tablet'       => '',
			'creative_cols_mobile'       => '',
			'items_list'                 => '',
			'loadmore_type'              => '',
			'loadmore_label'             => esc_html__( 'Load More', 'alpha-core' ),

			// Post Type
			'follow_theme_option'        => '',
			'post_type'                  => 'default',
			'overlay'                    => '',
			'read_more_label'            => esc_html__( 'Read More', 'alpha-core' ),
			'read_more_class'            => '',
			'excerpt_custom'             => '',
			'excerpt_length'             => 20,
			'excerpt_type'               => 'words',

			// Style
			'content_align'              => '',
			'page_builder'               => '',
			'wrapper_id'                 => '',
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

$posts_per_page = $count['size'];

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $posts_per_page,
);

if ( $post_ids ) {
	$args['post__in'] = $post_ids;
	$orderby          = 'post__in';
}

if ( $categories ) {
	$cat_arr = $categories;
	if ( isset( $cat_arr[0] ) && is_numeric( trim( $cat_arr[0] ) ) ) {
		$args['cat'] = $categories;
	} else {
		$args['category_name'] = $categories;
	}
}

if ( $orderby ) {
	if ( 'likes_count' == $orderby ) {
		$args['orderby']    = 'meta_value_num';
		$args['meta_query'] = array(
			array(
				'key'     => 'udesign_post_likes',
				'compare' => 'EXISTS',
			),
		);
	} else {
		$args['orderby'] = $orderby;
	}
}
if ( $orderway ) {
	$args['order'] = $orderway;
}
if ( isset( $atts['status'] ) && 'related' == $atts['status'] ) {
	// Single builder's related posts
	$args['post_type']    = get_post_type();
	$args['post__not_in'] = array( get_the_ID() );
	$args['cat']          = array_map(
		function( $term ) {
			return $term->term_id;
		},
		get_the_category()
	);
}
$posts = new WP_Query( $args );

// Process Posts /////////////////////////////////////////////////////////////////

if ( $posts->have_posts() ) {

	$wrapper_class = array( 'posts' );
	$wrapper_attrs = '';

	// Props

	$props = array(
		'widget'       => true,
		'posts_layout' => $layout_type,
		'cpt'          => 'post',
	);

	if ( ! $follow_theme_option ) {
		$props['type']    = $post_type;
		$props['overlay'] = $overlay;
		if ( 'yes' == $excerpt_custom ) {
			$props['excerpt_length'] = $excerpt_limit;
			$props['excerpt_type']   = $excerpt_type;
		}
		// $props['read_more_label'] = alpha_widget_button_get_label( $atts, '', $read_more_label ? $read_more_label : esc_html__( 'Read More', 'alpha-core' ) );
		// $props['read_more_class'] = $read_more_class ? implode( ' ', alpha_widget_button_get_class( $atts ) ) : '';

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

	if ( 'grid' == $layout_type || 'slider' == $layout_type ) {
		$wrapper_class[] = alpha_get_col_class( $col_cnt );

	} elseif ( 'creative' == $layout_type ) {
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
				if ( ! isset( $props['repeaters']['ids'][ (int) $item['item_no'] ] ) ) {
					$props['repeaters']['ids'][ (int) $item['item_no'] ] = '';
				}
				$props['repeaters']['ids'][ (int) $item['item_no'] ]   .= ' elementor-repeater-item-' . $item['_id'];
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
	if ( in_array( $content_align, array( 'start', 'center', 'end' ) ) ) {
		$wrapper_class[] = 'text-' . $content_align;
	}

	// Load More Properties
	if ( $loadmore_type ) {
		$props['loadmore_props'] = $props; // this should be done before adding loadmore props.
		$args['cpt']             = 'post';
		$props['loadmore_args']  = $args;
		$props['posts']          = $posts;
		$props['loadmore_type']  = $loadmore_type;
		$props['loadmore_label'] = $loadmore_label;
	} else {
		$props['posts'] = 'no';
	}

	$props['wrapper_class'] = $wrapper_class;
	$props['wrapper_attrs'] = $wrapper_attrs;
	do_action( 'alpha_before_posts_loop', $props );

	alpha_get_template_part( 'posts/post', 'loop-start' );

	while ( $posts->have_posts() ) :
		$posts->the_post();
		alpha_get_template_part( 'posts/post' );
	endwhile;

	alpha_get_template_part( 'posts/post', 'loop-end' );

	do_action( 'alpha_after_posts_loop' );
}

wp_reset_postdata();
