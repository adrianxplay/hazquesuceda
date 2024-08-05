<?php
/**
 * Alpha Elementor Custom Css & Js
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.2.0
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Custom_Elementor' ) ) {
	/**
	 * Alpha Elementor Custom Css & Js
	 *
	 * @since 1.2.0
	 */
	class Alpha_Custom_Elementor extends Alpha_Base {

		/**
		 * The Constructor.
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			// Add controls to addon tab
			add_action( 'alpha_elementor_addon_controls', array( $this, 'add_controls' ), 99, 2 );
		}

		/**
		 * Add controls to addon tab.
		 *
		 * @since 1.2.0
		 */

		public function add_controls( $self, $source = '' ) {

			if ( 'banner' != $source ) {
				$self->start_controls_section(
					'_alpha_section_custom_css',
					array(
						'label' => esc_html__( 'Custom Page CSS', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);

					$self->add_control(
						'_alpha_custom_css',
						array(
							'type' => Controls_Manager::TEXTAREA,
							'rows' => 40,
						)
					);

				$self->end_controls_section();

				$self->start_controls_section(
					'_alpha_section_custom_js',
					array(
						'label' => esc_html__( 'Custom Page JS', 'alpha-core' ),
						'tab'   => Alpha_Widget_Advanced_Tabs::TAB_CUSTOM,
					)
				);

					$self->add_control(
						'_alpha_custom_js',
						array(
							'type' => Controls_Manager::TEXTAREA,
							'rows' => 40,
						)
					);

				$self->end_controls_section();
			}
		}

	}
	Alpha_Custom_Elementor::get_instance();
}
