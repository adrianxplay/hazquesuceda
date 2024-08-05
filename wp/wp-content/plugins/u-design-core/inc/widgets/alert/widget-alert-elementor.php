<?php
/**
 * Alert Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use ELementor\Group_Control_Background;
use Elementor\Group_Control_Border;

class Alpha_Alert_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_alert';
	}

	public function get_title() {
		return esc_html__( 'Alert', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-alert';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'message' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-alert', ALPHA_CORE_INC_URI . '/widgets/alert/alert' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		return array( 'alpha-alert' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

		$this->add_control(
			'alert_skin',
			array(
				'label'       => esc_html__( 'Skin', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'primary',
				'description' => esc_html__( 'Choose color skin of alert.', 'alpha-core' ),
				'options'     => array(
					'default'   => esc_html__( 'Default', 'alpha-core' ),
					'primary'   => esc_html__( 'Primary', 'alpha-core' ),
					'secondary' => esc_html__( 'Secondary', 'alpha-core' ),
					'dark'      => esc_html__( 'Dark', 'alpha-core' ),
					'accent'    => esc_html__( 'Accent', 'alpha-core' ),
					'success'   => esc_html__( 'Success', 'alpha-core' ),
					'info'      => esc_html__( 'Info', 'alpha-core' ),
					'warning'   => esc_html__( 'Warning', 'alpha-core' ),
					'danger'    => esc_html__( 'Danger', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'alert_type',
			array(
				'label'       => esc_html__( 'Type', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'description' => esc_html__( 'Choose alert type. Choose from Default, Solid and Outline.', 'alpha-core' ),
				'options'     => array(
					''        => esc_html__( 'Default', 'alpha-core' ),
					'solid'   => esc_html__( 'Solid', 'alpha-core' ),
					'outline' => esc_html__( 'Outline', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title & Description', 'alpha-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => esc_html__( 'Input title and description of alert.', 'alpha-core' ),
				'placeholder' => esc_html( 'Enter your title', 'alpha-core' ),
				'default'     => esc_html( 'This is an Alert', 'alpha-core' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'type'        => 'wysiwyg',
				'placeholder' => esc_html__( 'Enter your description', 'alpha-core' ),
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'alpha-core' ),
				'separator'   => 'none',
				'show_label'  => false,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'alert_icon',
			array(
				'label'                  => esc_html__( 'Choose Icon', 'alpha-core' ),
				'type'                   => 'icons',
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'label_block'            => false,
				'fa4compatibility'       => 'icon',
				'default'                => array(
					'value'   => 'fas fa-info-circle',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'show_dismiss',
			array(
				'label'       => esc_html__( 'Dismiss Button', 'alpha-core' ),
				'type'        => 'switcher',
				'label_off'   => esc_html__( 'Hide', 'alpha-core' ),
				'label_on'    => esc_html__( 'Show', 'alpha-core' ),
				'description' => esc_html__( 'Allows to show dismiss button on alert.', 'alpha-core' ),
				'default'     => 'yes',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'dismiss_icon',
			array(
				'label'                  => esc_html__( 'Choose Icon', 'alpha-core' ),
				'type'                   => 'icons',
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'label_block'            => false,
				'fa4compatibility'       => 'icon',
				'condition'              => array(
					'show_dismiss' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_style',
			array(
				'label' => esc_html__( 'Alert', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'alert_bg_color',
				'selector' => '{{WRAPPER}} .alert',
				'exclude'  => array( 'image' ),
			)
		);

		$this->add_responsive_control(
			'alert_padding',
			array(
				'label'      => esc_html__( 'Padding', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .alert' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'alert_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .alert' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_responsive_control(
			'alert_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'{{WRAPPER}} .alert' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'alert_border_color',
			array(
				'label'       => esc_html__( 'Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Set color of alert border.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .alert' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			array(
				'label' => esc_html__( 'Title', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Set color of title.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'.elementor-element-{{ID}} .alert-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '.elementor-element-{{ID}} .alert-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .alert-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'desc_style',
			array(
				'label' => esc_html__( 'Description', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Set color of description.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'.elementor-element-{{ID}} .alert-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '.elementor-element-{{ID}} .alert-desc',
			)
		);

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .alert-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_style',
			array(
				'label' => esc_html__( 'Icon', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Set color of icon.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'.elementor-element-{{ID}} .alert-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'       => esc_html__( 'Size', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Set size of icon.', 'alpha-core' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .alert-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .alert-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'dismiss_style',
			array(
				'label'     => esc_html__( 'Dismiss', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_dismiss' => 'yes',
				),
			)
		);

		$this->add_control(
			'dismiss_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Set color of dismiss.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .btn-close' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dismiss_hover_color',
			array(
				'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
				'description' => esc_html__( 'Set hover color of dismiss.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .btn-close:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'dismiss_size',
			array(
				'label'       => esc_html__( 'Size', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Set size of dismiss.', 'alpha-core' ),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn-close' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dismiss_margin',
			array(
				'label'      => esc_html__( 'Margin', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .btn-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'title' );
		$this->add_inline_editing_attributes( 'description' );

		require ALPHA_CORE_INC . '/widgets/alert/render-alert-elementor.php';
	}

	protected function content_template() {
		?>

		<#
		let wrapper_class = 'alert alert-'  + settings.alert_skin,
			html = '';

		if ( settings.alert_type ) {
			wrapper_class += ' alert-' + settings.alert_type;
		}

		html += '<div class="' + wrapper_class + '">';

		if ( settings.alert_icon.value ) {
			html += '<div class="alert-icon ' + settings.alert_icon.value + '">';
			html += '</div>';
		}

		if ( settings.alert_icon && settings.title && settings.description ) {
			html += '<div class="alert-content">';
		}

		if ( settings.title ) {
			view.addRenderAttribute( 'title', 'class', 'alert-title' );
			view.addInlineEditingAttributes( 'title' );

			html += '<div ' + view.getRenderAttributeString( 'title' ) + '>';
			html += settings.title;
			html += '</div>';
		}

		if ( settings.description ) {
			view.addRenderAttribute( 'description', 'class', 'alert-desc' );
			view.addInlineEditingAttributes( 'description' );

			html += '<div ' + view.getRenderAttributeString( 'description' ) + '>';
			html += settings.description;
			html += '</div>';
		}

		if ( settings.alert_icon && settings.title && settings.description ) {
			html += '</div>';
		}

		if ( 'yes' == settings.show_dismiss ) {
			html += '<button class="btn btn-link btn-close ' + ( settings.dismiss_icon.value ? settings.dismiss_icon.value : 'a-icon-times-solid' ) + '" type="button"></button>';
		}

		html += '</div>';
		print( html );
		#>

		<?php
	}
}
