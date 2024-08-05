<?php
/**
 * Alpha Elementor Mask Addon
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.3.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Mask_Elementor' ) ) {
	/**
	 * Alpha Elementor Mask Addon
	 *
	 * @since 1.3.0
	 */
	class Alpha_Mask_Elementor extends Alpha_Base {

		/**
		 * Gets a string of CSS rules to apply, and returns an array of selectors with those rules.
		 * This function has been created in order to deal with masking for image widget.
		 * For most of the widgets the mask is being applied to the wrapper itself, but in the case of an image widget,
		 * the `img` tag should be masked directly. So instead of writing a lot of selectors every time,
		 * this function builds both of those selectors easily.
		 *
		 * @param $rules string The CSS rules to apply.
		 *
		 * @return array Selectors with the rules applied.
		 */
		private function get_mask_selectors( $src, $rules ) {
			if ( 'section' == $src || 'column' == $src || 'container' == $src ) {
				$mask_selectors = array(
					'wrappper' => '{{WRAPPER}}',
				);
				return array(
					$mask_selectors['wrappper'] => $rules,
				);
			}

			$mask_selectors = array(
				'default' => '{{WRAPPER}}:not( .elementor-widget-image ) .elementor-widget-container',
				'image'   => '{{WRAPPER}}.elementor-widget-image .elementor-widget-container img',
			);
			return array(
				$mask_selectors['default'] => $rules,
				$mask_selectors['image']   => $rules,
			);
		}

		/**
		 * Return a translated user-friendly list of the available masking shapes.
		 *
		 * @param bool $add_custom Determine if the output should contain `Custom` options.
		 *
		 * @return array Array of shapes with their URL as key.
		 */
		private function get_shapes( $add_custom = true ) {
			$shapes = array(
				'circle'   => esc_html__( 'Circle', 'alpha-core' ),
				'flower'   => esc_html__( 'Flower', 'alpha-core' ),
				'sketch'   => esc_html__( 'Sketch', 'alpha-core' ),
				'triangle' => esc_html__( 'Triangle', 'alpha-core' ),
				'blob'     => esc_html__( 'Blob', 'alpha-core' ),
				'hexagon'  => esc_html__( 'Hexagon', 'alpha-core' ),
			);

			if ( $add_custom ) {
				$shapes['custom'] = esc_html__( 'Custom', 'alpha-core' );
			}

			return $shapes;
		}

		/**
		 * The Constructor.
		 *
		 * @since 1.3.0
		 */
		public function __construct() {
			// Add controls to addon tab
			add_action( 'alpha_elementor_addon_controls', array( $this, 'add_controls' ), 10, 2 );
		}

		/**
		 * Add controls to addon tab.
		 *
		 * @since 1.3.0
		 */
		public function add_controls( $self, $source = '' ) {

			$left  = is_rtl() ? 'right' : 'left';
			$right = 'left' == $left ? 'right' : 'left';

			if ( 'banner' != $source ) {
				$self->start_controls_section(
					'_alpha_section_mask',
					array(
						'label' => esc_html__( 'Mask', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);

					$self->add_control(
						'_alpha_mask_switch',
						array(
							'label'     => esc_html__( 'Mask', 'alpha-core' ),
							'type'      => Controls_Manager::SWITCHER,
							'label_on'  => esc_html__( 'On', 'alpha-core' ),
							'label_off' => esc_html__( 'Off', 'alpha-core' ),
							'default'   => '',
						)
					);

					$self->add_control(
						'_alpha_mask_shape',
						array(
							'label'     => esc_html__( 'Shape', 'alpha-core' ),
							'type'      => Controls_Manager::SELECT,
							'options'   => $this->get_shapes(),
							'default'   => 'circle',
							'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-image: url( ' . ELEMENTOR_ASSETS_URL . '/mask-shapes/{{VALUE}}.svg );' ),
							'condition' => array(
								'_alpha_mask_switch!' => '',
							),
						)
					);

					$self->add_control(
						'_alpha_mask_image',
						array(
							'label'        => esc_html__( 'Image', 'alpha-core' ),
							'type'         => Controls_Manager::MEDIA,
							'media_type'   => 'image',
							'should_include_svg_inline_option' => true,
							'library_type' => 'image/svg+xml',
							'dynamic'      => array(
								'active' => true,
							),
							'selectors'    => $this->get_mask_selectors( $source, '-webkit-mask-image: url( {{URL}} );' ),
							'condition'    => array(
								'_alpha_mask_switch!' => '',
								'_alpha_mask_shape'   => 'custom',
							),
						)
					);

					$self->add_responsive_control(
						'_alpha_mask_size',
						array(
							'label'     => esc_html__( 'Size', 'alpha-core' ),
							'type'      => Controls_Manager::SELECT,
							'options'   => array(
								'contain' => esc_html__( 'Fit', 'alpha-core' ),
								'cover'   => esc_html__( 'Fill', 'alpha-core' ),
								'custom'  => esc_html__( 'Custom', 'alpha-core' ),
							),
							'default'   => 'contain',
							'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-size: {{VALUE}};' ),
							'condition' => array(
								'_alpha_mask_switch!' => '',
							),
						)
					);

					$self->add_responsive_control(
						'_alpha_mask_size_scale',
						array(
							'label'      => esc_html__( 'Scale', 'alpha-core' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px', 'em', '%', 'vw' ),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 500,
								),
								'em' => array(
									'min' => 0,
									'max' => 100,
								),
								'%'  => array(
									'min' => 0,
									'max' => 200,
								),
								'vw' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'default'    => array(
								'unit' => '%',
								'size' => 100,
							),
							'selectors'  => $this->get_mask_selectors( $source, '-webkit-mask-size: {{SIZE}}{{UNIT}};' ),
							'condition'  => array(
								'_alpha_mask_switch!' => '',
								'_alpha_mask_size'    => 'custom',
							),
						)
					);

					$self->add_responsive_control(
						'_alpha_mask_position',
						array(
							'label'     => esc_html__( 'Position', 'alpha-core' ),
							'type'      => Controls_Manager::SELECT,
							'options'   => array(
								'center center' => esc_html__( 'Center Center', 'alpha-core' ),
								'center left'   => esc_html__( 'Center Left', 'alpha-core' ),
								'center right'  => esc_html__( 'Center Right', 'alpha-core' ),
								'top center'    => esc_html__( 'Top Center', 'alpha-core' ),
								'top left'      => esc_html__( 'Top Left', 'alpha-core' ),
								'top right'     => esc_html__( 'Top Right', 'alpha-core' ),
								'bottom center' => esc_html__( 'Bottom Center', 'alpha-core' ),
								'bottom left'   => esc_html__( 'Bottom Left', 'alpha-core' ),
								'bottom right'  => esc_html__( 'Bottom Right', 'alpha-core' ),
								'custom'        => esc_html__( 'Custom', 'alpha-core' ),
							),
							'default'   => 'center center',
							'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-position: {{VALUE}};' ),
							'condition' => array(
								'_alpha_mask_switch!' => '',
							),
						)
					);

					$self->add_responsive_control(
						'_alpha_mask_position_x',
						array(
							'label'      => esc_html__( 'X Position', 'alpha-core' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px', 'em', '%', 'vw' ),
							'range'      => array(
								'px' => array(
									'min' => -500,
									'max' => 500,
								),
								'em' => array(
									'min' => -100,
									'max' => 100,
								),
								'%'  => array(
									'min' => -100,
									'max' => 100,
								),
								'vw' => array(
									'min' => -100,
									'max' => 100,
								),
							),
							'default'    => array(
								'unit' => '%',
								'size' => 0,
							),
							'selectors'  => $this->get_mask_selectors( $source, '-webkit-mask-position-x: {{SIZE}}{{UNIT}};' ),
							'condition'  => array(
								'_alpha_mask_switch!'  => '',
								'_alpha_mask_position' => 'custom',
							),
						)
					);

					$self->add_responsive_control(
						'_alpha_mask_position_y',
						array(
							'label'      => esc_html__( 'Y Position', 'alpha-core' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px', 'em', '%', 'vw' ),
							'range'      => array(
								'px' => array(
									'min' => -500,
									'max' => 500,
								),
								'em' => array(
									'min' => -100,
									'max' => 100,
								),
								'%'  => array(
									'min' => -100,
									'max' => 100,
								),
								'vw' => array(
									'min' => -100,
									'max' => 100,
								),
							),
							'default'    => array(
								'unit' => '%',
								'size' => 0,
							),
							'selectors'  => $this->get_mask_selectors( $source, '-webkit-mask-position-y: {{SIZE}}{{UNIT}};' ),
							'condition'  => array(
								'_alpha_mask_switch!'  => '',
								'_alpha_mask_position' => 'custom',
							),
						)
					);

					$self->add_responsive_control(
						'_alpha_mask_repeat',
						array(
							'label'     => esc_html__( 'Repeat', 'alpha-core' ),
							'type'      => Controls_Manager::SELECT,
							'options'   => array(
								'no-repeat' => esc_html__( 'No-Repeat', 'alpha-core' ),
								'repeat'    => esc_html__( 'Repeat', 'alpha-core' ),
								'repeat-x'  => esc_html__( 'Repeat-X', 'alpha-core' ),
								'repeat-Y'  => esc_html__( 'Repeat-Y', 'alpha-core' ),
								'round'     => esc_html__( 'Round', 'alpha-core' ),
								'space'     => esc_html__( 'Space', 'alpha-core' ),
							),
							'default'   => 'no-repeat',
							'selectors' => $this->get_mask_selectors( $source, '-webkit-mask-repeat: {{VALUE}};' ),
							'condition' => array(
								'_alpha_mask_switch!' => '',
								'_alpha_mask_size!'   => 'cover',
							),
						)
					);

				$self->end_controls_section();
			}
		}
	}
	Alpha_Mask_Elementor::get_instance();
}
