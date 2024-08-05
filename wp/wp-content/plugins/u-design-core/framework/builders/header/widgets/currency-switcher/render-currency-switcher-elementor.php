<?php
/**
 * Header currency switcher template
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

if ( has_nav_menu( 'cur-switcher' ) ) {
	wp_nav_menu(
		array(
			'theme_location'  => 'cur-switcher',
			'container'       => 'nav',
			'container_class' => '',
			'items_wrap'      => '<ul id="%1$s" class="menu switcher cur-switcher">%3$s</ul>',
			'walker'          => new Alpha_Walker_Nav_Menu(),
		)
	);
} elseif ( class_exists( 'WOOCS' ) ) {
	global $WOOCS;
	$currencies       = $WOOCS->get_currencies();
	$current_currency = $WOOCS->current_currency;

	$active_c = '';
	$other_c  = '';

	foreach ( $currencies as $currency ) {
		$label = ( $currency['flag'] ? '<span class="flag"><img src="' . esc_url( $currency['flag'] ) . '" height="12" alt="' . esc_attr( $currency['name'] ) . '" width="18" /></span>' : '' ) . esc_html( $currency['name'] . ' ' . $currency['symbol'] );
		if ( $currency['name'] == $current_currency ) {
			$active_c .= $label;
		} else {
			$other_c .= '<li rel="' . esc_attr( $currency['name'] ) . '"><a href="#">' . alpha_strip_script_tags( $label ) . '</a></li>';
		}
	}
	?>
	<ul class="menu switcher cur-switcher">
		<a href="#" class="switcher-toggle"><?php echo alpha_strip_script_tags( $active_c ); ?></a>
		<?php if ( $other_c ) { ?>
		<ul>
			<?php echo alpha_strip_script_tags( $other_c ); ?>
		</ul>
		<?php } ?>
	</ul>
	<?php
} elseif ( class_exists( 'WCML_Multi_Currency' ) ) {
	global $sitepress, $woocommerce_wpml;

	if ( $woocommerce_wpml->multi_currency ) {
		$settings      = $woocommerce_wpml->get_settings();
		/**
		 * Filters wcml multi currency by default.
		 *
		 * @since 1.0
		 */
		$format        = apply_filters( 'alpha_wcml_multi_currency_format', '%symbol% %code%' );
		$wc_currencies = get_woocommerce_currencies();
		if ( ! isset( $settings['currencies_order'] ) ) {
			$currencies = $woocommerce_wpml->multi_currency->get_currency_codes();
		} else {
			$currencies = $settings['currencies_order'];
		}
		$active_c = '';
		$other_c  = '';

		foreach ( $currencies as $currency ) {
			if ( 1 == $woocommerce_wpml->settings['currency_options'][ $currency ]['languages'][ $sitepress->get_current_language() ] ) {
				$selected        = $currency == $woocommerce_wpml->multi_currency->get_client_currency() ? ' selected="selected"' : '';
				$currency_format = preg_replace(
					array( '#%name%#', '#%symbol%#', '#%code%#' ),
					array( $wc_currencies[ $currency ], get_woocommerce_currency_symbol( $currency ), $currency ),
					$format
				);

				if ( $selected ) {
					$active_c .= $currency_format;
				} else {
					$other_c .= '<li><a href="#" rel="' . esc_attr( $currency ) . '">' . alpha_strip_script_tags( $currency_format ) . '</a></li>';
				}

				if ( 1 == count( $currencies ) ) {
					$active_c = $currency_format;
					$other_c .= '<li><a href="#" rel="' . esc_attr( $currency ) . '">' . alpha_strip_script_tags( $currency_format ) . '</a></li>';
				}
			}
		}
		?>
		<ul class="menu switcher cur-switcher wcml_currency_switcher" id="menu-currency-switcher">
			<li class="menu-item-has-children">
				<a class="switcher-toggle" href="#"><?php echo alpha_strip_script_tags( $active_c ); ?></a>
				<?php if ( $other_c ) { ?>
				<ul class="wcml-cs-submenu wcml-switcher">
					<?php echo alpha_strip_script_tags( $other_c ); ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
		<?php
	}
} else {
	?>
	<ul class="menu switcher cur-switcher">
		<li class="menu-item-has-children">
			<a class="switcher-toggle" href="#"><?php esc_html_e( 'USD', 'alpha-core' ); ?></a>
			<ul>
				<li><a href="#"><?php esc_html_e( 'EUR', 'alpha-core' ); ?></a></li>
				<li><a href="#"><?php esc_html_e( 'USD', 'alpha-core' ); ?></a></li>
			</ul>
		</li>
	</ul>
	<?php
}
