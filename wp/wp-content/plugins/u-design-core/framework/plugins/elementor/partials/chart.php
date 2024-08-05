<?php
/**
 * Chart Partial
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

/**
 * Register chart font options.
 *
 * @since 1.2.0
 */

if ( ! function_exists( 'alpha_elementor_chart_font_options' ) ) {
	function alpha_elementor_chart_font_options( $self, $prefix = 'legend', $condition_key = 'show_legend', $condition_value = 'true' ) {

		$self->start_controls_section(
			'section_chart_' . $prefix . '_stylel',
			array(
				'label'     => esc_html__( sprintf( '%s', ucfirst( $prefix ) ), 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$condition_key => $condition_value,
				),
			)
		);
			$self->add_control(
				$prefix . '_font_family',
				array(
					'label'       => esc_html__( 'Font Family', 'alpha-core' ),
					'type'        => Controls_Manager::FONT,
					'description' => esc_html__( 'Choose your favorite font.', 'alpha-core' ),
					'default'     => '',
				)
			);
			$self->add_control(
				$prefix . '_font_size',
				array(
					'label'       => esc_html__( 'Font Size', 'alpha-core' ),
					'description' => esc_html__( 'Set font size.', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
				)
			);

			$typo_weight_options = array(
				'' => esc_html__( 'Default', 'alpha-core' ),
			);

			foreach ( array_merge( array( 'normal', 'bold' ), range( 100, 900, 100 ) ) as $weight ) {
				$typo_weight_options[ $weight ] = ucfirst( $weight );
			}

			$self->add_control(
				$prefix . '_font_weight',
				array(
					'label'       => esc_html__( 'Font Weight', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'description' => esc_html__( 'Set the font weight.', 'alpha-core' ),
					'default'     => '',
					'options'     => $typo_weight_options,
				)
			);
			$self->add_control(
				$prefix . '_font_style',
				array(
					'label'       => esc_html__( 'Font Style', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'description' => esc_html__( 'Set the font style.', 'alpha-core' ),
					'default'     => '',
					'options'     => array(
						''        => esc_html__( 'Default', 'alpha-core' ),
						'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'alpha-core' ),
						'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'alpha-core' ),
						'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'alpha-core' ),
					),
				)
			);
			$self->add_control(
				$prefix . '_font_color',
				array(
					'label'       => esc_html__( 'Font Color', 'alpha-core' ),
					'description' => esc_html__( 'Set the color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
				)
			);
		$self->end_controls_section();
	}
}

/**
 * Register chart settings controls
 *
 * @since 1.2.0
 */

if ( ! function_exists( 'alpha_elementor_chart_settings' ) ) {
	function alpha_elementor_chart_settings( $self, $type = '' ) {
		$self->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Chart Controls', 'alpha-core' ),
			)
		);

		if ( 'bar-chart' == $type ) {
			$self->add_control(
				'show_grid',
				array(
					'label'        => esc_html__( 'Show Grid Line', 'alpha-core' ),
					'description'  => esc_html__( 'Determines whether grid(guide) lines are shown or not.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
				)
			);

			$self->add_control(
				'data_value_range',
				array(
					'label'       => esc_html__( 'Axis Range', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 10,
					'description' => esc_html__( 'Set maximum value of range that can be set for the data value', 'alpha-core' ),
					'condition'   => array(
						'show_grid' => 'true',
					),
				)
			);

			$self->add_control(
				'data_value_step',
				array(
					'label'       => esc_html__( 'Axis Unit', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 1,
					'description' => esc_html__( 'Set scale of the axis to show data value', 'alpha-core' ),
					'condition'   => array(
						'show_grid' => 'true',
					),
				)
			);

			$self->add_control(
				'show_label',
				array(
					'label'        => esc_html__( 'Show Axis Labels', 'alpha-core' ),
					'description'  => esc_html__( 'Choose whether labels should be displayed. Default currently set to Yes.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'separator'    => 'after',
					'default'      => 'true',
					'return_value' => 'true',
				)
			);
		}

			$self->add_control(
				'chart_legend_heading',
				array(
					'label' => esc_html__( 'Legend', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);
			$self->add_control(
				'show_legend',
				array(
					'label'        => esc_html__( 'Show Legend', 'alpha-core' ),
					'description'  => esc_html__( 'Enable to show legend for polar chart.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
				)
			);
			$self->add_control(
				'legend_position',
				array(
					'label'       => esc_html__( 'Position', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'top',
					'description' => esc_html__( 'Control the position of legend on chart.', 'alpha-core' ),
					'options'     => array(
						'top'    => esc_html__( 'Top', 'alpha-core' ),
						'left'   => esc_html__( 'Left', 'alpha-core' ),
						'bottom' => esc_html__( 'Bottom', 'alpha-core' ),
						'right'  => esc_html__( 'Right', 'alpha-core' ),
					),
					'condition'   => array(
						'show_legend' => 'true',
					),
				)
			);
			$self->add_control(
				'legend_reverse',
				array(
					'label'        => esc_html__( 'Reverse', 'alpha-core' ),
					'description'  => esc_html__( 'Legend would be rearranged in reverse.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'true',
					'condition'    => array(
						'show_legend' => 'true',
					),
				)
			);
			$self->add_control(
				'tooltip_heading',
				array(
					'label'     => esc_html__( 'Tooltip', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);
			$self->add_control(
				'show_tooltip',
				array(
					'label'        => esc_html__( 'Show Tooltip', 'alpha-core' ),
					'description'  => esc_html__( 'Choose whether tooltips should be displayed on hover. Default currently set to Yes.', 'alpha-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'true',
					'return_value' => 'true',
				)
			);
		$self->end_controls_section();
	}
}

