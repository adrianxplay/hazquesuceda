<?php
/**
 * Alpha IconList Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

$settings          = $this->get_settings_for_display();
$fallback_defaults = array(
	ALPHA_ICON_PREFIX . '-icon-heart',
	ALPHA_ICON_PREFIX . '-icon-star',
	ALPHA_ICON_PREFIX . '-icon-cog',
);

$this->add_render_attribute( 'icon_list', 'class', 'elementor-icon-list-items' );

if ( 'inline' === $settings['view'] ) {
	$this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
}
?>
<ul <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
	<?php
	foreach ( $settings['icon_list'] as $index => $item ) :



		$repeater_item_key  = $this->get_repeater_setting_key( 'item', 'icon_list', $index );
		$repeater_text_key  = $this->get_repeater_setting_key( 'text', 'icon_list', $index );
		$repeater_badge_key = $this->get_repeater_setting_key( 'badge_label', 'icon_list', $index );

		$this->add_render_attribute( $repeater_item_key, 'class', 'elementor-icon-list-item' );
		$this->add_render_attribute( $repeater_text_key, 'class', 'elementor-icon-list-text' );
		$this->add_render_attribute( $repeater_badge_key, 'class', 'elementor-icon-list-badge' );

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( $repeater_item_key, 'class', 'elementor-inline-item' );
		}
		$this->add_render_attribute( $repeater_item_key, 'class', 'elementor-repeater-item-' . $item['_id'] );

		$this->add_inline_editing_attributes( $repeater_text_key );
		$this->add_inline_editing_attributes( $repeater_badge_key );

		$migration_allowed = Elementor\Icons_Manager::is_migration_allowed();
		?>
		<li <?php $this->print_render_attribute_string( $repeater_item_key ); ?>>
			<?php
			if ( ! empty( $item['link']['url'] ) ) {
				$link_key = 'link_' . $index;

				$this->add_link_attributes( $link_key, $item['link'] );

				echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
			}

			// add old default
			if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
				$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : ALPHA_ICON_PREFIX . '-icon-check';
			}

			$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
			$is_new   = ! isset( $item['icon'] ) && $migration_allowed;
			if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) :
				?>
				<span class="elementor-icon-list-icon">
				<?php
				if ( $is_new || $migrated ) {
					Elementor\Icons_Manager::render_icon( $item['selected_icon'], array( 'aria-hidden' => 'true' ) );
				} else {
					?>
					<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
					<?php
				}
				?>
				</span>
			<?php endif; ?>
			<span <?php $this->print_render_attribute_string( $repeater_text_key ); ?>><?php echo alpha_strip_script_tags( $item['text'] ); ?></span>
			<?php if ( $item['badge_label'] ) : ?>
			<span <?php $this->print_render_attribute_string( $repeater_badge_key ); ?>><?php echo alpha_strip_script_tags( $item['badge_label'] ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $item['link']['url'] ) ) : ?>
				</a>
			<?php endif; ?>
		</li>
		<?php
	endforeach;
	?>
</ul>
