<?php
/**
 * Alpha Elementor Duplex Addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.2.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Utils;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Duplex_Elementor' ) ) {
	/**
	 * Alpha Elementor Duplex Addon
	 *
	 * @since 1.2.0
	 */
	class Alpha_Duplex_Elementor extends Alpha_Base {

		/**
		 * The Constructor.
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			// Enqueue component css
			add_action( 'alpha_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );

			// Add controls to addon tab
			add_action( 'alpha_elementor_addon_controls', array( $this, 'add_controls' ), 20, 2 );

			// Add render options
			add_filter( 'alpha_elementor_addon_options', array( $this, 'addon_options' ), 20, 2 );

			// Addon renderer
			add_filter( 'alpha_elementor_addon_render', array( $this, 'addon_render' ), 20, 2 );
		}

		/**
		 * Enqueue component css.
		 *
		 * @since 1.2.0
		 */
		public function enqueue_scripts() {
			if ( alpha_is_elementor_preview() ) {
				wp_enqueue_style( 'alpha-duplex', alpha_core_framework_uri( '/plugins/elementor/tabs/duplex/duplex.min.css' ), array(), ALPHA_CORE_VERSION );
			}
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
					'alpha_widget_duplex_section',
					array(
						'label' => esc_html__( 'Duplex', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);

					$self->add_control(
						'alpha_widget_duplex',
						array(
							'label'        => esc_html__( 'Use Duplex Effect?', 'alpha-core' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => esc_html__( 'Yes', 'alpha-core' ),
							'label_off'    => esc_html__( 'No', 'alpha-core' ),
							'return_value' => 'true',
							'default'      => 'false',
							'render_type'  => 'template',
						)
					);

					$self->start_controls_tabs(
						'alpha_widget_duplex_tabs',
						array(
							'condition' => array(
								'alpha_widget_duplex' => 'true',
							),
						)
					);

						$self->start_controls_tab(
							'alpha_widget_duplex_settings_tab',
							array(
								'label' => esc_html__( 'Settings', 'alpha-core' ),
							)
						);

							$self->add_control(
								'alpha_widget_duplex_type',
								array(
									'label'       => esc_html__( 'Type', 'alpha-core' ),
									'type'        => Controls_Manager::CHOOSE,
									'description' => esc_html__( 'Choose from 2 duplex type: text or image.', 'alpha-core' ),
									'options'     => array(
										'text'  => array(
											'title' => esc_html__( 'Text', 'alpha-core' ),
											'icon'  => 'eicon-t-letter',
										),
										'image' => array(
											'title' => esc_html__( 'Image', 'alpha-core' ),
											'icon'  => 'eicon-image',
										),
									),
									'default'     => 'text',
									'toggle'      => false,
									'render_type' => 'template',
								)
							);

							$self->add_control(
								'alpha_widget_duplex_text',
								array(
									'label'       => esc_html__( 'Text', 'alpha-core' ),
									'type'        => Controls_Manager::TEXT,
									'description' => esc_html__( 'Type a text will be shown on the duplex.', 'alpha-core' ),
									'default'     => esc_html__( 'Duplex', 'alpha-core' ),
									'condition'   => array(
										'alpha_widget_duplex_type' => 'text',
									),
									'render_type' => 'template',
								)
							);

							$self->add_control(
								'alpha_widget_duplex_image',
								array(
									'label'       => esc_html__( 'Image', 'alpha-core' ),
									'type'        => Controls_Manager::MEDIA,
									'description' => esc_html__( 'Choose a image will be shown on the duplex.', 'alpha-core' ),
									'default'     => array(
										'url' => Utils::get_placeholder_image_src(),
									),
									'condition'   => array(
										'alpha_widget_duplex_type' => 'image',
									),
									'render_type' => 'template',
								)
							);

							$self->add_control(
								'alpha_widget_duplex_origin',
								array(
									'label'       => esc_html__( 'Origin', 'alpha-core' ),
									'description' => esc_html__( 'Set transform axis point.', 'alpha-core' ),
									'type'        => Controls_Manager::CHOOSE,
									'options'     => array(
										't-m'  => array(
											'title' => esc_html__( 'Vertical Center', 'alpha-core' ),
											'icon'  => 'eicon-v-align-middle',
										),
										't-c'  => array(
											'title' => esc_html__( 'Horizontal Center', 'alpha-core' ),
											'icon'  => 'eicon-h-align-center',
										),
										't-mc' => array(
											'title' => esc_html__( 'Center', 'alpha-core' ),
											'icon'  => 'eicon-frame-minimize',
										),
									),
									'default'     => 't-mc',
								)
							);

							$self->add_responsive_control(
								'alpha_widget_duplex_x_offset',
								array(
									'label'       => esc_html__( 'X-Offset', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Control duplex\'s X position.', 'alpha-core' ),
									'size_units'  => array( 'px', '%', 'em' ),
									'range'       => array(
										'px' => array(
											'min' => -500,
											'max' => 500,
										),
										'%'  => array(
											'min' => -100,
											'max' => 100,
										),
									),
									'default'     => array(
										'size' => 50,
										'unit' => '%',
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .duplex-wrap-{{ID}}' => $left . ': {{SIZE}}{{UNIT}};',
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_duplex_y_offset',
								array(
									'label'       => esc_html__( 'Y-Offset', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Control duplex\'s Y position.', 'alpha-core' ),
									'size_units'  => array( 'px', '%', 'em' ),
									'range'       => array(
										'px' => array(
											'min' => -500,
											'max' => 500,
										),
										'%'  => array(
											'min' => -100,
											'max' => 100,
										),
									),
									'default'     => array(
										'size' => 0,
										'unit' => 'px',
									),
									'selectors'   => array(
										'{{WRAPPER}} .duplex-wrap-{{ID}}' => 'top: {{SIZE}}{{UNIT}};',
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_duplex_rotate',
								array(
									'label'       => esc_html__( 'Rotate', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Control rotate angle of duplex wrap.', 'alpha-core' ),
									'size_units'  => array( 'deg' ),
									'range'       => array(
										'deg' => array(
											'min' => -180,
											'max' => 180,
										),
									),
									'default'     => array(
										'size' => 0,
										'unit' => 'deg',
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex' => 'transform: rotate({{SIZE}}deg)',
									),
								)
							);

							$self->add_responsive_control(
								'alpha_widget_duplex_stroke_width',
								array(
									'label'       => esc_html__( 'Stroke Width (px)', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Control stroke width of text type.', 'alpha-core' ),
									'size_units'  => array( 'px' ),
									'range'       => array(
										'px' => array(
											'step' => 1,
											'min'  => 1,
											'max'  => 50,
										),
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex-text' => '-webkit-text-fill-color: transparent; -webkit-text-stroke-width: {{SIZE}}px;',
									),
									'condition'   => array(
										'alpha_widget_duplex_type' => 'text',
									),
								)
							);

							$self->add_control(
								'alpha_widget_duplex_z_index',
								array(
									'label'     => esc_html__( 'z-Index', 'alpha-core' ),
									'type'      => Controls_Manager::NUMBER,
									'min'       => 0,
									'max'       => 999,
									'step'      => 1,
									'selectors' => array(
										'.elementor-element-{{ID}} .duplex-wrap-{{ID}}' => 'z-index:{{VALUE}}',
									),
								)
							);

						$self->end_controls_tab();

						$self->start_controls_tab(
							'alpha_widget_duplex_styles_tab',
							array(
								'label' => esc_html__( 'Styles', 'alpha-core' ),
							)
						);

							$self->add_control(
								'alpha_widget_duplex_text_color',
								array(
									'label'       => esc_html__( 'Color', 'alpha-core' ),
									'type'        => Controls_Manager::COLOR,
									'description' => esc_html__( 'Set color of duplex content.', 'alpha-core' ),
									'condition'   => array(
										'alpha_widget_duplex_type' => 'text',
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex-text' => 'color: {{VALUE}}',
									),
								)
							);

							$self->add_group_control(
								Group_Control_Typography::get_type(),
								array(
									'name'        => 'alpha_widget_duplex_text_typography',
									'description' => esc_html__( 'Set font style of duplex content.', 'alpha-core' ),
									'selector'    => '.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex-text',
									'condition'   => array(
										'alpha_widget_duplex_type' => 'text',
									),
								)
							);

							$self->add_group_control(
								Group_Control_Text_Shadow::get_type(),
								array(
									'name'      => 'alpha_widget_duplex_text_shadow',
									'selector'  => '.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex-text',
									'condition' => array(
										'alpha_widget_duplex_type' => 'text',
									),
								)
							);

							$self->add_control(
								'alpha_widget_duplex_image_width',
								array(
									'label'       => esc_html__( 'Width', 'alpha-core' ),
									'type'        => Controls_Manager::SLIDER,
									'description' => esc_html__( 'Set width of duplex image.', 'alpha-core' ),
									'size_units'  => array( 'px', 'rem', '%' ),
									'range'       => array(
										'px'  => array(
											'step' => 1,
											'min'  => 1,
											'max'  => 300,
										),
										'%'   => array(
											'step' => 1,
											'min'  => 1,
											'max'  => 200,
										),
										'rem' => array(
											'step' => 1,
											'min'  => 1,
											'max'  => 30,
										),
									),
									'condition'   => array(
										'alpha_widget_duplex_type' => 'image',
									),
									'selectors'   => array(
										'.elementor-element-{{ID}} .duplex-wrap-{{ID}}' => 'width:{{SIZE}}{{UNIT}}',
									),
								)
							);

					$self->add_group_control(
						Group_Control_Css_Filter::get_type(),
						array(
							'name'     => 'alpha_widget_duplex_css_filters',
							'selector' => '.elementor-element-{{ID}} .duplex-wrap-{{ID}} > .duplex',
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
			if ( isset( $settings['alpha_widget_duplex'] ) && filter_var( $settings['alpha_widget_duplex'], FILTER_VALIDATE_BOOLEAN ) ) {
				$class = 'duplex-widget';
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
					'alpha_widget_duplex'        => 'false',
					'alpha_widget_duplex_type'   => 'text',
					'alpha_widget_duplex_text'   => '',
					'alpha_widget_duplex_image'  => array(
						'url' => '',
						'id'  => '',
					),
					'alpha_widget_duplex_origin' => 't-mc',
				)
			);

			if ( isset( $settings['alpha_widget_duplex'] ) && filter_var( $settings['alpha_widget_duplex'], FILTER_VALIDATE_BOOLEAN ) ) {
				wp_enqueue_style( 'alpha-duplex', alpha_core_framework_uri( '/plugins/elementor/tabs/duplex/duplex.min.css' ), array(), ALPHA_CORE_VERSION );
				switch ( $settings['alpha_widget_duplex_type'] ) {
					case 'text':
						if ( ! empty( $settings['alpha_widget_duplex_text'] ) ) {
							printf(
								'<div class="duplex-wrap duplex-wrap-%2$s' . ( $settings['alpha_widget_duplex_origin'] ? ( ' ' . esc_attr( $settings['alpha_widget_duplex_origin'] ) ) : '' ) . '">
											<span class="duplex duplex-text">%1$s</span>
										</div>',
								esc_attr( $settings['alpha_widget_duplex_text'] ),
								$id
							);
						}
						break;

					case 'image':
						if ( ! empty( $settings['alpha_widget_duplex_image']['id'] ) || ! empty( $settings['alpha_widget_duplex_image']['url'] ) ) {
							printf(
								'<div class="duplex-wrap duplex-wrap-%2$s' . ( $settings['alpha_widget_duplex_origin'] ? ( ' ' . esc_attr( $settings['alpha_widget_duplex_origin'] ) ) : '' ) . '">
											<div class="duplex duplex-image">
												%1$s
											</div>
										</div>',
								! empty( $settings['alpha_widget_duplex_image']['id'] ) ? wp_get_attachment_image( $settings['alpha_widget_duplex_image']['id'], 'full' ) : Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'alpha_widget_duplex_image' ),
								$id
							);
						}
						break;
				}
			}
		}
	}
	Alpha_Duplex_Elementor::get_instance();
}
