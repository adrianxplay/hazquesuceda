<?php
/**
 * Events Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Alpha_Controls_Manager;

class Alpha_Events_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_events';
	}

	public function get_title() {
		return esc_html__( 'Events', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-event';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'event', 'calendar' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-events', ALPHA_CORE_INC_URI . '/widgets/events/events' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		return array( 'alpha-events' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_events_layout',
			array(
				'label' => esc_html__( 'Events Layout', 'alpha-core' ),
			)
		);

			$this->add_control(
				'layout_type',
				array(
					'label'   => esc_html__( 'Events Layout', 'alpha-core' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'grid',
					'options' => array(
						'grid'     => array(
							'title' => esc_html__( 'Grid', 'alpha-core' ),
							'icon'  => 'eicon-column',
						),
						'slider'   => array(
							'title' => esc_html__( 'Slider', 'alpha-core' ),
							'icon'  => 'eicon-slider-3d',
						),
						'creative' => array(
							'title' => esc_html__( 'Creative Grid', 'alpha-core' ),
							'icon'  => 'eicon-inner-section',
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
					'exclude' => array( 'custom' ),
					'default' => 'woocommerce_thumbnail',
				)
			);

			alpha_elementor_grid_layout_controls( $this, 'layout_type', true, 'has_rows' );
			alpha_elementor_slider_layout_controls( $this, 'layout_type' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_events_selector',
			array(
				'label' => esc_html__( 'Query', 'alpha-core' ),
			)
		);
			$this->add_control(
				'show_past_events',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'Allow to show past events.', 'alpha-core' ),
					'label'       => esc_html__( 'Display Past Events', 'alpha-core' ),
				)
			);
			$this->add_control(
				'event_ids',
				array(
					'label'       => esc_html__( 'Select Events', 'alpha-core' ),
					'description' => esc_html__( 'Choose event ids of specific events to display.', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'options'     => 'tribe_events',
					'label_block' => true,
					'multiple'    => 'true',
				)
			);

			$this->add_control(
				'event_cat',
				array(
					'label'       => esc_html__( 'Select Categories', 'alpha-core' ),
					'description' => esc_html__( 'Choose categories which include events to display.', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'options'     => 'tribe_events_cat',
					'label_block' => true,
					'multiple'    => 'true',
				)
			);

			$this->add_control(
				'count',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Event Count', 'alpha-core' ),
					'description' => esc_html__( 'Controls number of events to display or load more.', 'alpha-core' ),
					'default'     => array(
						'unit' => 'px',
						'size' => 10,
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
				)
			);

			$this->add_control(
				'orderway',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Order', 'alpha-core' ),
					'description' => esc_html__( 'Defines events ordering type: Ascending or Descending.', 'alpha-core' ),
					'default'     => 'ASC',
					'options'     => array(
						'ASC'  => esc_html__( 'Ascending', 'alpha-core' ),
						'DESC' => esc_html__( 'Descending', 'alpha-core' ),
					),
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_type',
			array(
				'label' => esc_html__( 'Event Type', 'alpha-core' ),
			)
		);

			$this->add_control(
				'event_type',
				array(
					'label'   => esc_html__( 'Event Type', 'alpha-core' ),
					'type'    => Alpha_Controls_Manager::IMAGE_CHOOSE,
					'default' => 'event-1',
					'options' => array(
						'event-1' => 'assets/images/events/event-1.jpg',
						'event-2' => 'assets/images/events/event-2.jpg',
						'list-1'  => 'assets/images/events/event-3.jpg',
						'list-2'  => 'assets/images/events/event-4.jpg',
						'widget'  => 'assets/images/events/event-5.jpg',
					),
					'width'   => 1,
				)
			);
			$this->add_control(
				'date_skin',
				array(
					'label'       => esc_html__( 'Date Skin', 'alpha-core' ),
					'description' => esc_html__( 'Choose color skin of date.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'light',
					'options'     => array(
						'light' => esc_html__( 'Light', 'alpha-core' ),
						'dark'  => esc_html__( 'Dark', 'alpha-core' ),
					),
				)
			);
			$this->add_control(
				'date_position',
				array(
					'label'       => esc_html__( 'Date Position', 'alpha-core' ),
					'description' => esc_html__( 'Choose position of date.', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::SELECT,
					'default'     => 'top',
					'options'     => array(
						'top'    => esc_html__( 'Top', 'alpha-core' ),
						'bottom' => esc_html__( 'Bottom', 'alpha-core' ),
					),
					'width'       => 2,
					'condition'   => array(
						'event_type' => array( 'event-1', 'event-2' ),
					),
				)
			);

			$this->add_control(
				'overlay',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Overlay', 'alpha-core' ),
					'description' => esc_html__( 'Choose overlay type to display on image.', 'alpha-core' ),
					'options'     => array(
						''           => esc_html__( 'No', 'alpha-core' ),
						'light'      => esc_html__( 'Light', 'alpha-core' ),
						'dark'       => esc_html__( 'Dark', 'alpha-core' ),
						'zoom'       => esc_html__( 'Zoom', 'alpha-core' ),
						'zoom_light' => esc_html__( 'Zoom and Light', 'alpha-core' ),
						'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha-core' ),
					),
					'condition'   => array(
						'event_type!' => 'widget',
					),
				)
			);

			$this->add_responsive_control(
				'content_align',
				array(
					'label'       => esc_html__( 'Alignment', 'alpha-core' ),
					'description' => esc_html__( 'Controls alignment of event content. Choose from Left, Center and Right.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'default'     => 'center',
					'options'     => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .event-content' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'excerpt_by',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Excerpt By', 'alpha-core' ),
					'default'   => 'words',
					'options'   => array(
						'words'     => esc_html__( 'Words', 'alpha-core' ),
						'character' => esc_html__( 'Characters', 'alpha-core' ),
					),
					'condition' => array(
						'event_type' => array( 'list-1', 'list-2' ),
					),
				)
			);

			$this->add_control(
				'excerpt_length',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => esc_html__( 'Excerpt Length', 'alpha-core' ),
					'default'   => array(
						'unit' => 'px',
						'size' => 15,
					),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 500,
						),
					),
					'condition' => array(
						'event_type' => array( 'list-1', 'list-2' ),
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_general',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_shadow',
			array(
				'label'     => esc_html__( 'Shadow Effect', 'alpha-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'event_type!' => array( 'list-1', 'widget' ),
				),
			)
		);

		$this->add_responsive_control(
			'event_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .event' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_title',
			array(
				'label' => esc_html__( 'Title', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .event-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .event-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'title_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .event-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_meta',
			array(
				'label' => esc_html__( 'Meta', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'meta_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .event-wrap .event-venue, .elementor-element-{{ID}} .event-wrap .event-schedule',
				)
			);

			$this->add_control(
				'meta_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .event-wrap .event-venue, .elementor-element-{{ID}} .event-wrap .event-schedule' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_excerpt',
			array(
				'label' => esc_html__( 'Excerpt', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'excerpt_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .event-excerpt',
				)
			);

			$this->add_control(
				'excerpt_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .event-excerpt' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'excerpt_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .event-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_event_calendar',
			array(
				'label' => esc_html__( 'Calendar', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'calendar_size',
				array(
					'type'       => Controls_Manager::SLIDER,
					'label'      => esc_html__( 'Size', 'alpha-core' ),
					'range'      => array(
						'px'  => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 200,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .post-calendar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'calendar_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-calendar' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'calendar_border',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-calendar' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'calendar_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .post-calendar' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'calendar_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .post-calendar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'heading_day',
				array(
					'label'     => esc_html__( 'Day', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'day_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .post-day',
				)
			);

			$this->add_control(
				'day_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-day' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'day_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .post-day' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'heading_month',
				array(
					'label'     => esc_html__( 'Month', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'month_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '{{WRAPPER}} .post-month',
				)
			);

			$this->add_control(
				'month_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .post-month' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'month_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'rem',
					),
					'selectors'  => array(
						'{{WRAPPER}} .post-month' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();
		alpha_elementor_slider_style_controls( $this, 'layout_type' );

	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		require ALPHA_CORE_INC . '/widgets/events/render-events-elementor.php';
	}
}
