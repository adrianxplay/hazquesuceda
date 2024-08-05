<?php
/**
 * Alpha Elementor Single Post Prev-Next Navigation Widget
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
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
			'section_single_navigation_icon',
			array(
				'label' => esc_html__( 'Icon', 'alpha-core' ),
			)
		);

			$this->add_control(
				'icon_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} a i, .elementor-element-{{ID}} .post-nav-blog' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'icon_hover_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} a:hover i, .elementor-element-{{ID}} .post-nav-blog:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'sp_size',
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
						'.elementor-element-{{ID}} .navigation .nav-links i, .elementor-element-{{ID}} .navigation .post-nav-blog' => 'font-size: {{SIZE}}px',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_item_color' );
				$this->start_controls_tab(
					'tab_item_prev',
					array(
						'label' => esc_html__( 'Prev', 'alpha-core' ),
					)
				);
					$this->add_control(
						'prev_icon',
						array(
							'label' => esc_html__( 'Prev Icon', 'alpha-core' ),
							'type'  => Controls_Manager::ICONS,
						)
					);
				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_item_next',
					array(
						'label' => esc_html__( 'Next', 'alpha-core' ),
					)
				);
					$this->add_control(
						'next_icon',
						array(
							'label' => esc_html__( 'Next Icon', 'alpha-core' ),
							'type'  => Controls_Manager::ICONS,
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_single_navigation_text',
			array(
				'label' => esc_html__( 'Text', 'alpha-core' ),
			)
		);

			$this->add_control(
				'show_texts',
				array(
					'label' => esc_html__( 'Show Texts', 'alpha-core' ),
					'type'  => Controls_Manager::SWITCHER,
				)
			);

			$this->add_control(
				'heading_label_style',
				array(
					'label'     => esc_html__( 'Label', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'show_texts' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'label_typo',
					'selector'  => '.elementor-element-{{ID}} .navigation .nav-links .label',
					'condition' => array(
						'show_texts' => 'yes',
					),
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
					'condition' => array(
						'show_texts' => 'yes',
					),
				)
			);

			$this->add_control(
				'heading_title_style',
				array(
					'label'     => esc_html__( 'Title', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'show_texts' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'title_typo',
					'selector'  => '.elementor-element-{{ID}} .pager-link-title',
					'condition' => array(
						'show_texts' => 'yes',
					),
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} a .pager-link-title' => 'color: {{VALUE}}',
					),
					'condition' => array(
						'show_texts' => 'yes',
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
					'condition' => array(
						'show_texts' => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		if ( apply_filters( 'alpha_single_builder_set_preview', false ) ) {
			$atts = $this->get_settings_for_display();
			alpha_get_template_part(
				'posts/single/post',
				'navigation',
				array(
					'prev_icon'  => $atts['prev_icon']['value'],
					'next_icon'  => $atts['next_icon']['value'],
					'show_texts' => 'yes' == $atts['show_texts'] ? true : false,
				)
			);
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}
