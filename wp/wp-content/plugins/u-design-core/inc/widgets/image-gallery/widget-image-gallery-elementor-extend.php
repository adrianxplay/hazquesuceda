<?php

/**
 * Alpha Image Gallery Widget Extend
 *
 * Alpha Widget to display image.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_imagegallery/gallery_style/after_section_end',
	function ( $self ) {
		$self->add_responsive_control(
			'img_max_height',
			array(
				'label'      => esc_html__( 'Max Width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'rem',
					'%',
					'vh',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} img' => 'max-width:{{SIZE}}{{UNIT}}; width: 100%',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'gallery_image_border',
				),
			)
		);
	}
);
