<?php
/**
 * Alpha Yith Wishlist
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
if ( ! class_exists( 'Alpha_Core_Wishlist' ) ) {

	class Alpha_Core_Wishlist extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// YITH Wishlist Admin Page
			add_filter( 'yith_wcwl_add_to_wishlist_options', array( $this, 'yith_wcwl_wishlist_options' ) );
		}

		public function yith_wcwl_wishlist_options( $args ) {
			$remove_options = array(
				'shop_page_section_start',
				'show_on_loop',
				'loop_position',
				'shop_page_section_end',
			);
			foreach ( $remove_options as $option ) {
				unset( $args['add_to_wishlist'][ $option ] );
			}
			return $args;
		}
	}
}

Alpha_Core_Wishlist::get_instance();
