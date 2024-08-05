<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Pagination Widget
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

class Alpha_Shop_Pagination_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_shop_widget_pagination';
	}

	public function get_title() {
		return esc_html__( 'Pagination', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_shop_widget' );
	}

	public function get_keywords() {
		return array( 'pagination', 'shop', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-number-field';
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_result',
			array(
				'label' => esc_html__( 'Page Item', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'text_typography',
					'selector' => '.elementor-element-{{ID}} .page-numbers:not(.next, .prev), .elementor-element-{{ID}} .page-numbers i',
				)
			);

			$this->add_responsive_control(
				'btn_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_btn_cat' );

			$this->start_controls_tab(
				'tab_btn_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$this->add_control(
				'btn_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'btn_back_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'btn_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_btn_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$this->add_control(
				'btn_color_hover',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers:hover, .elementor-element-{{ID}} .page-numbers.current' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'btn_back_color_hover',
				array(
					'label'     => esc_html__( 'Hover Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers:hover, .elementor-element-{{ID}} .page-numbers.current' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'btn_border_color_hover',
				array(
					'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers:hover, .elementor-element-{{ID}} .page-numbers.current' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'item_space',
				array(
					'label'     => esc_html__( 'Space', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .page-numbers:not(:last-child)' => "margin-{$right}: {{SIZE}}px",
					),
				)
			);

		$this->end_controls_section();

	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		global $wp_query;

		if ( apply_filters( 'alpha_shop_builder_set_preview', false ) ) {
			if ( 1 != $wp_query->max_num_pages ) {
				woocommerce_pagination();
			} else {
				echo '<div class="pagination d-none"></div>';
			}
		}
		do_action( 'alpha_shop_builder_unset_preview' );
	}

	protected function content_template() {}
}
