<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Stretch Container
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! class_exists( 'Alpha_Stretch_Container_Elementor_Widget_Addon' ) ) {
	class Alpha_Stretch_Container_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'alpha_elementor_container_addon_controls', array( $this, 'add_scroll_section_controls' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_tabs', array( $this, 'add_stretch_container_tab' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_content_template', array( $this, 'container_addon_content_template' ) );
			add_filter( 'alpha_elementor_container_addon_render_attributes', array( $this, 'container_addon_attributes' ), 10, 3 );
		}

		/**
		 * Add banner controls to section element
		 *
		 * @since 1.0
		 */
		public function add_scroll_section_controls( $self, $condition_value ) {
			$self->add_control(
				'stretch_container',
				array(
					'label' => esc_html__( 'Stretch Container', 'alpha-core' ),
					'type'  => Controls_Manager::SWITCHER,
				)
			);
		}

		/**
		 * Add controls tab to section element
		 *
		 * @since 1.0
		 */
		public function add_stretch_container_tab( $self ) {
			$self->start_controls_section(
				'alpha_stretch_con_section',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Stretch Container', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						'stretch_container' => 'yes',
					),
				)
			);

				$self->add_control(
					'stretch_container_dir',
					array(
						'label'   => esc_html__( 'Stretch Out To', 'alpha-core' ),
						'type'    => Controls_Manager::CHOOSE,
						'default' => 'full',
						'options' => array(
							'full'  => array(
								'title' => esc_html__( 'Full Width', 'alpha-core' ),
								'icon'  => 'eicon-h-align-stretch',
							),
							'left'  => array(
								'title' => esc_html__( 'Left', 'alpha-core' ),
								'icon'  => 'eicon-h-align-left',
							),
							'right' => array(
								'title' => esc_html__( 'Right', 'alpha-core' ),
								'icon'  => 'eicon-h-align-right',
							),
						),
					)
				);

			$breakpoints       = new Breakpoints_Manager();
			$breakpoints_value = $breakpoints->get_breakpoints_config();

			$options = array( '0' => esc_html__( 'Mobile', 'alpha-core' ) );
			foreach ( $breakpoints_value as $key => $breakpoint ) {
				if ( $breakpoint['is_enabled'] ) {
					$options[ $key ] = $breakpoint['label'];
				}
			}

				$self->add_control(
					'stretch_min_width',
					array(
						'label'     => esc_html__( 'Stretch Out Until', 'alpha-core' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => $options,
						'default'   => '0',
						'condition' => array(
							'stretch_container_dir!' => 'full',
						),
					)
				);

			$self->end_controls_section();
		}

		/**
		 * Print scroll section content in elementor container template function
		 *
		 * @since 1.0
		 */
		public function container_addon_content_template( $self ) {
			?>
			<#
			view.addRenderAttribute( 'con-data', 'class', 'con-data' );
			if ( settings.stretch_container == 'yes' ) { 
				view.addRenderAttribute( 'con-data', 'data-stretch', settings.stretch_container_dir );
				view.addRenderAttribute( 'con-data', 'data-stretch-width', typeof elementor.breakpoints.responsiveConfig.activeBreakpoints[settings.stretch_min_width] != 'undefined' ? elementor.breakpoints.responsiveConfig.activeBreakpoints[settings.stretch_min_width].value : 767 );
			}
			#>
			<?php
		}

		/**
		 * Add render attributes for scroll section in container mode
		 *
		 * @since 1.0
		 */
		public function container_addon_attributes( $options, $self, $settings ) {
			if ( 'yes' == $settings['stretch_container'] ) {
				$breakpoints       = new Breakpoints_Manager();
				$breakpoints_value = $breakpoints->get_breakpoints_config();

				$options['data-stretch']       = $settings['stretch_container_dir'];
				$options['data-stretch-width'] = isset( $breakpoints_value[ $settings['stretch_min_width'] ]['value'] ) ? $breakpoints_value[ $settings['stretch_min_width'] ]['value'] : 0;
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
Alpha_Stretch_Container_Elementor_Widget_Addon::get_instance();
