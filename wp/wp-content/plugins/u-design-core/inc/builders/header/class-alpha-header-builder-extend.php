<?php
/**
 * Alpha Builder Header class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

class Alpha_Header_Builder_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		// Update Header Widgets
		$extend_widgets = array(
			'language-switcher',
			'currency-switcher',
		);
		if ( class_exists( 'WooCommerce' ) ) {
			$extend_widgets[] = 'cart';
		}

		foreach ( $extend_widgets as $widget ) {
			require_once ALPHA_CORE_INC . '/builders/header/widgets/' . $widget . '/widget-' . $widget . '-elementor-extend.php';
		}

		// Register Document Controls
		add_action( 'elementor/documents/register_controls', array( $this, 'register_document_controls' ) );
	}
	public function register_document_controls( $document ) {
		if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		// Add Template Builder Controls
		$id = (int) $document->get_main_id();

		if ( ALPHA_NAME . '_template' == get_post_type( $id ) ) {
			$category = get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true );

			if ( $id && 'header' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {

				$document->start_controls_section(
					'alpha_header_settings',
					array(
						'label' => alpha_elementor_panel_heading( esc_html__( 'Header Settings', 'alpha-core' ) ),
						'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
					)
				);

					$document->add_control(
						'alpha_sticky_transparent',
						array(
							'type'         => Elementor\Controls_Manager::SWITCHER,
							'label'        => esc_html__( 'Transparent Header', 'alpha-core' ),
							'description'  => esc_html__( 'This will make the header transparent and overlap the main page.', 'alpha-core' ),
							'return_value' => 'transparent',
							'prefix_class' => 'sticky-content-',
							'condition' => array(
								'alpha_header_pos' => '',
							),
						)
					);

					$document->add_control(
						'alpha_header_bg',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the background color of header.', 'alpha-core' ),
							'type'        => Elementor\Controls_Manager::COLOR,
							'selectors'   => array(
								'.header-' . $id => 'background-color: {{VALUE}};',
							),
						)
					);

					$document->add_control(
						'alpha_header_pos',
						array(
							'label'   => esc_html__( 'Position', 'alpha-core' ),
							'type'    => Elementor\Controls_Manager::SELECT,
							'options' => array(
								''     => esc_html__( 'Top', 'alpha-core' ),
								'side' => esc_html__( 'Side', 'alpha-core' ),
							),
							'condition' => array(
								'alpha_sticky_transparent!' => 'transparent',
							),
						)
					);

					$document->add_responsive_control(
						'alpha_side_header_width',
						array(
							'label'      => esc_html__( 'Side Header Width', 'alpha-core' ),
							'type'       => Elementor\Controls_Manager::SLIDER,
							'size_units' => array(
								'px',
								'%',
								'vw',
							),
							'range'      => array(
								'px' => array(
									'min' => 0,
									'max' => 500,
								),
							),
							'selectors'  => array(
								'.page-wrapper' => '--alpha-side-header-width: {{SIZE}}{{UNIT}};',
							),
							'condition'  => array(
								'alpha_header_pos' => 'side',
							),
						)
					);

					$document->add_control(
						'alpha_side_header_breakpoint',
						array(
							'label'     => esc_html__( 'Side Header Breakpoint', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::SELECT,
							'default'   => '',
							'options'   => array(
								''        => esc_html__( 'Never', 'alpha-core' ),
								'desktop' => esc_html__( 'Desktop', 'alpha-core' ),
								'tablet'  => esc_html__( 'Tablet', 'alpha-core' ),
								'mobile'  => esc_html__( 'Mobile', 'alpha-core' ),
							),
							'condition' => array(
								'alpha_header_pos' => 'side',
							),
						)
					);

				$document->end_controls_section();
			}
		}
	}
}

Alpha_Header_Builder_Extend::get_instance();
