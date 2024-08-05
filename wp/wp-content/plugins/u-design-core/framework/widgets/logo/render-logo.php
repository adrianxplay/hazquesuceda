<?php
/**
 * Header logo template
 *
 * This is the site logo, and it doesn't be lazyloaded.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

$logo_id    = function_exists( 'alpha_get_option' ) ? alpha_get_option( 'custom_logo' ) : get_theme_mod( 'custom_logo' );
$site_title = get_bloginfo( 'name', 'display' );
?>

<a href="<?php echo esc_url( apply_filters( 'alpha_header_element_logo_url', home_url( '/' ) ) ); ?>" class="logo" title="<?php echo esc_attr( $site_title ); ?> - <?php esc_attr( bloginfo( 'description' ) ); ?>">
	<?php
	if ( $logo_id ) {
		echo str_replace( ' class="', ' class="site-logo skip-data-lazy ', wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => esc_attr( $site_title ) ) ) );
	} elseif ( defined( 'ALPHA_VERSION' ) ) {
		echo '<img class="site-logo skip-data-lazy" src="' . ALPHA_ASSETS . '/images/logo.png" width="135" height="42" alt="' . esc_attr( $site_title ) . '" title="' . esc_attr( $site_title ) . '"/>';
	}
	?>
</a>
