<?php
/**
 * Render of Alpha Contact Form Widget
 *
 * Alpha Widget to display contact form with cf7 & wpform.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.3.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'cf7_form' => '',
		),
		$atts
	)
);

if ( $cf7_form ) { // Contact Form 7
	$form_id = absint( $cf7_form );

	echo do_shortcode( '[contact-form-7 id="' . $form_id . '" title=""]' );
}
