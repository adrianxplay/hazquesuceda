<?php
/**
 * Alpha IconBox Widget
 *
 * Alpha Widget to display iconbox.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Icon_Box_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_iconbox';
	}

	public function get_title() {
		return esc_html__( 'Icon Box', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-icon-box';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'icon', 'box' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-icon-box', ALPHA_CORE_INC_URI . '/widgets/icon-box/icon-box' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		return array( 'alpha-icon-box' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

		$this->add_control(
			'selected_icon',
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
			'link',
			array(
				'label'       => esc_html__( 'Link Url', 'alpha-core' ),
				'type'        => Controls_Manager::URL,
				'description' => esc_html__( 'Input URL where you will move when iconbox is clicked.', 'alpha-core' ),
				'default'     => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'title_text',
			array(
				'label'       => esc_html__( 'Title', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Input iconbox title.', 'alpha-core' ),
				'default'     => esc_html__( 'This is the heading', 'alpha-core' ),
				'placeholder' => esc_html__( 'Enter your title', 'alpha-core' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'       => esc_html__( 'Title HTML Tag', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select the HTML Title tag from H1 to H6 and P tag too.', 'alpha-core' ),
				'options'     => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				),
				'default'     => 'h3',
			)
		);

		$this->add_control(
			'description_text',
			array(
				'label'       => esc_html__( 'Description', 'alpha-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input iconbox content.', 'alpha-core' ),
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpha-core' ),
				'placeholder' => esc_html__( 'Enter your description', 'alpha-core' ),
				'rows'        => 10,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'     => esc_html__( 'Show Button', 'alpha-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'     => esc_html__( 'Label', 'alpha-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'alpha-core' ),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		alpha_elementor_button_layout_controls( $this, 'show_button', 'yes' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'alpha-core' ),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'       => esc_html__( 'Icon Position', 'alpha-core' ),
				'description' => esc_html__( 'Select the icon position', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'top',
				'options'     => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'alpha-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'      => false,
			)
		);

		$this->add_responsive_control(
			'box_align',
			array(
				'label'       => esc_html__( 'Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Select the icon box\'s alignment.', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box.icon-box-side' => 'justify-content: {{VALUE}};',
				),
				'condition'   => array(
					'icon_position!' => 'top',
				),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'       => esc_html__( 'Text Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Select the content\'s alignment.', 'alpha-core' ),
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
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'vertical_alignment',
			array(
				'label'       => esc_html__( 'Vertical Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Select the iconbox vertical alignment.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'flex-start' => esc_html__( 'Top', 'alpha-core' ),
					'center'     => esc_html__( 'Middle', 'alpha-core' ),
					'flex-end'   => esc_html__( 'Bottom', 'alpha-core' ),
				),
				'default'     => 'flex-start',
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box' => 'align-items: {{VALUE}};',
				),
				'condition'   => array(
					'icon_position!' => 'top',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_icon',
			array(
				'label' => esc_html__( 'Icon', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'info_box_icon_type',
			array(
				'label'       => esc_html__( 'Icon View', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'description' => esc_html__( 'Select the icon type.', 'alpha-core' ),
				'options'     => array(
					'default' => esc_html__( 'Default', 'alpha-core' ),
					'stacked' => esc_html__( 'Stacked', 'alpha-core' ),
					'framed'  => esc_html__( 'Framed', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'info_box_icon_shape',
			array(
				'label'       => esc_html__( 'Shape', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select the icon shape.', 'alpha-core' ),
				'default'     => 'circle',
				'options'     => array(
					'circle' => esc_html__( 'Circle', 'alpha-core' ),
					''       => esc_html__( 'Square', 'alpha-core' ),
				),
				'condition'   => array(
					'info_box_icon_type!' => 'default',
				),
			)
		);

		$this->add_responsive_control(
			'info_space',
			array(
				'label'       => esc_html__( 'Spacing', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Control the space between icon and content.', 'alpha-core' ),
				'default'     => array(
					'size' => 15,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .position-right .icon-box-feature' => 'margin-left: {{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} .position-left .icon-box-feature' => 'margin-right: {{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} .position-top .icon-box-feature' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'info_size',
			array(
				'label'       => esc_html__( 'Size', 'alpha-core' ),
				'default'     => array(
					'size' => 150,
					'unit' => 'px',
				),
				'description' => esc_html__( 'Control icon box size.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'rem',
				),
				'range'       => array(
					'px'  => array(
						'min' => 6,
						'max' => 300,
					),
					'rem' => array(
						'min' => 6,
						'max' => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} .icon-box-side .icon-box-feature' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'info_box_icon_type!' => 'default',
				),
			)
		);

		$this->add_responsive_control(
			'info_icon_size',
			array(
				'label'       => esc_html__( 'Icon Size', 'alpha-core' ),
				'description' => esc_html__( 'Control icon size.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'rem',
				),
				'range'       => array(
					'px'  => array(
						'min' => 6,
						'max' => 300,
					),
					'rem' => array(
						'min' => 6,
						'max' => 30,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .icon-box-feature i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .icon-box-feature svg' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'border_width',
			array(
				'label'       => esc_html__( 'Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Control icon box border width.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array(
					'info_box_icon_type' => 'framed',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Control icon box border radius.', 'alpha-core' ),
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array(
					'info_box_icon_shape' => '',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_icon_color' );
			$this->start_controls_tab(
				'tab_icon_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

				$this->add_control(
					'info_box_icon_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'selected_icon[library]!' => 'svg',
						),
					)
				);

				$this->add_control(
					'info_box_svg_stroke',
					array(
						'label'     => esc_html__( 'Stroke Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box .icon-box-feature svg' => 'stroke: {{VALUE}};',
						),
						'condition' => array(
							'selected_icon[library]' => 'svg',
						),
					)
				);

				$this->add_control(
					'info_box_svg_fill',
					array(
						'label'     => esc_html__( 'Fill Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box .icon-box-feature svg' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							'selected_icon[library]' => 'svg',
						),
					)
				);

				$this->add_control(
					'info_box_icon_bg_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							'info_box_icon_type' => 'stacked',
						),
					)
				);

				$this->add_control(
					'info_box_icon_border_color',
					array(
						'label'     => esc_html__( 'Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box .icon-box-feature' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'info_box_icon_type' => 'framed',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_icon_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

				$this->add_control(
					'info_box_icon_hover_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box:hover .icon-box-feature' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'selected_icon[library]!' => 'svg',
						),
					)
				);

				$this->add_control(
					'info_box_svg_hover_stroke',
					array(
						'label'     => esc_html__( 'Stroke Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box:hover .icon-box-feature svg' => 'stroke: {{VALUE}};',
						),
						'condition' => array(
							'selected_icon[library]' => 'svg',
						),
					)
				);

				$this->add_control(
					'info_box_svg_hover_fill',
					array(
						'label'     => esc_html__( 'Fill Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box:hover .icon-box-feature svg' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							'selected_icon[library]' => 'svg',
						),
					)
				);

				$this->add_control(
					'info_box_icon_hover_bg_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box:hover .icon-box-feature' => 'background-color: {{VALUE}}',
							'.elementor-element-{{ID}} .icon-box:hover .icon-box-feature:after' => 'box-shadow: 0 0 0 2px {{VALUE}}',
						),
						'condition' => array(
							'info_box_icon_type' => 'stacked',
						),
					)
				);

				$this->add_control(
					'info_box_icon_hover_border_color',
					array(
						'label'     => esc_html__( 'Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .icon-box:hover .icon-box-feature' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'info_box_icon_type' => 'framed',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'info_box_hover',
			array(
				'label'     => esc_html__( 'Animation on Hover', 'alpha-core' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => '',
				'options'   => array(
					''       => esc_html__( 'None', 'alpha-core' ),
					'float'  => esc_html__( 'Float', 'alpha-core' ),
					'rotate' => esc_html__( 'Rotate', 'alpha-core' ),
					'grow'   => esc_html__( 'Grow', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'info_box_icon_hover',
			array(
				'label'     => esc_html__( 'Enable Overlay on Hover', 'alpha-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'info_box_icon_type!' => 'default',
				),
			)
		);

		$this->add_control(
			'info_box_icon_hover_overlay_color',
			array(
				'label'     => esc_html__( 'Overlay Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.elementor-element-{{ID}} .icon-box .icon-box-feature:after' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'info_box_icon_type!' => 'default',
					'info_box_icon_hover' => 'yes',
				),
			)
		);

		$this->add_control(
			'info_box_icon_shadow',
			array(
				'label' => esc_html__( 'Enable Shadow', 'alpha-core' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Title', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'title_space',
			array(
				'label'       => esc_html__( 'Spacing', 'alpha-core' ),
				'description' => esc_html__( 'Control space between title and description.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .icon-box-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.elementor-element-{{ID}} .icon-box:hover .icon-box-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .icon-box-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_desc',
			array(
				'label' => esc_html__( 'Description', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'desc_space',
			array(
				'label'       => esc_html__( 'Spacing', 'alpha-core' ),
				'description' => esc_html__( 'Control space between description and button.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 20,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-box-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.elementor-element-{{ID}} .icon-box-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.elementor-element-{{ID}} .icon-box:hover .icon-box-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '.elementor-element-{{ID}} .icon-box-desc',
			)
		);

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this, array( 'show_button' => 'yes' ), esc_html__( 'Button', 'alpha-core' ), '', false );

	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title_text' );
		$this->add_inline_editing_attributes( 'description_text' );
		$this->add_inline_editing_attributes( 'button_label' );

		require ALPHA_CORE_INC . '/widgets/icon-box/render-icon-box-elementor.php';
	}

	protected function content_template() {
		?>

		<#
		let wrapper_cls = ['icon-box'],
			html = '',
			icon_html = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' );

		<?php
			alpha_elementor_button_template();
		?>

		if ( 'top' != settings.icon_position ) {
			wrapper_cls.push( 'icon-box-side' );
		}
		wrapper_cls.push( 'position-' + settings.icon_position );

		wrapper_cls.push( 'icon-' + settings.info_box_icon_type );
		if ( 'yes' == settings.info_box_icon_shadow ) {
			wrapper_cls.push( 'icon-box-icon-shadow' );
		}
		if ( settings.info_box_icon_shape ) {
			wrapper_cls.push( 'shape-' + settings.info_box_icon_shape );
		}

		if ( 'default' != settings.info_box_icon_type ) {
			if ( settings.info_box_icon_hover ) {
				wrapper_cls.push( 'hover-overlay' );
				wrapper_cls.push( 'hover-' + settings.info_box_icon_type );
			}
		}
		if ( settings.info_box_hover ) {
			wrapper_cls.push( settings.info_box_hover );
		}

		var linkAttr = 'href="'  + ( settings.link.url ? settings.link.url : '#' ) + '"';
		var linkOpen = settings.link.url ? '<a class="link" ' + linkAttr + '>' : '';
		var linkClose = settings.link.url ? '</a>' : '';

		html += '<div class="' + wrapper_cls.join( ' ' ) + '">';

			if ( settings.link.url ) {
				html += linkOpen + linkClose;
			}

			html += '<div class="icon-box-feature">';

			if ( icon_html && icon_html.rendered ) {
				html += icon_html.value;
			} else {
				html += linkOpen + '<i class="' + settings.selected_icon.value + '"></i>' + linkClose;
			}

			html += '</div>';

			html += '<div class="icon-box-content">';

		if ( settings.title_text ) {
			view.addRenderAttribute( 'title_text', 'class', 'icon-box-title' );
			view.addInlineEditingAttributes( 'title_text' );
			var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag );
			html += linkOpen + '<' + titleHTMLTag + ' ' + view.getRenderAttributeString( 'title_text' ) + '>' + settings.title_text + '</' + titleHTMLTag + '>' + linkClose;
		}
		if ( settings.description_text ) {
			view.addRenderAttribute( 'description_text', 'class', 'icon-box-desc' );
			view.addInlineEditingAttributes( 'description_text' );
			html += '<p ' + view.getRenderAttributeString( 'description_text' ) + '>' + settings.description_text + '</p>';
		}

		if ( 'yes' == settings.show_button ) {

			var linkAttr = 'href="'  + ( settings.link.url ? settings.link.url : '#' ) + '"';
			
			view.addInlineEditingAttributes( 'button_label' );

			<?php
				alpha_elementor_button_template();
			?>

			var buttonLabel = alpha_widget_button_get_label( settings, view, settings.button_label, 'button_label' );
			var buttonClass    = alpha_widget_button_get_class( settings );
			buttonClass = 'btn ' + buttonClass.join(' ');
			
			html  += '<a class="' + buttonClass +  '" ' + linkAttr + '>' + buttonLabel + '</a>';
		}

		html += '</div>';
		html += '</div>';
		print( html );
		#>
		<?php
	}
}
