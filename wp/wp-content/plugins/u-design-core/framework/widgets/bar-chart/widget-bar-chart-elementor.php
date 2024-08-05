<?php
/**
 * Alpha Bar Chart Widget
 *
 * Alpha Widget to display bar chart.
 *
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 * @author     D-THEMES
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

class Alpha_Bar_Chart_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Get a name of widget
	 *
	 * @since 1.0.0
	 */
	public function get_name() {
		return ALPHA_NAME . '_widget_bar_chart';
	}


	/**
	 * Get a title of widget
	 *
	 * @since 1.0.0
	 */
	public function get_title() {
		return esc_html__( 'Bar Chart', 'alpha-core' );
	}


	/**
	 * Get an icon of widget
	 *
	 * @since 1.0.0
	 */
	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-bar-chart';
	}


	/**
	 * Get categories of widget
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-bar-chart', alpha_core_framework_uri( '/widgets/bar-chart/bar-chart' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-chart-lib', 'alpha-bar-chart' );
	}

	/**
	 * Register Control
	 *
	 * @since 1.0.0
	 */
	public function register_controls() {

		// Layout Section
		$this->start_controls_section(
			'section_bar_chart_layout',
			array(
				'label' => esc_html__( 'Bar Chart', 'alpha-core' ),
			)
		);
			$this->add_control(
				'type',
				array(
					'label'       => esc_html__( 'Chart Type', 'alpha-core' ),
					'description' => esc_html__( 'Select chart type.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'bar',
					'options'     => array(
						'bar'           => esc_html__( 'Vertical', 'alpha-core' ),
						'horizontalBar' => esc_html__( 'Horizontal', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'data_axis_label',
				array(
					'label'       => esc_html__( 'Data Labels', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Jan, Feb, Mar', 'alpha-core' ),
					'description' => esc_html__( 'Set labels of data axis. Write multiple labels separated by comma(,). Ex: Jan, Feb, Mar', 'alpha-core' ),
					'separator'   => 'before',
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
						'bg_hover_color',
						array(
							'label'       => esc_html__( 'Background Hover Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set background hover color of chart area', 'alpha-core' ),
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
						'border_hover_color',
						array(
							'label'       => esc_html__( 'Border Hover Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set border hover color of chart area', 'alpha-core' ),
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
							'label'              => esc_html__( 'Microsoft', 'alpha-core' ),
							'data'               => esc_html__( '3, 4, 8', 'alpha-core' ),
							'bg_color'           => 'rgba(221,75,57,0.4)',
							'bg_hover_color'     => '#dd4b39',
							'border_color'       => '#dd4b39',
							'border_hover_color' => '#dd4b39',
						),
						array(
							'label'              => esc_html__( 'Sumsung', 'alpha-core' ),
							'data'               => esc_html__( '4, 5, 3', 'alpha-core' ),
							'bg_color'           => 'rgba(59,89,152,0.4)',
							'bg_hover_color'     => '#3b5998',
							'border_color'       => '#3b5998',
							'border_hover_color' => '#3b5998',
						),
						array(
							'label'              => esc_html__( 'Apple', 'alpha-core' ),
							'data'               => esc_html__( '5, 9, 5', 'alpha-core' ),
							'bg_color'           => 'rgba(85,172,238,0.4)',
							'bg_hover_color'     => '#55acee',
							'border_color'       => '#55acee',
							'border_hover_color' => '#55acee',
						),
					),
					'title_field' => '{{{ label }}}',
				)
			);
		$this->end_controls_section();

		// Setting Section
		alpha_elementor_chart_settings( $this, 'bar-chart' );

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
					'label'       => esc_html__( 'Height of Chart Area' ),
					'description' => esc_html__( 'Controls the height of chart area.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 400,
							'max' => 1200,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .bar-chart-container' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'chart_border_width',
				array(
					'label'       => esc_html__( 'Border Width', 'alpha-core' ),
					'description' => esc_html__( 'Controls the border width of chart.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 10,
						),
					),
				)
			);
			$this->add_control(
				'chart_grid_color',
				array(
					'label'       => esc_html__( 'Grid Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of grid.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'default'     => 'rgba(0,0,0,0.05)',
					'condition'   => array(
						'show_grid' => 'true',
					),
				)
			);
		$this->end_controls_section();

		// Label
		alpha_elementor_chart_font_options( $this, 'labels', 'show_label' );
		// Legend
		alpha_elementor_chart_font_options( $this, 'legend', 'show_legend' );
	}


	/**
	 * Get options of chart widget
	 *
	 * @since 1.0.0
	 */
	public function get_options() {
		$settings = $this->get_settings_for_display();

		$show_label   = filter_var( $settings['show_label'], FILTER_VALIDATE_BOOLEAN );
		$show_tooltip = filter_var( $settings['show_tooltip'], FILTER_VALIDATE_BOOLEAN );
		$show_legend  = filter_var( $settings['show_legend'], FILTER_VALIDATE_BOOLEAN );
		$show_grid    = filter_var( $settings['show_grid'], FILTER_VALIDATE_BOOLEAN );

		$options = array(
			'tooltips'            => array(
				'enabled' => $show_tooltip,
			),
			'legend'              => array(
				'display'  => $show_legend,
				'position' => ! empty( $settings['legend_position'] ) ? $settings['legend_position'] : 'top',
				'reverse'  => filter_var( $settings['legend_reverse'], FILTER_VALIDATE_BOOLEAN ),
			),
			'maintainAspectRatio' => false,
		);

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
					$style_value = $this->get_chart_font_style_string( $setting_name );

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
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
							'max'         => isset( $settings['data_value_range'] ) ? intval( $settings['data_value_range'] ) : 10,
							'stepSize'    => isset( $settings['data_value_step'] ) ? intval( $settings['data_value_step'] ) : 1,
						),
						'gridLines' => array(
							'drawBorder' => false,
							'color'      => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
						),
					),
				),
				'xAxes' => array(
					array(
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
							'max'         => isset( $settings['data_value_range'] ) ? intval( $settings['data_value_range'] ) : 10,
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
				'yAxes' => array(
					array(
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
						),
						'gridLines' => array(
							'display' => false,
						),
					),
				),
				'xAxes' => array(
					array(
						'ticks'     => array(
							'display'     => $show_label,
							'beginAtZero' => true,
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
					$style_value = $this->get_chart_font_style_string( $setting_name );

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
	 * Get font style string
	 *
	 * @since 1.0.0
	 * @param {Array} $fonts
	 */
	public function get_chart_font_style_string( $fonts = array() ) {
		if ( ! is_array( $fonts ) ) {
			return '';
		}

		$settings = $this->get_settings_for_display();

		$font_styles = array();

		foreach ( $fonts as $font ) {
			if ( ! empty( $settings[ $font ] ) ) {
				$font_styles[] = $settings[ $font ];
			}
		}

		if ( empty( $font_styles ) ) {
			return '';
		}

		$font_styles = array_unique( $font_styles );

		return join( ' ', $font_styles );
	}

	/**
	 * Get data of chart widget
	 *
	 * @since 1.0.0
	 */
	public function get_chart_data() {
		$settings = $this->get_settings_for_display();

		$datasets   = array();
		$chart_data = $settings['chart_data'];

		foreach ( $chart_data as $item_data ) {
			$item_data['label']                = ! empty( $item_data['label'] ) ? $item_data['label'] : '';
			$item_data['data']                 = ! empty( $item_data['data'] ) ? array_map( 'floatval', explode( ',', $item_data['data'] ) ) : '';
			$item_data['backgroundColor']      = ! empty( $item_data['bg_color'] ) ? alpha_rgba_hex_2_rgba_func( $item_data['bg_color'] ) : '#fefae9';
			$item_data['hoverBackgroundColor'] = ! empty( $item_data['bg_hover_color'] ) ? alpha_rgba_hex_2_rgba_func( $item_data['bg_hover_color'] ) : '#ffefe7';
			$item_data['borderColor']          = ! empty( $item_data['border_color'] ) ? alpha_rgba_hex_2_rgba_func( $item_data['border_color'] ) : '#ffefe7';
			$item_data['hoverBorderColor']     = ! empty( $item_data['border_hover_color'] ) ? alpha_rgba_hex_2_rgba_func( $item_data['border_hover_color'] ) : '#ffefe7';
			$item_data['borderWidth']          = ( '' !== $settings['chart_border_width']['size'] ) ? $settings['chart_border_width']['size'] : 1;

			$datasets[] = $item_data;
		}

		return $datasets;
	}

	/**
	 * Render widget
	 *
	 * @since 1.0.0
	 */
	protected function render() {

		$settings     = $this->get_settings_for_display();
		$data_chart   = $this->get_chart_data();
		$data_options = $this->get_options();
		$canvas_class = 'bar-chart chart';

		$this->add_render_attribute(
			array(
				'container' => array(
					'class'         => 'bar-chart-container chart-container',
					'data-settings' =>
					esc_attr(
						json_encode(
							array(
								'type'    => $settings['type'],
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
