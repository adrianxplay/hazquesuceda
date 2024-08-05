<?php
/**
 * Header compare template
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
			'type'        => 'block',
			'show_icon'   => true,
			'icon_pos'    => 'yes',
			'show_badge'  => true,
			'show_label'  => true,
			'icon'        => ALPHA_ICON_PREFIX . '-icon-compare',
			'label'       => esc_html( 'Compare', 'alpha-core' ),
			'minicompare' => '',
		),
		$atts
	)
);

$minicompare = ! empty( $minicompare ) ? $minicompare : '';

$badge    = 0;
$prod_ids = array();
if ( ! class_exists( 'Alpha_Product_Compare' ) ) {
	return;
}

$cookie_name = Alpha_Product_Compare::get_instance()->compare_cookie_name();
if ( isset( $_COOKIE[ $cookie_name ] ) ) {
	$prod_ids = json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true );

	$badge = count( $prod_ids );
}

ob_start();

?>
<div class="mini-basket-empty">
	<i class="<?php echo THEME_ICON_PREFIX; ?>-icon-compare-empty"></i>
	<div class="mini-basket-empty-content">
		<p class="empty-msg"><?php esc_html_e( 'Compare list is empty.', 'alpha-core' ); ?></p>
		<a href="<?php echo wc_get_page_permalink( 'shop' ); ?>"><?php esc_html_e( 'Continue Shopping', 'alpha-core' ); ?><i class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-long-arrow-<?php echo is_rtl() ? 'left' : 'right'; ?>"></i></a>
	</div>
</div>

<?php

$empty_html = ob_get_clean();

if ( $minicompare ) {
	echo '<div class="dropdown compare-dropdown mini-basket-box ' . ( 'offcanvas' == $minicompare ? 'offcanvas ' : ' ' ) . esc_attr( $minicompare ) . '-type" data-minicompare-type="' . esc_attr( $minicompare ) . '">';
}

if ( '' === $type ) {
	$type = 'inline';
}
?>

<a class="offcanvas-open<?php echo esc_attr( $type ? ( ' ' . $type . '-type' ) : '' ); ?>" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'compare' ) ) ); ?>">
	<?php if ( $show_icon ) { ?>
		<?php if ( 'block' === $type || 'yes' === $icon_pos ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
			<?php if ( $show_badge ) { ?>
				<span class="compare-count"><?php echo absint( $badge ); ?></span>
			<?php } ?>
			</i>
		<?php } ?>
	<?php } ?>
	<?php if ( $show_label ) { ?>
		<span><?php echo esc_html( $label ); ?></span>		
	<?php } ?>
	<?php if ( $show_icon ) { ?>
		<?php if ( '' === $icon_pos && 'inline' === $type ) { ?>
			<i class="<?php echo esc_attr( $icon ); ?>">
				<?php if ( $show_badge ) { ?>
				<span class="compare-count"><?php echo absint( $badge ); ?></span>
				<?php } ?>
			</i>
		<?php } ?>
	<?php } ?>
</a>

<?php
if ( $minicompare ) {
	if ( 'offcanvas' == $minicompare ) {
		?>
		<div class="offcanvas-overlay"></div>
	<?php } ?>

	<div class="<?php echo ( 'offcanvas' === $minicompare ? 'offcanvas-content' : 'dropdown-box' ) . ( empty( $prod_ids ) ? ' empty' : '' ); ?>">
		<?php
		if ( 'offcanvas' == $minicompare ) {
			echo '<div class="popup-header"><h3>' . esc_html__( 'Compare', 'alpha-core' ) . '</h3><a class="btn btn-link btn-icon-right btn-close" href="#">' . esc_html__( 'close', 'alpha-core' ) . '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i></a></div>';
		}
		?>
		<div class="widget_compare_content">
			<?php
			if ( empty( $prod_ids ) ) {
				echo $empty_html;
			} else {
				?>
				<ul class="scrollable mini-list compare-list">
					<?php
					foreach ( $prod_ids as $id ) {
						$product = wc_get_product( $id );
						if ( $product ) {
							$product_name      = $product->get_data()['name'];
							$thumbnail         = $product->get_image( 'alpha-product-thumbnail', array( 'class' => 'do-not-lazyload' ) );
							$product_price     = $product->get_price_html();
							$product_permalink = $product->is_visible() ? $product->get_permalink() : '';

							if ( ! $product_price ) {
								$product_price = '';
							}

							echo '<li class="mini-item compare-item">';

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

							echo '<a href="#" class="remove remove_from_compare" data-product_id="' . absint( $id ) . '"><i class="fas fa-times"></i></a>';

							echo '</li>';
						}
					}
					?>
				</ul>
				<p class="compare-buttons buttons">
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'compare' ) ) ); ?>" class="btn btn-dark btn-md btn-block"><?php esc_html_e( 'Go To Compare List', 'alpha-core' ); ?></a>
				</p>
				<?php
			}
			echo '<script type="text/template" class="alpha-minicompare-no-item-html">' . $empty_html . '</script>';
			?>
		</div>
	</div>
</div>
	<?php
}
