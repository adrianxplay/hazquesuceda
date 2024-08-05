<?php
/**
 * Header mini-cart template
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

if ( class_exists( 'WooCommerce' ) ) :
	extract( // @codingStandardsIgnoreLine
		shortcode_atts(
			array(
				'type'      => 'inline',
				'icon_type' => '',
				'icon_pos'  => '',
				'mini_cart' => 'dropdown',
				'title'     => '',
				'label'     => '',
				'price'     => '',
				'delimiter' => '',
				'pfx'       => '',
				'sfx'       => '',
				'icon'      => ALPHA_ICON_PREFIX . '-icon-cart',
			),
			$atts
		)
	);

	$type = $type ? $type . '-type' : '';

	if ( 'offcanvas' === $mini_cart ) {
		$extra_class = ' cart-offcanvas offcanvas offcanvas-type ';
	} else {
		$extra_class = '';
	}
	if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'cart_show_qty' ) ) {
		$extra_class .= ' cart-with-qty';
		wp_enqueue_style( 'alpha-minicart-quantity-input' );
		wp_enqueue_script( 'alpha-minicart-quantity-input' );
	}
	if ( '' === $type ) {
		$type = 'inline-type';
	}
	?>
	<div class="dropdown mini-basket-box cart-dropdown <?php echo esc_attr( $type . ( $icon_type ? ' ' . $icon_type . '-type ' : ' ' ) . $extra_class ); ?>">
		<a class="cart-toggle offcanvas-open" href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>">
		<?php if ( 'yes' === $icon_pos && 'inline-type' === $type ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<span class="cart-count"><i class="fas fa-spinner fa-pulse"></i></span>
			</i>
			<?php
		}
		if ( $title || $price ) :
			?>
			<div class="cart-label">
		<?php endif; ?>
				<?php if ( $title ) : ?>
				<span class="cart-name"><?php echo esc_html( $label ); ?></span>
					<?php if ( $delimiter ) : ?>
						<span class="cart-name-delimiter"><?php echo esc_html( $delimiter ); ?></span>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $price ) : ?>
				<span class="cart-price"><?php echo wc_price( 0 ); ?></span>
				<?php endif; ?>
			<?php if ( $title || $price ) : ?>
			</div>
			<?php endif; ?>
				<?php if ( 'yes' !== $icon_pos || 'block-type' === $type ) : ?>
					<?php if ( 'badge' === $icon_type ) : ?>
				<i class="<?php echo esc_attr( $icon ); ?>">
					<span class="cart-count"><i class="fas fa-spinner fa-pulse"></i></span>
				</i>
				<?php elseif ( 'label' === $icon_type ) : ?>
					<span class="cart-count-wrap">
					<?php
					$html = '';
					if ( $pfx ) {
						$html .= esc_html( $pfx );
					}
						$html .= '<span class="cart-count"><i class="fas fa-spinner fa-pulse"></i></span>';
					if ( $sfx ) {
						$html .= esc_html( $sfx );
					}
					echo alpha_escaped( $html );
					?>
					</span>
					<?php
					endif;
				endif;
				?>
		</a>
		<div class="cart-overlay offcanvas-overlay"></div>
		<div class="widget_shopping_cart <?php echo ( 'offcanvas' === $mini_cart ? 'offcanvas-content' : 'dropdown-box' ) . ( is_object( WC()->cart ) && WC()->cart->is_empty() ? ' empty' : '' ); ?>">
			<div class="widget_shopping_cart_content">
				<div class="cart-loading"></div>
			</div>
		</div>
	</div>
	<?php
endif;

