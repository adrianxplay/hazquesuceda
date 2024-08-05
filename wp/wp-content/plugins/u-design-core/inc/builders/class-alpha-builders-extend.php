<?php
/**
 * Alpha Template
 *
 * @author     Andon
 * @package    Alpha Core
 * @subpackage Core
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;

if ( ! class_exists( 'Alpha_Builders_Extend' ) ) {
	class Alpha_Builders_Extend {

		/**
		 * The Constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {

			// Builder Extends
			require_once ALPHA_CORE_INC . '/builders/header/class-alpha-header-builder-extend.php';
			require_once ALPHA_CORE_INC . '/builders/footer/class-alpha-footer-builder.php';

			if ( class_exists( 'WooCommerce' ) ) {
				require_once ALPHA_CORE_INC . '/builders/shop/class-alpha-shop-builder-extend.php';
			}

			// Should be called after framework class exists
			add_action( 'alpha_after_core_framework_builders', array( $this, 'after_framework_builders_init' ) );
		}

		public function after_framework_builders_init() {
			if ( class_exists( 'WooCommerce' ) ) {
				require_once ALPHA_CORE_INC . '/builders/single-product/class-alpha-single-product-builder-extend.php';
			}
		}
	}

	new Alpha_Builders_Extend;
}
