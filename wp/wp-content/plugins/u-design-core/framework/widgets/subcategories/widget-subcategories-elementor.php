<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Subcategoy Widget
 *
 * Alpha Widget to display subcategory.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */


use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Alpha_Controls_Manager;

class Alpha_Subcategories_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_subcategories';
	}

	public function get_title() {
		return esc_html__( 'Subcategories List', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-table-of-contents';
	}

	public function get_keywords() {
		return array( 'subcategory', 'product', 'post', 'alpha', 'category', 'list' );
	}

	/**
	 * Get Style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-subcat', alpha_core_framework_uri( '/widgets/subcategories/subcat' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-subcat' );
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_list',
			array(
				'label' => esc_html__( 'List', 'alpha-core' ),
			)
		);

			$this->add_control(
				'list_type',
				array(
					'label'       => esc_html__( 'Type', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'pcat',
					'options'     => array(
						'cat'  => esc_html__( 'Categories', 'alpha-core' ),
						'pcat' => esc_html__( 'Product Categories', 'alpha-core' ),
					),
					'description' => esc_html__( 'Choose to show category of POST or PRODUCT.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'category_ids',
				array(
					'label'       => esc_html__( 'Select Categories', 'alpha-core' ),
					'description' => esc_html__( 'Choose parent categories that you want to show subcategories of.', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'options'     => 'category',
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'list_type' => 'cat',
					),
				)
			);

			$this->add_control(
				'product_category_ids',
				array(
					'label'       => esc_html__( 'Select Categories', 'alpha-core' ),
					'description' => esc_html__( 'Choose parent categories that you want to show subcategories of.', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'options'     => 'product_cat',
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'list_type' => 'pcat',
					),
				)
			);

			$this->add_control(
				'show_subcategories',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Show Subcategories', 'alpha-core' ),
					'description' => esc_html__( 'Controls to show/hide subcategories.', 'alpha-core' ),
					'default'     => 'yes',
				)
			);

			$this->add_control(
				'count',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Subcategories Count', 'alpha-core' ),
					'description' => esc_html__( '0 value will show all categories.', 'alpha-core' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 24,
						),
					),
				)
			);

			$this->add_control(
				'hide_empty',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Hide Empty', 'alpha-core' ),
					'description' => esc_html__( 'Choose to show/hide empty subcategories which have no products or posts.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'list_style',
				array(
					'label'       => esc_html__( 'Style', 'alpha-core' ),
					'description' => esc_html__( 'Choose subcategory style.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						''          => esc_html__( 'Simple', 'alpha-core' ),
						'underline' => esc_html__( 'Underline', 'alpha-core' ),
					),
					'condition'   => array(
						'show_subcategories' => 'yes',
					),
				)
			);

			$this->add_control(
				'view_all',
				array(
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'View All Labels', 'alpha-core' ),
					'description' => esc_html__( 'This label link will be appended to subcategories list.', 'alpha-core' ),
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'cat_delimiter',
				array(
					'type'        => Controls_Manager::TEXT,
					'label'       => esc_html__( 'Category Delimiter', 'alpha-core' ),
					'description' => esc_html__( 'Type the delimiter text between parent and child categories.', 'alpha-core' ),
					'default'     => '',
					'selectors'   => array(
						'.elementor-element-{{ID}} .subcat-title::after' => "content: '{{value}}'",
						'.elementor-element-{{ID}} .subcat-title' => "margin-{$right}: 0;",
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_type_style',
			array(
				'label'     => esc_html__( 'Title', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_subcategories' => 'yes',
				),
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typo',
					'selector' => '.elementor-element-{{ID}} .subcat-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Choose color of parent category.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .subcat-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'title_space',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Space', 'alpha-core' ),
					'description' => esc_html__( 'Controls space between parent category and child category list.', 'alpha-core' ),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 200,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .subcat-title' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_link_style',
			array(
				'label' => esc_html__( 'Link', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'link_typo',
					'selector' => '.elementor-element-{{ID}} a',
				)
			);

			$this->add_control(
				'link_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Choose color of child categories.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'link__hover_color',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Choose hover color of the child categories.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} a:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'link_space',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Space', 'alpha-core' ),
					'description' => esc_html__( 'Controls space between each subcategory items.', 'alpha-core' ),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 200,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .subcat-nav a+a' => "margin-{$left}: {{SIZE}}{{UNIT}};",
						'.elementor-element-{{ID}} .subcat-nav a+a:before' => "margin-{$right}: {{SIZE}}{{UNIT}};",
						'.elementor-element-{{ID}} .subcat-underline a::after' => "{$left}: {{SIZE}}{{UNIT}};",
					),
					'condition'   => array(
						'show_subcategories' => 'yes',
					),
				)
			);

			$this->add_control(
				'link_space_hide_sub',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Space', 'alpha-core' ),
					'description' => esc_html__( 'Controls space between each category items.', 'alpha-core' ),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 200,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} a' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
					'condition'   => array(
						'show_subcategories!' => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/subcategories/render-subcategories-elementor.php' );
	}
}
