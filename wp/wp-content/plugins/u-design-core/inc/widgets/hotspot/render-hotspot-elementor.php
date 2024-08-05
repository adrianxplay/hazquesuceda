<?php
/**
 * The hotspot render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */
use Elementor\Group_Control_Image_Size;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'type'           => 'html',
			'html'           => '',
			'block'          => '',
			'link'           => '',
			'product'        => '',
			'image'          => array(),
			'icon'           => '',
			'popup_position' => 'top',
			'el_class'       => '',
			'effect'         => '',
			'page_builder'   => '',
			'image_size'     => '',
		),
		$atts
	)
);

if ( $icon && ! is_array( $icon ) ) {
	$icon = json_decode( $icon, true );
	if ( isset( $icon['icon'] ) ) {
		$icon['value'] = $icon['icon'];
	} else {
		$icon['value'] = '';
	}
}

$url        = isset( $link['url'] ) && $link['url'] ? esc_url( $link['url'] ) : '';
$product_id = $product;

if ( ! is_numeric( $product_id ) && is_string( $product_id ) ) {
	$product_id = alpha_get_post_id_by_name( 'product', $product_id );
}

$hs_wrap_class   = array( 'hotspot-wrapper' );
$hs_wrap_class[] = $el_class;
// Type
$hs_wrap_class[] = 'hotspot-' . $type;

// Effect
if ( $effect ) {
	$hs_wrap_class[] = 'hotspot-' . $effect;
}

?>
<div class="<?php echo esc_attr( implode( ' ', $hs_wrap_class ) ); ?>">
	<?php if ( $url ) : ?>
		<a href="<?php echo esc_url( 'product' == $type && $product_id ? get_permalink( $product_id ) : $url ); ?>"
		<?php echo ( ( isset( $link['is_external'] ) && $link['is_external'] ) ? ' target="_blank"' : '' ) . ( ( isset( $link['nofollow'] ) && $link['nofollow'] ) ? ' rel="nofollow"' : '' ); ?>
		class="hotspot<?php echo ( ( 'product' == $type && $product_id ) ? ' btn-quickview' : '' ); ?>"<?php echo ( 'product' == $type && $product_id ) ? ( ' data-product="' . $product_id . '"' ) : ''; ?>>
		<?php else : ?>
		<span class="hotspot<?php echo ( ( 'product' == $type && $product_id ) ? ' btn-quickview' : '' ); ?>"<?php echo ( 'product' == $type && $product_id ) ? ( ' data-product="' . $product_id . '"' ) : ''; ?>>
		<?php endif; ?>

		<?php if ( $icon['value'] ) : ?>
			<i class="<?php echo esc_attr( $icon['value'] ); ?>"></i>
		<?php endif; ?>
		<?php if ( $url ) : ?>
		</a>
		<?php else : ?>
		</span><?php endif; ?>
	<?php if ( 'none' != $popup_position ) : ?>
	<div class="hotspot-box hotspot-box-<?php echo esc_attr( $popup_position ); ?>">
		<?php
		if ( 'html' == $type ) {
			echo do_shortcode( $html );
		} elseif ( 'block' == $type ) {
			alpha_print_template( $block );
		} elseif ( 'image' == $type ) {
			$image_html = '';
			if ( ! empty( $image ) ) {
				if ( $page_builder ) {
					$image_html = wp_get_attachment_image( $image, $image_size );
				} else {
					$image_html = Group_Control_Image_Size::get_attachment_image_html( $atts, 'image' );
				}
			}

			echo '<figure>' . $image_html . '</figure>';
		} elseif ( $product_id && class_exists( 'WooCommerce' ) ) {
			/**
			 * Enqueue styles and scripts for woocommerce.
			 *
			 * @since 1.2.0
			 */
			wp_enqueue_script( 'alpha-woocommerce' );
			wp_enqueue_style( 'alpha-product', alpha_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

			setup_postdata( $product_id );
			?>
			<div <?php wc_product_class( 'woocommerce product-widget', $product_id ); ?>>
				<div class="product-media">
					<a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
						<?php echo woocommerce_get_product_thumbnail(); ?>
					</a>
				</div>
				<div class="product-body">
					<h3 class="woocommerce-loop-product__title product-title">
						<a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( get_the_title( $product_id ) ); ?></a>
					</h3>
					<?php
					$product_item = wc_get_product( $product_id );
					if ( $product_item ) {
						if ( $price_html = $product_item->get_price_html() ) {
							echo '<span class="price">' . $price_html . '</span>';
						}
						woocommerce_template_loop_add_to_cart(
							array(
								'class' => implode(
									' ',
									array_filter(
										array(
											'btn-product-icon',
											'hotspot-product-action',
											'product_type_' . $product_item->get_type(),
											$product_item->is_purchasable() && $product_item->is_in_stock() ? 'add_to_cart_button' : '',
											$product_item->supports( 'ajax_add_to_cart' ) && $product_item->is_purchasable() && $product_item->is_in_stock() ? 'ajax_add_to_cart' : '',
										)
									)
								),
							)
						);
					}
					?>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		}
		?>
	</div>
	<?php endif; ?>
</div>
