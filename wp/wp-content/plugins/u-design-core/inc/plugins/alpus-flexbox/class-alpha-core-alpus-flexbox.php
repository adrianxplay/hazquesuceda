<?php
/**
 * Alpha Alpus Flexbox Class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.9.0
 * @version    4.9.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Plugin;
use Elementor\Controls_Manager;

class Alpha_Core_Alpus_Flexbox extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.9
	 */
	public function __construct() {
		// Remove admin menu
		add_action( 'admin_menu', array( $this, 'customize_admin_menus' ), 99 );
		add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );
		add_action( 'elementor/element/alpus-nested-slider/section_slider_options/before_section_end', array( $this, 'add_slider_layout_options' ), 10, 2 );
		add_action( 'elementor/element/alpus-nested-slider/section_style_dots/before_section_end', array( $this, 'add_slider_dot_options' ), 10, 2 );
	}

	/**
	 * Customize alpus flexbox admin menu
	 *
	 * @since 4.9.0
	 */
	public function customize_admin_menus() {
		global $menu;

		$admin_menus = array();

		foreach ( $menu as $key => $menu_item ) {
			if ( isset( $menu_item[2] ) && 'alpus-addons' == $menu_item[2] ) {
			} else {
				$admin_menus[ $key ] = $menu_item;
			}
		}

		$menu = $admin_menus;
	}

	/**
	 * Enqueue Scripts
	 *
	 * @since 4.9.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'alpha-alpus-flexbox-slider', ALPHA_CORE_INC_URI . '/plugins/alpus-flexbox/flexbox-slider' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
		wp_enqueue_script( 'alpha-alpus-flexbox', ALPHA_CORE_INC_URI . '/plugins/alpus-flexbox/alpus-flexbox' . ALPHA_JS_SUFFIX, array(), ALPHA_CORE_VERSION, true );
	}

	/**
	 * Add custom slider layout options
	 *
	 * @return array
	 */
	public function add_slider_layout_options( $self, $args ) {

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$self->add_control(
			'slides_to_show_xl',
			array(
				'label'              => esc_html__( 'Slides to Show ( >= 1200px )', 'alpus-flexbox' ),
				'description'        => esc_html__( 'Select number of slide items to display on large display( >= 1200px ). ', 'alpus-flexbox' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'step'               => 0.5,
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .elementor-main-swiper:not(.' . $swiper_class . '-initialized) .swiper-slide' => 'max-width: calc(100% / var(--alpus-nested-carousel-slides-to-show, 1));',
					'{{WRAPPER}}' => '--alpus-nested-carousel-slides-to-show: {{VALUE}}',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'slides_to_show',
				),
			)
		);

		$self->add_control(
			'slides_to_show_min',
			array(
				'label'              => esc_html__( 'Slides to Show ( < 576px )', 'alpus-flexbox' ),
				'description'        => esc_html__( 'Select number of slide items to display on mobile( < 576px ). ', 'alpus-flexbox' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'step'               => 0.5,
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .elementor-main-swiper:not(.' . $swiper_class . '-initialized) .swiper-slide' => 'max-width: calc(100% / var(--alpus-nested-carousel-slides-to-show, 1));',
					'{{WRAPPER}}' => '--alpus-nested-carousel-slides-to-show: {{VALUE}}',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'slides_to_scroll',
				),
			)
		);

		$self->add_control(
			'focus_on_active',
			array(
				'label'       => esc_html__( 'Focus On Center Item', 'alpus-flexbox' ),
				'description' => esc_html__( 'Focus on active slide with zoom or highlight effect.', 'alpus-flexbox' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'center_mode' => 'yes',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'disable_drag',
				),
			)
		);

		$self->add_control(
			'focus_effect',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Focus Effect', 'alpus-flexbox' ),
				'description' => esc_html__( 'Focus Effect for active slide.', 'alpus-flexbox' ),
				'default'     => 'scale',
				'options'     => array(
					'scale'         => esc_html__( 'Scale', 'alpus-flexbox' ),
					'opacity'       => esc_html__( 'Opacity', 'alpus-flexbox' ),
					'scale_opacity' => esc_html__( 'Scale & Opacity', 'alpus-flexbox' ),
				),
				'condition'   => array(
					'center_mode'     => 'yes',
					'focus_on_active' => 'yes',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'disable_drag',
				),
			)
		);

		$self->add_responsive_control(
			'scale_size',
			array(
				'label'       => esc_html__( 'Scale Size', 'alpus-flexbox' ),
				'description' => esc_html__( 'Input the scale size you want for active slide.', 'alpus-flexbox' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 3,
				'step'        => 0.1,
				'default'     => 1.3,
				'selectors'   => array(
					'.elementor-element-{{ID}} .swiper-zoom-in-active-slide .swiper-slide-active' => 'transform: scale({{SIZE}});',
				),
				'condition'   => array(
					'center_mode'     => 'yes',
					'focus_on_active' => 'yes',
					'focus_effect'    => array( 'scale', 'scale_opacity' ),
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'disable_drag',
				),
			)
		);

		$self->add_responsive_control(
			'viewport_spacing',
			array(
				'label'       => esc_html__( 'Viewport Spacing (px)', 'alpus-flexbox' ),
				'description' => esc_html__( 'You have to set padding top and bottom for zoom in effect.', 'alpus-flexbox' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'rem', 'em', '%' ),
				'default'     => array(
					'size' => 50,
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .swiper-zoom-in-active-slide .swiper-wrapper' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'center_mode'     => 'yes',
					'focus_on_active' => 'yes',
					'focus_effect'    => array( 'scale', 'scale_opacity' ),
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'disable_drag',
				),
			)
		);

		$self->add_control(
			'transparency',
			array(
				'label'       => esc_html__( 'Transparency', 'alpus-flexbox' ),
				'description' => esc_html__( 'Input transparency amount you want for slides except active one.', 'alpus-flexbox' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'step'        => 0.1,
				'default'     => 0.5,
				'selectors'   => array(
					'.elementor-element-{{ID}} .swiper-active-slide-opacity .swiper-slide' => 'opacity: {{SIZE}};',
				),
				'condition'   => array(
					'center_mode'     => 'yes',
					'focus_on_active' => 'yes',
					'focus_effect'    => array( 'opacity', 'scale_opacity' ),
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'disable_drag',
				),
			)
		);

		$self->add_control(
			'box_shadow_slider',
			array(
				'label'              => esc_html__( 'Prevent Box Shadow Clip', 'alpus-flexbox' ),
				'description'        => esc_html__( 'It should be enabled in slider, if use box shadow.', 'alpus-flexbox' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'disable_drag',
				),
			)
		);

		// $self->add_control(
		// 	'scale_drag',
		// 	array(
		// 		'type'        => Controls_Manager::SWITCHER,
		// 		'label'       => esc_html__( 'Scale When Dragging', 'alpha-core' ),
		// 		'description' => esc_html__( 'This will cause your slider items to shrink when dragging.', 'alpha-core' ),
		// 		'condition'   => array(
		// 			'disable_drag!' => 'yes',
		// 		),
		// 	),
		// 	array(
		// 		'position' => array(
		// 			'at' => 'after',
		// 			'of' => 'disable_drag',
		// 		),
		// 	)
		// );
	}

	/**
	 * Add custom slider dot options
	 *
	 * @return array
	 */
	public function add_slider_dot_options( $self, $args ) {
		$self->add_control(
			'dots_type',
			array(
				'label'              => esc_html__( 'Dots Type', 'alpus-flexbox' ),
				'description'        => esc_html__( 'Controls the dots type.', 'alpus-flexbox' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'default',
				'options'            => array(
					'default'       => esc_html__( 'Type 1', 'alpus-flexbox' ),
					'inner_circle'  => esc_html__( 'Type 2', 'alpus-flexbox' ),
					'active_circle' => esc_html__( 'Type 3', 'alpus-flexbox' ),
				),
				'prefix_class'       => 'elementor-pagination-type-',
				'condition'          => array(
					'pagination' => 'bullets',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'pagination',
				),
			)
		);
		$self->add_control(
			'dots_skin',
			array(
				'label'        => esc_html__( 'Dots Skin', 'alpus-flexbox' ),
				'description'  => esc_html__( 'Controls the dots skin.', 'alpus-flexbox' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default' => esc_html__( 'Default', 'alpus-flexbox' ),
					'white'   => esc_html__( 'White', 'alpus-flexbox' ),
					'grey'    => esc_html__( 'Grey', 'alpus-flexbox' ),
					'dark'    => esc_html__( 'Dark', 'alpus-flexbox' ),
				),
				'prefix_class' => 'elementor-pagination-skin-',
				'condition'    => array(
					'pagination' => 'bullets',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'dots_type',
				),
			)
		);

		$self->update_control(
			'dots_bg_color',
			array(
				'selectors'  => array(
					'{{WRAPPER}}' => '--alpha-slider-dot-bg: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'pagination',
							'operator' => 'in',
							'value'    => array( 'dynamic', 'progressbar' ),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'pagination',
									'operator' => '==',
									'value'    => 'bullets',
								),
								array(
									'name'     => 'dots_type',
									'operator' => '==',
									'value'    => 'default',
								),
							),
						),
					),
				),
			)
		);

		$self->update_control(
			'dots_hover_bg_color',
			array(
				'selectors'  => array(
					'{{WRAPPER}}' => '--alpha-slider-dot-hover-bg: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'pagination',
							'operator' => '==',
							'value'    => 'dynamic',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'pagination',
									'operator' => '==',
									'value'    => 'bullets',
								),
								array(
									'name'     => 'dots_type',
									'operator' => '==',
									'value'    => 'default',
								),
							),
						),
					),
				),
			)
		);

		$self->update_control(
			'dots_active_bg_color',
			array(
				'selectors'  => array(
					'{{WRAPPER}}' => '--alpha-slider-dot-active-bg: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'pagination',
							'operator' => 'in',
							'value'    => array( 'dynamic', 'progressbar' ),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'pagination',
									'operator' => '==',
									'value'    => 'bullets',
								),
								array(
									'name'     => 'dots_type',
									'operator' => '==',
									'value'    => 'default',
								),
							),
						),
					),
				),
			)
		);

		$self->add_control(
			'dot_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--alpha-slider-dot-bd: {{VALUE}};',
				),
				'condition' => array(
					'pagination' => 'bullets',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'dots_bg_color',
				),
			)
		);

		$self->add_control(
			'dot_hover_border_color',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--alpha-slider-dot-hover-bd: {{VALUE}};',
				),
				'condition' => array(
					'pagination' => 'bullets',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'dots_hover_bg_color',
				),
			)
		);

		$self->add_control(
			'dot_active_border_color',
			array(
				'label'     => esc_html__( 'Active Border Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--alpha-slider-dot-active-bd: {{VALUE}};',
				),
				'condition' => array(
					'pagination' => 'bullets',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'dots_active_bg_color',
				),
			)
		);
	}
}

Alpha_Core_Alpus_Flexbox::get_instance();
