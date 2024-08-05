<?php

$attrs = '';
global $product;
if ( ! empty( $product ) && isset( $atts['link_source'] ) ) {
	$common_cls = 'alpha-tb-woo-link';
	$icon_pos   = empty( $atts['icon_pos'] ) ? 'left' : $atts['icon_pos'];

	$icon_html = '';
	if ( 'cart' == $atts['link_source'] && 'variable' === $product->get_type() ) {
		if ( ! empty( $atts['icon_cls_variable'] ) ) {
			$icon_html .= '<i class="' . esc_attr( $atts['icon_cls_variable'] ) . '"></i>';
			if ( empty( $atts['hide_title'] ) ) {
				$common_cls .= ' alpha-tb-icon-' . $icon_pos;
			}
		}
	} elseif ( ! empty( $atts['icon_cls'] ) ) {
		$icon_html .= '<i class="' . esc_attr( $atts['icon_cls'] ) . '"></i>';
		if ( empty( $atts['hide_title'] ) ) {
			$common_cls .= ' alpha-tb-icon-' . $icon_pos;
		}
	}
	if ( ! empty( $atts['el_class'] ) && wp_is_json_request() ) {
		$common_cls .= ' ' . esc_attr( $atts['el_class'] );
	}
	if ( ! empty( $atts['className'] ) ) {
		$common_cls .= ' ' . esc_attr( trim( $atts['className'] ) );
	}

	if ( 'cart' == $atts['link_source'] ) {
		$args = array(
			'quantity'              => 1,
			'attributes'            => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);
		if ( method_exists( $product, 'add_to_cart_aria_describedby' ) ) {
			$args['aria-describedby_text'] = $product->add_to_cart_aria_describedby();
		}

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $args, $product );

		$tag = 'a';

		$btn_classes = $common_cls . ' alpha-tb-addcart add_to_cart_button product_type_' . $product->get_type();
		if ( isset( $args['class'] ) ) {
			$btn_classes .= ' ' . trim( $args['class'] );
		}
		if ( $product->is_purchasable() && $product->is_in_stock() ) {
			if ( $product->supports( 'ajax_add_to_cart' ) ) {
				$btn_classes .= ' ajax_add_to_cart';
			}
		} else {
			$btn_classes .= ' add_to_cart_read_more';
		}

		if ( ! empty( $atts['show_quantity_input'] ) ) {
			woocommerce_quantity_input(
				array(
					'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
					'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
					'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( sanitize_text_field( wp_unslash( $_POST['quantity'] ) ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
				)
			);
		}

		$link = $product->add_to_cart_url();
		if ( class_exists( 'Uni_Cpo' ) ) {
			$product_id   = (int) $product->get_id();
			$product_data = Uni_Cpo_Product::get_product_data_by_id( $product_id );
			if ( 'on' === $product_data['settings_data']['cpo_enable'] ) {
				$link = get_permalink( $product_id );
			}
		}

		echo apply_filters(
			'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
			sprintf(
				'<%s href="%s" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_%s" data-quantity="%s" class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $btn_classes, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons' ) ) . ' %s" %s>%s</%s>',
				$tag,
				esc_attr( $product->get_id() ),
				esc_url( $link ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( ( isset( $args['class'] ) ? $args['class'] : '' ) . ( $product->is_purchasable() && $product->is_in_stock() ? '' : ' add_to_cart_read_more' ) ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				( 'right' != $icon_pos ? $icon_html : '' ) . ( empty( $atts['hide_title'] ) ? esc_html( $product->add_to_cart_text() ) : '' ) . ( 'right' == $icon_pos ? $icon_html : '' ),
				$tag
			),
			$product,
			isset( $args ) ? $args : array()
		);
		
		if ( isset( $args['aria-describedby_text'] ) ) {
			?>
			<span id="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr( $product->get_id() ); ?>" class="screen-reader-text">
				<?php echo esc_html( $args['aria-describedby_text'] ); ?>
			</span>
		<?php
		}

	} elseif ( 'wishlist' == $atts['link_source'] && defined( 'YITH_WCWL' ) ) {
		$exists    = YITH_WCWL()->is_product_in_wishlist( $product->get_id() );
		$shortcode = '[yith_wcwl_add_to_wishlist container_classes="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $common_cls . ' alpha-tb-wishlist', $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons' ) ) . '"';
		if ( ! empty( $atts['icon_cls'] ) ) {
			$shortcode .= ' icon="' . esc_attr( $atts['icon_cls'] ) . '"';
		}
		$shortcode .= ']';
		echo do_shortcode( $shortcode );
	} elseif ( 'compare' == $atts['link_source'] && alpha_get_option( 'compare_available' ) && class_exists( 'Alpha_Product_Compare' ) ) {
		$common_cls .= ' alpha-tb-compare compare';
		$product_id  = $product->get_id();
		$url         = '#';
		$compared    = false;

		if ( Alpha_Product_Compare::get_instance()->is_compared_product( $product_id ) ) {
			$url         = get_permalink( wc_get_page_id( 'compare' ) );
			$common_cls .= ' added';
			$compared    = true;
			/**
			 * Filters the added label of woocompare.
			 *
			 * @since 1.0
			 */
			$button_text = apply_filters( 'alpha_woocompare_added_label', esc_html__( 'Added', 'alpha-core' ) );

			if ( ! empty( $atts['icon_cls_added'] ) ) {
				$icon_html = '<i class="' . esc_attr( $atts['icon_cls_added'] ) . '"></i>';
			}
		} else {
			/**
			 * Filters the add label of woocompare.
			 *
			 * @since 1.0
			 */
			$button_text = apply_filters( 'alpha_woocompare_add_label', esc_html__( 'Compare', 'alpha-core' ) );
		}

		$inner_html_escaped = '';
		if ( empty( $atts['hide_title'] ) ) {
			$inner_html_escaped = esc_html( $button_text );
		}
		if ( $icon_html ) {
			if ( 'right' != $icon_pos ) {
				$inner_html_escaped = $icon_html . $inner_html_escaped;
			} else {
				$inner_html_escaped .= $icon_html;
			}
		}

		printf( '<a href="%s" class="%s" title="%s" data-product_id="%d" data-add-text="%s" data-added-text="%s"' . ( ! empty( $atts['icon_cls'] ) ? ' data-add-icon="' . esc_attr( $atts['icon_cls'] ) . '"' : '' ) . ( ! empty( $atts['icon_cls_added'] ) ? ' data-added-icon="' . esc_attr( $atts['icon_cls_added'] ) . '"' : '' ) . '>%s</a>', esc_url( $url ), esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $common_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons' ) ), esc_html( $button_text ), $product_id, esc_html( apply_filters( 'alpha_woocompare_add_label', esc_html__( 'Compare', 'alpha-core' ) ) ), esc_html( apply_filters( 'alpha_woocompare_added_label', esc_html__( 'Added', 'alpha-core' ) ) ), $inner_html_escaped );

	} elseif ( 'quickview' == $atts['link_source'] ) {
		if ( ! wp_script_is( 'wc-add-to-cart-variation' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		$label = __( 'Quick View', 'alpha-core' );

		$inner_html_escaped = '';
		if ( empty( $atts['hide_title'] ) ) {
			$inner_html_escaped = esc_html( $label );
		}
		if ( $icon_html ) {
			if ( 'right' != $icon_pos ) {
				$inner_html_escaped = $icon_html . $inner_html_escaped;
			} else {
				$inner_html_escaped .= $icon_html;
			}
		}
		echo '<a href="#" class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $common_cls . ' alpha-tb-quickview btn-quickview', $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons' ) ) . '" data-mfp-src="' . alpha_get_product_featured_image_src( $product ) . '" data-product="' . absint( $product->get_id() ) . '" title="' . esc_attr( $label ) . '">' . $inner_html_escaped . '</a>';

	} elseif ( 'swatch' == $atts['link_source'] ) {
		if ( ! wp_script_is( 'wc-add-to-cart-variation' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		ob_start();
		do_action( 'alpha_wc_product_listed_attributes' );
		$html_escaped = ob_get_clean();
		if ( $html_escaped ) {
			echo '<div class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $common_cls . ' alpha-tb-swatch', $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-buttons' ) ) . '">';
			echo alpha_escaped( $html_escaped );
			echo '</div>';
		}
	}
}
