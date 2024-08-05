<?php //@codingStandardsIgnoreLine
/**
 * Price List Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

extract( //@codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'price_list'                  => array(),
			'hide_items_with_empty_price' => false,
		),
		$atts
	)
);

$this->add_render_attribute( 'price_list', 'class', 'alpha-price-lists' );
$this->add_render_attribute( 'list_item', 'class', 'alpha-price-list-item' );


if ( is_array( $price_list ) && ! empty( $price_list ) ) {
	?>
	<ul <?php $this->print_render_attribute_string( 'price_list' ); ?>>
		<?php
		foreach ( $price_list as $index => $item ) {

			if ( $hide_items_with_empty_price && isset( $item['item_price'] ) && ! $item['item_price'] ) {
				continue;
			}

			$item_title_key = $this->get_repeater_setting_key( 'item_title', 'price_list', $index );
			$item_price_key = $this->get_repeater_setting_key( 'item_price', 'price_list', $index );
			$item_desc_key  = $this->get_repeater_setting_key( 'item_text', 'price_list', $index );

			$this->add_render_attribute( $item_title_key, 'class', 'alpha-price-list-title' );
			$this->add_render_attribute( $item_price_key, 'class', 'alpha-price-list-price' );
			$this->add_render_attribute( $item_desc_key, 'class', 'alpha-price-list-desc' );

			$this->add_inline_editing_attributes( $item_title_key );
			$this->add_inline_editing_attributes( $item_price_key );
			$this->add_inline_editing_attributes( $item_desc_key );
			?>
			<li <?php $this->print_render_attribute_string( 'list_item' ); ?>>
				<div class="alpha-price-list-main">
					<?php
					echo
					sprintf(
						'<a href="%1$s" %2$s %3$s>',
						esc_url( ! empty( $item['item_url']['url'] ) ? $item['item_url']['url'] : '#' ),
						$item['item_url']['is_external'] && 'target="_blank"',
						$item['item_url']['nofollow'] && 'rel="nofollow"'
					);
					?>
						<h5 <?php $this->print_render_attribute_string( $item_title_key ); ?>><?php echo esc_html( $item['item_title'] ); ?></h5>
					</a>
					<span></span>
					<div <?php $this->print_render_attribute_string( $item_price_key ); ?>><?php echo esc_html( $item['item_price'] ); ?></div>
				</div>
				<p <?php $this->print_render_attribute_string( $item_desc_key ); ?>><?php echo esc_html( $item['item_text'] ); ?></p>
				<?php if ( isset( $item['item_image']['id'] ) && $item['item_image']['id'] ) : ?>
					<figure class="price-hover-image" data-img="<?php echo esc_url( $item['item_image']['url'] ); ?>">
						<div class="price-hover-wrap">
							<?php echo wp_get_attachment_image( $item['item_image']['id'], 'full' ); ?>
						</div>
					</figure>
				<?php endif; ?>
			</li>
			<?php
		}
		?>
	</ul>
	<?php
}
