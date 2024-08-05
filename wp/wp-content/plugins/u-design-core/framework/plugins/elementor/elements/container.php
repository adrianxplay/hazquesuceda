<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Container Element
 *
 * Extended Element_Container Class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.3
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Embed;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Shapes;
use Elementor\Modules\DynamicTags\Module as TagsModule;

add_action( 'elementor/frontend/container/before_render', 'alpha_container_render_attributes', 10, 1 );

// Container extended options
add_action( 'elementor/element/container/section_layout_container/before_section_end', 'alpha_container_layout_options', 10, 2 );
add_action( 'elementor/element/container/section_layout/before_section_end', 'alpha_container_advanced_options', 10, 2 );
add_action( 'elementor/element/container/section_background_overlay/before_section_end', 'alpha_container_bg_overlay_options', 10, 2 );

if ( ! class_exists( 'Alpha_Element_Container' ) ) {
	class Alpha_Element_Container extends Elementor\Includes\Elements\Container {
		/**
		 * Render the element JS template.
		 *
		 * @return void
		 */
		protected function content_template() {
			?>

			<#
			view.addRenderAttribute( 'con-data', 'class', 'con-data' );
			if ( 'boxed' === settings.content_width && 'yes' == settings.section_content_type ) { 
				view.addRenderAttribute( 'con-data', 'data-c-fluid', 'true' );
			}
			if ( 'yes' == settings.mask_reveal ) { 
				view.addRenderAttribute( 'con-data', 'data-mask-reveal', 'true' );
			}
			#>
			
			<?php
			/**
			 * Fires after print section output in elementor column content template function.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_container_addon_content_template', $this );
			?>

			<#
			if ( 'boxed' === settings.content_width ) { 
				#>
				<div class="e-con-inner">
				<#
			}
			if ( settings.background_video_link ) {
				let videoAttributes = 'autoplay muted playsinline';

				if ( ! settings.background_play_once ) {
					videoAttributes += ' loop';
				}

				view.addRenderAttribute( 'background-video-container', 'class', 'elementor-background-video-container' );

				if ( ! settings.background_play_on_mobile ) {
					view.addRenderAttribute( 'background-video-container', 'class', 'elementor-hidden-phone' );
				}
				#>
				<div {{{ view.getRenderAttributeString( 'background-video-container' ) }}}>
					<div class="elementor-background-video-embed"></div>
					<video class="elementor-background-video-hosted elementor-html5-video" {{ videoAttributes }}></video>
				</div>
			<# } #>
			<div class="elementor-shape elementor-shape-top"></div>
			<div class="elementor-shape elementor-shape-bottom"></div>
			
			<div {{{ view.getRenderAttributeString( 'con-data' ) }}}></div>
			
			<# if ( 'boxed' === settings.content_width ) { #>
				</div>
			<# } #>
			<?php
		}

		/**
		 * Render the video background markup.
		 *
		 * @return void
		 */
		protected function render_video_background() {
			$settings = $this->get_settings_for_display();

			if ( 'video' !== $settings['background_background'] ) {
				return;
			}

			if ( ! $settings['background_video_link'] ) {
				return;
			}

			$video_properties = Embed::get_video_properties( $settings['background_video_link'] );

			$this->add_render_attribute( 'background-video-container', 'class', 'elementor-background-video-container' );

			if ( ! $settings['background_play_on_mobile'] ) {
				$this->add_render_attribute( 'background-video-container', 'class', 'elementor-hidden-phone' );
			}

			?>
			<div <?php $this->print_render_attribute_string( 'background-video-container' ); ?>>
				<?php if ( $video_properties ) : ?>
					<div class="elementor-background-video-embed"></div>
					<?php
				else :
					$video_tag_attributes = 'autoplay muted playsinline';

					if ( 'yes' !== $settings['background_play_once'] ) {
						$video_tag_attributes .= ' loop';
					}
					?>
					<video class="elementor-background-video-hosted elementor-html5-video" <?php echo esc_attr( $video_tag_attributes ); ?>></video>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Render the Container's shape divider.
		 * TODO: Copied from `section.php`.
		 *
		 * Used to generate the shape dividers HTML.
		 *
		 * @param string $side - Shape divider side, used to set the shape key.
		 *
		 * @return void
		 */
		protected function render_shape_divider( $side ) {
			$settings         = $this->get_active_settings();
			$base_setting_key = "shape_divider_$side";
			$negative         = ! empty( $settings[ $base_setting_key . '_negative' ] );
			$divider_key      = $settings[ $base_setting_key ];

			if ( 'custom' != $divider_key ) {
				$shape_path = Shapes::get_shape_path( $settings[ $base_setting_key ], $negative );

				if ( 'alpha-' == substr( $divider_key, 0, strlen( 'alpha-' ) ) ) {
					$shape_path = ALPHA_CORE_PATH . '/assets/images/builders/elementor/shapes/' . str_replace( 'alpha-', '', $divider_key ) . ( $negative ? '-negative' : '' ) . '.svg';
				}

				if ( ! is_file( $shape_path ) || ! is_readable( $shape_path ) ) {
					return;
				}
			}
			?>
			<div class="elementor-shape elementor-shape-<?php echo esc_attr( $side ); ?>" data-negative="<?php Utils::print_unescaped_internal_string( $negative ? 'true' : 'false' ); ?>">
				<?php
				if ( 'custom' != $divider_key ) {
					// PHPCS - The file content is being read from a strict file path structure.
					echo Utils::file_get_contents( $shape_path ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					if ( isset( $settings[ "shape_divider_{$side}_custom" ] ) && isset( $settings[ "shape_divider_{$side}_custom" ]['value'] ) ) {
						\ELEMENTOR\Icons_Manager::render_icon( $settings[ "shape_divider_{$side}_custom" ] );
					}
				}
				?>
			</div>
			<?php
		}

		/**
		 * Print safe HTML tag for the element based on the element settings.
		 *
		 * @return void
		 */
		protected function print_html_tag() {
			$html_tag = $this->get_settings( 'html_tag' );

			if ( empty( $html_tag ) ) {
				$html_tag = 'div';
			}

			Utils::print_validated_html_tag( $html_tag );
		}

		/**
		 * Before rendering the container content. (Print the opening tag, etc.)
		 *
		 * @return void
		 */
		public function before_render() {
			$settings = $this->get_settings_for_display();
			$link     = $settings['link'];

			if ( ! empty( $link['url'] ) ) {
				$this->add_link_attributes( '_wrapper', $link );
			}

			?>
			<<?php $this->print_html_tag(); ?> <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
			<?php

			/**
			 * Fires before rendering section addon html.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_before_elementor_container_render', $this, $settings );

			if ( $this->is_boxed_container( $settings ) ) {
				?>
				<div class="e-con-inner">
				<?php
			} else {
				if ( ( isset( $settings['mask_reveal'] ) && $settings['mask_reveal'] ) ||
					( isset( $settings['section_content_sticky'] ) && 'yes' == $settings['section_content_sticky'] && 'side' == $settings['sticky_position'] ) ) {
					echo '<div class="e-con-custom-inner">';
				}
			}

			$this->render_video_background();

			if ( ! empty( $settings['shape_divider_top'] ) ) {
				$this->render_shape_divider( 'top' );
			}

			if ( ! empty( $settings['shape_divider_bottom'] ) ) {
				$this->render_shape_divider( 'bottom' );
			}

			/**
			 * Fires after rendering effect addons such as duplex and ribbon.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_addon_render', $settings, $this->get_ID() );
		}

		/**
		 * After rendering the Container content. (Print the closing tag, etc.)
		 *
		 * @return void
		 */
		public function after_render() {
			$settings = $this->get_settings_for_display();

			if ( $this->is_boxed_container( $settings ) ) {
				?>
				</div>
				<?php
			} else {
				if ( ( isset( $settings['mask_reveal'] ) && $settings['mask_reveal'] ) ||
				( isset( $settings['section_content_sticky'] ) && 'yes' == $settings['section_content_sticky'] && 'side' == $settings['sticky_position'] ) ) {
					echo '</div>';
				}
			}

			/**
			 * Fires before rendering section addon html.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_after_elementor_container_render', $this, $settings );
			?>

			</<?php $this->print_html_tag(); ?>>
			<?php
		}

		protected function is_boxed_container( array $settings ) {
			return ! empty( $settings['content_width'] ) && 'boxed' === $settings['content_width'];
		}

		/**
		 * Register the Container's controls.
		 *
		 * @return void
		 */
		protected function register_controls() {
			parent::register_controls();

			$this->update_responsive_control(
				'width',
				array(
					'label' => esc_html__( 'Max Width', 'alpha-core' ),
				)
			);

			$this->remove_control( 'boxed_width' );

			$this->start_controls_section(
				'section_alpha_additional',
				array(
					'label' => alpha_elementor_panel_heading( ALPHA_DISPLAY_NAME . esc_html__( ' Options', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_LAYOUT,
				)
			);

				do_action( 'alpha_elementor_container_addon_controls', $this, '' );

			$this->end_controls_section();

			/**
			 * Fires after add controls to section element.
			 *
			 * @since 1.3
			 */
			alpha_elementor_addon_controls( $this, 'section' );

			do_action( 'alpha_elementor_container_addon_tabs', $this, '' );
		}
	}
}

if ( ! function_exists( 'alpha_container_render_attributes' ) ) {
	/**
	 * Add render attributes for container.
	 *
	 * @since 1.0
	 */
	function alpha_container_render_attributes( $self ) {
		$settings = $self->get_settings_for_display();
		$options  = array( 'class' => '' );

		if ( isset( $settings['section_content_type'] ) && 'yes' == $settings['section_content_type'] ) {
			$options['class'] .= ' c-fluid';
		}

		if ( isset( $settings['mask_reveal'] ) && $settings['mask_reveal'] ) {
			$options['class'] .= ' alpha-entrance-reveal';
		}

		/**
		 * Filters render attribute for add on section.
		 *
		 * @since 1.0
		 */
		$options = apply_filters( 'alpha_elementor_container_addon_render_attributes', $options, $self, $settings );

		$self->add_render_attribute(
			array(
				'_wrapper' => alpha_get_elementor_addon_options( $settings, $options ),
				// '_wrapper' => $options,
			)
		);
	}
}

if ( ! function_exists( 'alpha_container_layout_options' ) ) {
	/**
	 * Add container layout extended options
	 *
	 * @since 1.3
	 */
	function alpha_container_layout_options( $self, $args ) {
		$self->add_control(
			'section_content_type',
			array(
				'label'       => alpha_elementor_panel_heading( esc_html__( 'Container Fluid', 'alpha-core' ) ),
				'description' => esc_html__( 'Determines whether content width is container or container fluid.', 'alpha-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'content_width' => 'boxed',
				),
			),
			array(
				'position' => array(
					'of' => 'content_width',
				),
			)
		);

		$self->add_responsive_control(
			'extend_con_width',
			array(
				'label'       => alpha_elementor_panel_heading( esc_html__( 'Extend Width', 'alpha-core' ) ),
				'description' => esc_html__( 'Extend container width if it has sub-containers that have paddings so that it could be same in width with other containers.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'rem',
				),
				'range'       => array(
					'px'  => array(
						'min' => 0,
						'max' => 200,
					),
					'rem' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}}' => '--alpha-con-ex-width: {{SIZE}}{{UNIT}}',
				),
				'condition'   => array(
					'content_width' => 'boxed',
				),
			),
			array(
				'position' => array(
					'of' => 'section_content_type',
				),
			)
		);
	}
}

if ( ! function_exists( 'alpha_container_advanced_options' ) ) {
	function alpha_container_advanced_options( $self, $args ) {
		$self->add_responsive_control(
			'e_con_width',
			array(
				'label'      => esc_html__( 'Width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 300,
						'max'  => 1500,
						'step' => 10,
					),
				),
				'selectors'  => [
					'{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				],
			),
			array(
				'position' => array(
					'of' => 'padding',
				),
			)
		);
	}
}

if ( ! function_exists( 'alpha_container_bg_overlay_options' ) ) {
	function alpha_container_bg_overlay_options( $self, $args ) {
		$self->add_responsive_control(
			'bg_overlay_z_index',
			array(
				'label'     => esc_html__( 'Z-Index', 'alpha-core' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'selectors' => [
					'{{WRAPPER}}::before, {{WRAPPER}} > .elementor-background-video-container::before, {{WRAPPER}} > .e-con-inner > .elementor-background-video-container::before, {{WRAPPER}} > .elementor-background-slideshow::before, {{WRAPPER}} > .e-con-inner > .elementor-background-slideshow::before, {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer::before' => 'z-index: {{VALUE}};',
				],
			),
		);
	}
}
