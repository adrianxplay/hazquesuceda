<?php
/**
 * Alpha_Sidebar_Builder_Extend class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

class Alpha_Sidebar_Builder_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * Add actions and filters for sidbar widgets.
	 *
	 * @since 4.0
	 */
	public function __construct() {
		add_filter( 'alpha_sidebar_widgets', array( $this, 'add_widgets' ) );
	}

	/**
	 * Add sidebar widgets for the Theme.
	 *
	 * @since 4.0
	 */
	public function add_widgets( $widgets ) {
		$add = array( 'posts' );
		if ( class_exists( 'WooCommerce' ) ) {
			$add[] = 'products';
			$add[] = 'product_status';
		}
		return array_merge( $widgets, $add );
	}
}

Alpha_Sidebar_Builder_Extend::get_instance();
