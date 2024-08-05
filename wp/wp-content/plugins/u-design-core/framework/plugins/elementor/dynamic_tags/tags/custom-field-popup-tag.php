<?php
/**
 * Alpha Dynamic Tags class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.3.0
 */

use Elementor\Alpha_Controls_Manager;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Alpha_Core_Custom_Field_Popup_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'alpha-custom-field-popup';
	}

	public function get_title() {
		return esc_html__( 'Popup', 'alpha-core' );
	}

	public function get_group() {
		return Alpha_Core_Dynamic_Tags::ALPHA_CORE_GROUP;
	}

	public function get_categories() {
		return array(
			Alpha_Core_Dynamic_Tags::URL_CATEGORY,
		);
	}

	protected function register_advanced_section() {}

	protected function register_controls() {
		$this->add_control(
			'dynamic_popup_template',
			array(
				'label'       => esc_html__( 'Popup', 'alpha-core' ),
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'options'     => 'popup',
				'label_block' => true,
			)
		);
	}

	public function render() {

		wp_enqueue_style( 'alpha-magnific-popup' );
		wp_enqueue_script( 'alpha-magnific-popup' );

		$atts     = $this->get_settings();
		$popup_id = $atts['dynamic_popup_template'];
		$href     = '#' . ALPHA_NAME . '-action:popup-id-' . $popup_id;

		add_filter( 'wpforms_global_assets', '__return_true' );

		echo alpha_escaped( $href );
	}
}
