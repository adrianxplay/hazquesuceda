<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Section Element
 *
 * Extended Element_Section Class
 * Added Slider, Banner, Creative Grid Functions.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

add_action( 'elementor/frontend/section/before_render', 'alpha_section_render_attributes', 10, 1 );

if ( ! class_exists( 'Alpha_Element_Section' ) ) {
	class Alpha_Element_Section extends Elementor\Element_Section {

		public $legacy_mode     = true;
		private static $presets = array();

		public function __construct( array $data = array(), array $args = null ) {
			parent::__construct( $data, $args );
			$this->legacy_mode = ! alpha_elementor_if_dom_optimization();
		}

		protected function get_initial_config() {
			global $post;
			if ( ( $post && ALPHA_NAME . '_template' == $post->post_type && ( 'header' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) || 'footer' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) ) {
				$config = parent::get_initial_config();

				$config['presets']       = self::get_presets();
				$config['controls']      = $this->get_controls();
				$config['tabs_controls'] = $this->get_tabs_controls();

				return $config;
			} else {
				return parent::get_initial_config();
			}
		}

		public static function get_presets( $columns_count = null, $preset_index = null ) {
			if ( ! self::$presets ) {
				self::init_presets();
			}

			$presets = self::$presets;

			if ( null !== $columns_count ) {
				$presets = $presets[ $columns_count ];
			}

			if ( null !== $preset_index ) {
				$presets = $presets[ $preset_index ];
			}

			return $presets;
		}

		public static function init_presets() {

			$additional_presets = array(
				2 => array(
					array(
						'preset' => array( 'flex-1', 'flex-auto' ),
					),
					array(
						'preset' => array( 33, 66 ),
					),
					array(
						'preset' => array( 66, 33 ),
					),
				),
				3 => array(
					array(
						'preset' => array( 'flex-1', 'flex-auto', 'flex-1' ),
					),
					array(
						'preset' => array( 'flex-auto', 'flex-1', 'flex-auto' ),
					),
					array(
						'preset' => array( 25, 25, 50 ),
					),
					array(
						'preset' => array( 50, 25, 25 ),
					),
					array(
						'preset' => array( 25, 50, 25 ),
					),
					array(
						'preset' => array( 16, 66, 16 ),
					),
				),
			);

			foreach ( range( 1, 10 ) as $columns_count ) {
				self::$presets[ $columns_count ] = array(
					array(
						'preset' => array(),
					),
				);

				$preset_unit = floor( 1 / $columns_count * 100 );

				for ( $i = 0; $i < $columns_count; $i++ ) {
					self::$presets[ $columns_count ][0]['preset'][] = $preset_unit;
				}

				if ( ! empty( $additional_presets[ $columns_count ] ) ) {
					self::$presets[ $columns_count ] = array_merge( self::$presets[ $columns_count ], $additional_presets[ $columns_count ] );
				}

				foreach ( self::$presets[ $columns_count ] as $preset_index => & $preset ) {
					$preset['key'] = $columns_count . $preset_index;
				}
			}
		}

		public function get_html_tag() {
			$html_tag = $this->get_settings( 'html_tag' );

			if ( empty( $html_tag ) ) {
				$html_tag = 'section';
			}

			return Elementor\Utils::validate_html_tag( $html_tag );
		}

		protected function print_shape_divider( $side ) {
			$settings         = $this->get_active_settings();
			$base_setting_key = "shape_divider_$side";
			$negative         = ! empty( $settings[ $base_setting_key . '_negative' ] );
			$divider_key      = $settings[ $base_setting_key ];

			if ( 'custom' != $divider_key ) {
				$shape_path = Elementor\Shapes::get_shape_path( $divider_key, $negative );

				if ( 'alpha-' == substr( $divider_key, 0, strlen( 'alpha-' ) ) ) {
					$shape_path = ALPHA_CORE_PATH . '/assets/images/builders/elementor/shapes/' . str_replace( 'alpha-', '', $divider_key ) . ( $negative ? '-negative' : '' ) . '.svg';
				}

				if ( ! is_file( $shape_path ) || ! is_readable( $shape_path ) ) {
					return;
				}
			}
			?>
			<div class="elementor-shape elementor-shape-<?php echo esc_attr( $side ); ?>" data-negative="<?php echo var_export( $negative ); ?>">
				<?php
				if ( 'custom' != $divider_key ) {
					// PHPCS - The file content is being read from a strict file path structure.
					echo file_get_contents( $shape_path ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					if ( isset( $settings[ "shape_divider_{$side}_custom" ] ) && isset( $settings[ "shape_divider_{$side}_custom" ]['value'] ) ) {
						\ELEMENTOR\Icons_Manager::render_icon( $settings[ "shape_divider_{$side}_custom" ] );
					}
				}
				?>
			</div>
			<?php
		}

		protected function register_controls() {
			parent::register_controls();

			$this->update_responsive_control(
				'margin',
				array(
					'allowed_dimensions' => 'all',
					'placeholder'        => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'selectors'          => array(
						'{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->update_responsive_control(
				'gap_columns_custom',
				array(
					'selectors' => array(
						'{{WRAPPER}}>.elementor-container' => '--alpha-el-section-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->start_controls_section(
				'section_general',
				array(
					'label' => alpha_elementor_panel_heading( esc_html__( 'Settings', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_LAYOUT,
				)
			);

				$this->add_control(
					'section_content_type',
					array(
						'label'     => esc_html__( 'Wrap with Container-Fluid', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'condition' => array(
							'layout' => 'boxed',
						),
					)
				);

				$this->add_control(
					'section_content_sticky',
					array(
						'label' => esc_html__( 'Sticky Content', 'alpha-core' ),
						'type'  => Controls_Manager::SWITCHER,
					)
				);

				$this->add_control(
					'section_content_sticky_auto',
					array(
						'label'     => esc_html__( 'Smart Sticky', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'condition' => array(
							'section_content_sticky' => 'yes',
						),
					)
				);

				$this->add_responsive_control(
					'section_sticky_padding',
					array(
						'label'      => esc_html__( 'Sticky Padding', 'alpha-core' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array(
							'px',
							'%',
						),
						'selectors'  => array(
							'{{WRAPPER}}.fixed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'section_content_sticky' => 'yes',
						),
					)
				);

				$this->add_control(
					'section_sticky_bg',
					array(
						'label'     => esc_html__( 'Sticky Background', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}}.fixed' => 'background-color: {{VALUE}}',
						),
						'condition' => array(
							'section_content_sticky' => 'yes',
						),
					)
				);

				$this->add_control(
					'section_sticky_blur',
					array(
						'label'       => esc_html__( 'Blur Effect', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'description' => esc_html__( 'Enable to make the contents blurry under sticky content. The background of sticky content should be alpha color value.', 'alpha-core' ),
						'selectors'   => array(
							'{{WRAPPER}}.fixed' => 'backdrop-filter: blur(30px)',
						),
						'separator'   => 'after',
						'condition'   => array(
							'section_content_sticky' => 'yes',
						),
					)
				);

			do_action( 'alpha_elementor_section_before_addon_controls', $this );

			/**
			 * Filters section element which add on by theme.
			 *
			 * @since 1.0
			 */
			$section_addons = apply_filters( 'alpha_elementor_section_addons', array() );

			if ( ! empty( $section_addons ) ) {
				$section_addons = array_merge( array( '' => esc_html__( 'Default', 'alpha-core' ) ), $section_addons );

				$this->add_control(
					'use_as',
					array(
						'type'    => Controls_Manager::SELECT,
						'label'   => esc_html__( 'Use Section For', 'alpha-core' ),
						'default' => '',
						'options' => $section_addons,
						// 'options' => array(
						// 	''          => esc_html__( 'Default', 'alpha-core' ),
						// 	'creative'  => esc_html__( 'Creative Grid', 'alpha-core' ),
						// ),
					)
				);
			}

			$this->end_controls_section();
			/**
			 * Fires after add controls to section element.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_section_addon_controls', $this, 'use_as' );
			alpha_elementor_addon_controls( $this, 'section' );

			do_action( 'alpha_elementor_section_after_addon_controls', $this );
		}

		protected function content_template() {
			/**
			 * Fires after print section output in elementor column content template function.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_before_elementor_section_content_template', $this );
			?>
			<#			
			let content_width = '';
			let extra_class = '';
			let extra_attrs = '';
			let wrapper_class = '';
			let wrapper_attrs = '';

			if ( 'yes' == settings.section_content_type && settings.layout == 'boxed' ) {
				content_width = ' container-fluid';
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

			<div class="elementor-background-overlay"></div>
			<div class="elementor-shape elementor-shape-top"></div>
			<div class="elementor-shape elementor-shape-bottom"></div>
			
			<#
				if('yes' == settings.mask_reveal) {
					#>
					<div data-mask-reveal="true"></div>
					<#
				}
			#>

			<# let addon_html = ''; #>

			<?php
			/**
			 * Fires after print section output in elementor column content template function.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_section_addon_content_template', $this );
			?>

			<# if ( addon_html ) { #>
				{{{ addon_html }}}
			<# } else { #>

				<div class="elementor-container{{ content_width }} elementor-column-gap-{{ settings.gap }}">

				<?php if ( $this->legacy_mode ) { ?>
					<div class="elementor-row"></div>
				<?php } ?>

				</div>

			<# } #>
			<?php
		}

		public function before_render() {
			$settings = $this->get_settings_for_display();
			global $alpha_section;
			?>
			<<?php echo esc_html( $this->get_html_tag() ); ?> <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
				<?php
				if ( 'video' == $settings['background_background'] ) :
					if ( $settings['background_video_link'] ) :
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
								if ( 'yes' !== $settings['background_play_once'] ) :
									$video_tag_attributes .= ' loop';
								endif;
								?>
								<video class="elementor-background-video-hosted elementor-html5-video" <?php echo $video_tag_attributes; ?>></video>
							<?php endif; ?>
						</div>
						<?php
					endif;
				endif;

				$overlay_background = $settings['background_overlay_background'] ?? '';
				$overlay_hover_background = $settings['background_overlay_hover_background'] ?? '';

				$has_background_overlay = in_array( $overlay_background, [ 'classic', 'gradient' ], true ) ||
										in_array( $overlay_hover_background, [ 'classic', 'gradient' ], true );

				if ( $has_background_overlay ) :
					?>
					<div class="elementor-background-overlay"></div>
					<?php
				endif;

				if ( $settings['shape_divider_top'] ) {
					$this->print_shape_divider( 'top' );
				}

				if ( $settings['shape_divider_bottom'] ) {
					$this->print_shape_divider( 'bottom' );
				}

				/**
				 * Fires before rendering section addon html.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_before_elementor_section_render', $this, $settings );

				ob_start();
				/**
				 * Fires before rendering section html.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_section_render', $this, $settings );
				$addon_html = ob_get_clean();

				if ( ! $addon_html ) {
					/**
					 * Fires after rendering effect addons such as duplex and ribbon.
					 *
					 * @since 1.0
					 */
					do_action( 'alpha_elementor_addon_render', $settings, $this->get_ID() );

					if ( $this->legacy_mode ) {
						?>
						<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-<?php echo esc_attr( $settings['gap'] ); ?>">
					<?php } else { ?>
						<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-<?php echo esc_attr( $settings['gap'] ); ?>">
					<?php } ?>

					<?php if ( $this->legacy_mode ) { ?>
						<div class="elementor-row">
						<?php
					}
				} else {
					echo alpha_escaped( $addon_html );
				}

				/*
				// Additional Settings
				$extra_class  = '';
				$extra_attrs  = '';
				if ( 'creative' == $settings['use_as'] ) { // if using as creative grid
				} elseif ( 'slider' == $settings['use_as'] ) {
				} elseif ( 'banner' == $settings['use_as'] ) { // if using as banner

				} elseif ( 'tab' == $settings['use_as'] ) { // if using as tab
				} elseif ( 'accordion' == $settings['use_as'] ) { // if using as accordion
				}
				?>
				<?php if ( $this->legacy_mode ) { ?>
					<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-<?php echo esc_attr( $settings['gap'] ) . ( ( 'slider' == $settings['use_as'] && 'thumb' == $settings['dots_type'] ) ? ' flex-wrap' : '' ); ?>">
				<?php } else { ?>
					<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-<?php echo esc_attr( $settings['gap'] ) . esc_attr( $extra_class ); ?>" <?php echo alpha_strip_script_tags( $extra_attrs ); ?>>
				<?php }
					<?php if ( $this->legacy_mode ) { ?>
						<div class="elementor-row<?php echo esc_attr( $extra_class ); ?>"<?php echo alpha_strip_script_tags( $extra_attrs ); ?>>
						<?php
					} elseif ( 'slider' == $settings['use_as'] ) {
					}


					*/
		}

		public function after_render() {
			$settings = $this->get_settings_for_display();

			ob_start();
			/**
			 * Fires after rendering column html.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_section_after_render', $this, $settings );
			$addon_html = ob_get_clean();

			if ( ! $addon_html ) {
				if ( true == $this->legacy_mode ) :
					?>
					</div>
				<?php endif; ?>
				</div>
				</<?php echo esc_html( $this->get_html_tag() ); ?>>
				<?php
			} else {
				echo alpha_escaped( $addon_html );
			}

			/*
			?>

			<?php if ( true == $this->legacy_mode ) { ?>
				</div>
				<?php
			}
			if ( 'slider' != $settings['use_as'] || 'thumb' != $settings['dots_type'] || $this->legacy_mode ) {
				?>
				</div>
				<?php
			}
			if ( ! $this->legacy_mode && 'slider' == $settings['use_as'] ) {
			}
			if ( 'slider' == $settings['use_as'] && 'yes' == $settings['show_dots'] && 'thumb' == $settings['dots_type'] ) {
			}

			if ( true == $this->legacy_mode ) :
				?>
				</div>
				<?php
			endif;
			?>
			</<?php echo esc_html( $this->get_html_tag() ); ?>>
			<?php
				*/

		}

		public function get_embed_params() {
			$settings = $this->get_settings_for_display();

			$params = array();

			if ( $settings['video_autoplay'] && ! $this->has_image_overlay() ) {
				$params['autoplay'] = '1';
			}

			$params_dictionary = array();

			if ( 'youtube' == $settings['video_type'] ) {
				$params_dictionary = array(
					'video_loop',
					'video_controls',
					'video_mute',
					'rel',
					'modestbranding',
				);

				if ( $settings['video_loop'] ) {
					$video_properties = Embed::get_video_properties( $settings['youtube_url'] );

					$params['playlist'] = $video_properties['video_id'];
				}

				$params['wmode'] = 'opaque';
			} elseif ( 'vimeo' == $settings['video_type'] ) {
				$params_dictionary = array(
					'video_loop',
					'video_mute'     => 'muted',
					'vimeo_title'    => 'title',
					'vimeo_portrait' => 'portrait',
					'vimeo_byline'   => 'byline',
				);

				$params['color'] = str_replace( '#', '', $settings['color'] );

				$params['autopause'] = '0';
			} elseif ( 'dailymotion' == $settings['video_type'] ) {
				$params_dictionary = array(
					'video_controls',
					'video_mute',
					'showinfo' => 'ui-start-screen-info',
					'logo'     => 'ui-logo',
				);

				$params['ui-highlight'] = str_replace( '#', '', $settings['color'] );

				$params['start'] = $settings['start'];

				$params['endscreen-enable'] = '0';
			}

			foreach ( $params_dictionary as $key => $param_name ) {
				$setting_name = $param_name;

				if ( is_string( $key ) ) {
					$setting_name = $key;
				}

				$setting_value = $settings[ $setting_name ] ? '1' : '0';

				$params[ $param_name ] = $setting_value;
			}

			return $params;
		}

		/**
		 * Whether the video has an overlay image or not.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function has_image_overlay() {
			$settings = $this->get_settings_for_display();

			return ! empty( $settings['banner_background_image']['url'] ) && 'yes' == $settings['show_image_overlay'];
		}

		/**
		 * @since 1.0
		 * @access public
		 */
		public function get_embed_options() {
			$settings = $this->get_settings_for_display();

			$embed_options = array();

			if ( 'youtube' == $settings['video_type'] ) {
				$embed_options['privacy'] = $settings['yt_privacy'];
			} elseif ( 'vimeo' == $settings['video_type'] ) {
				$embed_options['start'] = $settings['start'];
			}

			$embed_options['lazy_load'] = ! empty( $settings['lazy_load'] );

			return $embed_options;
		}

		/**
		 * @since 1.0
		 * @access public
		 */
		public function get_hosted_params() {
			$settings = $this->get_settings_for_display();

			$video_params = array();

			foreach ( array( 'autoplay', 'loop', 'controls' ) as $option_name ) {
				if ( $settings[ 'video_' . $option_name ] ) {
					$video_params[ $option_name ] = '';
				}
			}

			if ( $settings['video_mute'] ) {
				$video_params['muted'] = 'muted';
			}

			return $video_params;
		}

		/**
		 * Returns video url
		 *
		 * @since 1.0
		 * @access public
		 */
		public function get_hosted_video_url() {
			$settings = $this->get_settings_for_display();

			if ( ! empty( $settings['insert_url'] ) ) {
				$video_url = $settings['external_url']['url'];
			} else {
				$video_url = $settings['hosted_url']['url'];
			}

			if ( empty( $video_url ) ) {
				return '';
			}

			return $video_url;
		}

		/**
		 * @since 1.0
		 * @access public
		 */
		public function render_hosted_video() {
			$video_url = $this->get_hosted_video_url();
			if ( empty( $video_url ) ) {
				return;
			}

			$video_params = $this->get_hosted_params();
			?>
			<video class="elementor-video" src="<?php echo esc_url( $video_url ); ?>" <?php echo Utils::render_html_attributes( $video_params ); ?>></video>
			<?php
		}
	}
}

if ( ! function_exists( 'alpha_section_render_attributes' ) ) {
	/**
	 * Add render attributes for sections.
	 *
	 * @since 1.0
	 */
	function alpha_section_render_attributes( $self ) {
		$settings = $self->get_settings_for_display();
		$options  = array( 'class' => '' );

		if ( 'banner' != $settings['use_as'] && $settings['background_image'] && $settings['background_image']['url'] && function_exists( 'alpha_get_option' ) && alpha_get_option( 'lazyload' ) ) { // Lazyload background image
			if ( ! is_admin() && ! is_customize_preview() && ! alpha_doing_ajax() && 'banner' != $settings['use_as'] ) {
				if ( ! $settings['background_color'] ) {
					$options['style'] = 'background-color:' . alpha_get_option( 'lazyload_bg' ) . ';';
				}
				$options['data-lazy'] = esc_url( $settings['background_image']['url'] );
			}
		}

		/**
		 * Filters render attribute for add on section.
		 *
		 * @since 1.0
		 */
		$options = apply_filters( 'alpha_elementor_section_addon_render_attributes', $options, $self, $settings );

		if ( isset( $settings['section_content_sticky'] ) && $settings['section_content_sticky'] ) {
			$options['class'] .= ' sticky-content fix-top';
		}
		if ( isset( $settings['mask_reveal'] ) && $settings['mask_reveal'] ) {
			$options['class'] .= ' alpha-entrance-reveal';
		}
		if ( isset( $settings['section_content_sticky_auto'] ) && $settings['section_content_sticky_auto'] ) {
			$options['data-sticky-options'] = '{\'scrollMode\': true}';
		}
		$self->add_render_attribute(
			array(
				'_wrapper' => alpha_get_elementor_addon_options( $settings, $options ),
			)
		);
	}
}
