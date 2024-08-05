<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Custom Cursor
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

if ( ! class_exists( 'Alpha_Custom_Cursor_Elementor_Widget_Addon' ) ) {
	class Alpha_Custom_Cursor_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// For section
			add_action( 'alpha_elementor_section_before_addon_controls', array( $this, 'add_custom_cursor_controls' ), 10, 2 );
			add_action( 'alpha_elementor_section_after_addon_controls', array( $this, 'add_custom_cursor_tab' ), 10, 2 );
			add_action( 'alpha_before_elementor_section_content_template', array( $this, 'section_addon_content_template' ) );
			add_action( 'alpha_before_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );

			// For container
            add_action( 'alpha_elementor_container_addon_controls', array( $this, 'add_custom_cursor_controls' ), 10, 2 );
            add_action( 'alpha_elementor_container_addon_tabs', array( $this, 'add_custom_cursor_tab' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_content_template', array( $this, 'section_addon_content_template' ) );
			add_filter( 'alpha_before_elementor_container_render', array( $this, 'section_addon_render' ), 10, 3 );
		}

		/**
		 * Add switcher control to section element
		 *
		 * @since 1.0
		 */
		public function add_custom_cursor_controls( $self ) {
            $self->add_control(
                'section_cursor_type',
                array(
                    'label' => esc_html__( 'Change Cursor Type', 'alpha-core' ),
                    'type'  => Controls_Manager::SWITCHER,
                )
            );
		}

		/**
		 * Add controls tab to section element
		 *
		 * @since 1.0
		 */
		public function add_custom_cursor_tab( $self ) {
			$self->start_controls_section(
				'alpha_cursor_style',
				array(
					'label' => alpha_elementor_panel_heading( esc_html__( 'Custom Cursor', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						'section_cursor_type' => 'yes',
					)
				)
			);
			
				$self->add_control(
					'cursor_style',
					array(
						'label'       => esc_html__( 'Cursor Style', 'alpha-core' ),
						'description' => esc_html__( 'Select the cursor style.', 'alpha-core' ),
						'default'     => 'dot_circle',
						'type'        => Controls_Manager::SELECT,
						'options'     => array(
							'circle'       => esc_html__( 'Circle', 'alpha-core' ),
							'dot_circle'   => esc_html__( 'Dot Inner Circle', 'alpha-core' ),
						),
					)
				);
				
				$self->add_control(
					'cursor_size',
					array(
						'type'  => Controls_Manager::SLIDER,
						'label' => esc_html__( 'Size', 'alpha-core' ),
						'range' => array(
							'px' => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 10,
							),
						),
						'selectors' => array(
							'{{WRAPPER}} .cursor-element-{{ID}}.cursor-inner, {{WRAPPER}} .cursor-element-{{ID}}.cursor-outer' => '--alpha-cursor-size: {{SIZE}}px',
						),
					)
				);
				
				$self->add_control(
					'blend_mode',
					array(
						'label'       => esc_html__( 'Blend Mode', 'alpha-core' ),
						'description' => esc_html__( 'This option inverts object color under cursor area.', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'selectors' => array(
							'{{WRAPPER}} .cursor-element-{{ID}}.cursor-inner, {{WRAPPER}} .cursor-element-{{ID}}.cursor-outer' => 'mix-blend-mode: difference',
						),
					)
				);
				
				$self->start_controls_tabs( 'custom_cursor_style_tabs' );
	
					$self->start_controls_tab(
						'cursor_style_normal',
						array(
							'label' => esc_html__( 'Normal', 'alpha-core' ),
						)
					);
	
						$self->add_control(
							'section_cusor_dot_color',
							array(
								'label'     => esc_html__( 'Dot Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-inner' => '--alpha-cursor-inner-color: {{VALUE}}',
								),
								'condition' => array(
									'cursor_style' => 'dot_circle',
								)
							)
						);
	
						$self->add_control(
							'section_cusor_bg_color',
							array(
								'label'     => esc_html__( 'Circle Background Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-outer' => '--alpha-cursor-outer-bg-color: {{VALUE}}',
								),
							)
						);
	
						$self->add_control(
							'section_cusor_border_color',
							array(
								'label'     => esc_html__( 'Circle Border Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-outer' => '--alpha-cursor-outer-color: {{VALUE}}',
								),
							)
						);
						
						$self->add_control(
							'cursor_circle_border',
							array(
								'type'  => Controls_Manager::SLIDER,
								'label' => esc_html__( 'Border Width (px)', 'alpha-core' ),
								'range' => array(
									'px' => array(
										'step' => 0.1,
										'min'  => 0,
										'max'  => 5,
									),
								),
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-outer' => '--alpha-cursor-border-width: {{SIZE}}px',
								),
							)
						);
						$self->add_control(
							'cursor_circle_text',
							array(
								'label'       => esc_html__( 'Text Inner Circle', 'alpha-core' ),
								'description' => esc_html__( 'Text is shown inner cursor circle area.', 'alpha-core' ),
								'type'        => Controls_Manager::TEXT,
								'condition' => array(
									'cursor_style' => 'circle',
								)
							)
						);
						
						$self->add_group_control(
							Group_Control_Typography::get_type(),
							array(
								'name'     => 'cursor_circle_text_typo',
								'selector' => '{{WRAPPER}} .cursor-element-{{ID}}.cursor-outer:after',
								'condition' => array(
									'cursor_style' => 'circle',
								)
							)
						);
						
						$self->add_control(
							'cursor_circle_text_color',
							array(
								'label'     => esc_html__( 'Text Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-outer' => '--alpha-cursor-text-color: {{VALUE}}',
								),
								'condition' => array(
									'cursor_style' => 'circle',
								)
							)
						);
	
					$self->end_controls_tab();
	
					$self->start_controls_tab(
						'cursor_style_hover',
						array(
							'label' => esc_html__( 'Hover/Focus', 'alpha-core' ),
						)
					);
						
						$self->add_control(
							'hover_cursor_dot_color',
							array(
								'label'     => esc_html__( 'Dot Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused.cursor-inner' => '--alpha-cursor-inner-color: {{VALUE}}',
								),
								'condition' => array(
									'cursor_style' => 'dot_circle',
								)
							)
						);
	
						$self->add_control(
							'hover_cusor_bg_color',
							array(
								'label'     => esc_html__( 'Circle Background Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused.cursor-outer' => '--alpha-cursor-outer-bg-color: {{VALUE}}',
								),
							)
						);
	
						$self->add_control(
							'hover_cusor_border_color',
							array(
								'label'     => esc_html__( 'Circle Border Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused.cursor-outer' => '--alpha-cursor-outer-color: {{VALUE}}',
								),
							)
						);
						
						$self->add_control(
							'hover_cursor_circle_border',
							array(
								'type'  => Controls_Manager::SLIDER,
								'label' => esc_html__( 'Border Width (px)', 'alpha-core' ),
								'range' => array(
									'px' => array(
										'step' => 0.1,
										'min'  => 0,
										'max'  => 5,
									),
								),
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused.cursor-outer' => '--alpha-cursor-border-width: {{SIZE}}px',
								),
							)
						);
						
						$self->add_control(
							'hover_cursor_scale',
							array(
								'type'  => Controls_Manager::SLIDER,
								'label' => esc_html__( 'Scale', 'alpha-core' ),
								'range' => array(
									'px' => array(
										'step' => 0.1,
										'min'  => 0,
										'max'  => 5,
									),
								),
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused' => '--alpha-cursor-scale: {{SIZE}}',
								),
							)
						);
						$self->add_control(
							'hover_cursor_circle_text',
							array(
								'label'       => esc_html__( 'Text Inner Circle', 'alpha-core' ),
								'description' => esc_html__( 'Text is shown inner cursor circle area.', 'alpha-core' ),
								'type'        => Controls_Manager::TEXT,
								'condition' => array(
									'cursor_style' => 'circle',
								)
							)
						);
	
						$self->add_group_control(
							Group_Control_Typography::get_type(),
							array(
								'name'     => 'hover_cursor_circle_text_typo',
								'selector' => '{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused:after',
								'condition' => array(
									'cursor_style' => 'circle',
								)
							)
						);
						
						$self->add_control(
							'hover_cursor_circle_text_color',
							array(
								'label'     => esc_html__( 'Text Color', 'alpha-core' ),
								'type'      => Controls_Manager::COLOR,
								'selectors' => array(
									'{{WRAPPER}} .cursor-element-{{ID}}.cursor-focused' => '--alpha-cursor-text-color: {{VALUE}}',
								),
								'condition' => array(
									'cursor_style' => 'circle',
								)
							)
						);
	
					$self->end_controls_tab();
			
				$self->end_controls_tabs();
	
	
			$self->end_controls_section();
		}

		/**
		 * Print scroll section content in elementor section content template function
		 *
		 * @since 1.0
		 */
		public function section_addon_content_template( $self ) {
			?>
			<#
			const iframeWindow = elementorFrontend.elements.$window.get(0);
			if ( typeof iframeWindow.cursor_settings == 'undefined' ) {
				iframeWindow.cursor_settings = [];
			}
	
			const section_id = view.getEditModel().attributes.id;
			iframeWindow.cursor_settings.forEach( function( i, index ) {
				if ( i.id && 'cursor-element-' + section_id == i.id ) {
					iframeWindow.cursor_settings.splice( index, 1 );
					return false;
				}
			} );
			if ( settings.section_cursor_type == 'yes' ) {
				
				var cursor_cls = '',
					cursor_style = '';
	
				if ( section_id ) {
					cursor_cls   = 'cursor-element-' + section_id;
					cursor_style = 'cursor-' + settings.cursor_style;
				}
			#>
				<div class="cursor-inner cursor-hover-visible {{ cursor_cls }} {{ cursor_style }}"></div>
				<div class="cursor-outer cursor-hover-visible {{ cursor_cls }} {{ cursor_style }}" data-inner-text="{{ settings.cursor_circle_text }}" data-inner-text-hover="{{ settings.hover_cursor_circle_text }}"></div>
			<#
				iframeWindow.cursor_settings.push( { id: 'cursor-element-' + section_id, selector: section_id, cursor_style: settings.cursor_style } );
			}
			#>
			<?php
		}

		/**
		 * Render scroll section HTML
		 *
		 * @since 1.0
		 */
		public function section_addon_render( $self, $settings ) {
            // Section Cursor Control Render
            if ( ! empty( $settings ) && 'yes' == $settings['section_cursor_type'] ) {
                $section_id = $self->get_id();

                $cursor_cls = '';
                if ( ! empty( $section_id ) ) {
                    $cursor_cls   = 'cursor-element-' . $section_id;
                    $cursor_style = 'cursor-' . $settings['cursor_style'];
                }

                echo '<div class="cursor-inner cursor-hover-visible ' . $cursor_cls . ' ' . $cursor_style . '"></div>';
                echo '<div class="cursor-outer cursor-hover-visible ' . $cursor_cls . ' ' . $cursor_style . '" data-inner-text="' . alpha_strip_script_tags( $settings['cursor_circle_text'] ) . '" data-inner-text-hover="' . alpha_strip_script_tags( $settings['hover_cursor_circle_text'] ) . '"></div>';
                
                ?>
                    <script>
                        if( typeof window.cursor_settings == 'undefined') {
                            window.cursor_settings = [];
                        }

                        window.cursor_settings.length && window.cursor_settings.forEach(function(i, index) {
                            if ( i.id && '<?php echo esc_js( $cursor_cls ); ?>' == i.id ) {
                                window.cursor_settings.splice( index, 1 );
                                return false;
                            }
                        });

                        window.cursor_settings.push({
                            id: '<?php echo esc_js( $cursor_cls ); ?>',
                            selector: '<?php echo sanitize_text_field( $section_id ); ?>',
                            style: '<?php echo esc_js( $cursor_style ); ?>'
                        });
                    </script>
                <?php
            }
		}
	}
}

/**
 * Create instance
 *
 * @since 1.0
 */
Alpha_Custom_Cursor_Elementor_Widget_Addon::get_instance();
