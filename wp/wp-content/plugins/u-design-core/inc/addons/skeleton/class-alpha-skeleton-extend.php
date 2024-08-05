<?php
/**
 * Skeleton screen for lazyload.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */
class Alpha_Skeleton_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * Add actions and filters for skeleton.
	 * @since 1.0
	 */
	public function __construct() {
		// Posts (archive + single) skeleton
		remove_filter( 'alpha_post_loop_wrapper_classes', array( Alpha_Skeleton::get_instance(), 'post_loop_wrapper_class' ) );
		// remove_filter( 'alpha_post_single_class', array( Alpha_Skeleton::get_instance(), 'post_loop_wrapper_class' ) );
		add_filter( 'alpha_post_loop_wrapper_classes', array( $this, 'post_loop_wrapper_class' ) );
	}

	/**
	 * Post loop wrapper class
	 *
	 * @param array $classes The class list
	 * @since 1.0
	 */
	public function post_loop_wrapper_class( $classes ) {
		if ( ! Alpha_Skeleton::get_instance()->is_doing ) {
			$layout_type = alpha_get_page_layout();
			if ( 0 === strpos( $layout_type, 'single_' ) || 0 === strpos( $layout_type, 'archive_' ) ) {
				$classes[] = 'skeleton-body';
			}
		}
		return $classes;
	}
}

Alpha_Skeleton_Extend::get_instance();
