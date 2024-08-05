<?php  // @codingStandardsIgnoreLine ?>
<div class="vendor-widget vendor-widget-3 vendor-widget-banner">
	<figure class="vendor-banner">
		<?php echo wp_get_attachment_image( $vendor_info['banner'], 'full', false, array( 'alt' => $vendor_info['store_name'] ) ); ?> 
	</figure>

	<div class="vendor-details">
		<?php if ( $visible['avatar'] ) : ?>
		<figure class="vendor-logo">
			<a href="<?php echo esc_url( $vendor_info['store_url'] ); ?>">
			<?php
			if ( class_exists( 'WCFM' ) ) {
				echo wp_get_attachment_image( $vendor_info['avatar_id'], 60 );
			} else {
				echo get_avatar( $vendor_info['id'], 60, '', $vendor_info['store_name'] );
			}
			?>
			</a>
		</figure>
		<?php endif; ?>

		<?php if ( $visible['name'] ) : ?>
		<h4 class="vendor-name">
			<a href="<?php echo esc_url( $vendor_info['store_url'] ); ?>" title="<?php echo esc_attr( $vendor_info['store_name'] ); ?>"><?php echo esc_html( $vendor_info['store_name'] ); ?></a>
		</h4>
		<?php endif; ?>

		<?php if ( $visible['rating'] ) : ?>
		<div class="ratings-container">
			<?php
			echo wc_get_rating_html( $vendor_info['rating'] );
			?>
		</div>
		<?php endif; ?>

		<?php if ( $visible['product_count'] ) : ?>
		<p class="vendor-products-count"><?php printf( esc_html__( '%s Products', 'alpha-core' ), (int) $vendor_info['products_count'] ); ?> </p>
		<?php endif; ?>

		<?php if ( 'yes' == $show_vendor_link ) : ?>
		<a href="<?php echo esc_url( $vendor_info['store_url'] ); ?>" class="visit-vendor-btn" title="<?php echo esc_attr( $vendor_info['store_name'] ); ?>"><?php echo esc_html( $vendor_link_text ); ?></a>
		<?php endif; ?>

		<?php if ( $visible['products'] && $list->have_posts() ) : ?>
		<div class="vendor-products row cols-3 gutter-sm">
			<?php
			$index = 0;
			while ( $list->have_posts() && $index < 3 ) {
				global $post;
				$list->the_post();
				?>
				<figure class="product-media">
					<a href="<?php esc_url( the_permalink() ); ?>">
					<?php
					echo get_the_post_thumbnail( $post->ID, $thumbnail_size );
					?>
					</a>
				</figure>
				<?php
				$index++;
			}
			wp_reset_postdata();
			?>
		</div>
		<?php endif; ?>
	</div>
</div>
