<?php
/**
 * Alpha Elementor Extend Class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0.0
 * @version    4.0.0
 */

defined( 'ABSPATH' ) || die;

if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
	return;
}

use Elementor\Controls_Manager;
use Elementor\Core\Files\CSS\Global_CSS;
use Elementor\Alpha_Controls_Manager;

class Alpha_Core_Elementor_Extend extends Alpha_Base {

	public $shapes_options;

	/**
	 * Constructor
	 *
	 * @since 4.0.0
	 */
	public function __construct() {

		$this->shapes_options = array(
			'top'    => array(
				''                      => 'assets/images/shape-dividers/none.jpg',
				'mountains'             => 'assets/images/shape-dividers/mountain-top.jpg',
				'drops'                 => 'assets/images/shape-dividers/drops-top.jpg',
				'clouds'                => 'assets/images/shape-dividers/clouds-top.jpg',
				'zigzag'                => 'assets/images/shape-dividers/zigzag-top.jpg',
				'pyramids'              => 'assets/images/shape-dividers/pyramids-top.jpg',
				'triangle'              => 'assets/images/shape-dividers/triangle-top.jpg',
				'triangle-asymmetrical' => 'assets/images/shape-dividers/triangle2-top.jpg',
				'tilt'                  => 'assets/images/shape-dividers/tilt-top.jpg',
				'opacity-tilt'          => 'assets/images/shape-dividers/tilt2-top.jpg',
				'opacity-fan'           => 'assets/images/shape-dividers/fan-top.jpg',
				'curve-asymmetrical'    => 'assets/images/shape-dividers/curve2-top.jpg',
				'waves'                 => 'assets/images/shape-dividers/wave-top.jpg',
				'wave-brush'            => 'assets/images/shape-dividers/wave2-top.jpg',
				'waves-pattern'         => 'assets/images/shape-dividers/wave3-top.jpg',
				'arrow'                 => 'assets/images/shape-dividers/arrow-top.jpg',
				'split'                 => 'assets/images/shape-dividers/split-top.jpg',
				'book'                  => 'assets/images/shape-dividers/book-top.jpg',
				'alpha-shape1'          => 'assets/images/shape-dividers/alpha1-top.jpg',
				'alpha-shape2'          => 'assets/images/shape-dividers/curve-top.jpg',
				'alpha-shape3'          => 'assets/images/shape-dividers/alpha3-top.jpg',
				'alpha-shape4'          => 'assets/images/shape-dividers/alpha4-top.jpg',
				'alpha-shape5'          => 'assets/images/shape-dividers/alpha5-top.jpg',
				'alpha-shape6'          => 'assets/images/shape-dividers/alpha6-top.jpg',
				'alpha-shape7'          => 'assets/images/shape-dividers/alpha7-top.jpg',
				'custom'                => 'assets/images/shape-dividers/custom.jpg',
			),
			'bottom' => array(
				''                      => 'assets/images/shape-dividers/none.jpg',
				'mountains'             => 'assets/images/shape-dividers/mountain-bottom.jpg',
				'drops'                 => 'assets/images/shape-dividers/drops-bottom.jpg',
				'clouds'                => 'assets/images/shape-dividers/clouds-bottom.jpg',
				'zigzag'                => 'assets/images/shape-dividers/zigzag-bottom.jpg',
				'pyramids'              => 'assets/images/shape-dividers/pyramids-bottom.jpg',
				'triangle'              => 'assets/images/shape-dividers/triangle-bottom.jpg',
				'triangle-asymmetrical' => 'assets/images/shape-dividers/triangle2-bottom.jpg',
				'tilt'                  => 'assets/images/shape-dividers/tilt-bottom.jpg',
				'opacity-tilt'          => 'assets/images/shape-dividers/tilt2-bottom.jpg',
				'opacity-fan'           => 'assets/images/shape-dividers/fan-bottom.jpg',
				'curve-asymmetrical'    => 'assets/images/shape-dividers/curve2-bottom.jpg',
				'waves'                 => 'assets/images/shape-dividers/wave-bottom.jpg',
				'wave-brush'            => 'assets/images/shape-dividers/wave2-bottom.jpg',
				'waves-pattern'         => 'assets/images/shape-dividers/wave3-bottom.jpg',
				'arrow'                 => 'assets/images/shape-dividers/arrow-bottom.jpg',
				'split'                 => 'assets/images/shape-dividers/split-bottom.jpg',
				'book'                  => 'assets/images/shape-dividers/book-bottom.jpg',
				'alpha-shape1'          => 'assets/images/shape-dividers/alpha1-bottom.jpg',
				'alpha-shape2'          => 'assets/images/shape-dividers/curve-bottom.jpg',
				'alpha-shape3'          => 'assets/images/shape-dividers/alpha3-bottom.jpg',
				'alpha-shape4'          => 'assets/images/shape-dividers/alpha4-bottom.jpg',
				'alpha-shape5'          => 'assets/images/shape-dividers/alpha5-bottom.jpg',
				'alpha-shape6'          => 'assets/images/shape-dividers/alpha6-bottom.jpg',
				'alpha-shape7'          => 'assets/images/shape-dividers/alpha7-bottom.jpg',
				'custom'                => 'assets/images/shape-dividers/custom.jpg',
			),
		);

		if ( alpha_is_elementor_preview() ) {
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_preview_scripts' ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'register_script' ), 10 );

		add_action( 'wp_enqueue_scripts', array( $this, 'add_elementor_css' ), 35 );

		// Include Elementor Admin CSS and Framework Icon
		add_action(
			'elementor/editor/after_enqueue_styles',
			function() {
				wp_enqueue_style( 'alpha-elementor-admin-extend', ALPHA_CORE_INC_URI . '/plugins/elementor/assets/elementor-admin-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
				if ( defined( 'ALPHA_VERSION' ) ) {
					wp_dequeue_style( 'alpha-icons' );
					if ( alpha_get_option( 'legacy_icons' ) ) {
						wp_enqueue_style( 'alpha-icons', ALPHA_ASSETS . '/vendor/icons/legacy/css/icons.min.css', array(), ALPHA_VERSION );
						wp_enqueue_style( 'alpha-framework-icons', ALPHA_ASSETS . '/vendor/wpalpha-icons/legacy/css/icons.min.css', array(), ALPHA_VERSION );
					} else {
						wp_enqueue_style( 'alpha-icons', ALPHA_ASSETS . '/vendor/icons/' . alpha_get_option( 'icon_weight' ) . '/css/icons.min.css', array(), ALPHA_VERSION );
						wp_enqueue_style( 'alpha-framework-icons', ALPHA_ASSETS . '/vendor/wpalpha-icons/' . alpha_get_option( 'icon_weight' ) . '/css/icons.min.css', array(), ALPHA_VERSION );
					}
				}
			},
			20
		);

		// Register Icons
		$this->add_framework_icon();

		// Extend Widgets
		add_filter( 'alpha_elementor_widgets', array( $this, 'add_widgets' ) );

		add_filter( 'alpha_select_post_types', array( $this, 'more_post_types' ) );
		add_filter( 'alpha_select_taxonomies', array( $this, 'more_taxonomies' ) );

		add_filter( 'alpha_col_sp', array( $this, 'extend_col_sp' ), 10, 2 );
		add_filter( 'alpha_col_default', array( $this, 'set_default_gap' ) );

		// Extend Partials
		add_action( 'alpha_extend_elementor_partials', array( $this, 'extend_partials' ) );

		// Add Elementor widget extend functions
		$widgets = array(
			'heading',
			'hotspot',
			'image-gallery',
			'image-compare',
			'360-degree',
			'button',
			'contact',
		);
		foreach ( $widgets as $widget ) {
			require_once ALPHA_CORE_INC . '/widgets/' . $widget . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor-extend.php';
		}

		// Add Elementor column extensions
		$add_widgets = array(
			'half-container',
		);
		foreach ( $add_widgets as $widget ) {
			require_once ALPHA_CORE_INC . '/widgets/' . $widget . '/widget-' . str_replace( '_', '-', $widget ) . '-elementor.php';
		}

		require_once ALPHA_CORE_INC . '/plugins/elementor/partials/grid-extend.php';
		require_once ALPHA_CORE_INC . '/plugins/elementor/partials/slider-extend.php';

		// Update shape divider controls
		add_action( 'alpha_elementor_section_addon_controls', array( $this, 'update_shape_divider_controls' ) );
		add_action( 'alpha_elementor_container_addon_tabs', array( $this, 'update_shape_divider_controls' ) );

		// Duplex Extended Tag
		add_action( 'alpha_elementor_addon_controls', array( $this, 'add_duplex_style_controls' ), 22, 2 );

		// Remove framework elementor functions
		add_action(
			'alpha_after_core_framework_plugins',
			function() {
				if ( class_exists( 'Alpha_Core_Elementor' ) ) {
					remove_filter( 'elementor/icons_manager/additional_tabs', array( Alpha_Core_Elementor::get_instance(), 'alpha_add_icon_library' ) );
					remove_action( 'alpha_before_enqueue_custom_css', array( Alpha_Core_Elementor::get_instance(), 'add_elementor_page_css' ), 20 );
				}
			}
		);
		add_action( 'alpha_before_enqueue_custom_css', array( $this, 'add_elementor_page_css' ), 20 );
	}

	/**
	 * Add controls to duplex tab
	 *
	 * @since 4.1.0
	 */
	public function add_duplex_style_controls( $self, $source = '' ) {
		$self->remove_control( 'alpha_widget_duplex_stroke_width' );

		$self->add_control(
			'duplex_stroke_color',
			array(
				'label'       => esc_html__( 'Stroke Color', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Set stroke color of duplex content.', 'alpha-core' ),
				'condition'   => array(
					'alpha_widget_duplex_type' => 'text',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex-text' => '-webkit-text-stroke-color: {{VALUE}}',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'alpha_widget_duplex_text_color',
				),
			)
		);

		$self->add_responsive_control(
			'duplex_stroke_width',
			array(
				'label'       => esc_html__( 'Stroke Width (px)', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Control stroke width of text type.', 'alpha-core' ),
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 50,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .duplex-wrap-{{ID}} .duplex-text' => '-webkit-text-fill-color: transparent; -webkit-text-stroke-width: {{SIZE}}px;',
				),
				'condition'   => array(
					'alpha_widget_duplex_type' => 'text',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'alpha_widget_duplex_text_color',
				),
			)
		);

		$self->update_control(
			'alpha_widget_duplex_z_index',
			array(
				'label'     => esc_html__( 'z-Index', 'alpha-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'max'       => 999,
				'step'      => 1,
				'selectors' => array(
					'.elementor-element-{{ID}} .duplex-wrap-{{ID}}' => 'z-index:{{VALUE}}',
				),
			)
		);
	}

	/**
	 * Load extended elementor css
	 *
	 * @since 4.0.0
	 */
	public function add_elementor_css() {
		wp_enqueue_style( 'alpha-elementor-extend-style', ALPHA_CORE_INC_URI . '/plugins/elementor/assets/elementor-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
	}

	public function add_elementor_page_css() {
		// Add page css
		wp_enqueue_style( 'elementor-post-' . get_the_ID() );

		// Add inline styles for heading element responsive control
		$breakpoints = alpha_get_breakpoints();
		if ( $breakpoints ) :
			ob_start();
			?>
		<style>
			@media (max-width: <?php echo esc_html( $breakpoints['lg'] - 1 . 'px' ); ?>) {
				div.title-lg-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-lg-left .title {
					margin-right: auto;
					margin-left: 0;
					text-align: left;
				}
				div.title-lg-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
			@media (max-width: <?php echo esc_html( $breakpoints['md'] - 1 . 'px' ); ?>) {
				div.title-md-center .title {
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
				div.title-md-left .title {
					margin-right: auto;
					text-align: left;
					margin-left: 0;
				}
				div.title-md-right .title {
					margin-left: auto;
					margin-right: 0;
					text-align: right;
				}
			}
		</style>
			<?php
			alpha_filter_inline_css( ob_get_clean() );
		endif;
	}

	/**
	 * Load Elementor Editor Scripts
	 *
	 * @since 4.0.0
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script( 'alpha-elementor-admin-extend', ALPHA_CORE_INC_URI . '/plugins/elementor/assets/elementor-admin-extend' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
	}

	public function load_preview_scripts() {
		// load needed style file in elementor preview
		wp_enqueue_style( 'alpha-elementor-preview-extend', ALPHA_CORE_INC_URI . '/plugins/elementor/assets/elementor-preview-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' );
		wp_enqueue_script( 'alpha-elementor-extend', ALPHA_CORE_INC_URI . '/plugins/elementor/assets/elementor-extend' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
	}

	/**
	 * Register Scripts
	 *
	 * @since 4.0.0
	 */
	public function register_script() {
		wp_deregister_script( 'alpha-chart-lib' );
		wp_register_script( 'alpha-chart-lib', ALPHA_CORE_URI . '/assets/js/chart.min.js', array(), ALPHA_CORE_VERSION, true );
	}


	/**
	 * Add Icons
	 *
	 * @since 4.0.0
	 */
	public function add_icon_library( $icons ) {
		if ( defined( 'ALPHA_VERSION' ) ) {
			$icons['theme-icons'] = array(
				'name'          => ALPHA_NAME,
				'label'         => ALPHA_DISPLAY_NAME . esc_html__( ' Icons', 'alpha-core' ),
				'prefix'        => THEME_ICON_PREFIX . '-icon-',
				'displayPrefix' => ' ',
				'labelIcon'     => ALPHA_ICON_PREFIX . '-icon-gift',
				'fetchJson'     => ALPHA_CORE_INC_URI . '/plugins/elementor/assets/theme-icons' . ( alpha_get_option( 'legacy_icons' ) ? '-legacy' : '' ) . '.js',
				'ver'           => ALPHA_CORE_VERSION,
				'native'        => false,
			);
		}
		return $icons;
	}
	public function add_alpha_icon_library( $icons ) {
		if ( defined( 'ALPHA_VERSION' ) ) {
			$icons['themes-icons'] = array(
				'name'          => 'alpha',
				'label'         => esc_html__( 'Framework Icons', 'alpha-core' ),
				'prefix'        => ALPHA_ICON_PREFIX . '-icon-',
				'displayPrefix' => ' ',
				'labelIcon'     => ALPHA_ICON_PREFIX . '-icon-gift',
				'fetchJson'     => alpha_core_framework_uri( '/plugins/elementor/assets/icons' . ( alpha_get_option( 'legacy_icons' ) ? '-legacy' : '' ) . '.js' ),
				'ver'           => ALPHA_CORE_VERSION,
				'native'        => false,
			);
		}
		return $icons;
	}
	public function add_framework_icon() {
		add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_icon_library' ) );
		add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_alpha_icon_library' ) );
	}
	/**
	 * Extend elementor widgets
	 *
	 * @since 4.0.0
	 */
	public function add_widgets( $widgets ) {
		$extended_widgets = array(
			'alert'      => true,
			'icon-box'   => true,
			'counters'   => true,
			'image-box'  => true,
			'search'     => true,
			'price-list' => true,
			'sticky-nav' => true,
			'posts'      => true,
			'scroll-nav' => true,
			'marquee'    => true,
		);

		if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
			$extended_widgets['events'] = true;
		}

		$extended_widgets = array_merge( $widgets, $extended_widgets );

		$woo_extended_widgets = array(
			'products'   => true,
			'categories' => true,
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$extended_widgets = array_merge( $extended_widgets, $woo_extended_widgets );
		}

		$removed_widgets = array();
		if ( class_exists( 'WooCommerce' ) ) {
			$removed_widgets = array(
				'products-tab',
				'products-banner',
				'singleproducts',
			);
		}
		$removed_widgets[] = 'subcategories';

		foreach ( $removed_widgets as $widget ) {
			$extended_widgets[ $widget ] = false;
		}

		return $extended_widgets;
	}


	/**
	 * Include extended partials
	 *
	 * @since 4.0.0
	 */
	public function extend_partials() {
		$partials = array(
			'products',
		);
		foreach ( $partials as $partial ) {
			include_once alpha_core_framework_path( ALPHA_CORE_ELEMENTOR . '/partials/' . $partial . '.php' );
		}
	}

	/**
	 * Extend elementor select control post types
	 *
	 * @since 4.0
	 */
	public function more_post_types( $post_types ) {
		if ( defined( 'TRIBE_EVENTS_FILE' ) ) { // The Events Calander
			$post_types[] = 'tribe_events';
		}
		return $post_types;
	}

	/**
	 * Extend elementor select control taxonomies
	 *
	 * @since 4.0
	 */
	public function more_taxonomies( $taxonomies ) {
		if ( defined( 'TRIBE_EVENTS_FILE' ) ) { // The Events Calander
			$taxonomies[] = 'tribe_events_cat';
		}
		return $taxonomies;
	}

	/**
	 * Extend elementor column spacing
	 *
	 * @since 4.0
	 */
	public function extend_col_sp( $spacings, $page_builder ) {
		if ( 'elementor' == $page_builder ) {
			$spacings['xl'] = array(
				'title' => esc_html__( 'Extra Large', 'alpha-core' ),
				'icon'  => 'alpha-size-xl alpha-choose-type',
			);
		}
		return $spacings;
	}

	/**
	 * Change elementor column default spacing
	 *
	 * @since 4.0
	 */
	public function set_default_gap( $spacing ) {
		return 'lg';
	}

	/**
	 * Update shape divider controls
	 *
	 * @since 4.0
	 */
	public function update_shape_divider_controls( $self ) {

		$self->update_control(
			'shape_divider_top',
			array(
				'label'              => esc_html__( 'Type', 'alpha-core' ),
				'type'               => Alpha_Controls_Manager::IMAGE_CHOOSE,
				'options'            => $this->shapes_options['top'],
				'width'              => 1,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$self->update_control(
			'shape_divider_bottom',
			array(
				'label'              => esc_html__( 'Type', 'alpha-core' ),
				'type'               => Alpha_Controls_Manager::IMAGE_CHOOSE,
				'options'            => $this->shapes_options['bottom'],
				'width'              => 1,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$self->update_responsive_control(
			'shape_divider_top_width',
			array(
				'label'     => esc_html__( 'Width', 'alpha-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => '%',
				),
				'range'     => array(
					'%' => array(
						'min' => 100,
						'max' => 300,
					),
				),
				'condition' => array(
					'shape_divider_top' => array_keys( Elementor\Shapes::filter_shapes( 'height_only', Elementor\Shapes::FILTER_EXCLUDE ) ),
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-shape-top svg, {{WRAPPER}} > .e-con-inner > .elementor-shape-top svg' => 'width: calc({{SIZE}}% + 1.3px)',
				),
			)
		);

		$self->update_responsive_control(
			'shape_divider_bottom_width',
			array(
				'label'     => esc_html__( 'Width', 'alpha-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => '%',
				),
				'range'     => array(
					'%' => array(
						'min' => 100,
						'max' => 300,
					),
				),
				'condition' => array(
					'shape_divider_bottom' => array_keys( Elementor\Shapes::filter_shapes( 'height_only', Elementor\Shapes::FILTER_EXCLUDE ) ),
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-shape-bottom svg, {{WRAPPER}} > .e-con-inner > .elementor-shape-bottom svg' => 'width: calc({{SIZE}}% + 1.3px)',
				),
			)
		);
	}
}

/**
 * Create instance
 */
Alpha_Core_Elementor_Extend::get_instance();
