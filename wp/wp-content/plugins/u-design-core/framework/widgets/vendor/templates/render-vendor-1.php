<?php // @codingStandardsIgnoreLine ?>
<div class="vendor-widget vendor-widget-1"> 
	<?php if ( $visible['products'] && $list->have_posts() ) : ?>
	<div class="vendor-products grid-type gutter-xs">
		<?php
		$index = 0;
		while ( $list->have_posts() && $index < 3 ) {
			global $post;
			$list->the_post();
			$class = ( 0 == $index ) ? 'large-item' : ( ( 1 == $index ) ? 'small-item small-item-1' : 'small-item small-item-2' );
			?>
			<figure class="product-media <?php echo esc_attr( $class ); ?>">
				<a href="<?php esc_url( the_permalink() ); ?>">
				<?php
				echo get_the_post_thumbnail( $post->ID, ( 'large-item' == $class ) ? $thumbnail_size : 'shop_thumbnail' );
				?>
				</a>
			</figure>
			<?php
			++ $index;
		}
		wp_reset_postdata();
		?>
	</div>
	<?php endif; ?>
	<div class="vendor-details">

		<?php if ( $visible['avatar'] ) : ?>
		<figure class="vendor-logo">
			<a href="<?php echo esc_url( $vendor_info['store_url'] ); ?>">
			<?php
			if ( class_exists( 'WCFM' ) ) {
				echo wp_get_attachment_image( $vendor_info['avatar_id'], 60, false, array( 'alt' => $vendor_info['store_name'] ) );
			} else {
				echo get_avatar( $vendor_info['id'], 60, '', $vendor_info['store_name'] );
			}
			?>
			</a>
		</figure>
		<?php endif; ?>

		<?php if ( $visible['name'] || $visible['product_count'] || $visible['rating'] || 'yes' == $show_total_sale ) : ?>
		<div class="vendor-personal">
			<?php if ( $visible['name'] ) : ?>
			<h4 class="vendor-name">
				<a href="<?php echo esc_url( $vendor_info['store_url'] ); ?>"  title="<?php echo esc_attr( $vendor_info['store_name'] ); ?>"><?php echo esc_html( $vendor_info['store_name'] ); ?></a>
			</h4>
			<?php endif; ?>

			<?php if ( $visible['product_count'] ) : ?>
			<span class="vendor-products-count">(<?php printf( __( '%s Products', 'alpha-core' ), (int) $vendor_info['products_count'] ); ?>)</span>
			<?php endif; ?>

			<?php if ( $visible['rating'] ) : ?>
			<div class="ratings-container">
				<?php echo wc_get_rating_html( $vendor_info['rating'] ); ?>
			</div>
			<?php endif; ?>

			<?php if ( 'yes' == $show_total_sale ) : ?>
			<p class="vendor-sale">
				<?php echo get_woocommerce_currency_symbol() . round( $vendor_info['total_sale'], 2 ) . esc_html__( ' earned', 'alpha-core' ); ?>
			</p>
			<?php endif; ?>

			<?php if ( 'yes' == $show_vendor_link ) : ?>
				<a href="<?php echo esc_url( $vendor_info['store_url'] ); ?>" class="visit-vendor-btn" title="<?php echo esc_attr( $vendor_info['store_name'] ); ?>"><?php echo esc_html( $vendor_link_text ); ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</div>
