<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Polar Chart Widget
 *
 * Alpha Widget to display Polar chart.
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Alpha_Polar_Chart_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Get a name of widget
	 *
	 * @since 1.0
	 */
	public function get_name() {
		return ALPHA_NAME . '_widget_polar_chart';
	}


	/**
	 * Get a title of widget
	 *
	 * @since 1.0
	 */
	public function get_title() {
		return esc_html__( 'Polar Chart', 'alpha-core' );
	}


	/**
	 * Get an icon of widget
	 *
	 * @since 1.0
	 */
	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-polar-chart';
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
		wp_register_script( 'alpha-polar-chart', alpha_core_framework_uri( '/widgets/polar-chart/polar-chart' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-chart-lib', 'alpha-polar-chart' );
	}


	/**
	 * Register Control
	 *
	 * @since 1.0
	 */
	public function register_controls() {

		// Dataset section
		$this->start_controls_section(
			'section_polar_chart',
			array(
				'label' => esc_html__( 'Polar Chart', 'alpha-core' ),
			)
		);

			$ds_repeater = new Repeater();

			$ds_repeater->add_control(
				'dataset_label',
				array(
					'label'       => esc_html__( 'Dataset Label', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'description' => esc_html__( 'Input the label of dataset.', 'alpha-core' ),
					'placeholder' => esc_html__( 'New Series', 'alpha-core' ),
					'default'     => esc_html__( 'New Series', 'alpha-core' ),
				)
			);

			$this->add_control(
				'chart_dataset',
				array(
					'label'       => esc_html__( 'Data Set', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $ds_repeater->get_controls(),
					'default'     => array(
						array(
							'dataset_label' => esc_html__( 'Series 1', 'alpha-core' ),
						),
						array(
							'dataset_label' => esc_html__( 'Series 2', 'alpha-core' ),
						),
					),
					'title_field' => '{{{ dataset_label }}}',
				)
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'label',
				array(
					'label'       => esc_html__( 'Label', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'description' => esc_html__( 'Set the label of each polar chart.', 'alpha-core' ),
					'placeholder' => esc_html__( 'Microsoft', 'alpha-core' ),
					'default'     => esc_html__( 'Microsoft', 'alpha-core' ),
				)
			);
			$repeater->add_control(
				'data',
				array(
					'label'       => esc_html__( 'Value', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'description' => esc_html__( 'Enter multiple values divided by comma(,) without whitespace according to the count of datasets. Example: if you have 2 datasets, input two values, 40,45. First is for dataset 1, and second is for dataset 2', 'alpha-core' ),
					'placeholder' => esc_html__( '40,50', 'alpha-core' ),
					'default'     => esc_html__( '40,50', 'alpha-core' ),
				)
			);
			$repeater->add_control(
				'chart_bg_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'description' => esc_html__( 'Set the background color of each data.', 'alpha-core' ),
					'placeholder' => '#00bcd4',
					'default'     => '#00bcd4',
				)
			);
			$repeater->add_control(
				'chart_border_width',
				array(
					'label'       => esc_html__( 'Border Width', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'description' => esc_html__( 'Set the border width of each polar chart.', 'alpha-core' ),
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 10,
						),
					),
				)
			);
			$repeater->add_control(
				'chart_border_color',
				array(
					'label'       => esc_html__( 'Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Set the border color of each chart.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'placeholder' => '#00bcd4',
					'default'     => '#00bcd4',
				)
			);

			$this->add_control(
				'chart_data',
				array(
					'label'       => esc_html__( 'Data', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'separator'   => 'before',
					'fields'      => $repeater->get_controls(),
					'default'     => array(
						array(
							'label'              => esc_html__( 'Microsoft', 'alpha-core' ),
							'data'               => esc_html__( '40,50', 'alpha-core' ),
							'chart_bg_color'     => '#00bcd4',
							'chart_border_color' => '#00bcd4',
						),
						array(
							'label'              => esc_html__( 'Sumsung', 'alpha-core' ),
							'data'               => esc_html__( '20,30', 'alpha-core' ),
							'chart_bg_color'     => '#D88989',
							'chart_border_color' => '#D88989',
						),
						array(
							'label'              => esc_html__( 'Apple', 'alpha-core' ),
							'data'               => esc_html__( '30,25', 'alpha-core' ),
							'chart_bg_color'     => '#4A37E7',
							'chart_border_color' => '#4A37E7',
						),
						array(
							'label'              => esc_html__( 'IBM', 'alpha-core' ),
							'data'               => esc_html__( '10,60', 'alpha-core' ),
							'chart_bg_color'     => '#97BBCD',
							'chart_border_color' => '#97BBCD',
						),
						array(
							'label'              => esc_html__( 'Benq', 'alpha-core' ),
							'data'               => esc_html__( '30,40', 'alpha-core' ),
							'chart_bg_color'     => '#97BBCD',
							'chart_border_color' => '#97BBCD',
						),
						array(
							'label'              => esc_html__( 'Huawei', 'alpha-core' ),
							'data'               => esc_html__( '30,60', 'alpha-core' ),
							'chart_bg_color'     => '#BAB33C',
							'chart_border_color' => '#BAB33C',
						),
					),
					'title_field' => '{{{ label }}}',
				)
			);
		$this->end_controls_section();

		// Setting Section
		alpha_elementor_chart_settings( $this );

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
					'label'       => esc_html__( 'Height of Polar Area', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'description' => esc_html__( 'Set the height of chart conatiner.', 'alpha-core' ),
					'range'       => array(
						'px' => array(
							'min' => 100,
							'max' => 1200,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .polar-chart-container' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
		$this->end_controls_section();

		// Legend
		alpha_elementor_chart_font_options( $this );
	}

	/**
	 * Get chart data.
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_chart_data() {
		$settings = $this->get_settings_for_display();

		$datum = array();

		$chart_dataset = $settings['chart_dataset'];
		$chart_data    = $settings['chart_data'];
		$i             = 0;

		foreach ( $chart_dataset as $dataset ) {
			$data = array(
				'label'            => '',
				'data'             => array(),
				'backgroundColor'  => array(),
				'borderWidth'      => array(),
				'borderColor'      => array(),
				'borderDashOffset' => array(),
			);

			$data['label'] = ! empty( $dataset['dataset_label'] ) ? $dataset['dataset_label'] : '';

			foreach ( $chart_data as $item ) {
				$data['data'][]             = ! empty( $item['data'] ) ? floatval( explode( ',', $item['data'] )[ $i ] ) : '';
				$data['backgroundColor'][]  = ! empty( $item['chart_bg_color'] ) ? alpha_rgba_hex_2_rgba_func( $item['chart_bg_color'] ) : '#fff';
				$data['borderColor'][]      = ! empty( $item['chart_border_color'] ) ? alpha_rgba_hex_2_rgba_func( $item['chart_border_color'] ) : '';
				$data['borderWidth'][]      = ! empty( $item['chart_border_width']['size'] ) ? $item['chart_border_width']['size'] : 1;
				$data['borderDashOffset'][] = 40;
			}

			$datum['datasets'][] = $data;
			$i++;
		}

		foreach ( $chart_data as $item ) {
			$datum['labels'][] = ! empty( $item['label'] ) ? $item['label'] : '';
		}

		return $datum;
	}

	/**
	 * Get Chart Options
	 *
	 * @return array
	 */
	public function get_chart_options() {
		$settings = $this->get_settings_for_display();

		$show_legend  = filter_var( $settings['show_legend'], FILTER_VALIDATE_BOOLEAN );
		$show_tooltip = filter_var( $settings['show_tooltip'], FILTER_VALIDATE_BOOLEAN );
		$show_tooltip = filter_var( $settings['show_tooltip'], FILTER_VALIDATE_BOOLEAN );

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
			'tooltip'             => array(
				'enabled' => $show_tooltip,
			),
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
		$canvas_class = 'polar-chart chart';

		$this->add_render_attribute(
			array(
				'container' => array(
					'class'        => 'polar-chart-container chart-container',
					'data-chart'   => esc_attr( json_encode( $data_chart ) ),
					'data-options' => esc_attr( json_encode( $data_options ) ),
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
