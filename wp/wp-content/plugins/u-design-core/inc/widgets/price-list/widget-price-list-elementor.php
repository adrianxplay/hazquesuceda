<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Icon List Widget
 *
 * Alpha Widget to display icon list.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use ELementor\Group_Control_Box_Shadow;

class Alpha_Price_List_Elementor_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return ALPHA_NAME . '_widget_price_list';
	}

	public function get_title() {
		return esc_html__( 'Price List', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'pricelist', 'price', 'list', 'order', 'menu' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-pricelist';
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-price-list', alpha_core_framework_uri( '/widgets/price-list/price-list' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-price-list' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-price-hover-animation-js', ALPHA_CORE_INC_URI . '/widgets/price-list/price-hover-animation.js', array(), ALPHA_CORE_VERSION, true );
		return array( 'alpha-price-hover-animation-js' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_general',
			array(
				'label'      => esc_html__( 'General', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'alpha-core' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Item #', 'alpha-core' ),
			)
		);

		$repeater->add_control(
			'item_price',
			array(
				'label'   => esc_html__( 'Price', 'alpha-core' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( '$10', 'alpha-core' ),
			)
		);

		$repeater->add_control(
			'item_text',
			array(
				'label'   => esc_html__( 'Description', 'alpha-core' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur.', 'alpha-core' ),
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'alpha-core' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => '',
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_url',
			array(
				'label'   => esc_html__( 'URL', 'alpha-core' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url' => '',
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'price_list',
			array(
				'label'       => esc_html__( 'List items', 'alpha-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_title' => esc_html__( 'Item #1', 'alpha-core' ),
						'item_price' => esc_html__( '$32', 'alpha-core' ),
						'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur.', 'alpha-core' ),
					),
					array(
						'item_title' => esc_html__( 'Item #2', 'alpha-core' ),
						'item_price' => esc_html__( '$41', 'alpha-core' ),
						'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur.', 'alpha-core' ),
					),
					array(
						'item_title' => esc_html__( 'Item #3', 'alpha-core' ),
						'item_price' => esc_html__( '$25', 'alpha-core' ),
						'item_text'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur.', 'alpha-core' ),
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->add_control(
			'hide_items_with_empty_price',
			array(
				'label'     => esc_html__( 'Hide all unpriced catalogues', 'alpha-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
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

		$this->add_responsive_control(
			'item_space_between',
			array(
				'label'      => esc_html__( 'Space Between Items (px)', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .alpha-price-list-item' => 'padding-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .alpha-price-list-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .alpha-price-list-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .alpha-price-list-title:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price_style',
			array(
				'label'      => esc_html__( 'Price', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .alpha-price-list-price',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .alpha-price-list-price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_style',
			array(
				'label'      => esc_html__( 'Description', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .alpha-price-list-desc',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .alpha-price-list-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .alpha-price-lists p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_divider_style',
			array(
				'label'      => esc_html__( 'Divider', 'alpha-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .alpha-price-list-main>span' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		require ALPHA_CORE_INC . '/widgets/price-list/render-price-list-elementor.php';
	}

	protected function content_template() {
		?>
			<#
				view.addRenderAttribute( 'price_list', 'class', 'alpha-price-lists' );
				view.addRenderAttribute( 'list_item', 'class', 'alpha-price-list-item' );
			#>
			<# if ( settings.price_list ) { #>
				<ul {{{ view.getRenderAttributeString( 'price_list' ) }}}>
				<# _.each( settings.price_list, function( item, index ) {

					var itemTextKey = view.getRepeaterSettingKey( 'item_title', 'price_list', index );
						var itemPriceKey = view.getRepeaterSettingKey( 'item_price', 'price_list', index );
						var itemDescKey = view.getRepeaterSettingKey( 'item_text', 'price_list', index );

						view.addRenderAttribute( itemTextKey, 'class', 'alpha-price-list-title' );
						view.addRenderAttribute( itemPriceKey, 'class', 'alpha-price-list-price' );
						view.addRenderAttribute( itemDescKey, 'class', 'alpha-price-list-desc' );

						view.addInlineEditingAttributes( itemTextKey );
						view.addInlineEditingAttributes( itemPriceKey );
						view.addInlineEditingAttributes( itemDescKey );

					if('yes' == settings.hide_items_with_empty_price ) {
						if(item.item_price && item.item_title) {
					 #>
						<li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
							<div class="alpha-price-list-main">
								<# if ( item.item_url) { #>
									<a href="{{ item.item_url.url }}" >
								<# } #>
								<h5 {{{view.getRenderAttributeString( itemTextKey )}}}>{{{item.item_title}}}</h5>
							</a>
							<span></span>
							<div {{{view.getRenderAttributeString( itemPriceKey )}}}>{{{ item.item_price }}}</div>
							</div>
							<p {{{view.getRenderAttributeString( itemDescKey )}}}>{{{item.item_text}}}</p>
							<# if ( item.item_image && item.item_image.url ) { #>
								<figure class="price-hover-image">
									<div class="price-hover-wrap">
										<img src="{{item.item_image.url}}" alt="{{item.item_image.alt}}" />
									</div>
								</figure>
							<# } #>
						</li>
					<# } } else {
						#>
						<li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
							<div class="alpha-price-list-main">
								<# if ( item.item_url) { #>
									<a href="{{ item.item_url.url }}" >
								<# } #>
								<h5 {{{view.getRenderAttributeString( itemTextKey )}}}>{{{item.item_title}}}</h5>
							</a>
							<span></span>
							<div {{{view.getRenderAttributeString( itemPriceKey )}}}>{{{ item.item_price }}}</div>
							</div>
							<p {{{view.getRenderAttributeString( itemDescKey )}}}>{{{item.item_text}}}</p>
							<# if ( item.item_image && item.item_image.url ) { #>
								<figure class="price-hover-image">
									<div class="price-hover-wrap">
										<img src="{{item.item_image.url}}" alt="{{item.item_image.alt}}" />
									</div>
								</figure>
							<# } #>
						</li>
						<#
					}
				} ); #>
				</ul>
			<#	} #>
		<?php
	}
}

