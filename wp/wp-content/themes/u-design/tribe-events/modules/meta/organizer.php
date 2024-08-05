<?php
/**
 * Single Event Meta (Organizer) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/organizer.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 */

$organizer_ids = tribe_get_organizer_ids();
$multiple      = count( $organizer_ids ) > 1;

$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
// $website       = tribe_get_organizer_website_link();
// $website_title = tribe_events_get_organizer_website_title();
$post_id     = tribe_get_organizer_id();
$website_url = tribe_get_event_meta( $post_id, '_OrganizerWebsite', true );
?>

<div class="tribe-events-meta-group tribe-events-meta-group-organizer">
	<h2 class="tribe-events-single-meta-title text-primary"><?php echo tribe_get_organizer_label( ! $multiple ); ?></h2>
	<dl>
		<?php
		do_action( 'tribe_events_single_meta_organizer_section_start' );

		foreach ( $organizer_ids as $organizer ) {
			if ( ! $organizer ) {
				continue;
			}

			?>
			<dt style="display:none;"><?php // This element is just to make sure we have a valid HTML ?></dt>
			<dd class="tribe-organizer">
				<?php echo tribe_get_organizer_link( $organizer ); ?>
			</dd>
			<?php
		}

		if ( ! $multiple ) { // only show organizer details if there is one
			if ( ! empty( $phone ) ) {
				?>
				<dt class="tribe-organizer-tel-label">
					<?php esc_html_e( 'Phone:', 'alpha' ); ?>
				</dt>
				<dd class="tribe-organizer-tel">
					<?php echo esc_html( $phone ); ?>
				</dd>
				<?php
			}//end if

			if ( ! empty( $email ) ) {
				?>
				<dt class="tribe-organizer-email-label">
					<?php esc_html_e( 'Email:', 'alpha' ); ?>
				</dt>
				<dd class="tribe-organizer-email">
					<?php echo esc_html( $email ); ?>
				</dd>
				<?php
			}//end if

			if ( ! empty( $website_url ) ) :
				?>
				<dt class="tribe-events-event-url-label"><?php echo esc_html__( 'Website:', 'alpha' ); ?></dt>
				<dd class="tribe-events-event-url"><a href="<?php echo esc_url( $website_url ); ?>" rel="noopener noreferrer"><?php echo esc_html( $website_url ); ?></a></dd>
				<?php
			endif;
		}//end if

		do_action( 'tribe_events_single_meta_organizer_section_end' );
		?>
	</dl>
</div>
