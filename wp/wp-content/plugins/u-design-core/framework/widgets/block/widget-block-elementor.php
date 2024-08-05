<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Block Widget
 *
 * Alpha Widget to display custom block.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

class Alpha_Block_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_block';
	}

	public function get_title() {
		return esc_html__( 'Block', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'block' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-block';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_block',
			array(
				'label' => esc_html__( 'Block', 'alpha-core' ),
			)
		);

			$this->add_control(
				'name',
				array(
					'label'       => esc_html__( 'Select a Block', 'alpha-core' ),
					'description' => esc_html__( 'Choose your favourite block from pre-built blocks.', 'alpha-core' ),
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'options'     => 'block',
					'label_block' => true,
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/block/render-block.php' );
	}

	protected function content_template() {}
}
