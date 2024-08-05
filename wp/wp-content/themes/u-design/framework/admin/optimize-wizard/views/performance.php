<?php
/**
 * Performance template
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
		<h2 class="wizard-title"><?php esc_html_e( 'Performance', 'alpha' ); ?></h2>
		<p class="wizard-description"><?php esc_html_e( 'Introduce some features to be able to impact the performance of your site.', 'alpha' ); ?></p>
	</div>
	<div class="wizard-action">
		<a href="#" class="button-success button button-large button-recommendations" data-recommend="performance"><?php echo '<i class="' . ALPHA_ICON_PREFIX . '-icon-verification"></i>' . esc_html__( 'Apply Recommendations', 'alpha' ); ?></a>
	</div>
</div>

<form method="post" class="alpha_submit_form">

	<h3 style="margin-top: 10px;"><?php esc_html_e( '1. Optimize Mobile', 'alpha' ); ?></h3>

	<label class="checkbox checkbox-inline" style="margin-bottom: 10px">
		<input type="checkbox" name="mobile_disable_animation" <?php checked( alpha_get_option( 'mobile_disable_animation' ) ); ?>> <?php esc_html_e( 'Disable Mobile Animations', 'alpha' ); ?>
	</label>
	<p style="margin: 0 0 20px;"><?php esc_html_e( 'Disable appear and slide animations in mobile.', 'alpha' ); ?></p>

	<label class="checkbox checkbox-inline" style="margin-bottom: 10px">
		<input type="checkbox" name="mobile_disable_slider" <?php checked( alpha_get_option( 'mobile_disable_slider' ) ); ?>> <?php esc_html_e( 'Disable Mobile Sliders', 'alpha' ); ?>
	</label>
	<p style="margin: 0 0 20px;"><?php esc_html_e( 'Disable slider feature for elements in mobile.', 'alpha' ); ?></p>
	<?php
		/**
		 * Fires after performance mobile main content.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_after_performance_mobile' );
	?>

	<h3 style="padding-top: 15px;"><?php esc_html_e( '2. Serve Fonts', 'alpha' ); ?></h3>
	<h4 class="sub-title" style="margin-bottom: 10px">
		<?php esc_html_e( '- Preload Webfonts and images', 'alpha' ); ?>
	</h4>
	<p style="margin-bottom: .5rem">
		<?php /* translators: Google Page Speed url */ ?>
		<?php printf( esc_html__( 'This improves page load time as the browser caches preloaded resources so they are available immediately when needed. By using this option, you can increase page speed about 1 ~ 4 percent in %1$sGoogle PageSpeed Insights%2$s for both of mobile and desktop.', 'alpha' ), '<a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank">', '</a>' ); ?>
	</p>
	<p>
		<label class="checkbox checkbox-inline">
		<?php
			$preload_fonts = alpha_get_option( 'preload_fonts' );
		if ( empty( $preload_fonts ) ) {
			$preload_fonts = array();
		}
		?>
			<input type="checkbox" value="alpha" name="preload_fonts[]" <?php checked( in_array( 'alpha', $preload_fonts ) ); ?>> <?php echo ALPHA_DISPLAY_NAME . esc_html( ' Icons', 'alpha' ); ?>
		</label>&nbsp;
		<label class="checkbox checkbox-inline">
			<input type="checkbox" value="fas" name="preload_fonts[]" <?php checked( in_array( 'fas', $preload_fonts ) ); ?>> <?php esc_html_e( 'Font Awesome 5 Solid', 'alpha' ); ?>
		</label>&nbsp;
		<label class="checkbox checkbox-inline">
			<input type="checkbox" value="far" name="preload_fonts[]" <?php checked( in_array( 'far', $preload_fonts ) ); ?>> <?php esc_html_e( 'Font Awesome 5 Regular', 'alpha' ); ?>
		</label>&nbsp;
		<label class="checkbox checkbox-inline">
			<input type="checkbox" value="fab" name="preload_fonts[]" <?php checked( in_array( 'fab', $preload_fonts ) ); ?>> <?php esc_html_e( 'Font Awesome 5 Brands', 'alpha' ); ?>
		</label>&nbsp;
		<?php
			/**
			 * Fires after performance font main content.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_after_performance_font', $preload_fonts );
		?>
		<br>
		<br>
		<label><?php printf( esc_html__( 'Please input other resources that will be pre loaded. Ex. %1$swp-content/themes/alpha-child/fonts/custom.woff2.', 'alpha' ), ALPHA_SERVER_URI ); ?></label>
		<textarea class="form-control input-text" name="preload_fonts_custom" style="width: 100%; margin-top: .4rem" rows="4" value="<?php echo isset( $preload_fonts['custom'] ) ? esc_attr( $preload_fonts['custom'] ) : ''; ?>"><?php echo isset( $preload_fonts['custom'] ) ? esc_html( $preload_fonts['custom'] ) : ''; ?></textarea>
	</p>

	<h4 class="sub-title" style="margin-bottom: 10px">
		<?php esc_html_e( '- Font Face Rendering', 'alpha' ); ?>
	</h4>
	<p style="margin-bottom: .5rem">
		<?php /* translators: Google Page Speed url */ ?>
		<?php printf( esc_html__( 'Choosing "Swap" for font-display will ensure text remains visible during webfont load and this will improve page speed score in %1$sGoogle PageSpeed Insights%2$s for both of mobile and desktop.', 'alpha' ), '<a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank">', '</a>' ); ?>
	</p>
	<p>
		<label class="checkbox checkbox-inline">
			<input type="checkbox" name="font_face_display" <?php checked( alpha_get_option( 'font_face_display' ) ); ?>> <?php esc_html_e( 'Swap for font display', 'alpha' ); ?>
		</label>
	</p>

	<h3 style="padding-top: 15px;"><?php esc_html_e( '3. Asynchronous Scripts', 'alpha' ); ?></h3>

	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="resource_async_js" <?php checked( alpha_get_option( 'resource_async_js' ) ); ?>> <?php esc_html_e( 'Asynchronous load', 'alpha' ); ?>
	</label>
	<p><?php esc_html_e( 'Some javascript libraries does not affect first paint. And you can increase page loading speed by loading them asynchronously.', 'alpha' ); ?></p>

	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="resource_split_tasks" <?php checked( alpha_get_option( 'resource_split_tasks' ) ); ?>> <?php esc_html_e( 'Split tasks', 'alpha' ); ?>
	</label>
	<p><?php esc_html_e( 'Long time tasks may cause unintentional rendering suspension or affect to its performance. To make pages faster, please check split task option.', 'alpha' ); ?></p>

	<label class="checkbox checkbox-inline">
		<input type="checkbox" name="resource_after_load" <?php checked( alpha_get_option( 'resource_after_load' ) ); ?>> <?php esc_html_e( 'Process after load event', 'alpha' ); ?>
	</label>
	<p><?php esc_html_e( 'This will accelerate page\'s load time. But this may cause compatibility issue since page still not be ready. It will be in ready state after document or window load event is ready. To fix this problem, Please add event handlers to window\'s "alpha_complete" event.', 'alpha' ); ?></p>
	<?php
		/**
		 * Fires after performance main content.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_after_performance_main_content' );
	?>
	<p class="alpha-admin-panel-actions">
		<button type="submit" class="button-dark button button-large button-next" name="save_step"><?php esc_html_e( 'Continue', 'alpha' ); ?></button>
		<input type="hidden" name="css_js" id="css_js" value="<?php echo checked( alpha_get_option( 'minify_css_js' ), true, false ) ? 'true' : 'false'; ?>">
		<input type="hidden" name="font_icons" id="font_icons" value="<?php echo checked( alpha_get_option( 'minify_font_icons' ), true, false ) ? 'true' : 'false'; ?>">
		<?php wp_nonce_field( 'alpha-setup-wizard' ); ?>
	</p>
</form>
