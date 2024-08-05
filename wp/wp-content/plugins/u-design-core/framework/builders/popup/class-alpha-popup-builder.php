<?php
/**
 * Alpha Popup Builder class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_Template_Popup_Builder extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		// @start feature: fs_pb_elementor
		if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) ) {
			// Register Document Controls
			add_action( 'elementor/documents/register_controls', array( $this, 'register_document_controls' ) );

			// Register Layout Builder Controls
			add_filter( 'alpha_layout_get_controls', array( $this, 'add_layout_builder_controls' ) );

			// Clear popup cache after template save
			if ( wp_doing_ajax() ) {
				add_action( 'elementor/document/before_save', array( $this, 'clear_popup_cache' ), 20, 2 );
			}
		}
		// @end feature: fs_pb_elementor
	}

	public function register_document_controls( $document ) {
		if ( ! $document instanceof Elementor\Core\DocumentTypes\PageBase && ! $document instanceof Elementor\Modules\Library\Documents\Page ) {
			return;
		}

		// Add Template Builder Controls
		$id = (int) $document->get_main_id();

		if ( ALPHA_NAME . '_template' == get_post_type( $id ) ) {
			$category = get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true );

			if ( $id && 'popup' == get_post_meta( $id, ALPHA_NAME . '_template_type', true ) ) {

				$selector = '.mfp-alpha-' . $id;

				$document->start_controls_section(
					'alpha_popup_settings',
					array(
						'label' => alpha_elementor_panel_heading( esc_html__( 'Popup Settings', 'alpha-core' ) ),
						'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
					)
				);

					$document->add_responsive_control(
						'popup_width',
						array(
							'label'      => esc_html__( 'Width', 'alpha-core' ),
							'type'       => Elementor\Controls_Manager::SLIDER,
							'default'    => array(
								'size' => 600,
							),
							'size_units' => array(
								'px',
								'vw',
							),
							'range'      => array(
								'vw' => array(
									'step' => 1,
									'min'  => 0,
								),
							),
							'selectors'  => array(
								( $selector . ' .popup' ) => 'width: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$document->add_control(
						'popup_height_type',
						array(
							'label'   => __( 'Height', 'alpha-core' ),
							'type'    => Elementor\Controls_Manager::SELECT,
							'options' => array(
								''       => esc_html__( 'Fit To Content', 'alpha-core' ),
								'custom' => esc_html__( 'Custom', 'alpha-core' ),
							),
						)
					);

					$document->add_responsive_control(
						'popup_height',
						array(
							'label'      => esc_html__( 'Custom Height', 'alpha-core' ),
							'type'       => Elementor\Controls_Manager::SLIDER,
							'default'    => array(
								'size' => 380,
							),
							'size_units' => array(
								'px',
								'vh',
							),
							'range'      => array(
								'vh' => array(
									'step' => 1,
									'min'  => 0,
									'max'  => 100,
								),
							),
							'condition'  => array(
								'popup_height_type' => 'custom',
							),
							'selectors'  => array(
								( $selector . ' .popup' ) => 'height: {{SIZE}}{{UNIT}};',
							),
						)
					);

					$document->add_control(
						'popup_content_pos_heading',
						array(
							'label'     => __( 'Content Position', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$document->add_responsive_control(
						'popup_content_h_pos',
						array(
							'label'     => __( 'Horizontal', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::CHOOSE,
							'toggle'    => false,
							'default'   => 'center',
							'options'   => array(
								'flex-start' => array(
									'title' => __( 'Top', 'alpha-core' ),
									'icon'  => 'eicon-h-align-left',
								),
								'center'     => array(
									'title' => __( 'Middle', 'alpha-core' ),
									'icon'  => 'eicon-h-align-center',
								),
								'flex-end'   => array(
									'title' => __( 'Bottom', 'alpha-core' ),
									'icon'  => 'eicon-h-align-right',
								),
							),
							'selectors' => array(
								( $selector . ' .alpha-popup-content' ) => 'justify-content: {{VALUE}};',
							),
						)
					);

					$document->add_responsive_control(
						'popup_content_v_pos',
						array(
							'label'     => __( 'Vertical', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::CHOOSE,
							'toggle'    => false,
							'default'   => 'center',
							'options'   => array(
								'flex-start' => array(
									'title' => __( 'Top', 'alpha-core' ),
									'icon'  => 'eicon-v-align-top',
								),
								'center'     => array(
									'title' => __( 'Middle', 'alpha-core' ),
									'icon'  => 'eicon-v-align-middle',
								),
								'flex-end'   => array(
									'title' => __( 'Bottom', 'alpha-core' ),
									'icon'  => 'eicon-v-align-bottom',
								),
							),
							'selectors' => array(
								( $selector . ' .alpha-popup-content' ) => 'align-items: {{VALUE}};',
							),
						)
					);

					$document->add_control(
						'popup_pos_heading',
						array(
							'label'     => __( 'Position', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$document->add_responsive_control(
						'popup_h_pos',
						array(
							'label'     => __( 'Horizontal', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::CHOOSE,
							'toggle'    => true,
							'default'   => 'center',
							'options'   => array(
								'flex-start' => array(
									'title' => __( 'Left', 'alpha-core' ),
									'icon'  => 'eicon-h-align-left',
								),
								'center'     => array(
									'title' => __( 'Center', 'alpha-core' ),
									'icon'  => 'eicon-h-align-center',
								),
								'flex-end'   => array(
									'title' => __( 'Right', 'alpha-core' ),
									'icon'  => 'eicon-h-align-right',
								),
							),
							'selectors' => array(
								( $selector . ' .mfp-content' ) => 'justify-content: {{VALUE}};',
							),
						)
					);

					$document->add_responsive_control(
						'popup_v_pos',
						array(
							'label'     => __( 'Vertical', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::CHOOSE,
							'toggle'    => true,
							'default'   => '',
							'options'   => array(
								'flex-start' => array(
									'title' => __( 'Top', 'alpha-core' ),
									'icon'  => 'eicon-v-align-top',
								),
								'center'     => array(
									'title' => __( 'Middle', 'alpha-core' ),
									'icon'  => 'eicon-v-align-middle',
								),
								'flex-end'   => array(
									'title' => __( 'Bottom', 'alpha-core' ),
									'icon'  => 'eicon-v-align-bottom',
								),
							),
							'selectors' => array(
								( $selector . ' .mfp-content' ) => 'align-items: {{VALUE}}; height: 100%',
							),
						)
					);

					$document->add_control(
						'popup_style_heading',
						array(
							'label'     => __( 'Style', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						)
					);

					$document->add_control(
						'popup_overlay_color',
						array(
							'label'     => esc_html__( 'Overlay Color', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::COLOR,
							'selectors' => array(
								( '.mfp-bg' . $selector ) => 'background-color: {{VALUE}};',
							),
						)
					);

					$document->add_control(
						'popup_content_color',
						array(
							'label'     => esc_html__( 'Content Color', 'alpha-core' ),
							'type'      => Elementor\Controls_Manager::COLOR,
							'selectors' => array(
								( $selector . ' .popup .alpha-popup-content' ) => 'background-color: {{VALUE}};',
							),
						)
					);

					$document->add_group_control(
						Elementor\Group_Control_Box_Shadow::get_type(),
						array(
							'name'     => 'popup_box_shadow',
							'selector' => ( $selector . ' .mfp-content>*' ),
						)
					);

					$document->add_responsive_control(
						'popup_border_radius',
						array(
							'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
							'type'       => Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'%',
								'em',
							),
							'selectors'  => array(
								( $selector . ' .popup .alpha-popup-content' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$document->add_responsive_control(
						'popup_margin',
						array(
							'label'      => esc_html__( 'Margin', 'alpha-core' ),
							'type'       => Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => array(
								'px',
								'%',
								'em',
							),
							'selectors'  => array(
								( $selector . ' .mfp-content .popup' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						)
					);

					$document->add_control(
						'popup_animation',
						array(
							'type'      => Elementor\Controls_Manager::SELECT,
							'label'     => esc_html__( 'Animation In', 'alpha-core' ),
							'options'   => alpha_get_animations( 'in' ),
							'default'   => 'fadeIn',
							'separator' => 'before',
						)
					);

					$document->add_control(
						'popup_remove_animation',
						array(
							'type'      => Elementor\Controls_Manager::SELECT,
							'label'     => esc_html__( 'Animation Out', 'alpha-core' ),
							'options'   => alpha_get_animations( 'out' ),
							'default'   => 'fadeOut',
							'selectors' => array(
								( $selector . '.mfp-removing .popup' ) => 'animation-name: {{VALUE}}; opacity: 1;',
							),
						)
					);

					$document->add_control(
						'reveal_effect_color',
						array(
							'label'       => esc_html__( 'Animation Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the color of the reveal amination.', 'alpha-core' ),
							'type'        => Elementor\Controls_Manager::COLOR,
							'condition'   => array(
								'popup_animation' => array( 'revealInDown', 'revealInLeft', 'revealInRight', 'revealInUp' ),
							),
							'selectors'   => array(
								( $selector . ' .mfp-content .popup' ) => '--alpha-reveal-animation-color: {{VALUE}};',
							),
						),
					);

					$document->add_control(
						'popup_anim_duration',
						array(
							'type'    => Elementor\Controls_Manager::NUMBER,
							'label'   => esc_html__( 'Animation Duration (ms)', 'alpha-core' ),
							'default' => 400,
						)
					);

				$document->end_controls_section();
			}
		}
	}

	public function add_layout_builder_controls( $controls ) {
		$controls['general'] = array_merge(
			$controls['general'],
			array(
				'popup'       => array(
					'type'  => 'block_popup',
					'label' => esc_html__( 'Popup', 'alpha' ),
				),
				'popup_delay' => array(
					'type'  => 'number',
					'label' => esc_html__( 'Popup Delay (s)', 'alpha' ),
					'unit'  => esc_html( 'seconds', 'alpha' ),
				),
			)
		);

		return $controls;
	}

	public function clear_popup_cache( $self, $data ) {
		if ( empty( $_REQUEST['editor_post_id'] ) ) {
			return;
		}
		$post_id = absint( $_REQUEST['editor_post_id'] );
		if ( 'popup' == get_post_meta( $post_id, ALPHA_NAME . '_template_type', true ) ) {
			set_theme_mod( 'popup_last_time', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
		}
	}
}

Alpha_Template_Popup_Builder::get_instance();
