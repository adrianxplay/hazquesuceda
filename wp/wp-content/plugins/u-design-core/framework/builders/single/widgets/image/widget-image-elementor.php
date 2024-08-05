<?php
/**
 * Alpha Elementor Single Post Image Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

class Alpha_Single_Image_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_single_image';
	}

	public function get_title() {
		return esc_html__( 'Featured Image', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-featured-image';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'image', 'thumbnail', 'gallery' );
	}

	public function get_script_depends() {
		$depends = array( 'swiper' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_single_image',
			array(
				'label' => esc_html__( 'Featured Media', 'alpha-core' ),
			)
		);

			$this->add_group_control(
				Elementor\Group_Control_Image_Size::get_type(),
				array(
					'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
					'exclude' => array( 'custom' ),
					'default' => 'full',
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_builder_set_preview', false ) ) {
			alpha_set_loop_prop( 'single_image_size', $this->get_settings_for_display( 'thumbnail_size' ) );
			alpha_get_template_part( 'posts/single/post', 'media' );
			alpha_reset_loop();
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}
