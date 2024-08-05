<?php
/**
 * Alpha Elementor Floating Addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.2.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Floating_Elementor' ) ) {
	/**
	 * Alpha Elementor Floating Addon
	 *
	 * @since 1.2.0
	 */
	class Alpha_Floating_Elementor extends Alpha_Base {

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
					'_alpha_section_floating_effect',
					array(
						'label' => esc_html__( 'Scroll Effects', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);
			}

			if ( 'section' == $source ) {
				$self->add_control(
					'alpha_floating',
					array(
						'label'       => esc_html__( 'Scroll Effects', 'alpha-core' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => '',
						'description' => esc_html__( 'Select the certain scroll effect you want to implement in your page.', 'alpha-core' ),
						'groups'      => array(
							''                      => esc_html__( 'None', 'alpha-core' ),
							'transform_group'       => array(
								'label'   => esc_html__( 'Transform Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_transform_up'    => esc_html__( 'Move To Up', 'alpha-core' ),
									'skr_transform_down'  => esc_html__( 'Move To Down', 'alpha-core' ),
									'skr_transform_left'  => esc_html__( 'Move To Left', 'alpha-core' ),
									'skr_transform_right' => esc_html__( 'Move To Right', 'alpha-core' ),
								),
							),
							'fade_in_group'         => array(
								'label'   => esc_html__( 'Fade In Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_fade_in'       => esc_html__( 'Fade In', 'alpha-core' ),
									'skr_fade_in_up'    => esc_html__( 'Fade In Up', 'alpha-core' ),
									'skr_fade_in_down'  => esc_html__( 'Fade In Down', 'alpha-core' ),
									'skr_fade_in_left'  => esc_html__( 'Fade In Left', 'alpha-core' ),
									'skr_fade_in_right' => esc_html__( 'Fade In Right', 'alpha-core' ),
								),
							),
							'fade_out_group'        => array(
								'label'   => esc_html__( 'Fade Out Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_fade_out'       => esc_html__( 'Fade Out', 'alpha-core' ),
									'skr_fade_out_up'    => esc_html__( 'Fade Out Up', 'alpha-core' ),
									'skr_fade_out_down'  => esc_html__( 'Fade Out Down', 'alpha-core' ),
									'skr_fade_out_left'  => esc_html__( 'Fade Out Left', 'alpha-core' ),
									'skr_fade_out_right' => esc_html__( 'Fade Out Right', 'alpha-core' ),
								),
							),
							'flip_group'            => array(
								'label'   => esc_html__( 'Flip Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_flip_x' => esc_html__( 'Flip Horizontally', 'alpha-core' ),
									'skr_flip_y' => esc_html__( 'Flip Vertically', 'alpha-core' ),
								),
							),
							'rotate_group'          => array(
								'label'   => esc_html__( 'Rotate Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_rotate'       => esc_html__( 'Rotate', 'alpha-core' ),
									'skr_rotate_left'  => esc_html__( 'Rotate To Left', 'alpha-core' ),
									'skr_rotate_right' => esc_html__( 'Rotate To Right', 'alpha-core' ),
								),
							),
							'zoom_in_group'         => array(
								'label'   => esc_html__( 'Zoom In Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_zoom_in'       => esc_html__( 'Zoom In', 'alpha-core' ),
									'skr_zoom_in_up'    => esc_html__( 'Zoom In Up', 'alpha-core' ),
									'skr_zoom_in_down'  => esc_html__( 'Zoom In Down', 'alpha-core' ),
									'skr_zoom_in_left'  => esc_html__( 'Zoom In Left', 'alpha-core' ),
									'skr_zoom_in_right' => esc_html__( 'Zoom In Right', 'alpha-core' ),
								),
							),
							'zoom_out_group'        => array(
								'label'   => esc_html__( 'Zoom Out Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_zoom_out'       => esc_html__( 'Zoom Out', 'alpha-core' ),
									'skr_zoom_out_up'    => esc_html__( 'Zoom Out Up', 'alpha-core' ),
									'skr_zoom_out_down'  => esc_html__( 'Zoom Out Down', 'alpha-core' ),
									'skr_zoom_out_left'  => esc_html__( 'Zoom Out Left', 'alpha-core' ),
									'skr_zoom_out_right' => esc_html__( 'Zoom Out Right', 'alpha-core' ),
								),
							),
							'horizontal_zoom_group' => array(
								'label'   => esc_html__( 'Horizontal Zoom Effect', 'alpha-core' ),
								'options' => array(
									'skr_horizontal_zoom_in'  => esc_html__( 'Horizontal Zoom In', 'alpha-core' ),
									'skr_horizontal_zoom_out' => esc_html__( 'Horizontal Zoom Out', 'alpha-core' ),
								),
							),
						),
					)
				);
			} else {
				$self->add_control(
					'alpha_floating',
					array(
						'label'       => esc_html__( 'Scroll Effects', 'alpha-core' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => '',
						'description' => esc_html__( 'Select the certain scroll effect you want to implement in your page.', 'alpha-core' ),
						'groups'      => array(
							''                  => esc_html__( 'None', 'alpha-core' ),
							'transform_group'   => array(
								'label'   => esc_html__( 'Transform Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_transform_up'    => esc_html__( 'Move To Up', 'alpha-core' ),
									'skr_transform_down'  => esc_html__( 'Move To Down', 'alpha-core' ),
									'skr_transform_left'  => esc_html__( 'Move To Left', 'alpha-core' ),
									'skr_transform_right' => esc_html__( 'Move To Right', 'alpha-core' ),
								),
							),
							'fade_in_group'     => array(
								'label'   => esc_html__( 'Fade In Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_fade_in'       => esc_html__( 'Fade In', 'alpha-core' ),
									'skr_fade_in_up'    => esc_html__( 'Fade In Up', 'alpha-core' ),
									'skr_fade_in_down'  => esc_html__( 'Fade In Down', 'alpha-core' ),
									'skr_fade_in_left'  => esc_html__( 'Fade In Left', 'alpha-core' ),
									'skr_fade_in_right' => esc_html__( 'Fade In Right', 'alpha-core' ),
								),
							),
							'fade_out_group'    => array(
								'label'   => esc_html__( 'Fade Out Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_fade_out'       => esc_html__( 'Fade Out', 'alpha-core' ),
									'skr_fade_out_up'    => esc_html__( 'Fade Out Up', 'alpha-core' ),
									'skr_fade_out_down'  => esc_html__( 'Fade Out Down', 'alpha-core' ),
									'skr_fade_out_left'  => esc_html__( 'Fade Out Left', 'alpha-core' ),
									'skr_fade_out_right' => esc_html__( 'Fade Out Right', 'alpha-core' ),
								),
							),
							'flip_group'        => array(
								'label'   => esc_html__( 'Flip Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_flip_x' => esc_html__( 'Flip Horizontally', 'alpha-core' ),
									'skr_flip_y' => esc_html__( 'Flip Vertically', 'alpha-core' ),
								),
							),
							'rotate_group'      => array(
								'label'   => esc_html__( 'Rotate Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_rotate'       => esc_html__( 'Rotate', 'alpha-core' ),
									'skr_rotate_left'  => esc_html__( 'Rotate To Left', 'alpha-core' ),
									'skr_rotate_right' => esc_html__( 'Rotate To Right', 'alpha-core' ),
								),
							),
							'zoom_in_group'     => array(
								'label'   => esc_html__( 'Zoom In Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_zoom_in'       => esc_html__( 'Zoom In', 'alpha-core' ),
									'skr_zoom_in_up'    => esc_html__( 'Zoom In Up', 'alpha-core' ),
									'skr_zoom_in_down'  => esc_html__( 'Zoom In Down', 'alpha-core' ),
									'skr_zoom_in_left'  => esc_html__( 'Zoom In Left', 'alpha-core' ),
									'skr_zoom_in_right' => esc_html__( 'Zoom In Right', 'alpha-core' ),
								),
							),
							'zoom_out_group'    => array(
								'label'   => esc_html__( 'Zoom Out Scroll Effect', 'alpha-core' ),
								'options' => array(
									'skr_zoom_out'       => esc_html__( 'Zoom Out', 'alpha-core' ),
									'skr_zoom_out_up'    => esc_html__( 'Zoom Out Up', 'alpha-core' ),
									'skr_zoom_out_down'  => esc_html__( 'Zoom Out Down', 'alpha-core' ),
									'skr_zoom_out_left'  => esc_html__( 'Zoom Out Left', 'alpha-core' ),
									'skr_zoom_out_right' => esc_html__( 'Zoom Out Right', 'alpha-core' ),
								),
							),
							'mouse_track_group' => array(
								'label'   => esc_html__( 'Mouse Tracking', 'alpha-core' ),
								'options' => array(
									'mouse_tracking_x' => esc_html__( 'Track Horizontally', 'alpha-core' ),
									'mouse_tracking_y' => esc_html__( 'Track Vertically', 'alpha-core' ),
									'mouse_tracking'   => esc_html__( 'Track Any Direction', 'alpha-core' ),
								),
							),
						),
					)
				);
			}

				$self->add_control(
					'alpha_m_track_dir',
					array(
						'label'       => esc_html__( 'Inverse Mouse Move', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'description' => esc_html__( 'Move object in opposite direction of mouse move.', 'alpha-core' ),
						'condition'   => array(
							'alpha_floating' => array( 'mouse_tracking_x', 'mouse_tracking_y', 'mouse_tracking' ),
						),
					)
				);

				$self->add_control(
					'alpha_m_track_speed',
					array(
						'label'       => esc_html__( 'Track Speed', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'description' => esc_html__( 'Controls speed of floating object while mouse is moving.', 'alpha-core' ),
						'default'     => array(
							'size' => 0.5,
						),
						'range'       => array(
							'' => array(
								'step' => 0.1,
								'min'  => 0,
								'max'  => 5,
							),
						),
						'condition'   => array(
							'alpha_floating' => array( 'mouse_tracking_x', 'mouse_tracking_y', 'mouse_tracking' ),
						),
					)
				);

				$self->add_control(
					'alpha_scroll_size',
					array(
						'label'       => esc_html__( 'Floating Size', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'description' => esc_html__( 'Controls offset of floating object position while scrolling.', 'alpha-core' ),
						'default'     => array(
							'size' => '50',
							'unit' => '%',
						),
						'range'       => array(
							'%' => array(
								'step' => 1,
								'min'  => 20,
								'max'  => 500,
							),
						),
						'condition'   => array(
							'alpha_floating!' => array( '', 'mouse_tracking_x', 'mouse_tracking_y', 'mouse_tracking' ),
						),
					)
				);

				$self->add_control(
					'alpha_scroll_stop',
					array(
						'label'       => esc_html__( 'When scrolling effect should be stopped', 'alpha-core' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'center',
						'options'     => array(
							'top'        => esc_html__( 'After Top of Object reaches Top of Screen', 'alpha-core' ),
							'center-top' => esc_html__( 'After Top of Object reaches Center of Screen', 'alpha-core' ),
							'center'     => esc_html__( 'After Center of Object reaches Center of Screen', 'alpha-core' ),
						),
						'condition'   => array(
							'alpha_floating!' => array( '', 'mouse_tracking_x', 'mouse_tracking_y', 'mouse_tracking' ),
						),
						'description' => esc_html__( 'Determine how to stop scrolling effect.', 'alpha-core' ),
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
			// Floating Options
			if ( 0 === strpos( $settings['alpha_floating'], 'mouse_tracking' ) ) { // mouse tracking
				$floating_settings = array();
				if ( 'yes' == $settings['alpha_m_track_dir'] ) {
					$floating_settings['invertX'] = true;
					$floating_settings['invertY'] = true;
				} else {
					$floating_settings['invertX'] = false;
					$floating_settings['invertY'] = false;
				}

				if ( 'mouse_tracking_x' == $settings['alpha_floating'] ) {
					$floating_settings['limitY'] = '0';
				} elseif ( 'mouse_tracking_y' == $settings['alpha_floating'] ) {
					$floating_settings['limitX'] = '0';
				}

				if ( ! empty( $settings['builder'] ) && 'wpb' == $settings['builder'] ) {
					$speed = empty( $settings['alpha_m_track_speed'] ) ? 0.5 : floatval( $settings['alpha_m_track_speed'] );
				} else {
					$speed = empty( $settings['alpha_m_track_speed']['size'] ) ? 0.5 : floatval( $settings['alpha_m_track_speed']['size'] );
				}

				wp_enqueue_script( 'jquery-floating' );
				$options = $options +
					array(
						'data-plugin'         => 'floating',
						'data-options'        => json_encode( $floating_settings ),
						'data-floating-depth' => $speed,
					);
			} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_' ) ) { // scrolling effect
				$scroll_settings = array();
				$pos             = isset( $settings['alpha_scroll_stop'] ) ? esc_attr( $settings['alpha_scroll_stop'] ) : 'center';

				if ( ! empty( $settings['builder'] ) && 'wpb' == $settings['builder'] ) {
					$size = empty( $settings['alpha_scroll_size'] ) ? 50 : floatval( $settings['alpha_scroll_size'] );
				} else {
					$size = empty( $settings['alpha_scroll_size']['size'] ) ? 50 : floatval( $settings['alpha_scroll_size']['size'] );
				}

				if ( ! isset( $scroll_settings['options'] ) ) {
					$scroll_settings['options'] = array();
				}

				if ( 0 === strpos( $settings['alpha_floating'], 'skr_transform_' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_transform_up':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, ' . $size . '%);';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'transform: translate(0, -' . $size . '%);';
							break;
						case 'skr_transform_down':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, -' . $size . '%);';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'transform: translate(0, ' . $size . '%);';
							break;
						case 'skr_transform_left':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(' . $size . '%, 0);';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'transform: translate(-' . $size . '%, 0);';
							break;
						case 'skr_transform_right':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(-' . $size . '%, 0);';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'transform: translate(' . $size . '%, 0);';
							break;
					}
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_fade_in' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_fade_in':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, 0); opacity: 0;';
							break;
						case 'skr_fade_in_up':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, ' . $size . '%); opacity: 0;';
							break;
						case 'skr_fade_in_down':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, -' . $size . '%); opacity: 0;';
							break;
						case 'skr_fade_in_left':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(' . $size . '%, 0); opacity: 0;';
							break;
						case 'skr_fade_in_right':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(-' . $size . '%, 0); opacity: 0;';
							break;
					}

					$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0%, 0%); opacity: 1;';
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_fade_out' ) ) {
					$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0%, 0%); opacity: 1;';

					switch ( $settings['alpha_floating'] ) {
						case 'skr_fade_out':
							$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0, 0); opacity: 0;';
							break;
						case 'skr_fade_out_up':
							$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0, -' . $size . '%); opacity: 0;';
							break;
						case 'skr_fade_out_down':
							$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0, ' . $size . '%); opacity: 0;';
							break;
						case 'skr_fade_out_left':
							$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(-' . $size . '%, 0); opacity: 0;';
							break;
						case 'skr_fade_out_right':
							$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(' . $size . '%, 0); opacity: 0;';
							break;
					}
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_flip' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_flip_x':
							$scroll_settings['options']['data-bottom-top'] = 'transform: perspective(20cm) rotateY(' . $size . 'deg)';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'transform: perspective(20cm), rotateY(-' . $size . 'deg)';
							break;
						case 'skr_flip_y':
							$scroll_settings['options']['data-bottom-top'] = 'transform: perspective(20cm) rotateX(-' . $size . 'deg)';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'transform: perspective(20cm), rotateX(' . $size . 'deg)';
							break;
					}
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_rotate' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_rotate':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, 0) rotate(-' . ( 360 * $size / 50 ) . 'deg);';
							break;
						case 'skr_rotate_left':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(' . ( 100 * $size / 50 ) . '%, 0) rotate(-' . ( 360 * $size / 50 ) . 'deg);';
							break;
						case 'skr_rotate_right':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(-' . ( 100 * $size / 50 ) . '%, 0) rotate(-' . ( 360 * $size / 50 ) . 'deg);';
							break;
					}

					$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0, 0) rotate(0deg);';
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_zoom_in' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_zoom_in':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, 0) scale(' . ( 1 - $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_in_up':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, ' . ( 40 + $size ) . '%) scale(' . ( 1 - $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_in_down':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, -' . ( 40 + $size ) . '%) scale(' . ( 1 - $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_in_left':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(' . $size . '%, 0) scale(' . ( 1 - $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_in_right':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(-' . $size . '%, 0) scale(' . ( 1 - $size / 100 ) . '); transform-origin: center;';
							break;
					}

					$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0, 0) scale(1);';
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_zoom_out' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_zoom_out':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, 0) scale(' . ( 1 + $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_out_up':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, ' . ( 40 + $size ) . '%) scale(' . ( 1 + $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_out_down':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(0, -' . ( 40 + $size ) . '%) scale(' . ( 1 + $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_out_left':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(' . $size . '%, 0) scale(' . ( 1 + $size / 100 ) . '); transform-origin: center;';
							break;
						case 'skr_zoom_out_right':
							$scroll_settings['options']['data-bottom-top'] = 'transform: translate(-' . $size . '%, 0) scale(' . ( 1 + $size / 100 ) . '); transform-origin: center;';
							break;
					}

					$scroll_settings['options'][ 'data-' . $pos ] = 'transform: translate(0, 0) scale(1);';
				} elseif ( 0 === strpos( $settings['alpha_floating'], 'skr_horizontal_zoom_' ) ) {
					switch ( $settings['alpha_floating'] ) {
						case 'skr_horizontal_zoom_in':
							$scroll_settings['options']['data-bottom-top'] = 'width: ' . $size . '%; margin: 0 auto;';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'width: 100%; margin: 0 auto;';
							break;
						case 'skr_horizontal_zoom_out':
							$scroll_settings['options']['data-bottom-top'] = 'width: 100%; margin: 0 auto;';
							$scroll_settings['options'][ 'data-' . $pos ]  = 'width: ' . $size . '%; margin: 0 auto;';
							break;
					}
				}

				wp_enqueue_script( 'jquery-skrollr' );
				$options = $options +
					array(
						'data-plugin'  => 'skrollr',
						'data-options' => json_encode( $scroll_settings['options'] ),
					);
			}
			return $options;
		}
	}
	Alpha_Floating_Elementor::get_instance();
}
