<?php
/**
 * Alpha Highlight Widget
 *
 * Alpha Widget to display Highlight text.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

class Alpha_Highlight_Elementor_Widget extends Elementor\Widget_Heading {

	public $svgs;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->svgs = array(
			'circle'           => array( 'M284.72,15.61C276.85,14.43,2-2.85,2,80.46c0,34.09,45.22,58.86,196.31,62.81C719.59,154.18,467-74.85,109,29.15' ),
			'curly'            => array( 'M1.15,18C64.07,44.13,108.42,1.4,169.63,3.1,182.11,3.76,191.39,6.58,201,10c71.41,33.39,112-8.7,188.65-7,35.22,1.74,69.81,22.6,103,17' ),
			'underline'        => array( 'M.68,28.11c110.51-22,247.46-34.55,400.89-14.68,32.94,4.27,64.42,9.74,94.37,16.09' ),
			'underline-2'      => array( 'M3.2,9.3c0,0,202-9.6,328.2-4.6c74.6,2.9,190.6,13.6,161,13.9C260.9,20.9,76.3,33.4,62,38.7c-14.2,5.3,258.5,6.2,258.5,6.2' ),
			'double'           => array( 'M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M.58,127s93-13.31,303.15-10.26C421.79,118.48,483.83,127,483.83,127' ),
			'double-underline' => array( 'M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M29.83,33.28S111.54,17.1,296.13,20.8c103.71,2.08,158.2,12.48,158.2,12.48' ),
			'underline-zigzag' => array( 'M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9' ),
			'diagonal'         => array( 'M.25,3.49C114.44,11.6,252,36.14,397.07,97.15c31.14,13.1,60.52,27,88.18,41.34' ),
			'strikethrough'    => array( 'M4,74.8h499.3' ),
			'x'                => array( 'M1.61,3.49C115.8,11.6,253.39,36.14,398.43,97.15c31.14,13.1,60.53,27,88.18,41.34', 'M486.61,3.49C372.42,11.6,234.84,36.14,89.79,97.15c-31.14,13.1-60.52,27-88.18,41.34' ),
		);
	}

	public function get_name() {
		return ALPHA_NAME . '_widget_highlight';
	}

	public function get_title() {
		return esc_html__( 'Highlight', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-highlight';
	}

	public function get_keywords() {
		return array( 'highlight', 'animated', 'heading', 'text', 'alpha' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-highlight', alpha_core_framework_uri( '/widgets/highlight/highlight' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-highlight' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-highlight', alpha_core_framework_uri( '/widgets/highlight/highlight' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		return array( 'alpha-highlight' );
	}

	protected function register_controls() {

		parent::register_controls();

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Text', 'alpha-core' ),
				'description' => esc_html__( 'Input the text.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'highlight',
			array(
				'label'       => esc_html__( 'Highlight', 'alpha-core' ),
				'description' => esc_html__( 'Make the text highlight one.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'alpha-core' ),
				'label_off'   => esc_html__( 'No', 'alpha-core' ),
				'default'     => false,
			)
		);

		$repeater->add_control(
			'highlight_type',
			array(
				'label'       => esc_html__( 'Type', 'alpha-core' ),
				'description' => esc_html__( 'Select highlight style to use.', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'fill'             => esc_html__( 'Fill', 'alpha-core' ),
					'circle'           => esc_html__( 'Circle', 'alpha-core' ),
					'underline-zigzag' => esc_html__( 'Underline Zigzag', 'alpha-core' ),
					'curly'            => esc_html__( 'Curly', 'alpha-core' ),
					'x'                => esc_html__( 'Cross X', 'alpha-core' ),
					'strikethrough'    => esc_html__( 'Linethrough', 'alpha-core' ),
					'underline'        => esc_html__( 'Underline', 'alpha-core' ),
					'underline-2'      => esc_html__( 'Underline 2', 'alpha-core' ),
					'double'           => esc_html__( 'Double', 'alpha-core' ),
					'double-underline' => esc_html__( 'Double Underline', 'alpha-core' ),
					'diagonal'         => esc_html__( 'Diagonal', 'alpha-core' ),
				),
				'default'     => 'fill',
			)
		);

		$repeater->add_control(
			'text_color',
			array(
				'label'       => esc_html__( 'Text Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the text color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
				'condition'   => array(
					'highlight' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'text_color_hover',
			array(
				'label'       => esc_html__( 'Text Hover Color', 'alpha-core' ),
				'description' => esc_html__( 'Set the text hover color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}:hover' => 'color: {{VALUE}}',
				),
				'condition'   => array(
					'highlight'      => 'yes',
					'highlight_type' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'highlight_color',
			array(
				'label'       => esc_html__( 'Highlight Color', 'alpha-core' ),
				'description' => esc_html__( 'Determine the text highlight color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'background-image: linear-gradient({{VALUE}}, {{VALUE}})',
				),
				'condition'   => array(
					'highlight'      => 'yes',
					'highlight_type' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'highlight_height',
			array(
				'label'       => esc_html__( 'Height (%)', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array(
					'%' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					// '.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'background-size: 0 {{SIZE}}%',
					// '.elementor-element-{{ID}} {{CURRENT_ITEM}}.animating' => 'background-size: 100% {{SIZE}}%',
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => '--alpha-highlight-bg-size: {{SIZE}}%',
				),
				'description' => esc_html__( 'Determine the highlight part within a text.', 'alpha-core' ),
				'condition'   => array(
					'highlight'      => 'yes',
					'highlight_type' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'svg_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#605BE5',
				'selectors' => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}} path' => 'stroke: {{VALUE}};',
				),
				'condition' => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_responsive_control(
			'svg_width',
			array(
				'label'      => esc_html__( 'Width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}} svg' => 'width: {{SIZE}}%;',
				),
				'condition'  => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_responsive_control(
			'svg_height',
			array(
				'label'      => esc_html__( 'Height', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 90,
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}} svg' => 'height: {{SIZE}}%;',
				),
				'condition'  => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_responsive_control(
			'svg_weight',
			array(
				'label'      => esc_html__( 'Weight', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}} path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'highlight_offset_y',
			array(
				'label'       => esc_html__( 'Vertical Offset', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%', 'em' ),
				'range'       => array(
					'%' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => '--alpha-highlight-offset-y: {{SIZE}}{{UNIT}}',
				),
				'description' => esc_html__( 'Set highlight effect\'s offset y position.', 'alpha-core' ),
				'condition'   => array(
					'highlight'       => 'yes',
					'highlight_type!' => array( 'fill', 'circle' ),
				),
			)
		);

		$repeater->add_control(
			'a_duration',
			array(
				'label'     => esc_html__( 'Animation Duration', 'alpha-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'min'       => 0,
				'max'       => 50,
				'step'      => 1,
				'selectors' => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}} svg path' => 'animation-duration: {{VALUE}}s;',
				),
				'condition' => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'a_delay',
			array(
				'label'     => esc_html__( 'Animation Delay', 'alpha-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'min'       => 0,
				'max'       => 15,
				'step'      => 0.1,
				'selectors' => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}.highlight svg path' => 'animation-delay: {{VALUE}}s;',
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}.highlight-x svg path:first-child' => 'animation-delay: calc({{VALUE}}s + 0.3s);',
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}.highlight-double svg path:last-child' => 'animation-delay: calc({{VALUE}}s + 0.3s);',
				),
				'condition' => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'a_loop',
			array(
				'label'       => esc_html__( 'Loop', 'alpha-core' ),
				'description' => esc_html__( 'Allow to highlight infinitely.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}} svg path' => 'animation-iteration-count: infinite;',
				),
				'render_type' => 'template',
				'condition'   => array(
					'highlight'       => 'yes',
					'highlight_type!' => 'fill',
				),
			)
		);

		$repeater->add_control(
			'highlight_padding',
			array(
				'label'       => esc_html__( 'Padding', 'alpha-core' ),
				'description' => esc_html__( 'Please give padding of highlight text.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'rem' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$repeater->add_control(
			'line_break',
			array(
				'label'       => esc_html__( 'Line Break', 'alpha-core' ),
				'description' => esc_html__( 'It will be placed on a whole line.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
			)
		);

		$repeater->add_control(
			'custom_class',
			array(
				'label'       => esc_html__( 'Custom Class', 'alpha-core' ),
				'description' => esc_html__( 'Add some custom classes.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
			)
		);

		$presets = array(
			array(
				'text' => esc_html__( 'Have your', 'alpha-core' ),
			),
			array(
				'text'      => esc_html__( 'website popup', 'alpha-core' ),
				'highlight' => 'yes',
			),
			array(
				'text' => esc_html__( 'out with', 'alpha-core' ),
			),
			array(
				'text'      => esc_html__( 'Our Theme', 'alpha-core' ),
				'highlight' => 'yes',
			),
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Content', 'alpha-core' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ text }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => $presets,
				'description' => esc_html__( 'Add repeater items.', 'alpha-core' ),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title',
				),
			)
		);

		$this->remove_control( 'title' );
		$this->remove_control( 'link' );
		$this->remove_control( 'size' );
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/highlight/render-highlight-elementor.php' );
	}

	protected function content_template() {
		?>
		<#
		var headerSizeTag = elementor.helpers.validateHTMLTag( settings.header_size );
		var highlightSvgs = {
			'circle'           : ['M284.72,15.61C276.85,14.43,2-2.85,2,80.46c0,34.09,45.22,58.86,196.31,62.81C719.59,154.18,467-74.85,109,29.15'],
			'curly'            : ['M1.15,18C64.07,44.13,108.42,1.4,169.63,3.1,182.11,3.76,191.39,6.58,201,10c71.41,33.39,112-8.7,188.65-7,35.22,1.74,69.81,22.6,103,17'],
			'underline'        : ['M.68,28.11c110.51-22,247.46-34.55,400.89-14.68,32.94,4.27,64.42,9.74,94.37,16.09'],
			'underline-2'      : ['M3.2,9.3c0,0,202-9.6,328.2-4.6c74.6,2.9,190.6,13.6,161,13.9C260.9,20.9,76.3,33.4,62,38.7c-14.2,5.3,258.5,6.2,258.5,6.2'],
			'double'           : ['M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M.58,127s93-13.31,303.15-10.26C421.79,118.48,483.83,127,483.83,127'],
			'double-underline' : ['M.58,16s93-15.56,303-12c118,2,180,12,180,12', 'M29.83,33.28S111.54,17.1,296.13,20.8c103.71,2.08,158.2,12.48,158.2,12.48'],
			'underline-zigzag' : ['M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9'],
			'diagonal'         : ['M.25,3.49C114.44,11.6,252,36.14,397.07,97.15c31.14,13.1,60.52,27,88.18,41.34'],
			'strikethrough'    : ['M4,74.8h499.3'],
			'x'                : ['M1.61,3.49C115.8,11.6,253.39,36.14,398.43,97.15c31.14,13.1,60.53,27,88.18,41.34', 'M486.61,3.49C372.42,11.6,234.84,36.14,89.79,97.15c-31.14,13.1-60.52,27-88.18,41.34'],
		}
		view.addRenderAttribute( 'title', 'class', ['elementor-heading-title', 'highlight-text'] );
		#>
		<{{{ headerSizeTag }}} {{{ view.getRenderAttributeString( 'title' ) }}}>
			<#
			_.each( settings.items, function( item, index ) {
				let item_key = view.getRepeaterSettingKey( 'text', 'items', index ),
					svg = '';
				view.addRenderAttribute(item_key, 'class', item.custom_class);

				if(item._id && item.highlight) {
					view.addRenderAttribute( item_key, 'class', 'highlight highlight-' + item.highlight_type + ' animating elementor-repeater-item-' + item._id );
					if ('yes' == item.a_loop) {
						view.addRenderAttribute( item_key, 'class', 'highlight-infinite' );
					}

					if ('fill' != item.highlight_type) {
						svg += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none">';
						_.each( highlightSvgs[item.highlight_type], function( item ) {
							svg += '<path d="' + item + '"></path>';
						});
						svg += '</svg>';
					}
				}
				view.addInlineEditingAttributes( item_key );

				var lineBreak = '';
				if ('yes' == item.line_break) {
					lineBreak = '<br>';
				}
				#>
				<span {{{ view.getRenderAttributeString( item_key ) }}}>{{{ item.text }}}{{{ svg }}}{{{ lineBreak }}}</span>
				<#
			});
			#>
		</{{{ headerSizeTag }}}>
		<?php
	}
}
