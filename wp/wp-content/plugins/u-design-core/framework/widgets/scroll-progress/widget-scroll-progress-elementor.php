<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Alpha Scroll Progress widget
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.3.0
 */

use Elementor\Controls_Manager;

class Alpha_Scroll_Progress_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_scroll_progress';
	}

	public function get_title() {
		return esc_html__( 'Scroll Progress', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-import-export';
	}

	public function get_keywords() {
		return array( 'top', 'circle', 'bar', 'inner' );
	}

	public function get_style_depends() {
		wp_register_style( 'alpha-scroll-progress', alpha_core_framework_uri( '/widgets/scroll-progress/scroll-progress' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-scroll-progress' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-scroll-progress', alpha_core_framework_uri( '/widgets/scroll-progress/scroll-progress' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-scroll-progress' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_scroll_progress',
			array(
				'label' => __( 'Scroll Progress', 'alpha-core' ),
			)
		);

		$this->add_control(
			'type',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Progressbar Type', 'alpha-core' ),
				'label_block' => true,
				'options'     => array(
					''       => __( 'Horizontal progress bar', 'alpha-core' ),
					'circle' => __( 'Around the Scroll to Top button', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Fixed Position', 'alpha-core' ),
				'options'   => array(
					''             => __( 'No', 'alpha-core' ),
					'top'          => __( 'Fixed on Top', 'alpha-core' ),
					'under-header' => __( 'Under Sticky Header', 'alpha-core' ),
					'bottom'       => __( 'Fixed on Bottom', 'alpha-core' ),
				),
				'condition' => array(
					'type' => '',
				),
			)
		);

		$this->add_control(
			'offset_top',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Offset Height', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type'     => '',
					'position' => 'top',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'offset_bottom',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Offset Height', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type'     => '',
					'position' => 'bottom',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_cls',
			array(
				'type'                   => Controls_Manager::ICONS,
				'label'                  => __( 'Select Icon', 'alpha-core' ),
				'fa4compatibility'       => 'icon',
				'default'                => array(
					'value'   => ALPHA_ICON_PREFIX . '-icon-verification',
					'library' => 'alpha-icons',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'condition'              => array(
					'type' => 'circle',
				),
			)
		);

		$this->add_control(
			'circle_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Size', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type' => 'circle',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress-circle' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position1',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Position', 'alpha-core' ),
				'options'   => array(
					''   => __( 'Bottom Right', 'alpha-core' ),
					'bl' => __( 'Bottom Left', 'alpha-core' ),
					'tl' => __( 'Top Left', 'alpha-core' ),
					'tr' => __( 'Top Right', 'alpha-core' ),
				),
				'condition' => array(
					'type' => 'circle',
				),
			)
		);

		$this->add_control(
			'offset_x1',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Offset X', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type'      => 'circle',
					'position1' => array( 'tl', 'bl' ),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress-circle' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'offset_x2',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Offset X', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type'      => 'circle',
					'position1' => array( 'tr', '' ),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress-circle' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'offset_y1',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Offset Y', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type'      => 'circle',
					'position1' => array( 'tl', 'tr' ),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress-circle' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'offset_y2',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Offset Y', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 200,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type'      => 'circle',
					'position1' => array( '', 'bl' ),
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress-circle' => 'bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thickness1',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Thickness (px)', 'alpha-core' ),
				'min'       => 1,
				'max'       => 100,
				'condition' => array(
					'type' => '',
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress' => 'height: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'thickness2',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Thickness of progress bar (px)', 'alpha-core' ),
				'min'       => 1,
				'max'       => 10,
				'condition' => array(
					'type' => 'circle',
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress circle' => 'stroke-width: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Icon Size', 'alpha-core' ),
				'range'      => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'em'  => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
					'rem' => array(
						'step' => 0.1,
						'min'  => 0,
						'max'  => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array(
					'px',
					'em',
					'rem',
				),
				'condition'  => array(
					'type' => 'circle',
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} .scroll-progress-circle' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_bgcolor',
			array(
				'type'        => Controls_Manager::COLOR,
				'label'       => __( 'Background Color', 'alpha-core' ),
				'description' => __( 'Set the background color of icon part.', 'alpha-core' ),
				'condition'   => array(
					'type' => 'circle',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .scroll-progress i' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Icon Color', 'alpha-core' ),
				'condition' => array(
					'type' => 'circle',
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'br',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Border Radius (px)', 'alpha-core' ),
				'condition' => array(
					'type' => '',
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress' => '--alpha-scroll-progress-radius: {{VALUE}}px;',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'br2',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Active Bar Border Radius (px)', 'alpha-core' ),
				'condition' => array(
					'type' => '',
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress::-webkit-progress-value' => 'border-radius: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'bgcolor',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Bar Color', 'alpha-core' ),
				'condition' => array(
					'type' => '',
				),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress' => 'background-color: {{VALUE}};',
					'.elementor-element-{{ID}} .scroll-progress::-webkit-progress-bar' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'active_bgcolor',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Active Bar Color', 'alpha-core' ),
				'selectors' => array(
					'.elementor-element-{{ID}} .scroll-progress::-moz-progress-bar' => 'background-color: {{VALUE}};',
					'.elementor-element-{{ID}} .scroll-progress::-webkit-progress-value' => 'background-color: {{VALUE}};',
					'.elementor-element-{{ID}} .scroll-progress circle' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function content_template() {
		?>
		&nbsp;
		<#
			let cls = 'scroll-progress scroll-progress-circle';
			if ( 'circle' == settings.type ) {
				if ( settings.position1 ) {
					cls += ' pos-' + settings.position1;
				}
		#>
				<a class="{{ cls }}" href="#" role="button">
					<i class="{{ settings.icon_cls.value }}"></i>
					<svg  version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70">
						<circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34"/>
					</svg>
				</a><style>#scroll-top{display:none !important}</style>
		<#
			} else {
				let cls = 'scroll-progress';
				if ( settings.position ) {
					cls += ' fixed-' + settings.position;
					if ( 'under-header' == settings.position ) {
						cls += ' fixed-top';
					}
				}
		#>
				<progress class="{{ cls }}" max="100">
				</progress>
		<#
			}
		#>
		<?php
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		if ( isset( $atts['icon_cls'] ) && isset( $atts['icon_cls']['value'] ) ) {
			$atts['icon'] = $atts['icon_cls']['value'];
		}
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/scroll-progress/render-scroll-progress-elementor.php' );
	}
}
