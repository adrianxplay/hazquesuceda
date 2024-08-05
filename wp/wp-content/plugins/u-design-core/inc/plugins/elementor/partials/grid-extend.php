<?php
defined( 'ABSPATH' ) || die;

/**
 * Grid Functions
 * Load More Functions
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.5
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

		$self->add_control(
			'col_cnt_xl',
			array(
				'label'       => esc_html__( 'Columns ( >= 1200px )', 'alpha-core' ),
				'description' => esc_html__( 'Select number of columns to display on large display( >= 1200px ). ', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
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
				'condition'   => array(
					$condition_key => array( 'slider', 'grid', 'masonry' ),
				),
			)
		);

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

		$self->add_control(
			'col_cnt_min',
			array(
				'label'       => esc_html__( 'Columns ( < 576px )', 'alpha-core' ),
				'description' => esc_html__( 'Select number of columns to display on mobile( < 576px ). ', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
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
				'condition'   => array(
					$condition_key => array( 'slider', 'grid', 'masonry' ),
				),
			)
		);

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
				base = cols.lg ? parseInt(cols.lg) : ( cols.xl ? parseInt(cols.xl) : 4 );

			if ( 6 < base ) {
				if (!cols.lg) {
					result = {
						xl: base,
						lg: 6,
						md: 4,
						sm: 3,
						min: 2,
					};
				} else {
					result = {
						lg: base,
						md: 6,
						sm: 4,
						min: 3
					};
				}
			} else if ( 4 < base ) {
				result = {
					lg: base,
					md: 4,
					sm: 3,
					min: 2,
				};

				if (!cols.xl) {
					result['xl'] = base;
					result['lg'] = 4;
				}
			} else if ( 2 < base ) {
				result = {
					lg: base,
					md: 3,
					sm: 2,
					min: 2,
				};
			} else {
				result = {
					lg: base,
					md: base,
					sm: 1,
					min: 1,
				};
			}

			for ( var w in cols ) {
				cols[w] > 0 && ( result[w] = cols[w] );
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
			var col_cnt = {
				xl: settings[key + 'xl'] ? settings[key + 'xl'] : 0,
				lg: settings[key] ? settings[key] : 0,
			}
			col_cnt.xl = settings[key + 'xl'] ? settings[key + 'xl'] : 0;
			col_cnt.lg = settings[key] ? settings[key] : 0;
			col_cnt.md = settings[key + 'tablet'] ? settings[key + 'tablet'] : 0;
			col_cnt.sm = settings[key + 'mobile'] ? settings[key + 'mobile'] : 0;
			col_cnt.min = settings[key + 'min'] ? settings[key + 'min'] : 0;

			return alpha_get_responsive_cols( col_cnt );
		}
		<?php
	}
}
