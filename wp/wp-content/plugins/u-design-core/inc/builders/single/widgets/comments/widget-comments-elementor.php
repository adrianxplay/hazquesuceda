<?php
/**
  * Alpha Elementor Single Post Comments Widget
  *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.3
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
			'section_comment_base',
			array(
				'label' => esc_html__( 'Base Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'heading_title_style',
				array(
					'label' => esc_html__( 'Title', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'comment_title',
					'selector' => '.elementor-element-{{ID}} .comment-respond .comment-reply-title',
				)
			);

			$this->add_control(
				'comment_title_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-respond .comment-reply-title' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'heading_desc_style',
				array(
					'label'     => esc_html__( 'Description', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'comment_desc',
					'selector' => '.elementor-element-{{ID}} .comment-respond p',
				)
			);

			$this->add_control(
				'comment_desc_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-respond p' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'comment_desc_margin',
				array(
					'label'      => esc_html__( 'Margin', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'{{WRAPPER}} .comment-respond p:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_single_author',
			array(
				'label' => esc_html__( 'Comments Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_control(
				'heading_avatar_style',
				array(
					'label' => esc_html__( 'Commenter Avatar', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$this->add_responsive_control(
				'comment_avatar_size',
				array(
					'label'      => esc_html__( 'Avatar Size', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'size_units' => array(
						'px',
						'%',
						'rem',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .comment .comment-avatar' => 'max-width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .comment-avatar img' => 'height: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$this->add_control(
				'heading_name_style',
				array(
					'label'     => esc_html__( 'Commenter Name', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
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

			$this->add_control(
				'heading_separator_style',
				array(
					'label'     => esc_html__( 'Comments Form', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'comment_form_typo',
					'selector' => '{{WRAPPER}} .form-control',
				)
			);

			$this->add_control(
				'comment_form_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-form .form-control' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'comment_form_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-form .form-control' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'comment_form_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-form .form-control' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'comment_form_focus_border_color',
				array(
					'label'     => esc_html__( 'Focus Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .comment-form .form-control:focus' => 'border-color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'comment_form_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .comment-form .form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

			$this->add_responsive_control(
				'comment_form_border_width',
				array(
					'label'      => esc_html__( 'Border Width', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
						'em',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .comment-form .form-control' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this, array(), esc_html__( 'Submit Style', 'alpha-core' ), '', false, true, false );
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

// Update style option
add_action(
	'elementor/element/' . ALPHA_NAME . '_single_comments/section_button_style/before_section_end',
	function( $self, $args ) {
		$self->add_responsive_control(
			'comment_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'btn_padding',
				),
			)
		);
	},
	10,
	2
);
