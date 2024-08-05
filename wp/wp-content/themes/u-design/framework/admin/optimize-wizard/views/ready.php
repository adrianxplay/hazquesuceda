<?php
/**
 * Ready template
 *
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;
update_option( 'alpha_setup_complete', time() );
?>
<h2 class="wizard-title"><?php esc_html_e( 'Now, your website is faster than before!', 'alpha' ); ?></h2>
<p class="lead success"><?php esc_html_e( 'Congratulations! Now, your site is fully optimized. The page speed has been promoted much faster than before. Please visit your new site, by doing so you can easily notice how fast your site is now.', 'alpha' ); ?></p>
<p style="margin: 15px 0 0;"><a class="button-dark button button-large" target="_blank" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'View your optimized website!', 'alpha' ); ?></a></p>
<?php /* translators: opening and closing a tag */ ?>
<p class="info-qt light-info" style="margin-top: 20px;"><?php printf( esc_html__( 'Don`t forget to leave a %1$s5-star rating%2$s if you are satisfied with this theme. Thanks!', 'alpha' ), '<a href="http://themeforest.net/downloads" target="_blank">', '</a>' ); ?></p>
