<?php
/**
 * Alpha Builder Footer class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.2
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;

class Alpha_Footer_Builder extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.2
	 */
	public function __construct() {

		// Add Controls
		add_action( 'elementor/documents/register_controls', array( $this, 'register_elementor_footer_controls' ) );
	}

	/**
	 * Add footer builder's preview controls for elementor
	 *
	 * @since 4.2
	 * @access public
	 * @param object $document
	 */
	public function register_elementor_footer_controls( $document ) {
		if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		// Add Template Builder Controls
		$id = (int) $document->get_main_id();

		if ( $id && ALPHA_NAME . '_template' == get_post_type( $id ) && 'footer' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {

			$document->start_controls_section(
				'alpha_footer_settings',
				array(
					'label' => alpha_elementor_panel_heading( esc_html__( 'Footer Settings', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_SETTINGS,
				)
			);

			$document->add_control(
				'alpha_fixed_footer',
				array(
					'label' => esc_html__( 'Is Fixed?', 'alpha-core' ),
					'type'  => Elementor\Controls_Manager::SWITCHER,
				)
			);

			$document->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'footer_background',
					'types'    => array( 'classic', 'gradient' ),
					'selector' => '.footer-' . $id,
				)
			);

			$document->end_controls_section();
		}
	}
}

Alpha_Footer_Builder::get_instance();
