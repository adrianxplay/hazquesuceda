<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Events Calendar Widget Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			// Events Calendar Layout
			'events_calendar_layout' => '',
			'events_calendar_title'  => '',
		),
		$atts
	)
);

do_action( 'alpha_render_events_calendar', $atts );
