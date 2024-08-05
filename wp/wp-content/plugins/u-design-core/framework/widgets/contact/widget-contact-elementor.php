<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Contact Widget
 *
 * Alpha Widget to display contact.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpha_Contact_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_contact';
	}

	public function get_title() {
		return esc_html__( 'Contact', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-widget-icon-call alpha-elementor-widget-icon';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'contact', 'link' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-contact', alpha_core_framework_uri( '/widgets/contact/contact' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-contact' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_contact_content',
			array(
				'label' => esc_html__( 'Contact Box', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
			$this->add_control(
				'contact_icon',
				array(
					'label'       => esc_html__( 'Contact Icon', 'alpha-core' ),
					'description' => esc_html__( 'Select contact icon from Icon Libary or SVG Icons.​', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
					'default'     => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-call',
						'library' => 'alpha-icons',
					),
				)
			);

			$this->add_control(
				'contact_link_text',
				array(
					'label'       => esc_html__( 'Live Chat Text', 'alpha-core' ),
					'description' => esc_html__( 'Set text for live chat.​', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Live Chat', 'alpha-core' ),
					'label_block' => true,
					'qa_selector' => '.contact .live-chat',
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$this->add_control(
				'link',
				array(
					'label'       => esc_html__( 'Live Chat Link', 'alpha-core' ),
					'description' => esc_html__( 'Set link url for live chat.​', 'alpha-core' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => 'mailto://youremail',
				)
			);

			$this->add_control(
				'contact_telephone',
				array(
					'label'       => esc_html__( 'Telephone Number', 'alpha-core' ),
					'description' => esc_html__( 'Set telephone number.​', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( '0(800)123-456', 'alpha-core' ),
					'label_block' => true,
					'qa_selector' => '.contact .telephone',
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$this->add_control(
				'contact_telephone_link',
				array(
					'label'       => esc_html__( 'Telephone Link', 'alpha-core' ),
					'description' => esc_html__( 'Set telephone link.​', 'alpha-core' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => 'tel://1234567890',
				)
			);

			$this->add_control(
				'contact_delimiter',
				array(
					'label'       => esc_html__( 'Delimiter', 'alpha-core' ),
					'description' => esc_html__( 'Set delimiter text between live chat and telephone.​', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'or:', 'alpha-core' ),
					'condition'   => array(
						'contact_link_text!' => '',
						'contact_telephone!' => '',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_icon_style',
			array(
				'label' => esc_html__( 'Contact Icon', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'icon_font_size',
				array(
					'label'       => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'description' => esc_html__( 'Set size of contact icon.​', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact i, .elementor-element-{{ID}} .contact svg' => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .contact' => '--alpha-contact-icon-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'icon_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Set color of contact icon.​', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact i' => 'color: {{VALUE}};',
						'.elementor-element-{{ID}} .contact svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_padding',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Set padding of contact icon.​', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', 'rem' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact i, .elementor-element-{{ID}} .contact svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
		$this->end_controls_section();
		$this->start_controls_section(
			'contact_link_style',
			array(
				'label' => esc_html__( 'Live Chat', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'link_typography',
					'selector' => '.elementor-element-{{ID}} .contact-content .live-chat',
				)
			);

			$this->add_control(
				'live_chat_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Set color of live chat link.​', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact-content .live-chat' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'live_chat_hover_color',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Set hover color of live chat link.​', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact-content .live-chat:hover' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
		$this->start_controls_section(
			'contact_telephone_style',
			array(
				'label' => esc_html__( 'Telephone', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'telephone_typography',
					'selector' => '.elementor-element-{{ID}} .contact-content .telephone',
				)
			);

			$this->add_control(
				'telephone_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Set color of telephone number.​', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact-content .telephone' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'telephone_hover_color',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Set hover color of contact icon.​', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact:hover .telephone, .elementor-element-{{ID}} .contact:hover i' => 'color: {{VALUE}};',
						'.elementor-element-{{ID}} .contact:hover svg' => 'fill: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
		$this->start_controls_section(
			'contact_delimiter_style',
			array(
				'label' => esc_html__( 'Delimiter', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'delimiter_typography',
					'selector' => '.elementor-element-{{ID}} .contact-content .contact-delimiter',
				)
			);

			$this->add_control(
				'delimiter_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Set color of delimiter.​', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .contact-content .contact-delimiter' => 'color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'live_chat'      => $settings['contact_link_text'],
			'live_chat_link' => $settings['link'],
			'tel_num'        => $settings['contact_telephone'],
			'tel_num_link'   => $settings['contact_telephone_link'],
			'delimiter'      => $settings['contact_delimiter'],
			'icon'           => $settings['contact_icon'],
			'self'           => $this,
		);
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/contact/render-contact-elementor.php' );
	}
}
