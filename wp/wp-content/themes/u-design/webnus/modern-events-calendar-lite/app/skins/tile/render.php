<?php
/** no direct access **/
defined( 'MECEXEC' ) or die();

/** @var MEC_skin_tile $this */

$styling                 = $this->main->get_styling();
$event_colorskin         = ( isset( $styling['mec_colorskin'] ) || isset( $styling['color'] ) ) ? 'colorskin-custom' : '';
$display_label           = isset( $this->skin_options['display_label'] ) ? $this->skin_options['display_label'] : false;
$reason_for_cancellation = isset( $this->skin_options['reason_for_cancellation'] ) ? $this->skin_options['reason_for_cancellation'] : false;

$method     = isset( $this->skin_options['sed_method'] ) ? $this->skin_options['sed_method'] : false;
$map_events = array();
?>
<div class="mec-wrap <?php echo esc_attr( $event_colorskin ); ?>">
	<div class="mec-event-tile-view">
		<?php
		$count = $this->count;
		if ( $count == 0 or $count == 5 ) {
			$col = 4;
		} else {
			$col = 12 / $count;
		}

		echo '<div class="row">';
		if ( ! count( $this->events ) ) {
			echo '<article class="mec-event-article mec-event-empty"><div class="mec-event-detail"><i class="u-calendar-empty"></i>' . __( 'No Events', 'alpha' ) . '</div></article>';
		}
		foreach ( $this->events as $date ) :
			foreach ( $date as $event ) :
				$map_events[] = $event;
				echo '<div class="col-md-' . $col . ' col-sm-' . $col . '">';

				$location_id = $this->main->get_master_location_id( $event );
				$location    = ( ( $location_id and isset( $event->data->locations[ $location_id ] ) ) ? $event->data->locations[ $location_id ] : array() );

				$start_time       = ( isset( $event->data->time ) ? $event->data->time['start'] : '' );
				$event_start_date = ! empty( $event->date['start']['date'] ) ? $event->date['start']['date'] : '';
				$event_color      = ( ( isset( $event->data->meta['mec_color'] ) and trim( $event->data->meta['mec_color'] ) ) ? '#' . $event->data->meta['mec_color'] : '' );
				$background_image = ( isset( $event->data->featured_image['tileview'] ) && trim( $event->data->featured_image['tileview'] ) ) ? ' url(\'' . trim( $event->data->featured_image['tileview'] ) . '\')' : '';

				// Multiple Day Event Class
				$me_class = $event_start_date == $event->date['end']['date'] || ( isset( $this->settings['multiple_day_show_method'] ) && $this->settings['multiple_day_show_method'] == 'all_days' ) ? '' : 'tile-multipleday-event';

				// MEC Schema
				do_action( 'mec_schema', $event );
				?>
					<article 
					<?php
					if ( $method != 'no' ) :
						?>
						 data-href="<?php echo esc_url( $this->main->get_event_date_permalink( $event, $event->date['start']['date'] ) ); ?>" data-target="<?php echo ( 'new' == $method ? 'blank' : esc_attr( $method ) ); ?>"<?php endif; ?> <?php echo 'style="background:' . $event_color . $background_image . '"'; ?> class="<?php echo ( ( isset( $event->data->meta['event_past'] ) and trim( $event->data->meta['event_past'] ) ) ? 'mec-past-event' : '' ); ?> mec-event-article mec-tile-item <?php echo esc_attr( $me_class ); ?> mec-clear <?php echo esc_attr( $this->get_event_classes( $event ) ); ?>">
						<?php do_action( 'mec_skin_tile_view', $event ); ?>
						<?php echo alpha_escaped( $this->get_label_captions( $event ) ); ?>
						<div class="mec-event-content" data-target="<?php echo 'new' == $method ? 'blank' : esc_attr( $method ); ?>" data-event-id="<?php echo esc_attr( $event->ID ); ?>">
						<?php
						if ( $method != 'no' ) :
							?>
							<a href="<?php echo esc_url( $this->main->get_event_date_permalink( $event, $event->date['start']['date'] ) ); ?>" target="<?php echo ( 'new' == $method ? '_blank' : esc_attr( $method ) ); ?>" class="mec-tile-into-content-link"></a><?php endif; ?>
							<div class="mec-tile-event-content">
								<div class="mec-event-detail">
									<?php echo alpha_escaped( $this->display_categories( $event ) ); ?>
									<?php echo alpha_escaped( $this->display_organizers( $event ) ); ?>
									<?php echo ( isset( $location['name'] ) ? '<span class="mec-event-loc-place"><i class="mec-sl-location-pin"></i>' . $location['name'] . '</span>' : '' ); ?>
									<?php echo alpha_escaped( $this->display_cost( $event ) ); ?>
								</div>
							<?php if ( isset( $this->settings['multiple_day_show_method'] ) && $this->settings['multiple_day_show_method'] == 'all_days' ) : ?>
								<div class="mec-event-date"><?php echo alpha_escaped( $this->main->date_i18n( $this->date_format_clean_1, strtotime( $event->date['start']['date'] ) ) ); ?></div>
								<div class="mec-event-month"><?php echo alpha_escaped( $this->main->date_i18n( $this->date_format_clean_2, strtotime( $event->date['start']['date'] ) ) ); ?></div>
							<?php else : ?>
								<div class="mec-event-month"><?php echo alpha_escaped( $this->main->dateify( $event, $this->date_format_clean_1 . ' ' . $this->date_format_clean_2 ) ); ?></div>
							<?php endif; ?>
								<?php echo alpha_escaped( $this->main->get_normal_labels( $event, $display_label ) . $this->main->display_cancellation_reason( $event, $reason_for_cancellation ) ); ?><?php do_action( 'mec_shortcode_virtual_badge', $event->data->ID ); ?>
								<h4 class="mec-event-title"><?php echo alpha_escaped( $this->display_link( $event ) ); ?><?php echo alpha_escaped( $this->display_custom_data( $event ) ); ?><?php echo alpha_escaped( $this->main->get_flags( $event ) ); ?></h4>
								<div class="mec-event-time"><i class="mec-sl-clock"></i><?php echo alpha_escaped( $start_time ); ?></div>
								<?php echo alpha_escaped( $this->booking_button( $event ) ); ?>
							</div>
						</div>
					</article>
				<?php
				echo '</div>';
				?>
			<?php endforeach; ?>
		<?php endforeach; ?>
		<?php echo '</div>'; ?>
	</div>
	<?php
		$div_count = count( $map_events ) - ( floor( count( $map_events ) / $count ) * $count );
	if ( $div_count > 0 and $div_count < $count ) {
		echo '</div>';
	}
	?>
</div>
