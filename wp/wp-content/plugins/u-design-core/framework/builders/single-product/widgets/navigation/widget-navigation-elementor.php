<?php
/**
 * Alpha Elementor Single Product Prev-Next Navigation Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpha_Single_Product_Navigation_Elementor_Widget extends \Elementor\Widget_Base {

	public $prev_icon = '';
	public $next_icon = '';

	public function get_name() {
		return ALPHA_NAME . '_sproduct_navigation';
	}

	public function get_title() {
		return esc_html__( 'Product Navigation', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-post-navigation';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'navigation', 'prev', 'next' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_navigation',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sp_align',
				array(
					'label'     => esc_html__( 'Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => 'flex-start',
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
						'.elementor-element-{{ID}} .product-navigation' => 'justify-content: {{VALUE}}',
					),
					'toggle'    => false,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'        => 'sp_typo',
					'label'       => esc_html__( 'Typography', 'alpha-core' ),
					'description' => esc_html__( 'Set font style of product title in navigation popup.', 'alpha-core' ),
					'selector'    => '.elementor-element-{{ID}} .product-nav span span',
				)
			);

			$this->add_control(
				'nav_icon_size',
				array(
					'label'     => esc_html__( 'Icon Size', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .product-nav i' => 'font-size: {{SIZE}}px',
					),
					'separator' => 'before',
				)
			);

			$this->add_control(
				'sp_prev_icon',
				array(
					'label'                  => esc_html__( 'Prev Icon', 'alpha-core' ),
					'type'                   => Controls_Manager::ICONS,
					'fa4compatibility'       => 'icon',
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-angle-left',
						'library' => '',
					),
					'recommended'            => array(
						'fa-solid'   => array(
							'chevron-down',
							'angle-down',
							'angle-double-down',
							'caret-down',
							'caret-square-down',
						),
						'fa-regular' => array(
							'caret-square-down',
						),
					),
					'exclude_inline_options' => array(
						'svg',
					),
					'skin'                   => 'inline',
					'label_block'            => false,
				)
			);

			$this->add_control(
				'sp_next_icon',
				array(
					'label'                  => esc_html__( 'Next Icon', 'alpha-core' ),
					'type'                   => Controls_Manager::ICONS,
					'fa4compatibility'       => 'icon',
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-angle-right',
						'library' => '',
					),
					'recommended'            => array(
						'fa-solid'   => array(
							'chevron-down',
							'angle-down',
							'angle-double-down',
							'caret-down',
							'caret-square-down',
						),
						'fa-regular' => array(
							'caret-square-down',
						),
					),
					'exclude_inline_options' => array(
						'svg',
					),
					'skin'                   => 'inline',
					'label_block'            => false,
				)
			);

			$this->start_controls_tabs( 'sp_tabs' );
				$this->start_controls_tab(
					'sp_normal_tab',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'sp_i_color',
						array(
							'label'     => esc_html__( 'Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} i' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'sp_i_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} i' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'sp_i_bd_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} i' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();
				$this->start_controls_tab(
					'sp_hover_tab',
					array(
						'label' => esc_html__( 'Hover', 'alpha-core' ),
					)
				);

					$this->add_control(
						'sp_i_color_hover',
						array(
							'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} li:hover i' => 'color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'sp_i_bg_color_hover',
						array(
							'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} li:hover i' => 'background-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'sp_i_bd_color_hover',
						array(
							'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} li:hover i' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function get_prev_icon() {
		return $this->prev_icon;
	}

	public function get_next_icon() {
		return $this->next_icon;
	}

	protected function render() {
		/**
		 * Filters post products in single product builder
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {

			$settings = $this->get_settings_for_display();

			$this->prev_icon = ! empty( $settings['sp_prev_icon']['value'] ) ? $settings['sp_prev_icon']['value'] : 'a-icon-angle-left';
			$this->next_icon = ! empty( $settings['sp_next_icon']['value'] ) ? $settings['sp_next_icon']['value'] : 'a-icon-angle-right';

			add_filter( 'alpha_check_single_next_prev_nav', '__return_true' );
			add_filter( 'alpha_single_product_nav_prev_icon', array( $this, 'get_prev_icon' ) );
			add_filter( 'alpha_single_product_nav_next_icon', array( $this, 'get_next_icon' ) );

			echo '<div class="product-navigation">' . alpha_single_product_navigation() . '</div>';

			remove_filter( 'alpha_check_single_next_prev_nav', '__return_true' );
			remove_filter( 'alpha_single_product_nav_prev_icon', array( $this, 'get_prev_icon' ) );
			remove_filter( 'alpha_single_product_nav_next_icon', array( $this, 'get_next_icon' ) );

			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
