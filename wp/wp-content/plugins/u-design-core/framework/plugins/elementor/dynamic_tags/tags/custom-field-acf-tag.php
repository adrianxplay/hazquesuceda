<?php
/**
 * Alpha Dynamic Tags class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Alpha_Core_Custom_Field_Acf_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'alpha-custom-field-acf';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'alpha-core' );
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
				'default' => 'acf',
			)
		);
		/**
		 * Fires before set current post type.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_core_dynamic_before_render' );

		//Add acf field
		do_action( 'alpha_dynamic_extra_fields', $this, 'field', 'acf' );

		do_action( 'alpha_core_dynamic_after_render' );

	}

	public function render() {

		if ( is_404() ) {
			return;
		}

		/**
		 * Fires before set current post type.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_core_dynamic_before_render' );

		$post_id = get_the_ID();
		$atts    = $this->get_settings();
		$ret     = '';

		/**
		 * Filters the content for dynamic extra fields.
		 *
		 * @since 1.0
		 */
		$ret = apply_filters( 'alpha_dynamic_extra_fields_content', null, $atts, 'field' );

		if ( ! is_wp_error( $ret ) ) {
			echo alpha_strip_script_tags( $ret );
		}

		do_action( 'alpha_core_dynamic_after_render' );
	}
}
