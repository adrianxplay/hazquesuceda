<?php
defined( 'ABSPATH' ) || die;

/**
 * Half Container
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;

if ( ! class_exists( 'Alpha_Half_Container_Elementor_Widget_Addon' ) ) {
	class Alpha_Half_Container_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 4.1
		 */
		public function __construct() {
			// Add half column controls
			add_filter( 'alpha_elementor_column_addons', array( $this, 'register_half_column_addon' ) );
			add_action( 'alpha_elementor_column_addon_controls', array( $this, 'add_half_column_controls' ), 10, 2 );
			add_action( 'alpha_elementor_column_addon_content_template', array( $this, 'half_column_addon_content_template' ) );
			add_action( 'alpha_elementor_column_render', array( $this, 'half_column_addon_render' ), 10, 4 );
		}

		/**
		 * Register half content addon to column element
		 *
		 * @since 4.1
		 */
		public function register_half_column_addon( $addons ) {
			$addons['half_content'] = esc_html__( 'Half Container', 'alpha-core' );

			return $addons;
		}

		/**
		 * Add half controls to column element
		 *
		 * @since 4.1
		 */
		public function add_half_column_controls( $self, $condition_value ) {
			$self->start_controls_section(
				'column_half',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Half Container', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'half_content',
					),
				)
			);
			$self->add_control(
				'is_half_right',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Is Right Aligned?', 'alpha-core' ),
					'description' => esc_html__( 'Make the column alignment to right.', 'alpha-core' ),
				)
			);

			$self->add_control(
				'full_breakpoint',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Full Container On', 'alpha-core' ),
					'description' => esc_html__( 'Make the column\'s width as normal container\'s on selected device.', 'alpha-core' ),
					'default'     => 'tablet',
					'options'     => array(
						'desktop' => esc_html__( 'Desktop', 'alpha-core' ),
						'tablet'  => esc_html__( 'Tablet', 'alpha-core' ),
						'mobile'  => esc_html__( 'Mobile', 'alpha-core' ),
					),
				)
			);

			$self->end_controls_section();
		}

		/**
		 * Print half container in elementor column content template function
		 *
		 * @since 4.1
		 */
		public function half_column_addon_content_template( $self ) {
			?>
		<#
			if ( 'half_content' == settings.use_as ) {
				let wrapper_element = '';
				wrapper_class += ' col-half-section';
				if (settings.is_half_right) {
					wrapper_class += ' col-half-section-right';
				}
				if (settings.full_breakpoint) {
					wrapper_class += ' col-half-section-' + settings.full_breakpoint;
				}
				<?php
				if ( ! alpha_elementor_if_dom_optimization() ) {
					?>
					wrapper_element = 'column';
					addon_html += '<div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + '" ' + wrapper_attrs + '>';
					addon_html += '<div class="elementor-background-overlay"></div>';
					addon_html += '<div class="elementor-widget-wrap' + extra_class + '" ' + extra_attrs + '></div></div>';
					<?php
				} else {
					?>
					extra_class  = '';
					wrapper_element = 'widget';
					addon_html += '<div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + extra_class + '" ' + wrapper_attrs + ' ' + extra_attrs + '>';
					addon_html += '<div class="elementor-background-overlay"></div>';
					<?php
				}
				?>
				<?php if ( alpha_elementor_if_dom_optimization() ) : ?>
					addon_html += '</div>';
				<?php endif; ?>
			}
		#>
			<?php
		}

		/**
		 * Render half container HTML
		 *
		 * @since 4.1
		 */
		public function half_column_addon_render( $self, $settings, $has_background_overlay, $is_legacy_mode_active ) {
			if ( 'half_content' == $settings['use_as'] ) {
				?>
			<!-- Start .elementor-column -->
			<<?php echo ( $self->get_html_tag() ) . ' ' . $self->get_render_attribute_string( '_wrapper' ); ?>>
				<?php
				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );
				$half_class = 'col-half-section ' . esc_attr( 'yes' == $settings['is_half_right'] ? 'col-half-section-right ' : '' ) . ' col-half-section-' . esc_attr( $settings['full_breakpoint'] );
				$self->add_render_attribute( '_inner_wrapper', 'class', $half_class );

				?>
			<!-- Start .elementor-column-wrap(optimize mode => .elementor-widget-wrap) -->
			<div <?php $self->print_render_attribute_string( '_inner_wrapper' ); ?>>
				<?php if ( $has_background_overlay ) : ?>
					<div <?php $self->print_render_attribute_string( '_background_overlay' ); ?>></div>
				<?php endif; ?>
				<?php if ( $is_legacy_mode_active ) : ?>
					<!-- Start .elementor-widget-wrap -->
					<div <?php $self->print_render_attribute_string( '_widget_wrapper' ); ?>>
				<?php endif; ?>
				<?php
			}
		}
	}
}

/**
 * Create instance
 *
 * @since 4.1
 */
Alpha_Half_Container_Elementor_Widget_Addon::get_instance();
