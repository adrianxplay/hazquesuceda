<?php
/**
 * Alpha Single Product Builder
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

class Alpha_Single_Product_Builder_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		if ( class_exists( 'Alpha_Single_Product_Builder' ) ) {
			remove_filter( 'alpha_run_single_product_builder', array( Alpha_Single_Product_Builder::get_instance(), 'run_template' ) );
			add_filter( 'alpha_run_single_product_builder', array( $this, 'run_template' ) );
		}
		add_filter( 'alpha_single_product_widgets', array( $this, 'single_product_builder_widgets' ) );
	}

	public function single_product_builder_widgets( $widgets ) {
		$widgets = array_merge(
			$widgets,
			array(
				'title'           => true,
				'meta'            => true,
				'excerpt'         => true,
				'vendor_products' => true,
				'attributes'      => true,
			)
		);
		array_multisort( array_keys( $widgets ), SORT_ASC, $widgets );
		return $widgets;
	}

	/**
	 * Run builder template
	 *
	 * @since 1.0
	 * @access public
	 * @param boolean $run
	 * @return boolean $run
	 */
	public function run_template( $run ) {

		if ( ! Alpha_Single_Product_Builder::get_instance()->is_product_layout ) {
			return $run;
		}

		global $post;
		if ( $post && ALPHA_NAME . '_template' == $post->post_type && 'product_layout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
			the_content();
			return true;

		} else {
			global $alpha_layout;
			if ( isset( $alpha_layout['single_product_type'] ) && 'builder' == $alpha_layout['single_product_type'] ) {
				if ( ! empty( $alpha_layout['single_product_block'] ) && is_numeric( $alpha_layout['single_product_block'] ) ) {
					$template = (int) $alpha_layout['single_product_block'];
					do_action( 'alpha_before_single_product_template', $template );
					alpha_print_template( $template );
					do_action( 'alpha_after_single_product_template', $template );
					return true;
				} elseif ( ! empty( $alpha_layout['single_product_block'] ) && 'hide' == $alpha_layout['single_product_block'] ) {
					// hide
					return true;
				}
			}
		}

		return $run;
	}
}

new Alpha_Single_Product_Builder_Extend;
