<?php
/**
  * Alpha Elementor Single Post Flash Sale Widget
  *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;

class Alpha_Single_Comments_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_single_comments';
	}

	public function get_title() {
		return esc_html__( 'Post Comments', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-comments';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'comments', 'post comments', 'discussion' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_single_author',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'heading_separator_style',
				array(
					'label' => esc_html__( 'Comments Separator', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_control(
				'comment_between_spacing',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => __( 'Between Spacing (px)', 'alpha-core' ),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .comments, .elementor-element-{{ID}} .comments+.comment-respond' => 'padding-top: {{SIZE}}px;',
						'.elementor-element-{{ID}} .comments' => 'padding-bottom: {{SIZE}}px;',
					),
				)
			);
			$this->add_control(
				'comment_separator_size',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => __( 'Separator Thickness (px)', 'alpha-core' ),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .comments' => 'border-width: {{SIZE}}px;',
					),
				)
			);

			$this->add_control(
				'comment_separator_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comments' => 'border-color: {{VALUE}}',
					),
					'separator' => 'after',
				)
			);

			$this->add_control(
				'heading_name_style',
				array(
					'label' => esc_html__( 'Commenter Name', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'comment_name',
					'selector' => '.elementor-element-{{ID}} .comment-name',
				)
			);

			$this->add_control(
				'comment_name_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-name a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'comment_name_h_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-name a:hover,.elementor-element-{{ID}} .comment-name a:focus' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'heading_date_style',
				array(
					'label'     => esc_html__( 'Comment Date', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'comment_date',
					'selector' => '.elementor-element-{{ID}} .comment-date',
				)
			);

			$this->add_control(
				'comment_date_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-date' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'heading_text_style',
				array(
					'label'     => esc_html__( 'Comment Text', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'comment_text',
					'selector' => '.elementor-element-{{ID}} .comment-text p',
				)
			);

			$this->add_control(
				'comment_text_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-text p' => 'color: {{VALUE}}',
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
			comments_template();
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}
