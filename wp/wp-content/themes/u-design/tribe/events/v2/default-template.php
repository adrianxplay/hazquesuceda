<?php
/**
 * View: Default Template for Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/default-template.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.0.0
 */

use Tribe\Events\Views\V2\Template_Bootstrap;

get_header();

do_action( 'alpha_before_content' );
?>
<!-- Wrap div.page-content for default event template -->
<div class="page-content">

<?php
	// Apply alpha-page-layout to default event template
	do_action( 'alpha_print_before_page_layout' );

	echo tribe( Template_Bootstrap::class )->get_view_html();

	do_action( 'alpha_print_after_page_layout' );
	// End of alpha page layout
?>

</div>
<!-- End of div.page-content -->

<?php
do_action( 'alpha_after_content' );

get_footer();
