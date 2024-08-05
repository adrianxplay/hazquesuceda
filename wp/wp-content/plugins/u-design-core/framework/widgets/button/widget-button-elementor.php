<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Button Widget
 *
 * Alpha Widget to display button.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;


class Alpha_Button_Elementor_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return ALPHA_NAME . '_widget_button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'Button', 'link', 'alpha' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-button';
	}

	public function get_script_depends() {
		return array();
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button Options', 'alpha-core' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'label'       => esc_html__( 'Text', 'alpha-core' ),
				'description' => esc_html__( 'Type text that will be shown on button.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Click here', 'alpha-core' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Button Url', 'alpha-core' ),
				'description' => esc_html__( 'Input URL where you will move when button is clicked.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'button_expand',
			array(
				'label'       => esc_html__( 'Expand', 'alpha-core' ),
				'description' => esc_html__( 'Makes button\'s width 100% full.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
			)
		);

		$this->add_responsive_control(
			'button_align',
			array(
				'label'       => esc_html__( 'Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Controls button\'s alignment. Choose from Left, Center, Right.', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .elementor-widget-container' => 'text-align: {{VALUE}}',
				),
				'condition'   => array(
					'button_expand!' => 'yes',
				),
			)
		);

		alpha_elementor_button_layout_controls( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_button',
			array(
				'label' => esc_html__( 'Video Options', 'alpha-core' ),
			)
		);

		$this->add_control(
			'play_btn',
			array(
				'label'       => esc_html__( 'Use as a play button in elementor section', 'alpha-core' ),
				'description' => esc_html__( 'You can play video whenever you enable video option in parent elementor section widget using as banner.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off', 'alpha-core' ),
				'label_on'    => esc_html__( 'On', 'alpha-core' ),
				'condition'   => array(
					'video_btn' => '',
				),
			)
		);

		$this->add_control(
			'video_btn',
			array(
				'label'       => esc_html__( 'Use as video button', 'alpha-core' ),
				'description' => esc_html__( 'You can play video on lightbox.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off', 'alpha-core' ),
				'label_on'    => esc_html__( 'On', 'alpha-core' ),
				'default'     => '',
				'condition'   => array(
					'play_btn' => '',
				),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => esc_html__( 'Video url', 'alpha-core' ),
				'description' => esc_html__( 'Type a certain URL of a video you want to upload.', 'alpha-core' ),
				'type'        => Controls_Manager::URL,
				'condition'   => array(
					'video_btn' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this );
	}

	public function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		$this->add_inline_editing_attributes( 'label' );
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/button/render-button-elementor.php' );
	}
}
