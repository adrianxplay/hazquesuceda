<?php
/**
 * Addon Partial
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

if ( ! function_exists( 'alpha_elementor_addon_controls' ) ) {
	/**
	* Register elementor custom addons for elements and widgets.
	*
	* @since 1.0
	*/
	function alpha_elementor_addon_controls( $self, $source = '' ) {

		/**
		 * Fires after add elementor addon controls.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_elementor_addon_controls', $self, $source );
	}
}
