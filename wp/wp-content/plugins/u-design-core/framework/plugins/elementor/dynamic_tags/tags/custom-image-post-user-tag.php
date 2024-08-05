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

class Alpha_Core_Custom_Image_Post_User_Tag extends Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'alpha-custom-image-post-user';
	}

	public function get_title() {
		return esc_html__( 'Posts / Users', 'alpha-core' );
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
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'featured',
				'options' => $this->get_objects(),
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

		do_action( 'alpha_core_dynamic_before_render' );

		$image_id  = '';
		$image_url = '';
		$atts      = $this->get_settings();

		switch ( $atts['dynamic_field_source'] ) {

			case 'featured':
				global $post;

				$image_id = get_post_thumbnail_id( $post->ID );

				if ( ! $image_id ) {
					$gallery = get_post_meta( $post->ID, 'supported_images' );
					if ( is_array( $gallery ) && count( $gallery ) ) {
						$image_id = $gallery[0];
					}
				}
				break;
			case 'user_avatar':
				$current_user = wp_get_current_user();
				if ( $current_user ) {
					$image_url = get_avatar_url( $current_user->ID );
				}
				break;
		}

		do_action( 'alpha_core_dynamic_after_render' );

		if ( ! $image_id && ! $image_url ) {
			return $atts['fallback'];
		}

		return array(
			'id'  => $image_id,
			'url' => $image_id ? wp_get_attachment_image_src( $image_id, 'full' )[0] : $image_url,
		);
	}

	public function get_objects() {
		$objects = array(
			'featured'    => esc_html__( 'Featured Image', 'alpha-core' ),
			'user_avatar' => esc_html__( 'User Avatar', 'alpha-core' ),
		);

		return $objects;
	}
}
