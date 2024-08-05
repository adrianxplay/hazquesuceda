<?php
/**
 * Alpha Header Elementor Cart
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Header_Cart_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_header_cart';
	}

	public function get_title() {
		return esc_html__( 'Cart', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-cart-medium';
	}

	public function get_categories() {
		return array( 'alpha_header_widget' );
	}

	public function get_keywords() {
		return array( 'header', 'alpha', 'cart', 'shop', 'mini', 'bag' );
	}

	public function get_script_depends() {
		$depends = array( 'alpha-woocommerce' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';
		$this->start_controls_section(
			'section_cart_content',
			array(
				'label' => esc_html__( 'Cart', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'type',
				array(
					'label'   => esc_html__( 'Cart Type', 'alpha-core' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'inline',
					'options' => array(
						'block'  => array(
							'title' => esc_html__( 'Block', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
						'inline' => array(
							'title' => esc_html__( 'Inline', 'alpha-core' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
				)
			);

			$this->add_control(
				'mini_cart',
				array(
					'label'       => esc_html__( 'Cart Items', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'dropdown',
					'options'     => array(
						'dropdown'  => esc_html__( 'Dropdown', 'alpha-core' ),
						'offcanvas' => esc_html__( 'Off-Canvas', 'alpha-core' ),
					),
					'description' => esc_html__( 'Select the way to show a mini-cart list.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'icon_type',
				array(
					'label'       => esc_html__( 'Cart Icon Type', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'badge',
					'options'     => array(
						'badge' => esc_html__( 'Badge Type', 'alpha-core' ),
						'label' => esc_html__( 'Label Type', 'alpha-core' ),
					),
					'description' => esc_html__( 'Select the type to show cart icon.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'icon',
				array(
					'label'                  => esc_html__( 'Cart Icon', 'alpha-core' ),
					'type'                   => Controls_Manager::ICONS,
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-cart',
						'library' => 'alpha-icons',
					),
					'condition'              => array(
						'icon_type' => 'badge',
					),
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
				)
			);

			$this->add_control(
				'icon_pos',
				array(
					'label'       => esc_html__( 'Show Icon Before', 'alpha-core' ),
					'default'     => 'yes',
					'type'        => Controls_Manager::SWITCHER,
					'conditions'  => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'icon_type',
								'operator' => '==',
								'value'    => 'badge',
							),
							array(
								'relation' => 'or',
								'terms'    => array(
									array(
										'name'     => 'type',
										'operator' => '==',
										'value'    => 'inline',
									),
									array(
										'name'     => 'type',
										'operator' => '==',
										'value'    => '',
									),
								),
							),
						),
					),
					'description' => esc_html__( 'You will be able to show cart icon before or after of label.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'show_label',
				array(
					'label'   => esc_html__( 'Show Label', 'alpha-core' ),
					'default' => 'yes',
					'type'    => Controls_Manager::SWITCHER,
				)
			);

			$this->add_control(
				'label',
				array(
					'label'       => esc_html__( 'Cart Label', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'My Cart', 'alpha-core' ),
					'condition'   => array(
						'show_label' => 'yes',
					),
					'description' => esc_html__( 'Controls the label of cart.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'show_price',
				array(
					'label'   => esc_html__( 'Show Cart Total Price', 'alpha-core' ),
					'default' => 'yes',
					'type'    => Controls_Manager::SWITCHER,
				)
			);

			$this->add_control(
				'delimiter',
				array(
					'label'       => esc_html__( 'Delimiter', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '/',
					'condition'   => array(
						'show_label' => 'yes',
						'show_price' => 'yes',
					),
					'description' => esc_html__( 'Controls the delimiter between icon and label.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'show_offcanvas_quantity',
				array(
					'label'       => esc_html__( 'Show Quantity', 'alpha-core' ),
					'default'     => 'yes',
					'type'        => Controls_Manager::SWITCHER,
					'condition'   => array(
						'mini_cart' => 'offcanvas',
					),
					'description' => esc_html__( 'Show quantity of product on offcanvas menu.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'count_pfx',
				array(
					'label'     => esc_html__( 'Cart Count Prefix', 'alpha-core' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '( ',
					'condition' => array(
						'icon_type' => 'label',
					),
				)
			);

			$this->add_control(
				'count_sfx',
				array(
					'label'     => esc_html__( 'Cart Count suffix', 'alpha-core' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => ' items )',
					'condition' => array(
						'icon_type' => 'label',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			array(
				'label' => esc_html__( 'Cart Toggle', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cart_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .offcanvas-open' => 'color: {{VALUE}};',
				),
				'description' => esc_html__( 'Controls the color of cart.', 'alpha-core' ),
			)
		);

		$this->add_control(
			'cart_hover_color',
			array(
				'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .cart-dropdown:hover .offcanvas-open' => 'color: {{VALUE}};',
				),
				'description' => esc_html__( 'Controls the hover color of cart.', 'alpha-core' ),
			)
		);

			$this->add_control(
				'cart_label_heading',
				array(
					'label'     => esc_html__( 'Cart Text', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'cart_typography',
					'selector' => '{{WRAPPER}} .offcanvas-open, {{WRAPPER}} .cart-count',
				)
			);

			$this->add_responsive_control(
				'cart_delimiter_space',
				array(
					'label'       => esc_html__( 'Delimiter Space (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .offcanvas-open .cart-name-delimiter' => 'margin: 0 {{SIZE}}px;',
					),
					'condition'   => array(
						'show_label' => 'yes',
						'show_price' => 'yes',
						'delimiter!' => '',
					),
					'description' => esc_html__( 'Controls the space between cart icon and label.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'cart_price_heading',
				array(
					'label'       => esc_html__( 'Cart Price', 'alpha-core' ),
					'type'        => Controls_Manager::HEADING,
					'separator'   => 'before',
					'condition'   => array(
						'show_price' => 'yes',
					),
					'description' => esc_html__( 'Controls the color of cart price.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'cart_price_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .cart-price' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'show_price' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'cart_price_typography',
					'selector'  => '.elementor-element-{{ID}} .cart-price',
					'condition' => array(
						'show_price' => 'yes',
					),
				)
			);

			$this->add_control(
				'cart_icon_heading',
				array(
					'label'     => esc_html__( 'Cart Icon', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'icon_type' => 'badge',
					),
				)
			);

			$this->add_responsive_control(
				'cart_icon',
				array(
					'label'       => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .cart-dropdown .offcanvas-open > i' => 'font-size: {{SIZE}}px;',
					),
					'condition'   => array(
						'icon_type' => 'badge',
					),
					'description' => esc_html__( 'Controls the size value of cart icon.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'cart_icon_space',
				array(
					'label'       => esc_html__( 'Icon Space (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .block-type .cart-label + i' => 'margin-bottom: {{SIZE}}px;',
						'.elementor-element-{{ID}} .inline-type .cart-label + i' => "margin-{$left}: {{SIZE}}px;",
						'.elementor-element-{{ID}} .inline-type i + .cart-label' => "margin-{$left}: {{SIZE}}px;",
					),
					'condition'   => array(
						'icon_type' => 'badge',
					),
					'description' => esc_html__( 'Controls the space between cart icon and label.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_badge_style',
			array(
				'label'     => esc_html__( 'Badge', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'icon_type' => 'badge',
				),
			)
		);

			$this->add_control(
				'badge_size',
				array(
					'label'       => esc_html__( 'Badge Size', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .badge-type .offcanvas-open >i >.cart-count' => 'font-size: {{SIZE}}px;',
					),
					'description' => esc_html__( 'Controls the size value of badge item.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'badge_h_position',
				array(
					'label'       => esc_html__( 'Horizontal Position', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .badge-type .offcanvas-open >i >.cart-count' => "{$left}: {{SIZE}}{{UNIT}};",
					),
					'description' => esc_html__( 'Controls the horizontal position value of badge item.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'badge_v_position',
				array(
					'label'       => esc_html__( 'Vertical Position', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', '%' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .badge-type .offcanvas-open >i >.cart-count' => 'top: {{SIZE}}{{UNIT}};',
					),
					'description' => esc_html__( 'Controls the vertical position value of badge item.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'badge_count_bg_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'condition'   => array(
						'icon_type' => 'badge',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .badge-type .offcanvas-open >i >.cart-count' => 'background-color: {{VALUE}};',
					),
					'description' => esc_html__( 'Controls background color of badge item.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'badge_count_bd_color',
				array(
					'label'       => esc_html__( 'Count Color', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'condition'   => array(
						'icon_type' => 'badge',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .badge-type .offcanvas-open >i >.cart-count' => 'color: {{VALUE}};',
					),
					'description' => esc_html__( 'Controls color of badge counter.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'type'      => $settings['type'],
			'icon_type' => $settings['icon_type'],
			'icon_pos'  => $settings['icon_pos'],
			'mini_cart' => $settings['mini_cart'],
			'title'     => $settings['show_label'],
			'label'     => $settings['label'],
			'price'     => $settings['show_price'],
			'delimiter' => $settings['delimiter'],
			'pfx'       => $settings['count_pfx'],
			'sfx'       => $settings['count_sfx'],
			'icon'      => ! empty( $settings['icon']['value'] ) ? $settings['icon']['value'] : ALPHA_ICON_PREFIX . '-icon-cart',
		);
		if ( 'yes' === $settings['show_offcanvas_quantity'] ) {
			add_filter( 'alpha_mini_cart_quantity', array( $this, 'alpha_mini_cart_quantity' ) );
		}
		require alpha_core_framework_path( ALPHA_BUILDERS . '/header/widgets/cart/render-cart-elementor.php' );
	}

	public function alpha_mini_cart_quantity( $param ) {
		return false;
	}
}
