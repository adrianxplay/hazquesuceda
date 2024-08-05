<?php
/**
 * Alpha_Child_Pages_Sidebar_Widget class
 *
 * @since 4.0
 */

class Alpha_LMS_Course_Categories_Sidebar_Widget extends WP_Widget {

	/**
	 * Sets up a new Pages widget instance.
	 *
	 * @since 4.0
	 */

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-lms-course-categories',
			'description' => esc_html__( 'Display course categories', 'alpha-core' ),
		);

		$control_ops = array( 'id_base' => 'lms-course-categories-widget' );

		parent::__construct( 'lms-course-categories-widget', ALPHA_DISPLAY_NAME . esc_html__( ' - Course Categories', 'alpha-core' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		?>

		<ul>
			<?php
			wp_list_categories(
				apply_filters(
					'alpha_course_categories_args',
					array(
						'show_count'   => $instance['show_course_count'],
						'hierarchical' => $instance['show_hierarchy'],
						'taxonomy'     => 'course_category',
						'hide_empty'   => true,
						'title_li'     => false,
					),
					$instance
				)
			);
			?>
		</ul>

		<?php

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Pages widget instance.
	 *
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']             = sanitize_text_field( $new_instance['title'] );
		$instance['show_course_count'] = $new_instance['show_course_count'];
		$instance['show_hierarchy']    = $new_instance['show_hierarchy'];

		return $instance;
	}

	/**
	 * Outputs the settings form for the Pages widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Defaults.
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'             => esc_html__( 'Course Categories', 'alpha-core' ),
				'show_course_count' => true,
				'show_hierarchy'    => true,
			)
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alpha-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_course_count'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_course_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_course_count' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_course_count' ) ); ?>"><?php esc_html_e( 'Show Course Count', 'alpha-core' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_hierarchy'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_hierarchy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_hierarchy' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_hierarchy' ) ); ?>"><?php esc_html_e( 'Show hierarchy', 'alpha-core' ); ?></label>
		</p>
		<?php
	}

}
