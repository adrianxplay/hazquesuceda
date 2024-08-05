<?php
/**
 * Alpha Elementor Single Author Box Widget
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

class Alpha_Single_Author_Box_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_single_author_box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-person';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'author', 'author info', 'author box' );
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
			'section_single_author',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'sb_author_description',
				array(
					'raw'             => sprintf( esc_html__( 'Please input <Biographical Info> in %1$sUser Profile%2$s', 'alpha-core' ), '<a href="' . admin_url( 'profile.php' ) . '" target="_blank">', '</a>.' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'alpha-notice notice-warning',
				)
			);

			$this->add_responsive_control(
				'author_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Set custom padding of author box.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
						'rem'
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .post-author-detail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'sp_align',
				array(
					'label'     => esc_html__( 'Align', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'    => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'  => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'   => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
						'justify' => array(
							'title' => esc_html__( 'Justify', 'alpha-core' ),
							'icon'  => 'eicon-text-align-justify',
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .post-author-detail' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'author_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-author-detail' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'author_shadow',
					'selector' => '.elementor-element-{{ID}} .post-author-detail',
				)
			);

			$this->add_control(
				'heading_avatar_style',
				array(
					'label'     => esc_html__( 'Author Avatar', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);
			
			$this->add_responsive_control(
				'author_avatar_size',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => esc_html__( 'Size', 'alpha-core' ),
					'size_units'  => array(
						'px',
						'%',
						'em',
						'rem',
						'vw',
					),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .author-avatar' => 'max-width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			
			$this->add_control(
				'author_avatar_radius',
				array(
					'label'       => esc_html__( 'Border radius', 'alpha-core' ),
					'description' => esc_html__( 'Set border radius of author avatar.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
						'rem'
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .author-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'author_avatar_margin',
				array(
					'label'       => esc_html__( 'Margin', 'alpha-core' ),
					'description' => esc_html__( 'Set custom margin of author avatar.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
						'rem'
					),
					'selectors'   => array(
						'{{WRAPPER}} .post-author-detail .author-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'heading_name_style',
				array(
					'label'     => esc_html__( 'Author Name', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'author_name',
					'selector' => '.elementor-element-{{ID}} .post-author-detail .author-name',
				)
			);

			$this->add_control(
				'author_name_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .author-name' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'author_header_margin',
				array(
					'label'       => esc_html__( 'Margin', 'alpha-core' ),
					'description' => esc_html__( 'Set custom margin of author name.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
						'rem'
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'heading_desc_style',
				array(
					'label'     => esc_html__( 'Author Description', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'author_desc',
					'selector' => '.elementor-element-{{ID}} .author-content',
				)
			);

			$this->add_control(
				'author_desc_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .author-content' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'heading_link_style',
				array(
					'label'     => esc_html__( 'Author Link', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'author_link',
					'selector' => '.elementor-element-{{ID}} .author-link',
				)
			);

			$this->add_control(
				'author_link_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .author-link' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'author_link_h_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .post-author-detail .author-link:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'author_link_margin',
				array(
					'label'       => esc_html__( 'Margin', 'alpha-core' ),
					'description' => esc_html__( 'Set custom margin of author link.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
						'em',
						'rem'
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .author-body .author-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			alpha_get_template_part( 'posts/single/post', 'author' );
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}