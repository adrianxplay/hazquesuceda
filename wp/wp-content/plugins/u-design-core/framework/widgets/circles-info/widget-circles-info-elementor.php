<?php
/**
 * Alpha Circles Info Widget
 *
 * Alpha Widget to display Circles Info.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.3.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

class Alpha_Circles_Info_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_circles_info';
	}

	public function get_title() {
		return esc_html__( 'Circles Info', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-circles-info';
	}

	public function get_keywords() {
		return array( 'circle info', 'circle', 'info', 'animated', 'heading', 'text', 'interactive', 'alpha' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-circles-info', alpha_core_framework_uri( '/widgets/circles-info/circles-info' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-circles-info' );
	}

	public function get_script_depends() {
		wp_register_script( 'alpha-circles-info', alpha_core_framework_uri( '/widgets/circles-info/circles-info' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
		return array( 'alpha-circles-info' );
	}


	protected function register_controls() {

		parent::register_controls();

		$repeater = new Repeater();

		$repeater->add_control(
			'link_type',
			array(
				'label'       => esc_html__( 'Dot Content Type', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Select the content type to show in a small circle.', 'alpha-core' ),
				'options'     => array(
					'icon'  => esc_html__( 'Icon', 'alpha-core' ),
					'image' => esc_html__( 'Image', 'alpha-core' ),
					'html'  => esc_html__( 'Html', 'alpha-core' ),
				),
				'default'     => 'icon',
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'     => esc_html__( 'Icon', 'alpha-core' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => ALPHA_ICON_PREFIX . '-icon-star',
					'library' => 'alpha-icons',
				),
				'condition' => array(
					'link_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label'     => esc_html__( 'Choose Image', 'alpha-core' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'link_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'html',
			array(
				'label'       => esc_html__( 'Html', 'alpha-core' ),
				'description' => esc_html__( 'Input the html code to show in a small circle.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'link_type' => 'html',
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
				'exclude'   => array( 'custom' ),
				'default'   => 'full',
				'condition' => array(
					'link_type' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'alpha-core' ),
				'description' => esc_html__( 'Input the title text.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Description', 'alpha-core' ),
				'description' => esc_html__( 'Input the description text.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link Url', 'alpha-core' ),
				'description' => esc_html__( 'Input URL where you will move when title is clicked.', 'alpha-core' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'url' => '',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$presets = array(
			array(
				'title' => esc_html__( 'Circle Info Item #1', 'alpha-core' ),
			),
			array(
				'title' => esc_html__( 'Circle Info Item #2', 'alpha-core' ),
			),
			array(
				'title' => esc_html__( 'Circle Info Item #3', 'alpha-core' ),
			),
		);

		$this->start_controls_section(
			'section_circles_info_content',
			array(
				'label' => esc_html__( 'Circle Info Items', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'items',
				array(
					'label'       => esc_html__( 'Items', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'title_field' => '{{{ title }}}',
					'fields'      => $repeater->get_controls(),
					'default'     => $presets,
					'description' => esc_html__( 'Add circle info items.', 'alpha-core' ),
				),
			);

			$this->add_control(
				'title_html_tag',
				array(
					'label'       => esc_html__( 'Title HTML Tag', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'description' => esc_html__( 'Select the HTML Title tag from H1 to H6 tag.', 'alpha-core' ),
					'options'     => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'default'     => 'h3',
				)
			);

			$this->add_control(
				'link_anim',
				array(
					'label'     => esc_html__( 'Animation', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'bounce',
					'separator' => 'before',
					'options'   => array(
						'bounce' => esc_html__( 'Bounce In', 'alpha-core' ),
						'spin'   => esc_html__( 'Spinning', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'active_on',
				array(
					'label'   => esc_html__( 'Active On', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'click',
					'options' => array(
						'mouseenter' => esc_html__( 'Hover', 'alpha-core' ),
						'click'      => esc_html__( 'Click', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'auto_rotate',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Auto Rotate', 'alpha-core' ),
					'description' => esc_html__( 'Enable to rotate automatically.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'pause',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Pause on Hover', 'alpha-core' ),
					'description' => esc_html__( 'Rotation is paused when mouse enters circles info area.', 'alpha-core' ),
					'condition'   => array(
						'auto_rotate' => 'yes',
					),
				)
			);

			$this->add_control(
				'rotate_time',
				array(
					'type'        => Controls_Manager::NUMBER,
					'label'       => esc_html__( 'Rotate Time Delay (s)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the dealy time between each icon item.', 'alpha-core' ),
					'default'     => 5,
					'condition'   => array(
						'auto_rotate' => 'yes',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ci_general_styles',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'ci_circle_size',
				array(
					'label'       => esc_html__( 'Circle Size', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
					),
					'range'       => array(
						'px'  => array(
							'min' => 0,
							'max' => 1000,
						),
						'rem' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'render_type' => 'template',
					'selectors'   => array(
						'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'ci_circle_padding',
				array(
					'label'      => esc_html__( 'Padding', 'alpha-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'rem' ),
					'selectors'  => array(
						'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'ci_circle_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-circle-bg-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'circle_border',
					'selector' => '{{WRAPPER}} .ci-wrapper',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ci_icon_styles',
			array(
				'label' => esc_html__( 'Item', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'ci_icon_size',
				array(
					'label'       => esc_html__( 'Icon Size', 'alpha-core' ),
					'description' => esc_html__( 'Control icon size.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
					),
					'range'       => array(
						'px'  => array(
							'min' => 6,
							'max' => 300,
						),
						'rem' => array(
							'min' => 6,
							'max' => 30,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-icon-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'ci_size',
				array(
					'label'       => esc_html__( 'Size', 'alpha-core' ),
					'description' => esc_html__( 'Control size of width & height.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'em',
						'rem',
					),
					'range'       => array(
						'px'  => array(
							'min' => 6,
							'max' => 300,
						),
						'em'  => array(
							'min' => 6,
							'max' => 30,
						),
						'rem' => array(
							'min' => 6,
							'max' => 30,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-link-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'ci_bd_size',
				array(
					'label'       => esc_html__( 'Border Width', 'alpha-core' ),
					'description' => esc_html__( 'Control size of width & height.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'em',
						'rem',
					),
					'range'       => array(
						'px'  => array(
							'min' => 1,
							'max' => 10,
						),
						'em'  => array(
							'min' => 1,
							'max' => 10,
						),
						'rem' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-link-border-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_icon_color' );
				$this->start_controls_tab(
					'tab_icon_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'ci_icon_color',
						array(
							'label'     => esc_html__( 'Icon Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-icon-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_svg_stroke',
						array(
							'label'     => esc_html__( 'Svg Stroke Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-svg-stroke-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_svg_fill',
						array(
							'label'     => esc_html__( 'Svg Fill Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-svg-fill-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_icon_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-link-bg-color: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_icon_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-link-border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_icon_hover',
					array(
						'label' => esc_html__( 'Hover/Active', 'alpha-core' ),
					)
				);

					$this->add_control(
						'ci_icon_hover_color',
						array(
							'label'     => esc_html__( 'Icon Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-icon-color-active: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_svg_hover_stroke',
						array(
							'label'     => esc_html__( 'Svg Stroke Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-svg-stroke-color-active: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_svg_hover_fill',
						array(
							'label'     => esc_html__( ' Svg Fill Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-svg-fill-color-active: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_icon_hover_bg_color',
						array(
							'label'     => esc_html__( 'Background Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-link-bg-color-active: {{VALUE}};',
							),
						)
					);

					$this->add_control(
						'ci_icon_hover_border_color',
						array(
							'label'     => esc_html__( 'Border Color', 'alpha-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .ci-wrapper' => '--alpha-ci-link-border-color-active: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ci_title_styles',
			array(
				'label' => esc_html__( 'Title', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typo',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .ci-wrapper .ci-title',
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Set the title color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .ci-wrapper .ci-title' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_control(
				'title_color_hover',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Set the title hover color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .ci-wrapper .ci-title:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'ci_title_line_limit',
				array(
					'label'       => esc_html__( 'Line Limit', 'alpha-core' ),
					'description' => esc_html__( 'Control limit line of title.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .ci-title' => 'display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: {{SIZE}}; overflow: hidden;',
					),
				)
			);

			$this->add_responsive_control(
				'title_margin',
				array(
					'label'       => esc_html__( 'Margin', 'alpha-core' ),
					'description' => esc_html__( 'Please give margin of title text.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px', 'rem' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .ci-wrapper .ci-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ci_desc_styles',
			array(
				'label' => esc_html__( 'Description', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'desc_typo',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .ci-wrapper .ci-desc',
				)
			);

			$this->add_control(
				'desc_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Set the description color.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .ci-wrapper .ci-desc' => 'color: {{VALUE}}',
					),
				)
			);

			$this->add_responsive_control(
				'ci_desc_line_limit',
				array(
					'label'       => esc_html__( 'Line Limit', 'alpha-core' ),
					'description' => esc_html__( 'Control limit line of title.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .ci-desc' => 'display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: {{SIZE}}; overflow: hidden;',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/circles-info/render-circles-info-elementor.php' );
	}

	protected function content_template() {
		?>
		<#
		var wrapper_cls  = 'ci-wrapper',
			icons_html   = '',
			content_html = '';
		
		var wrapper_attrs = {
			'animation' : settings.link_anim,
			'event'     : settings.active_on,
			'rotate'    : 'yes' == settings.auto_rotate ? true : false,
			'pause'     : 'yes' == settings.pause ? true : false,
			'delay'     : settings.rotate_time,
		};
		view.addRenderAttribute( 'wrapper', 'class', wrapper_cls );
		view.addRenderAttribute( 'wrapper', 'data-plugin-options', JSON.stringify( wrapper_attrs ) );
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
		<#
		_.each( settings.items, function( item, index ) {
			icons_html += '<div class="ci-icon-link" data-id="' + ( index + 1 ) + '"><span>';
			if ( 'image' == item.link_type ) {
				var image_url = elementor.imagesManager.getImageUrl( item.image );
				icons_html += '<img src="' + image_url + '"/>';
			} else if ( 'icon' == item.link_type ) {
				if( item.icon.library == 'svg' ) {
					icons_html += elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true } ).value;
				} else {
					icons_html += '<i class="' + item.icon.value + '"></i>';
				}
			} else {
				var repeaterKey = view.getRepeaterSettingKey( 'html', 'items', index );
				view.addInlineEditingAttributes( repeaterKey );
				icons_html += '<span ' + view.getRenderAttributeString( repeaterKey ) + '>' + item.html + '</span>';
			}
			icons_html += '</span></div>';

			content_html += '<div class="ci-content" data-id="' + parseInt( index + 1 ) + '">';
			
			var linkAttr = 'href="'  + ( item.link.url ? item.link.url : '#' ) + '"',
				linkOpen = item.link.url ? '<a class="link" ' + linkAttr + '>' : '',
				linkClose = item.link.url ? '</a>' : '';

			if ( item.title ) {
				var headerSizeTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ),
					repeaterKey = view.getRepeaterSettingKey( 'title', 'items', index );
				view.addInlineEditingAttributes( repeaterKey );
				content_html += '<' + headerSizeTag + ' class="ci-title">' + linkOpen + '<span ' + view.getRenderAttributeString( repeaterKey ) + '>' + item.title + '</span>' + linkClose  + '</' + headerSizeTag + '>';
			}
			
			if ( item.description ) {
				var repeaterKey = view.getRepeaterSettingKey( 'description', 'items', index );
				view.addInlineEditingAttributes( repeaterKey );
				content_html += '<p class="ci-desc">' + '<span ' + view.getRenderAttributeString( repeaterKey ) + '>' + item.description + '</span>' + '</p>';
			}

			content_html += '</div>';
		} )
		#>
		<div class="ci-icons-wrapper">{{{icons_html}}}</div>
		<div class="ci-contents-wrapper">{{{content_html}}}</div>
		</div>
		<?php
	}
}
