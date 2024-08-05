<?php
/**
 * Alpha_Child_Pages_Sidebar_Widget class
 *
 * @since 4.0
 */

class Alpha_Child_Pages_Sidebar_Widget extends WP_Widget {

	/**
	 * Sets up a new Pages widget instance.
	 *
	 * @since 4.0
	 */

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-child-pages',
			'description' => esc_html__( 'Display child pages of selected page', 'alpha-core' ),
		);

		$control_ops = array( 'id_base' => 'child-pages-widget' );

		parent::__construct( 'child-pages-widget', ALPHA_DISPLAY_NAME . esc_html__( ' - Child Pages', 'alpha-core' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$sortby      = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$parent_page = empty( $instance['parent_page'] ) ? '' : $instance['parent_page'];

		if ( $parent_page ) {
			$parent_page = intval( $parent_page );
		}

		if ( 'menu_order' === $sortby ) {
			$sortby = 'menu_order, post_title';
		}

		$out = wp_list_pages(
			apply_filters(
				'widget_child_pages_args',
				array(
					'echo'        => 0,
					'title_li'    => '',
					'sort_column' => $sortby,
					'child_of'    => $parent_page,
				),
				$instance
			)
		);

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( ! empty( $out ) ) {

			?>

			<ul>
				<?php echo $out; ?>
			</ul>

			<?php

		} else {
			?>
			<div><?php esc_html_e( 'No Sub Page found', 'alpha-core' ); ?></div>
			<?php
		}

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
		if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID' ), true ) ) {
			$instance['sortby'] = $new_instance['sortby'];
		} else {
			$instance['sortby'] = 'menu_order';
		}
		$instance['parent_page'] = sanitize_text_field( $new_instance['parent_page'] );

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
				'title'       => '',
				'sortby'      => 'post_title',
				'parent_page' => '',
			)
		);
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'alpha-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sortby' ) ); ?>"><?php esc_html_e( 'Sort by:', 'alpha-core' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'sortby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'sortby' ) ); ?>" class="widefat">
				<option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php esc_html_e( 'Page title', 'alpha-core' ); ?></option>
				<option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php esc_html_e( 'Page order', 'alpha-core' ); ?></option>
				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php esc_html_e( 'Page ID', 'alpha-core' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'parent_page' ) ); ?>"><?php esc_html_e( 'Parent Page:', 'alpha-core' ); ?></label>
			<input type="text" value="<?php echo esc_attr( $instance['parent_page'] ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'parent_page' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'parent_page' ) ); ?>" class="widefat" />
			<br />
			<small><?php esc_html_e( 'Please input parent page id in which has child pages.', 'alpha-core' ); ?></small>
		</p>
		<?php
	}

}
