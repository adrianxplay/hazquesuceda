<?php
/**
 * Alpha_Child_Pages_Sidebar_Widget class
 *
 * @since 4.0
 */

class Alpha_LMS_Course_Prices_Sidebar_Widget extends WP_Widget {

	/**
	 * Sets up a new Pages widget instance.
	 *
	 * @since 4.0
	 */

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-lms-course-prices',
			'description' => esc_html__( 'Display Course Prices', 'alpha-core' ),
		);

		$control_ops = array( 'id_base' => 'lms-course-prices-widget' );

		parent::__construct( 'lms-course-prices-widget', ALPHA_DISPLAY_NAME . esc_html__( ' - Course Prices', 'alpha-core' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// TODO can create custom query to optimize speed
		global $wp_query;

		// Get all courses count
		$course_query_vars               = $wp_query->query_vars;
		$course_query_vars['meta_query'] = array();
		$number_course                   = ( new WP_Query( $course_query_vars ) )->post_count;

		// Get paid courses count
		$course_query_vars['meta_query'] = array(
			array(
				'key'     => '_lp_price',
				'compare' => '!=',
				'value'   => '',
			),
		);
		$number_paid_course              = ( new WP_Query( $course_query_vars ) )->post_count;

		// Get free courses count
		$number_free_course = $number_course - $number_paid_course;

		$link = remove_query_arg( 'lp_price', set_url_scheme( 'http://' . wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		?>
			<ul class="list-price-filter">
				<?php do_action( 'alpha_before_course_filters' ); ?>
				<li class="price-item cat-item">
					<a href="<?php echo esc_url( $link ); ?>">
						<?php esc_html_e( 'All', 'alpha-core' ); ?>
					</a>
					<?php printf( esc_html__( '(%s)', 'alpha-core' ), $number_course ); ?>
				</li>
				<li class="price-item cat-item<?php echo ( isset( $_GET['lp_price'] ) && 'free' == $_GET['lp_price'] ) ? ' current-cat' : ''; ?>">
					<a href="<?php echo esc_url( add_query_arg( 'lp_price', 'free', $link ) ); ?>">
						<?php esc_html_e( 'Free', 'alpha-core' ); ?>
					</a>
					<?php printf( esc_html__( '(%s)', 'alpha-core' ), $number_free_course ); ?>
				</li>
				<li class="price-item cat-item<?php echo ( isset( $_GET['lp_price'] ) && 'paid' == $_GET['lp_price'] ) ? ' current-cat' : ''; ?>">
					<a href="<?php echo esc_url( add_query_arg( 'lp_price', 'paid', $link ) ); ?>">
						<?php esc_html_e( 'Paid', 'alpha-core' ); ?>
					</a>
					<?php printf( esc_html__( '(%s)', 'alpha-core' ), $number_paid_course ); ?>
				</li>
				<?php do_action( 'alpha_after_course_filters' ); ?>
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

		$instance['title'] = sanitize_text_field( $new_instance['title'] );

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
				'title' => esc_html__( 'Course Price', 'alpha-core' ),
			)
		);

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alpha-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php
	}

}
