<?php
/**
 * Alpha Icon List Widget
 *
 * Alpha Widget to display icon list.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use ELementor\Group_Control_Box_Shadow;

class Alpha_IconList_Elementor_Widget extends \Elementor\Widget_Icon_List {
	public function get_name() {
		return ALPHA_NAME . '_widget_iconlist';
	}

	public function get_group_name() {
		return 'icon-list';
	}

	public function get_title() {
		return esc_html__( 'Icon List', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'icon list', 'icon', 'list', 'alpha', 'menu' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-bullet-list';
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-iconlist', alpha_core_framework_uri( '/widgets/iconlist/iconlist' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-iconlist' );
	}

	public function get_script_depends() {
		return array();
	}

	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 4.0
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();
		$this->update_control(
			'view',
			array(
				'description' => esc_html__( 'Select a certain layout type of your list among Default and Inline types.', 'alpha-core' ),
			)
		);

		$this->remove_control( 'link_click' );

		$this->start_controls_section(
			'section_ordered',
			array(
				'label' => esc_html__( 'Ordered List', 'alpha-core' ),
			)
		);
			$this->add_control(
				'ordered_list',
				array(
					'label'       => esc_html__( 'Ordered List', 'alpha-core' ),
					'description' => esc_html__( 'Toggle for making your list ordered or not. *Please remove icons before setting this option.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_off'   => esc_html__( 'Off', 'alpha-core' ),
					'label_on'    => esc_html__( 'On', 'alpha-core' ),
					'selectors'   => array(
						'{{WRAPPER}} .elementor-icon-list-item' => 'display: list-item;',
					),
				)
			);
			$this->add_control(
				'list_style',
				array(
					'label'       => esc_html__( 'List Style', 'alpha-core' ),
					'description' => esc_html__( 'Select a certain list style for your ordered list.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'circle'               => esc_html__( 'Circle', 'alpha-core' ),
						'decimal'              => esc_html__( 'Decimal', 'alpha-core' ),
						'decimal-leading-zero' => esc_html__( 'Decimal Leading Zero', 'alpha-core' ),
						'lower-alpha'          => esc_html__( 'Lower-alpha', 'alpha-core' ),
						'upper-alpha'          => esc_html__( 'Upper-alpha', 'alpha-core' ),
						'disc'                 => esc_html__( 'Disc', 'alpha-core' ),
						'square'               => esc_html__( 'Square', 'alpha-core' ),
					),
					'default'     => 'circle',
					'separator'   => 'before',
					'selectors'   => array(
						'{{WRAPPER}} .elementor-icon-list-items' => 'list-style: {{VALUE}};',
					),
					'condition'   => array(
						'ordered_list' => 'yes',
					),
				)
			);
		$this->end_controls_section();

		$this->update_control(
			'space_between',
			array(
				'default'     => array(
					'size' => 10,
				),
				'description' => esc_html__( 'Controls the space between your list items.', 'alpha-core' ),
			)
		);
		$this->update_responsive_control(
			'icon_align',
			array(
				'label'       => esc_html__( 'Horizontal Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Controls the horizontal alignment of your lists.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'divider',
			array(
				'description' => esc_html__( 'Toggle for making your list items have dividers or not.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'divider_style',
			array(
				'description' => esc_html__( 'Controls the divider style.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'divider_weight',
			array(
				'description' => esc_html__( 'Controls the divider height.', 'alpha-core' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:after' => 'bottom: calc(-1 * {{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}} .elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->update_control(
			'divider_width',
			array(
				'description' => esc_html__( 'Controls the divider width.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'divider_height',
			array(
				'description' => esc_html__( 'Controls the divider height in the inline type.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'divider_color',
			array(
				'description' => esc_html__( 'Controls the divider color.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'icon_color',
			array(
				'description' => esc_html__( 'Controls the icon color.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'icon_color_hover',
			array(
				'description' => esc_html__( 'Controls the icon hover color.', 'alpha-core' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-item:hover > .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item a:hover .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item:hover > .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item a:hover .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->update_control(
			'icon_size',
			array(
				'description' => esc_html__( 'Controls the icon size.', 'alpha-core' ),
			)
		);
		$this->add_responsive_control(
			'icon_lineheight',
			array(
				'label'       => esc_html__( 'Line Height', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon line height.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-icon i'   => 'line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'height: {{SIZE}}{{UNIT}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'icon_size',
				),
			)
		);
		$this->update_control(
			'icon_color_hover_transition',
			array(
				'default'   => array(
					'unit' => 's',
					'size' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-list-icon i' => 'transition: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'transition: {{SIZE}}{{UNIT}}',
				),
			),
		);
		$this->remove_control(
			'icon_self_vertical_align'
		);
		$this->remove_control(
			'icon_vertical_offset'
		);

		$this->add_responsive_control(
			'bg_size',
			array(
				'label'       => esc_html__( 'Background Size', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon background size.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 25,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => 'display: inline-flex; justify-content: center; align-items: center; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'icon_lineheight',
				),
			)
		);

		$this->add_responsive_control(
			'icon_v_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'alpha-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'alpha-core' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'alpha-core' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'alpha-core' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .elementor-icon-list-items .elementor-icon-list-item' => 'align-items: {{VALUE}}',
				),
				'condition' => array(
					'ordered_list!' => 'yes',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'divider',
				),
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'       => esc_html__( 'Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon background color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'bg_size',
				),
			)
		);
		$this->add_control(
			'bg_color_hover',
			array(
				'label'       => esc_html__( 'Background Hover Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon background hover color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon' => 'background-color: {{VALUE}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'bg_color',
				),
			)
		);
		$this->add_control(
			'border_style',
			array(
				'label'     => esc_html__( 'Border Style', 'alpha-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'alpha-core' ),
					'solid'  => esc_html__( 'Solid', 'alpha-core' ),
					'double' => esc_html__( 'Double', 'alpha-core' ),
					'dotted' => esc_html__( 'Dotted', 'alpha-core' ),
					'dashed' => esc_html__( 'Dashed', 'alpha-core' ),
				),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => 'border-style: {{VALUE}}',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'bg_color_hover',
				),
			)
		);
		$this->add_control(
			'br_color',
			array(
				'label'       => esc_html__( 'Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon border color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => 'border-color: {{VALUE}};',
				),
				'condition'   => array(
					'border_style!' => 'none',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'border_style',
				),
			)
		);
		$this->add_control(
			'br_color_hover',
			array(
				'label'       => esc_html__( 'Border Hover Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon border hover color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-icon i' => 'transition: .3s;',
				),
				'condition'   => array(
					'border_style!' => 'none',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'br_color',
				),
			)
		);
		$this->add_control(
			'border_width',
			array(
				'label'       => esc_html__( 'Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon border width.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array(
					'border_style!' => 'none',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'br_color_hover',
				),
			)
		);
		$this->add_control(
			'border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
				'description' => esc_html__( 'Controls the icon border radius.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array(
					'border_style!' => 'none',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'border_width',
				),
			)
		);
		$this->remove_control(
			'icon_self_align'
		);
		$this->update_control(
			'text_color',
			array(
				'description' => esc_html__( 'Controls the list text color.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'text_color_hover',
			array(
				'selectors'   => array(
					'{{WRAPPER}} .elementor-icon-list-item:hover > .elementor-icon-list-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item a:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
				),
				'description' => esc_html__( 'Controls the list text hover color.', 'alpha-core' ),
			)
		);
		$this->update_control(
			'text_color_hover_transition',
			array(
				'default' => array(
					'unit' => 's',
					'size' => '',
				),
			)
		);
		$this->update_control(
			'text_indent',
			array(
				'separator'   => '',
				'selectors'   => [
					'{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Controls the spacing between icon and text.', 'alpha-core' ),
			)
		);

		$this->start_controls_section(
			'section_badge',
			array(
				'label' => esc_html__( 'Badge', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'badge_typography',
					'selector' => '{{WRAPPER}} .elementor-icon-list-badge',
				)
			);

			$this->add_responsive_control(
				'badge_v_position',
				array(
					'label'       => esc_html__( 'Vertical Position', 'alpha-core' ),
					'description' => esc_html__( 'Controls the vertical position of list badge.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'em' ),
					'range'       => array(
						'px' => array(
							'min' => -25,
							'max' => 25,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .elementor-icon-list-badge'   => 'top: {{SIZE}}{{UNIT}};',
					),
				),
			);

			$this->add_responsive_control(
				'badge_h_position',
				array(
					'label'       => esc_html__( 'Horizontal Position', 'alpha-core' ),
					'description' => esc_html__( 'Controls the horizontal position of list badge.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'em' ),
					'range'       => array(
						'px' => array(
							'min' => -25,
							'max' => 25,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .elementor-icon-list-badge'   => 'left: {{SIZE}}{{UNIT}};',
					),
				),
			);

			$this->add_control(
				'badge_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .elementor-icon-list-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				),
			);

			$this->add_control(
				'badge_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .elementor-icon-list-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				),
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_marker',
			array(
				'label'     => esc_html__( 'Marker', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ordered_list' => 'yes',
				),
			)
		);

			$this->add_control(
				'marker_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the marker color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'default'     => '',
					'selectors'   => array(
						'{{WRAPPER}} .elementor-icon-list-item::marker' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'marker_color_hover',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the marker hover color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'default'     => '',
					'selectors'   => array(
						'{{WRAPPER}} .elementor-icon-list-item:hover::marker' => 'color: {{VALUE}};',
						'{{WRAPPER}} .elementor-icon-list-item::marker' => 'transition: color .3s;',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'marker_typography',
					'selector' => '{{WRAPPER}} .elementor-icon-list-item::marker',
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/iconlist/render-iconlist-elementor.php' );
	}

	protected function content_template() {
		?>
		<#
			view.addRenderAttribute( 'icon_list', 'class', 'elementor-icon-list-items' );

			if ( 'inline' == settings.view ) {
				view.addRenderAttribute( 'icon_list', 'class', 'elementor-inline-items' );
			}
			var iconsHTML = {},
				migrated = {};
		#>
		<# if ( settings.icon_list ) { #>
			<ul {{{ view.getRenderAttributeString( 'icon_list' ) }}}>
			<# _.each( settings.icon_list, function( item, index ) {

					var iconItemKey = view.getRepeaterSettingKey( 'item', 'icon_list', index ),
						iconTextKey = view.getRepeaterSettingKey( 'text', 'icon_list', index ),
						iconBadgeKey = view.getRepeaterSettingKey( 'badge_label', 'icon_list', index );
						
					view.addRenderAttribute( iconItemKey, 'class', 'elementor-icon-list-item' );
					
					if ( 'inline' == settings.view ) {
						view.addRenderAttribute( iconItemKey, 'class', 'elementor-inline-item' );
					}
					view.addRenderAttribute( iconItemKey, 'class', 'elementor-repeater-item-' + item._id );

					view.addRenderAttribute( iconTextKey, 'class', 'elementor-icon-list-text' );
					view.addRenderAttribute( iconBadgeKey, 'class', 'elementor-icon-list-badge' );

					view.addInlineEditingAttributes( iconTextKey );
					view.addInlineEditingAttributes( iconBadgeKey ); #>

					<li {{{ view.getRenderAttributeString( iconItemKey ) }}}>
						<# if ( item.link && item.link.url ) { #>
							<a href="{{ item.link.url }}">
						<# } #>
						<# if ( item.icon || item.selected_icon.value ) { #>
						<span class="elementor-icon-list-icon">
							<#
								iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
								migrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.icon || migrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ item.icon }}" aria-hidden="true"></i>
								<# }
							#>
						</span>
						<# } #>
						<span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.text }}}</span>
						<# if ( item.badge_label ) { #>
						<span {{{ view.getRenderAttributeString( iconBadgeKey ) }}}>{{{ item.badge_label }}}</span>
						<# } #>
						<# if ( item.link && item.link.url ) { #>
							</a>
						<# } #>
					</li>
				<#
				} ); #>
			</ul>
		<#	} #>

		<?php
	}
}

add_action(
	'elementor/element/' . ALPHA_NAME . '_widget_iconlist/section_icon/before_section_end',
	function( $self, $args ) {
		$self->remove_control( 'icon_list' );

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_icon_list' );

		$repeater->start_controls_tab(
			'tab_icon_list',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

			$repeater->add_control(
				'text',
				array(
					'label'       => esc_html__( 'Text', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'List Item', 'alpha-core' ),
					'default'     => esc_html__( 'List Item', 'alpha-core' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'selected_icon',
				array(
					'label'            => esc_html__( 'Icon', 'alpha-core' ),
					'type'             => Controls_Manager::ICONS,
					'default'          => array(
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					),
					'fa4compatibility' => 'icon',
				)
			);

			$repeater->add_control(
				'link',
				array(
					'label'       => esc_html__( 'Link', 'alpha-core' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => array(
						'active' => true,
					),
					'placeholder' => esc_html__( 'https://your-link.com', 'alpha-core' ),
				)
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_icon_list_badge',
			array(
				'label' => esc_html__( 'Badge', 'alpha-core' ),
			)
		);

			$repeater->add_control(
				'badge_label',
				array(
					'label'   => esc_html__( 'Label', 'alpha-core' ),
					'type'    => Controls_Manager::TEXT,
					'dynamic' => array(
						'active' => true,
					),
				)
			);

			$repeater->add_control(
				'badge_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} {{CURRENT_ITEM}}.elementor-icon-list-item .elementor-icon-list-badge' => 'color: {{VALUE}}',
					),
				)
			);

			$repeater->add_control(
				'badge_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} {{CURRENT_ITEM}}.elementor-icon-list-item .elementor-icon-list-badge' => 'background-color: {{VALUE}}',
					),
				)
			);

		$repeater->end_controls_tab();

		$self->add_control(
			'icon_list',
			array(
				'label'       => esc_html__( 'Items', 'alpha-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'text'          => esc_html__( 'List Item #1', 'alpha-core' ),
						'selected_icon' => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
					),
					array(
						'text'          => esc_html__( 'List Item #2', 'alpha-core' ),
						'selected_icon' => array(
							'value'   => 'fas fa-times',
							'library' => 'fa-solid',
						),
					),
					array(
						'text'          => esc_html__( 'List Item #3', 'alpha-core' ),
						'selected_icon' => array(
							'value'   => 'fas fa-dot-circle',
							'library' => 'fa-solid',
						),
					),
				),
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
			)
		);
	},
	10,
	2
);
