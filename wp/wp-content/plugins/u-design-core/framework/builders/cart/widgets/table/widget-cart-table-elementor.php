<?php
/**
 * Alpha Cart Table Elementor Widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;


class Alpha_Cart_Table_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_cart_table';
	}

	public function get_title() {
		return esc_html__( 'Cart Table', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-cart-table';
	}

	public function get_categories() {
		return array( 'alpha_cart_widget' );
	}

	public function get_keywords() {
		return array( 'woo', 'alpha', 'cart', 'table', 'checkout' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_coupons_content',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'cart_show_clear',
				array(
					'label'       => esc_html__( 'Clear Cart Button', 'alpha-core' ),
					'description' => esc_html__( 'Enable to show clear cart button.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
				)
			);

			$this->add_control(
				'cart_auto_update',
				array(
					'label'       => esc_html__( 'Cart Auto Update', 'alpha-core' ),
					'description' => esc_html__( 'Enable to update automatically on quantity change.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_coupons_style',
			array(
				'label' => esc_html__( 'Table', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'cell_padding',
				array(
					'label'      => esc_html__( 'Cell Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} tbody tr td, .elementor-element-{{ID}} thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'title_header',
				array(
					'label'     => esc_html__( 'Table Heading', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'header_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} thead tr th' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'header_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} thead tr th' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'header_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} thead tr th',
				)
			);

		$this->end_controls_section();
	}
	protected function render() {

		$settings = $this->get_settings_for_display();
		if ( ! is_object( WC()->cart ) || ( WC()->cart->is_empty() && ! alpha_is_elementor_preview() ) ) {
			return;
		}
		?>
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
				<thead>
					<tr>
						<th class="product-thumbnail"><?php esc_html_e( 'Product', 'alpha-core' ); ?></th>
						<th class="product-name">&nbsp;</th>
						<th class="product-price"><?php esc_html_e( 'Price', 'alpha-core' ); ?></th>
						<th class="product-quantity"><?php esc_html_e( 'Quantity', 'alpha-core' ); ?></th>
						<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'alpha-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

								<td class="product-thumbnail">
									<div class="product-thumbnail-inner">
									<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo alpha_strip_script_tags( $thumbnail ); // PHPCS: XSS ok.
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
									}
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove fas fa-times" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'alpha-core' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
									</div>
								</td>

								<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'alpha-core' ); ?>">
								<?php
								if ( ! $product_permalink ) {
									echo alpha_strip_script_tags( apply_filters( 'woocommerce_cart_item_name', esc_html( $_product->get_name() ), $cart_item, $cart_item_key ) . '&nbsp;' );
								} else {
									echo alpha_strip_script_tags( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $_product->get_name() ) ), $cart_item, $cart_item_key ) );
								}

								do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

								// Meta data.
								echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

								// Backorder notification.
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo alpha_strip_script_tags( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'alpha-core' ) . '</p>', $product_id ) );
								}
								?>
								</td>

								<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'alpha-core' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</td>

								<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'alpha-core' ); ?>">
								<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										),
										$_product,
										false
									);
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
								?>
								</td>

								<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'alpha-core' ); ?>">
									<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</td>
							</tr>
							<?php
						}
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<tr>
						<td colspan="6" class="actions">
							<div class="cart-actions">
								<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn btn-dark btn-rounded btn-icon-left continue-shopping <?php echo is_rtl() ? 'ms-auto' : 'me-auto'; ?>"><?php echo is_rtl() ? '' : '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-left"></i>'; ?><?php esc_html_e( 'Continue Shopping', 'alpha-core' ); ?><?php echo is_rtl() ? '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>' : ''; ?></a>
								<?php if ( 'yes' == $settings['cart_show_clear'] ) : ?>
									<button type="submit" class="btn btn-rounded btn-outline btn-default btn-border-thin clear-cart-button" name="clear_cart" value="<?php esc_attr_e( 'Clear cart', 'alpha-core' ); ?>"><?php esc_html_e( 'Clear cart', 'alpha-core' ); ?></button>
								<?php endif; ?>
								<?php if ( 'yes' != $settings['cart_auto_update'] ) : ?>
									<button type="submit" class="btn btn-rounded btn-outline btn-default btn-border-thin wc-action-btn" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'alpha-core' ); ?>"><?php esc_html_e( 'Update cart', 'alpha-core' ); ?></button>
								<?php endif; ?>
							</div>

							<?php if ( wc_coupons_enabled() ) { ?>
								<div id="cart_coupon_box" style="display: none;">
									<div class="form-row form-coupon">
										<input type="text" name="coupon_code" class="input-text form-control" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter coupon code here...', 'alpha-core' ); ?>">
										<button type="submit" name="apply_coupon" class="btn btn-rounded btn-border-thin btn-outline btn-dark" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
										<?php do_action( 'woocommerce_cart_coupon' ); ?>
									</div>
								</div>
							<?php } ?>
							<?php do_action( 'woocommerce_cart_actions' ); ?>
							<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						</td>
					</tr>
					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
		<?php
	}
}
