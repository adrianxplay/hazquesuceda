<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Custom Cursor
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

if ( ! class_exists( 'Alpha_Sticky_Container_Elementor_Widget_Addon' ) ) {
	class Alpha_Sticky_Container_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// For container
			add_action( 'alpha_elementor_container_addon_controls', array( $this, 'add_sticky_container_controls' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_tabs', array( $this, 'add_sticky_container_tab' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_content_template', array( $this, 'container_addon_content_template' ) );
			add_filter( 'alpha_elementor_container_addon_render_attributes', array( $this, 'container_addon_attributes' ), 10, 3 );
		}

		/**
		 * Add switcher control to section element
		 *
		 * @since 1.0
		 */
		public function add_sticky_container_controls( $self ) {
			$self->add_control(
				'section_content_sticky',
				array(
					'label' => esc_html__( 'Sticky Content', 'alpha-core' ),
					'type'  => Controls_Manager::SWITCHER,
				)
			);
		}

		/**
		 * Add controls tab to section element
		 *
		 * @since 1.0
		 */
		public function add_sticky_container_tab( $self ) {
			$self->start_controls_section(
				'alpha_sticky_content_section',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Sticky Content', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						'section_content_sticky' => 'yes',
					),
				)
			);

				$self->add_control(
					'sticky_position',
					array(
						'label'       => esc_html__( 'Sticky At', 'alpha-core' ),
						'description' => esc_html__( 'Make the container as sticky on top of screen or inner content of the container sticky when neighboring containers are taller.', 'alpha-core' ),
						'type'        => Controls_Manager::SELECT,
						'options'     => array(
							'top'  => esc_html__( 'Top', 'alpha-core' ),
							'side' => esc_html__( 'Side', 'alpha-core' ),
						),
						'default'     => '',
					)
				);

				$self->add_responsive_control(
					'section_sticky_padding',
					array(
						'label'      => esc_html__( 'Sticky Padding', 'alpha-core' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
						'selectors'  => array(
							'{{WRAPPER}}.fixed' => '--padding-block-start: {{TOP}}{{UNIT}}; --padding-inline-end: {{RIGHT}}{{UNIT}}; --padding-block-end: {{BOTTOM}}{{UNIT}}; --padding-inline-start: {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'sticky_position' => 'top',
						),
					)
				);

				$self->add_control(
					'section_sticky_bg',
					array(
						'label'     => esc_html__( 'Sticky Background', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}}.fixed' => 'background-color: {{VALUE}}',
						),
						'condition' => array(
							'sticky_position' => 'top',
						),
					)
				);

				$self->add_control(
					'section_sticky_blur',
					array(
						'label'       => esc_html__( 'Blur Effect', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'description' => esc_html__( 'Enable to make the contents blurry under sticky content. The background of sticky content should be alpha color value.', 'alpha-core' ),
						'selectors'   => array(
							'{{WRAPPER}}.fixed' => 'backdrop-filter: blur(30px)',
						),
						'condition'   => array(
							'sticky_position' => 'top',
						),
					)
				);

				$self->add_responsive_control(
					'sticky_column_top',
					array(
						'type'       => Controls_Manager::SLIDER,
						'label'      => esc_html__( 'Top Space on Sticky', 'alpha-core' ),
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'range'      => array(
							'px' => array(
								'step' => 1,
								'min'  => 1,
								'max'  => 100,
							),
							'%'  => array(
								'step' => 1,
								'min'  => 1,
								'max'  => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} > .e-con-custom-inner' => 'top: {{SIZE}}{{UNIT}}',
						),
						'condition'  => array(
							'sticky_position' => 'side',
						),
					)
				);

			$self->end_controls_section();
		}

		/**
		 * Print scroll section content in elementor section content template function
		 *
		 * @since 1.0
		 */
		public function container_addon_content_template( $self ) {
			?>
			<#
			if ( 'yes' == settings.section_content_sticky ) {
				if ( 'side' == settings.sticky_position ) { 
					view.addRenderAttribute( 'con-data', 'data-sticky', 'side' );
				} else if ( 'top' == settings.sticky_position ) {
					view.addRenderAttribute( 'con-data', 'data-sticky', 'top' );
				}
			} else {
				view.addRenderAttribute( 'con-data', 'data-sticky', false );
			}
			#>
			<?php
		}

		/**
		 * Render scroll section HTML
		 *
		 * @since 1.0
		 */
		public function container_addon_attributes( $options, $self, $settings ) {
			if ( ! empty( $settings ) && 'yes' == $settings['section_content_sticky'] ) {
				if ( 'top' == $settings['sticky_position'] ) {
					$options['class'] .= ' sticky-content fix-top';

				} elseif ( 'side' == $settings['sticky_position'] ) { // Sticky Column
					$options['class'] .= ' alpha-sticky-column';
				}
			}
			return $options;
		}
	}
}

/**
 * Create instance
 *
 * @since 1.0
 */
Alpha_Sticky_Container_Elementor_Widget_Addon::get_instance();
