<?php
/**
 * Alpha Elementor Ribbon Addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.2.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Ribbon_Elementor' ) ) {
	/**
	 * Alpha Elementor Ribbon Addon
	 *
	 * @since 1.2.0
	 */
	class Alpha_Ribbon_Elementor extends Alpha_Base {

		/**
		 * The Constructor.
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			// Enqueue component css
			add_action( 'alpha_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );

			// Add controls to addon tab
			add_action( 'alpha_elementor_addon_controls', array( $this, 'add_controls' ), 30, 2 );

			// Add render options
			add_filter( 'alpha_elementor_addon_options', array( $this, 'addon_options' ), 30, 2 );

			// Add variable to alpha_vars
			add_filter(
				'alpha_vars',
				function( $vars ) {
					$vars['texts']['ribbon'] = esc_html__( 'Ribbon', 'alpha-core' );
					return $vars;
				}
			);

			// Addon renderer
			add_filter( 'alpha_elementor_addon_render', array( $this, 'addon_render' ), 30, 2 );
		}

		/**
		 * Enqueue component css
		 *
		 * @since 1.2.0
		 */
		public function enqueue_scripts() {
			if ( alpha_is_elementor_preview() ) {
				wp_enqueue_style( 'alpha-ribbon', alpha_core_framework_uri( '/plugins/elementor/tabs/ribbon/ribbon' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}
		}

		/**
		 * Add controls to addon tab.
		 *
		 * @since 1.2.0
		 */
		public function add_controls( $self, $source = '' ) {

			if ( 'banner' != $source ) {
				$self->start_controls_section(
					'alpha_widget_ribbon_section',
					array(
						'label' => esc_html__( 'Ribbon', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);

					$self->add_control(
						'alpha_widget_ribbon',
						array(
							'label'        => esc_html__( 'Add Ribbon?', 'alpha-core' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => esc_html__( 'Yes', 'alpha-core' ),
							'label_off'    => esc_html__( 'No', 'alpha-core' ),
							'return_value' => 'true',
							'default'      => 'false',
							'render_type'  => 'template',
						)
					);

					$self->start_controls_tabs(
						'alpha_widget_ribbon_tabs',
						array(
							'condition' => array(
								'alpha_widget_ribbon' => 'true',
							),
						)
					);

						$self->start_controls_tab(
							'alpha_widget_ribbon_settings_tab',
							array(
								'label' => esc_html__( 'Setting', 'alpha-core' ),
							)
						);

							$self->add_control(
								'alpha_widget_ribbon_type',
								array(
									'label'       => esc_html__( 'Ribbon Type', 'alpha-core' ),
									'type'        => Alpha_Controls_Manager::IMAGE_CHOOSE,
									'description' => esc_html__( 'Please select your favourite ribbon style.', 'alpha-core' ),
									'default'     => 'type-1',
									'options'     => array(
										'type-1' => 'assets/images/badges/badge-1.jpg',
										'type-2' => 'assets/images/badges/badge-2.jpg',
										'type-3' => 'assets/images/badges/badge-3.jpg',
										'type-4' => 'assets/images/badges/badge-4.jpg',
										'type-5' => 'assets/images/badges/badge-5.jpg',
										'type-6' => 'assets/images/badges/badge-6.jpg',
									),
									'width'       => 2,
								)
							);

							$self->add_control(
								'alpha_widget_ribbon_text',
								array(
									'label'       => esc_html__( 'Ribbon Text', 'alpha-core' ),
									'type'        => Controls_Manager::TEXT,
									'description' => esc_html__( 'Type text(html) that will be shown on ribbon.', 'alpha-core' ),
									'placeholder' => esc_html__( 'Ribbon', 'alpha-core' ),
								)
							);

							$self->add_control(
								'alpha_widget_ribbon_position',
								array(
									'label'       => esc_html__( 'Ribbon Position', 'alpha-core' ),
									'type'        => Controls_Manager::SELECT,
									'description' => esc_html__( 'Choose ribbon position that will be shown.', 'alpha-core' ),
									'default'     => 'top-left',
									'options'     => array(
										'top-left'     => esc_html__( 'Top - Left', 'alpha-core' ),
										'top-right'    => esc_html__( 'Top - Right', 'alpha-core' ),
										'bottom-left'  => esc_html__( 'Bottom - Left', 'alpha-core' ),
										'bottom-right' => esc_html__( 'Bottom - Right', 'alpha-core' ),
									),
								)
							);

							$self->add_control(
								'alpha_widget_ribbon_z_index',
								array(
									'label'     => esc_html__( 'z-Index', 'alpha-core' ),
									'type'      => Controls_Manager::NUMBER,
									'min'       => 0,
									'max'       => 999,
									'step'      => 1,
									'selectors' => array(
										'.elementor-element-{{ID}}  .ribbon-{{ID}}' => 'z-index:{{VALUE}}',
									),
								)
							);

						$self->end_controls_tab();

						$self->start_controls_tab(
							'alpha_widget_ribbon_styles_tab',
							array(
								'label' => esc_html__( 'Styles', 'alpha-core' ),
							)
						);

							$self->add_responsive_control(
								'alpha_widget_ribbon_4_size',
								array(
									'label'       => esc_html__( 'Ribbon Size', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Set size of ribbon wrap.', 'alpha-core' ),
									'range'       => array(
										'px' => array(
											'min' => 50,
											'max' => 300,
										),
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .ribbon-{{ID}}' => 'font-size: {{SIZE}}{{UNIT}};',
									),
									'condition'   => array(
										'alpha_widget_ribbon_type' => 'type-4',
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_ribbon_6_size',
								array(
									'label'       => esc_html__( 'Ribbon Size', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Set size of ribbon wrap.', 'alpha-core' ),
									'range'       => array(
										'px' => array(
											'min' => 50,
											'max' => 300,
										),
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .ribbon-{{ID}}' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
									),
									'condition'   => array(
										'alpha_widget_ribbon_type' => 'type-6',
									),
								)
							);

							$self->add_control(
								'alpha_widget_ribbon_text_color',
								array(
									'label'       => esc_html__( 'Color', 'alpha-core' ),
									'type'        => Controls_Manager::COLOR,
									'description' => esc_html__( 'Set color of ribbon content.', 'alpha-core' ),
									'condition'   => array(
										'alpha_widget_ribbon' => 'true',
										'alpha_widget_ribbon_type' => array( 'type-1', 'type-2', 'type-5', 'type-6' ),
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .ribbon-{{ID}} .ribbon-text' => 'color: {{VALUE}}',
									),
								)
							);

							$self->add_group_control(
								Group_Control_Typography::get_type(),
								array(
									'name'      => 'alpha_widget_ribbon_text_typography',
									'selector'  => '.elementor-element-{{ID}} .ribbon-{{ID}} .ribbon-text',
									'condition' => array(
										'alpha_widget_ribbon' => 'true',
									),
								)
							);

							$self->add_group_control(
								Group_Control_Background::get_type(),
								array(
									'name'           => 'alpha_widget_ribbon_bg_color',
									'selector'       => '.elementor-element-{{ID}} .ribbon-{{ID}}',
									'description'    => esc_html__( 'Set background of ribbon wrap.', 'alpha-core' ),
									'exclude'        => array( 'image' ),
									'fields_options' => array(
										'background' => array(
											'label' => esc_html__( 'Background Color', 'alpha-core' ),
										),
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_ribbon_margin',
								array(
									'label'       => esc_html__( 'Margin', 'alpha-core' ),
									'type'        => Controls_Manager::DIMENSIONS,
									'description' => esc_html__( 'Set margin of ribbon wrap.', 'alpha-core' ),
									'size_units'  => array( 'px', 'rem', '%' ),
									'selectors'   => array(
										'.elementor-element-{{ID}} .ribbon-{{ID}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition'   => array(
										'alpha_widget_ribbon_type' => array( 'type-1', 'type-3', 'type-6' ),
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_ribbon_margin2',
								array(
									'label'              => esc_html__( 'Margin', 'alpha-core' ),
									'type'               => Controls_Manager::DIMENSIONS,
									'description'        => esc_html__( 'Set margin of ribbon wrap.', 'alpha-core' ),
									'size_units'         => array( 'px', 'rem', '%' ),
									'allowed_dimensions' => 'vertical',
									'selectors'          => array(
										'.elementor-element-{{ID}} .ribbon-{{ID}}' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
									),
									'condition'          => array(
										'alpha_widget_ribbon_type' => array( 'type-2' ),
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_ribbon_padding',
								array(
									'label'       => esc_html__( 'Padding', 'alpha-core' ),
									'type'        => Controls_Manager::DIMENSIONS,
									'description' => esc_html__( 'Set padding of ribbon wrap.', 'alpha-core' ),
									'size_units'  => array( 'px', 'rem', '%' ),
									'selectors'   => array(
										'.elementor-element-{{ID}} .ribbon-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									),
									'condition'   => array(
										'alpha_widget_ribbon_type' => array( 'type-1', 'type-2', 'type-3', 'type-5' ),
									),
								)
							);

						$self->end_controls_tab();

					$self->end_controls_tabs();

				$self->end_controls_section();
			}
		}
		/**
		 * Add render options.
		 *
		 * @since 1.2.0
		 */
		public function addon_options( $options, $settings ) {
			if ( isset( $settings['alpha_widget_ribbon'] ) && filter_var( $settings['alpha_widget_ribbon'], FILTER_VALIDATE_BOOLEAN ) ) {
				$class = 'ribbon-widget';
				if ( 'type-4' == $settings['alpha_widget_ribbon_type'] || 'type-5' == $settings['alpha_widget_ribbon_type'] ) {
					$class .= ' overflow-hidden';
				}
				if ( ! empty( $options['class'] ) ) {
					$options['class'] .= ' ' . $class;
				} else {
					$options['class'] = $class;
				}
			}
			return $options;
		}

		/**
		 * Addon renderer.
		 *
		 * @since 1.2.0
		 */
		public function addon_render( $settings, $id ) {
			$settings = wp_parse_args(
				$settings,
				array(
					'alpha_widget_ribbon'          => 'false',
					'alpha_widget_ribbon_type'     => 'type-1',
					'alpha_widget_ribbon_text'     => esc_html__( 'Ribbon', 'alpha-core' ),
					'alpha_widget_ribbon_position' => 'top-left',
				)
			);

			// Ribbon
			if ( isset( $settings['alpha_widget_ribbon'] ) && filter_var( $settings['alpha_widget_ribbon'], FILTER_VALIDATE_BOOLEAN ) ) {

				wp_enqueue_style( 'alpha-ribbon', alpha_core_framework_uri( '/plugins/elementor/tabs/ribbon/ribbon' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

				printf(
					'<div class="ribbon ribbon-%4$s ribbon-%1$s ribbon-%2$s">
								<span class="ribbon-text">
									%3$s
								</span>
							</div>',
					esc_attr( $settings['alpha_widget_ribbon_type'] ),
					esc_attr( $settings['alpha_widget_ribbon_position'] ),
					$settings['alpha_widget_ribbon_text'] ? alpha_strip_script_tags( $settings['alpha_widget_ribbon_text'] ) : esc_html__( 'Ribbon', 'alpha-core' ),
					$id
				);
			}
		}
	}
	Alpha_Ribbon_Elementor::get_instance();
}
