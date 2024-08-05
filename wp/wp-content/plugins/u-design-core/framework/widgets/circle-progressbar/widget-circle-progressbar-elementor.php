<?php
/**
 * Alpha Circle Progressbar Element
 *
 * @author  D-THEMES
 * @package Alpha Core Framework
 * @subpackage Core
 * @since 1.3.0
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;

class Alpha_Circle_Progressbar_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_circle_progressbar';
	}

	public function get_title() {
		return esc_html__( 'Circle Progressbar', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-counter-circle';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'progress bar', 'chart' );
	}

	public function get_style_depends() {
		wp_register_style( 'alpha-circle-progressbar', alpha_core_framework_uri( '/widgets/circle-progressbar/circle-progressbar' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-circle-progressbar' );
	}

	public function get_script_depends() {
		wp_register_script( 'easypiechart', alpha_core_framework_uri( '/widgets/circle-progressbar/easypiechart.min.js' ), array(), ALPHA_CORE_VERSION, true );
		wp_register_script( 'alpha-circle-progressbar', alpha_core_framework_uri( '/widgets/circle-progressbar/circle-progressbar' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		return array( 'easypiechart', 'alpha-circle-progressbar' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_circular_bar',
			array(
				'label' => __( 'Circular Bar', 'alpha-core' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'alpha-core' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'value',
			array(
				'label'   => esc_html__( 'Progressbar Value', 'alpha-core' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 70,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'units',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Units', 'alpha-core' ),
				'default'     => '%',
				'description' => __( 'Enter measurement units (Example: %, px, points, etc).', 'alpha-core' ),
			)
		);

		$this->add_control(
			'view',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'View Mode', 'alpha-core' ),
				'options' => array(
					''           => esc_html__( 'Title and Value', 'alpha-core' ),
					'only-title' => esc_html__( 'Only Title', 'alpha-core' ),
					'only-value' => esc_html__( 'Only Value', 'alpha-core' ),
					'only-icon'  => esc_html__( 'Only Icon', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'icon_cl',
			array(
				'type'             => Controls_Manager::ICONS,
				'label'            => __( 'Select Icon', 'alpha-core' ),
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => ALPHA_ICON_PREFIX . '-icon-verification',
					'library' => 'alpha-icons',
				),
				'condition'        => array(
					'view' => 'only-icon',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Icon Color', 'alpha-core' ),
				'condition' => array(
					'view' => 'only-icon',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Icon Size (px)', 'alpha-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .circular-bar i' => 'font-size: {{SIZE}}px;',
				),
				'condition' => array(
					'view' => 'only-icon',
				),
			)
		);

		$this->add_control(
			'linecap',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Bar Shape', 'alpha-core' ),
				'description' => __( 'Choose how the corner of the bar line looks like.', 'alpha-core' ),
				'default'     => 'square',
				'options'     => array(
					'square' => __( 'Square', 'alpha-core' ),
					'round'  => __( 'Round', 'alpha-core' ),
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => __( 'Animation Speed (ms)', 'alpha-core' ),
				'default' => 2500,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_style',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_pos',
			array(
				'label'       => esc_html__( 'Content Position (%)', 'alpha-core' ),
				'description' => esc_html__( 'Type a certain number for content position.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 200,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .circular-bar .bar-content' => 'top: {{SIZE}}%;',
				),
			)
		);

		$this->add_responsive_control(
			'content_spacing',
			array(
				'label'       => esc_html__( 'Content Spacing (px)', 'alpha-core' ),
				'description' => esc_html__( 'Type a certain number for spacing between title and value.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 10,
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 50,
					),
				),
				'condition'   => array(
					'view' => '',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .circular-bar strong' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'size',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => __( 'Circle Size', 'alpha-core' ),
				'default' => 175,
				'min'     => 10,
				'max'     => 500,
			)
		);

		$this->add_control(
			'barcolor',
			array(
				'type'        => Controls_Manager::COLOR,
				'label'       => __( 'Bar color', 'alpha-core' ),
				'description' => __( 'Select progressbar color. Please clear this if you want to use the default color.', 'alpha-core' ),
			)
		);

		$this->add_control(
			'trackcolor',
			array(
				'type'        => Controls_Manager::COLOR,
				'label'       => __( 'Track Color', 'alpha-core' ),
				'default'     => '#D4DAE1',
				'description' => __( 'Choose the color of the track. Please clear this if you want to use the default color.', 'alpha-core' ),
			)
		);

		$this->add_control(
			'line',
			array(
				'type'    => Controls_Manager::NUMBER,
				'label'   => __( 'Line Width (px)', 'alpha-core' ),
				'default' => 5,
				'min'     => 1,
				'max'     => 50,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			array(
				'label'     => esc_html__( 'Title', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'view' => array( '', 'only-title' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '.elementor-element-{{ID}} .circular-bar strong',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'       => esc_html__( 'Title Color', 'alpha-core' ),
				'description' => esc_html__( 'Control the title color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .circular-bar strong' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'value_style',
			array(
				'label'     => esc_html__( 'Value', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'view' => array( '', 'only-value' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => '.elementor-element-{{ID}} .circular-bar label',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'       => esc_html__( 'Value Color', 'alpha-core' ),
				'description' => esc_html__( 'Control the value color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .circular-bar label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		if ( isset( $atts['icon_cl'] ) && isset( $atts['icon_cl']['value'] ) ) {
			$atts['icon'] = $atts['icon_cl']['value'];
		}
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/circle-progressbar/render-circle-progressbar-elementor.php' );
	}

	protected function content_template() {
		?>
		<#
			let options = {};
			options['trackColor']          = settings.trackcolor;
			options['barColor']            = settings.barcolor ? settings.barcolor : '<?php echo esc_js( alpha_get_option( 'primary_color' ) ); ?>';
			options['lineCap']             = settings.linecap;
			options['lineWidth']           = settings.line;
			options['size']                = settings.size;
			options['animate']             = {};
			options['animate']['duration'] = settings.speed;

			view.addRenderAttribute( 'wrapper', 'class', 'circular-bar center' );
			if ( settings.type ) {
				view.addRenderAttribute( 'wrapper', 'class', settings.type );
			}
			if ( settings.view ) {
				view.addRenderAttribute( 'wrapper', 'class', settings.view );
			}
			view.addRenderAttribute( 'title', 'class', 'bar-title' );
			view.addInlineEditingAttributes( 'title' );
			view.addRenderAttribute( 'innerWrapper', 'data-percent', settings.value );
			view.addRenderAttribute( 'innerWrapper', 'data-plugin-options', JSON.stringify( options ) );
			view.addRenderAttribute( 'innerWrapper', 'style', 'height:' + Number( settings.size ) + 'px;' );
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<div class="circular-bar-chart" {{{ view.getRenderAttributeString( 'innerWrapper' ) }}}>
				<div class="bar-content">
			<#
				if ( 'only-icon' == settings.view ) {
					if( 'svg' == settings.icon_cl.library ) {
						var svgHtml = elementor.helpers.renderIcon( view, settings.icon_cl, { 'aria-hidden': true } );
					#>
						{{{ svgHtml.value }}}
					<#} else {
						view.addRenderAttribute( 'icon', 'class', settings.icon_cl.value );
						if ( settings.icon_color ) {
							view.addRenderAttribute( 'icon', 'style', 'color:' + settings.icon_color );
						}
					#>
						<i {{{ view.getRenderAttributeString( 'icon' ) }}}></i>
					<#
					}
			#>
			<# } else if ( 'only-title' == settings.view ) { #>
				<# if ( settings.title ) { #>
					<strong {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</strong>
				<# } #>
			<# } else { #>
				<# if ( settings.title && 'only-value' !== settings.view ) { #>
					<strong {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</strong>
				<# } #>
				<label><span class="percent">0</span>{{{ settings.units }}}</label>
				</div>
			<# } #>
			</div>
		</div>
		<?php
	}
}
