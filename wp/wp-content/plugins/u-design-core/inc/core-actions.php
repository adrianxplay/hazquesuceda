<?php
/**
 * Core Actions & Filters
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

add_filter( 'alpha_gutenberg_blocks', 'alpha_remove_gutenberg_iconbox' );

if ( ! function_exists( 'alpha_remove_gutenberg_iconbox' ) ) {
	function alpha_remove_gutenberg_iconbox( $vars ) {
		unset( $vars['icon-box'] );
		return $vars;
	}
}

add_action(
	'alpha_after_framework_init',
	function() {
		if ( class_exists( 'WooCommerce' ) ) {
			// Shop Builder
			remove_filter( 'loop_shop_per_page', 'alpha_loop_shop_per_page' );
			add_filter( 'wp', 'alpha_loop_shop_per_page' );
		}
	}
);

if ( ! function_exists( 'alpha_loop_shop_per_page' ) ) {
	function alpha_loop_shop_per_page( $count_select = '' ) {
		if ( ! empty( $_GET['count'] ) ) {
			return (int) $_GET['count'];
		}
		if ( ! is_array( $count_select ) ) {

			$count_select = '';

			if ( ! $count_select ) {
				/**
				 * Filters the count of showing products.
				 *
				 * @since 1.0
				 */
				$count_select = apply_filters( 'alpha_products_count_select', alpha_get_option( 'products_count_select' ) );
			}

			if ( $count_select ) {
				$count_select = explode( ',', str_replace( ' ', '', $count_select ) );
			} else {
				$count_select = array( '9', '_12', '24', '36' );
			}
		}

		$default = $count_select[0];

		foreach ( $count_select as $num ) {
			if ( is_string( $num ) && '_' == substr( $num, 0, 1 ) ) {
				$default = (int) str_replace( '_', '', $num );
				break;
			}
		}

		return $default;
	}
}

// Get layout options from theme option.
// Woocommerce Shop
add_action(
	'init',
	function() {
		if ( class_exists( 'Alpha_Layout_Builder' ) ) {
			remove_action( 'wp', array( Alpha_Layout_Builder::get_instance(), 'setup_layout' ), 5 );
			remove_filter( 'alpha_layout_default_args', array( Alpha_Layout_Builder::get_instance(), 'get_layout_default_args' ), 10, 2 );
		}
	}
);
