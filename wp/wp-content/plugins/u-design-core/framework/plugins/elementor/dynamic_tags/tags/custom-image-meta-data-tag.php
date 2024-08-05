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

class Alpha_Core_Custom_Image_Meta_Data_Tag extends Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'alpha-custom-image-meta-data';
	}

	public function get_title() {
		return esc_html__( 'Meta Data', 'alpha-core' );
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
			'dynamic_image_custom_meta_key',
			array(
				'label'       => esc_html__( 'Custom meta key', 'alpha-core' ),
				'type'        => Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);
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
		$post_id  = get_the_ID();
		$atts     = $this->get_settings();

		if ( isset( $atts['dynamic_image_custom_meta_key'] ) && $atts['dynamic_image_custom_meta_key'] ) {
			$meta_key = $atts['dynamic_image_custom_meta_key'];
			$image_id = get_post_meta( $post_id, $meta_key, true );
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

		$img_arr = wp_get_attachment_image_src( $image_id, 'full' );

		return array(
			'id'  => $image_id,
			'url' => $img_arr && ! is_wp_error( $img_arr ) ? $img_arr[0] : '',
		);
	}
}
