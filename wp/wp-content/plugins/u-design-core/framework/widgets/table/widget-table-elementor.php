<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Table Widget
 *
 * Alpha Widget to display table.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

class Alpha_Table_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Get a name of widget
	 *
	 * @since 1.0.0
	 */
	public function get_name() {
		return ALPHA_NAME . '_widget_table';
	}


	/**
	 * Get a title of widget
	 *
	 * @since 1.0.0
	 */
	public function get_title() {
		return esc_html__( 'Table', 'alpha-core' );
	}


	/**
	 * Get an icon of widget
	 *
	 * @since 1.0.0
	 */
	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-table';
	}


	/**
	 * Get categories of widget
	 *
	 * @since 1.0.0
	 */
	public function get_categories() {
		return array( 'alpha_widget' );
	}


	/**
	 * Get keywords of widget
	 *
	 * @since 1.0.0
	 */
	public function get_keywords() {
		return array( 'table' );
	}


	/**
	 * Get the style depends
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-table', alpha_core_framework_uri( '/widgets/table/table' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-table' );
	}


	/**
	 * Register Controls of widget
	 *
	 * @since 1.2.0
	 */
	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_table_header',
			array(
				'label' => esc_html__( 'Table Header', 'alpha-core' ),
			)
		);

			$tbl_header_repeater = new Repeater();

			$tbl_header_repeater->start_controls_tabs( 'table_header_tabs' );

				// Start of Content Tab
				$tbl_header_repeater->start_controls_tab(
					'table_header_content_tab',
					array(
						'label' => esc_html__( 'Content', 'alpha-core' ),
					)
				);
					$tbl_header_repeater->add_control(
						'table_header_cell_text',
						array(
							'label'       => esc_html__( 'Cell Text', 'alpha-core' ),
							'label_block' => true,
							'type'        => Controls_Manager::TEXT,
							'description' => esc_html__( 'Set text to be shown in the cell of table header.', 'alpha-core' ),
							'dynamic'     => array(
								'active' => true,
							),
						)
					);

					$tbl_header_repeater->add_control(
						'table_header_cell_show_icon',
						array(
							'label'       => esc_html__( 'Show Icon', 'alpha-core' ),
							'description' => esc_html__( 'Show icon in the cell of table header.​', 'alpha-core' ),
							'type'        => Controls_Manager::SWITCHER,
						)
					);

					$tbl_header_repeater->add_control(
						'table_header_cell_icon',
						array(
							'label'                  => esc_html__( 'Icon', 'alpha-core' ),
							'description'            => esc_html__( 'Set icon to be show in the cell of table header.​', 'alpha-core' ),
							'type'                   => Controls_Manager::ICONS,
							'skin'                   => 'inline',
							'exclude_inline_options' => array( 'svg' ),
							'label_block'            => false,
							'condition'              => array(
								'table_header_cell_show_icon' => 'yes',
							),
						)
					);

					$tbl_header_repeater->add_control(
						'table_header_cell_icon_position',
						array(
							'label'       => esc_html__( 'Icon Position', 'alpha-core' ),
							'description' => esc_html__( 'Controls poistion of icon in header cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::SELECT,
							'default'     => 'before',
							'options'     => array(
								'before' => esc_html__( 'Before', 'alpha-core' ),
								'after'  => esc_html__( 'After', 'alpha-core' ),
							),
							'condition'   => array(
								'table_header_cell_show_icon' => 'yes',
							),
						)
					);
				$tbl_header_repeater->end_controls_tab();

				// Start of Advanced Tab
				$tbl_header_repeater->start_controls_tab(
					'table_header_advanced_tab',
					array(
						'label' => esc_html__( 'Advanced', 'alpha-core' ),
					)
				);

					$tbl_header_repeater->add_control(
						'table_header_col_span',
						array(
							'label'       => esc_html__( 'Column Span', 'alpha-core' ),
							'description' => esc_html__( 'Set colum width for this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::NUMBER,
							'min'         => 1,
							'step'        => 1,
						)
					);

					$tbl_header_repeater->add_responsive_control(
						'table_header_col_width',
						array(
							'label'       => esc_html__( 'Column Width', 'alpha-core' ),
							'description' => esc_html__( 'Set colum width of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::SLIDER,
							'size_units'  => array( 'px', '%' ),
							'range'       => array(
								'px' => array(
									'min' => 0,
									'max' => 1000,
								),
							),
							'selectors'   => array(
								'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
							),
						)
					);

				$tbl_header_repeater->end_controls_tab();

				// Start of Style Tab
				$tbl_header_repeater->start_controls_tab(
					'table_header_style_tab',
					array(
						'label' => esc_html__( 'Style', 'alpha-core' ),
					)
				);

					$tbl_header_repeater->add_control(
						'table_header_cell_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Set color of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
							),
						)
					);

					$tbl_header_repeater->add_control(
						'table_header_cell_bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Set background color of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
							),
						)
					);

				$tbl_header_repeater->end_controls_tab();

			$tbl_header_repeater->end_controls_tabs();

			$this->add_control(
				'table_header',
				array(
					'type'        => Controls_Manager::REPEATER,
					'label'       => esc_html__( 'Header Content', 'alpha-core' ),
					'fields'      => $tbl_header_repeater->get_controls(),
					'default'     => array(
						array(
							'table_header_cell_text' => esc_html__( 'Heading 1', 'alpha-core' ),
						),
						array(
							'table_header_cell_text' => esc_html__( 'Heading 2', 'alpha-core' ),
						),
						array(
							'table_header_cell_text' => esc_html__( 'Heading 3', 'alpha-core' ),
						),
					),
					'title_field' => esc_html__( 'Column: ', 'alpha-core' ) . '{{ table_header_cell_text }}',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_table_body',
			array(
				'label' => esc_html__( 'Table Body', 'alpha-core' ),
			)
		);

			$tbl_body_repeater = new Repeater();

			$tbl_body_repeater->add_control(
				'table_body_action',
				array(
					'label'       => esc_html__( 'Action', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'col',
					'options'     => array(
						'row' => esc_html__( 'Add a New Row', 'alpha-core' ),
						'col' => esc_html__( 'Add a New Col', 'alpha-core' ),
					),
					'description' => esc_html__( 'Add a new row or column to table.', 'alpha-core' ),
				)
			);

			$tbl_body_repeater->add_control(
				'table_body_custom_row_style',
				array(
					'label'       => esc_html__( 'Custom Style?', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => '',
					'description' => esc_html__( 'Add custom style to the row of table.', 'alpha-core' ),
					'condition'   => array(
						'table_body_action' => 'row',
					),
				)
			);

			$tbl_body_repeater->add_control(
				'table_body_row_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'description' => esc_html__( 'Add custom color to the selected row.', 'alpha-core' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} {{CURRENT_ITEM}} .table-body-cell' => 'color: {{VALUE}};',
					),
					'condition'   => array(
						'table_body_action'            => 'row',
						'table_body_custom_row_style!' => '',
					),
				)
			);

			$tbl_body_repeater->add_control(
				'table_body_row_bg_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'description' => esc_html__( 'Set custom background color of the selected row.', 'alpha-core' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} {{CURRENT_ITEM}} .table-body-cell' => 'background-color: {{VALUE}};',
					),
					'condition'   => array(
						'table_body_action'            => 'row',
						'table_body_custom_row_style!' => '',
					),
				)
			);

			$tbl_body_repeater->start_controls_tabs(
				'table_body_tabs',
				array(
					'condition' => array(
						'table_body_action' => 'col',
					),
				)
			);

				// Content Tab
				$tbl_body_repeater->start_controls_tab(
					'table_body_content_tab',
					array(
						'label' => esc_html__( 'Content', 'alpha-core' ),
					)
				);
					$tbl_body_repeater->add_control(
						'table_body_cell_text',
						array(
							'label'       => esc_html__( 'Cell Text', 'alpha-core' ),
							'label_block' => true,
							'description' => esc_html__( 'Set text of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::TEXT,
							'dynamic'     => array(
								'active' => true,
							),
						)
					);
					$tbl_body_repeater->add_control(
						'table_body_cell_link',
						array(
							'label'       => esc_html__( 'Cell Link', 'alpha-core' ),
							'description' => esc_html__( 'Give link to this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::URL,
							'placeholder' => esc_html__( 'https://your-link.com', 'alpha-core' ),
						)
					);
				$tbl_body_repeater->end_controls_tab();

				// Advanced Tab
				$tbl_body_repeater->start_controls_tab(
					'table_body_advanced_tab',
					array(
						'label' => esc_html__( 'Advanced', 'alpha-core' ),
					)
				);
					$tbl_body_repeater->add_control(
						'table_body_col_span',
						array(
							'label'       => esc_html__( 'Col Span', 'alpha-core' ),
							'description' => esc_html__( 'Set column span of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::NUMBER,
							'min'         => 1,
							'step'        => 1,
						)
					);
					$tbl_body_repeater->add_control(
						'table_body_row_span',
						array(
							'label'       => esc_html__( 'Row Span', 'alpha-core' ),
							'description' => esc_html__( 'Set row span of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::NUMBER,
							'min'         => 1,
							'step'        => 1,
						)
					);
					$tbl_body_repeater->add_control(
						'table_body_col_is_th',
						array(
							'label'       => esc_html__( 'This cell is Table Heading?', 'alpha-core' ),
							'description' => esc_html__( 'Enables you to use this cell as a table heading.​', 'alpha-core' ),
							'type'        => Controls_Manager::SWITCHER,
							'condition'   => array(
								'table_body_action' => 'col',
							),
						)
					);
				$tbl_body_repeater->end_controls_tab();

				// Style Tab
				$tbl_body_repeater->start_controls_tab(
					'table_body_style_tab',
					array(
						'label' => esc_html__( 'Style', 'alpha-core' ),
					)
				);
					$tbl_body_repeater->add_control(
						'cell_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Set color of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .widget-table {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .widget-table {{CURRENT_ITEM}} a' => 'color: {{VALUE}};',
							),
						)
					);

					$tbl_body_repeater->add_control(
						'cell_bg_color',
						array(
							'label'       => esc_html__( 'Background color', 'alpha-core' ),
							'description' => esc_html__( 'Set background color of this cell.​', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
							),
						)
					);
				$tbl_body_repeater->end_controls_tab();

			$tbl_body_repeater->end_controls_tabs();

			$this->add_control(
				'table_body',
				array(
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $tbl_body_repeater->get_controls(),
					'label'       => esc_html__( 'Body Content', 'alpha-core' ),
					'default'     => array(
						array(
							'table_body_action' => 'row',
						),
						array(
							'table_body_action'    => 'col',
							'table_body_cell_text' => esc_html__( 'Body Cell', 'alpha-core' ),
						),
						array(
							'table_body_action'    => 'col',
							'table_body_cell_text' => esc_html__( 'Body Cell', 'alpha-core' ),
						),
						array(
							'table_body_action'    => 'col',
							'table_body_cell_text' => esc_html__( 'Body Cell', 'alpha-core' ),
						),
					),
					'title_field' => '{{ table_body_action === "row" ? "' . esc_html__( 'Start Row:', 'alpha-core' ) . '" : "' . esc_html__( 'Cell:', 'alpha-core' ) . ' " + table_body_cell_text }}',
				)
			);

		$this->end_controls_section();

		// Style tab

		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_responsive_control(
				'table_width',
				array(
					'label'      => esc_html__( 'Table Width', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 320,
							'max'  => 1920,
						),
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .widget-table' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();
		// Table Header Style
		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => esc_html__( 'Table Header', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'table_head_typography',
					'selector' => '.elementor-element-{{ID}} .widget-table .table-header-cell',
				)
			);

			$this->add_control(
				'table_head_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .table-head' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'table_head_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .table-head' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'table_header_cell_icon_style',
				array(
					'label' => esc_html__( 'Icon', 'alpha-core' ),
					'type'  => Controls_Manager::POPOVER_TOGGLE,
				)
			);

			$this->start_popover();

				$this->add_control(
					'table_header_cell_icon_size',
					array(
						'label'      => esc_html__( 'Font Size', 'alpha-core' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array(
							'px',
							'em',
							'rem',
						),
						'range'      => array(
							'px' => array(
								'min' => 1,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'.elementor-element-{{ID}} .table-header-cell i' => 'font-size: {{SIZE}}{{UNIT}};',
						),
						'condition'  => array(
							'table_header_cell_icon_style' => 'yes',
						),
					)
				);

				$this->add_control(
					'table_header_cell_icon_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .table-header-cell i' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'table_header_cell_icon_style' => 'yes',
						),
					)
				);

				$this->add_control(
					'table_header_cell_icon_space',
					array(
						'label'       => esc_html__( 'Spacing', 'alpha-core' ),
						'description' => esc_html__( 'Set spacing of icon.​', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min' => 1,
								'max' => 50,
							),
						),
						'selectors'   => array(
							'body .elementor-element-{{ID}} .icon-position-before:not(:only-child)' => "margin-{$right}: {{SIZE}}{{UNIT}};",
							'body .elementor-element-{{ID}} .icon-position-after:not(:only-child)' => "margin-{$left}: {{SIZE}}{{UNIT}};",
						),
						'condition'   => array(
							'table_header_cell_icon_style' => 'yes',
						),
					)
				);

			$this->end_popover();

			$this->add_responsive_control(
				'table_header_cell_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .table-header-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'  => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'table_header_cell_border',
					'selector' => '.elementor-element-{{ID}} .table-header-cell',
				)
			);

			$this->add_responsive_control(
				'table_header_cell_align',
				array(
					'label'                => esc_html__( 'Alignment', 'alpha-core' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => array(
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
					'selectors_dictionary' => array(
						$left    => 'justify-content: flex-start;',
						'center' => 'justify-content: center;',
						$right   => 'justify-content: flex-end;',
					),
					'selectors'            => array(
						'.elementor-element-{{ID}} .table-header-cell .table-cell-inner' => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				'table_header_cell_vert_align',
				array(
					'label'     => esc_html__( 'Vertical Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'top'    => array(
							'title' => esc_html__( 'Top', 'alpha-core' ),
							'icon'  => 'eicon-v-align-top',
						),
						'middle' => array(
							'title' => esc_html__( 'Middle', 'alpha-core' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),

					'selectors' => array(
						'.elementor-element-{{ID}} .table-header-cell' => 'vertical-align: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();

		// Table Body Style
		$this->start_controls_section(
			'section_table_body_style',
			array(
				'label' => esc_html__( 'Table Body', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'table_body_typography',
					'selector' => '.elementor-element-{{ID}} .widget-table .table-body-cell',
				)
			);

			$this->start_controls_tabs( 'table_body_tabs' );

				$this->start_controls_tab(
					'table_body_normal_tab',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'table_body_row_color',
						array(
							'label'       => esc_html__( 'Row Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set color of each row globally.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .widget-table .table-body-cell' => 'color:{{VALUE}};',
							),
						)
					);

					$this->add_control(
						'table_body_row_bg_color',
						array(
							'label'       => esc_html__( 'Row Background Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set background color of each row globally.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .widget-table .table-body-cell' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'table_body_striped_row',
						array(
							'label'       => esc_html__( 'Stripped Row', 'alpha-core' ),
							'description' => esc_html__( 'Enables you to make this table a stripped one.​', 'alpha-core' ),
							'type'        => Controls_Manager::SWITCHER,
						)
					);

					$this->add_control(
						'table_body_even_row_color',
						array(
							'label'       => esc_html__( 'Even Row Color', 'alpha-core' ),
							'description' => esc_html__( 'Set color of even row globally.​', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-row:nth-child(even) .table-body-cell' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'table_body_striped_row' => 'yes',
							),
						)
					);

					$this->add_control(
						'table_body_even_row_bg_color',
						array(
							'label'       => esc_html__( 'Even Row Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Set background color of even row globally.​', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-row:nth-child(even) .table-body-cell' => 'background-color: {{VALUE}};',
							),
							'condition'   => array(
								'table_body_striped_row' => 'yes',
							),
						)
					);

					$this->add_control(
						'table_body_link_color',
						array(
							'label'       => esc_html__( 'Link Color', 'alpha-core' ),
							'description' => esc_html__( 'Set color of link globally.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-cell a' => 'color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'table_body_hover_tab',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

					$this->add_control(
						'table_body_row_hover_color',
						array(
							'label'       => esc_html__( 'Row Hover Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set hover color of each row globally.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-row:hover .table-body-cell' => 'color:{{VALUE}};',
							),
						)
					);

					$this->add_control(
						'table_body_row_hover_bg_color',
						array(
							'label'       => esc_html__( 'Row Background Hover Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set background hover color of each row globally.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-row:hover .table-body-cell' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'table_body_even_row_hover_color',
						array(
							'label'       => esc_html__( 'Even Row Hover Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Set hover color of even row globally.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-row:nth-child(even):hover .table-body-cell' => 'color: {{VALUE}};',
							),
							'condition'   => array(
								'table_body_striped_row' => 'yes',
							),
						)
					);

					$this->add_control(
						'table_body_even_row_hover_bg_color',
						array(
							'label'       => esc_html__( 'Even Row Bg Hover Color', 'alpha-core' ),
							'description' => esc_html__( 'Set background hover color of even row globally.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-row:nth-child(even):hover .table-body-cell' => 'background-color: {{VALUE}};',
							),
							'condition'   => array(
								'table_body_striped_row' => 'yes',
							),
						)
					);

					$this->add_control(
						'table_body_link_hover_color',
						array(
							'label'       => esc_html__( 'Link Hover Color', 'alpha-core' ),
							'description' => esc_html__( 'Set hover color of link globally.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .table-body-cell a:hover' => 'color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'table_body_cell_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Set padding for body cell.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .table-body .table-body-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'   => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'table_body_cell_border',
					'selector' => '.elementor-element-{{ID}} .widget-table .table-body-cell',
				)
			);

			$this->add_responsive_control(
				'table_body_cell_align',
				array(
					'label'                => esc_html__( 'Alignment', 'alpha-core' ),
					'type'                 => Controls_Manager::CHOOSE,
					'options'              => array(
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
					'selectors_dictionary' => array(
						$left    => 'justify-content: flex-start;',
						'center' => 'justify-content: center;',
						$right   => 'justify-content: flex-end;',
					),
					'selectors'            => array(
						'.elementor-element-{{ID}} .table-body-cell .table-cell-inner' => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				'table_body_cell_vert_align',
				array(
					'label'     => esc_html__( 'Vertical Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'top'    => array(
							'title' => esc_html__( 'Top', 'alpha-core' ),
							'icon'  => 'eicon-v-align-top',
						),
						'middle' => array(
							'title' => esc_html__( 'Middle', 'alpha-core' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),

					'selectors' => array(
						'.elementor-element-{{ID}} .widget-table .table-body-cell' => 'vertical-align: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Render widget
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;

		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/table/render-table-elementor.php' );
	}


	/**
	 * Add wrapper class before rendering
	 *
	 * @since 1.0.0
	 */
	public function before_render() {
		$atts = $this->get_settings_for_display();
		?>
		<div <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
		<?php
	}

	/**
	 * Content Template
	 *
	 * @since 1.0.0
	 */
	protected function content_template() {
		?>

		<#

		/**
		* Render table cell
		*
		* @since 1.0.0
		*/
		function alpha_get_table_cell( data = array(), context = 'head' ) {
			let html = '',
				is_first_row = true;

			if ( 'head' === context ) {
				html += '<tr class="table-head-row">';
			}
			_.each( data, function( item, index ) {
				if ( item['table_body_action'] && 'row' == item['table_body_action'] ) {
					// Render row html
					if ( is_first_row ) {
						html        += '<tr class="table-body-row elementor-repeater-item-' + item['_id'] + '">';
						is_first_row = false;
					} else {
						html += '</tr><tr class="table-body-row elementor-repeater-item-' + item['_id'] + '">';
					}
				} else {
					// Render cell html
					let additional_content = '',
						show_icon          =  item['table_header_cell_show_icon'] ? item['table_header_cell_show_icon'] : '',
						position           =  item['table_header_cell_icon_position'] ? item['table_header_cell_icon_position'] : 'before',
						icon               =  item['table_header_cell_icon'] ? '<i class="' + item['table_header_cell_icon']['value'] + '"></i>' : '',
						th_colspan         = item['table_header_col_span'] ? 'colspan="' + item['table_header_col_span'] + '"' : '',
						tb_colspan         = item['table_body_col_span'] ? 'colspan="' + item['table_body_col_span'] + '"' : '',
						tb_rowspan         = item['table_body_row_span'] ? 'rowspan="' + item['table_body_row_span'] + '"' : '';

					if ( show_icon ) {
						additional_content = '<span class="table-header-cell-icon icon-position-' + position + '">' + icon + '</span>';
					}

					if ( 'head' == context ) {
						html += '<th class="table-header-cell elementor-repeater-item-' + item['_id'] + '"' + th_colspan + '><div class="table-cell-inner">';
						html += additional_content;
						html += '<span class="table-header-cell-text">' + item['table_header_cell_text'] + '</span>';
						html += '</div></th>';
					} else {
						content_html = '';
						if ( item['table_body_cell_link'] && '' != item['table_body_cell_link']['url'] ) {
							content_html = '<a href="' + item['table_body_cell_link']['url'] + '"' + ( item['table_body_cell_link']['is_external'] ? ' target="_blank"' : '' ) + ( item['table_body_cell_link']['nofollow'] ? ' rel="nofollow"' : '' ) + '>' + item['table_body_cell_text'] + '</a>';
						} else {
							content_html = '<span class="table-body-cell-text">' + item['table_body_cell_text'] + '</span>';
						}

						if ( 'yes' == item['table_body_col_is_th'] ) {
							html += '<th class="table-body-cell elementor-repeater-item-' + item['_id'] + '"' + tb_colspan + ' ' + tb_rowspan + '>';
						} else {
							html += '<td class="table-body-cell elementor-repeater-item-' + item['_id'] + '"' + tb_colspan + ' ' + tb_rowspan + '>';
						}

						html += '<div class="table-cell-inner">';
						html += content_html;
						html += '</div>';

						if ( 'yes' == item['table_body_col_is_th'] ) {
							html += '</th>';
						} else {
							html += '</td>';
						}
					}
				}
			} );

			html += '</tr>';

			return html;
		}

		#>

		<div class="widget-table-wrapper">
			<table class="widget-table">
				<thead class="table-head"><# print( alpha_get_table_cell( settings.table_header, 'head' ) ); #></thead>
				<tbody class="table-body"><# print( alpha_get_table_cell( settings.table_body, 'body' ) ); #></tbody>
			</table>
		</div>

		<?php
	}
}
