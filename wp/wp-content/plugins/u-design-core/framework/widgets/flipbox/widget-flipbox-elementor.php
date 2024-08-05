<?php
/**
 * Alpha Flipbox Widget
 *
 * Alpha Widget to display flipbox.
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Background;

class Alpha_Flipbox_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_flipbox';
	}

	public function get_title() {
		return esc_html__( 'Flipbox', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-flipbox';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-flipbox', alpha_core_framework_uri( '/widgets/flipbox/flipbox' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-flipbox' );
	}

	public function get_script_depends() {
		return array();
	}

	public function get_keywords() {
		return array( 'flipbox' );
	}

	protected function register_controls() {

		// Start Front Side Content
		$this->start_controls_section(
			'section_front_side_content',
			array(
				'label' => esc_html__( 'Front', 'alpha-core' ),
			)
		);

		$this->start_controls_tabs( 'front_content_tabs' );

		$this->start_controls_tab(
			'front_content_tab',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

			$this->add_control(
				'front_side_icon',
				array(
					'label'       => esc_html__( 'Icon', 'alpha-core' ),
					'description' => esc_html__( 'Choose icon or svg from library.', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
					'default'     => array(
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					),
				)
			);

			$this->add_control(
				'front_icon_type',
				array(
					'label'       => esc_html__( 'View', 'alpha-core' ),
					'description' => esc_html__( 'Select frontside icon view type.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'default',
					'options'     => array(
						'default' => esc_html__( 'Default', 'alpha-core' ),
						'stacked' => esc_html__( 'Stacked', 'alpha-core' ),
						'framed'  => esc_html__( 'Framed', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'front_icon_shape',
				array(
					'label'       => esc_html__( 'Shape', 'alpha-core' ),
					'description' => esc_html__( 'Control flipbox frontside icon border type.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'circle',
					'options'     => array(
						'circle' => esc_html__( 'Circle', 'alpha-core' ),
						''       => esc_html__( 'Square', 'alpha-core' ),
					),
					'condition'   => array(
						'front_icon_type!' => array( 'default' ),
					),
				)
			);

			$this->add_control(
				'front_side_title',
				array(
					'label'       => esc_html__( 'Title', 'alpha-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Title', 'alpha-core' ),
					'separator'   => 'before',
					'description' => esc_html__( 'Input frontend flipbox title.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'front_side_subtitle',
				array(
					'label'       => esc_html__( 'Subtitle', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'description' => esc_html__( 'Input frontend flipbox subtitle.', 'alpha-core' ),
					'default'     => esc_html__( 'Subtitle', 'alpha-core' ),
				)
			);

			$this->add_control(
				'front_side_content',
				array(
					'label'       => esc_html__( 'Description', 'alpha-core' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => esc_html__( 'Add some contents here', 'alpha-core' ),
					'description' => esc_html__( 'Input frontend flipbox content.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'front_side_button_text',
				array(
					'label'       => esc_html__( 'Button Label', 'alpha-core' ),
					'label_block' => true,
					'separator'   => 'before',
					'description' => esc_html__( 'Input frontend flipbox button text.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'View More', 'alpha-core' ),
				)
			);

			$this->add_control(
				'front_side_button_link',
				array(
					'label'       => esc_html__( 'Button Link', 'alpha-core' ),
					'description' => esc_html__( 'Input frontend flipbox button link.', 'alpha-core' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => 'http://your-link.com',
					'default'     => array(
						'url' => '#',
					),
					'dynamic'     => array( 'active' => true ),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'front_background_tab',
				array(
					'label' => esc_html__( 'Background' ),
				)
			);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					array(
						'name'     => 'front_side_background',
						'selector' => '{{WRAPPER}} .flipbox .flipbox_front',
					)
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		// Start Back-Side content
		$this->start_controls_section(
			'section_back_side_content',
			array(
				'label' => esc_html__( 'Back', 'alpha-core' ),
			)
		);

		$this->start_controls_tabs( 'back_content_tabs' );
		$this->start_controls_tab(
			'back_content_tab',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

			$this->add_control(
				'back_side_icon',
				array(
					'label'       => esc_html__( 'Icon', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
					'description' => esc_html__( 'Choose icon or svg from library.', 'alpha-core' ),
					'default'     => array(
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					),
				)
			);

			$this->add_control(
				'back_icon_type',
				array(
					'label'       => esc_html__( 'View', 'alpha-core' ),
					'description' => esc_html__( 'Control flipbox backside icon view type.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'default',
					'options'     => array(
						'default' => esc_html__( 'Default', 'alpha-core' ),
						'stacked' => esc_html__( 'Stacked', 'alpha-core' ),
						'framed'  => esc_html__( 'Framed', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'back_icon_shape',
				array(
					'label'       => esc_html__( 'Shape', 'alpha-core' ),
					'description' => esc_html__( 'Control flipbox backside icon shape.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'circle',
					'options'     => array(
						'circle' => esc_html__( 'Circle', 'alpha-core' ),
						''       => esc_html__( 'Square', 'alpha-core' ),
					),
					'condition'   => array(
						'back_icon_type!' => array( 'default' ),
					),
				)
			);

			$this->add_control(
				'back_side_title',
				array(
					'label'       => esc_html__( 'Title', 'alpha-core' ),
					'label_block' => true,
					'separator'   => 'before',
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Title', 'alpha-core' ),
					'description' => esc_html__( 'Input backend flipbox title.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'back_side_subtitle',
				array(
					'label'       => esc_html__( 'Subtitle', 'alpha-core' ),
					'label_block' => true,
					'description' => esc_html__( 'Input backend flipbox subtitle.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Subtitle', 'alpha-core' ),
				)
			);

			$this->add_control(
				'back_side_content',
				array(
					'label'       => esc_html__( 'Description', 'alpha-core' ),
					'label_block' => true,
					'description' => esc_html__( 'Input backend flipbox content.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => esc_html__( 'Add some contents here', 'alpha-core' ),
				)
			);

			$this->add_control(
				'back_side_button_text',
				array(
					'label'       => esc_html__( 'Button Label', 'alpha-core' ),
					'label_block' => true,
					'separator'   => 'before',
					'description' => esc_html__( 'Input backend flipbox button text.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'View More', 'alpha-core' ),
				)
			);

			$this->add_control(
				'back_side_button_link',
				array(
					'label'       => esc_html__( 'Button Link', 'alpha-core' ),
					'description' => esc_html__( 'Input backend flipbox button link.', 'alpha-core' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => 'http://your-link.com',
					'default'     => array(
						'url' => '#',
					),
					'dynamic'     => array( 'active' => true ),
				)
			);

			$this->end_controls_tab();
			$this->start_controls_tab(
				'back_background_tab',
				array(
					'label' => esc_html__( 'Background', 'alpha-core' ),
				)
			);

				$this->add_group_control(
					Group_Control_Background::get_type(),
					array(
						'name'     => 'back_side_background',
						'selector' => '{{WRAPPER}} .flipbox .flipbox_back',
					)
				);

			$this->end_controls_tab();
			$this->end_controls_tabs();
		$this->end_controls_section();

		// Add Flipbox Settings Controls Section
		$this->start_controls_section(
			'section_flipbox_settings',
			array(
				'label' => esc_html__( 'Flipbox Settings', 'alpha-core' ),
			)
		);

			$this->add_control(
				'flipbox_general_height',
				array(
					'label'       => esc_html__( 'Height', 'alpha-core' ),
					'description' => esc_html__( 'Set the flipbox height.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem', 'vh' ),
					'range'       => array(
						'px'  => array(
							'min' => 100,
							'max' => 1000,
						),
						'rem' => array(
							'min' => 1,
							'max' => 50,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .flipbox,{{WRAPPER}} .flipbox_front,{{WRAPPER}} .flipbox_back' => 'max-height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
						// '{{WRAPPER}} .flipbox .flipbox_back' => 'min-height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'flipbox_border_radius',
				array(
					'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
					'description' => esc_html__( 'Control the flipboxes border-radius.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem', '%' ),
					'selectors'   => array(
						'{{WRAPPER}} .flipbox .flipbox_front' => 'border-radius: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .flipbox .flipbox_back' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'flipbox_animation_effect',
				array(
					'label'       => esc_html__( 'Animation Effect', 'alpha-core' ),
					'description' => esc_html__( 'Set your favourite effect for flipbox.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'fb-flip-vertical',
					'groups'      => array(
						'pull'  => array(
							'label'   => esc_html__( 'Pull', 'alpha-core' ),
							'options' => array(
								'fb-pull-left'  => esc_html__( 'Pull Left', 'alpha-core' ),
								'fb-pull-up'    => esc_html__( 'Pull Up', 'alpha-core' ),
								'fb-pull-right' => esc_html__( 'Pull Right', 'alpha-core' ),
								'fb-pull-down'  => esc_html__( 'Pull Down', 'alpha-core' ),
							),
						),
						'slide' => array(
							'label'   => esc_html__( 'Slide', 'alpha-core' ),
							'options' => array(
								'fb-slide-left'   => esc_html__( 'Slide Left', 'alpha-core' ),
								'fb-slide-top'    => esc_html__( 'Slide Top', 'alpha-core' ),
								'fb-slide-right'  => esc_html__( 'Slide Right', 'alpha-core' ),
								'fb-slide-bottom' => esc_html__( 'Slide Bottom', 'alpha-core' ),
							),
						),
						'fall'  => array(
							'label'   => esc_html__( 'Fall', 'alpha-core' ),
							'options' => array(
								'fb-fall-horizontal fb-fall-left' => esc_html__( 'Fall Left', 'alpha-core' ),
								'fb-fall-vertical fb-fall-up'   => esc_html__( 'Fall Up', 'alpha-core' ),
								'fb-fall-horizontal fb-fall-right' => esc_html__( 'Fall Right', 'alpha-core' ),
								'fb-fall-vertical fb-fall-down' => esc_html__( 'Fall Down', 'alpha-core' ),
							),
						),
						'flip'  => array(
							'label'   => esc_html__( 'Flip', 'alpha-core' ),
							'options' => array(
								'fb-flip-horizontal' => esc_html__( 'Flip Horizontal', 'alpha-core' ),
								'fb-flip-vertical'   => esc_html__( 'Flip Vertical', 'alpha-core' ),
							),
						),
					),
				)
			);
		$this->end_controls_section();

		// Add Flipbox Styles Tab
		$this->start_controls_section(
			'section_flipbox_layout_style',
			array(
				'label' => esc_html__( 'Front', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

					$this->add_responsive_control(
						'front_side_padding',
						array(
							'label'       => esc_html__( 'Padding', 'alpha-core' ),
							'description' => esc_html__( 'Control the flipbox frontside padding.', 'alpha-core' ),
							'type'        => Controls_Manager::DIMENSIONS,
							'size_units'  => array( 'px', 'rem', '%' ),
							'selectors'   => array(
								'{{WRAPPER}} .flipbox .flipbox_front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'front_side_align',
						array(
							'label'       => esc_html__( 'Alignment', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside horizontal alignment.', 'alpha-core' ),
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
							'default'     => 'center',
						)
					);

					$this->add_control(
						'front_vertical_alignment',
						array(
							'label'       => esc_html__( 'Vertical Position', 'alpha-core' ),
							'type'        => Controls_Manager::CHOOSE,
							'description' => esc_html__( 'Control flipbox frontside vertical alignment.', 'alpha-core' ),
							'default'     => 'center',
							'options'     => array(
								'flex-start' => array(
									'title' => esc_html__( 'Top', 'alpha-core' ),
									'icon'  => 'eicon-v-align-top',
								),
								'center'     => array(
									'title' => esc_html__( 'Middle', 'alpha-core' ),
									'icon'  => 'eicon-v-align-middle',
								),
								'flex-end'   => array(
									'title' => esc_html__( 'Bottom', 'alpha-core' ),
									'icon'  => 'eicon-v-align-bottom',
								),
							),
							'separator'   => 'after',
							'selectors'   => array(
								'{{WRAPPER}} .flipbox .flipbox_front' => 'justify-content: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'front_heading_icon_style',
						array(
							'label' => esc_html__( 'Icon', 'alpha-core' ),
							'type'  => Controls_Manager::HEADING,
						)
					);

					$this->add_control(
						'front_icon_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_front .flipbox-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'front_icon_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside icon color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon.stacked' => 'background-color: {{VALUE}};',
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon.framed' => 'border-color: {{VALUE}};',
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon.framed' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon.default' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'front_side_icon[library]!' => 'svg',
							),
						)
					);

					$this->add_control(
						'front_icon_svg_stroke',
						array(
							'label'       => esc_html__( 'Stroke Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside svg stroke color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon svg' => 'stroke: {{VALUE}};',
							),
							'condition'   => array(
								'front_side_icon[library]' => 'svg',
							),
						)
					);

					$this->add_control(
						'front_icon_svg_fill',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside svg fill color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon svg' => 'fill: {{VALUE}};',
							),
							'condition'   => array(
								'front_side_icon[library]' => 'svg',
							),
						)
					);

					$this->add_control(
						'front_icon_size',
						array(
							'label'       => esc_html__( 'Size (px)', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside icon size.', 'alpha-core' ),
							'type'        => Controls_Manager::SLIDER,
							'range'       => array(
								'px' => array(
									'min' => 6,
									'max' => 300,
								),
							),
							'default'     => array(
								'size' => 60,
								'unit' => 'px',
							),
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}}' => '--alpha-flipbox-front-icon-size: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'front_heading_title_style',
						array(
							'label'     => esc_html__( 'Title', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$this->add_control(
						'front_title_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_front .flipbox-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'front_title_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside title color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-title' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'front_title_typography',
							'selector' => '.elementor-element-{{ID}} .flipbox_front .flipbox-title',
						)
					);

					$this->add_control(
						'front_heading_subtitle_style',
						array(
							'label'     => esc_html__( 'Subtitle', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$this->add_control(
						'front_subtitle_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_front .flipbox-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'front_subtitle_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside subtitle color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-subtitle' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'front_subtitle_typography',
							'selector' => '.elementor-element-{{ID}} .flipbox_front .flipbox-subtitle',
						)
					);

					$this->add_control(
						'front_heading_desc_style',
						array(
							'label'     => esc_html__( 'Description', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$this->add_control(
						'front_desc_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_front .flipbox-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'front_desc_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox frontside description color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_front .flipbox-description' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'front_description_typography',
							'selector' => '.elementor-element-{{ID}} .flipbox_front .flipbox-description',
						)
					);

					$this->add_control(
						'front_heading_button_style',
						array(
							'label'     => esc_html__( 'Button', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					alpha_elementor_button_layout_controls( $this, '', 'yes', 'front_' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_flipbox_back_style',
			array(
				'label' => esc_html__( 'Back', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

					$this->add_responsive_control(
						'back_side_padding',
						array(
							'label'       => esc_html__( 'Padding', 'alpha-core' ),
							'description' => esc_html__( 'Control the flipbox backside padding.', 'alpha-core' ),
							'type'        => Controls_Manager::DIMENSIONS,
							'size_units'  => array( 'px', 'rem', '%' ),
							'selectors'   => array(
								'{{WRAPPER}} .flipbox .flipbox_back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'back_side_align',
						array(
							'label'       => esc_html__( 'Alignment', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside horizontal alignment.', 'alpha-core' ),
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
							'default'     => 'center',
						)
					);

					$this->add_control(
						'back_vertical_alignment',
						array(
							'label'       => esc_html__( 'Vertical Position', 'alpha-core' ),
							'type'        => Controls_Manager::CHOOSE,
							'description' => esc_html__( 'Control flipbox backside vertical alignment.', 'alpha-core' ),
							'default'     => 'center',
							'options'     => array(
								'flex-start' => array(
									'title' => esc_html__( 'Top', 'alpha-core' ),
									'icon'  => 'eicon-v-align-top',
								),
								'center'     => array(
									'title' => esc_html__( 'Middle', 'alpha-core' ),
									'icon'  => 'eicon-v-align-middle',
								),
								'flex-end'   => array(
									'title' => esc_html__( 'Bottom', 'alpha-core' ),
									'icon'  => 'eicon-v-align-bottom',
								),
							),
							'separator'   => 'after',
							'selectors'   => array(
								'{{WRAPPER}} .flipbox .flipbox_back' => 'justify-content: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'back_heading_icon_style',
						array(
							'label' => esc_html__( 'Icon', 'alpha-core' ),
							'type'  => Controls_Manager::HEADING,
						)
					);

					$this->add_control(
						'back_icon_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_back .flipbox-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'back_icon_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside icon color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon.stacked' => 'background-color: {{VALUE}};',
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon.framed' => 'border-color: {{VALUE}};',
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon.framed' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon.default' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'back_side_icon[library]!' => 'svg',
							),
						)
					);

					$this->add_control(
						'back_icon_svg_stroke',
						array(
							'label'       => esc_html__( 'Stroke Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside svg stroke color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon svg' => 'stroke: {{VALUE}};',
							),
							'condition'   => array(
								'back_side_icon[library]' => 'svg',
							),
						)
					);

					$this->add_control(
						'back_icon_svg_fill',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside svg fill color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon svg' => 'fill: {{VALUE}};',
							),
							'condition'   => array(
								'back_side_icon[library]' => 'svg',
							),
						)
					);

					$this->add_control(
						'back_icon_size',
						array(
							'label'       => esc_html__( 'Size (px)', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside icon size.', 'alpha-core' ),
							'type'        => Controls_Manager::SLIDER,
							'range'       => array(
								'px' => array(
									'min' => 6,
									'max' => 300,
								),
							),
							'default'     => array(
								'size' => 60,
								'unit' => 'px',
							),
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}}' => '--alpha-flipbox-back-icon-size: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'back_heading_title_style',
						array(
							'label'     => esc_html__( 'Title', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$this->add_control(
						'back_title_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_back .flipbox-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'back_title_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside title color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-title' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'back_title_typography',
							'selector' => '.elementor-element-{{ID}} .flipbox_back .flipbox-title',
						)
					);

					$this->add_control(
						'back_heading_subtitle_style',
						array(
							'label'     => esc_html__( 'Subtitle', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$this->add_control(
						'back_subtitle_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_back .flipbox-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'back_subtitle_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside subtitle color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-subtitle' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'back_subtitle_typography',
							'selector' => '.elementor-element-{{ID}} .flipbox_back .flipbox-subtitle',
						)
					);

					$this->add_control(
						'back_heading_desc_style',
						array(
							'label'     => esc_html__( 'Description', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$this->add_control(
						'back_desc_spacing',
						array(
							'label'     => esc_html__( 'Spacing (px)', 'alpha-core' ),
							'type'      => Controls_Manager::SLIDER,
							'range'     => array(
								'px' => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'selectors' => array(
								'{{WRAPPER}} .flipbox_back .flipbox-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$this->add_control(
						'back_desc_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Control flipbox backside description color.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .flipbox_back .flipbox-description' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_group_control(
						Group_Control_Typography::get_type(),
						array(
							'name'     => 'back_description_typography',
							'selector' => '.elementor-element-{{ID}} .flipbox_back .flipbox-description',
						)
					);

					$this->add_control(
						'back_heading_button_style',
						array(
							'label'     => esc_html__( 'Button', 'alpha-core' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					alpha_elementor_button_layout_controls( $this, '', 'yes', 'back_' );

		$this->end_controls_section();
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;

		$this->add_inline_editing_attributes( 'front_side_title' );
		$this->add_inline_editing_attributes( 'front_side_subtitle' );
		$this->add_inline_editing_attributes( 'front_side_content' );
		$this->add_inline_editing_attributes( 'front_side_button_text' );
		$this->add_inline_editing_attributes( 'back_side_title' );
		$this->add_inline_editing_attributes( 'back_side_subtitle' );
		$this->add_inline_editing_attributes( 'back_side_content' );
		$this->add_inline_editing_attributes( 'back_side_button_text' );
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/flipbox/render-flipbox-elementor.php' );
	}

	public function before_render() {
		$atts = $this->get_settings_for_display();
		?>
		<div <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
		<?php
	}

	protected function content_template() {
		$widget_id = $this->get_id();
		?>
		<#
		let flipbox_class = ['flipbox'],
			html = '';

		if ( settings.flipbox_animation_effect ) {
			flipbox_class.push( settings.flipbox_animation_effect.split( '-' )[1] );
			flipbox_class.push( settings.flipbox_animation_effect );
		}

		html += '<div class="' + flipbox_class.join(' ') + '" data-flipbox-settings="{effect: ' + settings.flipbox_animation_effect + '}">';

		// Font Side Content
		html += '<div class="flipbox_front ' + settings.front_side_align + '-align">';
			html += render_flipbox_content( 'front' );
		html += '</div>';

		// Back Side content
		html += '<div class="flipbox_back ' + settings.back_side_align + '-align">';
			html += render_flipbox_content( 'back' );
		html += '</div>';

		html += '</div>';

		print( html );

		/**
		 * Render flipbox Front and Back side content
		 */
		function render_flipbox_content( mode = 'front' ) {
			let content_str = '',
				btn_class = 'btn',
				btn_label = '',
				icon_html = elementor.helpers.renderIcon( view, settings[ mode + '_side_icon' ], { 'aria-hidden': true }, 'i' , 'object' );

			<?php
				alpha_elementor_button_template();
			?>

			btn_class += ' ' + alpha_widget_button_get_class( settings, mode + '_' ).join( ' ' );
			btn_label = alpha_widget_button_get_label( settings, view, settings[ mode + '_side_button_text' ], 'label', mode + '_' );

			// Icon
			let icon_wrap_class = ['flipbox-icon'];

			if ( settings[ mode + '_icon_type' ] ) {
				icon_wrap_class.push( settings[ mode + '_icon_type' ]);
			}

			if ( settings[ mode + '_icon_shape' ] ) {
				icon_wrap_class.push( settings[ mode + '_icon_shape' ] );
			}
			if ( settings[ mode + '_side_icon' ] && settings[ mode + '_side_icon' ]['value'] ) {
				content_str += '<div class="flipbox-icon-wrap">';
				content_str += '<span class="' + icon_wrap_class.join(' ') + ('svg' == settings[mode + '_side_icon']['library'] ? ' flipbox-svg' : '') + '">';
					if ( icon_html && icon_html.rendered ) {
						content_str += icon_html.value;
					} else {
						content_str += '<i class="' + settings[ mode + '_side_icon' ]['value'] + '"></i>';
					}
				content_str += '</span></div>';
			}

			content_str += '<div class="flipbox-content">';

			// Title
			if ( settings[ mode + '_side_title' ] ) {
				view.addRenderAttribute( mode + '_side_title', 'class', 'flipbox-title' );
				view.addInlineEditingAttributes( mode + '_side_title' );

				content_str += '<h3 ' + view.getRenderAttributeString( mode + '_side_title' ) + '>';
					content_str += settings[ mode + '_side_title' ];
				content_str += '</h3>';
			}

			// Subtitle
			if ( settings[ mode + '_side_subtitle' ] ) {
				view.addRenderAttribute( mode + '_side_subtitle', 'class', 'flipbox-subtitle' );
				view.addInlineEditingAttributes( mode + '_side_subtitle' );

				content_str += '<h4 ' + view.getRenderAttributeString( mode + '_side_subtitle' ) + '>';
					content_str += settings[ mode + '_side_subtitle' ];
				content_str += '</h4>';
			}

			// Description
			if ( settings[ mode + '_side_content' ] ) {
				view.addRenderAttribute( mode + '_side_content', 'class', 'flipbox-description' );
				view.addInlineEditingAttributes( mode + '_side_content' );

				content_str += '<p ' + view.getRenderAttributeString( mode + '_side_content' ) + '>';
					content_str += settings[ mode + '_side_content' ];
				content_str += '</p>';
			}

			// View More Button
			if ( settings[ mode + '_side_button_text' ] ) {
				view.addRenderAttribute( mode + '_side_button_text', 'class', btn_class );
				view.addInlineEditingAttributes( mode + '_side_button_text' );
				button_attrs = {};
				button_link = settings[mode + '_side_button_link'];
				button_attrs['href'] = button_link['url'].length ? button_link['url'] : '#';
				button_attrs['target'] = button_link['is_external'].length ? '_blank' : '';
				button_attrs['rel'] = button_link['nofollow'].length ? 'nofollow' : '';
				if(button_link['custom_attributes'].length) {
					button_link['custom_attributes'].split(',').forEach(function(value){
						key = value.split('|')[0];
						val = value.split('|').slice(1).join(' ');
						if(button_attrs[key]) {
							button_attrs[key] = ' ' + val;
						} else {
							button_attrs[key] = val;
						}
					})
				}

				attrs = '';
				_.each(button_attrs, function(value, key) {
					if(value.length) {
						attrs += key + '="' + value + '" ';
					}
				})

				content_str += '<a ' + view.getRenderAttributeString( mode + '_side_button_text' ) + ' ' + attrs +'>' + btn_label + '</a>';
			}

			content_str += '</div>';

			return content_str;
		}
		#>
		<?php
	}
}
