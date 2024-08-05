<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Alpha Testimonial Widget
 *
 * Alpha Widget to display testimonial.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Testimonial_Group_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_testimonial_group';
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-testimonial';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'testimonial', 'rating', 'comment', 'review', 'customer', 'slider', 'grid', 'group' );
	}

	/**
	 * Get Style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-testimonial', alpha_core_framework_uri( '/widgets/testimonial/testimonial' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-testimonial' );
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_testimonial_group',
			array(
				'label' => esc_html__( 'Testimonials', 'alpha-core' ),
			)
		);

			$repeater = new Repeater();

			alpha_elementor_testimonial_content_controls( $repeater );

			$presets = array(
				array(
					'name'          => esc_html__( 'John Doe', 'alpha-core' ),
					'role'          => esc_html__( 'Programmer', 'alpha-core' ),
					'comment_title' => '',
					'content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpha-core' ),
				),
				array(
					'name'          => esc_html__( 'Henry Harry', 'alpha-core' ),
					'role'          => esc_html__( 'Banker', 'alpha-core' ),
					'comment_title' => '',
					'content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpha-core' ),
				),
				array(
					'name'          => esc_html__( 'Tom Jakson', 'alpha-core' ),
					'role'          => esc_html__( 'Vendor', 'alpha-core' ),
					'comment_title' => '',
					'content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna.', 'alpha-core' ),
				),
			);

			$this->add_control(
				'testimonial_group_list',
				array(
					'label'   => esc_html__( 'Testimonial Group', 'alpha-core' ),
					'type'    => Controls_Manager::REPEATER,
					'fields'  => $repeater->get_controls(),
					'default' => $presets,
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_testimonials_layout',
			array(
				'label' => esc_html__( 'Testimonials Layout', 'alpha-core' ),
			)
		);

			$this->add_control(
				'layout_type',
				array(
					'label'       => esc_html__( 'Testimonials Layout', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'default'     => 'grid',
					'options'     => array(
						'grid'   => array(
							'title' => esc_html__( 'Grid', 'alpha-core' ),
							'icon'  => 'eicon-column',
						),
						'slider' => array(
							'title' => esc_html__( 'Slider', 'alpha-core' ),
							'icon'  => 'eicon-slider-3d',
						),
					),
					'qa_selector' => '.testimonial-group',
				)
			);

			alpha_elementor_grid_layout_controls( $this, 'layout_type' );

		$this->end_controls_section();

		$this->start_controls_section(
			'testimonial_general',
			array(
				'label' => esc_html__( 'Testimonial Type', 'alpha-core' ),
			)
		);

			alpha_elementor_testimonial_type_controls( $this );

			$this->add_control(
				'star_icon',
				array(
					'label'   => esc_html__( 'Star Icon', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						''        => 'Theme',
						'fa-icon' => 'Font Awesome',
					),
				)
			);

		$this->end_controls_section();

		alpha_elementor_testimonial_style_controls( $this );

		alpha_elementor_slider_style_controls( $this, 'layout_type' );
	}


	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/testimonial/render-testimonial-group-elementor.php' );
	}
}
