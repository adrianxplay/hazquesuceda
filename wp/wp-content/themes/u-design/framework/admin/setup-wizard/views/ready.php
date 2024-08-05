<?php
/**
 * Ready panel
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;
update_option( 'alpha_setup_complete', time() );
?>
<h2 class="wizard-title"><?php esc_html_e( 'Your Website is Ready!', 'alpha' ); ?></h2>

<p class="wizard-description"><?php esc_html_e( 'Congratulations! The theme has been activated and your website is ready. Please go to your WordPress dashboard to make changes and modify the content for your needs.', 'alpha' ); ?></p>

<p><?php esc_html_e( 'This theme comes with 6 months item support from purchase date (with the option to extend this period). This license allows you to use this theme on a single website. Please purchase an additional license to use this theme on another website.', 'alpha' ); ?></p>
<div class="alpha-admin-panel-row">
	<div class="alpha-support">
		<?php /* translators: $1 and $2 opening and closing strong tags respectively */ ?>
		<h4 class="success system-status"><i class="circle fa fa-check"></i> <?php printf( esc_html__( 'Item Support %1$sDOES%2$s Include:', 'alpha' ), '<strong class="success">', '</strong>' ); ?></h4>

		<ul class="list">
			<li><?php esc_html_e( 'Availability of the author response to the questions', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Answering technical questions about item features', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Assistance with reported bugs and issues', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Help with bundled 3rd party plugins', 'alpha' ); ?></li>
		</ul>
	</div>
	<div class="alpha-support">
		<?php /* translators: $1 and $2 opening and closing strong tags respectively */ ?>
		<h4 class="error system-status"><i class="circle fas fa-ban"></i> <?php printf( esc_html__( 'Item Support %1$sDOES NOT%2$s Include:', 'alpha' ), '<strong class="error">', '</strong>' ); ?></h4>
		<ul class="list">
			<li><?php esc_html_e( 'Customization services', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Installation services', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Help and Support for non-bundled 3rd party plugins (i.e. plugins you installed yourself later on)', 'alpha' ); ?></li>
		</ul>
	</div>
</div>
<?php /* translators: $1 and $2 opening and closing anchor tags respectively */ ?>
<p class="info-qt light-info"><?php printf( esc_html__( 'More details about item support can be found in the ThemeForest %1$sItem Support Policy%2$s.', 'alpha' ), '<a href="http://themeforest.net/page/item_support_policy" target="_blank">', '</a>' ); ?></p>
<br>
<div class="alpha-setup-next-steps">
	<div class="alpha-setup-next-steps-first">
		<h4><?php esc_html_e( 'More Resources', 'alpha' ); ?></h4>
		<ul class="external-links" style="margin-bottom:40px;">
			<li class="documentation"><a href="<?php echo esc_url( Alpha_Admin::get_instance()->admin_config['links']['documentation']['url'] ); ?>" target="_blank"><i class="far fa-file-alt"></i><?php echo ALPHA_DISPLAY_NAME . esc_html( ' Documentation', 'alpha' ); ?></a></li>
			<li class="woocommerce documentation"><a href="https://docs.woocommerce.com/document/woocommerce-101-video-series/" target="_blank"><i class="fas fa-shopping-cart"></i><?php esc_html_e( 'Learning WooCommerce', 'alpha' ); ?></a></li>
			<li class="howto" style="font-style: normal;"><a href="https://wordpress.org/support/" target="_blank"><i class="fab fa-wordpress"></i><?php esc_html_e( 'Learning WordPress', 'alpha' ); ?></a></li>
			<li class="rating"><a href="<?php echo esc_url( Alpha_Admin::get_instance()->admin_config['links']['reviews']['url'] ); ?>" target="_blank"><i class="far fa-star"></i><?php esc_html_e( 'Show Your Love', 'alpha' ); ?></a></li>
		</ul>
	</div>
	<div class="alpha-admin-panel-actions">
		<a class="button button-large button-dark button-next" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'View your new website!', 'alpha' ); ?></a>
	</div>
</div>
