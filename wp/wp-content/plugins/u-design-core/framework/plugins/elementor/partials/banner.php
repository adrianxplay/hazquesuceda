<?php
/**
 * Banner Partial
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;

/**
 * Register banner controls.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_banner_controls' ) ) {

	function alpha_elementor_banner_controls( $self, $mode = '' ) {

		$self->start_controls_section(
			'section_banner',
			array(
				'label' => esc_html__( 'Banner', 'alpha-core' ),
			)
		);

		if ( 'insert_number' == $mode ) {
			$self->add_control(
				'banner_insert',
				array(
					'label'       => esc_html__( 'Banner Index', 'alpha-core' ),
					'description' => esc_html__( 'Determines which index the banner is inserted in.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '2',
					'options'     => array(
						'1'    => '1',
						'2'    => '2',
						'3'    => '3',
						'4'    => '4',
						'5'    => '5',
						'6'    => '6',
						'7'    => '7',
						'8'    => '8',
						'9'    => '9',
						'last' => esc_html__( 'At last', 'alpha-core' ),
					),
				)
			);
		}

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_banner_btn_cat' );

			$repeater->start_controls_tab(
				'tab_banner_content',
				array(
					'label' => esc_html__( 'Content', 'alpha-core' ),
				)
			);

				$repeater->add_control(
					'banner_item_type',
					array(
						'label'       => esc_html__( 'Type', 'alpha-core' ),
						'description' => esc_html__( 'Choose the content item type.', 'alpha-core' ),
						'label_block' => true,
						'type'        => Controls_Manager::SELECT,
						'default'     => 'text',
						'options'     => array(
							'text'    => esc_html__( 'Text', 'alpha-core' ),
							'button'  => esc_html__( 'Button', 'alpha-core' ),
							'image'   => esc_html__( 'Image', 'alpha-core' ),
							'hotspot' => esc_html__( 'Hotspot', 'alpha-core' ),
							'divider' => esc_html__( 'Divider', 'alpha-core' ),
						),
					)
				);

				/* Text Item */
				$repeater->add_control(
					'banner_text_content',
					array(
						'label'       => esc_html__( 'Content', 'alpha-core' ),
						'description' => esc_html__( 'Please input the text.', 'alpha-core' ),
						'type'        => Controls_Manager::TEXTAREA,
						'default'     => esc_html__( 'Add Your Text Here', 'alpha-core' ),
						'condition'   => array(
							'banner_item_type' => 'text',
						),
						'dynamic'     => array(
							'active' => true,
						),
					)
				);

				$repeater->add_control(
					'banner_text_tag',
					array(
						'label'       => esc_html__( 'Tag', 'alpha-core' ),
						'description' => esc_html__( 'Select the HTML Heading tag from H1 to H6 and P tag too.', 'alpha-core' ),
						'label_block' => true,
						'type'        => Controls_Manager::SELECT,
						'default'     => 'h2',
						'options'     => array(
							'h1'   => esc_html__( 'H1', 'alpha-core' ),
							'h2'   => esc_html__( 'H2', 'alpha-core' ),
							'h3'   => esc_html__( 'H3', 'alpha-core' ),
							'h4'   => esc_html__( 'H4', 'alpha-core' ),
							'h5'   => esc_html__( 'H5', 'alpha-core' ),
							'h6'   => esc_html__( 'H6', 'alpha-core' ),
							'p'    => esc_html__( 'p', 'alpha-core' ),
							'div'  => esc_html__( 'div', 'alpha-core' ),
							'span' => esc_html__( 'span', 'alpha-core' ),
						),
						'condition'   => array(
							'banner_item_type' => 'text',
						),
					)
				);

				/* Button */
				$repeater->add_control(
					'banner_btn_text',
					array(
						'label'       => esc_html__( 'Text', 'alpha-core' ),
						'description' => esc_html__( 'Type text that will be shown on button.', 'alpha-core' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( 'Click here', 'alpha-core' ),
						'dynamic'     => array(
							'active' => true,
						),
						'condition'   => array(
							'banner_item_type' => 'button',
						),
					)
				);

				$repeater->add_control(
					'banner_btn_link',
					array(
						'label'       => esc_html__( 'Link Url', 'alpha-core' ),
						'description' => esc_html__( 'Input URL where you will move when button is clicked.', 'alpha-core' ),
						'type'        => Controls_Manager::URL,
						'default'     => array(
							'url' => '#',
						),
						'dynamic'     => array(
							'active' => true,
						),
						'condition'   => array(
							'banner_item_type' => 'button',
						),
					)
				);

				alpha_elementor_button_layout_controls( $repeater, 'banner_item_type', 'button' );

				/* Image */
				$repeater->add_control(
					'banner_image',
					array(
						'label'       => esc_html__( 'Choose Image', 'alpha-core' ),
						'description' => esc_html__( 'Upload an image to display.', 'alpha-core' ),
						'type'        => Controls_Manager::MEDIA,
						'default'     => array(
							'url' => \Elementor\Utils::get_placeholder_image_src(),
						),
						'condition'   => array(
							'banner_item_type' => 'image',
						),
					)
				);

				$repeater->add_group_control(
					Group_Control_Image_Size::get_type(),
					array(
						'name'      => 'banner_image',
						'exclude'   => [ 'custom' ],
						'default'   => 'full',
						'separator' => 'none',
						'condition' => array(
							'banner_item_type' => 'image',
						),
					)
				);

				$repeater->add_control(
					'img_link',
					array(
						'label'       => esc_html__( 'Link', 'alpha-core' ),
						'description' => esc_html__( 'Determines the URL which the picture will link to.', 'alpha-core' ),
						'type'        => Controls_Manager::URL,
						'placeholder' => esc_html__( 'https://your-link.com', 'alpha-core' ),
						'condition'   => array(
							'banner_item_type' => 'image',
						),
						'show_label'  => false,
					)
				);

				$repeater->add_responsive_control(
					'banner_divider_width',
					array(
						'label'       => esc_html__( 'Width', 'alpha-core' ),
						'description' => esc_html__( 'Controls the width of the divider.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'default'     => array(
							'size' => 50,
						),
						'size_units'  => array(
							'px',
							'%',
						),
						'range'       => array(
							'px' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'%'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'condition'   => array(
							'banner_item_type' => 'divider',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'width: {{SIZE}}{{UNIT}}',
						),
					)
				);

				$repeater->add_responsive_control(
					'banner_divider_height',
					array(
						'label'       => esc_html__( 'Height', 'alpha-core' ),
						'description' => esc_html__( 'Controls the height of the divider.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'default'     => array(
							'size' => 4,
						),
						'size_units'  => array(
							'px',
							'%',
						),
						'range'       => array(
							'px' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'%'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'condition'   => array(
							'banner_item_type' => 'divider',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'border-top-width: {{SIZE}}{{UNIT}}',
						),
					)
				);

				/* ----- Hotspot ----- */
				alpha_elementor_hotspot_layout_controls( $repeater, 'hotspot_', 'banner_item_type', 'hotspot', true );
				/* ----- Hotspot ----- */

				$repeater->add_control(
					'banner_item_display',
					array(
						'label'       => esc_html__( 'Inline Item', 'alpha-core' ),
						'description' => esc_html__( 'Choose the display type of content item.', 'alpha-core' ),
						'separator'   => 'before',
						'type'        => Controls_Manager::SWITCHER,
						'condition'   => array( 'banner_item_type!' => 'hotspot' ),
					)
				);

				$repeater->add_control(
					'banner_item_aclass',
					array(
						'label'       => esc_html__( 'Custom Class', 'alpha-core' ),
						'description' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'alpha-core' ),
						'type'        => Controls_Manager::TEXT,
						'condition'   => array( 'banner_item_type!' => 'hotspot' ),
					)
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'tab_banner_style',
				array(
					'label' => esc_html__( 'Style', 'alpha-core' ),
				)
			);

				$repeater->add_control(
					'banner_text_color',
					array(
						'label'       => esc_html__( 'Color', 'alpha-core' ),
						'description' => esc_html__( 'Controls the color of text.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => array(
							'banner_item_type' => 'text',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}}.text, .elementor-element-{{ID}} {{CURRENT_ITEM}} .text' => 'color: {{VALUE}};',
						),
					)
				);
				$repeater->add_group_control(
					Group_Control_Typography::get_type(),
					array(
						'name'      => 'banner_text_typo',
						'condition' => array(
							'banner_item_type!' => array( 'image', 'divider', 'hotspot' ),
						),
						'selector'  => '.elementor-element-{{ID}} {{CURRENT_ITEM}}.text, .elementor-element-{{ID}} {{CURRENT_ITEM}} .text, .elementor-element-{{ID}} {{CURRENT_ITEM}}.btn, .elementor-element-{{ID}} {{CURRENT_ITEM}} .btn',
					)
				);

				$repeater->add_control(
					'divider_color',
					array(
						'label'       => esc_html__( 'Color', 'alpha-core' ),
						'description' => esc_html__( 'Controls the color of divider.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => array(
							'banner_item_type' => 'divider',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'border-color: {{VALUE}};',
						),
					)
				);

				$repeater->add_responsive_control(
					'banner_image_width',
					array(
						'label'       => esc_html__( 'Width', 'alpha-core' ),
						'description' => esc_html__( 'Set the width the image should take up.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array(
							'px',
							'%',
						),
						'condition'   => array(
							'banner_item_type' => 'image',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner-item{{CURRENT_ITEM}}.image, .elementor-element-{{ID}} .banner-item{{CURRENT_ITEM}} .image' => 'width: {{SIZE}}{{UNIT}}',
						),
					)
				);

				$repeater->add_control(
					'banner_btn_border_radius',
					array(
						'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
						'description' => esc_html__( 'Controls the border radius.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array(
							'px',
							'%',
						),
						'condition'   => array(
							'banner_item_type!' => array( 'text', 'hotspot', 'button' ),
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}} img, .elementor-element-{{ID}} {{CURRENT_ITEM}}.divider-wrap, .elementor-element-{{ID}} {{CURRENT_ITEM}} .divider' => 'border-radius: {{SIZE}}{{UNIT}};',
						),
					)
				);

				$repeater->add_responsive_control(
					'banner_item_margin',
					array(
						'label'       => esc_html__( 'Margin', 'alpha-core' ),
						'description' => esc_html__( 'Controls the margin of item.', 'alpha-core' ),
						'type'        => Controls_Manager::DIMENSIONS,
						'size_units'  => array( 'px', '%', 'em' ),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'   => array(
							'banner_item_type!' => 'hotspot',
						),
					)
				);

				/* Hotspot Style Controls */
				alpha_elementor_hotspot_style_controls( $repeater, 'hotspot_', 'banner_item_type', 'hotspot', true );
				/* Hotspot Style Controls */

				$repeater->add_control(
					'_animation',
					array(
						'label'              => esc_html__( 'Entrance Animation', 'alpha-core' ),
						'description'        => esc_html__( 'Select the type of animation to use on the item.', 'alpha-core' ),
						'type'               => Controls_Manager::ANIMATION,
						'frontend_available' => true,
						'separator'          => 'before',
						'condition'          => array( 'banner_item_type!' => 'hotspot' ),
					)
				);

				$repeater->add_control(
					'reveal_effect_color',
					array(
						'label'       => esc_html__( 'Animation Color', 'alpha-core' ),
						'description' => esc_html__( 'Controls the color of the reveal amination.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => array(
							'_animation'        => array( 'revealInDown', 'revealInLeft', 'revealInRight', 'revealInUp' ),
							'banner_item_type!' => 'hotspot',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => '--alpha-reveal-animation-color: {{VALUE}};',
						),
					),
				);

				$repeater->add_control(
					'animation_duration',
					array(
						'label'        => esc_html__( 'Animation Duration', 'alpha-core' ),
						'type'         => Controls_Manager::SELECT,
						'default'      => '',
						'options'      => array(
							'slow' => esc_html__( 'Slow', 'alpha-core' ),
							''     => esc_html__( 'Normal', 'alpha-core' ),
							'fast' => esc_html__( 'Fast', 'alpha-core' ),
						),
						'prefix_class' => 'animated-',
						'condition'    => array(
							'_animation!'       => '',
							'banner_item_type!' => 'hotspot',
						),
					)
				);

				$repeater->add_control(
					'_animation_delay',
					array(
						'label'              => esc_html__( 'Animation Delay', 'alpha-core' ) . ' (ms)',
						'type'               => Controls_Manager::NUMBER,
						'min'                => 0,
						'step'               => 100,
						'condition'          => array(
							'_animation!'       => '',
							'banner_item_type!' => 'hotspot',
						),
						'render_type'        => 'none',
						'frontend_available' => true,
					)
				);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'banner_item_floating',
				array(
					'label' => esc_html__( 'Scroll Effect', 'alpha-core' ),
				)
			);

				alpha_elementor_addon_controls( $repeater, 'banner' );

			$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$presets = array(
			array(
				'banner_item_type'    => 'text',
				'banner_item_display' => '',
				'banner_text_content' => sprintf( esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nummy nibh %seuismod tincidunt ut laoreet dolore magna aliquam erat volutpat.', 'alpha-core' ), '<br/>' ),
				'banner_text_tag'     => 'p',
			),
			array(
				'banner_item_type'    => 'button',
				'banner_item_display' => 'yes',
				'banner_btn_text'     => esc_html__( 'Click here', 'alpha-core' ),
				'button_type'         => '',
				'button_skin'         => 'btn-white',
			),
		);

		alpha_elementor_banner_layout_controls( $self );

		$self->end_controls_section();

		$self->start_controls_section(
			'section_banner_content',
			array(
				'label' => esc_html__( 'Banner Content', 'alpha-core' ),
			)
		);

		$self->add_responsive_control(
			'banner_text_align',
			array(
				'label'       => esc_html__( 'Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Select the content\'s alignment.', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
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
					'.elementor-element-{{ID}} .banner-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'banner_item_list',
			array(
				'label'       => esc_html__( 'Content Items', 'alpha-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $presets,
				'title_field' => sprintf( '{{{ banner_item_type == "hotspot" ? \'%6$s\' : ( banner_item_type == "text" ? \'%1$s\' : ( banner_item_type == "image" ? \'%2$s\' : ( banner_item_type == "button" ? \'%3$s\' : \'%4$s\' ) ) ) }}}  {{{ banner_item_type == "hotspot" ? \'%7$s\' : ( banner_item_type == "text" ? banner_text_content : ( banner_item_type == "image" ? banner_image[\'url\'] : ( banner_item_type == "button" ?  banner_btn_text : \'%5$s\' ) ) ) }}}', '<i class="eicon-t-letter"></i>', '<i class="eicon-image"></i>', '<i class="eicon-button"></i>', '<i class="eicon-divider"></i>', esc_html__( 'Divider', 'alpha-core' ), '<i class="eicon-image-hotspot"></i>', esc_html__( 'Hotspot', 'alpha-core' ) ),
			)
		);

		$self->end_controls_section();

		/* Banner Style */
		alpha_elementor_banner_style_controls( $self );
	}
}

/**
 * Render banner.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_products_render_banner' ) ) {
	function alpha_products_render_banner( $self, $atts ) {
		$atts['self'] = $self;
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/banner/render-banner-elementor.php' );
	}
}


/**
 * Register elementor layout controls for section & widget banner.
 *
 * @since 1.0
 * @since 1.2.0 Merged section's banner and banner widget controls
 */
if ( ! function_exists( 'alpha_elementor_banner_layout_controls' ) ) {
	function alpha_elementor_banner_layout_controls( $self, $widget = true ) {

		$self->add_responsive_control(
			'banner_min_height',
			array(
				'label'       => esc_html__( 'Min Height', 'alpha-core' ),
				'description' => esc_html__( 'Controls min height value of banner.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'rem',
					'%',
					'vh',
				),
				'range'       => array(
					'px'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 700,
					),
					'rem' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'%'   => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
					'vh'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => $widget ? array(
					'.elementor-element-{{ID}} .banner' => 'min-height:{{SIZE}}{{UNIT}};',
				) : array(
					'.elementor .elementor-element-{{ID}}' => 'min-height:{{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} > .elementor-container' => 'min-height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$self->add_responsive_control(
			'banner_max_height',
			array(
				'label'       => esc_html__( 'Max Height', 'alpha-core' ),
				'description' => esc_html__( 'Controls max height value of banner.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'rem',
					'%',
					'vh',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 700,
					),
				),
				'selectors'   => $widget ? array(
					'{{WRAPPER}}, .elementor-element-{{ID}} .banner, .elementor-element-{{ID}} img' => 'max-height:{{SIZE}}{{UNIT}};overflow:hidden;',
				) : array(
					'.elementor .elementor-element-{{ID}}' => 'max-height:{{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} > .elementor-container' => 'max-height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		if ( $widget ) {
			$self->add_control(
				'banner_wrap',
				array(
					'label'       => esc_html__( 'Wrap with', 'alpha-core' ),
					'description' => esc_html__( 'Choose to wrap banner content in Fullscreen banner.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						''                => esc_html__( 'None', 'alpha-core' ),
						'container'       => esc_html__( 'Container', 'alpha-core' ),
						'container-fluid' => esc_html__( 'Container Fluid', 'alpha-core' ),
					),
				)
			);
		}

		$self->add_control(
			'banner_background_color',
			array(
				'label'       => esc_html__( 'Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the background color of banner.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#eee',
				'selectors'   => array(
					'.elementor-element-{{ID}} .banner' => 'background-color: {{VALUE}};',
				),
			)
		);

		$self->add_control(
			'banner_background_image',
			array(
				'label'       => esc_html__( 'Choose Image', 'alpha-core' ),
				'description' => esc_html__( 'Upload an image to display.', 'alpha-core' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => defined( 'ALPHA_ASSETS' ) ? ( ALPHA_ASSETS . '/images/placeholders/banner-placeholder.jpg' ) : \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$self->add_responsive_control(
			'banner_img_pos',
			array(
				'label'       => esc_html__( 'Image Position (%)', 'alpha-core' ),
				'description' => esc_html__( 'Changes image position when image is larger than render area.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'%' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .banner-img img' => 'object-position: {{SIZE}}%;',
				),
				'condition'   => $widget ? array(
					'parallax!'         => 'yes',
					'background_effect' => '',
				) : array(
					'parallax!'            => 'yes',
					'background_effect'    => '',
					'video_banner_switch!' => 'yes',
				),
			)
		);
	}
}

/**
 * Register elementor style controls for section & widget banner.
 *
 * @since 1.2.0
 */
if ( ! function_exists( 'alpha_elementor_banner_style_controls' ) ) {
	function alpha_elementor_banner_style_controls( $self, $condition_value = '', $widget = true ) {

		if ( $widget ) {
			$self->start_controls_section(
				'section_banner_style',
				array(
					'label' => esc_html__( 'Banner Wrapper', 'alpha-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

				$self->add_control(
					'stretch_height',
					array(
						'type'        => Controls_Manager::SWITCHER,
						'label'       => esc_html__( 'Stretch height as Parent\'s', 'alpha-core' ),
						'description' => esc_html__( 'You can make your banner height full of its parent', 'alpha-core' ),
					)
				);

			$self->end_controls_section();

			$self->start_controls_section(
				'banner_layer_layout',
				array(
					'label' => esc_html__( 'Banner Content', 'alpha-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

				$self->add_responsive_control(
					'banner_width',
					array(
						'label'       => esc_html__( 'Width', 'alpha-core' ),
						'description' => esc_html__( 'Changes banner content width.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array( 'px', '%' ),
						'range'       => array(
							'px' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 1000,
							),
							'%'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'default'     => array(
							'unit' => '%',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner .banner-content' => 'max-width:{{SIZE}}{{UNIT}}; width: 100%',
						),
					)
				);

				$self->add_responsive_control(
					'banner_content_padding',
					array(
						'label'       => esc_html__( 'Padding', 'alpha-core' ),
						'description' => esc_html__( 'Controls padding of banner content.', 'alpha-core' ),
						'type'        => Controls_Manager::DIMENSIONS,
						'default'     => array(
							'unit' => 'px',
						),
						'size_units'  => array( 'px', '%' ),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$self->add_control(
					'banner_origin',
					array(
						'label'       => esc_html__( 'Origin X, Y', 'alpha-core' ),
						'description' => esc_html__( 'Set base point of banner content to determine content position.', 'alpha-core' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 't-mc',
						'options'     => array(
							't-none' => esc_html__( '---------- ----------', 'alpha-core' ),
							't-m'    => esc_html__( '---------- Center', 'alpha-core' ),
							't-c'    => esc_html__( 'Center ----------', 'alpha-core' ),
							't-mc'   => esc_html__( 'Center Center', 'alpha-core' ),
						),
					)
				);

				$self->start_controls_tabs( 'banner_position_tabs' );

				$self->start_controls_tab(
					'banner_pos_left_tab',
					array(
						'label' => esc_html__( 'Left', 'alpha-core' ),
					)
				);

				$self->add_responsive_control(
					'banner_left',
					array(
						'label'       => esc_html__( 'Left Offset', 'alpha-core' ),
						'description' => esc_html__( 'Set Left position of banner content.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array(
							'px',
							'rem',
							'%',
							'vw',
						),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 500,
							),
							'rem' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'%'   => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'vw'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'default'     => array(
							'size' => 50,
							'unit' => '%',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner .banner-content' => 'left:{{SIZE}}{{UNIT}};',
						),
					)
				);

				$self->end_controls_tab();

				$self->start_controls_tab(
					'banner_pos_top_tab',
					array(
						'label' => esc_html__( 'Top', 'alpha-core' ),
					)
				);

				$self->add_responsive_control(
					'banner_top',
					array(
						'label'       => esc_html__( 'Top Offset', 'alpha-core' ),
						'description' => esc_html__( 'Set Top position of banner content.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array(
							'px',
							'rem',
							'%',
							'vh',
						),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 500,
							),
							'rem' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'%'   => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'vh'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'default'     => array(
							'size' => 50,
							'unit' => '%',
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner .banner-content' => 'top:{{SIZE}}{{UNIT}};',
						),
					)
				);

				$self->end_controls_tab();

				$self->start_controls_tab(
					'banner_pos_right_tab',
					array(
						'label' => esc_html__( 'Right', 'alpha-core' ),
					)
				);

				$self->add_responsive_control(
					'banner_right',
					array(
						'label'       => esc_html__( 'Right Offset', 'alpha-core' ),
						'description' => esc_html__( 'Set Right position of banner content.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array(
							'px',
							'rem',
							'%',
							'vw',
						),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 500,
							),
							'rem' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'%'   => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'vw'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner .banner-content' => 'right:{{SIZE}}{{UNIT}};',
						),
					)
				);

				$self->end_controls_tab();

				$self->start_controls_tab(
					'banner_pos_bottom_tab',
					array(
						'label' => esc_html__( 'Bottom', 'alpha-core' ),
					)
				);

				$self->add_responsive_control(
					'banner_bottom',
					array(
						'label'       => esc_html__( 'Bottom Offset', 'alpha-core' ),
						'description' => esc_html__( 'Set Bottom position of banner content.', 'alpha-core' ),
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array(
							'px',
							'rem',
							'%',
							'vh',
						),
						'range'       => array(
							'px'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 500,
							),
							'rem' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'%'   => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
							'vh'  => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 100,
							),
						),
						'selectors'   => array(
							'.elementor-element-{{ID}} .banner .banner-content' => 'bottom:{{SIZE}}{{UNIT}};',
						),
					)
				);

				$self->end_controls_tab();

				$self->end_controls_tabs();

			$self->end_controls_section();
		}

		$self->start_controls_section(
			'banner_effect',
			$widget ? array(
				'label' => esc_html__( 'Banner Effect', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			) : array(
				'label'     => alpha_elementor_panel_heading( esc_html__( 'Banner Effect', 'alpha-core' ) ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$condition_value       => 'banner',
					'video_banner_switch!' => 'yes',
				),
			)
		);

		if ( $widget ) {
			$self->add_control(
				'banner_image_effect',
				array(
					'label' => esc_html__( 'Image Effect', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);
		}

			$self->add_control(
				'overlay',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Hover Effect', 'alpha-core' ),
					'description' => esc_html__( 'Note: Please avoid giving Hover Effect and Background Effect together.', 'alpha-core' ),
					'options'     => array(
						''           => esc_html__( 'No', 'alpha-core' ),
						'light'      => esc_html__( 'Light', 'alpha-core' ),
						'dark'       => esc_html__( 'Dark', 'alpha-core' ),
						'zoom'       => esc_html__( 'Zoom', 'alpha-core' ),
						'zoom_light' => esc_html__( 'Zoom and Light', 'alpha-core' ),
						'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha-core' ),
						'effect-1'   => esc_html__( 'Effect 1', 'alpha-core' ),
						'effect-2'   => esc_html__( 'Effect 2', 'alpha-core' ),
						'effect-3'   => esc_html__( 'Effect 3', 'alpha-core' ),
						'effect-4'   => esc_html__( 'Effect 4', 'alpha-core' ),
						'effect-5'   => esc_html__( 'Effect 5', 'alpha-core' ),
						'effect-6'   => esc_html__( 'Effect 6', 'alpha-core' ),
						'effect-7'   => esc_html__( 'Effect 7', 'alpha-core' ),
					),
					'condition'   => array(
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'banner_overlay_color',
				array(
					'label'       => esc_html__( 'Hover Effect Color', 'alpha-core' ),
					'description' => esc_html__( 'Choose banner overlay color on hover.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .banner figure:after, .elementor-element-{{ID}} .overlay-effect:after,.elementor-element-{{ID}} .overlay-effect:before' => 'background-color: {{VALUE}};',
					),
					'condition'   => array(
						'overlay!'  => array( '', 'zoom', 'effect-5', 'effect-6', 'effect-7' ),
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'banner_overlay_color1',
				array(
					'label'       => esc_html__( 'Hover Effect Color', 'alpha-core' ),
					'description' => esc_html__( 'Choose banner overlay color on hover.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .overlay-effect-5:before, .elementor-element-{{ID}} .overlay-effect-7:before, .elementor-element-{{ID}} .overlay-effect-6+figure:before' => 'background-color: {{VALUE}};',
					),
					'condition'   => array(
						'overlay'   => array( 'effect-5', 'effect-6', 'effect-7' ),
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'overlay_border_color',
				array(
					'label'       => esc_html__( 'Hover Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Choose overlay border color on hover.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .overlay-effect-5:after, .elementor-element-{{ID}} .overlay-effect-6:after, .elementor-element-{{ID}} .overlay-effect-6:before, .elementor-element-{{ID}} .overlay-effect-7:after' => 'border-color: {{VALUE}};',
					),
					'condition'   => array(
						'overlay'   => array( 'effect-5', 'effect-6', 'effect-7' ),
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'border_angle',
				array(
					'type'      => Controls_Manager::SLIDER,
					'label'     => __( 'Border Angle', 'alpha-core' ),
					'selectors' => array(
						'.elementor-element-{{ID}} .overlay-effect-5:after' => 'transform: rotate3d(0,0,1,{{SIZE}}deg) scale3d(1,0,1);',
						'.elementor-element-{{ID}} .overlay-wrapper:hover .overlay-effect-5:after' => 'transform: rotate3d(0,0,1,{{SIZE}}deg) scale3d(1,1,1);',
					),
					'condition' => array(
						'overlay'   => 'effect-5',
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'dropdown_box_shadow',
					'selector'  => '.elementor-element-{{ID}} .overlay-effect-7:after',
					'condition' => array(
						'overlay'   => 'effect-7',
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'overlay_filter',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Hover Filter Effect', 'alpha-core' ),
					'description' => esc_html__( 'Choose banner filter effect on hover.', 'alpha-core' ),
					'options'     => array(
						''                   => esc_html__( 'No', 'alpha-core' ),
						'blur(4px)'          => esc_html__( 'Blur', 'alpha-core' ),
						'brightness(1.5)'    => esc_html__( 'Brightness', 'alpha-core' ),
						'contrast(1.5)'      => esc_html__( 'Contrast', 'alpha-core' ),
						'grayscale(1)'       => esc_html__( 'Greyscale', 'alpha-core' ),
						'hue-rotate(270deg)' => esc_html__( 'Hue Rotate', 'alpha-core' ),
						'opacity(0.5)'       => esc_html__( 'Opacity', 'alpha-core' ),
						'saturate(3)'        => esc_html__( 'Saturate', 'alpha-core' ),
						'sepia(0.5)'         => esc_html__( 'Sepia', 'alpha-core' ),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .banner img' => 'transition: transform .3s, filter .3s;',
						'.elementor-element-{{ID}} .banner:hover img' => 'filter: {{VALUE}};',
					),
					'condition'   => array(
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'background_effect',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Background Effect', 'alpha-core' ),
					'options'     => array(
						''                   => esc_html__( 'No', 'alpha-core' ),
						'kenBurnsToRight'    => esc_html__( 'kenBurnsRight', 'alpha-core' ),
						'kenBurnsToLeft'     => esc_html__( 'kenBurnsLeft', 'alpha-core' ),
						'kenBurnsToLeftTop'  => esc_html__( 'kenBurnsLeftTop', 'alpha-core' ),
						'kenBurnsToRightTop' => esc_html__( 'kenBurnsRightTop', 'alpha-core' ),
					),
					'description' => esc_html__( 'Note: Please avoid giving Hover Effect and Background Effect together.', 'alpha-core' ),
					'condition'   => array(
						'parallax!' => 'yes',
					),
				)
			);

			$self->add_control(
				'background_effect_duration',
				array(
					'label'       => esc_html__( 'Background Effect Duration (s)', 'alpha-core' ),
					'description' => esc_html__( 'Set banner background effect duration time.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						's',
					),
					'default'     => array(
						'size' => 30,
						'unit' => 's',
					),
					'range'       => array(
						's' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 60,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .background-effect' => 'animation-duration:{{SIZE}}s;',
					),
					'condition'   => array(
						'parallax!'          => 'yes',
						'background_effect!' => '',
					),
				)
			);

			$self->add_control(
				'particle_effect',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Particle Effects', 'alpha-core' ),
					'description' => esc_html__( 'Shows animating particles over banner image. Choose from Snowfall, Sparkle.', 'alpha-core' ),
					'options'     => array(
						''         => esc_html__( 'No', 'alpha-core' ),
						'snowfall' => esc_html__( 'Snowfall', 'alpha-core' ),
						'sparkle'  => esc_html__( 'Sparkle', 'alpha-core' ),
					),
				)
			);

			$self->add_control(
				'parallax',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Enable Parallax', 'alpha-core' ),
					'description' => esc_html__( 'Set to enable parallax effect for banner.', 'alpha-core' ),
					'condition'   => array(
						'overlay'           => '',
						'overlay_filter'    => '',
						'background_effect' => '',
					),
				)
			);

		if ( $widget ) {
			$self->add_control(
				'banner_content_effect',
				array(
					'label'     => esc_html__( 'Content Effect', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$self->add_control(
				'_content_animation',
				array(
					'label'              => esc_html__( 'Content Entrance Animation', 'alpha-core' ),
					'description'        => esc_html__( 'Set entrance animation for entire banner content.', 'alpha-core' ),
					'type'               => Controls_Manager::ANIMATION,
					'frontend_available' => true,
				)
			);

			$self->add_control(
				'content_animation_duration',
				array(
					'label'        => esc_html__( 'Animation Duration', 'alpha-core' ),
					'description'  => esc_html__( 'Determine how long entrance animation should be shown.', 'alpha-core' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => '',
					'options'      => array(
						'slow' => esc_html__( 'Slow', 'alpha-core' ),
						''     => esc_html__( 'Normal', 'alpha-core' ),
						'fast' => esc_html__( 'Fast', 'alpha-core' ),
					),
					'prefix_class' => 'animated-',
					'condition'    => array(
						'_content_animation!' => '',
					),
				)
			);

			$self->add_control(
				'_content_animation_delay',
				array(
					'label'              => esc_html__( 'Animation Delay', 'alpha-core' ) . ' (ms)',
					'description'        => esc_html__( 'Set delay time for content entrance animation.', 'alpha-core' ),
					'type'               => Controls_Manager::NUMBER,
					'min'                => 0,
					'step'               => 100,
					'condition'          => array(
						'_content_animation!' => '',
					),
					'render_type'        => 'none',
					'frontend_available' => true,
				)
			);
		}

		$self->end_controls_section();

		$self->start_controls_section(
			'parallax_options',
			$widget ? array(
				'label'     => esc_html__( 'Parallax', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'parallax' => 'yes',
				),
			) : array(
				'label'     => esc_html__( 'Banner', 'alpha-core' ) . ' ' . alpha_elementor_panel_heading( esc_html__( 'Parallax', 'alpha-core' ) ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$condition_value       => 'banner',
					'parallax'             => 'yes',
					'video_banner_switch!' => 'yes',
				),
			)
		);

			$self->add_control(
				'parallax_direction',
				array(
					'label'       => esc_html__( 'Direction', 'alpha-core' ),
					'description' => esc_html__( 'Choose moving direction of background when scroll down.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						'up'    => array(
							'title' => esc_html__( 'Up', 'alpha-core' ),
							'icon'  => 'eicon-arrow-up',
						),
						'down'  => array(
							'title' => esc_html__( 'Down', 'alpha-core' ),
							'icon'  => 'eicon-arrow-down',
						),
						'left'  => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-arrow-left',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-arrow-right',
						),
					),
					'default'     => 'down',
					'toggle'      => false,
				)
			);

			$self->add_control(
				'parallax_speed',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Parallax Speed', 'alpha-core' ),
					'description' => esc_html__( 'Change speed of banner parallax effect.', 'alpha-core' ),
					'condition'   => array(
						'parallax' => 'yes',
					),
					'default'     => array(
						'size' => 3,
						'unit' => 'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
					),
				)
			);

		$self->end_controls_section();

		if ( ! $widget ) {
			$self->start_controls_section(
				'section_video_style',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Banner Video', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						$condition_value      => 'banner',
						'video_banner_switch' => 'yes',
						'lightbox'            => 'yes',
					),
				)
			);

				$self->add_control(
					'aspect_ratio',
					array(
						'label'              => esc_html__( 'Aspect Ratio', 'alpha-core' ),
						'type'               => Controls_Manager::SELECT,
						'options'            => array(
							'169' => '16:9',
							'219' => '21:9',
							'43'  => '4:3',
							'32'  => '3:2',
							'11'  => '1:1',
							'916' => '9:16',
						),
						'default'            => '169',
						'prefix_class'       => 'elementor-aspect-ratio-',
						'frontend_available' => true,
					)
				);

				$self->add_group_control(
					Group_Control_Css_Filter::get_type(),
					array(
						'name'     => 'video_css_filters',
						'selector' => '#elementor-lightbox-{{ID}} .elementor-fit-aspect-ratio',
					)
				);

				$self->add_responsive_control(
					'video_border_radius',
					array(
						'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array(
							'px',
							'%',
							'rem',
						),
						'selectors'  => array(
							'#elementor-lightbox-{{ID}} .elementor-fit-aspect-ratio' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$self->add_control(
					'play_icon_title',
					array(
						'label'     => esc_html__( 'Play Icon', 'alpha-core' ),
						'type'      => Controls_Manager::HEADING,
						'condition' => array(
							'show_image_overlay' => 'yes',
							'show_play_icon'     => 'yes',
						),
						'separator' => 'before',
					)
				);

				$self->add_control(
					'play_icon_color',
					array(
						'label'     => esc_html__( 'Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'.elementor-element-{{ID}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'show_image_overlay' => 'yes',
							'show_play_icon'     => 'yes',
						),
					)
				);

				$self->add_responsive_control(
					'play_icon_size',
					array(
						'label'     => esc_html__( 'Size', 'alpha-core' ),
						'type'      => Controls_Manager::SLIDER,
						'range'     => array(
							'px' => array(
								'min' => 10,
								'max' => 300,
							),
						),
						'selectors' => array(
							'.elementor-element-{{ID}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
						),
						'condition' => array(
							'show_image_overlay' => 'yes',
							'show_play_icon'     => 'yes',
						),
					)
				);

				$self->add_group_control(
					Group_Control_Text_Shadow::get_type(),
					array(
						'name'           => 'play_icon_text_shadow',
						'selector'       => '.elementor-element-{{ID}} .elementor-custom-embed-play i',
						'fields_options' => array(
							'text_shadow_type' => array(
								'label' => _x( 'Shadow', 'Text Shadow Control', 'alpha-core' ),
							),
						),
						'condition'      => array(
							'show_image_overlay' => 'yes',
							'show_play_icon'     => 'yes',
						),
					)
				);

			$self->end_controls_section();

			$self->start_controls_section(
				'section_lightbox_style',
				array(
					'label'     => esc_html__( 'Lightbox', 'alpha-core' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'use_as'              => 'banner',
						'video_banner_switch' => 'yes',
						'show_image_overlay'  => 'yes',
						'image_overlay[url]!' => '',
						'lightbox'            => 'yes',
					),
				)
			);

				$self->add_control(
					'lightbox_color',
					array(
						'label'     => esc_html__( 'Background Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'lightbox_ui_color',
					array(
						'label'     => esc_html__( 'UI Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
						),
					)
				);

				$self->add_control(
					'lightbox_ui_color_hover',
					array(
						'label'     => esc_html__( 'UI Hover Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
						),
						'separator' => 'after',
					)
				);

				$self->add_control(
					'lightbox_video_width',
					array(
						'label'     => esc_html__( 'Content Width', 'alpha-core' ),
						'type'      => Controls_Manager::SLIDER,
						'default'   => array(
							'unit' => '%',
						),
						'range'     => array(
							'%' => array(
								'min' => 30,
							),
						),
						'selectors' => array(
							'(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
						),
					)
				);

				$self->add_control(
					'lightbox_content_position',
					array(
						'label'                => esc_html__( 'Content Position', 'alpha-core' ),
						'type'                 => Controls_Manager::SELECT,
						'frontend_available'   => true,
						'options'              => array(
							''    => esc_html__( 'Center', 'alpha-core' ),
							'top' => esc_html__( 'Top', 'alpha-core' ),
						),
						'selectors'            => array(
							'#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
						),
						'selectors_dictionary' => array(
							'top' => 'top: 60px',
						),
					)
				);

				$self->add_responsive_control(
					'lightbox_content_animation',
					array(
						'label'              => esc_html__( 'Entrance Animation', 'alpha-core' ),
						'type'               => Controls_Manager::ANIMATION,
						'frontend_available' => true,
					)
				);

			$self->end_controls_section();

			$self->update_control(
				'color_link',
				array(
					'selectors' => array(
						'.elementor-element-{{ID}} a' => 'color: {{VALUE}}',
					),
				)
			);

			$self->update_control(
				'color_link_hover',
				array(
					'selectors' => array(
						'.elementor-element-{{ID}} a:hover' => 'color: {{VALUE}}',
					),
				)
			);
		}
	}
}

/**
 * Register elementor layout controls for column banner layer.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_banner_layer_layout_controls' ) ) {
	function alpha_elementor_banner_layer_layout_controls( $self, $condition_key ) {

		$self->start_controls_section(
			'banner_layer_layout',
			array(
				'label'     => alpha_elementor_panel_heading( esc_html__( 'Banner Layer', 'alpha-core' ) ),
				'tab'       => Controls_Manager::TAB_LAYOUT,
				'condition' => array(
					$condition_key => 'banner_layer',
				),
			)
		);
			$self->add_control(
				'banner_text_align',
				array(
					'label'       => esc_html__( 'Text Alignment', 'alpha-core' ),
					'description' => esc_html__( 'Select the content\'s alignment.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'default'     => 'center',
					'options'     => array(
						'left'    => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'  => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'   => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
						'justify' => array(
							'title' => esc_html__( 'Justify', 'alpha-core' ),
							'icon'  => 'eicon-text-align-justify',
						),
					),
					'selectors'   => array(
						'{{WRAPPER}}' => 'text-align: {{VALUE}}',
					),
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
					'toggle'      => false,
				)
			);

			$self->add_control(
				'banner_origin',
				array(
					'label'       => esc_html__( 'Origin', 'alpha-core' ),
					'description' => esc_html__( 'Set base point of banner content to determine content position.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						't-m'  => array(
							'title' => esc_html__( 'Vertical Center', 'alpha-core' ),
							'icon'  => 'eicon-v-align-middle',
						),
						't-c'  => array(
							'title' => esc_html__( 'Horizontal Center', 'alpha-core' ),
							'icon'  => 'eicon-h-align-center',
						),
						't-mc' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-frame-minimize',
						),
					),
					'default'     => 't-mc',
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->start_controls_tabs( 'banner_position_tabs' );

			$self->start_controls_tab(
				'banner_pos_left_tab',
				array(
					'label'     => esc_html__( 'Left', 'alpha-core' ),
					'condition' => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->add_responsive_control(
				'banner_left',
				array(
					'label'       => esc_html__( 'Left Offset', 'alpha-core' ),
					'description' => esc_html__( 'Set Left position of banner content.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
						'%',
						'vw',
					),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 500,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'%'   => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'vw'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'default'     => array(
						'size' => 50,
						'unit' => '%',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}}.banner-content,.elementor-element-{{ID}}>.banner-content,.elementor-element-{{ID}}>div>.banner-content' => 'left:{{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'banner_pos_top_tab',
				array(
					'label'     => esc_html__( 'Top', 'alpha-core' ),
					'condition' => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->add_responsive_control(
				'banner_top',
				array(
					'label'       => esc_html__( 'Top Offset', 'alpha-core' ),
					'description' => esc_html__( 'Set Top position of banner content.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
						'%',
						'vh',
					),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 500,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'%'   => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'vh'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'default'     => array(
						'size' => 50,
						'unit' => '%',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}}.banner-content,.elementor-element-{{ID}}>.banner-content,.elementor-element-{{ID}}>div>.banner-content' => 'top:{{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'banner_pos_right_tab',
				array(
					'label'     => esc_html__( 'Right', 'alpha-core' ),
					'condition' => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->add_responsive_control(
				'banner_right',
				array(
					'label'       => esc_html__( 'Right Offset', 'alpha-core' ),
					'description' => esc_html__( 'Set Right position of banner content.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
						'%',
						'vw',
					),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 500,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'%'   => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'vw'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}}.banner-content,.elementor-element-{{ID}}>.banner-content,.elementor-element-{{ID}}>div>.banner-content' => 'right:{{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->end_controls_tab();

			$self->start_controls_tab(
				'banner_pos_bottom_tab',
				array(
					'label'     => esc_html__( 'Bottom', 'alpha-core' ),
					'condition' => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->add_responsive_control(
				'banner_bottom',
				array(
					'label'       => esc_html__( 'Bottom Offset', 'alpha-core' ),
					'description' => esc_html__( 'Set Bottom position of banner content.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
						'%',
						'vw',
					),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 500,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'%'   => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
						'vw'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}}.banner-content,.elementor-element-{{ID}}>.banner-content,.elementor-element-{{ID}}>div>.banner-content' => 'bottom:{{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
				)
			);

			$self->end_controls_tab();

			$self->end_controls_tabs();

			$self->add_responsive_control(
				'banner_width',
				array(
					'label'       => esc_html__( 'Width', 'alpha-core' ),
					'description' => esc_html__( 'Changes banner content width.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', '%' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 1000,
						),
						'%'  => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'separator'   => 'before',
					'selectors'   => array(
						'.elementor-element-{{ID}}.banner-content,.elementor-element-{{ID}}>.banner-content,.elementor-element-{{ID}}>div>.banner-content' => 'max-width:{{SIZE}}{{UNIT}};width: 100%;',
					),
					'condition'   => array(
						$condition_key => 'banner_layer',
					),
				)
			);

		$self->end_controls_section();
	}
}
