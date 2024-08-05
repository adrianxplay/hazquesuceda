<?php
/**
 * Text Marquee widget
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.9
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpha_Marquee_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_marquee';
	}

	public function get_title() {
		return esc_html__( 'Marquee', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-marquee';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'marquee', 'text', 'image' );
	}
	public function get_style_depends() {
		wp_register_style( 'alpha-marquee', alpha_core_framework_uri( '/widgets/marquee/marquee' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-marquee' );
	}

	protected function register_controls() {

        $this->start_controls_section(
			'section_marquee_content',
			array(
				'label' => esc_html__( 'Marquee Content', 'alpha-core' ),
			)
		);
        
        $this->add_control(
            'marquee_type',
            array(
                'label'       => esc_html__( 'Marquee Type', 'alpha-core' ),
                'description' => esc_html__( 'Select text or image type for marquee.', 'alpha-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'text',
                'options'     => array(
                    'text'  => esc_html__( 'Text', 'alpha-core' ),
                    'image' => esc_html__( 'Image', 'alpha-core' ),
                ),
            )
        );


		$this->add_control(
			'marquee_layout',
			array(
                'label'       => esc_html__( 'Marquee Layout', 'alpha-core' ),
                'description' => esc_html__( 'Select layout of marquee.', 'alpha-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'horizontal',
				'options' => array(
					'vertical'     => array(
						'title' => esc_html__( 'Vertical', 'alpha-core' ),
						'icon'  => 'eicon-navigation-vertical',
					),
					'horizontal' => array(
						'title' => esc_html__( 'Horizontal', 'alpha-core' ),
						'icon'  => 'eicon-navigation-horizontal',
					),
				),
				'condition' => array(
					'marquee_type' => 'image',
				),
			)
		);

		$this->add_control(
			'anim_direction',
			array(
                'label'       => esc_html__( 'Animation Direction', 'alpha-core' ),
                'description' => esc_html__( 'Select text marquee animation direction.', 'alpha-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'ltr',
				'options' => array(
					'ltr'     => array(
						'title' => esc_html__( 'Right to Left', 'alpha-core' ),
						'icon'  => 'eicon-arrow-left',
					),
					'rtl' => array(
						'title' => esc_html__( 'Left to Right', 'alpha-core' ),
						'icon'  => 'eicon-arrow-right',
					),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'marquee_type',
							'operator' => '===',
							'value'    => 'text',
						),
						array(
							'name'     => 'marquee_layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
					)
				),
			)
		);
		
		$this->add_control(
			'anim_direction2',
			array(
                'label'       => esc_html__( 'Animation Direction', 'alpha-core' ),
                'description' => esc_html__( 'Select text marquee animation direction.', 'alpha-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'up',
				'options' => array(
					'up'     => array(
						'title' => esc_html__( 'Bottom to Top', 'alpha-core' ),
						'icon'  => 'eicon-arrow-up',
					),
					'down' => array(
						'title' => esc_html__( 'Top to Bottom', 'alpha-core' ),
						'icon'  => 'eicon-arrow-down',
					),
				),
				'condition' => array(
					'marquee_type' => 'image',
					'marquee_layout' => 'vertical'
				),
			)
		);

		$this->add_control(
			'anim_speed',
			array(
                'label'       => esc_html__( 'Animation Speed (s)', 'alpha-core' ),
                'description' => esc_html__( 'Select the animation speed.', 'alpha-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '25',
				'selectors'   => array(
					'{{WRAPPER}} .marquee' => '--alpha-marquee-animation-duration: {{SIZE}}s;',
				),
			)
		);

		$this->add_control(
			'text_content',
			array(
				'label'       => esc_html__( 'Text Content', 'alpha-core' ),
				'description' => esc_html__( 'Type the text content for marquee.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Add your text content', 'alpha-core' ),
				'placeholder' => esc_html__( 'Enter your text content', 'alpha-core' ),
				'condition' => array(
					'marquee_type' => 'text',
				),
			)
		);

		$this->add_control(
			'marquee_images',
			array(
				'label'       => esc_html__( 'Add Images', 'alpha-core' ),
				'type'        => Controls_Manager::GALLERY,
				'default'     => array(),
				'show_label'  => false,
				'description' => esc_html__( 'Insert images from the library', 'alpha-core' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition' => array(
					'marquee_type' => 'image',
				),
			)
		);
        
        $this->add_control(
            'content_repeat',
            array(
                'label'       => esc_html__( 'Number of Content Repeats', 'alpha-core' ),
                'description' => esc_html__( 'Select how much do you want to repeat your content in marquee.', 'alpha-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '3',
                'options'     => array(
                    '3'  => esc_html__( '3', 'alpha-core' ),
                    '4'  => esc_html__( '4', 'alpha-core' ),
                    '5'  => esc_html__( '5', 'alpha-core' ),
                    '6'  => esc_html__( '6', 'alpha-core' ),
                    '7'  => esc_html__( '7', 'alpha-core' ),
                    '8'  => esc_html__( '8', 'alpha-core' ),
                ),
            )
        );

        $this->add_control(
            'text_type',
            array(
                'label'       => esc_html__( 'Text Type', 'alpha-core' ),
                'description' => esc_html__( 'Select the text type.', 'alpha-core' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'default',
                'options'     => array(
                    'default' => esc_html__( 'Default', 'alpha-core' ),
                    'outline' => esc_html__( 'Outline', 'alpha-core' ),
                ),
				'condition' => array(
					'marquee_type' => 'text',
				),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_marquee_style',
			array(
				'label' => esc_html__( 'Content Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		
			$this->add_responsive_control(
				'image_size',
				array(
					'label'       => esc_html__( 'Image Size', 'alpha-core' ),
					'description' => esc_html__( 'Type a certain number for marquee image size.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', '%' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 1000,
						),
						'%' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-image-size: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'marquee_type' => 'image',
					),
				)
			);

			$this->add_control(
				'text_color',
				array(
					'label'       => esc_html__( 'Text Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the text color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-color: {{VALUE}};',
					),
					'condition' => array(
						'marquee_type' => 'text',
						'text_type!' => 'outline',
					),
				)
			);

			$this->add_control(
				'text_hv_color',
				array(
					'label'       => esc_html__( 'Text Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the text hover color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-hover-color: {{VALUE}};',
					),
					'condition' => array(
						'marquee_type' => 'text',
						'text_type!' => 'outline',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'text_typography',
					'selector' => '{{WRAPPER}} .marquee .marquee-inner-content',
					'condition' => array(
						'marquee_type' => 'text',
					),
				)
			);
			
			
			$this->add_responsive_control(
				'content_spacing',
				array(
					'label'       => esc_html__( 'Space Between', 'alpha-core' ),
					'description' => esc_html__( 'Type a certain number for spacing between content.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem', 'em' ),
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-item-spacing: {{SIZE}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_text_outline_style',
			array(
				'label' => esc_html__( 'Outline Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'marquee_type' => 'text',
					'text_type' => 'outline',
				),
			)
		);		

			$this->add_control(
				'text_outline_width',
				array(
					'label'       => esc_html__( 'Outline Width (px)', 'alpha-core' ),
					'description' => esc_html__( 'Type a certain number for outline width.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-stroke-width: {{SIZE}}px;',
					),
				)
			);

			$this->start_controls_tabs( 'text_outline_style');

			$this->start_controls_tab(
				'text_outline_normal_style',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$this->add_control(
				'text_outline_color',
				array(
					'label'       => esc_html__( 'Outline Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the outline color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-stroke-color: {{VALUE}};',
					),
				)
			);
			
			$this->add_control(
				'text_outline_bg_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the text background color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-fill-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
			
			$this->start_controls_tab(
				'text_outline_hover_style',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$this->add_control(
				'text_outline_hv_color',
				array(
					'label'       => esc_html__( 'Outline Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the outline hover color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-stroke-hover-color: {{VALUE}};',
					),
				)
			);
			
			$this->add_control(
				'text_outline_hv_bg_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the text hover background color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'{{WRAPPER}} .marquee' => '--alpha-marquee-fill-hover-color: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/marquee/render-marquee-elementor.php' );
	}
}
