<?php
/**
 * Alpha Shop Builder class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_Template_Shop_Builder_Extend extends Alpha_Base {
	/**
	 * Constructor
	 *
	 * @since 4.1
	 */
	public function __construct() {
		add_filter( 'alpha_shop_widgets', array( $this, 'shop_builder_widgets' ) );
	}

	public function shop_builder_widgets( $widgets ) {
		$widgets = array_merge(
			$widgets,
			array(
				'pagination' => true,
			)
		);
		return $widgets;
	}
}

new Alpha_Template_Shop_Builder_Extend;
