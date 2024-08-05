<?php

/**
 * Alpha Button Widget Extend
 *
 * Alpha Widget to display button.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_button/section_button/after_section_end',
	function ( $self ) {
		$self->update_control(
			'link',
			array(
				'default' => array(
					'url'  => '',
					'id'   => '',
					'type' => '',
				),
			)
		);
	}
);
