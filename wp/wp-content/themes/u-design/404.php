<?php
/**
 * Error 404 page template
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

get_header();
do_action( 'alpha_before_content' );
?>

<div class="page-content">

	<?php
	do_action( 'alpha_print_before_page_layout' );

	global $alpha_layout;

	if ( ! empty( $alpha_layout['error_block'] ) && 'hide' != $alpha_layout['error_block'] ) {

		alpha_print_template( $alpha_layout['error_block'] );

	} elseif ( empty( $alpha_layout['error_block'] ) || 'hide' != $alpha_layout['error_block'] ) {

		?>
		<div class="area_404">
			<div class="container">
				<div class="content_404">
					<h1 class="text-uppercase"><strong class="d-block"><?php esc_html_e( '404', 'alpha' ); ?></strong><?php esc_html_e( 'Error', 'alpha' ); ?></h1>
					<h3 class="ms-1"><strong><?php esc_html_e( 'Oops!', 'alpha' ); ?></strong> <?php esc_html_e( 'This page cannot be found.', 'alpha' ); ?></h3>
					<p class="ms-1"><?php esc_html_e( 'Sorry, the page you are looking for is not available. Maybe you could go home.', 'alpha' ); ?></p>
					<a href="<?php echo esc_url( home_url() ); ?>" class="btn btn-primary btn-icon-right ms-1"><?php esc_html_e( 'Go Home', 'alpha' ); ?></a>
				</div>
			</div>
		</div>
		<?php

	}

	do_action( 'alpha_print_after_page_layout' );

	?>

</div>

<?php
do_action( 'alpha_after_content' );
get_footer();
