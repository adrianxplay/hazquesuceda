<?php
/**
 * The hotspot render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
use Elementor\Group_Control_Image_Size;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'type'           => 'html',
			'html'           => '',
			'block'          => '',
			'link'           => '#',
			'product'        => '',
			'image'          => array(),
			'icon'           => '',
			'popup_position' => 'top',
			'el_class'       => '',
			'effect'         => '',
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

$product_id = $product;

if ( ! empty( $product_id ) && ! is_numeric( $product_id ) && is_string( $product_id ) ) {
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

$attrs           = [];
$url             = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '#';
$attrs['href']   = 'product' == $type && $product_id ? esc_url( get_permalink( $product_id ) ) : $url;
$attrs['target'] = ! empty( $link['is_external'] ) ? '_blank' : '';
$attrs['rel']    = ! empty( $link['nofollow'] ) ? 'nofollow' : '';
if ( ! empty( $link['custom_attributes'] ) ) {
	foreach ( explode( ',', $link['custom_attributes'] ) as $attr ) {
		$key   = explode( '|', $attr )[0];
		$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
		if ( isset( $attrs[ $key ] ) ) {
			$attrs[ $key ] .= ' ' . $value;
		} else {
			$attrs[ $key ] = $value;
		}
	}
}
$link_attrs = '';
foreach ( $attrs as $key => $value ) {
	if ( ! empty( $value ) ) {
		$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
	}
}
$product_item = wc_get_product( $product_id );
?>
<div class="<?php echo esc_attr( implode( ' ', $hs_wrap_class ) ); ?>">
	<a <?php echo alpha_escaped( $link_attrs ); ?> class="hotspot<?php echo ( ( 'product' == $type && $product_id ) ? ' btn-quickview' : '' ); ?>"<?php echo ( 'product' == $type && $product_id ) ? ( ' data-product="' . $product_id . '"' ) : ''; ?>
		<?php echo ( ( 'product' == $type && $product_id && function_exists( 'alpha_get_product_featured_image_src' ) ) ? ' data-mfp-src="' . alpha_get_product_featured_image_src( $product_item ) . '"' : '' ); ?>>

		<?php
		if ( $icon['value'] ) {
			if ( isset( $icon['library'] ) && 'svg' == $icon['library'] ) {
				\ELEMENTOR\Icons_Manager::render_icon(
					array(
						'library' => 'svg',
						'value'   => array( 'id' => absint( isset( $icon['value']['id'] ) ? $icon['value']['id'] : 0 ) ),
					),
					array( 'aria-hidden' => 'true' )
				);
			} else {
				?>
				<i class="<?php echo esc_attr( $icon['value'] ); ?>"></i>
				<?php
			}
		}
		?>
	</a>
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
				$image_html = Group_Control_Image_Size::get_attachment_image_html( $atts, 'image' );
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
					<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
						<?php echo woocommerce_get_product_thumbnail(); ?>
					</a>
					<div class="product-action-vertical product-loop">
						<?php
						woocommerce_template_loop_add_to_cart(
							array(
								'class' => implode(
									' ',
									array_filter(
										array(
											'btn-product-icon',
											'product_type_' . $product_item->get_type(),
											$product_item->is_purchasable() && $product_item->is_in_stock() ? 'add_to_cart_button' : '',
											$product_item->supports( 'ajax_add_to_cart' ) && $product_item->is_purchasable() && $product_item->is_in_stock() ? 'ajax_add_to_cart' : '',
										)
									)
								),
							)
						);
						?>
					</div>
				</div>
				<div class="product-body">
					<h3 class="woocommerce-loop-product__title product-title">
						<a href="<?php echo esc_url( get_the_permalink( $product_id ) ); ?>"><?php echo esc_html( get_the_title( $product_id ) ); ?></a>
					</h3>
					<?php woocommerce_template_loop_price(); ?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<?php endif; ?>
</div>
