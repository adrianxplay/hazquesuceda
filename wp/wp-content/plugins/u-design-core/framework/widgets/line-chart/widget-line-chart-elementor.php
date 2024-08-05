<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Line Chart Widget
 *
 * Alpha Widget to display line chart.
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

class Alpha_Line_Chart_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Get a name of widget
	 *
	 * @since 1.0
	 */
	public function get_name() {
		return ALPHA_NAME . '_widget_line_chart';
	}


	/**
	 * Get a title of widget
	 *
	 * @since 1.0
	 */
	public function get_title() {
		return esc_html__( 'Line Chart', 'alpha-core' );
	}


	/**
	 * Get an icon of widget
	 *
	 * @since 1.0
	 */
	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-line-chart';
	}


	/**
	 * Get categories of widget
	 *
	 * @since 1.0
	 */
	public function get_categories() {
		return array( 'alpha_widget' );
	}

	/**
	 * Get style dependency
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-chart', alpha_core_framework_uri( '/widgets/bar-chart/bar-chart.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-chart' );
	}

	/**
	 * Get script dependency
	 *
	 * @since 1.0
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-line-chart', alpha_core_framework_uri( '/widgets/line-chart/line-chart' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-chart-lib', 'alpha-line-chart' );
	}


	/**
	 * Register Control
	 *
	 * @since 1.0
	 */
	public function register_controls() {

		$this->start_controls_section(
			'section_data',
			array(
				'label' => esc_html__( 'Line Chart', 'alpha-core' ),
			)
		);
			$this->add_control(
				'data_axis_label',
				array(
					'label'       => esc_html__( 'Data Labels', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Jan, Feb, Mar', 'alpha-core' ),
					'description' => esc_html__( 'Input multiple labels separated by comma(,)', 'alpha-core' ),
				)
			);
			$this->add_control(
				'data_axis_range',
				array(
					'label'       => esc_html__( 'Data Axis Range' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 10,
					'description' => esc_html__( 'Set maximum value of range that can be set for the data value', 'alpha-core' ),
				)
			);
			$this->add_control(
				'data_value_step',
				array(
					'label'       => esc_html__( 'Data Axis Unit', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 1,
					'description' => esc_html__( 'Set scale of the axis to show data value', 'alpha-core' ),
				)
			);
			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'bar_chart_tabs' );
				$repeater->start_controls_tab(
					'content_tab',
					array(
						'label' => esc_html__( 'Content', 'alpha-core' ),
					)
				);
					$repeater->add_control(
						'label',
						array(
							'label'       => esc_html__( 'Label', 'alpha-core' ),
							'type'        => Controls_Manager::TEXT,
							'description' => esc_html__( 'Set label of each bar chart', 'alpha-core' ),
						)
					);
					$repeater->add_control(
						'data',
						array(
							'label'       => esc_html__( 'Value', 'alpha-core' ),
							'type'        => Controls_Manager::TEXT,
							'description' => esc_html__( 'Enter data value separated by comma(,). Example: 2, 4, 6', 'alpha-core' ),
						)
					);
					$repeater->add_control(
						'point_style',
						array(
							'label'       => esc_html__( 'Pointer Style', 'alpha-core' ),
							'description' => esc_html__( 'Only after setting the point size in the style panel, it\'s possible to use this option.', 'alpha-core' ),
							'type'        => Controls_Manager::SELECT,
							'default'     => 'circle',
							'options'     => array(
								'circle' => esc_html__( 'Circle', 'alpha-core' ),
								'cross'  => esc_html__( 'Cross', 'alpha-core' ),
								'rect'   => esc_html__( 'Square', 'alpha-core' ),
								'star'   => esc_html__( 'Star', 'alpha-core' ),
							),
						)
					);
				$repeater->end_controls_tab();

				$repeater->start_controls_tab(
					'style_tab',
					array(
						'label' => esc_html__( 'Style', 'alpha-core' ),
					)
				);
					$repeater->add_control(
						'bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set background color of chart area', 'alpha-core' ),
						)
					);
					$repeater->add_control(
						'border_color',
						array(
							'label'       => esc_html__( 'Border Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set border color of chart area', 'alpha-core' ),
						)
					);
					$repeater->add_control(
						'point_color',
						array(
							'label'       => esc_html__( 'Point Color', 'alpha-core' ),
							'description' => esc_html__( 'Only after setting the point size in the style panel, it\'s possible to use this option.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
						)
					);
				$repeater->end_controls_tab();
			$repeater->end_controls_tab();

			$this->add_control(
				'chart_data',
				array(
					'label'       => esc_html__( 'Items', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => array(
						array(
							'label'        => esc_html__( 'Microsoft', 'alpha-core' ),
							'data'         => esc_html__( '3, 4, 8', 'alpha-core' ),
							'bg_color'     => 'rgba(221,75,57,0.4)',
							'border_color' => '#dd4b39',
							'point_color'  => '#dd4b39',
						),
						array(
							'label'        => esc_html__( 'Sumsung', 'alpha-core' ),
							'data'         => esc_html__( '4, 5, 3', 'alpha-core' ),
							'bg_color'     => 'rgba(59,89,152,0.4)',
							'border_color' => '#3b5998',
							'point_color'  => '#3b5998',
						),
						array(
							'label'        => esc_html__( 'Apple', 'alpha-core' ),
							'data'         => esc_html__( '5, 9, 5', 'alpha-core' ),
							'bg_color'     => 'rgba(85,172,238,0.4)',
							'border_color' => '#55acee',
							'point_color'  => '#55acee',
						),
					),
					'title_field' => '{{{ label }}}',
				)
			);
		$this->end_controls_section();

		// Setting Section
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Chart Controls', 'alpha-core' ),
			)
		);
			$this->add_control(
				'border_type',
				array(
					'label'       => esc_html__( 'Border Type', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'description' => esc_html__( 'Set border type of chart, Smooth, Non-Smooth', 'alpha-core' ),
					'default'     => 'smooth',
					'options'     => array(
						'smooth'     => esc_html__( 'Smooth', 'alpha-core' ),
						'non_smooth' => esc_html__( 'Non Smooth', 'alpha-core' ),
					),
				)
			);
			$this->add_control(
				'show_grid',
				array(
					'label'        => esc_html__( 'Show Grid Line', 'alpha-core' ),
					'description'  => esc_html__( 'Determines whether grid(guide) lines are shown or not.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
				)
			);
			$this->add_control(
				'show_label',
				array(
					'label'        => esc_html__( 'Show Label', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
				)
			);
			$this->add_control(
				'show_tooltip',
				array(
					'label'        => esc_html__( 'Show Tooltip', 'alpha-core' ),
					'description'  => esc_html__( 'Choose whether tooltips should be displayed on hover. Default currently set to Yes.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
					'condition'    => array(
						'point_size!' => '',
					),
				)
			);
			$this->add_control(
				'chart_legend_heading',
				array(
					'label'     => esc_html__( 'Legend', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);
			$this->add_control(
				'show_legend',
				array(
					'label'        => esc_html__( 'Show Legend', 'alpha-core' ),
					'description'  => esc_html__( 'Choose whether legend should be displayed. Default currently set to Yes.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
				)
			);
			$this->add_control(
				'legend_position',
				array(
					'label'     => esc_html__( 'Position', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'top',
					'options'   => array(
						'top'    => esc_html__( 'Top', 'alpha-core' ),
						'left'   => esc_html__( 'Left', 'alpha-core' ),
						'bottom' => esc_html__( 'Bottom', 'alpha-core' ),
						'right'  => esc_html__( 'Right', 'alpha-core' ),
					),
					'condition' => array(
						'show_legend' => 'true',
					),
				)
			);
			$this->add_control(
				'legend_reverse',
				array(
					'label'        => esc_html__( 'Reverse', 'alpha-core' ),
					'description'  => esc_html__( 'Legend will show datasets in reverse order.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'true',
					'condition'    => array(
						'show_legend' => 'true',
					),
				)
			);
		$this->end_controls_section();

		// Styles Tab
		$this->start_controls_section(
			'section_chart_general_style',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_responsive_control(
				'chart_height',
				array(
					'label'     => esc_html__( 'Height of Chart Area' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 400,
							'max' => 1200,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .line-chart-container' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'chart_border_width',
				array(
					'label'       => esc_html__( 'Border Width', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 10,
						),
					),
					'description' => esc_html__( 'Set the width of border in chart', 'alpha-core' ),
				)
			);
			$this->add_control(
				'point_size',
				array(
					'label'       => esc_html__( 'Point Size' ),
					'type'        => Controls_Manager::NUMBER,
					'description' => esc_html__( 'Set the size of point in chart. Setting 0 as value will remove point from chart', 'alpha-core' ),
					'default'     => 2,
				)
			);
			$this->add_control(
				'chart_grid_color',
				array(
					'label'       => esc_html__( 'Grid Line Color', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'default'     => 'rgba(0,0,0,0.05)',
					'condition'   => array(
						'show_grid' => 'true',
					),
					'description' => esc_html__( 'Set the color of grid line', 'alpha-core' ),
				)
			);
		$this->end_controls_section();

		// Label
		alpha_elementor_chart_font_options( $this, 'labels', 'show_label' );
		// Legend
		alpha_elementor_chart_font_options( $this, 'legend', 'show_legend' );
	}


	/**
	 * Get chart data.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_chart_data() {
		$settings = $this->get_settings_for_display();

		$datasets = array();
		$datum    = $settings['chart_data'];

		foreach ( $datum as $data ) {
			$data['label']                = ! empty( $data['label'] ) ? $data['label'] : '';
			$data['data']                 = ! empty( $data['data'] ) ? array_map( 'floatval', explode( ',', $data['data'] ) ) : '';
			$data['backgroundColor']      = ! empty( $data['bg_color'] ) ? alpha_rgba_hex_2_rgba_func( $data['bg_color'] ) : 'rgba(206,206,206,0.4)';
			$data['borderColor']          = ! empty( $data['border_color'] ) ? alpha_rgba_hex_2_rgba_func( $data['border_color'] ) : '#7a7a7a';
			$data['borderWidth']          = ( '' !== $settings['chart_border_width']['size'] ) ? $settings['chart_border_width']['size'] : 1;
			$data['pointBorderColor']     = ! empty( $data['point_color'] ) ? alpha_rgba_hex_2_rgba_func( $data['point_color'] ) : '#7a7a7a';
			$data['pointBackgroundColor'] = ! empty( $data['point_color'] ) ? alpha_rgba_hex_2_rgba_func( $data['point_color'] ) : '#7a7a7a';
			$data['pointRadius']          = $settings['point_size'] ? $settings['point_size'] : 0;
			$data['pointHoverRadius']     = $settings['point_size'] ? $settings['point_size'] : 0;
			$data['pointBorderWidth']     = 1;
			$data['pointStyle']           = ! empty( $data['point_style'] ) ? $data['point_style'] : 'circle';
			// $data['tension']              = ( 'non_smooth' == $settings['border_type'] ) ? 0.001 : 0;
			if ( 'non_smooth' == $settings['border_type'] ) {
				$data['tension'] = 0.001;
			}

			// if ( 'stepped' == $settings['border_type'] ) {
			// 	$data['stepped'] = 'true';
			// }

			$datasets[] = $data;
		}

		return $datasets;
	}


	/**
	 * Get Chart Options
	 *
	 * @return array
	 */
	public function get_chart_options() {
		$settings = $this->get_settings_for_display();

		$show_label   = filter_var( $settings['show_label'], FILTER_VALIDATE_BOOLEAN );
		$show_legend  = filter_var( $settings['show_legend'], FILTER_VALIDATE_BOOLEAN );
		$show_grid    = filter_var( $settings['show_grid'], FILTER_VALIDATE_BOOLEAN );
		$show_tooltip = filter_var( $settings['show_tooltip'], FILTER_VALIDATE_BOOLEAN );

		$options = array(
			'legend'              => array(
				'display'  => $show_legend,
				'position' => ! empty( $settings['legend_position'] ) ? $settings['legend_position'] : 'top',
				'reverse'  => filter_var( $settings['legend_reverse'], FILTER_VALIDATE_BOOLEAN ),
			),
			'maintainAspectRatio' => false,
		);
		if ( $show_tooltip ) {
			$options['tooltips'] = array(
				'mode'      => 'index',
				'intersect' => false,
			);
		}
		$legend_style = array();

		$legend_style_dictionary = array(
			'fontFamily' => 'legend_font_family',
			'fontSize'   => 'legend_font_size',
			'fontStyle'  => array( 'legend_font_style', 'legend_font_weight' ),
			'fontColor'  => 'legend_font_color',
		);

		if ( $show_legend ) {

			foreach ( $legend_style_dictionary as $style_property => $setting_name ) {

				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_font_styles( $setting_name );

					if ( ! empty( $style_value ) ) {
						$legend_style[ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$legend_style[ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$legend_style[ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}

			if ( ! empty( $legend_style ) ) {
				$options['legend']['labels'] = $legend_style;
			}
		}

		if ( $show_grid ) {
			$options['scales'] = array(
				'yAxes' => array(
					array(
						'stacked'   => false,
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
							'max'         => isset( $settings['data_axis_range'] ) ? intval( $settings['data_axis_range'] ) : 10,
							'stepSize'    => isset( $settings['data_value_step'] ) ? intval( $settings['data_value_step'] ) : 1,
						),
						'gridLines' => array(
							'drawBorder'    => false,
							'zeroLineColor' => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
							'color'         => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
						),
					),
				),
				'xAxes' => array(
					array(
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
							'max'         => isset( $settings['data_axis_range'] ) ? intval( $settings['data_axis_range'] ) : 10,
							'stepSize'    => isset( $settings['data_value_step'] ) ? intval( $settings['data_value_step'] ) : 1,
						),
						'gridLines' => array(
							'drawBorder' => false,
							'color'      => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
						),
					),
				),
			);
		} else {
			$options['scales'] = array(
				'stacked' => true,
				'yAxes'   => array(
					array(
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
							'max'         => isset( $settings['data_axis_range'] ) ? intval( $settings['data_axis_range'] ) : 10,
							'stepSize'    => isset( $settings['data_value_step'] ) ? intval( $settings['data_value_step'] ) : 1,
						),
						'gridLines' => array(
							'display' => false,
						),
					),
				),
				'xAxes'   => array(
					array(
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
							'max'         => isset( $settings['data_axis_range'] ) ? intval( $settings['data_axis_range'] ) : 10,
							'stepSize'    => isset( $settings['data_value_step'] ) ? intval( $settings['data_value_step'] ) : 1,
						),
						'gridLines' => array(
							'display' => false,
						),
					),
				),
			);
		}

		$labels_style = array();

		$labels_style_dictionary = array(
			'fontFamily' => 'labels_font_family',
			'fontSize'   => 'labels_font_size',
			'fontStyle'  => array( 'labels_font_style', 'labels_font_weight' ),
			'fontColor'  => 'labels_font_color',
		);

		if ( $show_label ) {

			foreach ( $labels_style_dictionary as $style_property => $setting_name ) {

				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_font_styles( $setting_name );

					if ( ! empty( $style_value ) ) {
						$labels_style[ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$labels_style[ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$labels_style[ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}

			if ( ! empty( $labels_style ) ) {
				$options['scales']['xAxes'][0]['ticks'] = array_merge( $options['scales']['xAxes'][0]['ticks'], $labels_style );
				$options['scales']['yAxes'][0]['ticks'] = array_merge( $options['scales']['yAxes'][0]['ticks'], $labels_style );
			}
		}

		return $options;
	}


	/**
	 * Get font style string.
	 *
	 * @param array $settings_names Settings names.
	 *
	 * @return string
	 */
	public function get_font_styles( $settings_names = array() ) {
		if ( ! is_array( $settings_names ) ) {
			return '';
		}

		$settings = $this->get_settings_for_display();

		$font_styles = array();

		foreach ( $settings_names as $setting_name ) {
			if ( ! empty( $settings[ $setting_name ] ) ) {
				$font_styles[] = $settings[ $setting_name ];
			}
		}

		if ( empty( $font_styles ) ) {
			return '';
		}

		$font_styles = array_unique( $font_styles );

		return join( ' ', $font_styles );
	}


	/**
	 * Render Widget
	 *
	 * @since 1.0
	 * @access protected
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$data_chart   = $this->get_chart_data();
		$data_options = $this->get_chart_options();
		$canvas_class = 'line-chart chart';

		$this->add_render_attribute(
			array(
				'container' => array(
					'class'         => 'line-chart-container chart-container',
					'data-settings' =>
					esc_attr(
						json_encode(
							array(
								'type'    => 'line',
								'data'    => array(
									'labels'   => explode( ',', $settings['data_axis_label'] ),
									'datasets' => $data_chart,
								),
								'options' => $data_options,
							)
						)
					),
				),
				'canvas'    => array(
					'class' => $canvas_class,
					'role'  => 'img',
				),
			)
		);

		?>
		<div <?php $this->print_render_attribute_string( 'container' ); ?>>
			<canvas <?php $this->print_render_attribute_string( 'canvas' ); ?>></canvas>
		</div>
		<?php
	}
}
