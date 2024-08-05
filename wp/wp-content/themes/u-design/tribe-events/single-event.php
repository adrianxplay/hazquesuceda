<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $alpha_layout;

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();

$has_left_sidebar  = ! empty( $alpha_layout['left_sidebar'] ) && 'hide' != $alpha_layout['left_sidebar'];
$has_right_sidebar = ! empty( $alpha_layout['right_sidebar'] ) && 'hide' != $alpha_layout['right_sidebar'];

$has_sidebar = false;
if ( $has_left_sidebar || $has_right_sidebar ) {
	$has_sidebar = true;
}

if ( ! apply_filters( 'alpha_run_single_builder', false ) ) :

	if ( ! $has_sidebar ) :
		wp_enqueue_script( 'alpha-sidebar' );
		?>
	<div class="row gutter-lg">
		<aside class="offcanvas sidebar sidebar-fixed sidebar-side right-sidebar alpha-tribe-single-event-meta-wrapper sidebar-sticky-wrapper">
			<div class="sidebar-overlay offcanvas-overlay"></div>
			<a class="sidebar-close" href="#"><i class="close-icon"></i></a>
			<a href="#" class="sidebar-toggle"><i class="fas fa-chevron-left"></i></a>

			<div class="sidebar-content offcanvas-content">
				<div class="sticky-sidebar">
					<!-- Event meta -->
						<?php do_action( 'tribe_events_single_event_before_the_meta' ); ?>
						<?php tribe_get_template_part( 'modules/meta' ); ?>
						<?php do_action( 'tribe_events_single_event_after_the_meta' ); ?>
				</div>
			</div>
		</aside>
		<?php
	endif;
	?>

		<div id="tribe-events-content" class="tribe-events-single">
			<!-- Notices -->
			<?php
			if ( function_exists( 'tribe_the_notices' ) ) {
				tribe_the_notices();
			} else {
				tribe_events_the_notices();
			}
			?>
			<?php the_title( '<h1 class="tribe-events-single-event-title">', '</h1>' ); ?>

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<!-- Event featured image, but exclude link -->
					<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

					<!-- Event content -->
					<?php do_action( 'tribe_events_single_event_before_the_content' ); ?>
					<div class="tribe-events-single-event-description tribe-events-content">
						<?php the_content(); ?>
					</div>
					<!-- .tribe-events-single-event-description -->
					<?php do_action( 'tribe_events_single_event_after_the_content' ); ?>
				</div> <!-- #post-x -->
				<?php
				if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) {
					comments_template();}
				?>
				<?php
			endwhile;
			?>
		</div>

		<?php if ( $has_sidebar ) : ?>
			<div class="alpha-tribe-single-event-meta-wrapper">
				<div class="alpha-tribe-single-event-meta-inner">
					<?php do_action( 'tribe_events_single_event_before_the_meta' ); ?>
					<?php tribe_get_template_part( 'modules/meta' ); ?>
					<?php do_action( 'tribe_events_single_event_after_the_meta' ); ?>
				</div>
			</div>
		<?php endif; ?>	

	<?php
	if ( ! $has_sidebar ) :
		?>
	</div>
		<?php
	endif;

endif;
