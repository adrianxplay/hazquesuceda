<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Scroll Section
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

if ( ! class_exists( 'Alpha_Scroll_Section_Elementor_Widget_Addon' ) ) {
	class Alpha_Scroll_Section_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {

			// For section
			add_filter( 'alpha_elementor_section_addons', array( $this, 'register_scroll_section' ) );
			add_action( 'alpha_elementor_section_addon_controls', array( $this, 'add_scroll_section_tabs' ), 10, 2 );
			add_action( 'alpha_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ) );
			add_action( 'alpha_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );
			add_filter( 'alpha_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 10, 3 );

			// For container
			add_action( 'alpha_elementor_container_addon_controls', array( $this, 'add_scroll_section_controls' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_tabs', array( $this, 'add_scroll_section_tabs' ), 10, 2 );
			add_action( 'alpha_elementor_container_addon_content_template', array( $this, 'container_addon_content_template' ) );
			add_filter( 'alpha_elementor_container_addon_render_attributes', array( $this, 'container_addon_attributes' ), 10, 3 );
		}

		/**
		 * Register scroll section addon to section element
		 *
		 * @since 1.0
		 */
		public function register_scroll_section( $addons ) {
			$addons['scroll_section'] = esc_html__( 'Scroll Section', 'alpha-core' );
			return $addons;
		}

		/**
		 * Add banner controls to section element
		 *
		 * @since 1.0
		 */
		public function add_scroll_section_controls( $self, $condition_value ) {
			$self->add_control(
				'section_scrollable',
				array(
					'label' => esc_html__( 'Scrollable Container', 'alpha-core' ),
					'type'  => Controls_Manager::SWITCHER,
				)
			);
		}

		/**
		 * Add banner controls to section element
		 *
		 * @since 1.0
		 */
		public function add_scroll_section_tabs( $self, $condition_value ) {

			$is_container = 'container' == $self->get_type();
			$condition = $is_container ? array( 'section_scrollable' => 'yes' ) : array( $condition_value => 'scroll_section' );

			$self->start_controls_section(
				'scroll_section',
				array(
					'label'     => alpha_elementor_panel_heading( $is_container ? esc_html__( 'Scrollable Container', 'alpha-core' ) : esc_html__( 'Scroll Section', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => $condition,
				)
			);

				$self->add_responsive_control(
					'max_height',
					[
						'label' => esc_html__( 'Max Height', 'alpha-core' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => array( 'px', 'em', 'rem', 'vh', 'custom' ),
						'range' => array(
							'px' => array(
								'min' => 0,
								'max' => 1440,
							),
							'vh' => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'default'   => array(
							'size' => 250,
						),
						'condition'   => $condition,
						'selectors' => $is_container ? array(
							'{{WRAPPER}}' => 'max-height: {{SIZE}}{{UNIT}};',
						) : array(
							'.elementor-element-{{ID}}.elementor-section .elementor-container' => 'max-height: {{SIZE}}{{UNIT}};',
						),
					]
				);

				$self->add_control(
					'scrollbar_handle_color',
					array(
						'label'       => esc_html__( 'Scrollbar Handle Color', 'alpha-core' ),
						'description' => esc_html__( 'Set background color of scrollbar handle.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => $condition,
						'selectors'   => $is_container ? array(
							'{{WRAPPER}}:hover::-webkit-scrollbar-thumb' => 'background: {{VALUE}};',
						) : array(
							'.elementor-element-{{ID}} .scrollable:hover::-webkit-scrollbar-thumb' => 'background: {{VALUE}};',
						),
					)
				);

				$self->add_control(
					'scrollbar_background_color',
					array(
						'label'       => esc_html__( 'Scrollbar Track Color', 'alpha-core' ),
						'description' => esc_html__( 'Set background color of scrollbar track.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => $condition,
						'selectors'   => $is_container ? array(
							'{{WRAPPER}}:hover::-webkit-scrollbar' => 'background: {{VALUE}};',
						) : array(
							'.elementor-element-{{ID}} .scrollable:hover::-webkit-scrollbar' => 'background: {{VALUE}};',
						),
					)
				);

			if ( ! $is_container ) {
				$self->add_control(
					'overlay_color',
					array(
						'label'       => esc_html__( 'Overlay Color', 'alpha-core' ),
						'description' => esc_html__( 'Set the overlay color of the scroll section.', 'alpha-core' ),
						'type'        => Controls_Manager::COLOR,
						'condition'   => $condition,
						'selectors'   => array(
							'.elementor-element-{{ID}}.scroll-overlay-section:after' => 'background-image: linear-gradient(180deg, transparent 0%, {{VALUE}} 159%);',
						),
					)
				);
			}
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
			if ( 'scroll_section' == settings.use_as ) {
				extra_class = ' scroll-section scrollable';
			#>

			<?php if ( $self->legacy_mode ) { ?>
				<#
				addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no" data-scrollable="true">';
				#>
			<?php } else { ?>
				<#
				addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no ' + extra_class + '" data-scrollable="true">';
				#>
			<?php } ?>

				<?php if ( $self->legacy_mode ) { ?>
					<#
					addon_html += '<!-- Begin .elementor-row --><div class="elementor-row' + extra_class + '" data-scrollable="true"	></div><!-- End .elementor-row -->';
					#>
				<?php } ?>

			<#
			addon_html += '</div>';
			}
			#>
			<?php
		}
		
		/**
		 * Print scroll section content in elementor container template function
		 *
		 * @since 1.0
		 */
		public function container_addon_content_template( $self ) {
			?>
			<#
			view.addRenderAttribute( 'con-data', 'class', 'con-data' );
			if ( 'yes' === settings.section_scrollable ) { 
				view.addRenderAttribute( 'con-data', 'data-scrollable', 'true' );
			}
			#>
			<?php
		}

		/**
		 * Add render attributes for scroll section
		 *
		 * @since 1.0
		 */
		public function section_addon_attributes( $options, $self, $settings ) {
			if ( 'scroll_section' == $settings['use_as'] ) {
				$options['class'] = 'scroll-overlay-section';
			}

			return $options;
		}
		
		/**
		 * Add render attributes for scroll section in container mode
		 *
		 * @since 1.0
		 */
		public function container_addon_attributes( $options, $self, $settings ) {
			if ( isset( $settings['section_scrollable'] ) && 'yes' == $settings['section_scrollable'] ) {
				$options['class'] .= ' scroll-section scrollable';
			}

			return $options;
		}

		/**
		 * Render scroll section HTML
		 *
		 * @since 1.0
		 */
		public function section_addon_render( $self, $settings ) {
			if ( 'scroll_section' === $settings['use_as'] ) {

				$extra_class = ' scroll-section scrollable';
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
				<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no<?php echo esc_attr( $extra_class ); ?>">
				<?php endif; ?>
				<?php if ( $self->legacy_mode ) : ?>
				<div class="elementor-row <?php echo esc_attr( $extra_class ); ?>">
					<?php
				endif;
			}
		}
	}
}

/**
 * Create instance
 *
 * @since 1.0
 */
Alpha_Scroll_Section_Elementor_Widget_Addon::get_instance();
