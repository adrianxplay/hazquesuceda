<?php
/**
 * Header mobile menu toggle template
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

// disable if mobile menu has no any items
if ( ! function_exists( 'alpha_get_option' ) || ! alpha_get_option( 'mobile_menu_items' ) ) {
	return;
}

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'icon_class' => '',
			'direction' => 'start',
		),
		$atts
	)
);
?>
<a href="#" class="mobile-menu-toggle d-lg-none direction-<?php echo esc_attr( $direction ); ?>" aria-label="<?php esc_attr_e( 'Mobile Menu', 'alpha-core' ); ?>">
	<i class="<?php echo esc_attr( $icon_class ? $icon_class : ALPHA_ICON_PREFIX . '-icon-hamburger', 'alpha-core' ); ?>"></i>
</a>
<?php

