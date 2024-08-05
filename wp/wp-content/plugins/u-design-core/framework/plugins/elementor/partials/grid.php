<?php
defined( 'ABSPATH' ) || die;

/**
 * Grid Functions
 * Load More Functions
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;

/**
 * Register elementor layout controls for grid.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_grid_layout_controls' ) ) {
	function alpha_elementor_grid_layout_controls( $self, $condition_key, $creative = false, $widget = '', $default_cols = '' ) {

		// $self->add_control(
		// 	'col_cnt_xl',
		// 	array(
		// 		'label'       => esc_html__( 'Columns ( >= 1200px )', 'alpha-core' ),
		// 		'description' => esc_html__( 'Select number of columns to display on large display( >= 1200px ). ', 'alpha-core' ),
		// 		'label_block' => true,
		// 		'type'        => Controls_Manager::SELECT,
		// 		'options'     => array(
		// 			'1' => 1,
		// 			'2' => 2,
		// 			'3' => 3,
		// 			'4' => 4,
		// 			'5' => 5,
		// 			'6' => 6,
		// 			'7' => 7,
		// 			'8' => 8,
		// 			''  => esc_html__( 'Default', 'alpha-core' ),
		// 		),
		// 		'condition'   => array(
		// 			$condition_key => array( 'slider', 'grid', 'masonry' ),
		// 		),
		// 	)
		// );

		$self->add_responsive_control(
			'col_cnt',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Columns', 'alpha-core' ),
				'description' => esc_html__( 'Select number of columns to display.', 'alpha-core' ),
				'label_block' => true,
				'options'     => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
					'6' => 6,
					'7' => 7,
					'8' => 8,
					''  => esc_html__( 'Default', 'alpha-core' ),
				),
				'default'     => $default_cols,
				'condition'   => array(
					$condition_key => array( 'slider', 'grid', 'masonry' ),
				),
			)
		);

		// $self->add_control(
		// 	'col_cnt_min',
		// 	array(
		// 		'label'       => esc_html__( 'Columns ( < 576px )', 'alpha-core' ),
		// 		'description' => esc_html__( 'Select number of columns to display on mobile( < 576px ). ', 'alpha-core' ),
		// 		'label_block' => true,
		// 		'type'        => Controls_Manager::SELECT,
		// 		'options'     => array(
		// 			'1' => 1,
		// 			'2' => 2,
		// 			'3' => 3,
		// 			'4' => 4,
		// 			'5' => 5,
		// 			'6' => 6,
		// 			'7' => 7,
		// 			'8' => 8,
		// 			''  => esc_html__( 'Default', 'alpha-core' ),
		// 		),
		// 		'condition'   => array(
		// 			$condition_key => array( 'slider', 'grid', 'masonry' ),
		// 		),
		// 	)
		// );

		if ( $creative ) {
			$self->add_responsive_control(
				'creative_cols',
				array(
					'type'           => Controls_Manager::SLIDER,
					'label'          => esc_html__( 'Columns', 'alpha-core' ),
					'description'    => esc_html__( 'Select number of columns to display.', 'alpha-core' ),
					'default'        => array(
						'size' => 4,
						'unit' => 'px',
					),
					'tablet_default' => array(
						'size' => 3,
						'unit' => 'px',
					),
					'mobile_default' => array(
						'size' => 2,
						'unit' => 'px',
					),
					'size_units'     => array(
						'px',
					),
					'range'          => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 60,
						),
					),
					'condition'      => array(
						$condition_key => 'creative',
					),
					'selectors'      => array(
						'.elementor-element-{{ID}} .creative-grid' => 'grid-template-columns: repeat(auto-fill, calc(100% / {{SIZE}}))',
					),
				)
			);
		}

		if ( 'product' == $widget || 'has_rows' == $widget ) {
			$self->add_control(
				'row_cnt',
				array(
					'label'       => esc_html__( 'Rows', 'alpha-core' ),
					'description' => esc_html__( 'Select number of rows to display.', 'alpha-core' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'1' => 1,
						'2' => 2,
						'3' => 3,
						'4' => 4,
						'5' => 5,
						'6' => 6,
					),
					'default'     => 1,
					'condition'   => array(
						$condition_key => array( 'slider' ),
					),
				)
			);
		}

		$self->add_control(
			'box_shadow_slider',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Prevent Box Shadow Clip', 'alpha-core' ),
				'description' => esc_html__( 'It should be enabled in slider, if use box shadow.', 'alpha-core' ),
				'condition'   => array(
					$condition_key => array( 'slider' ),
				),
			)
		);

		$self->add_control(
			'col_sp',
			array(
				'label'       => esc_html__( 'Columns Spacing', 'alpha-core' ),
				'description' => esc_html__( 'Select the size of spacing between items.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => apply_filters(
					'alpha_col_sp',
					array(
						'no' => array(
							'title' => esc_html__( 'No space', 'alpha-core' ),
							'icon'  => 'eicon-ban',
						),
						'xs' => array(
							'title' => esc_html__( 'Extra Small', 'alpha-core' ),
							'icon'  => 'alpha-size-xs alpha-choose-type',
						),
						'sm' => array(
							'title' => esc_html__( 'Small', 'alpha-core' ),
							'icon'  => 'alpha-size-sm alpha-choose-type',
						),
						'md' => array(
							'title' => esc_html__( 'Medium', 'alpha-core' ),
							'icon'  => 'alpha-size-md alpha-choose-type',
						),
						'lg' => array(
							'title' => esc_html__( 'Large', 'alpha-core' ),
							'icon'  => 'alpha-size-lg alpha-choose-type',
						),
					),
					'elementor'
				),
			)
		);

		$self->add_responsive_control(
			'col_sp_custom',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Custom Spacing', 'alpha-core' ),
				'description' => esc_html__( 'Controls the size of spacing between items.', 'alpha-core' ),
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
					'.elementor-element-{{ID}} .row' => '--alpha-gap: calc({{SIZE}}{{UNIT}} / 2);',
				),
				'condition'   => array(
					'col_sp' => '',
				),
			)
		);

		if ( $creative ) {
			/**
			 * Using Display Grid Css
			 */
			$repeater = new Repeater();

			$repeater->add_control(
				'item_no',
				array(
					'label'       => esc_html__( 'Item Index', 'alpha-core' ),
					'description' => esc_html__( 'Point out the specific item with following options.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => esc_html__( 'Blank for all items.', 'alpha-core' ),
				)
			);

			$repeater->add_responsive_control(
				'item_col_span',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Column Size', 'alpha-core' ),
					'description' => esc_html__( 'Controls the column size of selected item.', 'alpha-core' ),
					'default'     => array(
						'size' => 1,
						'unit' => 'px',
					),
					'size_units'  => array(
						'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 12,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'grid-column-end: span {{SIZE}}',
					),
				)
			);

			$repeater->add_responsive_control(
				'item_row_span',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Row Size', 'alpha-core' ),
					'description' => esc_html__( 'Controls the row size of selected item.', 'alpha-core' ),
					'default'     => array(
						'size' => 1,
						'unit' => 'px',
					),
					'size_units'  => array(
						'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 8,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'grid-row-end: span {{SIZE}}',
					),
				)
			);

			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'item_thumb', // Usage: `{name}_size` and `{name}_custom_dimension`
					'exclude'   => [ 'custom' ],
					'label'     => esc_html__( 'Image Size', 'alpha-core' ),
					'default'   => 'woocommerce_single',
					'condition' => array(
						'item_no!' => '',
					),
				)
			);

			if ( 'product' == $widget ) {
				$repeater->add_control(
					'product_type',
					array(
						'label'       => esc_html__( 'Product Type', 'alpha-core' ),
						'description' => esc_html__( 'Choose from product type which provides in theme.', 'alpha-core' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => '',
						'options'     => apply_filters(
							'alpha_product_loop_creative_types',
							array(
								''                => esc_html__( 'Default', 'alpha-core' ),
								'product-default' => esc_html__( 'Type 1', 'alpha-core' ),
							),
							'elementor'
						),
						'condition'   => array(
							'item_no!' => '',
						),
					)
				);
			}

			$self->add_control(
				'creative_layout_heading',
				array(
					'label'     => __( "Customize each grid item's layout", 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						$condition_key => 'creative',
					),
				)
			);

			$self->add_control(
				'items_list',
				array(
					'label'       => esc_html__( 'Grid Item Layouts', 'alpha-core' ),
					'description' => esc_html( 'Controls each grid item rows and cols in grid.', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'condition'   => array(
						$condition_key => 'creative',
					),
					'default'     => array(
						array(
							'item_no'       => '',
							'item_col_span' => array(
								'size' => 1,
								'unit' => 'px',
							),
							'item_row_span' => array(
								'size' => 1,
								'unit' => 'px',
							),
						),
						array(
							'item_no'       => 2,
							'item_col_span' => array(
								'size' => 2,
								'unit' => 'px',
							),
							'item_row_span' => array(
								'size' => 1,
								'unit' => 'px',
							),
						),
					),
					'title_field' => sprintf( '{{{ item_no ? \'%1$s\' : \'%2$s\' }}}' . '&nbsp;<strong>{{{ item_no }}}</strong>', esc_html__( 'Index', 'alpha-core' ), esc_html__( 'Base', 'alpha-core' ) ),
				)
			);

			$self->add_control(
				'creative_equal_height',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Different Row Height', 'alpha-core' ),
					'description' => esc_html__( 'Set the grid item`s height as auto.', 'alpha-core' ),
					'default'     => 'yes',
					'condition'   => array(
						$condition_key => 'creative',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .creative-grid' => 'grid-auto-rows: auto',
					),
				)
			);
		}
	}
}

if ( ! function_exists( 'alpha_elementor_grid_template' ) ) {
	function alpha_elementor_grid_template() {
		?>

		function alpha_get_responsive_cols( cols ) {
			var result = {},
				base = parseInt( typeof cols.xlg != 'undefined' ? cols.xlg : 4);

			base || (base = 4);

			if ( 6 < base ) {
				result = {
					xlg: base,
					xl: base,
					lg: 6,
					md: 4,
					sm: 3,
					min: 2
				};
			} else if ( 4 < base ) {
				result = {
					xlg: base,
					xl: base,
					lg: 4,
					md: 4,
					sm: 3,
					min: 2,
				};
			} else if ( 2 < base ) {
				result = {
					xlg: base,
					xl: base,
					lg: base,
					md: 3,
					sm: 2,
					min: 2,
				};
			} else {
				result = {
					xlg: base,
					xl: base,
					lg: base,
					md: base,
					sm: 1,
					min: 1,
				};
			}

			for ( var w in cols ) {
				cols[w] > 0 && ( result[w] = cols[w] );
			}

			return result;
		}

		function alpha_get_col_class( cols ) {
			var cls = ' row';
			for ( var w in cols ) {
				cols[w] > 0 && ( cls += ' cols-' + ( 'min' !== w ? w + '-' : '' ) + cols[w] );
			}
			return cls;
		}

		function alpha_get_grid_space_class( settings ) {
			var col_sp = settings['col_sp'];

			if ( ! col_sp ) {
				return  '';
			} else {
				return ' gutter-' + col_sp;
			}
		}

		function alpha_elementor_grid_col_cnt( settings, key = 'col_cnt' ) {
			var col_cnt = {};
			col_cnt.xxl = typeof settings[key + 'widescreen'] != 'undefined' ? settings[key + 'widescreen'] : 0;
			col_cnt.xlg = settings[key] ? settings[key] : 0;
			col_cnt.xl = typeof settings[key + 'laptop'] != 'undefined' ? settings[key + 'laptop'] : 0;
			col_cnt.lg = typeof settings[key + 'tablet_extra'] != 'undefined' ? settings[key + 'tablet_extra'] : 0;
			col_cnt.md = typeof settings[key + 'tablet'] != 'undefined' ? settings[key + 'tablet'] : 0;
			col_cnt.sm = typeof settings[key + 'mobile_extra'] != 'undefined' ? settings[key + 'mobile_extra'] : 0;
			col_cnt.min = typeof settings[key + 'mobile'] != 'undefined' ? settings[key + 'mobile'] : 0;

			return alpha_get_responsive_cols( col_cnt );
		}
		<?php
	}
}

if ( ! function_exists( 'alpha_elementor_loadmore_layout_controls' ) ) {
	function alpha_elementor_loadmore_layout_controls( $self, $condition_key ) {

		$self->add_control(
			'loadmore_type',
			array(
				'label'       => esc_html__( 'Load More', 'alpha-core' ),
				'description' => esc_html__( 'Choose load more type: By button, By Scroll.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''       => esc_html__( 'No', 'alpha-core' ),
					'button' => esc_html__( 'By button', 'alpha-core' ),
					'scroll' => esc_html__( 'By scroll', 'alpha-core' ),
				),
				'condition'   => array(
					$condition_key => array( 'grid' ),
				),
			)
		);

		$self->add_control(
			'loadmore_label',
			array(
				'label'       => esc_html__( 'Load More Label', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'Load More', 'alpha-core' ),
				'condition'   => array(
					'loadmore_type' => 'button',
					$condition_key  => array( 'grid' ),
				),
			)
		);
	}
}

if ( ! function_exists( 'alpha_elementor_loadmore_button_controls' ) ) {
	function alpha_elementor_loadmore_button_controls( $self, $condition_key, $name_prefix = '' ) {
		$self->start_controls_section(
			'section_load_more_btn_skin',
			array(
				'label'     => esc_html__( 'Load More Button', 'alpha-core' ),
				'condition' => array(
					'loadmore_type' => 'button',
					$condition_key  => array( 'grid', 'creative' ),
				),
			)
		);

		alpha_elementor_button_layout_controls( $self, $condition_key, array( 'grid', 'creative' ), $name_prefix );

		$self->end_controls_section();

		$self->start_controls_section(
			'section_load_more_btn_style',
			array(
				'label'     => esc_html__( 'Load More Button', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'loadmore_type' => 'button',
					$condition_key  => array( 'grid', 'creative' ),
				),
			)
		);

			$self->add_control(
				$name_prefix . 'button_customize_heading',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => esc_html__( 'Customize Options', 'alpha-core' ),
					'separator' => 'before',
				)
			);

			$self->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => $name_prefix . 'button_typography',
					'label'    => esc_html__( 'Label Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .btn-load',
				)
			);

			$self->add_responsive_control(
				$name_prefix . 'btn_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .btn-load' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->add_responsive_control(
				$name_prefix . 'btn_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .btn-load' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->add_responsive_control(
				$name_prefix . 'btn_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .btn-load' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					),
				)
			);

			$self->start_controls_tabs( $name_prefix . 'tabs_btn_cat' );

			$self->start_controls_tab(
				$name_prefix . 'tab_btn_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'btn_box_shadow',
					'selector' => '.elementor-element-{{ID}} .btn-load',
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				$name_prefix . 'tab_btn_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_color_hover',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_back_color_hover',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_border_color_hover',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'btn_box_shadow_hover',
					'selector' => '.elementor-element-{{ID}} .btn-load:hover',
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				$name_prefix . 'tab_btn_active',
				array(
					'label' => esc_html__( 'Active', 'alpha-core' ),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_color_active',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load:not(:focus):active, .elementor-element-{{ID}} .btn-load:focus' => 'color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_back_color_active',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load:not(:focus):active, .elementor-element-{{ID}} .btn-load:focus' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				$name_prefix . 'btn_border_color_active',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-load:not(:focus):active, .elementor-element-{{ID}} .btn-load:focus' => 'border-color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $name_prefix . 'btn_box_shadow_active',
					'selector' => '.elementor-element-{{ID}} .btn-load:active, .elementor-element-{{ID}} .btn-load:focus',
				)
			);

			$self->end_controls_tab();

			$self->end_controls_tabs();

		$self->end_controls_section();
	}
}

