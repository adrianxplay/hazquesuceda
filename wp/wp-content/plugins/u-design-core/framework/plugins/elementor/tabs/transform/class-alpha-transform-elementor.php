<?php
/**
 * Alpha Elementor Transform Addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.2.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Transform_Elementor' ) ) {
	/**
	 * Alpha Elementor Transform Addon
	 *
	 * @since 1.2.0
	 */
	class Alpha_Transform_Elementor extends Alpha_Base {

		/**
		 * The Constructor.
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			// Add controls to addon tab
			add_action( 'alpha_elementor_addon_controls', array( $this, 'add_controls' ), 10, 2 );

			// Add render options
			add_filter( 'alpha_elementor_addon_options', array( $this, 'addon_options' ), 10, 2 );
		}

		/**
		 * Add controls to addon tab.
		 *
		 * @since 1.2.0
		 */
		public function add_controls( $self, $source = '' ) {

			$left  = is_rtl() ? 'right' : 'left';
			$right = 'left' == $left ? 'right' : 'left';

			if ( 'banner' != $source ) {
				$self->start_controls_section(
					'_alpha_section_transform_effect',
					array(
						'label' => esc_html__( 'Transform Effects', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);
			}

					$self->add_control(
						'alpha_transform_effect_notice',
						array(
							'type'            => Controls_Manager::RAW_HTML,
							'raw'             => esc_html__( 'Note: Avoid applying motion effects or scroll effects with this option. Doing so might cause unexpected results.', 'alpha-core' ),
							'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
						)
					);

					$self->add_control(
						'alpha_enable_transform_effect',
						array(
							'label'        => esc_html__( 'Transform Effects', 'alpha-core' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
						)
					);

					$self->add_control(
						'alpha_transform_translate',
						array(
							'label'     => esc_html__( 'Translate', 'alpha-core' ),
							'type'      => Controls_Manager::POPOVER_TOGGLE,
							'condition' => array(
								'alpha_enable_transform_effect' => 'true',
							),
						)
					);

					$self->start_popover();

						$self->add_control(
							'alpha_from_translate_heading',
							array(
								'label' => esc_html__( 'From', 'alpha-core' ),
								'type'  => Controls_Manager::HEADING,
							)
						);

						$self->add_control(
							'alpha_transform_from_translateX',
							array(
								'label'      => esc_html__( 'Translate X', 'alpha-core' ),
								'type'       => Controls_Manager::SLIDER,
								'size_units' => array( 'px', '%', 'custom' ),
								'range'      => array(
									'px' => array(
										'min' => 0,
										'max' => 100,
									),
								),
								'selectors'  => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-from-translateX: {{SIZE}}{{UNIT}};',
								),
								'condition'  => array(
									'alpha_transform_translate' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_transform_from_translateY',
							array(
								'label'      => esc_html__( 'Translate Y', 'alpha-core' ),
								'type'       => Controls_Manager::SLIDER,
								'size_units' => array( 'px', '%', 'custom' ),
								'range'      => array(
									'px' => array(
										'min' => 0,
										'max' => 100,
									),
								),
								'selectors'  => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-from-translateY: {{SIZE}}{{UNIT}};',
								),
								'condition'  => array(
									'alpha_transform_translate' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_to_translate_heading',
							array(
								'label'     => esc_html__( 'To', 'alpha-core' ),
								'type'      => Controls_Manager::HEADING,
								'separator' => 'before',
							)
						);

						$self->add_control(
							'alpha_transform_to_translateX',
							array(
								'label'      => esc_html__( 'Translate X', 'alpha-core' ),
								'type'       => Controls_Manager::SLIDER,
								'size_units' => array( 'px', '%', 'custom' ),
								'range'      => array(
									'px' => array(
										'min' => 0,
										'max' => 100,
									),
								),
								'selectors'  => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-to-translateX: {{SIZE}}{{UNIT}};',
								),
								'condition'  => array(
									'alpha_transform_translate' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_transform_to_translateY',
							array(
								'label'      => esc_html__( 'Translate Y', 'alpha-core' ),
								'type'       => Controls_Manager::SLIDER,
								'size_units' => array( 'px', '%', 'custom' ),
								'range'      => array(
									'px' => array(
										'min' => 0,
										'max' => 100,
									),
								),
								'selectors'  => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-to-translateY: {{SIZE}}{{UNIT}};',
								),
								'condition'  => array(
									'alpha_transform_translate' => 'yes',
								),
							)
						);

					$self->end_popover();

					$self->add_control(
						'alpha_transform_rotate',
						array(
							'label'     => esc_html__( 'Rotate', 'alpha-core' ),
							'type'      => Controls_Manager::POPOVER_TOGGLE,
							'condition' => array(
								'alpha_enable_transform_effect' => 'true',
							),
						)
					);

					$self->start_popover();

						$self->add_control(
							'alpha_from_rotate_heading',
							array(
								'label' => esc_html__( 'From', 'alpha-core' ),
								'type'  => Controls_Manager::HEADING,
							)
						);

						$self->add_control(
							'alpha_transform_from_rotate',
							array(
								'label'     => esc_html__( 'Rotate', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'deg' => array(
										'min' => 0,
										'max' => 360,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-from-rotate: {{SIZE}}deg;',
								),
								'condition' => array(
									'alpha_transform_rotate' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_to_rotate_heading',
							array(
								'label'     => esc_html__( 'To', 'alpha-core' ),
								'type'      => Controls_Manager::HEADING,
								'separator' => 'before',
							)
						);

						$self->add_control(
							'alpha_transform_to_rotate',
							array(
								'label'     => esc_html__( 'Rotate', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'deg' => array(
										'min' => 0,
										'max' => 360,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-to-rotate: {{SIZE}}deg;',
								),
								'condition' => array(
									'alpha_transform_rotate' => 'yes',
								),
							)
						);

					$self->end_popover();

					$self->add_control(
						'alpha_transform_scale',
						array(
							'label'     => esc_html__( 'Scale', 'alpha-core' ),
							'type'      => Controls_Manager::POPOVER_TOGGLE,
							'condition' => array(
								'alpha_enable_transform_effect' => 'true',
							),
						)
					);

					$self->start_popover();

						$self->add_control(
							'alpha_from_scale_heading',
							array(
								'label' => esc_html__( 'From', 'alpha-core' ),
								'type'  => Controls_Manager::HEADING,
							)
						);

						$self->add_control(
							'alpha_transform_from_scaleX',
							array(
								'label'     => esc_html__( 'Scale X', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'px' => array(
										'min'  => 0,
										'max'  => 10,
										'step' => 0.1,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-from-scaleX: {{SIZE}};',
								),
								'condition' => array(
									'alpha_transform_scale' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_transform_from_scaleY',
							array(
								'label'     => esc_html__( 'Scale Y', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'px' => array(
										'min'  => 0,
										'max'  => 10,
										'step' => 0.1,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-from-scaleY: {{SIZE}};',
								),
								'condition' => array(
									'alpha_transform_scale' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_to_scale_heading',
							array(
								'label'     => esc_html__( 'To', 'alpha-core' ),
								'type'      => Controls_Manager::HEADING,
								'separator' => 'before',
							)
						);

						$self->add_control(
							'alpha_transform_to_scaleX',
							array(
								'label'     => esc_html__( 'Scale X', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'px' => array(
										'min'  => 0,
										'max'  => 10,
										'step' => 0.1,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-to-scaleX: {{SIZE}};',
								),
								'condition' => array(
									'alpha_transform_scale' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_transform_to_scaleY',
							array(
								'label'     => esc_html__( 'Scale Y', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'px' => array(
										'min'  => 0,
										'max'  => 10,
										'step' => 0.1,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-to-scaleY: {{SIZE}};',
								),
								'condition' => array(
									'alpha_transform_scale' => 'yes',
								),
							)
						);

					$self->end_popover();

					$self->add_control(
						'alpha_transform_transparency',
						array(
							'label'     => esc_html__( 'Transparency', 'alpha-core' ),
							'type'      => Controls_Manager::POPOVER_TOGGLE,
							'condition' => array(
								'alpha_enable_transform_effect' => 'true',
							),
						)
					);

					$self->start_popover();

						$self->add_control(
							'alpha_from_transparency_heading',
							array(
								'label' => esc_html__( 'From', 'alpha-core' ),
								'type'  => Controls_Manager::HEADING,
							)
						);

						$self->add_control(
							'alpha_transform_from_transparency',
							array(
								'label'     => esc_html__( 'Transparency', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'px' => array(
										'min'  => 0,
										'max'  => 1,
										'step' => 0.1,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-from-transparency: {{SIZE}};',
								),
								'condition' => array(
									'alpha_transform_transparency' => 'yes',
								),
							)
						);

						$self->add_control(
							'alpha_to_transparency_heading',
							array(
								'label'     => esc_html__( 'To', 'alpha-core' ),
								'type'      => Controls_Manager::HEADING,
								'separator' => 'before',
							)
						);

						$self->add_control(
							'alpha_transform_to_transparency',
							array(
								'label'     => esc_html__( 'Transparency', 'alpha-core' ),
								'type'      => Controls_Manager::SLIDER,
								'range'     => array(
									'px' => array(
										'min'  => 0,
										'max'  => 1,
										'step' => 0.1,
									),
								),
								'selectors' => array(
									'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-to-transparency: {{SIZE}};',
								),
								'condition' => array(
									'alpha_transform_transparency' => 'yes',
								),
							)
						);

					$self->end_popover();

				$self->add_control(
					'alpha_transform_duration',
					array(
						'label'     => esc_html__( 'Animation Duration (s)', 'alpha-core' ),
						'type'      => Controls_Manager::SLIDER,
						'default'   => array(
							'size' => 2,
						),
						'range'     => array(
							'px' => array(
								'max'  => 3,
								'step' => 0.1,
							),
						),
						'selectors' => array(
							'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-transform-duration: {{SIZE}}s;',
						),
						'condition' => array(
							'alpha_enable_transform_effect' => 'true',
						),
					)
				);

				$self->add_control(
					'alpha_transform_direction',
					array(
						'label'     => esc_html__( 'Animation Direction', 'alpha-core' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'alternate',
						'options'   => array(
							'normal'    => esc_html__( 'Normal', 'alpha-core' ),
							'alternate' => esc_html__( 'Alternate', 'alpha-core' ),
						),
						'selectors' => array(
							'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => '--alpha-transform-direction: {{VALUE}};',
						),
						'condition' => array(
							'alpha_enable_transform_effect' => 'true',
						),
					)
				);

				$self->add_control(
					'alpha_transform_timing',
					array(
						'label'     => esc_html__( 'Animation Timing Function', 'alpha-core' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'ease-in-out',
						'options'   => array(
							'ease'        => esc_html__( 'Ease', 'alpha-core' ),
							'ease-in'     => esc_html__( 'Ease In', 'alpha-core' ),
							'ease-out'    => esc_html__( 'Ease Out', 'alpha-core' ),
							'ease-in-out' => esc_html__( 'Ease In Out', 'alpha-core' ),
							'linear'      => esc_html__( 'Linear', 'alpha-core' ),
						),
						'selectors' => array(
							'{{WRAPPER}}' . ( 'banner' == $source ? ' {{CURRENT_ITEM}}' : '' ) => ' --alpha-transform-timing: {{VALUE}};',
						),
						'condition' => array(
							'alpha_enable_transform_effect' => 'true',
						),
					)
				);

			if ( 'banner' != $source ) {
				$self->end_controls_section();
			}
		}

		/**
		 * Add render options.
		 *
		 * @since 1.2.0
		 */
		public function addon_options( $options, $settings ) {
			if ( isset( $settings['alpha_enable_transform_effect'] ) && filter_var( $settings['alpha_enable_transform_effect'], FILTER_VALIDATE_BOOLEAN ) ) {
				$class = 'alpha-transform-animating';
				if ( ! empty( $options['class'] ) ) {
					$options['class'] .= ' ' . $class;
				} else {
					$options['class'] = $class;
				}
			}
			return $options;
		}
	}
	Alpha_Transform_Elementor::get_instance();
}
