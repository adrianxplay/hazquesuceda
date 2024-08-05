<?php
/**
 * Post Archive
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

global $alpha_layout;

$wrapper_class = alpha_get_loop_prop( 'wrapper_class', array() );
$wrapper_attrs = alpha_get_loop_prop( 'wrapper_attrs', '' );

$cpt            = alpha_get_loop_prop( 'cpt' );
$posts_layout   = alpha_get_loop_prop( 'posts_layout' );
$post_type      = alpha_get_loop_prop( 'type' );
$overlay        = alpha_get_loop_prop( 'overlay' );
$col_cnt        = alpha_get_loop_prop( 'col_cnt' );
$loop_classes   = alpha_get_loop_prop( 'loop_classes', array() );
$loop_classes[] = 'post';

// For timeline blog layout
global $prev_post_month, $prev_post_year, $post_count;
$prev_post_year  = null;
$prev_post_month = null;
$post_count      = 1;

$wrapper_class[] = 'posts';

if ( $cpt && 'post' != $cpt ) {
	$wrapper_class[] = $cpt . 's';
}

if ( ! alpha_get_loop_prop( 'widget' ) ) {
	// $show_info = array( 'image', 'category', 'author', 'date', 'content' );

	if ( alpha_get_loop_prop( 'related' ) ) {
		// $show_info = array( 'image', 'category', 'author', 'date', 'readmore' );
	} else {
		$posts_column = alpha_get_loop_prop( 'posts_column' );
		$image_size   = alpha_get_loop_prop( 'image_size' );
		if ( $posts_column > 1 ) {
			if ( 2 == $posts_column ) {
				$image_size = 'alpha-post-medium';
			}
			// $show_info[] = 'comment';
		} else {
			$loop_classes[] = 'post-lg';
			$image_size     = 'full';
		}
		if ( 'creative' == $posts_layout ) {
			$image_size = 'large';
		}
		alpha_set_loop_prop( 'image_size', $image_size );
	}
}

if ( ! alpha_get_loop_prop( 'related' ) && isset( $posts_column ) && 1 == $posts_column && 'timeline' != $posts_layout && 'post' == $cpt ) {
	$post_type = 'intro';
	alpha_set_loop_prop( 'type', $post_type );
	alpha_set_loop_prop( 'excerpt_type', 'character' );
	alpha_set_loop_prop( 'excerpt_length', 200 );
}

if ( 'intro' == $post_type ) {
	alpha_set_loop_prop( 'read_more_class', 'btn-dark btn-outline btn-md' );
	alpha_set_loop_prop( 'read_more_label', esc_html__( 'Read More', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>' );
} elseif ( 'modern' == $post_type ) {
	alpha_set_loop_prop( 'read_more_class', 'btn-link btn-underline btn-primary' );
	alpha_set_loop_prop( 'read_more_label', esc_html__( 'Read More', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>' );
}

if ( ! empty( $overlay ) ) {
	$loop_classes[] = alpha_get_overlay_class( $overlay );
}
if ( $post_type && ( 'list' != $post_type || 'creative' != $posts_layout ) ) {
	$loop_classes[] = ' ' . $cpt . '-' . $post_type;
}

alpha_set_loop_prop( 'loop_classes', $loop_classes );


// One column - List, Intro
if ( 'list' == $post_type ) {
	$wrapper_class[] = 'list-type-posts';
}

// Layouts - Grid, Masonry, Slider, Creative Grid(widget)
if ( 'slider' == $posts_layout ) {
	if ( ! alpha_get_loop_prop( 'widget' ) ) {

		$wrapper_class[] = alpha_get_slider_class();
		/**
		 * Filters the option of related slider.
		 *
		 * @since 1.0
		 */
		$wrapper_attrs = ' data-slider-options="' . esc_attr(
			json_encode(
				alpha_get_slider_attrs(
					array(
						'box_shadow_slider' => 'yes',
						'show_dots'         => 'yes',
						'dots_pos'          => 'close',
						'dots_skin'         => alpha_get_option( 'dark_skin' ) ? 'grey' : 'dark',
					),
					alpha_get_loop_prop( 'col_cnt' )
				)
			)
		) . '"';
	}
} elseif ( 'timeline' == $posts_layout ) {
	$wrapper_class[] = 'posts-timeline';
} elseif ( 'masonry' == $posts_layout && ! empty( $is_archive ) ) {
	$wrapper_class[] = 'grid';
	$wrapper_class[] = 'masonry';
	$wrapper_attrs   = " data-grid-options='" . json_encode( array( 'masonry' => array( 'horizontalOrder' => true ) ) ) . "'";
	wp_enqueue_script( 'isotope-pkgd' );
}

if ( 'creative' != $posts_layout ) {
	$wrapper_class[] = trim( alpha_get_col_class( $col_cnt ) );
}

// Loadmore Button or Pagination
$posts_query = alpha_get_loop_prop( 'posts' );

if ( empty( $posts_query ) ) {
	$posts_query = $GLOBALS['wp_query'];
}

if ( alpha_get_option( 'archive_ajax' ) ) {
	if ( ( isset( $posts_query->max_num_pages ) && 1 < $posts_query->max_num_pages ) || alpha_get_loop_prop( 'is_filter_cat' ) ) {
		if ( 'scroll' == alpha_get_loop_prop( 'loadmore_type' ) ) {
			$wrapper_class[] = 'load-scroll';
		}

		$wrapper_attrs .= ' ' . alpha_loadmore_attributes(
			$cpt,
			alpha_get_loop_prop( 'loadmore_props' ),
			alpha_get_loop_prop( 'loadmore_args' ),
			'page',
			$posts_query->max_num_pages
		);
	}

	if ( 'scroll' == alpha_get_loop_prop( 'loadmore_type' ) || 'button' == alpha_get_loop_prop( 'loadmore_type' ) || alpha_get_loop_prop( 'is_filter_cat' ) ) {
		wp_enqueue_script( 'alpha-ajax' );
	}
}

// Category Filter
if ( ! empty( $is_archive ) && ! is_search() && ( -1 === alpha_get_option( $cpt . 's_filter', -1 ) ? alpha_get_option( 'posts_filter' ) : alpha_get_option( $cpt . 's_filter' ) ) ) {
	$loop_classes[] = 'grid-item';

	wp_enqueue_style( 'alpha-tab', ALPHA_CORE_INC_URI . '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );

	alpha_get_template_part( 'posts/post', 'filter' );
}

// Print Posts
$wrapper_class = apply_filters( 'alpha_post_loop_wrapper_classes', $wrapper_class );

echo '<div class="' . esc_attr( implode( ' ', $wrapper_class ) ) . '"' . $wrapper_attrs . ( $post_type ? ' data-post-type="' . $post_type . '"' : '' ) . '>';

if ( 'timeline' == $posts_layout ) {
	echo '<section class="timeline">';
}
