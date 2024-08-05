<?php
/**
 * Alpha WooCommerce Archive Product Functions
 *
 * Functions used to display archive product.
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

/**
 * Alpha shop page show type
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_wc_shop_show_type' ) ) {
	function alpha_wc_shop_show_type() {
		$mode = 'grid';
		if ( ! empty( $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) ) {
			$mode = $_COOKIE[ ALPHA_NAME . '_gridcookie' ];
		}
		?>
		<div class="toolbox-item toolbox-show-type">
			<a href="#" class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-grid btn-showtype mode-grid<?php echo 'grid' == $mode ? ' active' : ''; ?>" aria-label="<?php esc_attr_e( 'Grid Layout', 'alpha' ); ?>"></a>
			<a href="#" class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-list btn-showtype mode-list<?php echo 'list' == $mode ? ' active' : ''; ?>" aria-label="<?php esc_attr_e( 'List Layout', 'alpha' ); ?>"></a>
		</div>
		<?php
		do_action( 'alpha_wc_archive_after_toolbox' );
	}
}
