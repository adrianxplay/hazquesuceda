<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Menu Widget
 *
 * Alpha Widget to display menu.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Menu_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_menu';
	}

	public function get_title() {
		return esc_html__( 'Menu', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-menu';
	}

	public function get_keywords() {
		return array( 'menu', 'alpha' );
	}

	public function get_script_depends() {
		return array();
	}


	/**
	 * Get menu items.
	 *
	 * @access public
	 *
	 * @return array Menu Items
	 */
	public function get_menu_items() {
		$menu_items = array();
		$menus      = wp_get_nav_menus();
		foreach ( $menus as $key => $item ) {
			$menu_items[ $item->term_id ] = $item->name;
		}
		return $menu_items;
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_menu',
			array(
				'label' => esc_html__( 'Menu', 'alpha-core' ),
			)
		);

			$this->add_control(
				'menu_id',
				array(
					'label'       => esc_html__( 'Select Menu', 'alpha-core' ),
					'description' => esc_html__( 'Select certain menu you want to place among menus have been created.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => $this->get_menu_items(),
				)
			);

			$this->add_control(
				'type',
				array(
					'label'       => esc_html__( 'Select Type', 'alpha-core' ),
					'description' => esc_html__( 'Select certain type you want to display among fashionable types.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					/**
					 * Filters menu widget default style.
					 *
					 * @since 1.0
					 */
					'default'     => apply_filters( 'alpha_menu_widget_default', 'horizontal' ),
					'options'     => array(
						'horizontal'  => esc_html__( 'Horizontal', 'alpha-core' ),
						'vertical'    => esc_html__( 'Vertical', 'alpha-core' ),
						'collapsible' => esc_html__( 'Vertical Collapsible', 'alpha-core' ),
						'dropdown'    => esc_html__( 'Toggle Dropdown', 'alpha-core' ),
						'flyout'      => esc_html__( 'Flyout', 'alpha-core' ),
					),
				)
			);

			$this->add_responsive_control(
				'width',
				array(
					'label'       => esc_html__( 'Width (px)', 'alpha-core' ),
					'description' => esc_html__( 'Type a number of your menu’s width.', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 300,
					'condition'   => array(
						'type!' => array( 'horizontal', 'flyout' ),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .menu, .elementor-element-{{ID}} .toggle-menu' => 'width: {{VALUE}}px;',
					),
				)
			);

			$this->add_control(
				'underline',
				array(
					'label'       => esc_html__( 'Underline on hover', 'alpha-core' ),
					'description' => esc_html__( 'Gives underline style to your menu items on hover.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'type!' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'label',
				array(
					'label'       => esc_html__( 'Toggle Label', 'alpha-core' ),
					'description' => esc_html__( 'Type a toggle label.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Browse Categories', 'alpha-core' ),
					'condition'   => array(
						'type' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'icon',
				array(
					'label'                  => esc_html__( 'Toggle Icon', 'alpha-core' ),
					'description'            => esc_html__( 'Choose a toggle icon.', 'alpha-core' ),
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
					'type'                   => Controls_Manager::ICONS,
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-category',
						'library' => 'alpha-icons',
					),
					'condition'              => array(
						'type' => array( 'dropdown', 'flyout' ),
					),
				)
			);

			$this->add_control(
				'no_bd',
				array(
					'label'       => esc_html__( 'No Border', 'alpha-core' ),
					'description' => esc_html__( 'Toggle Menu Dropdown will have no border.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'type' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'no_triangle',
				array(
					'label'       => esc_html__( 'No Triangle in Dropdown', 'alpha-core' ),
					'description' => esc_html__( 'Do not show a triangle in dropdown.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'selectors'   => array(
						'.elementor-element-{{ID}} .menu .menu-item-has-children:after, .elementor-element-{{ID}} .dropdown.toggle-menu:after, .elementor-element-{{ID}} .dropdown.toggle-menu:before' => 'content: none;',
					),
					'condition'   => array(
						'type' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'show_home',
				array(
					'label'       => esc_html__( 'Show on Homepage', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'Menu Dropdown will be shown in homepage.', 'alpha-core' ),
					'condition'   => array(
						'type' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'show_page',
				array(
					'label'       => esc_html__( 'Show on ALL Pages', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'Menu Dropdown will be shown after loading in all pages.', 'alpha-core' ),
					'condition'   => array(
						'type' => 'dropdown',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label'     => esc_html__( 'Menu Toggle', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'type' => array( 'dropdown', 'flyout' ),
				),
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'toggle_typography',
					'selector'  => '.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle',
					'condition' => array(
						'type!' => 'flyout',
					),
				)
			);

			$this->start_controls_tabs( 'toggle_color_tab' );
				$this->start_controls_tab(
					'toggle_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'toggle_color',
						array(
							'label'     => esc_html__( 'Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'toggle_back_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'toggle_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'toggle_hover',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

					$this->add_control(
						'toggle_hover_color',
						array(
							'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .toggle-menu:hover .dropdown-menu-toggle, .elementor-element-{{ID}} .toggle-menu.show .dropdown-menu-toggle, .home .elementor-section:not(.fixed) .elementor-element-{{ID}} .show-home .dropdown-menu-toggle' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'toggle_hover_back_color',
						array(
							'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .toggle-menu:hover .dropdown-menu-toggle, .elementor-element-{{ID}} .toggle-menu.show .dropdown-menu-toggle, .home .elementor-section:not(.fixed) .elementor-element-{{ID}} .show-home .dropdown-menu-toggle' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'toggle_hover_border_color',
						array(
							'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'.elementor-element-{{ID}} .toggle-menu:hover .dropdown-menu-toggle, .elementor-element-{{ID}} .toggle-menu.show .dropdown-menu-toggle, .home .elementor-section:not(.fixed) .elementor-element-{{ID}} .show-home .dropdown-menu-toggle' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_responsive_control(
				'toggle_icon',
				array(
					'label'       => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle i' => 'font-size: {{SIZE}}px;',
					),
					'qa_selector' => '.toggle-menu .dropdown-menu-toggle i',
					'separator'   => 'before',
				)
			);

			$this->add_control(
				'toggle_icon_space',
				array(
					'label'      => esc_html__( 'Icon Space (px)', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle i + span' => "margin-{$left}: {{SIZE}}px;",
					),
					'condition'  => array(
						'type!' => 'flyout',
					),
				)
			);

			$this->add_control(
				'toggle_border',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					),
				)
			);

			$this->add_control(
				'toggle_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'toggle_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', 'rem', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .toggle-menu .dropdown-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'qa_selector' => '.toggle-menu .dropdown-menu-toggle',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ancestor_style',
			array(
				'label' => esc_html__( 'Menu Ancestor', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'ancestor_typography',
					'selector' => '.elementor-element-{{ID}} .menu > li > a',
				)
			);

			$this->start_controls_tabs( 'ancestor_color_tab' );
				$this->start_controls_tab(
					'ancestor_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

				$this->add_control(
					'ancestor_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .menu > li > a' => 'color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'ancestor_back_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .menu > li > a' => 'background-color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'ancestor_border_color',
					array(
						'label'     => esc_html__( 'Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .menu > li > a' => 'border-color: {{VALUE}};',
						),
					)
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'ancestor_hover',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

				$this->add_control(
					'ancestor_hover_color',
					array(
						'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .menu > li:hover > a' => 'color: {{VALUE}};',
							'.elementor-element-{{ID}} .menu > .current-menu-item > a' => 'color: {{VALUE}};',
							'.elementor-element-{{ID}} .menu > li.current-menu-ancestor > a' => 'color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'ancestor_hover_back_color',
					array(
						'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .menu > li:hover > a' => 'background-color: {{VALUE}};',
							'.elementor-element-{{ID}} .menu > .current-menu-item > a' => 'background-color: {{VALUE}};',
							'.elementor-element-{{ID}} .menu > .current-menu-ancestor > a' => 'background-color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'ancestor_hover_border_color',
					array(
						'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .menu > li:hover > a' => 'border-color: {{VALUE}};',
							'.elementor-element-{{ID}} .menu > .current-menu-item > a' => 'border-color: {{VALUE}};',
							'.elementor-element-{{ID}} .menu > .current-menu-ancestor > a' => 'border-color: {{VALUE}};',
						),
					)
				);

				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_responsive_control(
				'ancestor_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', 'rem', '%' ),
					'selectors'   => array(
						'{{WRAPPER}} .menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}} .collapsible-menu>li>a>.toggle-btn' => "{$right}: {{RIGHT}}{{UNIT}}",
						'{{WRAPPER}} .vertical-menu>li>a:after' => "{$right}: {{RIGHT}}{{UNIT}}",
					),
					'separator'   => 'before',
					'qa_selector' => '.menu > li:nth-child(2) > a, .collapsible-menu>li>a>.toggle-btn',
				)
			);

			$this->add_responsive_control(
				'ancestor_sticky_padding',
				array(
					'label'       => esc_html__( 'Padding on Sticky', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', 'rem', '%' ),
					'selectors'   => array(
						'.sticky-content.fixed .elementor-element-{{ID}} .menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.sticky-content.fixed .elementor-element-{{ID}} .collapsible-menu>li>a>.toggle-btn' => "{$right}: {{RIGHT}}{{UNIT}}",
						'.sticky-content.fixed .elementor-element-{{ID}} .vertical-menu>li>a:after' => "{$right}: {{RIGHT}}{{UNIT}}",
					),
					'qa_selector' => '.sticky-content.fixed .menu > li:nth-child(2) > a, .sticky-content.fixed .collapsible-menu>li>a>.toggle-btn',
				)
			);

			$this->add_responsive_control(
				'ancestor_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .menu > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}} .menu > li:last-child' => "margin-{$right}: 0;",
					),
					'condition'  => array(
						'type' => 'horizontal',
					),
				)
			);

			$this->add_responsive_control(
				'ancestor_margin2',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .menu > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}} .menu > li:last-child' => 'margin-bottom: 0;',
					),
					'condition'  => array(
						'type!' => 'horizontal',
					),
				)
			);

			$this->add_control(
				'ancestor_border',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .menu > li > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					),
				)
			);

			$this->add_control(
				'ancestor_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .menu > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'underline_width',
				array(
					'label'       => esc_html__( 'Underline Height (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the height of underline.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'separator'   => 'before',
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .menu-active-underline > li > a:before' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'underline' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'underline_spacing',
				array(
					'label'       => esc_html__( 'Underline Position (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the position of underline from bottom of the menu item.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .menu-active-underline > li > a:before' => 'bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'underline' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'underline_spacing_sticky',
				array(
					'label'       => esc_html__( 'Underline Position on Sticky (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the position of underline from bottom of the menu item in sticky header.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'   => array(
						'.sticky-content.fixed .elementor-element-{{ID}} .menu-active-underline > li > a:before' => 'bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'underline' => 'yes',
					),
				)
			);

			$this->add_control(
				'underline_color',
				array(
					'label'     => esc_html__( 'Underline Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .menu-active-underline > li > a:before' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'underline' => 'yes',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submenu_style',
			array(
				'label' => esc_html__( 'Submenu Item', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'submenu_typography',
					'selector' => '.elementor-element-{{ID}} li ul',
				)
			);

			$this->start_controls_tabs( 'submenu_color_tab' );
				$this->start_controls_tab(
					'submenu_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

				$this->add_control(
					'submenu_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} li li > a' => 'color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'submenu_bg_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} li li > a' => 'background-color: {{VALUE}};',
						),
					)
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'submenu_hover',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

				$this->add_control(
					'submenu_hover_color',
					array(
						'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} li li:hover > a:not(.nolink)' => 'color: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'submenu_hover_bg_color',
					array(
						'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} li li:hover > a:not(.nolink)' => 'background-color: {{VALUE}};',
						),
					)
				);

				$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_responsive_control(
				'submenu_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', 'rem', '%' ),
					'selectors'   => array(
						'{{WRAPPER}} li li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'qa_selector' => '.menu>li>ul>li:first-child>a',
					'separator'   => 'before',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dropdown_style',
			array(
				'label'     => esc_html__( 'Menu Dropdown Box', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'type!' => 'flyout',
				),
			)
		);

			$this->add_control(
				'dropdown_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', '%' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .toggle-menu .menu, .elementor-element-{{ID}} .menu > li ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'dropdown_bg',
				array(
					'label'       => esc_html__( 'Background', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .toggle-menu .menu, .elementor-element-{{ID}} .menu li > ul, .elementor-element-{{ID}} .collapsible-menu' => 'background-color: {{VALUE}}',
						'.elementor-element-{{ID}} .menu > .menu-item-has-children::after, .elementor-element-{{ID}} .toggle-menu::after' => 'border-bottom-color:  {{VALUE}}',
						'.elementor-element-{{ID}} .menu.vertical-menu > .menu-item-has-children::after' => "border-bottom-color: transparent; border-{$right}-color: {{VALUE}}",
					),
					'qa_selector' => '.toggle-menu .menu, .menu>li>ul, .collapsible-menu',
				)
			);

			$this->add_control(
				'dropdown_bd_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .has-border ul.menu' => 'border-color: {{VALUE}}',
						'.elementor-element-{{ID}} .has-border::before' => 'border-bottom-color: {{VALUE}} !important',
					),
					'condition' => array(
						'type'   => 'dropdown',
						'no_bd!' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'dropdown_box_shadow',
					'selector' => '{{WRAPPER}} .dropdown-box, {{WRAPPER}} .show .dropdown-box, {{WRAPPER}} .menu>li>ul, {{WRAPPER}} .menu ul:not(.megamenu) ul,  .home {{WRAPPER}} .show-home .dropdown-box',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_flyout_general',
			array(
				'label'     => esc_html__( 'Overlay & Close', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'type' => array( 'flyout' ),
				),
			)
		);
			$this->add_control(
				'flyout_overlay_color',
				array(
					'label'     => esc_html__( 'Overlay Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .flyout-box' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'flyout_close_color',
				array(
					'label'     => esc_html__( 'Close Icon Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .flyout-close .close-icon:before, .elementor-element-{{ID}} .flyout-close .close-icon:after' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'flyout_close_size',
				array(
					'label'      => esc_html__( 'Close Icon Size (px)', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .flyout-close .close-icon' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/menu/render-menu.php' );
	}
}
