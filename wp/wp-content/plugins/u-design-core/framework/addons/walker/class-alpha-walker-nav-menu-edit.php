<?php

/**
 * Class Alpha_Walker_Nav_Menu_Edit
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

class Alpha_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

	/**
	 * Start the element output.
	 *
	 * @since 1.0
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$default = '';
		$substr  = '<p class="field-link-target description">';

		ob_start();
		do_action( 'alpha_add_custom_fields', $item->ID, $item, $depth, $args );
		$custom = ob_get_clean();

		parent::start_el( $default, $item, $depth, $args, $id );

		$output .= str_replace( $substr, $custom . $substr, $default );
	}
}
