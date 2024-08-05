<?php
/**
 * Alpha_Child_Pages_Sidebar_Widget class
 *
 * @since 4.0
 */

class Alpha_LMS_Instructors_Sidebar_Widget extends WP_Widget {

	/**
	 * Sets up a new Pages widget instance.
	 *
	 * @since 4.0
	 */

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-lms-instructors',
			'description' => esc_html__( 'Display instructors', 'alpha-core' ),
		);

		$control_ops = array( 'id_base' => 'lms-instructors-widget' );

		parent::__construct( 'lms-instructors-widget', ALPHA_DISPLAY_NAME . esc_html__( ' - Instructors', 'alpha-core' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$total_course = wp_count_posts( 'lp_course' );

		if ( ! $total_course->publish ) {
			return;
		}

		?>

		<?php
		global $wp_query, $wpdb;
		$instructor_counts = array();
		if ( is_tax( 'course_category' ) ) {
			$term_obj = get_queried_object();
			if ( $term_obj && $term_obj->term_id ) {
				$instructor_counts = $wpdb->get_results( $wpdb->prepare( "SELECT p.post_author AS instructor_id, count(p.ID) AS total FROM {$wpdb->posts} AS p INNER JOIN ( SELECT object_id FROM {$wpdb->term_relationships} INNER JOIN {$wpdb->term_taxonomy} using( term_taxonomy_id ) WHERE term_id = %d ) AS term_join ON term_join.object_id = p.ID WHERE p.post_type='lp_course' AND p.post_status='publish' GROUP BY p.post_author", (int) $term_obj->term_id ) );
			}
		} elseif ( is_post_type_archive( 'lp_course' ) || ( is_archive() && isset( $wp_query->query_vars ) && isset( $wp_query->query_vars['post_type'] ) && 'lp_course' == $wp_query->query_vars['post_type'] ) ) {
			$instructor_counts = $wpdb->get_results( "SELECT post_author AS instructor_id, count(ID) AS total FROM {$wpdb->posts} WHERE post_type='lp_course' AND post_status='publish' GROUP BY post_author" );
		}
		?>
		<?php
		if ( ! empty( $instructor_counts ) ) {
			global $post;
			?>
			<ul class="list-instructor-filter">
				<?php
				foreach ( $instructor_counts as $count_obj ) {
					if ( is_post_type_archive( 'lp_course' ) ) {
						$link = get_permalink( LP()->settings()->get( 'courses_page_id', 0 ) );
					} elseif ( is_tax( 'course_category' ) ) {
						$link = get_term_link( get_query_var( 'course_category' ), 'course_category' );
					} else {
						$queried_object = get_queried_object();
						$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
					}

					// Min/Max.
					// if ( isset( $_GET['min_price'] ) ) {
					// 	$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
					// }

					// if ( isset( $_GET['max_price'] ) ) {
					// 	$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
					// }

					// Order by.
					// if ( isset( $_GET['orderby'] ) ) {
					// 	$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
					// }
					$name = get_the_author_meta( 'user_nicename', $count_obj->instructor_id );
					$link = add_query_arg( 'author', wp_unslash( $name ), $link );
					?>
					<li class="cat-item instructor-item<?php echo ( isset( $_GET['author'] ) && $_GET['author'] == $name ) ? ' current-cat' : ''; ?>">
						<a href="<?php echo esc_url( $link ); ?>">
						<?php echo get_the_author_meta( 'display_name', $count_obj->instructor_id ); ?>
						</a>
						<?php /* translators: course count */ ?>
						<?php printf( esc_html__( '(%s)', 'alpha-core' ), $count_obj->total ); ?>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		}
		?>

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
				'title' => esc_html__( 'Course Categories', 'alpha-core' ),
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
