<?php
/**
 * Plugins step in optimize wizard template.
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
		<h2 class="wizard-title"><?php esc_html_e( 'Plugins', 'alpha' ); ?></h2>
		<p class="wizard-description"><?php esc_html_e( 'It allows you to install or deactivate any plugins with which this theme are compatiable.', 'alpha' ); ?></p>
	</div>
</div>

<form method="post">
	<h3 style="margin-top: 10px;"><?php esc_html_e( '1. Recommended Plugins', 'alpha' ); ?></h3>
	<?php
	$plugins = $this->_get_plugins();
	if ( count( $plugins['all'] ) ) {
		?>
		<p style="margin-bottom: 5px;">
			<?php esc_html_e( 'This will install the plugins which can accelerate your site.', 'alpha' ); ?><br>
			<?php esc_html_e( 'You should disable below plugins while in development. Changes may not be applied because of them.', 'alpha' ); ?>
		</p>
		<ul class="alpha-plugins">
			<?php
			foreach ( $plugins['all'] as $slug => $plugin ) {
				?>
				<li data-slug="<?php echo esc_attr( $slug ); ?>"<?php echo isset( $plugin['visibility'] ) && 'hidden' === $plugin['visibility'] ? ' class="hidden"' : ''; ?>>
					<label class="checkbox checkbox-inline">
						<input type="checkbox" name="setup-plugin"<?php echo ! $plugin['required'] ? '' : ' checked="checked"'; ?>><?php echo esc_html( $plugin['name'] ); ?>
						<span></span>
					</label>
					<div class="spinner"></div>
					<?php if ( $plugin['desc'] ) : ?>
						<p style="margin-top: 5px;margin-bottom: 15px;">
							<?php /* translators: %s: Plugin url and name */ ?>
							<?php printf( ' <a href="%s" target="_blank">%s</a>', 'https://wordpress.org/plugins/' . esc_attr( $slug ) . '/', $plugin['name'] ); ?><?php echo esc_html( $plugin['desc'] ); ?>
						</p>
					<?php endif; ?>
				</li>
				<?php if ( ALPHA_CORE_SLUG === $plugin['slug'] ) : ?>
					<li class="separator"></li>
				<?php endif; ?>
			<?php } ?>
		</ul>
		<ul style="margin-bottom: 20px;">
			<li class="howto">
				<a href="https://gtmetrix.com/leverage-browser-caching.html" target="_blank" style="font-style: normal;"><?php esc_html_e( 'How to enable leverage browser caching.', 'alpha' ); ?></a>
				<p style="margin-top: 0;font-style: normal;"><?php esc_html_e( 'Page loading duration can be significantly improved by asking visitors to save and reuse the files included in your website.', 'alpha' ); ?></p>
			</li>
		</ul>
		<?php
	} else {
		echo '<p>' . esc_html__( 'Good News! All recommended plugins are already installed up-to-date.', 'alpha' ) . '</p>';
	}
	?>

	<h3 style="padding-top: 15px;"><?php esc_html_e( '2. Installed Plugins', 'alpha' ); ?></h3>
	<p style="margin-bottom: 5px;"><?php esc_html_e( 'Please check active plugins. You can deactivate unnecessary plugins.', 'alpha' ); ?></p>

	<ul class="installed-plugins">
		<li class="plugins-label">
			<label><?php esc_html_e( 'Plugin Name', 'alpha' ); ?></label>
			<span><?php esc_html_e( 'Action', 'alpha' ); ?></span>
		</li>
		<?php
		foreach ( $plugins['installed'] as $slug => $plugin ) {
			?>
		<li>
			<label data-version="<?php echo esc_attr( $plugin['Version'] ); ?>"><?php echo esc_html( $plugin['Name'] ); ?></label>
			<a href="<?php echo esc_attr( $slug ); ?>"><?php esc_html_e( 'Deactivate', 'alpha' ); ?></a>
		</li>
			<?php
		}
		?>
		<?php
		if ( isset( $plugins['network_activated'] ) ) {
			foreach ( $plugins['network_activated'] as $slug => $plugin ) {
				?>
		<li>
			<label data-version="<?php echo esc_attr( $plugin['Version'] ); ?>"><?php echo esc_html( $plugin['Name'] ); ?></label>
			<span><?php esc_html_e( 'Network Activate', 'alpha' ); ?></span>
		</li>
				<?php
			}
		}
		?>
	</ul>

	<div class="form-checkbox">
		<input type="checkbox" id="share_plugins" name="allow_plugins_share" checked/><label style="font-weight: 600;"  for="share_plugins"><?php esc_html_e( 'Share Plugins Information', 'alpha' ); ?></label>
		<p style="margin: 5px 0 0;" ><?php esc_html_e( 'Please contribute to upgrade theme and your site to the best one! Your cooperation would be highly appreciated.', 'alpha' ); ?></p>
		<p class="info-qt light-info" style="margin-top: 0;margin-bottom: 20px;"><?php esc_html_e( 'We will never collect any sensivite or private data such as IP addresses, email, usernames, or passwords.', 'alpha' ); ?></p>
	</div>

	<p class="alpha-admin-panel-actions">
		<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-dark button button-large button-next btn-plugins" data-callback="install_plugins"><?php esc_html_e( 'Continue', 'alpha' ); ?></a>
		<?php wp_nonce_field( 'alpha-setup-wizard' ); ?>
	</p>
</form>
