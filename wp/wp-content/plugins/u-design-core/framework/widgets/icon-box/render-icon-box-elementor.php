<?php
/**
 * Render template for block widget.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'icon'       => 'fas fa-star',
			'icon_view'  => '',
			'icon_shape' => 'icon-circle',
			'title'      => '',
			'desc'       => '',
			'link'       => '',
			'icon_pos'   => '',
			'title_tag'  => 'h3',
			'wrap_class' => '',
		),
		$atts
	)
);

// dynamic content
if ( ! empty( $atts['icon_source'] ) && ! empty( $atts['icon_dynamic_content'] ) && ! empty( $atts['icon_dynamic_content']['source'] ) ) {
	$icon = apply_filters( 'alpha_dynamic_tags_content', '', null, $atts['icon_dynamic_content'] );
}
if ( ! empty( $atts['title_source'] ) && ! empty( $atts['title_dynamic_content'] ) && ! empty( $atts['title_dynamic_content']['source'] ) ) {
	$title = apply_filters( 'alpha_dynamic_tags_content', '', null, $atts['title_dynamic_content'] );
}
if ( ! empty( $atts['desc_source'] ) && ! empty( $atts['desc_dynamic_content'] ) && ! empty( $atts['desc_dynamic_content']['source'] ) ) {
	$desc = apply_filters( 'alpha_dynamic_tags_content', '', null, $atts['desc_dynamic_content'] );
}
if ( ! empty( $atts['link_source'] ) && ! empty( $atts['link_dynamic_content'] ) && ! empty( $atts['link_dynamic_content']['source'] ) ) {
	$link = apply_filters( 'alpha_dynamic_tags_content', '', null, $atts['link_dynamic_content'] );
}

if ( ! empty( $icon_view ) ) {
	$wrap_class .= ' ' . $icon_view;
}

if ( ! empty( $icon_shape ) ) {
	$wrap_class .= ' ' . $icon_shape;
}

if ( ! empty( $icon_pos ) ) {
	$wrap_class .= ' ' . $icon_pos;
}
wp_enqueue_style( 'alpha-icon-box', alpha_core_framework_uri( '/widgets/icon-box/icon-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

?>
<div class="<?php echo esc_attr( 'icon-box ' . $wrap_class ); ?>">
	<div class="icon-box-icon">
		<?php if ( ! empty( $link ) ) : ?>
			<a href="<?php echo esc_url( $link ); ?>">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			</a>
		<?php else : ?>
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
		<?php endif; ?>
	</div>
	<div class="icon-box-content">
		<<?php echo esc_html( $title_tag ); ?> class="icon-box-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
		<p><?php echo esc_html( $desc ); ?></p>
	</div>
</div>

