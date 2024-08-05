<?php
/**
 * Products partial
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Alpha_Controls_Manager;


/**
 * Register elementor products layout controls
 *
 * @since 4.0
 */
function alpha_elementor_products_layout_controls( $self, $mode = '' ) {

	$self->start_controls_section(
		'section_products_layout',
		array(
			'label' => esc_html__( 'Products Layout', 'alpha-core' ),
		)
	);

	if ( 'shop_builder' != $mode ) {
		$self->add_control(
			'layout_type',
			array(
				'label'   => esc_html__( 'Products Layout', 'alpha-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'custom_layouts' == $mode ? 'creative' : 'grid',
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
	} else {
		$self->add_control(
			'layout_type',
			array(
				'label'       => esc_html__( 'Products Layout', 'alpha-core' ),
				'description' => esc_html__( 'Choose products layout type: Grid, Slider, Creative Layout', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'grid',
				'condition'   => array(
					'undefined_option' => 'true',
				),
			)
		);
	}

	$self->add_group_control(
		Group_Control_Image_Size::get_type(),
		array(
			'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
			'exclude' => array( 'custom' ),
			'default' => 'woocommerce_thumbnail',
		)
	);

	alpha_elementor_grid_layout_controls( $self, 'layout_type', true, 'product' );
	alpha_elementor_slider_layout_controls( $self, 'layout_type' );

	$self->end_controls_section();

	if ( ! $mode ) {

		$self->start_controls_section(
			'product_filter_section',
			array(
				'label' => esc_html__( 'Product Ajax', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			alpha_elementor_loadmore_layout_controls( $self, 'layout_type' );

			$self->add_control(
				'filter_cat_w',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Filter by Category Widget', 'alpha-core' ),
					'description' => esc_html__( 'If there is a category widget enabled "Filter Products" option in the same section, you can filter products by category widget.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'filter_cat',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Filter by Category', 'alpha-core' ),
					'description' => esc_html__( 'Defines whether to show or hide category filters above products.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'show_all_filter',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Show "All" Filter', 'alpha-core' ),
					'default'   => 'yes',
					'condition' => array(
						'filter_cat' => 'yes',
					),
				)
			);

		$self->end_controls_section();
	}
}
/**
 * Register elementor products select controls
 *
 * @since 4.0
 */
function alpha_elementor_products_select_controls( $self, $add_section = true ) {

	if ( $add_section ) {
		$self->start_controls_section(
			'section_products_selector',
			array(
				'label' => esc_html__( 'Query', 'alpha-core' ),
			)
		);
	}

	$self->add_control(
		'product_ids',
		array(
			'label'       => esc_html__( 'Select Products', 'alpha-core' ),
			'description' => esc_html__( 'Choose product ids of specific products to display.', 'alpha-core' ),
			'type'        => Alpha_Controls_Manager::AJAXSELECT2,
			'options'     => 'product',
			'label_block' => true,
			'multiple'    => 'true',
		)
	);

	$self->add_control(
		'categories',
		array(
			'label'       => esc_html__( 'Select Categories', 'alpha-core' ),
			'description' => esc_html__( 'Choose categories which include products to display.', 'alpha-core' ),
			'type'        => Alpha_Controls_Manager::AJAXSELECT2,
			'options'     => 'product_cat',
			'label_block' => true,
			'multiple'    => 'true',
		)
	);

	if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'brand' ) ) {
		$self->add_control(
			'brands',
			array(
				'label'       => esc_html__( 'Select Brands', 'alpha-core' ),
				'description' => esc_html__( 'Choose brands which include products to display.', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => 'product_brand',
				'label_block' => true,
				'multiple'    => 'true',
			)
		);
	}

	$self->add_control(
		'count',
		array(
			'type'        => Controls_Manager::SLIDER,
			'label'       => esc_html__( 'Product Count', 'alpha-core' ),
			'description' => esc_html__( 'Controls number of products to display or load more.', 'alpha-core' ),
			'default'     => array(
				'unit' => 'px',
				'size' => 10,
			),
			'range'       => array(
				'px' => array(
					'step' => 1,
					'min'  => 1,
					'max'  => 50,
				),
			),
		)
	);

	$self->add_control(
		'status',
		array(
			'label'       => esc_html__( 'Product Status', 'alpha-core' ),
			'description' => esc_html__( 'Choose product status: All, Featured, On Sale, Recently Viewed.', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => '',
			'options'     => array(
				''         => esc_html__( 'All', 'alpha-core' ),
				'featured' => esc_html__( 'Featured', 'alpha-core' ),
				'sale'     => esc_html__( 'On Sale', 'alpha-core' ),
				'viewed'   => esc_html__( 'Recently Viewed', 'alpha-core' ),
			),
		)
	);

	$self->add_control(
		'orderby',
		array(
			'type'        => Controls_Manager::SELECT,
			'label'       => esc_html__( 'Order By', 'alpha-core' ),
			'description' => esc_html__( 'Defines how products should be ordered: Default, ID, Name, Date, Modified, Price, Random, Rating, Total Sales.', 'alpha-core' ),
			'default'     => '',
			'options'     => array(
				''               => esc_html__( 'Default', 'alpha-core' ),
				'ID'             => esc_html__( 'ID', 'alpha-core' ),
				'title'          => esc_html__( 'Name', 'alpha-core' ),
				'date'           => esc_html__( 'Date', 'alpha-core' ),
				'modified'       => esc_html__( 'Modified', 'alpha-core' ),
				'price'          => esc_html__( 'Price', 'alpha-core' ),
				'rand'           => esc_html__( 'Random', 'alpha-core' ),
				'rating'         => esc_html__( 'Rating', 'alpha-core' ),
				'comment_count'  => esc_html__( 'Comment count', 'alpha-core' ),
				'popularity'     => esc_html__( 'Total Sales', 'alpha-core' ),
				'wishqty'        => esc_html__( 'Wish', 'alpha-core' ),
				'sale_date_to'   => esc_html__( 'Sale End Date', 'alpha-core' ),
				'sale_date_from' => esc_html__( 'Sale Start Date', 'alpha-core' ),
			),
			'separator'   => 'before',
		)
	);

	$self->add_control(
		'orderway',
		array(
			'type'        => Controls_Manager::SELECT,
			'label'       => esc_html__( 'Order Way', 'alpha-core' ),
			'description' => esc_html__( 'Defines products ordering type: Ascending or Descending.', 'alpha-core' ),
			'default'     => 'ASC',
			'options'     => array(
				'ASC'  => esc_html__( 'Ascending', 'alpha-core' ),
				'DESC' => esc_html__( 'Descending', 'alpha-core' ),
			),
		)
	);

	$self->add_control(
		'order_from',
		array(
			'label'       => esc_html__( 'Date From', 'alpha-core' ),
			'description' => esc_html__( 'Start date that the ordering will be applied', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => '',
			'options'     => array(
				''       => '',
				'today'  => esc_html__( 'Today', 'alpha-core' ),
				'week'   => esc_html__( 'This Week', 'alpha-core' ),
				'month'  => esc_html__( 'This Month', 'alpha-core' ),
				'year'   => esc_html__( 'This Year', 'alpha-core' ),
				'custom' => esc_html__( 'Custom', 'alpha-core' ),
			),
			'condition'   => array(
				'product_ids' => '',
			),
		)
	);

	$self->add_control(
		'order_from_date',
		array(
			'label'     => esc_html__( 'Date', 'alpha-core' ),
			'type'      => Controls_Manager::DATE_TIME,
			'default'   => '',
			'condition' => array(
				'product_ids' => '',
				'order_from'  => 'custom',
			),
		)
	);

	$self->add_control(
		'order_to',
		array(
			'label'       => esc_html__( 'Date To', 'alpha-core' ),
			'description' => esc_html__( 'End date that the ordering will be applied', 'alpha-core' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => '',
			'options'     => array(
				''       => '',
				'today'  => esc_html__( 'Today', 'alpha-core' ),
				'week'   => esc_html__( 'This Week', 'alpha-core' ),
				'month'  => esc_html__( 'This Month', 'alpha-core' ),
				'year'   => esc_html__( 'This Year', 'alpha-core' ),
				'custom' => esc_html__( 'Custom', 'alpha-core' ),
			),
			'condition'   => array(
				'product_ids' => '',
			),
		)
	);

	$self->add_control(
		'order_to_date',
		array(
			'label'     => esc_html__( 'Date', 'alpha-core' ),
			'type'      => Controls_Manager::DATE_TIME,
			'default'   => '',
			'condition' => array(
				'product_ids' => '',
				'order_to'    => 'custom',
			),
		)
	);

	// $self->add_control(
	// 	'hide_out_date',
	// 	array(
	// 		'type'      => Controls_Manager::SWITCHER,
	// 		'label'     => esc_html__( 'Hide Product Out of Date', 'alpha-core' ),
	// 		'condition' => array(
	// 			'product_ids' => '',
	// 		),
	// 	)
	// );

	if ( $add_section ) {
		$self->end_controls_section();
	}
}

/**
 * Register elementor single product style controls
 *
 * @since 4.0
 */
function alpha_elementor_single_product_style_controls( $self ) {
	$self->start_controls_section(
		'section_sp_style',
		array(
			'label' => esc_html__( 'Single Product', 'alpha-core' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		)
	);

		$self->add_control(
			'product_summary_height',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Summary Max Height', 'alpha-core' ),
				'size_units' => array( 'px', 'rem', '%' ),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .product-single.product-widget .summary' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				),
			)
		);

		$self->start_controls_tabs(
			'sp_tabs',
			array(
				'separator' => 'before',
			)
		);

			$self->start_controls_tab(
				'sp_title_tab',
				array(
					'label' => esc_html__( 'Title', 'alpha-core' ),
				)
			);

				$self->add_control(
					'sp_title_color',
					array(
						'label'     => esc_html__( 'Title Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .product_title a' => 'color: {{VALUE}};',
						),
					)
				);

				$self->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'label'    => esc_html__( 'Title Typography', 'alpha-core' ),
						'name'     => 'sp_title_typo',
						'selector' => '.elementor-element-{{ID}} .product_title',
					)
				);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'sp_price_tab',
				array(
					'label' => esc_html__( 'Price', 'alpha-core' ),
				)
			);

				$self->add_control(
					'sp_price_color',
					array(
						'label'     => esc_html__( 'Price Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} p.price' => 'color: {{VALUE}};',
						),
					)
				);

				$self->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'label'    => esc_html__( 'Price Typography', 'alpha-core' ),
						'name'     => 'sp_price_typo',
						'selector' => '.elementor-element-{{ID}} p.price',
					)
				);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'sp_old_price_tab',
				array(
					'label' => esc_html__( 'Old Price', 'alpha-core' ),
				)
			);

				$self->add_control(
					'sp_old_price_color',
					array(
						'label'     => esc_html__( 'Old Price Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .price del' => 'color: {{VALUE}};',
						),
					)
				);

				$self->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'label'    => esc_html__( 'Old Price Typography', 'alpha-core' ),
						'name'     => 'sp_old_price_typo',
						'selector' => '.elementor-element-{{ID}} .price del',
					)
				);

			$self->end_controls_tab();

		$self->end_controls_tabs();

		$self->add_control(
			'style_heading_countdown',
			array(
				'label'     => esc_html__( 'Countdown', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$self->add_control(
			'sp_countdown_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .product-coundown-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'sp_countdown_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .product-countdown-container' => 'color: {{VALUE}};',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sp_countdown_typo',
				'selector' => '.elementor-element-{{ID}} .product-countdown-container',
			)
		);

		$self->add_control(
			'style_cart_button',
			array(
				'label'     => esc_html__( 'Add To Cart Button', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$self->start_controls_tabs( 'sp_cart_tabs' );

			$self->start_controls_tab(
				'sp_cart_btn_tab',
				array(
					'label' => esc_html__( 'Default', 'alpha-core' ),
				)
			);

				$self->add_control(
					'sp_cart_btn_bg',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							// Stronger selector to avoid section style from overwriting
							'.elementor-element-{{ID}} .single_add_to_cart_button' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'sp_cart_btn_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .single_add_to_cart_button' => 'color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'sp_cart_btn_tab_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

				$self->add_control(
					'sp_cart_btn_bg_hover',
					array(
						'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							// Stronger selector to avoid section style from overwriting
							'.elementor-element-{{ID}} .single_add_to_cart_button:not(.disabled):hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'sp_cart_btn_color_hover',
					array(
						'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .single_add_to_cart_button:hover' => 'color: {{VALUE}};',
						),
					)
				);

			$self->end_controls_tab();

		$self->end_controls_tabs();

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'sp_cart_btn_typo',
				'separator' => 'before',
				'selector'  => '.elementor-element-{{ID}} .single_add_to_cart_button',
			)
		);

	$self->end_controls_section();
}
/**
 * Register elementor product type controls
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_elementor_product_type_controls' ) ) {
	function alpha_elementor_product_type_controls( $self ) {

		$self->start_controls_section(
			'section_product_type',
			array(
				'label' => esc_html__( 'Product Type', 'alpha-core' ),
			)
		);

			$self->add_control(
				'follow_theme_option',
				array(
					'label'   => esc_html__( 'Follow Theme Option', 'alpha-core' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);

			$self->add_control(
				'product_type',
				array(
					'label'     => esc_html__( 'Product Type', 'alpha-core' ),
					'type'      => Alpha_Controls_Manager::IMAGE_CHOOSE,
					'default'   => '',
					'options'   => apply_filters(
						'alpha_product_loop_types',
						array(),
						'elementor'
					),
					'width'     => 1,
					'condition' => array(
						'follow_theme_option' => '',
					),
				)
			);

			$self->add_control(
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
					),
				)
			);

			$self->add_control(
				'show_labels',
				array(
					'type'     => Controls_Manager::SELECT2,
					'label'    => esc_html__( 'Show Labels', 'alpha-core' ),
					'multiple' => true,
					'default'  => array(
						'hot',
						'sale',
						'new',
						'stock',
					),
					'options'  => array(
						'hot'   => esc_html__( 'Hot', 'alpha-core' ),
						'sale'  => esc_html__( 'Sale', 'alpha-core' ),
						'new'   => esc_html__( 'New', 'alpha-core' ),
						'stock' => esc_html__( 'Stock', 'alpha-core' ),
					),
				)
			);

		$self->end_controls_section();
	}
}

/**
 * Register elementor product style controls
 *
 * @since 4.0
 */
function alpha_elementor_product_style_controls( $self ) {

	$self->start_controls_section(
		'section_filter_style',
		array(
			'label'     => esc_html__( 'Category Filter', 'alpha-core' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'filter_cat' => 'yes',
			),
		)
	);

		$self->add_responsive_control(
			'filter_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem', '%' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .product-filters' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.elementor-element-{{ID}} .portfolio-filters' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'filter_item_margin',
			array(
				'label'      => esc_html__( 'Item Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'.elementor-element-{{ID}} .nav-filters > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'filter_item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem', '%' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'cat_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'em',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'cat_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'em',
				),
				'separator'  => 'after',
				'selectors'  => array(
					'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$self->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography',
				'selector' => '.elementor-element-{{ID}} .nav-filters .nav-filter',
			)
		);

		$self->add_responsive_control(
			'cat_align',
			array(
				'label'     => esc_html__( 'Align', 'alpha-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'selectors' => array(
					'.elementor-element-{{ID}} .product-filters' => 'justify-content: {{VALUE}};',
					'.elementor-element-{{ID}} .portfolio-filters' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$self->start_controls_tabs( 'tabs_cat_color' );
			$self->start_controls_tab(
				'tab_cat_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$self->add_control(
				'cat_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'cat_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'cat_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'tab_cat_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$self->add_control(
				'cat_hover_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'cat_hover_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'cat_hover_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'tab_cat_active',
				array(
					'label' => esc_html__( 'Active', 'alpha-core' ),
				)
			);

			$self->add_control(
				'cat_active_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter.active' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'cat_active_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter.active' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'cat_active_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter.active' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->end_controls_tab();
		$self->end_controls_tabs();

	$self->end_controls_section();
}
