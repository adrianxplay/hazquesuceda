<?php
/**
 * Lazyload template
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 *
 */
defined( 'ABSPATH' ) || die;
?>
<div class="wizard-header">
	<div class="wizard-headings">
		<h2 class="wizard-title"><?php esc_html_e( 'Lazyload', 'alpha' ); ?></h2>
		<p class="wizard-description"><?php esc_html_e( 'This will help you make your site faster by lazyloading images and contents.', 'alpha' ); ?></p>
	</div>
	<div class="wizard-action">
		<a href="#" class="button-success button button-large button-recommendations" data-recommend="lazyload"><?php echo '<i class="' . ALPHA_ICON_PREFIX . '-icon-verification"></i>' . esc_html__( 'Apply Recommendations', 'alpha' ); ?></a>
	</div>
</div>

<form method="post" class="alpha_submit_form">
	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="lazyload" <?php checked( alpha_get_option( 'lazyload' ) ); ?>> <?php esc_html_e( 'Lazyload Images', 'alpha' ); ?>
	</label>
	<p style="margin: 10px 0 20px;">
		<?php esc_html_e( "All image resources will be lazyloaded so that page's loading speed gets faster.", 'alpha' ); ?>
		<?php esc_html_e( 'Use with caution! Disable this option if you have any compability problems.', 'alpha' ); ?>
	</p>

	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="lazyload_menu" <?php checked( alpha_get_option( 'lazyload_menu' ) ); ?>> <?php esc_html_e( 'Lazyload Menus', 'alpha' ); ?>
	</label>
	<p style="margin: 10px 0 20px;">
		<?php esc_html_e( 'Menus will be lazyloaded and cached in browsers for faster load.', 'alpha' ); ?>
		<?php esc_html_e( 'Cached menus will be updated after they have been changed or customizer panel has been saved.', 'alpha' ); ?>
	</p>

	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="skeleton" <?php checked( alpha_get_option( 'skeleton_screen' ) ); ?>> <?php esc_html_e( 'Skeleton Screen', 'alpha' ); ?>
	</label>
	<p style="margin: 10px 0 20px;"><?php esc_html_e( 'Instead of real content, skeleton is used to enhance speed of page loading and makes it more beautiful.', 'alpha' ); ?></p>

	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="webfont" <?php checked( alpha_get_option( 'google_webfont' ) ); ?>> <?php esc_html_e( 'Enable Google Web Font Lazyload', 'alpha' ); ?>
	</label>
	<p style="margin: 10px 0 20px;">
	<?php
		printf(
			/* translators: %s values are docs urls */
			esc_html__( 'Using %1$sWeb Font Loader%2$s, you can enhance page loading speed by about 4 percent in %3$sGoogle PageSpeed Insights%4$s for both mobile and desktop.', 'alpha' ),
			'<a href="https://developers.google.com/fonts/docs/webfont_loader" target="_blank">',
			'</a>',
			'<a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank">',
			'</a>'
		);
		?>
	</p>
	<?php
	/**
	 * Fires after lazyload main content.
	 *
	 * @since 1.0
	 */
	do_action( 'alpha_after_lazyload_main_content' );
	?>
	<p class="alpha-admin-panel-actions">
		<button type="submit" class="button-dark button button-large button-next" name="save_step" /><?php esc_html_e( 'Continue', 'alpha' ); ?></button>
		<?php wp_nonce_field( 'alpha-setup-wizard' ); ?>
	</p>
</form>
