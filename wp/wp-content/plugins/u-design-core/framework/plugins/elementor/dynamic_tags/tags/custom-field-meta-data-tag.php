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

class Alpha_Core_Custom_Field_Meta_Data_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'alpha-custom-field-meta-data';
	}

	public function get_title() {
		return esc_html__( 'Meta Data', 'alpha-core' );
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
			'dynamic_field_custom_meta_key',
			array(
				'label'       => esc_html__( 'Custom meta key', 'alpha-core' ),
				'type'        => Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);
	}

	public function render() {

		if ( is_404() ) {
			return;
		}

		do_action( 'alpha_core_dynamic_before_render' );

		$post_id = get_the_ID();
		$atts    = $this->get_settings();
		$ret     = '';

		if ( isset( $atts['dynamic_field_custom_meta_key'] ) && $atts['dynamic_field_custom_meta_key'] ) {
			$meta_key = $atts['dynamic_field_custom_meta_key'];
			$ret      = get_post_meta( $post_id, $meta_key, true );
		}

		if ( ! is_wp_error( $ret ) ) {
			echo alpha_strip_script_tags( $ret );
		}

		do_action( 'alpha_core_dynamic_after_render' );
	}
}
