<?php
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

/**
 * Register elementor style controls for testimonials.
 */
function alpha_elementor_testimonial_style_controls( $self ) {

	$self->start_controls_section(
		'testimonial_style',
		array(
			'label' => esc_html__( 'General', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->start_controls_tabs( 'tabs_testimonial_style' );

			$self->start_controls_tab(
				'tab_testimonial_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);
				$self->add_control(
					'testimonial_bg_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .testimonial:not(.testimonial-simple)' => 'background-color: {{VALUE}};',
							'.elementor-element-{{ID}} .testimonial.testimonial-simple .content' => 'background-color: {{VALUE}};',
							'.elementor-element-{{ID}} .testimonial.testimonial-simple .content::before' => 'background-color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'tab_testimonial_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);
				$self->add_control(
					'testimonial_bg_hover_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .testimonial:not(.testimonial-simple):hover' => 'background-color: {{VALUE}};',
							'.elementor-element-{{ID}} .testimonial-simple:hover .content' => 'background-color: {{VALUE}};',
							'.elementor-element-{{ID}} .testimonial-simple:hover .content::before' => 'background-color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

		$self->end_controls_tabs();

		$self->add_responsive_control(
			'testimonial_pd',
			array(
				'label'      => esc_html__( 'Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .testimonial' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'testimonial_type!' => 'simple',
				),
			)
		);

		$self->add_control(
			'testimonial_border_width',
			array(
				'label'       => esc_html__( 'Border Width (px)', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border width of testimonial.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
				),
				'selectors'   => array(
					'{{WRAPPER}} .testimonial' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
				),
				'condition'   => array(
					'testimonial_type!' => 'simple',
				),
			)
		);

		$self->add_responsive_control(
			'testimonial_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .testimonial' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'testimonial_type!' => 'simple',
				),
			)
		);

		$self->add_control(
			'testimonial_border_color',
			array(
				'label'       => esc_html__( 'Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border color of testimonial.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .testimonial' => 'border-color: {{VALUE}};',
				),
				'condition'   => array(
					'testimonial_type!' => 'simple',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'testimonial_shadow',
				'selector'  => '{{WRAPPER}} .testimonial',
				'condition' => array(
					'testimonial_type!' => 'simple',
				),
			)
		);

	$self->end_controls_section();

	$self->start_controls_section(
		'avatar_style',
		array(
			'label' => esc_html__( 'Avatar', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->add_responsive_control(
			'avatar_sz',
			array(
				'label'      => esc_html__( 'Size', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'em',
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 300,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .img-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} .testimonial .img-avatar img' => 'height: 100%;',
					'.elementor-element-{{ID}} .testimonial-simple .content::before' => 'left: calc(4rem + {{SIZE}}{{UNIT}} / 2 - 14px)',
					'.elementor-element-{{ID}} .testimonial .avatar' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$self->add_control(
			'avatar_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .avatar' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'avatar_type' => 'icon',
				),
			)
		);

		$self->add_control(
			'avatar_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .avatar' => 'background-color: {{VALUE}};',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'avatar_shadow',
				'selector' => '.elementor-element-{{ID}} .avatar',
			)
		);

		$self->add_responsive_control(
			'avatar_mg',
			array(
				'label'              => esc_html__( 'Margin', 'alpha-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array(
					'px',
					'em',
					'rem',
				),
				'allowed_dimensions' => 'vertical',
				'selectors'          => array(
					'.elementor-element-{{ID}} .testimonial .avatar' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}}',
				),
				'condition'          => array(
					'testimonial_type' => array( 'standard', 'boxed' ),
				),
			)
		);

		$self->add_responsive_control(
			'avatar_mg_2',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'testimonial_type!' => array( 'standard', 'boxed' ),
				),
			)
		);

		$self->add_responsive_control(
			'avatar_pd',
			array(
				'label'      => esc_html__( 'Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .avatar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'avatar_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .avatar' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid',
				),
			)
		);

		$self->add_control(
			'avatar_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .avatar' => 'border-color: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'avatar_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	$self->end_controls_section();

	$self->start_controls_section(
		'comment_style',
		array(
			'label' => esc_html__( 'Comment', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->add_control(
			'comment_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .comment' => 'color: {{VALUE}};',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'comment_typography',
				'label'    => esc_html__( 'Typography', 'alpha-core' ),
				'selector' => '{{WRAPPER}} .comment',
			)
		);

		$self->add_responsive_control(
			'comment_mg',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .comment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'comment_pd',
			array(
				'label'      => esc_html__( 'Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'comment_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'testimonial_type' => 'simple',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'testimonial_comment_shadow',
				'selector'  => '{{WRAPPER}} .testimonial-simple .content',
				'condition' => array(
					'testimonial_type' => 'simple',
				),
			)
		);

	$self->end_controls_section();

	$self->start_controls_section(
		'name_style',
		array(
			'label' => esc_html__( 'Name', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .testimonial .name' => 'color: {{VALUE}};',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => esc_html__( 'Name', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .testimonial .name',
			)
		);

		$self->add_responsive_control(
			'name_mg',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	$self->end_controls_section();

	$self->start_controls_section(
		'role_style',
		array(
			'label' => esc_html__( 'Role', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->add_control(
			'role_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .testimonial .role' => 'color: {{VALUE}};',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'role_typography',
				'label'    => esc_html__( 'Role', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .testimonial .role',
			)
		);

		$self->add_responsive_control(
			'role_mg',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .role' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	$self->end_controls_section();

	$self->start_controls_section(
		'rating_style',
		array(
			'label' => esc_html__( 'Rating', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->add_responsive_control(
			'rating_sz',
			array(
				'label'      => esc_html__( 'Size', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .ratings-full' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$self->add_control(
			'rating_sp',
			array(
				'label'      => esc_html__( 'Star Spacing', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
			)
		);

		$self->add_control(
			'rating_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .ratings-full .ratings::after' => 'color: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'rating_blank_color',
			array(
				'label'     => esc_html__( 'Blank Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .ratings-full::before' => 'color: {{VALUE}};',
				),
			)
		);

		$self->add_responsive_control(
			'rating_mg',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .testimonial .ratings-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	$self->end_controls_section();
}

/**
 * Register elementor content controls for testimonials
 */
function alpha_elementor_testimonial_content_controls( $self ) {
	$self->add_control(
		'name',
		array(
			'label'   => esc_html__( 'Name', 'alpha-core' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'John Doe',
		)
	);

	$self->add_control(
		'role',
		array(
			'label'   => esc_html__( 'Role', 'alpha-core' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Customer',
		)
	);

	$self->add_control(
		'content',
		array(
			'label'   => esc_html__( 'Description', 'alpha-core' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => '10',
			'default' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.',
		)
	);

	$self->add_control(
		'avatar_type',
		array(
			'label'   => esc_html__( 'Avatar Type', 'alpha-core' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'image',
			'options' => array(
				'image' => esc_html__( 'Image', 'alpha-core' ),
				'icon'  => esc_html__( 'Icon', 'alpha-core' ),
			),
		)
	);

	$self->add_control(
		'avatar',
		array(
			'label'     => esc_html__( 'Choose Avatar', 'alpha-core' ),
			'type'      => Controls_Manager::MEDIA,
			'condition' => array(
				'avatar_type' => 'image',
			),
		)
	);

	$self->add_control(
		'avatar_icon',
		array(
			'label'                  => esc_html__( 'Choose Icon', 'alpha-core' ),
			'type'                   => Controls_Manager::ICONS,
			'default'                => array(
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			),
			'skin'                   => 'inline',
			'exclude_inline_options' => array( 'svg' ),
			'label_block'            => false,
			'condition'              => array(
				'avatar_type' => 'icon',
			),
		)
	);

	$self->add_control(
		'link',
		array(
			'label'       => esc_html__( 'Link', 'alpha-core' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => esc_html__( 'https://your-link.com', 'alpha-core' ),
		)
	);

	$self->add_group_control(
		Group_Control_Image_Size::get_type(),
		array(
			'name'      => 'avatar',
			'default'   => 'full',
			'separator' => 'none',
			'exclude'   => array( 'custom' ),
			'condition' => array(
				'avatar_type' => 'image',
			),
		)
	);

	$self->add_control(
		'rating',
		array(
			'label'   => esc_html__( 'Rating', 'alpha-core' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 5,
			'step'    => 0.1,
			'default' => '',
		)
	);
}

/**
 * Register elementor type controls for testimonials.
 *
 * @since 1.0
 */
function alpha_elementor_testimonial_type_controls( $self ) {
	$self->add_control(
		'testimonial_type',
		array(
			'label'       => esc_html__( 'Testimonial Type', 'alpha-core' ),
			'description' => esc_html__( 'Select a certain display type of your testimonial among Standard, Boxed, Boxed Horizontal, Bordered and Simple.', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'boxed',
			'options'     => array(
				'standard' => esc_html__( 'Standard', 'alpha-core' ),
				'boxed'    => esc_html__( 'Boxed', 'alpha-core' ),
				'boxed-2'  => esc_html__( 'Boxed Horizontal', 'alpha-core' ),
				'bordered' => esc_html__( 'Bordered', 'alpha-core' ),
				'simple'   => esc_html__( 'Simple', 'alpha-core' ),
			),
		)
	);

	$self->add_control(
		'testimonial_inverse',
		array(
			'label'       => esc_html__( 'Inversed', 'alpha-core' ),
			'description' => esc_html__( 'Enables to change the alignment of your testimonial.', 'alpha-core' ),
			'type'        => Controls_Manager::SWITCHER,
			'condition'   => array(
				'testimonial_type' => array( 'simple' ),
			),
		)
	);

	$self->add_control(
		'avatar_pos',
		array(
			'label'       => esc_html__( 'Avatar Position', 'alpha-core' ),
			'description' => esc_html__( 'Choose the position of the avatar in the testimonial.', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'top',
			'options'     => array(
				'top'    => esc_html__( 'Top', 'alpha-core' ),
				'bottom' => esc_html__( 'Bottom', 'alpha-core' ),
			),
			'condition'   => array(
				'testimonial_type' => array( 'boxed', 'standard' ),
			),
		)
	);

	$self->add_control(
		'commenter_pos',
		array(
			'label'       => esc_html__( 'Commenter Position', 'alpha-core' ),
			'description' => esc_html__( 'Choose the position of the commenter in the testimonial.', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'after',
			'options'     => array(
				'before' => esc_html__( 'Before Comment', 'alpha-core' ),
				'after'  => esc_html__( 'After Comment', 'alpha-core' ),
			),
			'condition'   => array(
				'testimonial_type' => array( 'boxed', 'standard', 'boxed-2', 'bordered' ),
			),
		)
	);

	$self->add_control(
		'rating_pos',
		array(
			'label'       => esc_html__( 'Rating Position', 'alpha-core' ),
			'description' => esc_html__( 'Choose the position of the rating in the testimonial.', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'after_comment',
			'options'     => array(
				'before_comment' => esc_html__( 'Before Comment', 'alpha-core' ),
				'after_comment'  => esc_html__( 'After Comment', 'alpha-core' ),
			),
			'condition'   => array(
				'testimonial_type' => array( 'boxed', 'standard' ),
			),
		)
	);

	$self->add_control(
		'h_align',
		array(
			'label'       => esc_html__( 'Horizontal Alignment', 'alpha-core' ),
			'description' => esc_html__( 'Select the testimonial\'s horizontal alignment.', 'alpha-core' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => array(
				'left'   => array(
					'title' => esc_html__( 'Left', 'alpha-core' ),
					'icon'  => 'eicon-text-align-left',
				),
				'center' => array(
					'title' => esc_html__( 'Center', 'alpha-core' ),
					'icon'  => 'eicon-text-align-center',
				),
				'right'  => array(
					'title' => esc_html__( 'Right', 'alpha-core' ),
					'icon'  => 'eicon-text-align-right',
				),
			),
			'condition'   => array(
				'testimonial_type' => array( 'boxed', 'standard', 'boxed-2', 'bordered' ),
			),
		)
	);

	$self->add_control(
		'hide_role',
		array(
			'label' => esc_html__( 'Hide Role', 'alpha-core' ),
			'type'  => Controls_Manager::SWITCHER,
		)
	);

	$self->add_control(
		'hide_rating',
		array(
			'label' => esc_html__( 'Hide Rating', 'alpha-core' ),
			'type'  => Controls_Manager::SWITCHER,
		)
	);

	$self->add_control(
		'star_icon',
		array(
			'label'     => esc_html__( 'Star Icon', 'alpha-core' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => array(
				''        => 'Theme',
				'fa-icon' => 'Font Awesome',
			),
			'condition' => array(
				'hide_rating!' => 'yes',
			),
		)
	);

	$self->add_control(
		'content_line',
		array(
			'label'     => esc_html__( 'Description Limit Line', 'alpha-core' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 4,
			'selectors' => array(
				'.elementor-element-{{ID}} .testimonial .comment' => '-webkit-line-clamp: {{VALUE}};',
			),
		)
	);
}
