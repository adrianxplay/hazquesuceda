<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Accordion Widget Addon
 *
 * Alpha Accordion Widget Addon using Elementor Section/Column Element
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Accordion_Elementor_Widget_Addon' ) ) {
	class Alpha_Accordion_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			// Enqueue component css
			add_action( 'alpha_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );
			add_filter( 'alpha_elementor_section_addons', array( $this, 'register_section_addon' ) );
			add_action( 'alpha_elementor_section_addon_controls', array( $this, 'add_section_controls' ), 10, 2 );
			add_action( 'alpha_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ) );
			add_filter( 'alpha_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 10, 3 );
			add_action( 'alpha_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );
			add_action( 'alpha_elementor_section_after_render', array( $this, 'section_addon_after_render' ), 10, 2 );

			add_filter( 'alpha_elementor_column_addons', array( $this, 'register_column_addon' ) );
			add_action( 'alpha_elementor_column_addon_controls', array( $this, 'add_column_controls' ), 10, 2 );
			add_action( 'alpha_elementor_column_addon_content_template', array( $this, 'column_addon_content_template' ) );
			add_filter( 'alpha_elementor_column_addon_render_attributes', array( $this, 'column_addon_attributes' ), 10, 3 );
			add_action( 'alpha_elementor_column_render', array( $this, 'column_addon_render' ), 10, 4 );
		}

		/**
		 * Enqueue component css
		 *
		 * @since 1.2.0
		 */
		public function enqueue_scripts() {
			if ( alpha_is_elementor_preview() ) {
				wp_enqueue_style( 'alpha-accordion', alpha_core_framework_uri( '/widgets/accordion/accordion' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}
		}

		/**
		 * Register accordion addon to section element
		 *
		 * @since 1.2.0
		 */
		public function register_section_addon( $addons ) {
			$addons['accordion'] = esc_html__( 'Accordion', 'alpha-core' );

			return $addons;
		}

		/**
		 * Add accordion controls to section element
		 *
		 * @since 1.2.0
		 */
		public function add_section_controls( $self, $condition_value ) {
			$self->add_control(
				'section_accordion_description',
				array(
					'raw'             => sprintf( esc_html__( 'Use %1$schild columns%2$s as %1$saccordion content%2$s by using %1$s%3$s settings%2$s.', 'alpha-core' ), '<b>', '</b>', ALPHA_DISPLAY_NAME ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'alpha-notice notice-warning',
					'condition'       => array(
						$condition_value => 'accordion',
					),
				),
				array(
					'position' => array(
						'at' => 'after',
						'of' => $condition_value,
					),
				)
			);

			$self->start_controls_section(
				'section_accordion',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Accordion', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'accordion',
					),
				)
			);

			$self->add_control(
				'accordion_type',
				array(
					'label'       => esc_html__( 'Accordion Type', 'alpha-core' ),
					'description' => esc_html__( 'Choose the design style for accordion.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'width'       => 1,
					'options'     => array(
						''        => esc_html__( 'Default', 'alpha-core' ),
						'shadow'  => esc_html__( 'Shadow', 'alpha-core' ),
						'stacked' => esc_html__( 'Stacked', 'alpha-core' ),
					),
				)
			);

			$self->add_control(
				'accordion_card_heading',
				array(
					'label'     => esc_html__( 'Card', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$self->add_control(
				'accordion_card_space',
				array(
					'label'       => esc_html__( 'Card Space', 'alpha-core' ),
					'description' => esc_html__( 'Set the space between each card items.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', 'rem', 'em' ),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion' => '--alpha-accordion-card-spacing: {{SIZE}}{{UNIT}}',
					),
				)
			);

			$self->add_control(
				'accordion_card_header_heading',
				array(
					'label'     => esc_html__( 'Card Header', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$self->add_control(
				'accordion_icon',
				array(
					'label'            => esc_html__( 'Toggle Icon', 'alpha-core' ),
					'description'      => esc_html__( 'Choose inactive(closed) toggle icon of card header.', 'alpha-core' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'default'          => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-plus',
						'library' => 'alpha-icons',
					),
					'recommended'      => array(
						'fa-solid'   => array(
							'chevron-down',
							'angle-down',
							'angle-double-down',
							'caret-down',
							'caret-square-down',
						),
						'fa-regular' => array(
							'caret-square-down',
						),
					),
					'skin'             => 'inline',
					'label_block'      => false,
				)
			);

			$self->add_control(
				'accordion_active_icon',
				array(
					'label'            => esc_html__( 'Active Toggle Icon', 'alpha-core' ),
					'description'      => esc_html__( 'Choose active(opened) toggle icon of card header.', 'alpha-core' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon_active',
					'default'          => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-minus',
						'library' => 'alpha-icons',
					),
					'recommended'      => array(
						'fa-solid'   => array(
							'chevron-up',
							'angle-up',
							'angle-double-up',
							'caret-up',
							'caret-square-up',
						),
						'fa-regular' => array(
							'caret-square-up',
						),
					),
					'skin'             => 'inline',
					'label_block'      => false,
				)
			);

			$self->add_responsive_control(
				'toggle_icon_size',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Toggle Icon Size', 'alpha-core' ),
					'description' => esc_html__( 'Set size of card header toggle icon.', 'alpha-core' ),
					'size_units'  => array( 'px', 'rem', 'em' ),
					'range'       => array(
						'px'  => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 100,
						),
						'rem' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
						'em'  => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .toggle-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .toggle-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$self->end_controls_section();

			$self->start_controls_section(
				'section_accordion_style',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Accordion', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						$condition_value => 'accordion',
					),
				)
			);

			$self->add_control(
				'accordion_card_style_heading',
				array(
					'label' => esc_html__( 'Card', 'alpha-core' ),
					'type'  => Controls_Manager::HEADING,
				)
			);

			$self->add_control(
				'accordion_card_bg',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Set background color of card including card header and card body.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion .card' => 'background-color: {{VALUE}};',
					),
				)
			);

			$self->add_control(
				'accordion_card_bd_width',
				array(
					'label'       => esc_html__( 'Border Width', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'description' => esc_html__( 'Set the border width of accordion card.', 'alpha-core' ),
					'size_units'  => array(
						'px',
						'%',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion .card' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->add_control(
				'accordion_card_bd_radius',
				array(
					'label'       => esc_html__( 'Border Radius', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'description' => esc_html__( 'Set the border radius of accordion card.', 'alpha-core' ),
					'size_units'  => array(
						'px',
						'%',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion .card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					),
				)
			);

			$self->add_control(
				'accordion_card_bd_color',
				array(
					'label'       => esc_html__( 'Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Set border color of card.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion .card' => '--alpha-accordion-border-color: {{VALUE}};',
					),
				)
			);

			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'        => 'accordion_box_shadow',
					'description' => esc_html__( 'Set box shadow of entire accordion.', 'alpha-core' ),
					'selector'    => '.elementor-element-{{ID}} .accordion .card',
				)
			);

			$self->add_control(
				'accordion_card_header_style_heading',
				array(
					'label'     => esc_html__( 'Card Header', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$self->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'        => 'panel_header_typography',
					'label'       => esc_html__( 'Typography', 'alpha-core' ),
					'description' => esc_html__( 'Set typography of card headers.', 'alpha-core' ),
					'selector'    => '.elementor-element-{{ID}} .card-header a',
				)
			);

			$self->add_responsive_control(
				'accordion_header_pad',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'description' => esc_html__( 'Set padding of card headers.', 'alpha-core' ),
					'size_units'  => array(
						'px',
						'%',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion .card-header a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.elementor-element-{{ID}} .accordion .card-header .opened, .elementor-element-{{ID}} .accordion .card-header .closed' => 'right: {{RIGHT}}{{UNIT}};',
					),
				)
			);

			$self->start_controls_tabs( 'accordion_color_tabs' );

				$self->start_controls_tab(
					'accordion_color_normal_tab',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$self->add_control(
						'accordion_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Controls normal color of card header.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .card-header a' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .card-header svg' => 'fill: {{VALUE}};',
							),
						)
					);

					$self->add_control(
						'accordion_bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Controls normal background color of card header.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .accordion .card-header a' => 'background-color: {{VALUE}};',
							),
						)
					);

				$self->end_controls_tab();

				$self->start_controls_tab(
					'accordion_color_active_tab',
					array(
						'label' => esc_html__( 'Active', 'alpha-core' ),
					)
				);

					$self->add_control(
						'accordion_color_active',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Controls color of active card header', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .accordion .card-header a.collapse, .elementor-element-{{ID}} .accordion .card-header a:hover' => 'color: {{VALUE}};',
								'.elementor-element-{{ID}} .accordion .card-header a.collapse svg, .elementor-element-{{ID}} .accordion .card-header a:hover svg' => 'fill: {{VALUE}};',
							),
						)
					);

					$self->add_control(
						'accordion_bg_color_active',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'description' => esc_html__( 'Controls background color of active card header.', 'alpha-core' ),
							'selectors'   => array(
								'.elementor-element-{{ID}} .accordion .card-header a.collapse, .elementor-element-{{ID}} .accordion .card-header a:hover' => 'background-color: {{VALUE}};',
							),
						)
					);

				$self->end_controls_tab();

			$self->end_controls_tabs();

			$self->add_control(
				'accordion_card_body_style_heading',
				array(
					'label'     => esc_html__( 'Card Body', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$self->add_responsive_control(
				'accordion_body_pad',
				array(
					'label'       => esc_html__( 'Padding', 'alpha-core' ),
					'description' => esc_html__( 'Set padding of card body content.', 'alpha-core' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array(
						'px',
						'%',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .accordion .card .card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$self->end_controls_section();
		}

		/**
		 * Print accordion content in elementor section content template function
		 *
		 * @since 1.2.0
		 */
		public function section_addon_content_template( $self ) {
			?>
			<#
			if ( 'accordion' == settings.use_as ) {
				extra_class = ' accordion' + ( settings.accordion_type ? ' accordion-' + settings.accordion_type : '' );

				extra_attrs = ' data-toggle-icon="';
				if ( settings.accordion_icon && settings.accordion_icon.value ) {
					if( settings.accordion_icon.library && 'svg' == settings.accordion_icon.library ) {
						var svgHtml = elementor.helpers.renderIcon( view, settings.accordion_icon, { 'aria-hidden': true } );
					}
					if ( typeof svgHtml != 'undefined' ) {
						svgString = '' + svgHtml.value;
						extra_attrs += svgString.replaceAll( '\"', '\~' );
					} else {
						extra_attrs += '<i class=~' + settings.accordion_icon.value + '~></i>';
					}
				}

				extra_attrs = extra_attrs + '"' + ' data-toggle-active-icon="';
				if ( settings.accordion_active_icon && settings.accordion_active_icon.value ) {
					if( settings.accordion_active_icon.library && 'svg' == settings.accordion_active_icon.library ) {
						var svgHtml_active = elementor.helpers.renderIcon( view, settings.accordion_active_icon, { 'aria-hidden': true } );
					}
					if ( typeof svgHtml_active != 'undefined' ) {
						svgString = '' + svgHtml_active.value;
						extra_attrs += svgString.replaceAll( '\"', '\~' );
					} else {
						extra_attrs += '<i class=~' + settings.accordion_active_icon.value + '~></i>';
					}
				}

				extra_attrs +=  '"';
				#>

			<?php if ( $self->legacy_mode ) { ?>
				<#
				addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no" ' + wrapper_attrs + '>';
				#>
			<?php } else { ?>
				<#
				addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no ' + extra_class + '" ' + extra_attrs + '>';
				#>
			<?php } ?>

				<?php if ( $self->legacy_mode ) { ?>
					<#
					addon_html += '<!-- Begin .elementor-row --><div class="elementor-row' + extra_class + '" ' + extra_attrs + '></div><!-- End .elementor-row -->';
					#>
				<?php } ?>

			<#
			addon_html += '</div>';
			}
			#>
				<?php
		}

		/**
		 * Add render attributes for accordion
		 *
		 * @since 1.2.0
		 */
		public function section_addon_attributes( $options, $self, $settings ) {
			if ( 'accordion' == $settings['use_as'] ) {
				global $alpha_section;

				if ( ! isset( $alpha_section['section'] ) ) {
					$alpha_section = array(
						'section'     => 'accordion',
						'parent_id'   => $self->get_data( 'id' ),
						'index'       => 0,
						'icon'        => $settings['accordion_icon'],
						'active_icon' => $settings['accordion_active_icon'],
					);
				}
			}

			return $options;
		}

		/**
		 * Render accordion HTML
		 *
		 * @since 1.2.0
		 */
		public function section_addon_render( $self, $settings ) {
			if ( 'accordion' == $settings['use_as'] ) {
				wp_enqueue_style( 'alpha-accordion', alpha_core_framework_uri( '/widgets/accordion/accordion' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

				$extra_class = ' accordion' . ( $settings['accordion_type'] ? ' accordion-' . $settings['accordion_type'] : '' );

				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );

				if ( $self->legacy_mode ) :
					?>
				<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no">
				<?php else : ?>
				<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no <?php echo esc_attr( $extra_class ); ?>">
				<?php endif; ?>
				<?php if ( $self->legacy_mode ) : ?>
				<div class="elementor-row <?php echo esc_attr( $extra_class ); ?>">
					<?php
				endif;
			}
		}

		/**
		 * Render accordion HTML after elementor section render
		 *
		 * @since 1.2.0
		 */
		public function section_addon_after_render( $self, $settings ) {
			if ( 'accordion' == $settings['use_as'] ) {
				if ( $self->legacy_mode ) {
					echo '</div>';
				}
				echo '</div>';
				?>
			</<?php echo esc_html( $self->get_html_tag() ); ?>>
				<?php
				unset( $GLOBALS['alpha_section'] );
			}
		}

		/**
		 * Register accordion content addon to column element
		 *
		 * @since 1.2.0
		 */
		public function register_column_addon( $addons ) {
			$addons['accordion_content'] = alpha_elementor_panel_heading( esc_html__( 'Accordion Content', 'alpha-core' ) );

			return $addons;
		}

		/**
		 * Add accordion content controls to column element
		 *
		 * @since 1.2.0
		 */
		public function add_column_controls( $self, $condition_value ) {
			$left  = is_rtl() ? 'right' : 'left';
			$right = 'left' == $left ? 'right' : 'left';

			$self->start_controls_section(
				'column_acc',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Accordion Content', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'accordion_content',
					),
				)
			);

			$self->add_control(
				'accordion_title',
				array(
					'label'       => esc_html__( 'Card Title', 'alpha-core' ),
					'description' => esc_html__( 'Set header title of each card items.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Accordion Title', 'alpha-core' ),
				)
			);

			$self->add_control(
				'accordion_header_icon',
				array(
					'label'            => esc_html__( 'Prefix Icon', 'alpha-core' ),
					'description'      => esc_html__( 'Choose different prefix icon of each card headers.', 'alpha-core' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'skin'             => 'inline',
					'label_block'      => false,
				)
			);

			$self->add_control(
				'accordion_header_icon_size',
				array(
					'label'       => esc_html__( 'Prefix Icon Size', 'alpha-core' ),
					'description' => esc_html__( 'Set font size of prefix icons of card headers.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .card-header a > i:first-child'   => 'font-size: {{SIZE}}{{UNIT}};',
						'.elementor-element-{{ID}} .card-header a > svg:first-child' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$self->add_control(
				'accordion_header_icon_space',
				array(
					'label'       => esc_html__( 'Prefix Icon Space', 'alpha-core' ),
					'description' => esc_html__( 'Set spacing between prefix icon and card header title.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array(
						'px',
						'rem',
						'em',
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .card-header a > i:first-child' => "margin-{$right}: {{SIZE}}{{UNIT}};",
						'.elementor-element-{{ID}} .card-header a > svg:first-child' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
				)
			);
			$self->end_controls_section();
		}


		/**
		 * Get icon from library or svg from upload
		 *
		 * @since 1.2.0
		 */
		public function widget_accordion_get_label( $settings, $name ) {
			if ( isset( $settings[ $name ]['library'] ) && 'svg' === $settings[ $name ]['library'] ) {
				ob_start();
				\ELEMENTOR\Icons_Manager::render_icon(
					array(
						'library' => 'svg',
						'value'   => array( 'id' => absint( isset( $settings[ $name ]['value']['id'] ) ? $settings[ $name ]['value']['id'] : 0 ) ),
					),
					array( 'aria-hidden' => 'true' )
				);
				$svg = ob_get_clean();
				return $svg;
			}
			return '<i class="' . esc_attr( $settings[ $name ]['value'] ) . '"></i>';
		}

		/**
		 * Print accordion content in elementor column content template function
		 *
		 * @since 1.2.0
		 */
		public function column_addon_content_template( $self ) {
			?>
		<#

		if( 'accordion_content' == settings.use_as ) {
			let wrapper_class = '';
			let wrapper_element = '';

			wrapper_attrs += ' data-accordion-title="' + settings.accordion_title + '"' + ' data-accordion-icon="';
			if ( settings.accordion_header_icon && settings.accordion_header_icon.value ) {
				if( settings.accordion_header_icon.library && 'svg' == settings.accordion_header_icon.library ) {
					var svgString = '' + elementor.helpers.renderIcon( view, settings.accordion_header_icon, { 'aria-hidden': true } ).value;
					wrapper_attrs += svgString.replaceAll( '\"', '\~' );
				} else {
					wrapper_attrs += '<i class=~' + settings.accordion_header_icon.value + '~></i>';
				}
			}
			wrapper_attrs += '"';
			wrapper_class += ' card-body expanded';
			#>

			<?php if ( ! alpha_elementor_if_dom_optimization() ) { ?>
				<# wrapper_element = 'column'; #>
			<?php } else { ?>
				<# wrapper_element = 'widget'; #>
			<?php } ?>

			<#
			addon_html += '<h4 class="card-header"></h4>';
			addon_html += '<!-- Start .elementor-column-wrap(optimize mode => .elementor-widget-wrap) --><div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + '" ' + wrapper_attrs + '>';
			addon_html += '<div class="elementor-background-overlay"></div>';
			#>

			<?php if ( ! alpha_elementor_if_dom_optimization() ) { ?>
				<# addon_html += '<!-- Start .elementor-widget-wrap --><div class="elementor-widget-wrap' + extra_class + '" ' + extra_attrs + '></div>'; #>
			<?php } ?>

			<#
			addon_html += '</div><!-- End .elementor-column-wrap(optimize mode => .elementor-widget-wrap) -->';
		}

		#>
			<?php
		}

		/**
		 * Render accordion content HTML
		 *
		 * @since 1.2.0
		 */
		public function column_addon_render( $self, $settings, $has_background_overlay, $is_legacy_mode_active ) {
			if ( 'accordion_content' == $settings['use_as'] ) :
				?>
			<<?php echo $self->get_html_tag() . ' ' . $self->get_render_attribute_string( '_wrapper' ); ?>>
				<?php
				global $alpha_section;
				?>
				<h4 class="card-header">
					<a href="<?php echo esc_attr( $self->get_data( 'id' ) ); ?>" class="<?php echo 1 == $alpha_section['index'] ? esc_html__( 'collapse', 'alpha-core' ) : esc_html__( 'expand', 'alpha-core' ); ?>">
						<?php
						if ( $settings['accordion_header_icon']['value'] ) {
							echo alpha_escaped( $this->widget_accordion_get_label( $settings, 'accordion_header_icon' ) );
						}
						?>
						<span class="title"><?php echo ! $settings['accordion_title'] ? esc_html__( 'Untitled', 'alpha-core' ) : esc_html( $settings['accordion_title'] ); ?></span>
						<?php
						if ( isset( $alpha_section['active_icon'] ) && $alpha_section['active_icon']['value'] ) {
							printf( '<span class="toggle-icon opened">%1$s</span>', $this->widget_accordion_get_label( $alpha_section, 'active_icon' ) );
						}
						if ( isset( $alpha_section['icon'] ) && $alpha_section['icon']['value'] ) {
							printf( '<span class="toggle-icon closed">%1$s</span>', $this->widget_accordion_get_label( $alpha_section, 'icon' ) );
						}
						?>
					</a>
				</h4>
				<?php
				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );
				?>

				<div <?php $self->print_render_attribute_string( '_inner_wrapper' ); ?>>
					<?php if ( $has_background_overlay ) : ?>
					<div <?php $self->print_render_attribute_string( '_background_overlay' ); ?>></div>
					<?php endif; ?>
					<?php if ( $is_legacy_mode_active ) : ?>
					<div <?php $self->print_render_attribute_string( '_widget_wrapper' ); ?>>
					<?php endif; ?>
				<?php
			endif;
		}

		/**
		 * Add render attributes for accordion content
		 *
		 * @since 1.2.0
		 */
		public function column_addon_attributes( $options, $self, $settings ) {
			if ( 'accordion_content' == $settings['use_as'] ) {
				global $alpha_section;

				if ( isset( $alpha_section['section'] ) ) {
					$options['inner_args']['id']       = $self->get_data( 'id' );
					$options['wrapper_args']['class'] .= ' card';
					if ( 'accordion' == $alpha_section['section'] ) {
						if ( 0 == $alpha_section['index'] ) {
							$options['wrapper_args']['class'] .= ' collapse';
							$options['inner_args']['class']    = 'card-body expanded';
						} else {
							$options['inner_args']['class'] = 'card-body collapsed';
						}
					}
					$alpha_section['index'] = ++$alpha_section['index'];
				}
			}

			return $options;
		}
	}
}

/**
 * Create instance
 *
 * @since 1.2.0
 */
Alpha_Accordion_Elementor_Widget_Addon::get_instance();
