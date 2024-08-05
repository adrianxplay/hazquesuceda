<?php
/**
 * Alpha Elementor Search Widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Alpha_Controls_Manager;

class Alpha_Search_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_search';
	}

	public function get_title() {
		return esc_html__( 'Search', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-search';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'header', 'alpha', 'search', 'find' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_search_content',
			array(
				'label' => esc_html__( 'Search', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'type',
				array(
					'label'       => esc_html__( 'Type', 'alpha-core' ),
					'description' => esc_html__( 'Controls the design of the search form.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						''       => esc_html__( 'Classic', 'alpha-core' ),
						'toggle' => esc_html__( 'Toggle', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'label',
				array(
					'label'     => esc_html__( 'Toggle Label', 'alpha-core' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => esc_html__( 'Search', 'alpha-core' ),
					'condition' => array(
						'type' => 'toggle',
					),
				)
			);

			$this->add_control(
				'toggle_type',
				array(
					'label'     => esc_html__( 'Toggle Type', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'dropdown',
					'options'   => array(
						'overlap'    => esc_html__( 'Overlap', 'alpha-core' ),
						'dropdown'   => esc_html__( 'Dropdown', 'alpha-core' ),
						'fullscreen' => esc_html__( 'Fullscreen', 'alpha-core' ),
					),
					'condition' => array(
						'type' => 'toggle',
					),
				)
			);

			$this->add_control(
				'show_categories',
				array(
					'label'       => esc_html__( 'Show Categories List', 'alpha-core' ),
					'description' => esc_html__( 'Enable to show categories list with input field.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'conditions'  => array(
						'relation' => 'or',
						'terms'    => array(
							array(
								'name'     => 'type',
								'operator' => '!=',
								'value'    => 'toggle',
							),
							array(
								'name'     => 'toggle_type',
								'operator' => '!=',
								'value'    => 'overlap',
							),
						),
					),
				)
			);
			$this->add_control(
				'search_align',
				array(
					'label'     => esc_html__( 'Dropdown Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => 'start',
					'options'   => array(
						'start'  => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'end'    => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'toggle'    => false,
					'condition' => array(
						'type'        => 'toggle',
						'toggle_type' => 'dropdown',
					),
				)
			);
			$this->add_control(
				'search_type',
				array(
					'label'       => esc_html__( 'Search Results Content', 'alpha-core' ),
					'description' => esc_html__( 'Select post types to search', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => apply_filters(
						'alpha_search_content_types',
						class_exists( 'WooCommerce' ) ? array(
							''        => esc_html__( 'All', 'alpha-core' ),
							'product' => esc_html__( 'Product', 'alpha-core' ),
							'post'    => esc_html__( 'Post', 'alpha-core' ),
						) : array(
							''     => esc_html__( 'All', 'alpha-core' ),
							'post' => esc_html__( 'Post', 'alpha-core' ),
						)
					),
					'label_block' => true,
				)
			);

			$this->add_control(
				'placeholder',
				array(
					'label'       => esc_html__( 'Placeholder', 'alpha-core' ),
					'description' => esc_html__( 'Search placeholder', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Search in...', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label'     => esc_html__( 'Toggle', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'type' => 'toggle',
				),
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'toggle_typography',
					'selector' => '.elementor-element-{{ID}} .search-toggle',
				)
			);

			$this->add_responsive_control(
				'toggle_icon_size',
				array(
					'label'       => esc_html__( 'Toggle Icon Size (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the icon size of the toggle.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .search-toggle i' => 'font-size: {{SIZE}}px;',
					),
				)
			);

			$this->add_responsive_control(
				'toggle_icon_spacing',
				array(
					'label'       => esc_html__( 'Toggle Icon Spacing (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the icon spacing of the toggle.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .search-toggle i' => "margin-{$right}: {{SIZE}}px;",
					),
				)
			);
			$this->add_control(
				'toggle_color',
				array(
					'label'     => esc_html__( 'Text Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .search-toggle' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'toggle_hover_color',
				array(
					'label'     => esc_html__( 'Text Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .search-toggle:hover' => 'color: {{VALUE}};',
					),
				)
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => esc_html__( 'Search Form', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'search_width',
			array(
				'label'      => esc_html__( 'Search Width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 200,
						'max'  => 600,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .hs-toggle form' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type'        => 'toggle',
					'toggle_type' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'search_height',
			array(
				'label'       => esc_html__( 'Form Height', 'alpha-core' ),
				'description' => esc_html__( 'Controls the height of form input field.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 46,
						'max'  => 80,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper form' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'search_bg',
			array(
				'label'       => esc_html__( 'Form Background', 'alpha-core' ),
				'description' => esc_html__( 'Controls the background of the form.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper form' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'search_bd',
			array(
				'label'       => esc_html__( 'Form Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border width of the form.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'rem', '%' ),
				'separator'   => 'before',
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper form.input-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
				),
				'condition'   => array(
					'type' => '',
				),
			)
		);
		$this->add_control(
			'search_br',
			array(
				'label'       => esc_html__( 'Form Border Radius', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border radius of the form.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'rem', '%' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper form.input-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array(
					'type' => '',
				),
			)
		);

		$this->add_control(
			'search_bd_color',
			array(
				'label'       => esc_html__( 'Form Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border color of the form.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper form.input-wrapper' => 'border-color: {{VALUE}};',
					'.elementor-element-{{ID}} .search-wrapper form input.form-control, .elementor-element-{{ID}} .search-wrapper .btn-search' => 'border-color: {{VALUE}};',
				),
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'type',
							'operator' => '==',
							'value'    => '',
						),
						array(
							'name'     => 'toggle_type',
							'operator' => '==',
							'value'    => 'fullscreen',
						),
					),
				),
			)
		);

		$this->add_control(
			'overlap_search_bd_color',
			array(
				'label'       => esc_html__( 'Underline Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the underline.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper.hs-overlap form:before' => 'background-color: {{VALUE}};',
				),
				'condition'   => array(
					'type'        => 'toggle',
					'toggle_type' => 'overlap',
				),
			)
		);

		$this->add_control(
			'search_separator_color',
			array(
				'label'       => esc_html__( 'Separator Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the separator color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'separator'   => 'before',
				'selectors'   => array(
					'.elementor-element-{{ID}} .search-wrapper .select-box:after' => 'background: {{VALUE}};',
				),
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'show_categories',
							'operator' => '=',
							'value'    => 'yes',
						),
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'     => 'type',
									'operator' => '!=',
									'value'    => 'toggle',
								),
								array(
									'name'     => 'toggle_type',
									'operator' => '!=',
									'value'    => 'overlap',
								),
							),
						),
					),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => esc_html__( 'Input Field', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'input_typography',
					'label'    => esc_html__( 'Field Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .search-wrapper input.form-control',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'       => 'category_typography',
					'label'      => esc_html__( 'Category Typography', 'alpha-core' ),
					'selector'   => '.elementor-element-{{ID}} select',
					'conditions' => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'show_categories',
								'operator' => '=',
								'value'    => 'yes',
							),
							array(
								'relation' => 'or',
								'terms'    => array(
									array(
										'name'     => 'type',
										'operator' => '!=',
										'value'    => 'toggle',
									),
									array(
										'name'     => 'toggle_type',
										'operator' => '!=',
										'value'    => 'overlap',
									),
								),
							),
						),
					),
				)
			);

			$this->add_control(
				'search_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .search-wrapper input.form-control' => 'color: {{VALUE}};',
						'.elementor-element-{{ID}} .search-wrapper select' => 'color: {{VALUE}};',
						'.elementor-element-{{ID}} .hs-overlap .close-line' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'input_pd',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .search-wrapper input.form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'      => esc_html__( 'Search Button( Icon )', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'type',
							'operator' => '==',
							'value'    => '',
						),
						array(
							'name'     => 'toggle_type',
							'operator' => '!=',
							'value'    => 'overlap',
						),
					),
				),
			)
		);

			$this->add_control(
				'button_width',
				array(
					'label'       => esc_html__( 'Button Width', 'alpha-core' ),
					'description' => esc_html__( 'Controls the width of search button.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .search-wrapper .btn-search' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->add_control(
				'icon_size',
				array(
					'label'       => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the size of the icon.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .search-wrapper .btn-search i' => 'font-size: {{SIZE}}px;',
					),
				)
			);
			$this->add_control(
				'btn_color',
				array(
					'label'       => esc_html__( 'Icon Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of the icon.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .search-wrapper .btn-search' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'btn_hover_color',
				array(
					'label'       => esc_html__( 'Hover Icon Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover color of the icon.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .search-wrapper .btn-search:hover' => 'color: {{VALUE}};',
					),
				)
			);
		$this->end_controls_section();
	}

	public function before_render() {
		$atts = $this->get_settings_for_display();
		if ( 'toggle' == $atts['type'] && 'overlap' == $atts['toggle_type'] ) {
			$this->add_render_attribute( '_wrapper', 'class', 'elementor-widget_alpha_search_overlap' );
		}
		?>
		<div <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( function_exists( 'alpha_search_form' ) ) {

			if ( 'toggle' == $settings['type'] && 'fullscreen' == $settings['toggle_type'] ) {
				if ( ! $settings['search_type'] || 'product' == $settings['search_type'] ) {
					wp_enqueue_style( 'alpha-product', alpha_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
				}
				if ( ! $settings['search_type'] || 'post' == $settings['search_type'] ) {
					wp_enqueue_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
				}
			}

			alpha_search_form(
				array(
					'type'             => $settings['type'],
					'toggle_type'      => empty( $settings['toggle_type'] ) ? 'dropdown' : $settings['toggle_type'],
					'search_align'     => $settings['search_align'],
					'show_categories'  => 'yes' == $settings['show_categories'],
					'where'            => 'header',
					'search_post_type' => $settings['search_type'],
					'placeholder'      => $settings['placeholder'] ? $settings['placeholder'] : esc_html__( 'Search in...', 'alpha-core' ),
					'label'            => $settings['label'],
				)
			);
		}
	}
}
