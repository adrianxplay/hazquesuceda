<?php
/**
 * Resource template
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 * @since      1.2.0 Merge and Crtical css
 *
 */
defined( 'ABSPATH' ) || die;
?>
<div class="wizard-header">
	<div class="wizard-headings">
		<h2 class="wizard-title"><?php esc_html_e( 'Optimize Resources', 'alpha' ); ?></h2>
		<p class="wizard-description"><?php esc_html_e( 'After you have finished development, please run this wizard.', 'alpha' ); ?></p>
	</div>
	<div class="wizard-action">
		<a href="#" class="button-success button button-large button-recommendations" data-recommend="resources"><?php echo '<i class="' . ALPHA_ICON_PREFIX . '-icon-verification"></i>' . esc_html__( 'Apply Recommendations', 'alpha' ); ?></a>
	</div>
</div>

<form method="post" class="alpha-optimize-resources-form">
	<h3 style="margin-top: 10px;"><?php esc_html_e( '1. Dequeue Unnecessary Resources', 'alpha' ); ?></h3>
	<p class="descripion"><?php esc_html_e( 'This theme allows you to eliminate unnecessary resources such as WooCommerce blocks and gutenberg styles thus increasing your site speed.', 'alpha' ); ?></p>

	<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
		<input type="checkbox" name="resource_disable_gutenberg" <?php checked( alpha_get_option( 'resource_disable_gutenberg' ) ); ?> />
		<strong><?php esc_html_e( 'Gutenberg', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'If any gutenberg block doesn\'t be used in site, check me.', 'alpha' ); ?></span>
	</label>

	<?php if ( class_exists( 'WooCommerce' ) && alpha_get_feature( 'fs_plugin_woocommerce' ) ) : ?>
		<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
			<input type="checkbox" name="resource_disable_wc_blocks" <?php checked( alpha_get_option( 'resource_disable_wc_blocks' ) ); ?> />
			<strong><?php esc_html_e( 'WooCommerce blocks for Gutenberg', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'If any WooCommerce blocks for Gutenberg doesn\'t be used in sites, check me.', 'alpha' ); ?></span>
		</label>
	<?php endif; ?>

	<?php if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) { ?>
		<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
			<input type="checkbox" name="resource_disable_elementor" <?php checked( alpha_get_option( 'resource_disable_elementor' ) ); ?> />
			<strong><?php esc_html_e( 'Elementor Resources', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'Check this to speed up your elementor site remarkably, if your site has no compatibility issue.', 'alpha' ); ?></span>
		</label>
	<?php } ?>

	<?php
	if ( alpha_get_feature( 'fs_plugin_rev' ) && defined( 'RS_REVISION' ) ) {
		$rev_pages = array();
		if ( class_exists( 'Alpha_Optimize_Wizard' ) ) {
			$rev_pages = Alpha_Optimize_Wizard::get_instance()->get_used_shortcodes( array( 'rev_slider', 'rev_slider_vc' ), true );
		}

		?>
		<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
			<input type="checkbox" name="resource_disable_rev" <?php checked( alpha_get_option( 'resource_disable_rev' ) ); ?> />
			<input type="hidden" name="rev_pages" value="<?php echo implode( ',', $rev_pages ); ?>" />
			<strong><?php esc_html_e( 'Optimize Revolution Slider', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'This will help you to avoid loading revolution slider js/css resources for the pages that don\'t use revolution slider feature.', 'alpha' ); ?></span>
		</label>
	<?php } ?>

	<h3><?php esc_html_e( '2. Change WordPress defaults', 'alpha' ); ?></h3>
	<p class="descripion"><?php esc_html_e( 'You can dequeue WordPress default scripts that are not necessary for most websites.', 'alpha' ); ?></p>
	<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
		<input type="checkbox" name="resource_disable_emojis" <?php checked( alpha_get_option( 'resource_disable_emojis' ) ); ?> />
		<strong><?php esc_html_e( 'Emojis Script', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'By using this option, you can remove WordPress emojis script.', 'alpha' ); ?></span>
	</label>
	<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
		<input type="checkbox" name="resource_disable_jq_migrate" <?php checked( alpha_get_option( 'resource_disable_jq_migrate' ) ); ?> />
		<strong><?php esc_html_e( 'jQuery Migrate Script', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'Please use this option if you are not using any deprecated jQuery code.', 'alpha' ); ?></span>
	</label>
	<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
		<input type="checkbox" name="resource_jquery_footer" <?php checked( alpha_get_option( 'resource_jquery_footer' ) ); ?> />
		<strong><?php esc_html_e( 'Load jQuery In Footer', 'alpha' ); ?></strong> - <span><?php esc_html_e( 'Defer loading of jQuery to the footer of the page.', 'alpha' ); ?></span>
	</label>

	<div class="important-steps">
		<div class="important-step">
			<h3><?php esc_html_e( '3. File Compression', 'alpha' ); ?></h3>
			<p class="descripion"><?php esc_html_e( 'When enabled, it decreases the number of requests and caches files, As a result it brings out a top performance score with critical css engine.', 'alpha' ); ?></p>
			<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
				<input type="checkbox" name="resource_merge_stylesheets" <?php checked( alpha_get_option( 'resource_merge_stylesheets' ) ); ?> />
				<?php esc_html_e( 'Merge javascripts and stylesheets', 'alpha' ); ?>
			</label>
		</div>

		<div class="important-step">
			<h3><?php esc_html_e( '4. Critical Css - Advanced Feature', 'alpha' ); ?></h3>
			<p class="descripion"><?php esc_html_e( 'If you check this option, you can see a menu item "Critical Css" in the theme admin menu. It helps your site to increase the google page speed.', 'alpha' ); ?></p>
			<label class="checkbox checkbox-inline" style="margin-bottom: 15px;">
				<input type="checkbox" name="resource_critical_css" <?php checked( alpha_get_option( 'resource_critical_css' ) ); ?> />
				<?php esc_html_e( 'Enable Critical CSS', 'alpha' ); ?>
			</label>
		</div>
	</div>

	<h3><?php esc_html_e( '5. Disable Template Builders', 'alpha' ); ?></h3>
	<p class="descripion"><?php esc_html_e( 'If you want to disable template builder including header or footer, Please select.', 'alpha' ); ?></p>
	<?php
	if ( class_exists( 'Alpha_Builders' ) ) {
		$builders_array = json_decode( wp_unslash( alpha_get_option( 'resource_template_builders' ) ), true );
		foreach ( Alpha_Builders::get_template_types() as $builder_type => $value ) {
			?>
			<label class="checkbox checkbox-inline">
			<input type="checkbox" value="<?php echo esc_attr( $builder_type ); ?>" name="resource_template_builders" <?php checked( ! empty( $builders_array[ $builder_type ] ) ); ?>><?php echo alpha_escaped( $value ); ?></label>&nbsp;
			<?php
		}
	}
	?>
	<?php
		/**
		 * Fires after resources main content.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_after_resource_main_content' );
	?>
	<p class="alpha-admin-panel-actions">
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-dark button button-large button-next" data-callback="optimize_resources"><?php esc_html_e( 'Continue', 'alpha' ); ?></a>
	</p>
</form>
