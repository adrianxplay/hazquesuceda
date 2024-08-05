<?php
defined( 'ABSPATH' ) || die;

/**
 * Creative Grid Functions
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

/**
 * Register elementor layout controls for creative grid.
 *
 * @since 1.0
 */

if ( ! function_exists( 'alpha_elementor_creative_layout_controls' ) ) {
	function alpha_elementor_creative_layout_controls( $self, $condition_key, $widget = '' ) {

		/**
		 * Using Isotope
		 */
		$self->add_control(
			'creative_mode',
			array(
				'label'       => esc_html__( 'Creative Layout', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::IMAGE_CHOOSE,
				'default'     => 1,
				'options'     => alpha_creative_preset_imgs(),
				'description' => esc_html__( 'Select any preset to suit your need  under creative grid option.​', 'alpha-core' ),
				'condition'   => array(
					$condition_key => 'creative',
				),
				'width'       => 3,
			)
		);

		$self->add_control(
			'creative_height',
			array(
				'label'       => esc_html__( 'Change Grid Height', 'alpha-core' ),
				'description' => esc_html__( 'Determine the height of the grid layout.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 600,
				),
				'range'       => array(
					'px' => array(
						'step' => 5,
						'min'  => 100,
						'max'  => 1000,
					),
				),
				'condition'   => array(
					$condition_key => 'creative',
				),
			)
		);

		$self->add_control(
			'creative_height_ratio',
			array(
				'label'       => esc_html__( 'Grid Mobile Height (%)', 'alpha-core' ),
				'description' => esc_html__( 'Determine the height of the grid layout on mobile.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 75,
				),
				'range'       => array(
					'%' => array(
						'step' => 1,
						'min'  => 30,
						'max'  => 100,
					),
				),
				'condition'   => array(
					$condition_key => 'creative',
				),
			)
		);

		$self->add_control(
			'creative_col_sp',
			array(
				'label'       => esc_html__( 'Columns Spacing', 'alpha-core' ),
				'description' => esc_html__( 'Choose spacing around each grid items.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				/**
				 * Filters the default column spacing.
				 *
				 * @since 1.0
				 */
				'options'     => array(
					'no' => array(
						'title' => esc_html__( 'No', 'alpha-core' ),
						'icon'  => 'eicon-ban',
					),
					'xs' => array(
						'title' => esc_html__( 'Extra Small', 'alpha-core' ),
						'icon'  => 'alpha-size-xs alpha-choose-type',
					),
					'sm' => array(
						'title' => esc_html__( 'Small', 'alpha-core' ),
						'icon'  => 'alpha-size-sm alpha-choose-type',
					),
					'md' => array(
						'title' => esc_html__( 'Medium', 'alpha-core' ),
						'icon'  => 'alpha-size-md alpha-choose-type',
					),
					'lg' => array(
						'title' => esc_html__( 'Large', 'alpha-core' ),
						'icon'  => 'alpha-size-lg alpha-choose-type',
					),
				),
				'condition'   => array(
					$condition_key => 'creative',
				),
			)
		);

		$self->add_responsive_control(
			'creative_custom_col_sp',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Custom Spacing', 'alpha-core' ),
				'description' => esc_html__( 'Controls the size of spacing between items.', 'alpha-core' ),
				'default'     => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'  => array(
					'px',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 60,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .grid' => '--alpha-gap: calc({{SIZE}}{{UNIT}} / 2);',
				),
				'condition'   => array(
					'creative_col_sp' => '',
				),
			)
		);

		$self->add_control(
			'grid_float',
			array(
				'label'       => esc_html__( 'Use Float Grid', 'alpha-core' ),
				'description' => esc_html__( 'The Layout will be built with only float style not using isotope plugin. This is very useful for some simple creative layouts.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					$condition_key => 'creative',
				),
			)
		);
	}
}
