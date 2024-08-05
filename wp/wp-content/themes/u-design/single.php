<?php
/**
 * Single post and other post-types template
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
	/**
	 * Fires before print page layout.
	 *
	 * @since 1.0
	 */
	do_action( 'alpha_print_before_page_layout' );

	if ( ALPHA_NAME == substr( get_post_type(), 0, strlen( ALPHA_NAME ) ) ) {
		alpha_get_template_part( 'single-' . substr( get_post_type(), strlen( ALPHA_NAME ) + 1 ) );
	} else {
		alpha_get_template_part( 'posts/single' );
	}

	/**
	 * Fires after print page layout.
	 *
	 * @since 1.0
	 */
	do_action( 'alpha_print_after_page_layout' );
	?>
</div>

<?php
/**
 * Fires before rendering page content.
 *
 * @since 1.0
 */
do_action( 'alpha_after_content' );
get_footer();
