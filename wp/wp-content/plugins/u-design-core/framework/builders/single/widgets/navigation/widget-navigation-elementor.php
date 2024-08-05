<?php
/**
 * Alpha Elementor Single Post Prev-Next Navigation Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;

class Alpha_Single_Navigation_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_single_navigation';
	}

	public function get_title() {
		return esc_html__( 'Post Navigation', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-post-navigation';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'navigation', 'prev', 'next' );
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
		$right = is_rtl() ? 'left' : 'right';

		$this->start_controls_section(
			'section_single_navigation',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sb_size',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => esc_html__( 'Icon Size', 'alpha-core' ),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .post-navigation .label:before' => 'font-size: {{SIZE}}px',
					),
				)
			);

			$this->add_control(
				'heading_label_style',
				array(
					'label'     => esc_html__( 'Label', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'label_typo',
					'selector' => '.elementor-element-{{ID}} .label',
				)
			);

			$this->add_control(
				'label_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .navigation .nav-links .label' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'heading_title_style',
				array(
					'label'     => esc_html__( 'Title', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typo',
					'selector' => '.elementor-element-{{ID}} .pager-link-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .pager-link-title' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'title_h_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} a:hover .pager-link-title' => 'color: {{VALUE}}',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_builder_set_preview', false ) ) {
			alpha_get_template_part( 'posts/single/post-navigation' );
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}
