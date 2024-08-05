<?php
/**
 * Events Calendar Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Alpha_Controls_Manager;

/**
 * Alpha_Events_Calendar_Elementor_Widget class
 *
 * @since 4.0.0
 */
class Alpha_Events_Calendar_Elementor_Widget extends Elementor\Widget_Base {

	/**
	 * Get the name of widget
	 *
	 * @since 4.0.0
	 */
	public function get_name() {
		return ALPHA_NAME . '_widget_events_calendar';
	}

	/**
	 * Get the title of widget
	 *
	 * @since 4.0.0
	 */
	public function get_title() {
		return esc_html__( 'Events Calendar', 'alpha-core' );
	}

	/**
	 * Get the icon of widget
	 *
	 * @since 4.0.0
	 */
	public function get_icon() {
		return 'eicon-calendar';
	}

	/**
	 * Get the category of widget
	 *
	 * @since 4.0.0
	 */
	public function get_categories() {
		return array( 'alpha_widget' );
	}

	/**
	 * Get the keywords of widget
	 *
	 * @since 4.0.0
	 */
	public function get_keywords() {
		return array( 'event', 'calendar' );
	}

	/**
	 * Register Controls of widget
	 *
	 * @since 4.0.0
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_events_calendar_layout',
			array(
				'label' => esc_html__( 'Calendar Layout', 'alpha-core' ),
			)
		);

			$this->add_control(
				'events_calendar_layout',
				array(
					'label'   => esc_html__( 'Layout', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'month',
					'options' => array(
						'month' => esc_html__( 'Month', 'alpha-core' ),
						'day'   => esc_html__( 'Day', 'alpha-core' ),
						'list'  => esc_html__( 'List', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'events_calendar_title',
				array(
					'label'   => esc_html__( 'Title', 'alpha-core' ),
					'type'    => Controls_Manager::TEXT,
					'default' => esc_html__( 'Events Calendar', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_events_calendar_general_style',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'events_calendar_background',
					'selector' => '.elementor-element-{{ID}} .tribe-events-calendar-widget',
				)
			);

			$this->add_responsive_control(
				'events_calendar_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .tribe-events-calendar-widget.tribe-events' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'events_calendar_title_margin',
				array(
					'label'      => esc_html__( 'Calendar Header Spacing', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .tribe-events .tribe-events-header--widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_events_calendar_title_style',
			array(
				'label' => esc_html__( 'Title', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'events_calendar_title_typography',
					'label'    => esc_html__( 'Title Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .tribe-events .tribe-events-calendar--header-title',
				)
			);

			$this->add_control(
				'events_calendar_title_color',
				array(
					'label'     => esc_html__( 'Title Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .tribe-events .tribe-events-calendar--header-title' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_events_calendar_datepicker_style',
			array(
				'label' => esc_html__( 'Date Picker', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'events_calendar_datepicker_heading',
				array(
					'type'  => Controls_Manager::HEADING,
					'label' => esc_html__( 'Date Picker Style', 'alpha-core' ),
				)
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'events_calendar_datepicker_typography',
					'selector' => '.elementor-element-{{ID}} .tribe-events-c-top-bar__datepicker .tribe-common-h3',
				)
			);
			$this->add_control(
				'events_calendar_datepicker_color',
				array(
					'type'      => Controls_Manager::COLOR,
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .tribe-events-c-top-bar__datepicker .tribe-common-h3' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'events_calendar_nav_heading',
				array(
					'type'  => Controls_Manager::HEADING,
					'label' => esc_html__( 'Navigation Style' ),
				)
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'events_calendar_nav_typography',
					'selector' => '.elementor-element-{{ID}} .tribe-events-c-top-bar__nav-list-item .tribe-events-c-top-bar__nav-link',
				)
			);
			$this->add_control(
				'events_calendar_nav_color',
				array(
					'label'     => esc_html__( 'Nav Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .tribe-events-c-top-bar__nav-list-item .tribe-events-c-top-bar__nav-link' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_events_calendar_weekday_style',
			array(
				'label' => esc_html__( 'Weekday', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'events_calendar_weekday_typography',
					'selector' => '.elementor-element-{{ID}} .tribe-events .tribe-events-calendar-month__header-column-title',
				)
			);

			$this->add_control(
				'events_calendar_weekday_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .tribe-events-calendar-month__header-column-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'events_calendar_weekday_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .tribe-events-calendar-month__header-column' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'events_calendar_weekday_padding',
				array(
					'label'      => esc_html__( 'Weekday Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .tribe-events .tribe-events-calendar-month__header-column' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_events_calendar_day_style',
			array(
				'label' => esc_html__( 'Day', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'events_calendar_day_typography',
					'selector' => '.elementor-element-{{ID}} .compare-open',
				)
			);

			$this->add_control(
				'events_calendar_day_color',
				array(
					'label'    => esc_html__( 'Color', 'alpha-core' ),
					'type'     => Controls_Manager::COLOR,
					'selector' => '.elementor-element-{{ID}}',
				)
			);

			$this->add_control(
				'events_calendar_day_bg_color',
				array(
					'label'    => esc_html__( 'Background Color', 'alpha-core' ),
					'type'     => Controls_Manager::COLOR,
					'selector' => '.elementor-element-{{ID}}',
				)
			);

			$this->add_control(
				'events_calendar_day_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .tribe-events .tribe-events-calendar-month__day' => 'border-color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Render template
	 *
	 * @since 4.0.0
	 */
	protected function render() {
		$atts = $this->get_settings_for_display();

		require ALPHA_CORE_INC . '/widgets/events-calendar/render-events-calendar-elementor.php';
	}

	/**
	 * Add elementor-widget-container class to widget
	 *
	 * @since 4.0.0
	 */
	public function before_render() {
		$atts = $this->get_settings_for_display();
		?>
		<div <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
		<?php
	}
}
