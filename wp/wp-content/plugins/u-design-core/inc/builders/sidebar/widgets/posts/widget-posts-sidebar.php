<?php
/**
 * Alpha price filter sidebar widget
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

class Alpha_Posts_Sidebar_Widget extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget-posts',
			'description' => esc_html__( 'Display widget typed posts.', 'alpha-core' ),
		);

		$control_ops = array( 'id_base' => 'posts-widget' );

		parent::__construct( 'posts-widget', ALPHA_DISPLAY_NAME . esc_html__( ' - Posts', 'alpha-core' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract( $args ); // @codingStandardsIgnoreLine

		$title = '';
		if ( isset( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
		}

		$output = '';
		echo alpha_strip_script_tags( $before_widget );

		if ( $title ) {
			echo alpha_strip_script_tags( $before_title ) . sanitize_text_field( $title ) . alpha_strip_script_tags( $after_title );
		}

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => isset( $instance['count'] ) ? $instance['count'] : 6,
			'orderby'        => isset( $instance['orderby'] ) ? $instance['orderby'] : '',
			'order'          => isset( $instance['orderway'] ) ? $instance['orderway'] : 'ASC',
		);

		$posts = new WP_Query( $args );

		$count = count( $posts->posts );

		if ( $posts->have_posts() ) {
			if ( ! isset( $instance['slide_cnt'] ) ) {
				$instance['slide_cnt'] = 6;
			}
			if ( $instance['slide_cnt'] < $count ) {
				wp_enqueue_script( 'swiper' );
			}
			$props['cpt']            = 'post';
			$props['posts_layout']   = 'slider';
			$props['col_cnt']        = array( 'lg' => 1 );
			$props['widget']         = true;
			$props['type']           = 'widget';
			$props['show_info']      = array( 'image', 'date' );
			$props['overlay']        = '';
			$props['excerpt_length'] = '';
			$props['excerpt_type']   = '';
			$props['thumbnail_size'] = 'thumbnail';
			$props['row_cnt']        = isset( $instance['slide_cnt'] ) ? $instance['slide_cnt'] : 3;
			$props['wrapper_class']  = array( alpha_get_slider_class() . ' post-sidebar-widget' );
			$props['wrapper_attrs']  = ' data-slider-options="' . esc_attr(
				json_encode(
					alpha_get_slider_attrs(
						array(
							'show_nav' => true,
							'nav_pos'  => 'top',
						),
						array( 'lg' => 1 )
					)
				)
			) . '"';

			echo '<div><div>';

			do_action( 'alpha_before_posts_loop', $props );

			alpha_get_template_part( 'posts/post', 'loop-start' );

			while ( $posts->have_posts() ) :
				$posts->the_post();
				alpha_get_template_part( 'posts/post' );
			endwhile;

			alpha_get_template_part( 'posts/post', 'loop-end' );

			echo '</div></div>';

			do_action( 'alpha_after_posts_loop' );

			wp_reset_postdata();
		}

		echo alpha_strip_script_tags( $after_widget );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = $new_instance['title'];
		$instance['orderby']   = $new_instance['orderby'];
		$instance['orderway']  = $new_instance['orderway'];
		$instance['count']     = $new_instance['count'];
		$instance['slide_cnt'] = $new_instance['slide_cnt'];

		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title'     => '',
			'orderby'   => '',
			'orderway'  => 'ASC',
			'count'     => '6',
			'slide_cnt' => '3',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<strong><?php esc_html_e( 'Title', 'alpha-core' ); ?>:</strong>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo isset( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : ''; ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
				<strong><?php esc_html_e( 'Order By', 'alpha-core' ); ?>:</strong>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" value="<?php echo isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : ''; ?>">
					<?php

					echo '<option value=""' . selected( $instance['orderby'], '' ) . '>' . esc_html__( 'Default', 'alpha-core' );
					echo '<option value="ID"' . selected( $instance['orderby'], 'ID' ) . '>' . esc_html__( 'ID', 'alpha-core' );
					echo '<option value="title"' . selected( $instance['orderby'], 'title' ) . '>' . esc_html__( 'Title', 'alpha-core' );
					echo '<option value="date"' . selected( $instance['orderby'], 'date' ) . '>' . esc_html__( 'Date', 'alpha-core' );
					echo '<option value="modified"' . selected( $instance['orderby'], 'modified' ) . '>' . esc_html__( 'Modified', 'alpha-core' );
					echo '<option value="author"' . selected( $instance['orderby'], 'author' ) . '>' . esc_html__( 'Author', 'alpha-core' );
					echo '<option value="comment_count"' . selected( $instance['orderby'], 'comment_count' ) . '>' . esc_html__( 'Comment count', 'alpha-core' );

					?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderway' ) ); ?>">
				<strong><?php esc_html_e( 'Order Way', 'alpha-core' ); ?>:</strong>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderway' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderway' ) ); ?>" value="<?php echo isset( $instance['orderway'] ) ? esc_attr( $instance['orderway'] ) : ''; ?>">
					<?php
					echo '<option value="ASC"' . selected( $instance['orderway'], 'ASC' ) . '>' . esc_html__( 'Ascending', 'alpha-core' ) . '</option>';
					echo '<option value="DESC"' . selected( $instance['orderway'], 'DESC' ) . '>' . esc_html__( 'Descending', 'alpha-core' ) . '</option>';
					?>
				</select>
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">
				<strong><?php esc_html_e( 'Total Count', 'alpha-core' ); ?>:</strong>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $instance['count'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slide_cnt' ) ); ?>">
				<strong><?php esc_html_e( 'Count per Slide', 'alpha-core' ); ?>:</strong>
				<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slide_cnt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slide_cnt' ) ); ?>" value="<?php echo esc_attr( $instance['slide_cnt'] ); ?>" />
			</label>
		</p>
		<?php
	}
}
