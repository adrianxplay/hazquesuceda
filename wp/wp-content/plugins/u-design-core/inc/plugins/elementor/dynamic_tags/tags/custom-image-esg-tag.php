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

class Alpha_Core_Custom_Image_Esg_Tag extends Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'alpha-custom-image-esg';
	}

	public function get_title() {
		return esc_html__( 'Essential Grid', 'alpha-core' );
	}

	public function get_group() {
		return Alpha_Core_Dynamic_Tags::ALPHA_CORE_GROUP;
	}

	public function get_categories() {
		return array(
			Alpha_Core_Dynamic_Tags::IMAGE_CATEGORY,
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
		do_action( 'alpha_dynamic_extra_fields', $this, 'image', 'esg' );

		do_action( 'alpha_core_dynamic_after_render' );
	}

	public function register_advanced_section() {
		$this->start_controls_section(
			'advanced',
			array(
				'label' => esc_html__( 'Advanced', 'alpha-core' ),
			)
		);

		$this->add_control(
			'fallback',
			array(
				'label' => esc_html__( 'Fallback', 'alpha-core' ),
				'type'  => Elementor\Controls_Manager::MEDIA,
			)
		);

		$this->end_controls_section();
	}

	public function get_value( array $options = array() ) {

		do_action( 'alpha_core_dynamic_before_render' );

		$image_id = '';
		$atts     = $this->get_settings();

		$image_id = apply_filters( 'alpha_dynamic_extra_fields_content', null, $atts, 'image' );

		do_action( 'alpha_core_dynamic_after_render' );

		if ( ! $image_id ) {
			return $atts['fallback'];
		}

		return array(
			'id'  => $image_id,
			'url' => wp_get_attachment_image_src( $image_id, 'full' )[0],
		);
	}

}
