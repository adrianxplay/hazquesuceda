<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alpha Member Widget
 *
 * Alpha Widget to display member
 *
 * @since 4.1
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Alpha_Controls_Manager;

class Alpha_Member_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_members';
	}

	public function get_title() {
		return esc_html__( 'Member', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-person';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'member', 'team', 'custom post' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		wp_register_style( 'alpha-share', ALPHA_CORE_INC_URI . '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		wp_register_style( 'alpha-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/member.min.css', array( 'alpha-post', 'alpha-share' ), ALPHA_CORE_VERSION );
		return array( 'alpha-member' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/member' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_VERSION, true );

		return array( 'alpha-member' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'alpha-core' ),
			)
		);

		$this->add_control(
			'layout_type',
			array(
				'label'   => esc_html__( 'Layout', 'alpha-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'grid',
				'toggle'  => false,
				'options' => array(
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

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
				'exclude' => array( 'custom' ),
				'default' => 'medium_large',
			)
		);

		alpha_elementor_grid_layout_controls( $this, 'layout_type', true );

		alpha_elementor_loadmore_layout_controls( $this, 'layout_type' );

		alpha_elementor_slider_layout_controls( $this, 'layout_type' );

		$this->end_controls_section();

		$this->start_controls_section(
			'member_select',
			array(
				'label' => esc_html__( 'Query', 'alpha-core' ),
			)
		);

		$this->add_control(
			'member_ids',
			array(
				'label'       => esc_html__( 'Select Members', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => ALPHA_NAME . '_member',
				'label_block' => true,
				'multiple'    => true,
			)
		);

		$this->add_control(
			'categories',
			array(
				'label'       => esc_html__( 'Select Categories', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => ALPHA_NAME . '_member_category',
				'label_block' => true,
				'multiple'    => true,
			)
		);

		$this->add_control(
			'count',
			array(
				'type'    => Controls_Manager::SLIDER,
				'label'   => esc_html__( 'Member Count', 'alpha-core' ),
				'default' => array(
					'size' => 4,
					'unit' => 'px',
				),
				'range'   => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 50,
					),
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Order By', 'alpha-core' ),
				'default' => 'ID',
				'options' => array(
					''              => esc_html__( 'Default', 'alpha-core' ),
					'ID'            => esc_html__( 'ID', 'alpha-core' ),
					'title'         => esc_html__( 'Title', 'alpha-core' ),
					'date'          => esc_html__( 'Date', 'alpha-core' ),
					'modified'      => esc_html__( 'Modified', 'alpha-core' ),
					'author'        => esc_html__( 'Author', 'alpha-core' ),
					'comment_count' => esc_html__( 'Comment count', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'orderway',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Order Way', 'alpha-core' ),
				'default' => 'ASC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'alpha-core' ),
					'DESC' => esc_html__( 'Descending', 'alpha-core' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_member_type',
			array(
				'label' => esc_html__( 'Member Type', 'alpha-core' ),
			)
		);
		$this->add_control(
			'follow_theme_option',
			array(
				'label'   => esc_html__( 'Follow Theme Option', 'alpha-core' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'member_type',
			array(
				'label'     => esc_html__( 'Member Type', 'alpha-core' ),
				'type'      => Alpha_Controls_Manager::IMAGE_CHOOSE,
				'default'   => 'default',
				'options'   => array(
					'default' => 'assets/images/members/member-1.jpg',
					'card'    => 'assets/images/members/member-2.jpg',
					'gallery' => 'assets/images/members/member-3.jpg',
					'circle'  => 'assets/images/members/member-4.jpg',
					'boxed'   => 'assets/images/members/member-5.jpg',
					'info'    => 'assets/images/members/member-6.jpg',
				),
				'width'     => 1,
				'condition' => array(
					'follow_theme_option' => '',
				),
			)
		);

		$this->add_control(
			'overlay',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Overlay', 'alpha-core' ),
				'options'   => array(
					''           => esc_html__( 'No', 'alpha-core' ),
					'light'      => esc_html__( 'Light', 'alpha-core' ),
					'dark'       => esc_html__( 'Dark', 'alpha-core' ),
					'zoom'       => esc_html__( 'Zoom', 'alpha-core' ),
					'zoom_light' => esc_html__( 'Zoom and Light', 'alpha-core' ),
					'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha-core' ),
				),
				'condition' => array(
					'follow_theme_option' => '',
				),
			)
		);

		$this->add_control(
			'excerpt_custom',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Custom Excerpt', 'alpha-core' ),
				'separator' => 'before',
				'condition' => array(
					'follow_theme_option' => '',
					'member_type'         => 'info',
				),
			)
		);

		$this->add_control(
			'excerpt_type',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Excerpt By', 'alpha-core' ),
				'default'   => 'words',
				'options'   => array(
					'words'     => esc_html__( 'Words', 'alpha-core' ),
					'character' => esc_html__( 'Characters', 'alpha-core' ),
				),
				'condition' => array(
					'follow_theme_option' => '',
					'excerpt_custom'      => 'yes',
					'member_type!'        => 'gallery',
				),
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Excerpt Length', 'alpha-core' ),
				'range'     => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 500,
					),
				),
				'condition' => array(
					'follow_theme_option' => '',
					'excerpt_custom'      => 'yes',
					'member_type!'        => 'gallery',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_general',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'content_align',
				array(
					'label'     => esc_html__( 'Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
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
					'condition' => array(
						'follow_theme_option' => '',
						'member_type!'        => array( 'list' ),
					),
				)
			);

			$this->add_responsive_control(
				'border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .post-wrap .post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		alpha_elementor_slider_style_controls( $this, 'layout_type' );
	}

	public function before_render() {
		// Add `elementor-widget-theme-post-content` class to avoid conflicts that figure gets zero margin.
		$this->add_render_attribute(
			array(
				'_wrapper' => array(
					'class' => 'elementor-widget-theme-post-content',
				),
			)
		);

		parent::before_render();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		require ALPHA_CORE_INC . '/cpt/post_types/member/widgets/member/render-member-elementor.php';
	}
}
