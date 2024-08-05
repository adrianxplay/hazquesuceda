<?php
/** no direct access **/
defined( 'MECEXEC' ) or die();

// Get layout path
$render_path = $this->get_render_path();
$styling     = $this->main->get_styling();

$dark_mode = ( isset( $styling['dark_mode'] ) ? $styling['dark_mode'] : '' );
if ( 1 == $dark_mode ) {
	$set_dark = 'mec-dark-mode';
} else {
	$set_dark = '';
}

ob_start();
include $render_path;
$items_html = ob_get_clean();

if ( isset( $this->atts['return_items'] ) and $this->atts['return_items'] ) {
	echo json_encode(
		array(
			'html'     => $items_html,
			'end_date' => $this->end_date,
			'offset'   => $this->next_offset,
			'count'    => $this->found,
		)
	);
	exit;
}

$sed_method = $this->sed_method;
if ( 'new' == $sed_method ) {
	$sed_method = '0';
}

// Generating javascript code tpl
$javascript = 'jQuery(document).ready(function()
{
    jQuery("#mec_skin_' . $this->id . '").mecGridView(
    {
        id: "' . $this->id . '",
        start_date: "' . $this->start_date . '",
        end_date: "' . $this->end_date . '",
		offset: "' . $this->next_offset . '",
		limit: "' . $this->limit . '",
        atts: "' . http_build_query( array( 'atts' => $this->atts ), '', '&' ) . '",
        ajax_url: "' . admin_url( 'admin-ajax.php', null ) . '",
        sed_method: "' . $sed_method . '",
        image_popup: "' . $this->image_popup . '",
        sf:
        {
            container: "' . ( $this->sf_status ? '#mec_search_form_' . $this->id : '' ) . '",
        },
    });
});';

// Include javascript code into the page
if ( $this->main->is_ajax() ) {
	echo '<script>' . wp_strip_all_tags( $javascript ) . '</script>';
} else {
	$this->factory->params( 'footer', '<script>' . wp_strip_all_tags( $javascript ) . '</script>' );
}

do_action( 'mec_start_skin', $this->id );
do_action( 'mec_grid_skin_head' );
?>
<div class="mec-wrap mec-skin-grid-container <?php echo esc_attr( $this->html_class . ' ' . $set_dark ); ?>" id="mec_skin_<?php echo esc_attr( $this->id ); ?>">

	<?php
	if ( $this->sf_status ) {
		echo alpha_escaped( $this->sf_search_form() );
	}
	?>

	<?php if ( $this->found ) : ?>
		<?php if ( '1' == $this->map_on_top ) : ?>
		<div class="mec-wrap mec-skin-map-container <?php echo esc_attr( $this->html_class ); ?>" id="mec_skin_<?php echo esc_attr( $this->id ); ?>">
			<div class="mec-googlemap-skin" id="mec_googlemap_canvas<?php echo esc_attr( $this->id ); ?>" style="height: 500px;">
			<?php
				$map = isset( $this->settings['default_maps_view'] ) ? $this->settings['default_maps_view'] : 'google';
				do_action( 'mec_map_inner_element_tools', array( 'map' => $map ) );
			?>
			</div>
			<input type="hidden" id="gmap-data" value="">
		</div>
	<?php endif; ?>
	<div class="mec-skin-grid-events-container" id="mec_skin_events_<?php echo esc_attr( $this->id ); ?>">
		<?php echo alpha_escaped( $items_html ); ?>
	</div>
	<div class="mec-skin-grid-no-events-container mec-util-hidden mec-event-empty" id="mec_skin_no_events_<?php echo esc_attr( $this->id ); ?>">
		<div class="mec-event-detail"><i class="u-calendar-empty"></i><?php _e( 'No event found!', 'alpha' ); ?></div>
	</div>
	<?php else : ?>
	<div class="mec-skin-grid-events-container mec-event-empty" id="mec_skin_events_<?php echo esc_attr( $this->id ); ?>">
		<div class="mec-event-detail"><i class="u-calendar-empty"></i><?php _e( 'No event found!', 'alpha' ); ?></div>
	</div>
	<?php endif; ?>

	<?php if ( $this->load_more_button and $this->found >= $this->limit ) : ?>
	<div class="mec-load-more-wrap"><div class="mec-load-more-button" onclick=""><?php echo __( 'Load More', 'alpha' ); ?></div></div>
	<?php endif; ?>

</div>
