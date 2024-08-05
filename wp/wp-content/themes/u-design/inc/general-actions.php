<?php
/**
 * Theme Actions & Filters
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

add_action( 'admin_head', 'alpha_print_favicon' );

// Display Page Transition Effect
add_action( 'alpha_page_transition', 'alpha_page_transition' );

// The main tag's class
add_filter( 'alpha_main_class', 'alpha_add_main_class' );

// Nothing found result
add_action( 'alpha_template_nothing_found', 'alpha_template_nothing_found', 10, 2 );

// Google fonts
add_filter( 'alpha_google_font_options', 'alpha_google_font_options' );

// Posts
add_filter( 'alpha_post_types', 'alpha_theme_post_types', 10, 2 );
add_filter( 'navigation_markup_template', 'alpha_pager_posts' );

// Mobile sticky bar
add_action( 'alpha_after_page_wrapper', 'alpha_print_mobile_bar' );

// Single Product Layouts
add_filter( 'alpha_sp_types', 'alpha_single_product_types_extend', 10, 2 );

// Dark Skin
add_filter( 'body_class', 'alpha_add_dark_body_class' );

// Default Post Options
add_filter( 'alpha_post_loop_default_args', 'alpha_post_loop_default' );

// Optimize stylesheet compatibility
add_filter( 'alpha_replace_merged_css', 'alpha_customize_merged_css', 10, 2 );

// Change changelog url in theme updates
add_filter( 'alpha_importer_api_urls', 'alpha_importer_api_urls' );

if ( ! function_exists( 'alpha_print_favicon' ) ) {
	function alpha_print_favicon() {
		$favicon = alpha_get_option( 'site_icon' );
		if ( ! empty( $favicon['url'] ) ) {
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon['url'] ) . '" type="image/x-icon" />';
		}
	}
}

if ( ! function_exists( 'alpha_page_transition' ) ) {
	function alpha_page_transition() {
		$page_trans = alpha_get_option( 'page_transition' );
		if ( $page_trans ) :
			?>
			<div class="loading-screen" data-effect="<?php echo esc_attr( $page_trans ); ?>">
				<?php
				if ( 'slide' == $page_trans ) {
					echo '<div class="reveal"></div>';
				}
				?>
				<?php alpha_preloader(); ?>
			</div>
			<?php
		else :
			alpha_preloader();
		endif;
	}
}

if ( ! function_exists( 'alpha_add_main_class' ) ) {
	function alpha_add_main_class( $classes ) {
		return $classes;
	}
}

if ( ! function_exists( 'alpha_preloader' ) ) {
	function alpha_preloader() {
		$preloader = alpha_get_option( 'preloader' );
		if ( $preloader ) :
			?>
			<div class="loading-overlay <?php echo esc_attr( $preloader ); ?>">
				<div class="bounce-loader">
					<div class="loader loader-1"></div>
					<div class="loader loader-2"></div>
					<?php if ( 'preloader-3' != $preloader ) : ?>
					<div class="loader loader-3"></div>
					<?php endif; ?>
					<?php if ( 'preloader-4' == $preloader ) : ?>
					<div class="loader loader-4"></div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'alpha_template_nothing_found' ) ) {
	function alpha_template_nothing_found( $no_heading, $no_description ) {

		if ( defined( 'ALPHA_CORE_INC_URI' ) ) {
			wp_enqueue_style( 'alpha-alert', ALPHA_CORE_INC_URI . '/widgets/alert/alert' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		}

		ob_start();
		?>
		<h2 class="entry-title">
			<?php
			if ( empty( $no_heading ) ) {
				esc_html_e( 'Nothing Found', 'alpha' );
			} else {
				echo esc_html( $no_heading );
			}
			?>
		</h2>

		<?php if ( is_search() ) : ?>
			<p class="alert alert-light alert-info alert-outline"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'alpha' ); ?></p>
		<?php else : ?>
			<p class="alert alert-light alert-info alert-outline">
				<?php
				if ( empty( $no_description ) ) {
					esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'alpha' );
				} else {
					echo alpha_strip_script_tags( $no_description );
				}
				?>
			</p>
		<?php endif; ?>
		<?php
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'alpha_google_font_options' ) ) {
	function alpha_google_font_options( $fonts ) {
		return  array( 'typo_default', 'typo_heading', 'typo_custom1', 'typo_custom2', 'typo_custom3' );
	}
}


if ( ! function_exists( 'alpha_theme_post_types' ) ) {
	/**
	 * Add theme's post types
	 *
	 * @since 4.0.0
	 */
	function alpha_theme_post_types( $types, $src ) {
		return 'theme' == $src ? array(
			'default'     => ALPHA_ASSETS . '/images/options/posts/post-1.jpg',
			'bordered'    => ALPHA_ASSETS . '/images/options/posts/post-2.jpg',
			'boxed'       => ALPHA_ASSETS . '/images/options/posts/post-3.jpg',
			'classic'     => ALPHA_ASSETS . '/images/options/posts/post-4.jpg',
			'modern'      => ALPHA_ASSETS . '/images/options/posts/post-5.jpg',
			'list'        => ALPHA_ASSETS . '/images/options/posts/post-6.jpg',
			'mask'        => ALPHA_ASSETS . '/images/options/posts/post-7.jpg',
			'categorized' => ALPHA_ASSETS . '/images/options/posts/post-8.jpg',
			'widget'      => ALPHA_ASSETS . '/images/options/posts/post-9.jpg',
		) : array(
			'default'     => 'assets/images/posts/post-1.jpg',
			'bordered'    => 'assets/images/posts/post-2.jpg',
			'boxed'       => 'assets/images/posts/post-3.jpg',
			'classic'     => 'assets/images/posts/post-4.jpg',
			'modern'      => 'assets/images/posts/post-5.jpg',
			'list'        => 'assets/images/posts/post-6.jpg',
			'mask'        => 'assets/images/posts/post-7.jpg',
			'categorized' => 'assets/images/posts/post-8.jpg',
			'widget'      => 'assets/images/posts/post-9.jpg',
		);
	}
}

if ( ! function_exists( 'alpha_single_product_types_extend' ) ) {
	function alpha_single_product_types_extend( $types, $location ) {
		if ( 'layout' == $location ) {
			return array(
				''           => esc_html__( 'Default', 'alpha' ),
				'horizontal' => esc_html__( 'Horizontal Thumbs', 'alpha' ),
				'vertical'   => esc_html__( 'Vertical Thumbs', 'alpha' ),
				'builder'    => esc_html__( 'Use Builder', 'alpha' ),
			);
		} elseif ( 'theme' == $location ) {
			return array(
				'horizontal' => esc_html__( 'Horizontal Thumbs', 'alpha' ),
				'vertical'   => esc_html__( 'Vertical Thumbs', 'alpha' ),
			);
		} elseif ( 'hooks' == $location ) {
			return array(
				'horizontal' => true,
				'vertical'   => true,
			);
		}
		return array(
			''           => esc_html__( 'Default', 'alpha' ),
			'horizontal' => esc_html__( 'Horizontal Thumbs', 'alpha' ),
			'vertical'   => esc_html__( 'Vertical Thumbs', 'alpha' ),
		);
	}
}

if ( ! function_exists( 'alpha_pager_posts' ) ) {
	function alpha_pager_posts() {

		$post_type = get_post_type();

		$post_type_object = get_post_type_object( $post_type );

		$template = '
		<nav class="navigation %1$s" aria-label="%4$s">
			<h2 class="screen-reader-text">%2$s</h2>
			<div class="nav-links">%3$s</div>' .
			'<a class="post-nav-blog ' . ALPHA_ICON_PREFIX . '-icon-grid" href="' . esc_url( get_post_type_archive_link( $post_type ) ) . '" title="' . $post_type_object->labels->all_items . '"></a>' .
		'</nav>';

		return $template;
	}
}

if ( ! function_exists( 'alpha_add_dark_body_class' ) ) {
	function alpha_add_dark_body_class( $classes ) {
		if ( alpha_get_option( 'dark_skin' ) ) {
			$classes[] = 'alpha-dark';
		}
		return $classes;
	}
}

if ( ! function_exists( 'alpha_post_loop_default' ) ) {
	function alpha_post_loop_default( $args ) {
		return array_merge(
			$args,
			array(
				'type'            => alpha_get_option( $args['cpt'] . '_type' ),
				'overlay'         => alpha_get_option( $args['cpt'] . '_overlay' ),
				'excerpt_type'    => alpha_get_option( 'post' == $args['cpt'] ? 'excerpt_type' : $args['cpt'] . '_excerpt_type' ),
				'excerpt_length'  => alpha_get_option( 'post' == $args['cpt'] ? 'excerpt_length' : $args['cpt'] . '_excerpt_length' ),
				'read_more_label' => 'post' != $args['cpt'] ? alpha_get_option( $args['cpt'] . '_read_more_label' ) : esc_html__( 'Read More', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>',
			)
		);
	}
}

if ( ! function_exists( 'alpha_add_body_class' ) ) {
	/**
	 * Add classes to body
	 *
	 * @since 4.0
	 *
	 * @param array[string] $classes
	 *
	 * @return array[string] $classes
	 */
	function alpha_add_body_class( $classes ) {
		global $alpha_layout;

		// Site Layout
		if ( 'full' != alpha_get_option( 'site_type' ) ) { // Boxed or Framed
			$classes[] = 'site-boxed';
		}

		// Page Type
		$layout = alpha_get_page_layout();
		if ( false !== strpos( $layout, 'archive_' . ALPHA_NAME . '_portfolio' ) || false !== strpos( $layout, 'archive_' . ALPHA_NAME . '_member' ) ) {
			$classes[] = 'alpha-archive-post-layout';
		}
		$classes[] = 'alpha-' . str_replace( '_', '-', $layout ) . '-layout';

		$header = false;
		if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && isset( $alpha_layout['header'] ) && ! empty( get_post_meta( $alpha_layout['header'], '_elementor_data', true ) ) ) {
			$header = $alpha_layout['header'];
		}
		if ( ALPHA_NAME . '_template' == get_post_type() && 'header' == get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true ) ) {
			$header = get_the_ID();
		}
		if ( $header ) {
			$settings = get_post_meta( $header, '_elementor_page_settings', true );

			// Header Position
			if ( ! empty( $settings['alpha_header_pos'] ) ) {

				wp_enqueue_script( 'alpha-sidebar' );
				wp_enqueue_script( 'alpha-sticky-lib' );

				$classes[] = 'side-header';

				if ( ! empty( $settings['alpha_side_header_breakpoint'] ) ) {
					$classes[] = 'side-on-' . $settings['alpha_side_header_breakpoint'];
				}
			}

			// Transparent Header
			if ( ! empty( $settings['alpha_sticky_transparent'] ) ) {
				$classes[] = 'sticky-header';
			}
		}

		// Parallax Footer
		$footer = false;
		if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && isset( $alpha_layout['footer'] ) && ! empty( get_post_meta( $alpha_layout['footer'], '_elementor_data', true ) ) ) {
			$footer = $alpha_layout['footer'];
		}
		if ( ALPHA_NAME . '_template' == get_post_type() && 'footer' == get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true ) ) {
			$footer = get_the_ID();
		}
		if ( $footer && ! wp_is_mobile() ) {
			$settings = get_post_meta( $footer, '_elementor_page_settings', true );

			if ( ! empty( $settings['alpha_fixed_footer'] ) ) {
				$classes[] = 'fixed-footer';
			}
		}
		// Disable Mobile Slider
		if ( alpha_get_option( 'mobile_disable_slider' ) ) {
			$classes[] = 'alpha-disable-mobile-slider';
		}

		// Disable Mobile Animation
		if ( alpha_get_option( 'mobile_disable_animation' ) ) {
			$classes[] = 'alpha-disable-mobile-animation';
		}

		if ( is_customize_preview() ) {
			$classes[] = 'alpha-disable-animation';
		}

		// Add single-product-page or shop-page to body class
		if ( alpha_is_product() ) {
			$classes[] = 'single-product-page';
		} elseif ( alpha_is_shop() ) {
			$classes[] = 'product-archive-page';
		}

		// @start feature: fs_plugin_woocommerce
		if ( class_exists( 'WooCommerce' ) && wc_get_page_id( 'compare' ) == get_the_ID() ) {
			$classes[] = 'compare-page';
		}
		// @end feature: fs_plugin_woocommerce

		global $alpha_layout;

		$post_style_type = isset( $alpha_layout['post_style_type'] ) ? $alpha_layout : '';

		// Category Filter
		if ( is_archive() && 'post' == get_post_type() && alpha_get_option( 'posts_filter' ) ) {
			$classes[] = 'breadcrumb-divider-active';
		}

		// Rounded Skin
		if ( alpha_get_option( 'rounded_skin' ) ) {
			$classes[] = 'alpha-rounded-skin';
		}

		// Cursor Type
		if ( alpha_get_option( 'change_cursor_type' ) ) {
			$classes[] = 'custom-cursor-type';

			if ( alpha_get_option( 'cursor_style' ) ) {
				$classes[] = alpha_get_option( 'cursor_style' );
			}
		}

		// Grid Lines
		if ( alpha_get_option( 'bg_grid_line' ) ) {
			$classes[] = 'grid-lines-page';
		}

		// Smart Sticky
		if ( alpha_get_option( 'smart_sticky' ) ) {
			$classes[] = 'smart-sticky';
		}

		if ( is_admin_bar_showing() ) {
			$classes[] = 'alpha-adminbar';
		}
		if ( defined( 'ALPHA_FRAMEWORK_VENDORS' ) ) {
			$classes[] = 'alpha-use-vendor-plugin';
		}
		return $classes;
	}
}

/**
 * alpha_update_elementor_settings
 *
 * update default elementor active kit options
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_update_elementor_settings' ) ) {
	function alpha_update_elementor_settings( $demo = false, $add_kit = false ) {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}

		if ( $add_kit && $demo ) {
			$kits = get_posts(
				array(
					'post_type'      => 'elementor_library',
					'posts_per_page' => 1,
					'orderby'        => 'modified',
					'order'          => 'DESC',
					'meta_query'     => array(
						array(
							'key'   => '_elementor_template_type',
							'value' => 'kit',
						),
						array(
							'key'     => '_alpha_demo',
							'value'   => sanitize_text_field( $demo ),
							'compare' => 'LIKE',
						),
					),
				)
			);
			if ( ! empty( $kits ) && ! is_wp_error( $kits ) && is_array( $kits ) ) {
				update_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, (int) $kits[0]->ID );
				$default_kit = (int) $kits[0]->ID;
				$add_kit     = false;
			}
		}

		if ( $add_kit ) {
			// Create elementor default kit
			$kit = Elementor\Plugin::$instance->kits_manager->get_active_kit();
			if ( ! $kit->get_id() ) {
				$default_kit = Elementor\Plugin::$instance->kits_manager->create_default();
				if ( $default_kit ) {
					update_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, $default_kit );
				}
			}
		}

		if ( ! isset( $default_kit ) ) {
			$default_kit = get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 );
		}

		if ( $default_kit ) {
			$general_settings = get_post_meta( $default_kit, '_elementor_page_settings', true );
			$changed          = false;

			if ( empty( $general_settings ) ) {
				$general_settings = array();
			}

			// container width
			if ( empty( $general_settings['container_width'] ) || ! isset( $general_settings['container_width']['size'] ) || alpha_get_option( 'container' ) != $general_settings['container_width']['size'] ) {
				$general_settings['container_width'] = array(
					'size'  => alpha_get_option( 'container' ),
					'unit'  => 'px',
					'sizes' => array(),
				);
				$changed                             = true;
			}

			// space between widgets
			if ( empty( $general_settings['space_between_widgets'] ) || ! isset( $general_settings['space_between_widgets']['size'] ) || 0 != $general_settings['space_between_widgets']['size'] || ! isset( $general_settings['space_between_widgets']['column'] ) || 0 != $general_settings['space_between_widgets']['column'] ) {
				if ( version_compare( ELEMENTOR_VERSION, '3.16.0', '>=' ) ) {
					$general_settings['space_between_widgets'] = array(
						'size'     => 0,
						'unit'     => 'px',
						'column'   => 0,
						'row'      => 0,
						'isLinked' => true,
					);
				} else {
					$general_settings['space_between_widgets'] = array(
						'size'  => 0,
						'unit'  => 'px',
						'sizes' => array(),
					);
				}
				$changed = true;
			}

			// responsive breakpoint
			if ( empty( $general_settings['viewport_tablet'] ) || 991 !== (int) $general_settings['viewport_tablet'] ) {
				$general_settings['viewport_tablet'] = 991;
				$changed                             = true;
			}

			// system colors
			if ( empty( $general_settings['system_colors'] ) || ! isset( $general_settings['system_colors'][0] ) || alpha_get_option( 'primary_color' ) != $general_settings['system_colors'][0]['color'] ) {
				$general_settings['system_colors'][0]['_id']   = 'primary';
				$general_settings['system_colors'][0]['color'] = alpha_get_option( 'primary_color' );
				$general_settings['system_colors'][0]['title'] = esc_html__( 'Primary', 'alpha' );
				$changed                                       = true;
			}
			if ( empty( $general_settings['system_colors'] ) || ! isset( $general_settings['system_colors'][1] ) || alpha_get_option( 'secondary_color' ) != $general_settings['system_colors'][1]['color'] ) {
				$general_settings['system_colors'][1]['_id']   = 'secondary';
				$general_settings['system_colors'][1]['color'] = alpha_get_option( 'secondary_color' );
				$general_settings['system_colors'][1]['title'] = esc_html__( 'Secondary', 'alpha' );
				$changed                                       = true;
			}
			if ( empty( $general_settings['system_colors'] ) || ! isset( $general_settings['system_colors'][2] ) || alpha_get_option( 'typo_default' )['color'] != $general_settings['system_colors'][2]['color'] ) {
				$general_settings['system_colors'][2]['_id']   = 'text';
				$general_settings['system_colors'][2]['color'] = alpha_get_option( 'typo_default' )['color'];
				$general_settings['system_colors'][2]['title'] = esc_html__( 'Text', 'alpha' );
				$changed                                       = true;
			}
			if ( empty( $general_settings['system_colors'] ) || ! isset( $general_settings['system_colors'][3] ) || alpha_get_option( 'success_color' ) != $general_settings['system_colors'][3]['color'] ) {
				$general_settings['system_colors'][3]['_id']   = 'success';
				$general_settings['system_colors'][3]['color'] = alpha_get_option( 'success_color' );
				$general_settings['system_colors'][3]['title'] = esc_html__( 'Success', 'alpha' );
				$changed                                       = true;
			}

			// system fonts
			if ( empty( $general_settings['system_typography'] ) ) {
				$general_settings['system_typography'] = array(
					array(
						'_id'                    => 'primary',
						'title'                  => esc_html__( 'Primary', 'alpha' ),
						'typography_typography'  => 'custom',
						'typography_font_family' => alpha_get_option( 'typo_default' )['font-family'],
						'typography_font_weight' => 'default',
					),
					array(
						'_id'                    => 'secondary',
						'title'                  => esc_html__( 'Secondary', 'alpha' ),
						'typography_typography'  => 'custom',
						'typography_font_family' => 'default',
						'typography_font_weight' => 'default',
					),
					array(
						'_id'                    => 'text',
						'title'                  => esc_html__( 'Text', 'alpha' ),
						'typography_typography'  => 'custom',
						'typography_font_family' => 'default',
						'typography_font_weight' => 'default',
					),
					array(
						'_id'                    => 'accent',
						'title'                  => esc_html__( 'Accent', 'alpha' ),
						'typography_typography'  => 'custom',
						'typography_font_family' => 'default',
						'typography_font_weight' => 'default',
					),
				);

				$changed = true;
			}

			if ( $changed ) {
				update_post_meta( $default_kit, '_elementor_page_settings', $general_settings );

				try {
					\Elementor\Plugin::$instance->files_manager->clear_cache();
				} catch ( Exception $e ) {
				}
			}
		}

		if ( false === get_option( 'elementor_disable_color_schemes', false ) ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
		}
		if ( false === get_option( 'elementor_disable_typography_schemes', false ) ) {
			update_option( 'elementor_disable_typography_schemes', 'yes' );
		}
		if ( false === get_option( 'elementor_experiment-e_dom_optimization', false ) ) {
			update_option( 'elementor_experiment-e_dom_optimization', 'active' );
		}
	}
}

/**
 * alpha_customize_merged_css
 *
 * update merged css for url styles
 *
 * @since 4.7.0
 */
if ( ! function_exists( 'alpha_customize_merged_css' ) ) {
	function alpha_customize_merged_css( $contents, $index ) {
		if ( 'alpha-icon-box-css' == $index && defined( 'ALPHA_CORE_FILE' ) ) {
			$contents = str_replace( 'url(../../..', 'url(' . plugins_url( '/', ALPHA_CORE_FILE ), $contents );
		}
		return $contents;
	}
}

/**
 * alpha_importer_api_urls
 *
 * update url of changelog
 *
 * @since 4.7.1
 */
if ( ! function_exists( 'alpha_importer_api_urls' ) ) {
	function alpha_importer_api_urls( $url ) {
		$url['changelog'] = 'https://d-themes.com/wordpress/udesign/documentation/change-log/';
		return $url;
	}
}
