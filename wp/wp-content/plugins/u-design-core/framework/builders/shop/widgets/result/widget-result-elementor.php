<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Product Result Widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;


class Alpha_Shop_Result_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_shop_widget_results';
	}

	public function get_title() {
		return esc_html__( 'Result Count', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_shop_widget' );
	}

	public function get_keywords() {
		return array( 'product-result', 'shop', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-search-results';
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
			'section_result',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'text_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls color of text.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .show-info' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'number_color',
				array(
					'label'       => esc_html__( 'Info Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls color of info result text.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .show-info span' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'text_typography',
					'selector' => '.elementor-element-{{ID}} .show-info',
				)
			);

		$this->end_controls_section();

	}

	protected function render() {
		$atts = $this->get_settings_for_display();

		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_shop_builder_set_preview', false ) ) {
			woocommerce_result_count();
		}
		do_action( 'alpha_shop_builder_unset_preview' );
	}

	protected function content_template() {}
}
