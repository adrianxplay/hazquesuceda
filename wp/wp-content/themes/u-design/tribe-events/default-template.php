<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template'
 * is selected in Events -> Settings -> Display -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.23
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use Tribe\Events\Views\V2\Template_Bootstrap;

get_header();
do_action( 'alpha_before_content' );
?>
<div id="tribe-events" class="page-content">

	<?php do_action( 'alpha_print_before_page_layout' ); ?>

	<?php tribe_events_before_html(); ?>

	<?php echo tribe( Template_Bootstrap::class )->get_view_html(); ?>

	<?php tribe_events_after_html(); ?>

	<?php do_action( 'alpha_print_after_page_layout' ); ?>

</div>
<?php
do_action( 'alpha_after_content' );
get_footer();
