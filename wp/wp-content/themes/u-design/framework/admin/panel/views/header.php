<?php
/**
 * Header template in admin panel
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

$userinfo     = wp_get_current_user();
$errors       = get_option( 'alpha_register_error_msg' );
$is_activated = Alpha_Admin::get_instance()->is_registered() && ! $errors;

$active_class = $active_page;
if ( 'setup_wizard' == $active_page && isset( $_REQUEST['step'] ) ) {
	if ( 'demo_content' == $_REQUEST['step'] || 'default_plugins' == $_REQUEST['step'] ) {
		$active_page = $_REQUEST['step'];
	}
}
?>

<div class="alpha-wrap<?php echo alpha_escaped( $active_class ? ' alpha-' . $active_class : '' ); ?>"> <!-- Begin .alpha-wrap -->
	<?php if ( ! ( isset( $_REQUEST['page'] ) && 'alpha-layout-builder' == $_REQUEST['page'] && isset( $_REQUEST['is_elementor_preview'] ) && $_REQUEST['is_elementor_preview'] ) ) : // Hide if called in elementor preview ?>
	<div class="alpha-admin-header">
		<div class="alpha-admin-panel-row">
			<div class="col-left">
				<div class="alpha-admin-logo">
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=alpha' ) ); ?>">
						<?php echo '<img src="' . esc_url( alpha_get_option( 'white_label_logo' ) ? alpha_get_option( 'white_label_logo' ) : ALPHA_URI . '/assets/images/admin-logo.png' ) . '"' . ( alpha_get_option( 'white_label_logo' ) ? '' : ' data-image-src="' . esc_attr( ALPHA_URI . '/assets/images/' ) . '"' ) . ' alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '"/>'; ?>
					</a>
				</div>
			</div>
			<div class="col-right">
				<?php if ( ! empty( $admin_config['admin_top_navs'] ) ) : ?>
				<nav class="alpha-admin-nav">
					<?php

					foreach ( $admin_config['admin_top_navs'] as $key => $item ) {
						$url   = isset( $item['url'] ) ? $item['url'] : '#';
						$label = isset( $item['label'] ) ? $item['label'] : '';
						$icon  = isset( $item['icon'] ) ? $item['icon'] : '';
						if ( $icon ) {
							$icon_html = ! isset( $item['is_svg'] ) ? '<i class="' . esc_attr( $icon ) . '"></i>' : alpha_strip_script_tags( $icon );
						} else {
							$icon_html = '';
						}
						if ( empty( $item['submenu'] ) ) {
							$is_active = $key === $active_page ? ' active' : '';
							?>
							<a class="alpha-admin-nav-panel <?php echo esc_attr( $key . $is_active ); ?>" href="<?php echo esc_url( $url ); ?>">
								<?php echo alpha_strip_script_tags( $icon_html ); ?>
								<label><?php echo alpha_strip_script_tags( $label ); ?></label>
							</a>
							<?php
						} elseif ( 1 == count( $item['submenu'] ) ) {
							foreach ( $item['submenu'] as $subkey => $subitem ) {
								$url       = isset( $subitem['url'] ) ? $subitem['url'] : '#';
								$label     = isset( $subitem['label'] ) ? $subitem['label'] : '';
								$is_active = $subkey === $active_page ? ' active' : '';
								?>
								<a class="alpha-admin-nav-panel <?php echo esc_attr( $subkey . $is_active ); ?>" href="<?php echo esc_url( $url ); ?>">
									<?php echo alpha_strip_script_tags( $icon_html ); ?>
									<label><?php echo alpha_strip_script_tags( $label ); ?></label>
								</a>
								<?php
							}
						} else {
							$is_active = $key == $active_page || false !== array_search( $active_page, array_keys( $item['submenu'] ) ) ? ' active' : '';
							?>
							<div class="alpha-admin-nav-panel has-menu <?php echo esc_attr( $key . $is_active . ( '#' != $url ? ' has-link' : '' ) ); ?>">
								<?php if ( '#' != $url ) { ?>
								<a href="<?php echo esc_url( $url ); ?>">
								<?php } ?>
									<?php echo alpha_strip_script_tags( $icon_html ); ?>
									<label><?php echo alpha_strip_script_tags( $label ); ?></label>
								<?php if ( '#' != $url ) { ?>
								</a>
								<?php } ?>
								<div class="alpha-admin-subnavs">
								<?php
								$index = 0;
								foreach ( $item['submenu'] as $subkey => $subitem ) {
									$url   = isset( $subitem['url'] ) ? $subitem['url'] : '#';
									$label = isset( $subitem['label'] ) ? $subitem['label'] : '';
									$icon  = isset( $subitem['icon'] ) ? $subitem['icon'] : '';
									$desc  = isset( $subitem['desc'] ) ? $subitem['desc'] : '';
									if ( $icon ) {
										$icon_html = ! isset( $subitem['is_svg'] ) ? '<i class="' . esc_attr( $icon ) . '"></i>' : $icon;
									} else {
										$icon_html = '';
									}
									if ( count( $item['submenu'] ) > 4 && 0 == $index % 4 ) {
										echo '<div class="menu-col">';
									}
									?>
									<a class="alpha-admin-subnav <?php echo esc_attr( $subkey ); ?>" href="<?php echo esc_url( $url ); ?>">
										<?php echo alpha_strip_script_tags( $icon_html ); ?>
										<label>
											<?php echo alpha_strip_script_tags( $label ); ?>
											<span><?php echo alpha_strip_script_tags( $desc ); ?></span>
										</label>
									</a>
									<?php
									if ( count( $item['submenu'] ) > 4 && ( 3 == $index % 4 || count( $item['submenu'] ) == $index + 1 ) ) {
										echo '</div>';
									}
									$index ++;
								}
								?>
								</div>
							</div>
							<?php
						}
					}
					?>
				</nav>
				<?php endif; ?>
			</div>
		</div>
		<div class="alpha-admin-panel-row">
			<div class="col-left">
				<span class="alpha-version"><?php echo esc_html__( 'v.', 'alpha' ) . ALPHA_VERSION; ?></span>
				<div class="alpha-active-dropdown">
					<a href="#" class="alpha-toggle alpha-active-toggle <?php echo esc_attr( $is_activated ? 'activated' : '' ); ?>">
						<?php
						if ( $is_activated ) {
							esc_html_e( 'Registered', 'alpha' );
						} else {
							esc_html_e( 'Unregistered', 'alpha' );
						}
						?>
					</a>
					<div class="alpha-active-content">
					</div>
				</div>
			</div>
			<div class="col-right">
				<?php if ( ! empty( $admin_config['admin_bottom_navs'] ) ) : ?>
				<nav class="alpha-admin-nav">
					<?php
					foreach ( $admin_config['admin_bottom_navs'] as $key => $item ) {
						$url   = isset( $item['url'] ) ? $item['url'] : '#';
						$label = isset( $item['label'] ) ? $item['label'] : '';
						$icon  = isset( $item['icon'] ) ? $item['icon'] : '';
						if ( $icon ) {
							$icon_html = ! isset( $item['is_svg'] ) ? '<i class="' . esc_attr( $icon ) . '"></i>' : alpha_strip_script_tags( $icon );
						} else {
							$icon_html = '';
						}
						if ( empty( $item['submenu'] ) ) {
							$is_active = $key === $active_page ? ' active' : '';
							?>
							<a class="alpha-admin-nav-panel <?php echo esc_attr( $key . $is_active ); ?>" href="<?php echo esc_url( $url ); ?>">
								<?php echo alpha_strip_script_tags( $icon_html ); ?>
								<label><?php echo alpha_strip_script_tags( $label ); ?></label>
							</a>
							<?php
						} elseif ( 1 == count( $item['submenu'] ) ) {
							foreach ( $item['submenu'] as $subkey => $subitem ) {
								$url       = isset( $subitem['url'] ) ? $subitem['url'] : '#';
								$label     = isset( $subitem['label'] ) ? $subitem['label'] : '';
								$is_active = $subkey === $active_page ? ' active' : '';
								?>
								<a class="alpha-admin-nav-panel <?php echo esc_attr( $subkey . $is_active ); ?>" href="<?php echo esc_url( $url ); ?>">
									<?php echo alpha_strip_script_tags( $icon_html ); ?>
									<label><?php echo alpha_strip_script_tags( $label ); ?></label>
								</a>
								<?php
							}
						} else {
							$is_active = $key == $active_page || false !== array_search( $active_page, array_keys( $item['submenu'] ) ) ? ' active' : '';
							?>
							<div class="alpha-admin-nav-panel has-menu <?php echo esc_attr( $key . $is_active . ( '#' != $url ? ' has-link' : '' ) ); ?>">
								<?php if ( '#' != $url ) { ?>
								<a href="<?php echo esc_url( $url ); ?>">
								<?php } ?>
									<?php echo alpha_strip_script_tags( $icon_html ); ?>
									<label><?php echo alpha_strip_script_tags( $label ); ?></label>
								<?php if ( '#' != $url ) { ?>
								</a>
								<?php } ?>
								<div class="alpha-admin-subnavs">
								<?php
								$index = 0;
								foreach ( $item['submenu'] as $subkey => $subitem ) {
									$url   = isset( $subitem['url'] ) ? $subitem['url'] : '#';
									$label = isset( $subitem['label'] ) ? $subitem['label'] : '';
									$icon  = isset( $subitem['icon'] ) ? $subitem['icon'] : '';
									$desc  = isset( $subitem['desc'] ) ? $subitem['desc'] : '';
									if ( $icon ) {
										$icon_html = ! isset( $subitem['is_svg'] ) ? '<i class="' . esc_attr( $icon ) . '"></i>' : $icon;
									} else {
										$icon_html = '';
									}
									if ( count( $item['submenu'] ) > 4 && 0 == $index % 4 ) {
										echo '<div class="menu-col">';
									}
									?>
									<a class="alpha-admin-subnav <?php echo esc_attr( $subkey ); ?>" href="<?php echo esc_url( $url ); ?>">
										<?php echo alpha_strip_script_tags( $icon_html ); ?>
										<label>
											<?php echo alpha_strip_script_tags( $label ); ?>
											<span><?php echo alpha_strip_script_tags( $desc ); ?></span>
										</label>
									</a>
									<?php
									if ( count( $item['submenu'] ) > 4 && ( 3 == $index % 4 || count( $item['submenu'] ) == $index + 1 ) ) {
										echo '</div>';
									}
									$index ++;
								}
								?>
								</div>
							</div>
							<?php
						}
					}
					?>
				</nav>
				<?php endif; ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=alpha-setup-wizard' ) ); ?>" class="button-success button button-started"><?php esc_html_e( 'Get Started', 'alpha' ); ?></a>
			</div>
		</div>
		<!-- <button class="button button-dark button-large alpha-layouts-save"><i class="far fa-save"></i><?php esc_html_e( 'Save Layouts', 'alpha' ); ?></button> -->
	</div>
	<?php endif; ?>
	<div id="alpha_active_wrapper" style="<?php echo esc_attr( $is_activated ? 'display:none;' : '' ); ?>">
		<?php require_once alpha_framework_path( ALPHA_FRAMEWORK_PATH . '/admin/panel/views/activation.php' ); ?>
	</div>

	<div class="alpha-admin-panel"> <!-- Begin .alpha-admin-panel -->

	<?php if ( ! ( isset( $_REQUEST['page'] ) && 'alpha-layout-builder' == $_REQUEST['page'] && isset( $_REQUEST['is_elementor_preview'] ) && $_REQUEST['is_elementor_preview'] ) ) : // Hide if called in elementor preview ?>
		<div class="alpha-admin-panel-header"> <!-- Begin .alpha-admin-panel-header -->
		<?php if ( empty( $title ) ) { ?>
			<h2 class="alpha-panel-title"><?php printf( esc_html__( 'Welcome to %1$s!', 'alpha' ), ALPHA_DISPLAY_NAME ); ?></h2>
			<p class="alpha-running-info"><?php printf( esc_html__( 'Thanks for choosing %s. Get ready to build beautiful and attractive website? We hope you enjoy it!', 'alpha' ), ALPHA_DISPLAY_NAME ); ?></p>
		<?php } else { ?>
			<h2 class="alpha-panel-title"><?php echo alpha_strip_script_tags( $title['title'] ); ?></h2>
			<p class="alpha-running-info"><?php echo alpha_strip_script_tags( $title['desc'] ); ?></p>
		<?php } ?>
		</div> <!-- End .alpha-admin-panel-header -->
	<?php endif; ?>
