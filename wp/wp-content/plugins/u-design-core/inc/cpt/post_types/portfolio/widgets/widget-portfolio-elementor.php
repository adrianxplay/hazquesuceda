<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alpha Portfolio Widget
 *
 * Alpha Widget to display portfolio
 *
 * @since 4.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Alpha_Controls_Manager;

class Alpha_Portfolio_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_portfolios';
	}

	public function get_title() {
		return esc_html__( 'Portfolio', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-portfolio';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'portfolio', 'project', 'custom post' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		wp_register_style( 'alpha-portfolio', ALPHA_CORE_INC_URI . '/cpt/post_types/portfolio/assets/portfolio' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array( 'alpha-post' ), ALPHA_CORE_VERSION );

		return array( 'alpha-portfolio' );
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

		alpha_elementor_slider_layout_controls( $this, 'layout_type' );

		$this->end_controls_section();

		$this->start_controls_section(
			'portfolio_select',
			array(
				'label' => esc_html__( 'Query', 'alpha-core' ),
			)
		);

		$this->add_control(
			'portfolio_ids',
			array(
				'label'       => esc_html__( 'Select Portfolios', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => ALPHA_NAME . '_portfolio',
				'label_block' => true,
				'multiple'    => true,
			)
		);

		$this->add_control(
			'categories',
			array(
				'label'       => esc_html__( 'Select Categories', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => ALPHA_NAME . '_portfolio_category',
				'label_block' => true,
				'multiple'    => true,
			)
		);

		$this->add_control(
			'skills',
			array(
				'label'       => esc_html__( 'Select Skills', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => 'alpha_portfolio_skill',
				'label_block' => true,
				'multiple'    => true,
			)
		);

		$this->add_control(
			'count',
			array(
				'type'    => Controls_Manager::SLIDER,
				'label'   => esc_html__( 'Portfolio Count', 'alpha-core' ),
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
			'section_portfolio_type',
			array(
				'label' => esc_html__( 'Portfolio Type', 'alpha-core' ),
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
			'portfolio_type',
			array(
				'label'     => esc_html__( 'Portfolio Type', 'alpha-core' ),
				'type'      => Alpha_Controls_Manager::IMAGE_CHOOSE,
				'default'   => 'default',
				'options'   => array(
					'card'    => 'assets/images/portfolios/portfolio-1.jpg',
					'list'    => 'assets/images/portfolios/portfolio-2.jpg',
					'gallery' => 'assets/images/portfolios/portfolio-3.jpg',
					'default' => 'assets/images/portfolios/portfolio-4.jpg',
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
					'portfolio_type!'     => 'gallery',
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
					'portfolio_type!'     => 'gallery',
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
					'portfolio_type!'     => 'gallery',
				),
			)
		);

		$this->add_control(
			'read_more_label',
			array(
				'label'       => esc_html__( 'Read More Label', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'View More', 'alpha-core' ),
				'condition'   => array(
					'follow_theme_option' => '',
					'portfolio_type!'     => array( 'gallery', 'card' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_general',
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
					'portfolio_type!'     => array( 'gallery', 'list' ),
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

		alpha_elementor_button_style_controls(
			$this,
			array(
				'layout_type'   => 'grid',
				'loadmore_type' => 'button',
			),
			esc_html__( 'Load More Button', 'alpha-core' ),
			'',
			false,
			true,
			false
		);

		alpha_elementor_product_style_controls( $this );
		alpha_elementor_slider_style_controls( $this, 'layout_type' );

	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		require ALPHA_CORE_INC . '/cpt/post_types/portfolio/widgets/render-portfolio-elementor.php';
	}
}
