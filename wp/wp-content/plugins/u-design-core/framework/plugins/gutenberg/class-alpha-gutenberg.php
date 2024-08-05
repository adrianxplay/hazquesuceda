<?php
/**
 * Alpha Gutenberg Blocks
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

defined( 'ABSPATH' ) || die;
define( 'ALPHA_CORE_GUTENBERG', ALPHA_CORE_FRAMEWORK_PATH . '/plugins/gutenberg' );

class Alpha_Gutenberg extends Alpha_Base {

	/**
	 * Gutenberg widget names.
	 *
	 * @var array WP Alpha gutenberg widget names.
	 * @since 1.2.0
	 */
	public $gutenberg_blocks = array();

	/**
	 * The Constructor
	 *
	 * @since 1.2.0
	 */
	public function __construct() {

		/**
		 * Gutenberg filter name to add alpha css class to the widgets
		 *
		 * @since 1.2.0
		 */
		$this->gutenberg_blocks = array(
			'heading'   => array(
				'text_source'                 => array(
					'type' => 'string',
				),
				'dynamic_content'             => array(
					'type' => 'object',
				),
				'title'                       => array(
					'type'    => 'string',
					'default' => esc_html__( 'Add Your Heading Text Here', 'alpha-core' ),
				),
				'tag'                         => array(
					'type'    => 'string',
					'default' => 'h2',
				),
				'decoration'                  => array(
					'type'    => 'string',
					'default' => 'simple',
				),
				'title_align'                 => array(
					'type'    => 'string',
					'default' => 'title-left',
				),
				'decoration_spacing_selector' => array(
					'type' => 'string',
				),
				'border_color_selector'       => array(
					'type' => 'string',
				),
				'font_settings'               => array(
					'type' => 'object',
				),
				'style_options'               => array(
					'type' => 'object',
				),
			),
			'button'    => array(
				'label'                      => array(
					'type'    => 'string',
					'default' => esc_html__( 'Click Here', 'alpha-core' ),
				),
				'link'                       => array(
					'type'    => 'string',
					'default' => '',
				),
				'button_expand'              => array(
					'type' => 'boolean',
				),
				'button_align_selector'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'button_type'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'button_skin'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'button_gradient_skin'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'button_text_hover_effect'   => array(
					'type' => 'string',
				),
				'button_bg_hover_effect'     => array(
					'type' => 'string',
				),
				'button_bg_hover_color'      => array(
					'type' => 'string',
				),
				'button_hover_outline_color' => array(
					'type' => 'string',
				),
				'button_size'                => array(
					'type' => 'string',
				),
				'link_hover_type'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'shadow'                     => array(
					'type'    => 'string',
					'default' => '',
				),
				'button_border'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'show_icon'                  => array(
					'type' => 'boolean',
				),
				'icon'                       => array(
					'type'    => 'string',
					'default' => 'fas fa-arrow-right',
				),
				'icon_pos'                   => array(
					'type'    => 'string',
					'default' => 'after',
				),
				'icon_hover_effect'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'icon_hover_effect_infinite' => array(
					'type' => 'boolean',
				),
				'link_break'                 => array(
					'type'    => 'string',
					'default' => 'nowrap',
				),
				'button_typography'          => array(
					'type'    => 'object',
					'default' => '',
				),
				'icon_space_selector'        => array(
					'type' => 'integer',
				),
				'icon_size_selector'         => array(
					'type' => 'string',
				),
				'style_options'              => array(
					'type'    => 'object',
					'default' => '',
				),
				'color_tab'                  => array(
					'type'    => 'string',
					'default' => 'normal',
				),
				'font_settings'              => array(
					'type'    => 'object',
					'default' => '',
				),
			),
			'icon-box'  => array(
				'icon'                          => array(
					'type'    => 'string',
					'default' => 'fas fa-star',
				),
				'icon_view'                     => array(
					'type' => 'string',
				),
				'icon_shape'                    => array(
					'type'    => 'string',
					'default' => 'icon-circle',
				),
				'title'                         => array(
					'type'    => 'string',
					'default' => 'This is the heading',
				),
				'desc'                          => array(
					'type'    => 'string',
					'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'alpha-core' ),
				),
				'link'                          => array(
					'type' => 'string',
				),
				'icon_pos'                      => array(
					'type'    => 'string',
					'default' => '',
				),
				'title_tag'                     => array(
					'type'    => 'string',
					'default' => 'h3',
				),
				'icon_primary_selector'         => array(
					'type' => 'string',
				),
				'icon_primary_hover_selector'   => array(
					'type' => 'string',
				),
				'icon_secondary_selector'       => array(
					'type' => 'string',
				),
				'icon_secondary_hover_selector' => array(
					'type' => 'string',
				),
				'icon_spacing_selector'         => array(
					'type' => 'integer',
				),
				'icon_size_selector'            => array(
					'type' => 'integer',
				),
				'icon_padding_selector'         => array(
					'type' => 'integer',
				),
				'icon_border_width_selector'    => array(
					'type' => 'integer',
				),
				'content_align_selector'        => array(
					'type' => 'string',
				),
				'content_valign_selector'       => array(
					'type' => 'string',
				),
				'title_spacing_selector'        => array(
					'type' => 'integer',
				),
				'style_options'                 => array(
					'type' => 'object',
				),
				'title_font_settings'           => array(
					'type' => 'object',
				),
				'desc_font_settings'            => array(
					'type' => 'object',
				),
			),
			'image'     => array(
				'block_id'                       => array(
					'type' => 'number',
				),
				'image_source'                   => array(
					'type' => 'string',
				),
				'dynamic_content'                => array(
					'type' => 'object',
				),
				'img_source'                     => array(
					'type'    => 'object',
					'default' => '',
				),
				'img_size'                       => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'img_align_selector'             => array(
					'type' => 'string',
				),
				'show_caption_selector'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'custom_caption'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'link'                           => array(
					'type'    => 'string',
					'default' => '',
				),
				'lightbox'                       => array(
					'type'    => 'string',
					'default' => 'yes',
				),
				'link_url'                       => array(
					'type'    => 'string',
					'default' => '',
				),
				'img_style_selector'             => array(
					'type'    => 'object',
					'default' => '',
				),
				'img_filter_selector'            => array(
					'type'    => 'object',
					'default' => '',
				),
				'img_hover_filter_selector'      => array(
					'type'    => 'object',
					'default' => '',
				),
				'caption_style_selector'         => array(
					'type'    => 'object',
					'default' => '',
				),
				'style_options'                  => array(
					'type' => 'object',
				),
				'caption_font_settings_selector' => array(
					'type'    => 'object',
					'default' => '',
				),
			),
			'container' => array(
				'flex_box'         => array(
					'type' => 'boolean',
				),
				'flex_wrap'        => array(
					'type' => 'boolean',
				),
				'horizontal_align' => array(
					'type'    => 'string',
					'default' => 'start',
				),
				'vertical_align'   => array(
					'type'    => 'string',
					'default' => 'start',
				),
				'text_align'       => array(
					'type'    => 'string',
					'default' => 'left',
				),
				'style_options'    => array(
					'type' => 'object',
				),
			),
			'icon'      => array(
				'source'               => array(
					'type' => 'string',
				),
				'dynamic_content'      => array(
					'type' => 'object',
				),
				'icon'                 => array(
					'type'    => 'string',
					'default' => 'fas fa-star',
				),
				'link_source'          => array(
					'type' => 'string',
				),
				'link_dynamic_content' => array(
					'type' => 'object',
				),
				'link'                 => array(
					'type' => 'string',
				),
				'st_fs'                => array(
					'type' => 'integer',
				),
				'st_pd'                => array(
					'type' => 'integer',
				),
				'st_icon_clr'          => array(
					'type' => 'string',
				),
				'st_icon_clr_hover'    => array(
					'type' => 'string',
				),
				'style_options'        => array(
					'type' => 'object',
				),
			),
		);
		$this->gutenberg_blocks = apply_filters( 'alpha_gutenberg_blocks', $this->gutenberg_blocks );

		// Init gutenberg editor
		add_action( 'current_screen', array( $this, 'current_screen' ) );
		$this->add_gutenberg_blocks();
		// Dynamic Tags
		require_once alpha_core_framework_path( ALPHA_CORE_GUTENBERG . '/dynamic_tags/dynamic-tags.php' );
	}

	/**
	 * Init gutenberg editor
	 *
	 * @since 1.2.0
	 */
	public function current_screen() {
		$screen = get_current_screen();
		if ( $screen && 'post' === $screen->base ) {
			if ( $screen->is_block_editor() ) {
				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets_style' ), 999 );
				add_filter( 'block_categories_all', array( $this, 'blocks_categories' ), 10, 1 );
			} else {
				add_action( 'save_post', array( $this, 'save_meta_values' ), 99, 2 );
			}
		}
	}

	/**
	 * Import the block editor style
	 *
	 * @since 1.2.0
	 */
	public function enqueue_block_editor_assets_style() {
		wp_enqueue_style( 'alpha-blocks-style-editor', alpha_core_framework_uri( '/plugins/gutenberg/assets/css/gutenberg-editor' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array( 'alpha-admin' ), ALPHA_CORE_VERSION );
		wp_enqueue_script( 'alpha-blocks', alpha_core_framework_uri( '/plugins/gutenberg/assets/gutenberg.min.js' ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-data', 'alpha-admin' ), ALPHA_CORE_VERSION, true );

		if ( defined( 'ALPHA_VERSION' ) ) {
			alpha_load_google_font();
			Alpha_Layout_Builder::get_instance()->setup_layout();
			ob_start();
			include alpha_core_framework_path( ALPHA_CORE_GUTENBERG . '/gutenberg-variable.php' );
			$output_style = ob_get_clean();

			if ( function_exists( 'alpha_minify_css' ) ) {
				$output_style = alpha_minify_css( $output_style );
			}

			wp_add_inline_style( 'alpha-blocks-style-editor', $output_style );
		}
		$js_alpha_block_vars['googlefonts'] = class_exists( 'Kirki_Fonts' ) ? Kirki_Fonts::get_google_fonts() : array();
		$js_alpha_block_vars['theme_url']   = esc_url( get_parent_theme_file_uri() );
		$js_alpha_block_vars['core_url']    = ALPHA_CORE_FRAMEWORK_URI;
		if ( is_admin() && get_current_screen()->is_block_editor() ) {
			$js_alpha_block_vars['edit_post_id'] = (int) get_the_ID();
		}

		$image_sizes = array();
		foreach ( alpha_get_image_sizes() as $value => $key ) {
			$image_sizes[] = array(
				'label' => str_replace( '&amp;', '&', esc_js( $value ) ),
				'value' => esc_js( $key ),
			);
		}
		$js_alpha_block_vars['image_sizes'] = $image_sizes;

		wp_localize_script(
			'alpha-blocks',
			'alpha_block_vars',
			apply_filters( 'alpha_gutenberg_editor_vars', $js_alpha_block_vars )
		);
	}

	/**
	 * Add the block categories
	 *
	 * @param array $categories Show widget categories
	 * @since 1.2.0
	 */
	public function blocks_categories( $categories ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => ALPHA_NAME,
					'title' => ALPHA_DISPLAY_NAME,
					'icon'  => '',
				),
			)
		);
	}

	/**
	 * Save style options when saving post in gutenberg editor
	 *
	 * @param int $post_id The id of the certain post
	 * @param string $post Certain post
	 * @since 1.2.0
	 */
	public function save_meta_values( $post_id, $post ) {

		if ( ! $post || ! $post->post_content ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// save dynamic styles
		if ( false !== strpos( $post->post_content, '<!-- wp:' . ALPHA_NAME ) ) { // Gutenberg editor

			$blocks = parse_blocks( $post->post_content );

			if ( ! empty( $blocks ) ) {
				$css = $this->include_style( $blocks, '' );
				if ( $css ) {
					update_post_meta( $post_id, ALPHA_NAME . '_blocks_style_options_css', wp_strip_all_tags( $css ) );
				} else {
					delete_post_meta( $post_id, ALPHA_NAME . '_blocks_style_options_css' );
				}
			}
		}
	}

	/**
	 * Generate internal styles
	 *
	 * @param array $blocks Gutenberg Blocks
	 * @since 1.2.0
	 */
	protected function include_style( $blocks, $saved_css = '' ) {
		if ( empty( $blocks ) ) {
			return $saved_css;
		}

		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) && 0 === strpos( $block['blockName'], ALPHA_NAME ) ) {
				$prefix     = ALPHA_NAME . '/' . ALPHA_NAME . '-';
				$block_name = '';
				if ( false !== strpos( $block['blockName'], $prefix ) ) {
					$block_name = substr( $block['blockName'], strpos( $block['blockName'], $prefix ) + strlen( $prefix ) );
				}
				$attrs = array();

				// alpha options
				foreach ( $block['attrs'] as $key => $value ) {
					if ( false !== strpos( $key, 'font_settings' ) || false !== strpos( $key, 'style_options' ) || false !== strpos( $key, '_selector' ) || 0 === strpos( $key, 'st_' ) || ( 'spacing' == $key && ALPHA_NAME . '-tb/' . ALPHA_NAME . '-meta' == $block['blockName'] ) || ( 0 === strpos( $key, 'hover_' ) && ALPHA_NAME . '-tb/' . ALPHA_NAME . '-featured-image' == $block['blockName'] ) ) {
						$attrs[ $key ] = $value;
					}
				}
				if ( ! empty( $block['attrs']['alignment'] ) ) {
					if ( ! isset( $attrs['font_settings'] ) ) {
						$attrs['font_settings'] = array();
					}
					$attrs['font_settings']['alignment'] = $block['attrs']['alignment'];
				}
				if ( ! empty( $attrs ) ) {
					$selector = '.alpha-gb-' . self::get_global_hashcode( $attrs, str_replace( ALPHA_NAME . '/' . ALPHA_NAME . '-', '', $block['blockName'] ) );
					$settings = $attrs;

					$settings['selector'] = $selector;

					if ( ALPHA_NAME . '/' . ALPHA_NAME . '-heading' === $block['blockName'] ) {
						$settings['selector'] .= '.title-wrapper .title';
					} elseif ( ALPHA_NAME . '/' . ALPHA_NAME . '-button' === $block['blockName'] ) {
						$settings['selector'] .= ' .btn';
					}

					// Type Builder Blocks
					if ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-price' == $block['blockName'] ) {
						$settings['selector'] .= ' .price';
					} elseif ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-rating' == $block['blockName'] ) {
						$settings['selector'] .= ' .woocommerce-product-rating';
						if ( isset( $attrs['font_settings'], $attrs['font_settings']['alignment'] ) ) {
							$part_css = '';
							if ( 'center' == $attrs['font_settings']['alignment'] ) {
								$part_css = 'html ' . esc_html( $settings['selector'] ) . '{justify-content:center}';
							} elseif ( 'right' == $attrs['font_settings']['alignment'] ) {
								$part_css = 'html ' . esc_html( $settings['selector'] ) . '{justify-content:flex-end}';
							}
							if ( $part_css && false === strpos( $saved_css, $part_css ) ) {
								$saved_css .= $part_css;
							}

							unset( $attrs['font_settings']['alignment'] );
							if ( empty( $attrs['font_settings'] ) ) {
								unset( $attrs['font_settings'] );
							}
						}
					} elseif ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-stock' == $block['blockName'] ) {
						$settings['selector'] .= ' .stock';
					} elseif ( ALPHA_NAME . '-tb/' . ALPHA_NAME . '-woo-desc' == $block['blockName'] ) {
						$settings['selector'] .= ' p';
					}
					// Style for widgets
					if ( $block_name ) {
						ob_start();
						include alpha_core_framework_path( ALPHA_CORE_GUTENBERG . '/style/style-' . $block_name . '.php' );
						$part_css = ob_get_clean();
						if ( $part_css && false === strpos( $saved_css, $part_css ) ) {
							$saved_css .= $part_css;
						}
					}

					$part_css = apply_filters( 'alpha_gutenberg_block_style', '', $block['blockName'], $block['attrs'], $selector );
					if ( $part_css && false === strpos( $saved_css, $part_css ) ) {
						$saved_css .= $part_css;
					}

					foreach ( $attrs as $key => $value ) {
						if ( false !== strpos( $key, 'font_settings' ) || false !== strpos( $key, 'style_options' ) ) {
							$backup_selector      = $settings['selector'];
							$settings             = $attrs[ $key ];
							$settings['selector'] = $backup_selector;
						} else {
							continue;
						}

						if ( false !== strpos( $key, 'font_settings' ) ) {
							if ( ALPHA_NAME . '/' . ALPHA_NAME . '-icon-box' === $block['blockName'] ) {
								if ( false !== strpos( $key, 'title' ) ) {
									$settings['selector'] = 'html ' . $settings['selector'] . ' .icon-box-title';
								} elseif ( false !== strpos( $key, 'desc' ) ) {
									$settings['selector'] = 'html ' . $settings['selector'] . ' p';
								}
							}
							ob_start();
							include alpha_core_framework_path( ALPHA_CORE_GUTENBERG . '/style/style-font.php' );
							$part_css = ob_get_clean();
							if ( $part_css && false === strpos( $saved_css, $part_css ) ) {
								$saved_css .= $part_css;
							}
						} elseif ( false !== strpos( $key, 'style_options' ) ) {
							ob_start();
							include alpha_core_framework_path( ALPHA_CORE_GUTENBERG . '/style/style-options.php' );
							$part_css = ob_get_clean();
							if ( $part_css && false === strpos( $saved_css, $part_css ) ) {
								$saved_css .= $part_css;
							}
						}
					}
				}
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$saved_css = $this->include_style( $block['innerBlocks'], $saved_css );
			}
		}

		return $saved_css;
	}

	/**
	 * Get the global hashcode for selector
	 *
	 * @param array $attrs Widget values
	 * @param string $tag Tag name
	 * @param array $param Array for selectors
	 * @since 1.2.0
	 */
	public static function get_global_hashcode( $attrs, $tag, $params = array() ) {
		$filtered_attrs = array();
		foreach ( $attrs as $key => $value ) {
			if ( ! empty( $value ) ) {
				$filtered_attrs[ $key ] = $value;
			}
		}
		$result = '';
		if ( ! empty( $filtered_attrs ) ) {
			$hash = $tag . json_encode( $filtered_attrs );

			if ( 0 === strlen( $hash ) ) {
				return '0';
			}
			return hash( 'md5', $hash );
		}
		return '0';
	}

	/**
	 * Register block types to gutenberg blocks.
	 *
	 * @since 1.2.0
	 */
	public function add_gutenberg_blocks() {
		$is_gutenberg = function_exists( 'register_block_type' );
		foreach ( $this->gutenberg_blocks as $block => $attr ) {
			$callback = function( $atts, $content ) use ( $block ) {
				if ( ! empty( $atts ) && ! empty( $block ) ) {

					$atts_for_hash = array();
					foreach ( $atts as $key => $value ) {
						if ( false !== strpos( $key, 'font_settings' ) || false !== strpos( $key, 'style_options' ) || false !== strpos( $key, '_selector' ) || 0 === strpos( $key, 'st_' ) ) {
							$atts_for_hash[ $key ] = $value;
						}
					}
					$selector = 'alpha-gb-' . self::get_global_hashcode( $atts_for_hash, $block );
				}

				if ( ! empty( $atts['style_options']['position'] ) && ! empty( $atts['style_options']['position']['halign'] ) ) {
					$selector .= ' m' . $atts['style_options']['position']['halign'] . '-auto';
				}

				// Responsive classes
				if ( ! empty( $atts['style_options']['hideXl'] ) ) {
					$selector .= ' hide-on-xl';
				}
				if ( ! empty( $atts['style_options']['hideLg'] ) ) {
					$selector .= ' hide-on-lg';
				}
				if ( ! empty( $atts['style_options']['hideMd'] ) ) {
					$selector .= ' hide-on-md';
				}
				if ( ! empty( $atts['style_options']['hideSm'] ) ) {
					$selector .= ' hide-on-sm';
				}

				// Additional classes
				if ( ! empty( $atts['className'] ) ) {
					$selector .= ' ' . $atts['className'];
				}

				if ( ! empty( $selector ) && ( 'heading' === $block || 'icon-box' === $block || 'button' === $block || 'container' === $block || 'image' === $block || 'icon' === $block ) ) {
					$atts['wrap_class'] = isset( $atts['wrap_class'] ) ? $atts['wrap_class'] . ' ' . $selector : $selector;
				}
				ob_start();
				include alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . "/widgets/{$block}/render-{$block}-elementor.php" );
				return ob_get_clean();
			};
			if ( $is_gutenberg ) {
				register_block_type(
					ALPHA_NAME . '/' . ALPHA_NAME . '-' . $block,
					array(
						'attributes'      => isset( $attr ) ? $attr : array(),
						'editor_script'   => 'alpha-blocks',
						'render_callback' => $callback,
					)
				);
			}
		}
	}
}

Alpha_Gutenberg::get_instance();
