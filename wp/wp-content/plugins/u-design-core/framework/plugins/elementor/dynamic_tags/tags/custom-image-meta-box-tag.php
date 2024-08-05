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

class Alpha_Core_Custom_Image_Meta_Box_Tag extends Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'alpha-custom-image-meta-box';
	}

	public function get_title() {
		return esc_html__( 'Meta Box', 'alpha-core' );
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
				'default' => 'meta-box',
			)
		);

		/**
		 * Fires before set current post type.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_core_dynamic_before_render' );

		//Add acf field
		do_action( 'alpha_dynamic_extra_fields', $this, 'image', 'meta-box' );

		/**
		 * Fires after set current post type.
		 *
		 * @since 1.0
		 */
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

		if ( is_404() ) {
			return;
		}

		/**
		 * Fires before set current post type.
		 *
		 * @since 1.0
		 */
		do_action( 'alpha_core_dynamic_before_render' );

		$image_id = '';
		$atts     = $this->get_settings();

		/**
		 * Filters the content for dynamic extra fields.
		 *
		 * @since 1.0
		 */
		$image_id = apply_filters( 'alpha_dynamic_extra_fields_content', null, $atts, 'image' );
		if ( is_array( $image_id ) && count( $image_id ) ) {
			$image_id = $image_id[0];
		}

		/**
		 * Fires after set current post type.
		 *
		 * @since 1.0
		 */
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
