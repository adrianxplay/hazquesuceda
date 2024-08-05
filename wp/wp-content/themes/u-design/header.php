<?php
/**
 * Header template
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

?>

<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

		<?php
		/**
		 * udesign_print_header_meta
		 *
		 * @see print_header_meta
		 */
		do_action( 'udesign_print_header_meta' );
		?>

		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php alpha_print_favicon(); ?>

		<?php
		$preload_fonts = alpha_get_option( 'preload_fonts' );
		if ( ! empty( $preload_fonts ) ) {
			if ( in_array( 'alpha', $preload_fonts ) ) {
				if ( alpha_get_option( 'legacy_icons' ) ) {
					echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/wpalpha-icons/legacy/fonts/alpha.ttf?png09e" as="font" type="font/ttf" crossorigin>';
					echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/icons/legacy/fonts/' . ALPHA_NAME . '.ttf?y65ra8" as="font" type="font/ttf" crossorigin>';
				} else {
					echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/wpalpha-icons/' . alpha_get_option( 'icon_weight' ) . '/fonts/alpha.ttf?png09e" as="font" type="font/ttf" crossorigin>';
					echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/icons/' . alpha_get_option( 'icon_weight' ) . '/fonts/' . ALPHA_NAME . '.ttf?y65ra8" as="font" type="font/ttf" crossorigin>';
				}
			}
			if ( in_array( 'fas', $preload_fonts ) ) {
				echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>';
			}
			if ( in_array( 'far', $preload_fonts ) ) {
				echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin>';
			}
			if ( in_array( 'fab', $preload_fonts ) ) {
				echo '<link rel="preload" href="' . ALPHA_ASSETS . '/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>';
			}
		}
		if ( ! empty( $preload_fonts['custom'] ) ) {
			$font_urls = explode( PHP_EOL, $preload_fonts['custom'] );
			foreach ( $font_urls as $font_url ) {
				$dot_pos = strrpos( $font_url, '.' );
				if ( false !== $dot_pos ) {
					$type       = substr( $font_url, $dot_pos + 1 );
					$font_type  = array( 'ttf', 'woff', 'woff2', 'eot' );
					$image_type = array( 'jpg', 'jpeg', 'png', 'svg', 'gif', 'webp' );
					if ( in_array( $type, $font_type ) ) {
						echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/' . esc_attr( $type ) . '" crossorigin/>';
					} elseif ( in_array( $type, $image_type ) ) {
						echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="image" />';
					} else {
						echo '<link rel="preload" href="' . esc_url( $font_url ) . '" />';
					}
				}
			}
		}
		?>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<?php do_action( 'alpha_page_transition' ); ?>

		<?php do_action( 'alpha_before_page_wrapper' ); ?>

		<div class="page-wrapper">

			<?php
			global $alpha_layout;
			if ( ! empty( $alpha_layout['top_bar'] ) && 'hide' != $alpha_layout['top_bar'] ) {
				echo '<div class="top-notification-bar">';
				alpha_print_template( $alpha_layout['top_bar'] );
				echo '</div>';
			}

			alpha_get_template_part( 'header/header' );

			alpha_print_title_bar();

			?>

			<?php do_action( 'alpha_before_main' ); ?>

			<main id="main" class="<?php echo apply_filters( 'alpha_main_class', 'main' ); ?>">
