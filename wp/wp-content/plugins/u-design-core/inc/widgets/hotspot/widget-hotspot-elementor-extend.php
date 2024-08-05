<?php

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;

/**
 * Register hotspot style controls
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_elementor_hotspot_style_controls' ) ) {
	function alpha_elementor_hotspot_style_controls( $self, $name_prefix = '', $condition_key = '', $condition_value = '', $repeater = false ) {
		if ( empty( $name_prefix ) ) {
			$self->start_controls_section(
				$name_prefix . 'style_hotspot',
				array(
					'label' => esc_html__( 'Hotspot', 'alpha-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
		}
		$self->add_responsive_control(
			$name_prefix . 'size',
			array(
				'label'       => esc_html__( 'Hotspot Size', 'alpha-core' ),
				'description' => esc_html__( 'Controls hotspot size.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'unit' => 'px',
				),
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		$self->add_responsive_control(
			$name_prefix . 'icon_size',
			array(
				'label'       => esc_html__( 'Icon Size', 'alpha-core' ),
				'description' => esc_html__( 'Controls icon size in hotspot.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'unit' => 'px',
				),
				'size_units'  => array(
					'px',
					'em',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 500,
					),
					'em' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		$self->add_responsive_control(
			$name_prefix . 'border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
				'description' => esc_html__( 'Controls border radius value of hotspot.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
					'em',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		$self->add_control(
			$name_prefix . 'spread_color',
			array(
				'label'       => esc_html__( 'Spread Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of spread effects.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#fff',
				'condition'   => $condition_key ? array(
					$name_prefix . 'effect' => 'type1',
					$condition_key          => $condition_value,
				) : array(
					$name_prefix . 'effect' => 'type1',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.hotspot-type1:not(:hover):before' => 'background: {{VALUE}};',
				),
			)
		);

		if ( empty( $name_prefix ) ) {
			$self->start_controls_tabs( $name_prefix . 'tabs_hotspot' );

			$self->start_controls_tab(
				$name_prefix . 'tab_btn_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);
		}

		$self->add_control(
			$name_prefix . 'btn_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot' => 'color: {{VALUE}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		$self->add_control(
			$name_prefix . 'btn_back_color',
			array(
				'label'       => esc_html__( 'Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the background color of button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot' => 'background-color: {{VALUE}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		if ( empty( $name_prefix ) ) {
			$self->end_controls_tab();

			$self->start_controls_tab(
				$name_prefix . 'tab_btn_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);
		}

		$self->add_control(
			$name_prefix . 'btn_color_hover',
			array(
				'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the hover color of button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.hotspot-wrapper:hover .hotspot' => 'color: {{VALUE}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		$self->add_control(
			$name_prefix . 'btn_back_color_hover',
			array(
				'label'       => esc_html__( 'Hover Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the hover background color of button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : ' ' ) . '.hotspot-wrapper:hover .hotspot' => 'background-color: {{VALUE}};',
				),
				'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
			)
		);

		if ( empty( $name_prefix ) ) {
			$self->end_controls_tab();

			$self->end_controls_tabs();

			$self->end_controls_section();

			$self->start_controls_section(
				$name_prefix . 'style_popup',
				array(
					'label' => esc_html__( 'Popup', 'alpha-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);
		}
			$self->add_responsive_control(
				$name_prefix . 'popup_width',
				array(
					'label'       => esc_html__( 'Popup Width', 'alpha-core' ),
					'description' => esc_html__( 'Controls width hotspot content popup.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'%',
						'rem',
					),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 100,
							'max'  => 1000,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 10,
							'max'  => 100,
						),
						'%'   => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot-box' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}',
					),
					'condition'   => $condition_key ? array( $condition_key => $condition_value ) : '',
				)
			);
			$self->add_control(
				$name_prefix . 'popup_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot-box' => 'color: {{VALUE}};',
					),
					'condition' => $condition_key ? array( $condition_key => $condition_value ) : '',
				)
			);
			$self->add_control(
				$name_prefix . 'popup_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}}' . ( $repeater ? ' {{CURRENT_ITEM}}' : '' ) . ' .hotspot-box' => 'background-color: {{VALUE}};',
					),
					'condition' => $condition_key ? array( $condition_key => $condition_value ) : '',
				)
			);

		if ( empty( $name_prefix ) ) {
			$self->end_controls_section();
		}
	}
}
