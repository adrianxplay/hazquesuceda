<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Slider Widget Addon
 *
 * Alpha Slider Widget Addon using Elementor Section/Column Element
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

if ( ! class_exists( 'Alpha_Slider_Elementor_Widget_Addon' ) ) {
	class Alpha_Slider_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.2.0
		 */
		public function __construct() {
			add_filter( 'alpha_elementor_section_addons', array( $this, 'register_section_addon' ) );
			add_action( 'alpha_elementor_section_addon_controls', array( $this, 'add_section_controls' ), 10, 2 );
			add_action( 'alpha_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ) );
			add_filter( 'alpha_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 10, 3 );
			add_action( 'alpha_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );
			add_action( 'alpha_elementor_section_after_render', array( $this, 'section_addon_after_render' ), 10, 2 );

			add_filter( 'alpha_elementor_column_addons', array( $this, 'register_column_addon' ) );
			add_action( 'alpha_elementor_column_addon_controls', array( $this, 'add_column_controls' ), 10, 2 );
			add_action( 'alpha_elementor_column_addon_content_template', array( $this, 'column_addon_content_template' ) );
			add_action( 'alpha_elementor_column_render', array( $this, 'column_addon_render' ), 10, 4 );
			add_action( 'alpha_elementor_column_after_render', array( $this, 'column_addon_render_after' ), 10, 3 );
		}

		/**
		 * Register slider addon to section element
		 *
		 * @since 1.2.0
		 */
		public function register_section_addon( $addons ) {
			$addons['slider'] = esc_html__( 'Slider', 'alpha-core' );
			return $addons;
		}

		/**
		 * Add slider controls to section element
		 *
		 * @since 1.2.0
		 */
		public function add_section_controls( $self, $condition_value ) {
			$self->add_control(
				'section_slider_description',
				array(
					'raw'             => sprintf( esc_html__( 'Use %1$schild columns%2$s as %1$sslider item%2$s by using %1$s%3$s settings%2$s.', 'alpha-core' ), '<b>', '</b>', ALPHA_DISPLAY_NAME, ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'alpha-notice notice-warning',
					'condition'       => array(
						$condition_value => 'slider',
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
				'section_slider',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Slider', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'slider',
					),
				)
			);

				alpha_elementor_grid_layout_controls( $self, $condition_value );
				alpha_elementor_slider_layout_controls( $self, $condition_value );

			$self->end_controls_section();

			alpha_elementor_slider_style_controls( $self, $condition_value, false );
		}

		/**
		 * Print slider content in elementor section content template function
		 *
		 * @since 1.2.0
		 */
		public function section_addon_content_template( $self ) {
			?>
			<#
				if ( 'slider' == settings.use_as ) {
					<?php
					alpha_elementor_grid_template();
					alpha_elementor_slider_template();
					if ( ! $self->legacy_mode ) {
						?>
						extra_attrs += ' data-slider-class="' + extra_class + '"';
						extra_class  = '';
						<?php
					}
					?>
					settings.gap = 'no';

					<?php if ( $self->legacy_mode ) { ?>
						addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-' + settings.gap + '">';
						addon_html += '<!-- Begin .elementor-row --><div class="elementor-row' + extra_class + '"' + extra_attrs + '>';
						addon_html += '</div><!-- End .elementor-row -->';
						addon_html += '</div><!-- End .elementor-container -->';
					<?php } else { ?>
						addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-' + settings.gap + extra_class + '"' + extra_attrs + '>';
						addon_html += '</div><!-- End .elementor-container -->';
					<?php } ?>
					if( 'yes' == settings.show_dots && 'yes' == settings.enable_thumb ) {
						addon_html += '<div class="slider-thumb-dots dots-bordered slider-thumb-dots-' + view.getID() + '">';
							if ( settings.thumbs.length ) {
								settings.thumbs.map( function( img, index ) {
									addon_html += '<button class="slider-pagination-bullet' + ( index == 0 ? ' active' : '' ) + '">';
										addon_html += '<img src="' + img['url'] + '" />';
									addon_html += '</button>';
								});
							}
						addon_html += '</div>';
					}
				}
			#>
			<?php
		}

		/**
		 * Add render attributes for slider
		 *
		 * @since 1.2.0
		 */
		public function section_addon_attributes( $options, $self, $settings ) {
			if ( 'slider' == $settings['use_as'] ) {
				if ( 'yes' == $settings['enable_thumb'] ) {
					$options['class'] = 'flex-wrap';
				}
			}
			return $options;
		}

		/**
		 * Render slider HTML
		 *
		 * @since 1.2.0
		 */
		public function section_addon_render( $self, $settings ) {
			if ( 'slider' == $settings['use_as'] ) { // if using as slider
				$col_cnt         = alpha_elementor_grid_col_cnt( $settings );
				$slider_class    = alpha_get_col_class( $col_cnt );
				$slider_class   .= ' ' . alpha_get_grid_space_class( $settings );
				$slider_class   .= ' ' . alpha_get_slider_class( $settings );
				$slider_attrs    = ' data-slider-options="' . esc_attr(
					json_encode(
						alpha_get_slider_attrs( $settings, $col_cnt, $self->get_data( 'id' ) )
					)
				) . '"';
				$settings['gap'] = 'no';

				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );

				if ( $self->legacy_mode ) {
					?>
					<!-- Start .elementor-container -->
					<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-<?php echo esc_attr( $settings['gap'] ) . ( ( 'yes' == $settings['enable_thumb'] ) ? ' flex-wrap' : '' ); ?>">

					<!-- Start .elementor-row & Slider Wrapper -->
					<div class="elementor-row <?php echo esc_attr( $slider_class ); ?>"<?php echo alpha_escaped( $slider_attrs ); ?>>
					<?php
				} else {
					?>
					<!-- Start .elementor-container -->
					<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-<?php echo esc_attr( $settings['gap'] ); ?>">
					<!-- Start Slider Wrapper -->
					<div class="<?php echo esc_attr( $slider_class ); ?>" <?php echo alpha_escaped( $slider_attrs ); ?>>
					<?php
				}
			}
		}

		/**
		 * Render slider HTML after elementor section render
		 *
		 * @since 1.2.0
		 */
		public function section_addon_after_render( $self, $settings ) {
			if ( 'slider' == $settings['use_as'] ) {
				?>
				</div><!-- End Slider Wrapper & .elementor-row -->
				</div><!-- End .elementor-container -->
				<?php
				if ( 'yes' == $settings['show_dots'] && 'yes' == $settings['enable_thumb'] ) {
					?>
					<!-- Start .slider-thumb-dots -->
					<div class="slider-thumb-dots dots-bordered <?php echo 'slider-thumb-dots-' . esc_attr( $self->get_data( 'id' ) ); ?>">
						<?php
						if ( count( $settings['thumbs'] ) ) {
							$first = true;
							foreach ( $settings['thumbs'] as $thumb ) {
								echo '<button class="slider-pagination-bullet' . ( $first ? ' active' : '' ) . '">';
								echo wp_get_attachment_image( $thumb['id'] );
								echo '</button>';
								$first = false;
							}
						}
						?>
					</div>
					<!-- End .slider-thumb-dots -->
					<?php
				}
				?>
				</<?php echo esc_html( $self->get_html_tag() ); ?>>
				<?php
			}
		}

		/**
		 * Register slider addon to column element
		 *
		 * @since 1.2.0
		 */
		public function register_column_addon( $addons ) {
			$addons['slider'] = esc_html__( 'Slider', 'alpha-core' );
			return $addons;
		}

		/**
		 * Add slider controls to column element
		 *
		 * @since 1.2.0
		 */
		public function add_column_controls( $self, $condition_value ) {
			$left  = is_rtl() ? 'right' : 'left';
			$right = 'left' == $left ? 'right' : 'left';

			$self->start_controls_section(
				'column_slider',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Slider', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'slider',
					),
				)
			);
				alpha_elementor_grid_layout_controls( $self, $condition_value );
				alpha_elementor_slider_layout_controls( $self, $condition_value );

			$self->end_controls_section();

			alpha_elementor_slider_style_controls( $self, $condition_value, false );
		}

		/**
		 * Print slider in elementor column content template function
		 *
		 * @since 1.2.0
		 */
		public function column_addon_content_template( $self ) {
			?>
			<#
				if ( 'slider' == settings.use_as ) {
					let wrapper_element = '';
					<?php
						alpha_elementor_grid_template();
						alpha_elementor_slider_template();
					if ( ! alpha_elementor_if_dom_optimization() ) {
						?>
						wrapper_element = 'column';
						addon_html += '<div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + '" ' + wrapper_attrs + '>';
						addon_html += '<div class="elementor-background-overlay"></div>';
						addon_html += '<div class="elementor-widget-wrap' + extra_class + '" ' + extra_attrs + '></div></div>';
						<?php
					} else {
						?>
						extra_attrs += ' data-slider-class="' + extra_class + '"';
						extra_class  = '';
						wrapper_element = 'widget';
						if( settings.dots_pos && ( settings.dots_pos == 'inner' || settings.dots_pos == 'outer' ) ) {
							extra_class = ' elementor-slider-' + settings.dots_pos;
						}
						addon_html += '<div class="elementor-' + wrapper_element + '-wrap' + wrapper_class + extra_class + '" ' + wrapper_attrs + ' ' + extra_attrs + '>';
						addon_html += '<div class="elementor-background-overlay"></div>';
						<?php
					}
					?>
					if( 'yes' == settings.show_dots && 'yes' == settings.enable_thumb ) {
						addon_html += '<div class="slider-thumb-dots dots-bordered slider-thumb-dots-' + view.getID() + '">';
							if ( settings.thumbs.length ) {
								settings.thumbs.map( function( img, index ) {
									addon_html += '<button class="slider-pagination-bullet' + ( index == 0 ? ' active' : '' ) + '">';
									addon_html += '<img src="' + img['url'] + '"/>';
									addon_html += '</button>';
								} );
							}
						addon_html += '</div>';
					}
					<?php if ( alpha_elementor_if_dom_optimization() ) : ?>
						addon_html += '</div>';
					<?php endif; ?>
				}
			#>
			<?php
		}

		/**
		 * Render slider HTML
		 *
		 * @since 1.2.0
		 */
		public function column_addon_render( $self, $settings, $has_background_overlay, $is_legacy_mode_active ) {

			if ( 'slider' == $settings['use_as'] ) {
				if ( 'yes' == $settings['enable_thumb'] ) {
					$self->add_render_attribute( '_inner_wrapper', 'class', 'flex-wrap' );
				}
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
					<!-- Start .slider-container  -->
					<div class="w-100">
					<?php
					$col_cnt      = alpha_elementor_grid_col_cnt( $settings );
					$slider_class = alpha_get_col_class( $col_cnt ) . ' ' . alpha_get_grid_space_class( $settings ) . ' ' . alpha_get_slider_class( $settings );
					$slider_attrs = alpha_get_slider_attrs( $settings, $col_cnt, $self->get_data( 'id' ) );
					?>
					<!-- Start .slider-wrapper -->
					<div class="<?php echo esc_attr( $slider_class ); ?>" data-slider-options="<?php echo esc_attr( json_encode( $slider_attrs ) ); ?>">
				<?php
			}
		}

		/**
		 * Render after slider content.
		 *
		 * @since 1.2.0
		 */
		public function column_addon_render_after( $self, $settings, $is_legacy_mode_active ) {
			if ( 'slider' == $settings['use_as'] ) {
				?>
					</div><!-- End .slider-wrapper -->
					</div><!-- End .slider-container -->
					<?php if ( 'yes' == $settings['show_dots'] && 'yes' == $settings['enable_thumb'] ) : ?>
						<div class="slider-thumb-dots dots-bordered <?php echo 'slider-thumb-dots-' . esc_attr( $self->get_data( 'id' ) ); ?>">
							<?php
							if ( count( $settings['thumbs'] ) ) {
								$first = true;
								foreach ( $settings['thumbs'] as $thumb ) {
									echo '<button class="slider-pagination-bullet' . ( $first ? ' active' : '' ) . '">';
									echo wp_get_attachment_image( $thumb['id'] );
									echo '</button>';
									$first = false;
								}
							}
							?>
						</div>
					<?php endif; ?>

				<?php if ( $is_legacy_mode_active ) : ?>
				</div> <!-- End not optimize => .elementor-widget-wrap -->
				<?php endif; ?>
				</div> <!-- End optimize => .elementor-widget-wrap -->
				</<?php echo esc_html( $self->get_html_tag() ); ?>>
				<!-- End .elementor-column -->
					<?php
			}
		}
	}
}

/**
 * Create instance
 *
 * @since 1.2.0
 */
Alpha_Slider_Elementor_Widget_Addon::get_instance();
