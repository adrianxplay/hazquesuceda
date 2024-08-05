<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 */

if ( ! tribe_get_venue_id() ) {
	return;
}

$phone = tribe_get_phone();
// $website       = tribe_get_venue_website_link();
// $website_title = tribe_events_get_venue_website_title();

$post_id     = tribe_get_venue_id();
$website_url = tribe_get_venue_website_url( $post_id );

?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<h4 class="tribe-events-single-meta-title text-primary"> <?php echo esc_html( tribe_get_venue_label_singular() ); ?> </h4>
	<dl>
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ); ?>

		<dd class="tribe-venue"> <?php echo tribe_get_venue(); ?> </dd>

		<?php if ( tribe_address_exists() ) : ?>
			<dd class="tribe-venue-location">
				<address class="tribe-events-address">
					<?php echo tribe_get_full_address(); ?>

					<?php if ( tribe_show_google_map_link() ) : ?>
						<?php echo tribe_get_map_link_html(); ?>
					<?php endif; ?>
				</address>
			</dd>
		<?php endif; ?>


		<?php if ( ! empty( $website_url ) ) : ?>
			<dt class="tribe-events-event-url-label"> <?php echo esc_html__( 'Website: ', 'alpha' ); ?> </dt>
			<dd class="tribe-events-event-url"> <a href="<?php echo esc_url( $website_url ); ?> rel="noopener noreferrer"><?php echo esc_html( $website_url ); ?></a> </dd>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ); ?>
	</dl>
</div>
