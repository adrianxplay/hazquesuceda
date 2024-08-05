<?php
/**
 * Alpha Filter Widget
 *
 * Alpha Widget to display filter for products.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Alpha_Controls_Manager;

class Alpha_Filter_Elementor_Widget extends \Elementor\Widget_Base {
	public $attributes;

	public function get_name() {
		return ALPHA_NAME . '_widget_filter';
	}

	public function get_title() {
		return esc_html__( 'Advanced Filter', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-filter';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'filter', 'product', 'attribute', 'category', 'tag', 'search' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-filter', alpha_core_framework_uri( '/widgets/filter/filter' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-filter' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-filter', alpha_core_framework_uri( '/widgets/filter/filter' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		return array( 'alpha-shop', 'alpha-filter' );
	}

	protected function register_controls() {

		$post_types          = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			),
			'objects',
			'and'
		);
		$disabled_post_types = array( 'attachment', ALPHA_NAME . '_template', 'page', 'e-landing-page' );
		foreach ( $disabled_post_types as $disabled ) {
			unset( $post_types[ $disabled ] );
		}
		foreach ( $post_types as $key => $p_type ) {
			$post_types[ $key ] = esc_html( $p_type->label );
		}
		$post_types = apply_filters( 'alpha_posts_grid_post_types', $post_types );

		$taxes = get_taxonomies( array(), 'objects' );
		unset( $taxes['post_format'], $taxes['product_visibility'] );
		foreach ( $taxes as $tax_name => $tax ) {
			$taxes[ $tax_name ] = esc_html( $tax->label );
		}
		$taxes = apply_filters( 'alpha_posts_grid_taxonomies', $taxes );

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_filter_content',
			array(
				'label' => esc_html__( 'Filter', 'alpha-core' ),
			)
		);

		$this->add_control(
			'post_type',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Filter Post Type', 'alpha-core' ),
				'description' => esc_html__( 'Please select a post type of posts to filter.', 'alpha-core' ),
				'options'     => $post_types,
			)
		);

		// $this->add_control(
		// 	'query_opt',
		// 	array(
		// 		'label'       => esc_html__( 'Query Type', 'alpha-core' ),
		// 		'description' => esc_html__( 'Select query type.', 'alpha-core' ),
		// 		'type'        => Controls_Manager::SELECT,
		// 		'options'     => array(
		// 			'and' => esc_html__( 'AND', 'alpha-core' ),
		// 			'or'  => esc_html__( 'OR', 'alpha-core' ),
		// 		),
		// 		'default'     => 'and',
		// 	)
		// );

		$repeater = new Repeater();

		$repeater->add_control(
			'filter_type',
			array(
				'type'        => Alpha_Controls_Manager::SELECT,
				'label'       => esc_html__( 'Type', 'alpha-core' ),
				'description' => esc_html__( 'Select one of filter item type from search or taxonomy.', 'alpha-core' ),
				'default'     => 'taxonomy',
				'options'     => array(
					'search'   => esc_html__( 'Search', 'alpha-core' ),
					'taxonomy' => esc_html__( 'Taxonomy', 'alpha-core' ),
				),
			)
		);

		$repeater->add_control(
			'post_tax',
			array(
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'label'       => esc_html__( 'Taxonomy', 'alpha-core' ),
				'description' => esc_html__( 'Please select a post taxonomy to pull posts from.', 'alpha-core' ),
				'options'     => '%post_type%_alltax',
				'label_block' => true,
				'condition'   => array(
					'filter_type' => 'taxonomy',
				),
			)
		);

		$repeater->add_responsive_control(
			'filter_width',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Width (%)', 'alpha-core' ),
				'description' => esc_html__( 'Controls the width of the filter item.', 'alpha-core' ),
				'default'     => array(
					'size' => '',
					'unit' => '%',
				),
				'size_units'  => array(
					'%',
				),
				'range'       => array(
					'%' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				),
			)
		);

		$repeater->add_control(
			'search_placeholder',
			array(
				'type'      => Alpha_Controls_Manager::TEXT,
				'label'     => esc_html__( 'Placeholder', 'alpha-core' ),
				'default'   => esc_html__( 'Enter your keyword...', 'alpha-core' ),
				'condition' => array(
					'filter_type' => 'search',
				),
			)
		);

		$repeater->add_control(
			'dropdown_title',
			array(
				'type'        => Alpha_Controls_Manager::TEXT,
				'label'       => esc_html__( 'Dropdown Title', 'alpha-core' ),
				'description' => esc_html__( 'Enter your title to show when no terms are selected in taxonomy dropdown.', 'alpha-core' ),
				'condition'   => array(
					'filter_type' => 'taxonomy',
				),
			)
		);

		$presets = array(
			array(
				'filter_type' => 'search',
			),
			array(
				'filter_type' => 'taxonomy',
			),
		);

		$this->add_control(
			'filter_items',
			array(
				'label'       => esc_html__( 'Filter Items', 'alpha-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $presets,
				'title_field' => sprintf( '{{{ filter_type == "search" ? \'%1$s\' : post_tax }}}', esc_html__( 'Search', 'alpha-core' ) ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_content',
			array(
				'label' => esc_html__( 'Button', 'alpha-core' ),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => esc_html__( 'Width (%)', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
				),
				'range'      => array(
					'%' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .form-field-button' => 'width: {{SIZE}}%;',
				),
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'   => esc_html__( 'Label', 'alpha-core' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search', 'alpha-core' ),
			)
		);

		alpha_elementor_button_layout_controls( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_style',
			array(
				'label' => esc_html__( 'Filter', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'filter_gap',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Gap Spacing', 'alpha-core' ),
					'description' => esc_html__( 'Controls the size of spacing between filter items.', 'alpha-core' ),
					'default'     => array(
						'size' => '',
						'unit' => 'px',
					),
					'size_units'  => array(
						'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
						),
					),
					'render_type' => 'template',
					'selectors'   => array(
						'.elementor-element-{{ID}} .alpha-filters' => '--alpha-gap: calc({{SIZE}}{{UNIT}} / 2);',
					),
				)
			);

			$this->add_control(
				'filter_item_height',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Filter Item Height (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the height of filter items.', 'alpha-core' ),
					'default'     => array(
						'size' => '',
						'unit' => 'px',
					),
					'size_units'  => array(
						'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .filter-form-field' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'filter_item_padding',
				array(
					'label'       => esc_html__( 'Filter Item Padding', 'alpha' ),
					'description' => esc_html__( 'Controls padding of filter items.', 'alpha' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'default'     => array(
						'unit' => 'px',
					),
					'size_units'  => array( 'px', '%', 'em' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .filter-form-field > *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this );

		$this->remove_control( 'btn_min_width' );
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'button_label' );

		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/filter/render-filter.php' );
	}
}
