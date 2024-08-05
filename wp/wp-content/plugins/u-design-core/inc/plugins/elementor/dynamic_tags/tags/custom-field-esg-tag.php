<?php
/**
 * Alpha Dynamic Tags class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 * @version    4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Alpha_Core_Custom_Field_Esg_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'alpha-custom-field-esg';
	}

	public function get_title() {
		return esc_html__( 'Essential Grid', 'alpha-core' );
	}

	public function get_group() {
		return Alpha_Core_Dynamic_Tags::ALPHA_CORE_GROUP;
	}

	public function get_categories() {
		return array(
			Alpha_Core_Dynamic_Tags::TEXT_CATEGORY,
			Alpha_Core_Dynamic_Tags::NUMBER_CATEGORY,
			Alpha_Core_Dynamic_Tags::URL_CATEGORY,
			Alpha_Core_Dynamic_Tags::POST_META_CATEGORY,
			Alpha_Core_Dynamic_Tags::COLOR_CATEGORY,
		);
	}

	protected function register_controls() {

		$this->add_control(
			'dynamic_field_source',
			array(
				'label'   => esc_html__( 'Source', 'alpha-core' ),
				'type'    => Elementor\Controls_Manager::HIDDEN,
				'default' => 'esg',
			)
		);

		do_action( 'alpha_core_dynamic_before_render' );

		//Add esg field
		do_action( 'alpha_dynamic_extra_fields', $this, 'field', 'esg' );

		do_action( 'alpha_core_dynamic_after_render' );

	}

	public function render() {

		do_action( 'alpha_core_dynamic_before_render' );

		$post_id = get_the_ID();
		$atts    = $this->get_settings();
		$ret     = '';

		$ret = apply_filters( 'alpha_dynamic_extra_fields_content', null, $atts, 'field' );

		echo alpha_strip_script_tags( $ret );

		do_action( 'alpha_core_dynamic_after_render' );
	}
}
