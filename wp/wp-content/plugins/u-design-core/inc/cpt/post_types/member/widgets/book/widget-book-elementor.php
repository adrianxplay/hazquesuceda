<?php
/**
 * Alpha Elementor Single Book Widget
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Single_Book_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_single_book';
	}

	public function get_title() {
		return esc_html__( 'Book', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-kit-details';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'book', 'appointment' );
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
			'section_single_book',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'book_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Set custom padding of book now button.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'book_align',
				array(
					'label'     => esc_html__( 'Align', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
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
					'selectors' => array(
						'.elementor-element-{{ID}} .mini-basket-box' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'book-now',
					'selector' => '.elementor-element-{{ID}} .mini-basket-box.offcanvas-type .btn.btn-appointment',
				)
			);

			$this->add_responsive_control(
				'book_border_width',
				array(
					'label'       => esc_html__( 'Border Width', 'alpha-core' ),
					'description' => esc_html__( 'Controls border width of book now button.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					),
				)
			);
			$this->start_controls_tabs( 'book_tabs_btn_cat' );

			$this->start_controls_tab(
				'book_tab_btn_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$this->add_control(
				'book_btn_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of the button.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment' => 'color: {{VALUE}};fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'book_btn_back_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the background color of the button.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'book_btn_border_color',
				array(
					'label'       => esc_html__( 'Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the border color of the button.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'book_tab_btn_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$this->add_control(
				'book_btn_color_hover',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover color of the button.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'book_btn_back_color_hover',
				array(
					'label'       => esc_html__( 'Hover Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover background color of the button.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'book_btn_border_color_hover',
				array(
					'label'       => esc_html__( 'Hover Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover border color of the button.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-appointment:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		require ALPHA_CORE_INC . '/cpt/post_types/member/templates/posts/single/member-appointment.php';
	}
}
