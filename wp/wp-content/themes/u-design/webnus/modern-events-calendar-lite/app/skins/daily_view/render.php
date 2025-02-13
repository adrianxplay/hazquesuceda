<?php
/** no direct access **/
defined( 'MECEXEC' ) or die();

$this->localtime         = isset( $this->skin_options['include_local_time'] ) ? $this->skin_options['include_local_time'] : false;
$display_label           = isset( $this->skin_options['display_label'] ) ? $this->skin_options['display_label'] : false;
$reason_for_cancellation = isset( $this->skin_options['reason_for_cancellation'] ) ? $this->skin_options['reason_for_cancellation'] : false;
?>
<ul class="mec-daily-view-dates-events">
	<?php foreach ( $this->events as $date => $events ) : ?>
	<li class="mec-daily-view-date-events mec-util-hidden" id="mec_daily_view_date_events<?php echo esc_attr( $this->id ); ?>_<?php echo date( 'Ymd', strtotime( $date ) ); ?>">
		<?php if ( count( $events ) ) : ?>
			<?php foreach ( $events as $event ) : ?>
				<?php
				$location_id = $this->main->get_master_location_id( $event );
				$location    = ( ( $location_id and isset( $event->data->locations[ $location_id ] ) ) ? $event->data->locations[ $location_id ] : array() );

				$start_time       = ( isset( $event->data->time ) ? $event->data->time['start'] : '' );
				$end_time         = ( isset( $event->data->time ) ? $event->data->time['end'] : '' );
				$event_start_date = ! empty( $event->date['start']['date'] ) ? $event->date['start']['date'] : '';

				// MEC Schema
				do_action( 'mec_schema', $event );
				?>
			<article class="<?php echo ( isset( $event->data->meta['event_past'] ) and trim( $event->data->meta['event_past'] ) ) ? 'mec-past-event ' : ''; ?>mec-event-article <?php echo esc_attr( $this->get_event_classes( $event ) ); ?>">
				<div class="mec-event-image"><?php echo alpha_escaped( $event->data->thumbnails['thumbnail'] ); ?></div>
				<?php echo alpha_escaped( $this->get_label_captions( $event ) ); ?>
				<div class="mec-event-content">
					<h4 class="mec-event-title"><?php echo alpha_escaped( $this->display_link( $event ) ); ?><?php echo alpha_escaped( $this->main->get_flags( $event ) . $this->main->get_normal_labels( $event, $display_label ) . $this->main->display_cancellation_reason( $event, $reason_for_cancellation ) ); ?><?php do_action( 'mec_shortcode_virtual_badge', $event->data->ID ); ?></h4>
					<?php
					if ( trim( $start_time ) ) :
						?>
						<div class="mec-event-time mec-color"><i class="mec-sl-clock-o"></i> <?php echo alpha_escaped( $start_time . ( trim( $end_time ) ? ' - ' . $end_time : '' ) ); ?></div><?php endif; ?>
						<?php
						if ( $this->localtime ) {
							echo alpha_escaped( $this->main->module( 'local-time.type3', array( 'event' => $event ) ) );}
						?>
					<div class="mec-event-detail"><div class="mec-event-loc-place"><?php echo ( isset( $location['name'] ) ? $location['name'] : '' ); ?><div></div>
					<?php echo alpha_escaped( $this->display_categories( $event ) ); ?>
					<?php echo alpha_escaped( $this->display_organizers( $event ) ); ?>
					<?php echo alpha_escaped( $this->display_cost( $event ) ); ?>
					<?php echo alpha_escaped( $this->booking_button( $event ) ); ?>
					<?php echo alpha_escaped( $this->display_custom_data( $event ) ); ?>
				</div>
			</article>
		<?php endforeach; ?>
		<?php else : ?>
			<article class="mec-event-article"><div class="mec-daily-view-no-event mec-no-event"><?php _e( 'No event', 'alpha' ); ?></div></article>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>
<div class="mec-event-footer"></div>
