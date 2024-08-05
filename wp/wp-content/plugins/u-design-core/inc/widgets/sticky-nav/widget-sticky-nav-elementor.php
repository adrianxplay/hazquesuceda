<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sticky Nav Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;

class Alpha_Sticky_Nav_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_sticky_nav';
	}

	public function get_title() {
		return esc_html__( 'Sticky Navigation', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'sticky', 'navigation', 'menu', 'nav' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-navigation-horizontal';
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-sticky-nav', alpha_core_framework_uri( '/widgets/sticky-nav/sticky-nav' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-sticky-nav' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-sticky-nav', alpha_core_framework_uri( '/widgets/sticky-nav/sticky-nav' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-sticky-nav' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_sticky_nav',
			array(
				'label' => esc_html__( 'Sticky Navigation', 'alpha-core' ),
			)
		);

		$this->add_control(
			'container',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Wrap as Container', 'alpha-core' ),
			)
		);

		$this->add_control(
			'min_width',
			array(
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Min Width (unit: px)', 'alpha-core' ),
				'description' => esc_html__( 'Will be disable sticky if window width is smaller than min width', 'alpha-core' ),
				'min'         => 320,
				'max'         => 1920,
				'default'     => 991,
			)
		);

		$this->add_control(
			'top_space',
			array(
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Top Space (unit: px)', 'alpha-core' ),
				'description' => esc_html__( 'Set the top space of sticky nav', 'alpha-core' ),
				'min'         => 0,
				'max'         => 100,
				'default'     => 0,
				'size_units'  => array(
					'px',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .sticky-content.fixed' => 'top: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'alpha-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'start',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'full_width',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Set Width as full', 'alpha-core' ),
				'description' => esc_html__( 'This option allows you to set box width as whether full and auto.', 'alpha-core' ),
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Divider', 'alpha-core' ),
			)
		);

		$repeater = new Elementor\Repeater();

		$repeater->start_controls_tabs(
			'sticky_nav_items'
		);

		$repeater->start_controls_tab(
			'sticky_nav_item',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'type'  => Controls_Manager::TEXT,
				'label' => esc_html__( 'Label', 'alpha-core' ),
			)
		);

		$repeater->add_control(
			'tooltip',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Tooltip', 'alpha-core' ),
				'condition' => array( 'show_icon' => 'yes' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'type'  => Controls_Manager::URL,
				'label' => esc_html__( 'Link', 'alpha-core' ),
			)
		);
		$repeater->add_control(
			'show_icon',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Icon', 'alpha-core' ),
			)
		);
		$repeater->add_control(
			'icon_type',
			array(
				'label'       => esc_html__( 'Icon to display', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'icon'  => esc_html__( 'Icon Fonts', 'alpha-core' ),
					'image' => esc_html__( 'Custom Image', 'alpha-core' ),
				),
				'default'     => 'icon',
				'description' => esc_html__( 'Use an existing font icon or upload a custom image.', 'alpha-core' ),
				'condition'   => array(
					'show_icon' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'icon_cl',
			array(
				'type'             => Controls_Manager::ICONS,
				'label'            => esc_html__( 'Icon', 'alpha-core' ),
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'show_icon' => 'yes',
					'icon_type' => 'icon',
				),
			)
		);
		$repeater->add_control(
			'icon_image',
			array(
				'type'        => Controls_Manager::MEDIA,
				'label'       => esc_html__( 'Upload Image Icon:', 'alpha-core' ),
				'description' => esc_html__( 'Upload the custom image icon.', 'alpha-core' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_icon' => 'yes',
					'icon_type' => array( 'image' ),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'sticky_nav_item_style',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
			)
		);

		$repeater->add_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Image Width (px)', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 600,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .sticky-navs {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'link_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs {{CURRENT_ITEM}} > a' => 'color: {{VALUE}};',
				),
			)
		);
		$repeater->add_control(
			'link_bg_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Background Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs {{CURRENT_ITEM}} > a' => 'background-color: {{VALUE}};',
				),
			)
		);
		$repeater->add_control(
			'link_acolor1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Active Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs {{CURRENT_ITEM}}.active > a' => 'color: {{VALUE}};',
				),
			)
		);
		$repeater->add_control(
			'link_abg_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Active Background Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs {{CURRENT_ITEM}}.active > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$presets = array(
			array(
				'label' => esc_html__( 'Item 1', 'alpha-core' ),
				'link'  => '#',
			),
			array(
				'label' => esc_html__( 'Item 2', 'alpha-core' ),
				'link'  => '#',
			),
		);
		$this->add_control(
			'sticky_nav_item_list',
			array(
				'label'   => esc_html__( 'Sticky Nav Items', 'alpha-core' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => $presets,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'general_link_padding',
			array(
				'label'      => esc_html__( 'Nav Box Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => array(
					'.elementor-element-{{ID}} .sticky-navs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'size_units' => array( 'px', 'em', 'rem' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			array(
				'label'      => esc_html__( 'Item', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'bg_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'alpha-core' ),
				'selectors' => array(
					'{{WRAPPER}} .sticky-nav-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => esc_html__( 'Link Typograhy', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .sticky-navs > li > a, .elementor-element-{{ID}} .sticky-navs > li > span',
			)
		);

		$this->start_controls_tabs( 'tabs_link_color' );

		$this->start_controls_tab(
			'link_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'alpha-core' ),
			)
		);

		$this->add_control(
			'link_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_bg_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Background Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs > li > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'alpha-core' ),
			)
		);

		$this->add_control(
			'link_hover_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Hover Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs > li > a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_color_active',
			array(
				'label' => esc_html__( 'Active', 'alpha-core' ),
			)
		);

		$this->add_control(
			'link_acolor1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Active Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs > li.active > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_abg_color1',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Active Background Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-navs > li.active > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'link_padding',
			array(
				'label'      => esc_html__( 'Link Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => array(
					'.elementor-element-{{ID}} .sticky-navs > li > a, .elementor-element-{{ID}} .sticky-navs > li > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'size_units' => array( 'px', 'em', 'rem' ),
			)
		);

		$this->add_responsive_control(
			'link_margin',
			array(
				'label'      => esc_html__( 'Link Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'selectors'  => array(
					'.elementor-element-{{ID}} .sticky-navs > li > a, .elementor-element-{{ID}} .sticky-navs > li > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'size_units' => array( 'px', 'em', 'rem' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_divider_style',
			array(
				'label'     => esc_html__( 'Divider', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Divider Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .sticky-nav-container.with-divider li:before' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_height',
			array(
				'label'      => esc_html__( 'Height (%)', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .sticky-nav-container.with-divider li:before' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		require ALPHA_CORE_INC . '/widgets/sticky-nav/render-sticky-nav-elementor.php';
	}

	protected function content_template() {
		?>
		<#
		view.addRenderAttribute('nav-wrapper', 'class', 'sticky-content fix-top sticky-nav-container');
		if(settings.show_divider) {
			view.addRenderAttribute('nav-wrapper', 'class', 'with-divider');
		}
		view.addRenderAttribute( 'wrapper', 'class', 'nav-secondary' );
		view.addRenderAttribute( 'wrapper', 'data-plugin-options', "{'minWidth': " + Number( settings.min_width ) + "}" );
		view.addRenderAttribute( 'nav', 'class', 'nav sticky-navs' );
		#>
		<div {{{ view.getRenderAttributeString( 'nav-wrapper' ) }}}>
			<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<# if ( settings.container ) { #>
				<div class="container">
			<# } #>
				<ul {{{ view.getRenderAttributeString( 'nav' ) }}}>
					<#
					_.each( settings.sticky_nav_item_list, function( item, index ) {
						if ( item.show_icon ) {
							if ( 'image' == item.icon_type ) {
								view.addRenderAttribute( 'nav-link', 'class', 'icon-image', true );
							} else {
								view.addRenderAttribute( 'nav-link', 'class', item.icon_cl.value, true );
							}
						}
					if(item.icon_cl && item.icon_cl.value ) {
					#><li class="elementor-repeater-item-{{ item._id }}" title="{{item.tooltip}}">
						<# if(item.link.url) { #>
							<a href="{{item.link.url}}" > <#
						} else {#>
						<span> 
						<# } 
						let icon_html = elementor.helpers.renderIcon( view, item.icon_cl, { 'aria-hidden': true }, 'i' , 'object' );

						if(item.show_icon) {
							if ( icon_html && icon_html.rendered ) {
								print(icon_html.value);
							} else { #>
								<i {{{ view.getRenderAttributeString( 'nav-link' ) }}}>
									<# if ( 'image' == item.icon_type && item.icon_image.url ) { #>
										<img class="img-icon" src="{{ item.icon_image.url }}" />
									<# } #>
								</i>
							<# } 
						} #>
							{{{ item.label }}}
						<# if(item.link.url) { #> </a> <# } else { #> </span> <# } #>
						</li><# 
					} } );
					#>
				</ul>
			<# if ( settings.container ) { #>
				</div>
			<# } #>
			</div>
		</div>
		<?php
	}

}
