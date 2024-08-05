<?php
/**
 * Alpha Header Elementor Cart
 *
 * @author     Andon
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;

// Update content option
add_action(
	'elementor/element/' . ALPHA_NAME . '_header_cart/section_cart_content/before_section_end',
	function( $self, $args ) {
		$self->update_control(
			'label',
			array(
				'default' => esc_html__( 'Shopping Cart:', 'alpha-core' ),
			)
		);
		$self->update_control(
			'icon_pos',
			array(
				'default' => '',
			)
		);
		$self->update_control(
			'delimiter',
			array(
				'label'     => esc_html__( 'Delimiter', 'alpha-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'show_label' => 'yes',
					'show_price' => 'yes',
					'type'       => 'block',
				),
			)
		);
	},
	10,
	2
);

// Update style option
add_action(
	'elementor/element/' . ALPHA_NAME . '_header_cart/section_cart_style/before_section_end',
	function( $self, $args ) {
		$self->update_responsive_control(
			'cart_delimiter_space',
			array(
				'label'      => esc_html__( 'Delimiter Space (px)', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .cart-toggle .cart-name-delimiter' => 'margin: 0 {{SIZE}}px;',
				),
				'condition'  => array(
					'show_label' => 'yes',
					'show_price' => 'yes',
					'delimiter!' => '',
					'type'       => 'block',
				),
			)
		);
	},
	10,
	2
);
