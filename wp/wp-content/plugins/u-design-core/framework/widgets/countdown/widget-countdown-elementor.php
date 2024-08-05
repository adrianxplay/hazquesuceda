<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Countdown Widget
 *
 * Alpha Widget to display countdown.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

class Alpha_Countdown_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-countdown';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'countdown', 'counter', 'timer' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-countdown', alpha_core_framework_uri( '/widgets/countdown/countdown' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-countdown' );
	}

	/**
	 * Get the script depends.
	 *
	 * @since 1.0.0
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-countdown', alpha_core_framework_uri( '/widgets/countdown/countdown' . ALPHA_JS_SUFFIX ), array( 'jquery-countdown' ), ALPHA_CORE_VERSION, true );
		return array( 'jquery-countdown', 'alpha-countdown' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_countdown',
			array(
				'label' => esc_html__( 'Countdown', 'alpha-core' ),
			)
		);
		$this->add_control(
			'align',
			array(
				'label'       => esc_html__( 'Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Determine where the countdown is located, left, center or right.​', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'flex-start',
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-container' => 'justify-content: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'type',
			array(
				'label'       => esc_html__( 'Type', 'alpha-core' ),
				'description' => esc_html__( 'Select countdown type from block and inline types.​', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'block',
				'options'     => array(
					'block'  => esc_html__( 'Block', 'alpha-core' ),
					'inline' => esc_html__( 'Inline', 'alpha-core' ),
				),
			)
		);
		$this->add_control(
			'date',
			array(
				'label'       => esc_html__( 'Target Date', 'alpha-core' ),
				'description' => esc_html__( 'Set the certain date the countdown element will count down to.', 'alpha-core' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => date( 'Y-m-d H:i:s', strtotime( '+1 day' ) ),
			)
		);
		$this->add_control(
			'timezone',
			array(
				'label'       => esc_html__( 'Timezone', 'alpha-core' ),
				'description' => esc_html__( 'Allows you to specify which timezone is used, the sites or the viewer timezone.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''              => esc_html__( 'WordPress Defined Timezone', 'alpha-core' ),
					'user_timezone' => esc_html__( 'User System Timezone', 'alpha-core' ),
				),
			)
		);
		$this->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Label', 'alpha-core' ),
				'description' => esc_html__( 'Set label text.​', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Offer Ends In',
				'condition'   => array(
					'type' => 'inline',
				),
			)
		);
		$this->add_control(
			'label_type',
			array(
				'label'       => esc_html__( 'Unit Type', 'alpha-core' ),
				'description' => esc_html__( 'Select time unit type from full and short. The default type is the full type.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''      => esc_html__( 'Full', 'alpha-core' ),
					'short' => esc_html__( 'Short', 'alpha-core' ),
				),
				'condition'   => array(
					'type' => 'block',
				),
			)
		);
		$this->add_control(
			'label_pos',
			array(
				'label'       => esc_html__( 'Unit Position', 'alpha-core' ),
				'description' => esc_html__( 'Select unit position from inner, outer and custom. The default position is inner.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''       => esc_html__( 'Inner', 'alpha-core' ),
					'outer'  => esc_html__( 'Outer', 'alpha-core' ),
					'custom' => esc_html__( 'Custom', 'alpha-core' ),
				),
				'condition'   => array(
					'type' => 'block',
				),
			)
		);

		$this->add_control(
			'label_dimension',
			array(
				'label'       => esc_html__( 'Custom Position', 'alpha-core' ),
				'description' => esc_html__( 'Controls custom poistion of unit.​', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -50,
						'max'  => 50,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown .countdown-period' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'type'      => 'block',
					'label_pos' => 'custom',
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Units', 'alpha-core' ),
				'description' => esc_html__( 'Allows to show or hide the amount of time aspects used in the countdown element.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => array(
					'D',
					'H',
					'M',
					'S',
				),
				'options'     => array(
					'Y' => esc_html__( 'Year', 'alpha-core' ),
					'O' => esc_html__( 'Month', 'alpha-core' ),
					'W' => esc_html__( 'Week', 'alpha-core' ),
					'D' => esc_html__( 'Day', 'alpha-core' ),
					'H' => esc_html__( 'Hour', 'alpha-core' ),
					'M' => esc_html__( 'Minute', 'alpha-core' ),
					'S' => esc_html__( 'Second', 'alpha-core' ),
				),
			)
		);
		$this->add_control(
			'hide_split',
			array(
				'label'       => esc_html__( 'Hide Separator', 'alpha-core' ),
				'description' => esc_html__( 'Allows you to show or hide the splitters between time amounts.​', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'type' => 'block',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_dimension',
			array(
				'label' => esc_html__( 'Dimension', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'       => esc_html__( 'Item Padding', 'alpha-core' ),
				'description' => esc_html__( 'Controls the padding of each countdown section.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
					'em',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array(
					'type' => 'block',
				),
			)
		);

		$this->add_responsive_control(
			'item_spacing',
			array(
				'label'       => esc_html__( 'Item Spacing (px)', 'alpha-core' ),
				'description' => esc_html__( 'Controls spacing of each countdown section.​', 'alpha-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '20',
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown' => '--alpha-countdown-section-gap: {{VALUE}}px;',
				),
				'condition'   => array(
					'type' => 'block',
				),
			)
		);

		$this->add_control(
			'label_margin',
			array(
				'label'      => esc_html__( 'Label Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .countdown-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'type' => 'inline',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_typography',
			array(
				'label' => esc_html__( 'Typography', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'countdown_label',
				'label'     => esc_html__( 'Label', 'alpha-core' ),
				'selector'  => '.elementor-element-{{ID}} .countdown-label',
				'condition' => array( 'type' => 'inline' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'countdown_amount',
				'label'    => esc_html__( 'Amount', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .countdown-container .countdown-amount',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'countdown_unit',
				'label'     => esc_html__( 'Unit', 'alpha-core' ),
				'selector'  => '.elementor-element-{{ID}} .countdown-period',
				'condition' => array( 'type' => 'block' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'countdown_separator',
				'label'     => esc_html__( 'Separator', 'alpha-core' ),
				'selector'  => '.elementor-element-{{ID}} .countdown-section:not(:last-child):after',
				'condition' => array(
					'type'       => 'block',
					'hide_split' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_color',
			array(
				'label' => esc_html__( 'Color', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'countdown_section_color',
			array(
				'label'       => esc_html__( 'Section Background', 'alpha-core' ),
				'description' => esc_html__( 'Controls the backgorund color of the countdown section.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-section' => 'background: {{VALUE}};',
				),
				'condition'   => array(
					'type' => 'block',
				),
			)
		);

		$this->add_control(
			'countdown_label_color',
			array(
				'label'       => esc_html__( 'Label', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the countdown label.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-label'  => 'color: {{VALUE}};',
				),
				'condition'   => array( 'type' => 'inline' ),
			)
		);

		$this->add_control(
			'countdown_amount_color',
			array(
				'label'       => esc_html__( 'Amount', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the countdown amount.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'countdown_unit_color',
			array(
				'label'       => esc_html__( 'Unit', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the countdown unit.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-period' => 'color: {{VALUE}};',
				),
				'condition'   => array( 'type' => 'block' ),
			)
		);

		$this->add_control(
			'countdown_separator_color',
			array(
				'label'       => esc_html__( 'Separator', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the countdown separator.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .countdown-section:after' => 'color: {{VALUE}};',
				),
				'condition'   => array(
					'type'       => 'block',
					'hide_split' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_border',
			array(
				'label'     => esc_html__( 'Border', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'type' => 'block',
				),
			)
		);

		$this->add_control(
			'border',
			array(
				'label'     => _x( 'Border Type', 'Border Control', 'alpha-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'alpha-core' ),
					'solid'  => _x( 'Solid', 'Border Control', 'alpha-core' ),
					'double' => _x( 'Double', 'Border Control', 'alpha-core' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'alpha-core' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'alpha-core' ),
					'groove' => _x( 'Groove', 'Border Control', 'alpha-core' ),
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .countdown-section' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border-width',
			array(
				'label'      => _x( 'Width', 'Border Control', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => array(
					'.elementor-element-{{ID}} .countdown-section' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.elementor-element-{{ID}} .countdown-section:not(:last-child):after' => 'padding-left: {{LEFT}}{{UNIT}};padding-right: {{RIGHT}}{{UNIT}}',
					'.elementor-element-{{ID}} .countdown.outer-period .countdown-period' => 'padding-top: {{BOTTOM}}{{UNIT}};',
				),
				'condition'  => array(
					'border!' => '',
				),
				'responsive' => true,
			)
		);

		$this->add_control(
			'border-color',
			array(
				'label'     => _x( 'Color', 'Border Control', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.elementor-element-{{ID}} .countdown-section' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'border!' => '',
				),
			)
		);

		$this->add_control(
			'border-radius',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Border-radius', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border radius of the countdown section.', 'alpha-core' ),
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .countdown-section' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/countdown/render-countdown-elementor.php' );
	}

	protected function content_template() {
		?>
		<#

		var html = '',
			className = '';	

		if ( settings.date ) {			
			className = 'countdown';

			if ( settings.label_pos ) {
				className += ' outer-period';
			}
			if ( settings.timezone ) {
				className += ' user-tz';
			}
			if ( settings.hide_split ) {
				className += ' no-split';
			}

			var format = '';
			if ( settings.date_format.length > 0 ) {
				settings.date_format.forEach(function(f) {
					format += f;
				});
			} else {
				format = settings.date_format.replace(',', '');
			}

			html += '<div class="countdown-container ' + settings.type + '-type">';

			view.addInlineEditingAttributes( 'label' );
			view.addRenderAttribute( 'label', 'class', 'countdown-label' );
			if ( 'inline' == settings.type ) {
				html += '<label ' + view.getRenderAttributeString( 'label' ) + '>' + settings.label + '</label>';
			}

			var options = {
				year: 'numeric', month: 'numeric', day: 'numeric',
				hour: 'numeric', minute: 'numeric', second: 'numeric',
				hour12: false,
			};
			html += '<div class="' + className + '" data-until="' + settings.date + '" data-relative="" ' + ('inline' == settings.type ? 'data-compact="true" ' : ' ') + ('short' == settings.label_type ? 'data-labels-short="true" ' : ' ') + 'data-format="' + format + '" data-time-now="<?php echo esc_attr( str_replace( '-', '/', current_time( 'mysql' ) ) ); ?>">';

			if ( 'block' == settings.type ) {
				html += '<span class="countdown-row countdown-show ' + settings.date_format.length + '">';
				

				formats = 'short' == settings.label_type ? {
					Y: <?php echo json_encode( esc_html__( 'Years', 'alpha-core' ) ); ?>,
					O: <?php echo json_encode( esc_html__( 'Months', 'alpha-core' ) ); ?>,
					W: <?php echo json_encode( esc_html__( 'Weeks', 'alpha-core' ) ); ?>,
					D: <?php echo json_encode( esc_html__( 'Days', 'alpha-core' ) ); ?>,
					H: <?php echo json_encode( esc_html__( 'Hours', 'alpha-core' ) ); ?>,
					M: <?php echo json_encode( esc_html__( 'Mins', 'alpha-core' ) ); ?>,
					S: <?php echo json_encode( esc_html__( 'Secs', 'alpha-core' ) ); ?>
				} : {
					Y: <?php echo json_encode( esc_html__( 'Years', 'alpha-core' ) ); ?>,
					O: <?php echo json_encode( esc_html__( 'Months', 'alpha-core' ) ); ?>,
					W: <?php echo json_encode( esc_html__( 'Weeks', 'alpha-core' ) ); ?>,
					D: <?php echo json_encode( esc_html__( 'Days', 'alpha-core' ) ); ?>,
					H: <?php echo json_encode( esc_html__( 'Hours', 'alpha-core' ) ); ?>,
					M: <?php echo json_encode( esc_html__( 'Minutes', 'alpha-core' ) ); ?>,
					S: <?php echo json_encode( esc_html__( 'Seconds', 'alpha-core' ) ); ?>
				};

				if ( settings.date_format.length ) {
					settings.date_format.forEach(function(f) {;
						html += '<span class="countdown-section"><span class="countdown-amount">00</span><span class="countdown-period">' + formats[f] + '</span></span>';
					})
				}

				html += '</span>';
			} else {
				html += '00 : 00 : 00';
			}
		}

		html += '</div>';
		html += '</div>';

		print( html );
		#>
		<?php
	}

}
