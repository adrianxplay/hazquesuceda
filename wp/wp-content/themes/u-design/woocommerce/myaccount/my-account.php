<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || die;

if ( defined( 'ALPHA_CORE_VERSION' ) ) {
	wp_enqueue_style( 'alpha-icon-box', alpha_core_framework_uri( '/widgets/icon-box/icon-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
	wp_enqueue_style( 'alpha-share', alpha_core_framework_uri( '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
}

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
?>
<div class="row gutter-lg">
	<?php do_action( 'woocommerce_account_navigation' ); ?>
	<div class="woocommerce-MyAccount-content col-md-9 pt-2">
		<?php
			/**
			 * My Account content.
			 *
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_content' );
		?>
	</div>
</div>
