<?php
/**
 * Alpha Image Compare Widget
 *
 * Alpha Widget to compare images
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Image_Compare_Elementor_Widget extends Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_image_compare';
	}

	public function get_title() {
		return esc_html__( 'Image Compare', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-imagecomp';
	}

	public function get_keywords() {
		return array( 'image', 'compare', 'gallery', 'media', 'alpha' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-image-compare', alpha_core_framework_uri( '/widgets/image-compare/image-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-image-compare' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-image-compare', alpha_core_framework_uri( '/widgets/image-compare/image-compare' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-image-compare' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_images',
			array(
				'label' => esc_html__( 'Comparable Images', 'alpha-core' ),
			)
		);

		$this->add_control(
			'text_before',
			array(
				'label'       => esc_html__( 'Before Image Label', 'alpha-core' ),
				'description' => esc_html__( 'Input first compare image label.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Before', 'alpha-core' ),
			)
		);

		$this->add_control(
			'image_before',
			array(
				'label'       => esc_html__( 'Before Image', 'alpha-core' ),
				'description' => esc_html__( 'Select first compare image from the library.', 'alpha-core' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_control(
			'text_after',
			array(
				'label'       => esc_html__( 'After Image Label', 'alpha-core' ),
				'description' => esc_html__( 'input Second compare image label.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'After', 'alpha-core' ),
			)
		);

		$this->add_control(
			'image_after',
			array(
				'label'       => esc_html__( 'After Image', 'alpha-core' ),
				'description' => esc_html__( 'Select second compare image from the library.', 'alpha-core' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'        => 'image',
				'exclude'     => array( 'custom' ),
				'description' => esc_html__( 'Choose proper image size from several ones.', 'alpha-core' ),
				'default'     => 'large',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'alpha-core' ),
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'       => esc_html__( 'Show Labels', 'alpha-core' ),
				'description' => esc_html__( 'Allows you to show labels when the element is hovered.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
			)
		);

		$this->add_control(
			'labels_pos',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Labels Position', 'alpha-core' ),
				'default'     => 'center',
				'description' => esc_html__( 'Choose one from 2 label position modes.', 'alpha-core' ),
				'options'     => array(
					'center'  => esc_html__( 'Image Centered', 'alpha-core' ),
					'stretch' => esc_html__( 'Image Up & Down', 'alpha-core' ),
				),
				'condition'   => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'       => esc_html__( 'Direction', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'horizontal',
				'description' => esc_html__( 'Determines image compare handle direction.', 'alpha-core' ),
				'options'     => array(
					'horizontal' => array(
						'title' => esc_html__( 'Horizontal', 'alpha-core' ),
						'icon'  => 'eicon-navigation-vertical',
					),
					'vertical'   => array(
						'title' => esc_html__( 'Vertical', 'alpha-core' ),
						'icon'  => 'eicon-navigation-horizontal',
					),
				),
				'toggle'      => false,
			)
		);

		$this->add_control(
			'handle_type',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Handle Type', 'alpha-core' ),
				'description' => esc_html__( 'Selects your favourite handle type from 6 default ones.', 'alpha-core' ),
				'default'     => 'rect',
				'options'     => array(
					'none'    => esc_html__( 'None', 'alpha-core' ),
					'line'    => esc_html__( 'Line', 'alpha-core' ),
					'circle'  => esc_html__( 'Circle', 'alpha-core' ),
					'rect'    => esc_html__( 'Rectangle', 'alpha-core' ),
					'arrow'   => esc_html__( 'Arrow', 'alpha-core' ),
					'diamond' => esc_html__( 'Diamond', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'handle_control',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Handle Control', 'alpha-core' ),
				'description' => esc_html__( 'Selects your handle control type from 3 default ones.', 'alpha-core' ),
				'default'     => 'drag_click',
				'options'     => array(
					'drag'       => esc_html__( 'Drag', 'alpha-core' ),
					'drag_click' => esc_html__( 'Drag & Click', 'alpha-core' ),
					'hover'      => esc_html__( 'Hover', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'handle_offset',
			array(
				'label'       => esc_html__( 'Handle Offset (%)', 'alpha-core' ),
				'description' => esc_html__( 'Set your handle offset.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_images_style',
			array(
				'label' => esc_html__( 'Image Compare', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_heading',
			array(
				'label' => esc_html__( 'Image', 'alpha-core' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'image_min_height',
			array(
				'label'       => esc_html__( 'Min Height', 'alpha-core' ),
				'description' => esc_html__( 'Controls min height value of images.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'rem',
					'%',
					'vh',
				),
				'range'       => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 700,
					),
					'rem' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'%'   => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'vh'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icomp-wrapper img' => 'min-height: {{SIZE}}{{UNIT}}; object-fit: cover',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'handle_heading',
			array(
				'label'     => esc_html__( 'Handle', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'handle_size',
			array(
				'label'       => esc_html__( 'Handle Size', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Controls the handle size.', 'alpha-core' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => 2,
						'max' => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icomp-handle' => 'font-size: {{SIZE}}px;',
					'.elementor-element-{{ID}} .icomp-handle:before, .elementor-element-{{ID}} .icomp-handle:after' => 'border-width: {{SIZE}}px',
				),
			)
		);

		$this->add_control(
			'handle_color',
			array(
				'label'       => esc_html__( 'Handle Color', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Controls the handle color.', 'alpha-core' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icomp-handle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'handle_bg_color',
			array(
				'label'       => esc_html__( 'Handle Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the handle background color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .icomp-handle:before, .elementor-element-{{ID}} .icomp-handle:after' => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'handle_type' => array( 'line', 'circle', 'rect' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'        => 'handle_box_shadow',
				'description' => esc_html__( 'Controls the handle boxshadow.', 'alpha-core' ),
				'selector'    => '.elementor-element-{{ID}} .icomp-handle',
			)
		);

		$this->add_control(
			'text_heading',
			array(
				'label'     => esc_html__( 'Label', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Controls color of image labels.', 'alpha-core' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icomp-overlay>div:before' => 'color: {{VALUE}};',
				),
				'condition'   => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'text_bg_color',
			array(
				'label'       => esc_html__( 'Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the background color of image labels.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .icomp-overlay>div:before' => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'text_typo',
				'description' => esc_html__( 'Controls the typography of image labels.', 'alpha-core' ),
				'selector'    => '.elementor-element-{{ID}} .icomp-overlay>div:before',
				'condition'   => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/image-compare/render-image-compare-elementor.php' );
	}

	protected function content_template() {}
}
