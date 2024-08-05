<?php
/**
 * Header wishlist template
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'type'         => 'block',
			'show_label'   => true,
			'show_count'   => false,
			'show_icon'    => true,
			'icon_pos'     => true,
			'label'        => esc_html__( 'Wishlist', 'alpha-core' ),
			'icon'         => ALPHA_ICON_PREFIX . '-icon-heart',
			'miniwishlist' => '',
		),
		$atts
	)
);


if ( class_exists( 'YITH_WCWL' ) ) {
	$wc_link  = YITH_WCWL()->get_wishlist_url();
	$wc_count = yith_wcwl_count_products();

	$wishlist       = YITH_WCWL_Wishlist_Factory::get_current_wishlist( array() );
	$wishlist_items = array();
	if ( $wishlist && $wishlist->has_items() ) {
		$wishlist_items = $wishlist->get_items();
	}

	ob_start();
	
	?>
	<div class="mini-basket-empty">
		<i class="<?php echo THEME_ICON_PREFIX; ?>-icon-heart-empty"></i>
		<div class="mini-basket-empty-content">
			<p class="empty-msg"><?php esc_html_e( 'Wishlist is empty.', 'alpha-core' ); ?></p>
			<a href="<?php echo wc_get_page_permalink( 'shop' ); ?>"><?php esc_html_e( 'Continue Shopping', 'alpha-core' ); ?><i class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-long-arrow-<?php echo is_rtl() ? 'left' : 'right'; ?>"></i></a>
		</div>
	</div>

	<?php

	$empty_html = ob_get_clean();

	echo '<div class="dropdown wishlist-dropdown mini-basket-box' . ( $miniwishlist ? ( ( 'offcanvas' == $miniwishlist ? ' offcanvas ' : ' ' ) . esc_attr( $miniwishlist ) . '-type" data-miniwishlist-type="' . esc_attr( $miniwishlist ) ) : '' ) . '">';

	if ( '' === $type ) {
		$type = 'inline';
	}

	?>
	<a aria-label="Wishlist" class="wishlist offcanvas-open <?php echo esc_attr( $type . '-type' ); ?>" href="<?php echo esc_url( $wc_link ); ?>">
	<?php if ( $icon_pos || 'block' === $type ) : ?>
			<?php if ( $show_icon ) : ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<?php if ( $show_count ) : ?>
					<span class="wish-count"><?php echo esc_html( $wc_count ); ?></span>
				<?php endif; ?>
			</i>
				<?php
			endif;
		endif;
	?>
		<?php if ( $show_label ) : ?>
		<span><?php echo esc_html( $label ); ?></span>
		<?php endif; ?>
		<?php if ( ! $icon_pos && 'inline' === $type ) : ?>
			<?php if ( $show_icon ) : ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<?php if ( $show_count ) : ?>
				<span class="wish-count"><?php echo esc_html( $wc_count ); ?></span>
			<?php endif; ?>
			</i>
				<?php
			endif;
		endif;
		?>
	</a>
	<?php
	if ( $miniwishlist ) {
		if ( 'offcanvas' == $miniwishlist ) {
			echo '<div class="offcanvas-overlay"></div>';
		}
		?>

		<div class="<?php echo ( 'offcanvas' === $miniwishlist ? 'offcanvas-content' : 'dropdown-box' ) . ( empty( $wishlist_items ) ? ' empty' : '' ); ?>">
			<?php
			if ( 'offcanvas' == $miniwishlist ) {
				echo '<div class="popup-header"><h3>' . esc_html__( 'Wishlist', 'alpha-core' ) . '</h3><a class="btn btn-link btn-icon-after btn-close" href="#">' . esc_html__( 'close', 'alpha-core' ) . '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i></a></div>';
			}
			?>
			<div class="widget_wishlist_content">
				<?php
				if ( empty( $wishlist_items ) ) : 
					echo $empty_html;
				else :
					?>
					<ul class="scrollable mini-list wish-list">
					<?php
					foreach ( $wishlist_items as $item ) {
						$product = $item->get_product();
						if ( $product ) {
							$id                = $product->get_ID();
							$product_name      = $product->get_data()['name'];
							$thumbnail         = $product->get_image( 'alpha-product-thumbnail', array( 'class' => 'do-not-lazyload' ) );
							$product_price     = $product->get_price_html();
							$product_permalink = $product->is_visible() ? $product->get_permalink() : '';

							if ( ! $product_price ) {
								$product_price = '';
							}

							echo '<li class="mini-item wishlist-item">';

							echo '<div class="mini-item-meta">';

							if ( empty( $product_permalink ) ) {
								echo alpha_escaped( $product_name );
							} else {
								echo '<a href="' . esc_url( $product_permalink ) . '">' . $product_name . '</a>';
							}
							echo '<span class="quantity">' . $product_price . '</span>';

							echo '</div>';

							if ( empty( $product_permalink ) ) {
								echo alpha_escaped( $thumbnail );
							} else {
								echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumbnail . '</a>';
							}

							echo '<a href="#" class="remove remove_from_wishlist" data-product_id="' . $id . '"><i class="fas fa-times"></i></a>';

							echo '</li>';
						}
					}
					?>
					</ul>
					<p class="wishlist-buttons buttons">
						<a href="<?php echo esc_url( $wc_link ); ?>" class="btn btn-dark btn-md btn-block"><?php esc_html_e( 'Go To Wishlist', 'alpha-core' ); ?></a>
					</p>
					<?php
				endif;

				// print templates for js work
				echo '<script type="text/template" class="alpha-miniwishlist-no-item-html">' . $empty_html . '</script>';
				?>
			</div>
		</div>
		<?php
	}
	?>
	</div>
	<?php
} else {
	esc_html_e( 'Install YITH WooCommerce Wishlist plugin.', 'alpha-core' );
}
