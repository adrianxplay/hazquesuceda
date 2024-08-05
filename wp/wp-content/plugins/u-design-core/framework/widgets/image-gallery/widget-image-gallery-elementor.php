<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Image Gallery Widget
 *
 * Alpha Widget to display image.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Image_Gallery_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_imagegallery';
	}

	public function get_title() {
		return esc_html__( 'Image Gallery', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-slider-push';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'imagegallery', 'slider', 'carousel', 'grid', 'lightbox' );
	}

	/**
	 * Get style depends
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-image-gallery', alpha_core_framework_uri( '/widgets/image-gallery/image-gallery' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-magnific-popup', 'alpha-image-gallery' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-image-gallery', alpha_core_framework_uri( '/widgets/image-gallery/image-gallery' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'swiper', 'alpha-magnific-popup', 'alpha-image-gallery' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_image_carousel',
			array(
				'label' => esc_html__( 'Images', 'alpha-core' ),
			)
		);

		$this->add_control(
			'images',
			array(
				'label'       => esc_html__( 'Add Images', 'alpha-core' ),
				'type'        => Controls_Manager::GALLERY,
				'default'     => array(),
				'show_label'  => false,
				'description' => esc_html__( 'Insert images from the library', 'alpha-core' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'        => 'gallery_image',
				'separator'   => 'none',
				'default'     => 'full',
				'description' => esc_html__( 'Choose proper image size', 'alpha-core' ),
				'exclude'     => [ 'custom' ],
			)
		);

		$this->add_control(
			'image_popup',
			array(
				'label'       => esc_html__( 'Enable Popup', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Allow you to use image popup.', 'alpha-core' ),
				'default'     => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => esc_html__( 'Layout', 'alpha-core' ),
			)
		);

		$this->add_control(
			'layout_type',
			array(
				'label'       => esc_html__( 'Layout', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'grid',
				'toggle'      => false,
				'description' => esc_html__( 'Set gallery layout', 'alpha-core' ),
				'options'     => array(
					'grid'     => array(
						'title' => esc_html__( 'Grid', 'alpha-core' ),
						'icon'  => 'eicon-column',
					),
					'slider'   => array(
						'title' => esc_html__( 'Slider', 'alpha-core' ),
						'icon'  => 'eicon-slider-3d',
					),
					'creative' => array(
						'title' => esc_html__( 'Creative Grid', 'alpha-core' ),
						'icon'  => 'eicon-inner-section',
					),
				),
			)
		);

		alpha_elementor_grid_layout_controls( $this, 'layout_type', true, 'has_rows' );

		$this->add_control(
			'grid_image_expand',
			array(
				'label'       => esc_html__( 'Image Full Width', 'alpha-core' ),
				'description' => esc_html__( 'Box would be filled with image', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'selectors'   => array(
					'.elementor-element-{{ID}} .image-gallery .image-gallery-item, .elementor-element-{{ID}} .image-wrap img' => 'width: 100%;',
				),
				'condition'   => array(
					'layout_type' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'grid_horizontal_align',
			array(
				'label'       => esc_html__( 'Horizontal Align', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'description' => esc_html__( 'Control the horizontal align of gallery', 'alpha-core' ),
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} li' => 'display: flex; justify-content:{{VALUE}}',
				),
				'condition'   => array(
					'grid_image_expand' => '',
					'layout_type'       => 'grid',
				),
			)
		);

		$this->add_control(
			'slider_image_expand',
			array(
				'label'       => esc_html__( 'Image Full Width', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Box would be filled with image', 'alpha-core' ),
				'condition'   => array(
					'layout_type' => 'slider',
				),
			)
		);

		$this->add_responsive_control(
			'slider_horizontal_align',
			array(
				'label'       => esc_html__( 'Horizontal Align', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'description' => esc_html__( 'Control the horizontal align of gallery', 'alpha-core' ),
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .slider-slide figure' => 'justify-content:{{VALUE}}',
				),
				'condition'   => array(
					'slider_image_expand' => '',
					'layout_type'         => 'slider',
				),
			)
		);

		$this->add_control(
			'grid_vertical_align',
			array(
				'label'       => esc_html__( 'Vertical Align', 'alpha-core' ),
				'description' => esc_html__( 'Control the vertical align of gallery', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
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
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'alpha-core' ),
						'icon'  => 'eicon-v-align-stretch',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} li' => 'display: flex; align-items:{{VALUE}};',
				),
				'condition'   => array(
					'layout_type' => 'grid',
				),
			)
		);
		
		$this->add_control(
			'slider_vertical_align',
			array(
				'label'       => esc_html__( 'Vertical Align', 'alpha-core' ),
				'description' => esc_html__( 'Choose vertical alignment of items. Choose from Top, Middle, Bottom, Stretch.', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'top'         => array(
						'title' => esc_html__( 'Top', 'alpha-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle'      => array(
						'title' => esc_html__( 'Middle', 'alpha-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom'      => array(
						'title' => esc_html__( 'Bottom', 'alpha-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'same-height' => array(
						'title' => esc_html__( 'Stretch', 'alpha-core' ),
						'icon'  => 'eicon-v-align-stretch',
					),
				),
				'condition'   => array(
					'layout_type' => 'slider',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_overlay',
			array(
				'label' => esc_html__( 'Hover', 'alpha-core' ),
			)
		);

		$this->add_control(
			'overlay',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Hover Effect', 'alpha-core' ),
				'description' => esc_html__( 'Choose image overlay effect on hover.', 'alpha-core' ),
				'options'     => array(
					''           => esc_html__( 'No', 'alpha-core' ),
					'light'      => esc_html__( 'Light', 'alpha-core' ),
					'dark'       => esc_html__( 'Dark', 'alpha-core' ),
					'zoom'       => esc_html__( 'Zoom', 'alpha-core' ),
					'zoom_light' => esc_html__( 'Zoom and Light', 'alpha-core' ),
					'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha-core' ),
					'effect-1'   => esc_html__( 'Effect 1', 'alpha-core' ),
					'effect-2'   => esc_html__( 'Effect 2', 'alpha-core' ),
					'effect-3'   => esc_html__( 'Effect 3', 'alpha-core' ),
					'effect-4'   => esc_html__( 'Effect 4', 'alpha-core' ),
					'effect-5'   => esc_html__( 'Effect 5', 'alpha-core' ),
					'effect-6'   => esc_html__( 'Effect 6', 'alpha-core' ),
					'effect-7'   => esc_html__( 'Effect 7', 'alpha-core' ),
				),
				'qa_selector' => '.image-gallery .image-gallery-item',
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'       => esc_html__( 'Hover Effect Color', 'alpha-core' ),
				'description' => esc_html__( 'Choose image overlay color on hover.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .image-gallery figure:after, .elementor-element-{{ID}} .overlay-effect:after, .elementor-element-{{ID}} .overlay-effect:before' => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'overlay!' => array( '', 'zoom', 'effect-5', 'effect-6', 'effect-7' ),
				),
			)
		);

		$this->add_control(
			'overlay_color1',
			array(
				'label'       => esc_html__( 'Hover Effect Color', 'alpha-core' ),
				'description' => esc_html__( 'Choose image overlay color on hover.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .overlay-effect-5:before, .elementor-element-{{ID}} .overlay-effect-7:before, .elementor-element-{{ID}} .overlay-effect-6+figure:before' => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'overlay' => array( 'effect-5', 'effect-6', 'effect-7' ),
				),
			)
		);

		$this->add_control(
			'overlay_border_color',
			array(
				'label'       => esc_html__( 'Hover Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Choose overlay border color on hover.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .overlay-effect-5:after, .elementor-element-{{ID}} .overlay-effect-6:after, .elementor-element-{{ID}} .overlay-effect-6:before, .elementor-element-{{ID}} .overlay-effect-7:after' => 'border-color: {{VALUE}};',
				),
				'condition'   => array(
					'overlay' => array( 'effect-5', 'effect-6', 'effect-7' ),
				),
			)
		);

		$this->add_control(
			'caption_type',
			array(
				'label'       => esc_html__( 'Content', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'description' => esc_html__( 'Choose content you want to show on hover.', 'alpha-core' ),
				'options'     => array(
					''            => esc_html__( 'None', 'alpha-core' ),
					'icon'        => esc_html__( 'Icon', 'alpha-core' ),
					'title'       => esc_html__( 'Title', 'alpha-core' ),
					'caption'     => esc_html__( 'Caption', 'alpha-core' ),
					'description' => esc_html__( 'Description', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'gallery_icon',
			array(
				'label'                  => esc_html__( 'Choose Icon', 'alpha-core' ),
				'type'                   => Controls_Manager::ICONS,
				'default'                => array(
					'value'   => ALPHA_ICON_PREFIX . '-icon-plus',
					'library' => 'alpha-icons',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'label_block'            => false,
				'condition'              => array(
					'caption_type' => 'icon',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gallery_style',
			array(
				'label' => esc_html__( 'Image', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'gallery_image_border',
			array(
				'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
				'description' => esc_html__( 'Control the border radius of each image', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'rem', '%' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .image-gallery img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gallery_caption_style',
			array(
				'label' => esc_html__( 'Hover Content', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'caption_typo',
				'description' => esc_html__( 'Controls the typography of image labels.', 'alpha-core' ),
				'selector'    => '.elementor-element-{{ID}} figcaption',
			)
		);

		$this->add_control(
			'caption_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Controls the figure caption color.', 'alpha-core' ),
				'selectors'   => array(
					'{{WRAPPER}} figcaption' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();

		alpha_elementor_slider_style_controls( $this, 'layout_type' );
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/image-gallery/render-image-gallery-elementor.php' );
	}
}
