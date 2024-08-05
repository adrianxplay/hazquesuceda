<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Events Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Events Selector
			'show_past_events'           => '',
			'event_ids'                  => '',
			'event_cat'                  => '',
			'count'                      => array( 'size' => 10 ),
			'orderway'                   => 'ASC',

			// Events Layout
			'row_cnt'                    => 1,
			'col_cnt'                    => array( 'size' => 4 ),
			'col_sp'                     => '',
			'layout_type'                => 'grid',
			'creative_cols'              => '',
			'creative_cols_tablet'       => '',
			'creative_cols_mobile'       => '',
			'thumbnail_size'             => 'woocommerce_thumbnail',
			'thumbnail_custom_dimension' => '',
			'items_list'                 => array(),

			// Event Type
			'event_type'                 => 'event-1',
			'show_shadow'                => 'yes',
			'date_skin'                  => 'light',
			'date_position'              => 'bottom',
			'overlay'                    => '',
			'excerpt_length'             => 15,
			'excerpt_by'                 => 'words',
		),
		$atts
	)
);

$posts_per_page = $count['size'];

$args = array(
	'post_type'      => 'tribe_events',
	'posts_per_page' => $posts_per_page,
	'order'          => $orderway,
	'orderby'        => 'meta_value',
	'meta_key'       => '_EventStartDate', // phpcs:ignore WordPress.DB.SlowDBQuery
	'meta_type'      => 'DATETIME',
);

if ( $event_ids ) {
	$args['post__in'] = $event_ids;
	$orderby          = 'post__in';
}

if ( 'yes' != $show_past_events ) {
	$current_time       = current_time( 'Y-m-d H:i:s' );
	$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		'relation' => 'OR',
		array(
			'key'     => '_EventStartDate',
			'value'   => $current_time,
			'compare' => '>=',
		),
		array(
			'key'     => '_EventEndDate',
			'value'   => $current_time,
			'compare' => '>=',
		),
	);

} else {
	$args['eventDisplay'] = 'custom';
}

if ( $event_cat ) {
	$args['tax_query'] = [
		array(
			'taxonomy' => 'tribe_events_cat',
			'field'    => 'id',
			'terms'    => array_map( 'trim', $event_cat ),
		),
	];
}

$args['post_status'] = 'publish';

$events = new WP_Query( $args );

if ( $events->have_posts() ) {

	$col_cnt          = alpha_elementor_grid_col_cnt( $atts );
	$grid_space_class = alpha_get_grid_space_class( $atts );

	if ( $grid_space_class ) {
		$extra_class[] = $grid_space_class;
	}

	if ( 'grid' == $layout_type || 'slider' == $layout_type ) {
		$extra_class[] = alpha_get_col_class( $col_cnt );

	} elseif ( 'creative' == $layout_type ) {
		if ( is_array( $items_list ) ) {
			$extra_class[] = 'row creative-grid';
			if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
				$extra_class[] = 'editor-mode';
			}
			$props['repeaters'] = array(
				'ids'    => array(),
				'images' => array(),
			);
			foreach ( $items_list as $item ) {
				$props['repeaters']['ids'][ (int) $item['item_no'] ]    = 'elementor-repeater-item-' . $item['_id'];
				$props['repeaters']['images'][ (int) $item['item_no'] ] = $item['item_thumb_size'];
			}
			$props['post_idx'] = 0;
		}
	}

	$extra_attrs = '';
	if ( 'slider' == $layout_type ) {
		$extra_class[] = alpha_get_slider_class( $atts );
		$extra_attrs  .= ' data-slider-options="' . esc_attr(
			json_encode(
				alpha_get_slider_attrs( $atts, $col_cnt )
			)
		) . '"';

		$props['row_cnt'] = $row_cnt;
		if ( 1 < $row_cnt ) {
			$props['post_idx'] = 0;
		}
	}
	$extra_class[] = 'events-' . $event_type;

	echo '<div class="' . esc_attr( implode( ' ', apply_filters( 'alpha_post_loop_wrapper_classes', $extra_class ) ) ) . '"' . alpha_escaped( $extra_attrs ) . '>';

	$wrap_class   = 'event-wrap';
	$event_class  = 'event event-' . $event_type;
	$event_class .= ' ' . alpha_get_overlay_class( $overlay );
	$event_class .= 'yes' == $show_shadow ? ' event-shadow' : '';
	if ( $date_position ) {
		$event_class .= ' calendar-' . $date_position;
	}
	if ( 'event-1' != $event_type && 'event-2' != $event_type ) {
		$event_class .= ' event-horizontal';
	}

	while ( $events->have_posts() ) {
		$events->the_post();
		global $post;

		$wrap_attr = '';
		$add_class = '';

		if ( 'creative' == $layout_type ) {
			$idx = $props['post_idx'];
			if ( isset( $props['repeaters']['ids'][0] ) ) {
				$add_class .= ' ' . $props['repeaters']['ids'][0];
			}
			if ( isset( $props['repeaters']['ids'][ $idx + 1 ] ) ) {
				$add_class .= ' ' . $props['repeaters']['ids'][ $idx + 1 ];
			}
			if ( isset( $props['repeaters']['images'][ $idx + 1 ] ) ) {
				$g_image_size           = $atts['thumbnail_size'];
				$atts['thumbnail_size'] = $props['repeaters']['images'][ $idx + 1 ];
			}
			$add_class .= ' grid-item-' . ( $idx + 1 ) . '';
			$wrap_attr .= ' data-grid-idx="' . ( $idx + 1 ) . '"';
			$props['post_idx'] ++;
		}
		?>        
		<div class="<?php echo esc_attr( $wrap_class . $add_class ); ?>"<?php echo ! $wrap_attr ? '' : $wrap_attr; ?>>
			<div class="<?php echo esc_attr( $event_class ); ?>">
			<?php

			/**
			 * Hook: alpha_before_event_featured_image.
			 *
			 * @hooked Alpha_Tribe_Events::before_event_featured_image() - 10
			 */
			do_action( 'alpha_before_event_featured_image', $atts );
			/**
			 * Hook: alpha_event_featured_image.
			 *
			 * @hooked Alpha_Tribe_Events::event_featured_image() - 10
			 */
			do_action( 'alpha_event_featured_image', $atts );
			/**
			 * Hook: alpha_before_event_content.
			 *
			 * @hooked Alpha_Tribe_Events::before_event_content() - 10
			 */
			do_action( 'alpha_before_event_content', $atts );
			/**
			 * Hook: alpha_event_content.
			 *
			 * @hooked Alpha_Tribe_Events::event_content() - 10
			 */
			do_action( 'alpha_event_content', $atts );
			/**
			 * Hook: alpha_after_event_content.
			 *
			 * @hooked Alpha_Tribe_Events::after_event_content() - 10
			 */
			do_action( 'alpha_after_event_content', $atts );
			?>
			</div>
		</div>
		<?php
	}

	echo '</div>';
}

wp_reset_postdata();
