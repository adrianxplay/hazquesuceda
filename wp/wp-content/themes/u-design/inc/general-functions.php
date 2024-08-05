<?php
/**
 * Theme Functions
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
if ( ! function_exists( 'alpha_get_overlay_class' ) ) {
	function alpha_get_overlay_class( $overlay ) {
		if ( 'light' == $overlay || 'dark' == $overlay || 'zoom' == $overlay ) {
			return 'overlay-' . $overlay;
		}
		if ( 'zoom_light' == $overlay ) {
			return 'overlay-zoom overlay-light';
		}
		if ( 'zoom_dark' == $overlay ) {
			return 'overlay-zoom overlay-dark';
		}
		return '';
	}
}

if ( ! function_exists( 'alpha_comment_form_args' ) ) {
	function alpha_comment_form_args( $args ) {
		$args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
		$args['title_reply_after']  = '</h3>';
		$args['fields']['author']   = '<div class="col-md-6"><input name="author" type="text" class="form-control" value="" placeholder="' . esc_attr__( 'Your Name', 'alpha' ) . '"> </div>';
		$args['fields']['email']    = '<div class="col-md-6"><input name="email" type="text" class="form-control" value="" placeholder="' . esc_attr__( 'Your Email', 'alpha' ) . '"> </div>';

		$args['comment_field']  = isset( $args['comment_field'] ) ? $args['comment_field'] : '';
		$args['comment_field']  = substr( $args['comment_field'], 0, strpos( $args['comment_field'], '<p class="comment-form-comment">' ) );
		$args['comment_field'] .= '<textarea name="comment" id="comment" class="form-control" rows="6" maxlength="65525" required="required" placeholder="' . esc_attr__( 'Write Your Review Here&hellip;', 'alpha' ) . '"></textarea>';
		$args['submit_button']  = '<button type="submit" class="btn btn-primary btn-submit">' .
			( alpha_is_product() ? esc_html__( 'Submit Review', 'alpha' ) : esc_html__( 'Post Comment', 'alpha' ) . ' <i class=" ' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>' ) . '</button>';

		return $args;
	}
}

if ( ! function_exists( 'alpha_post_comment' ) ) {
	function alpha_post_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

				<?php if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) { ?>
			<div id="comment-<?php comment_ID(); ?>" class="comment comment-container">
				<p><?php esc_html_e( 'Pingback:', 'alpha' ); ?> <span><span><?php comment_author_link( get_comment_ID() ); ?></span></span> <?php edit_comment_link( esc_html__( '(Edit)', 'alpha' ), '<span class="edit-link">', '</span>' ); ?></p>
			</div>
			<?php } else { ?>
			<div class="comment">
				<figure class="comment-avatar">
					<?php echo get_avatar( $comment, 50 ); ?>
				</figure>

				<div class="comment-text">
					<div class="comment-header">
						<div class="comment-meta">
							<?php /* translators: %s represents the date of the comment. */ ?>
							<h5 class="comment-date"><?php printf( esc_html__( '%1$s at %2$s', 'alpha' ), get_comment_date(), get_comment_time() ); ?></h5>
							<h4 class="comment-name"><?php echo get_comment_author_link( get_comment_ID() ); ?></h4>
						</div>
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
								)
							)
						);
						?>
					</div>

					<?php
					if ( '0' == $comment->comment_approved ) {
						echo '<em>' . esc_html__( 'Your comment is awaiting moderation.', 'alpha' ) . '</em>';
						echo '<br />';
					}
					comment_text();
					?>
				</div>
			</div>
					<?php
			}
	}
}

/**
 * Set loop prop for woocommerce
 *
 * @since 1.0
 */
function alpha_wc_set_loop_prop() {
	// Category Props //////////////////////////////////////////////////////////////////////////////
	wc_set_loop_prop( 'category_type', alpha_get_option( 'category_type' ) );
	wc_set_loop_prop( 'overlay', alpha_get_option( 'category_overlay' ) );

	// Product Props ///////////////////////////////////////////////////////////////////////////////
	wc_set_loop_prop( 'product_type', alpha_get_option( 'product_type' ) );

	if ( alpha_is_shop() || alpha_is_product() ) {
		wc_set_loop_prop( 'show_labels', array( 'hot', 'sale', 'new', 'stock' ) );
	}

	global $alpha_layout;
	$info   = alpha_get_option( 'show_info' );
	$info[] = 'countdown';
	wc_set_loop_prop( 'show_info', $info );
}

/**
 * Loadmore html
 *
 * @since 1.0
 */
function alpha_loadmore_html( $query, $loadmore_type, $loadmore_label, $loadmore_btn_style = '', $name_prefix = '' ) {
	if ( 'button' == $loadmore_type ) {
		$class = 'btn btn-load ';

		if ( $loadmore_btn_style ) {
			$class .= function_exists( 'alpha_widget_button_get_class' ) ? implode( ' ', alpha_widget_button_get_class( $loadmore_btn_style, $name_prefix ) ) : '';
		} else {
			$class .= 'btn-primary btn-outline btn-md';
		}

		$label = empty( $loadmore_label ) ? esc_html__( 'Load More', 'alpha' ) : esc_html( $loadmore_label );
		echo '<button class="' . esc_attr( $class ) . '">' . ( $loadmore_btn_style && function_exists( 'alpha_widget_button_get_label' ) ? alpha_widget_button_get_label( $loadmore_btn_style, null, $label, $name_prefix ) : $label ) . '</button>';
	} elseif ( 'page' == $loadmore_type || ! $loadmore_type ) {
		echo alpha_get_pagination( $query, 'pagination-load' );
	}
}

/**
 * Save theme options
 * @since 4.0
 */
function alpha_save_theme_options() {
	ob_start();

	require_once alpha_framework_path( ALPHA_FRAMEWORK_ADMIN . '/customizer/customizer-function.php' );
	require_once alpha_framework_path( ALPHA_FRAMEWORK_ADMIN . '/customizer/dynamic/dynamic_vars.php' );

	global $wp_filesystem;
	// Initialize the WordPress filesystem, no more using file_put_contents function
	if ( empty( $wp_filesystem ) ) {
		require_once( ABSPATH . '/wp-admin/includes/file.php' );
		WP_Filesystem();
	}

	try {
		$target      = wp_upload_dir()['basedir'] . '/' . ALPHA_NAME . '_styles/dynamic_css_vars.css';
		$target_path = dirname( $target );
		if ( ! file_exists( $target_path ) ) {
			wp_mkdir_p( $target_path );
		}

		// check file mode and make it writable.
		if ( is_writable( $target_path ) == false ) {
			@chmod( get_theme_file_path( $target ), 0755 );
		}
		if ( file_exists( $target ) ) {
			if ( is_writable( $target ) == false ) {
				@chmod( $target, 0755 );
			}
			@unlink( $target );
		}

		$wp_filesystem->put_contents( $target, ob_get_clean(), FS_CHMOD_FILE );
	} catch ( Exception $e ) {
		var_dump( $e );
		var_dump( 'error occured while saving dynamic css vars.' );
	}
}

if ( ! function_exists( 'alpha_print_title_bar' ) ) {
	function alpha_print_title_bar() {
		global $alpha_layout;

		if ( is_front_page() ) {
			// Do not show page title bar and breadcrumb in home page.
		} else {
			if ( ! empty( $alpha_layout['ptb'] ) && 'hide' != $alpha_layout['ptb'] ) {
				// Display selected template instead of page title bar.
				$alpha_layout['is_page_header'] = true;
				alpha_print_template( $alpha_layout['ptb'] );
				unset( $alpha_layout['is_page_header'] );

			} elseif ( ( ! empty( $alpha_layout['ptb'] ) && 'hide' == $alpha_layout['ptb'] ) || apply_filters( 'alpha_is_vendor_store', false ) ) {
				// Hide page title bar.

			} elseif ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() ) ) {

				$alpha_layout['show_breadcrumb'] = 'no';
				?>
				<div class="woo-page-header">
					<div class="<?php echo esc_attr( 'full' == $alpha_layout['wrap'] ? 'container' : $alpha_layout['wrap'] ); ?>">
						<ul class="breadcrumb">
							<li class="<?php echo is_cart() ? esc_attr( 'current' ) : ''; ?>">
								<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'Shopping Cart', 'alpha' ); ?></a>
							</li>
							<li class="<?php echo is_checkout() && ! is_order_received_page() ? esc_attr( 'current' ) : ''; ?>">
								<i class="delimiter"></i>
								<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'alpha' ); ?></a>
							</li>
							<li class="<?php echo is_order_received_page() ? esc_attr( 'current' ) : esc_attr( 'disable' ); ?>">
								<i class="delimiter"></i>
								<a href="#"><?php esc_html_e( 'Order Complete', 'alpha' ); ?></a>
							</li>
						</ul>
					</div>
				</div>
							<?php
			} else {
				// Show page header
				Alpha_Layout_Builder::get_instance()->setup_titles();
				$ptb_attr = ' class="page-header';
				if ( alpha_get_option( 'ptb_animation' ) ) {
					$ptb_attr .= ' page-header-animate';
				}

				$is_parallax = alpha_get_option( 'ptb_parallax' ) && ! empty( alpha_get_option( 'ptb_bg_image' ) );

				if ( $is_parallax ) {
					wp_enqueue_script( 'jquery-skrollr' );
					$ptb_attr        .= ' parallax"';
					$parallax_img     = alpha_get_option( 'ptb_bg_image' );
					$parallax_options = array(
						'direction' => 'down',
						'speed'     => 1.5,
					);
					$parallax_options = "data-parallax-options='" . json_encode( $parallax_options ) . "'";
					$ptb_attr        .= ' data-parallax-image="' . $parallax_img . '" ' . $parallax_options;
				} else {
					$ptb_attr .= '"';
				}

				echo '<div' . $ptb_attr . '>';

				if ( ! $is_parallax && alpha_get_option( 'ptb_bg_image' ) ) {
					echo wp_get_attachment_image( attachment_url_to_postid( alpha_get_option( 'ptb_bg_image' ) ), 'full' );
				}

				?>
					<div class="page-title-bar">
						<div class="page-title-wrap">
				<?php if ( $alpha_layout['title'] ) : ?>
								<h2 class="page-title"><?php echo alpha_strip_script_tags( $alpha_layout['title'] ); ?></h2>
							<?php endif; ?>
							<?php if ( 'subtitle' == alpha_get_option( 'ptb_content' ) && $alpha_layout['subtitle'] ) : ?>
								<h3 class="page-subtitle"><?php echo alpha_strip_script_tags( $alpha_layout['subtitle'] ); ?></h3>
							<?php endif; ?>
						</div>
							<?php
							if ( 'breadcrumb' == alpha_get_option( 'ptb_content' ) ) {
								alpha_breadcrumb();
							} elseif ( 'search' == alpha_get_option( 'ptb_content' ) ) {
								alpha_search_form();
							}
							?>
					</div>
							<?php
							global $wp_filesystem;
							if ( empty( $wp_filesystem ) ) {
								require_once ABSPATH . '/wp-admin/includes/file.php';
								WP_Filesystem();
							}
							echo alpha_escaped( $wp_filesystem->get_contents( ALPHA_PATH . '/assets/images/shapes/shape' . alpha_get_option( 'ptb_divider' ) . '.svg' ) );

							?>
				</div>
							<?php
			}

			if ( ( empty( $alpha_layout['show_breadcrumb'] ) || 'no' != $alpha_layout['show_breadcrumb'] ) && 'breadcrumb' != alpha_get_option( 'ptb_content' ) ) {
				echo '<div class="breadcrumb-wrap">';
				alpha_breadcrumb();
				echo '</div>';
			}
		}
	}
}

if ( ! function_exists( 'alpha_set_avatar_size' ) ) {
	function alpha_set_avatar_size( $args ) {
		$args['size']   = 80;
		$args['width']  = 80;
		$args['height'] = 80;
		return $args;
	}
}

if ( ! function_exists( 'alpha_options_array_map_convert' ) ) {
	function alpha_options_array_map_convert( $option ) {
		if ( ! is_array( $option ) ) {
			return $option;
		}
		$ret = array();
		foreach ( $option as $key => $value ) {
			if ( '1' == $value ) {
				$ret[] = $key;
			}
		}
		return $ret;
	}
}

if ( ! function_exists( 'alpha_wc_count_per_page' ) ) {
	function alpha_wc_count_per_page() {
		global $alpha_layout;
		$count_select = apply_filters( 'alpha_products_count_select', alpha_get_option( 'products_count_select' ) );
		$ts           = ! empty( $alpha_layout['top_sidebar'] ) && 'hide' != $alpha_layout['top_sidebar'] && is_active_sidebar( $alpha_layout['top_sidebar'] );
		?>
		<div class="toolbox-item toolbox-show-count select-box">
			<label><?php esc_html_e( 'Show :', 'alpha' ); ?></label>
			<select name="count" class="count form-control">
				<?php
				if ( ! empty( $count_select ) ) {
					$count_select = explode( ',', str_replace( ' ', '', $count_select ) );
				} else {
					$count_select = array( '9', '_12', '24', '36' );
				}

				$current = alpha_loop_shop_per_page( $count_select );

				foreach ( $count_select as $count ) {
					$num = (int) str_replace( '_', '', $count );
					echo '<option value="' . $num . '" ' . selected( $num == $current, true, false ) . '>' . $num . '</option>';
				}
				?>
			</select>
			<?php
			$except = array( 'count' );
			// Keep query string vars intact
			foreach ( $_GET as $key => $val ) {
				if ( in_array( $key, $except ) ) {
					continue;
				}

				if ( is_array( $val ) ) {
					foreach ( $val as $inner_val ) {
						echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $inner_val ) . '" />';
					}
				} else {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
				}
			}
			?>
		</div>
		<?php
	}
}

/**
 * Get slider status class from settings array
 *
 * @since 4.0
 *
 * @param array $settings Slider settings array from elementor widget.
 *
 * @return string slider class
 */
if ( ! function_exists( 'alpha_get_slider_status_class' ) ) {
	function alpha_get_slider_status_class( $settings = array() ) {

		$class = '';
		// Nav & Dots
		if ( isset( $settings['nav_type'] ) && 'full' == $settings['nav_type'] ) {
			$class .= ' slider-nav-full';
		} else {
			if ( isset( $settings['nav_type'] ) && 'circle' == $settings['nav_type'] ) {
				$class .= ' slider-nav-circle';
			}
			if ( isset( $settings['nav_pos'] ) && 'top' == $settings['nav_pos'] ) {
				$class .= ' slider-nav-top';
			} elseif ( isset( $settings['nav_pos'] ) && 'bottom' == $settings['nav_pos'] ) {
				$class .= ' slider-nav-bottom';
			} elseif ( isset( $settings['nav_pos'] ) && 'inner' != $settings['nav_pos'] ) {
				$class .= ' slider-nav-outer';
			}
		}
		if ( isset( $settings['nav_hide'] ) && 'yes' == $settings['nav_hide'] ) {
			$class .= ' slider-nav-fade';
		}
		if ( isset( $settings['dots_type'] ) && $settings['dots_type'] ) {
			$class .= ' slider-dots-' . $settings['dots_type'];
		}
		if ( isset( $settings['dots_skin'] ) && $settings['dots_skin'] ) {
			$class .= ' slider-dots-' . $settings['dots_skin'];
		} else {
			$class .= ' slider-dots-default';
		}
		if ( isset( $settings['dots_pos'] ) && 'inner' == $settings['dots_pos'] ) {
			$class .= ' slider-dots-inner';
		}
		if ( isset( $settings['dots_pos'] ) && 'outer' == $settings['dots_pos'] ) {
			$class .= ' slider-dots-outer';
		}
		if ( isset( $settings['fullheight'] ) && 'yes' == $settings['fullheight'] ) {
			$class .= ' slider-full-height';
		}
		if ( isset( $settings['box_shadow_slider'] ) && 'yes' == $settings['box_shadow_slider'] ) {
			$class .= ' slider-shadow';
		}
		if ( isset( $settings['scale_drag'] ) && 'yes' == $settings['scale_drag'] ) {
			$class .= ' slider-scale-shrink';
		}

		if ( isset( $settings['focus_on_active'] ) && 'yes' == $settings['focus_on_active'] ) {
			if ( 'scale' == $settings['focus_effect'] ) {
				$class .= ' slider-zoom-in-active-slide';
			} elseif ( 'opacity' == $settings['focus_effect'] ) {
				$class .= ' slider-active-slide-opacity';
			} elseif ( 'scale_opacity' == $settings['focus_effect'] ) {
				$class .= ' slider-zoom-in-active-slide slider-active-slide-opacity';
			}
		}

		if ( isset( $settings['slider_vertical_align'] ) && ( 'top' == $settings['slider_vertical_align'] ||
		'middle' == $settings['slider_vertical_align'] ||
		'bottom' == $settings['slider_vertical_align'] ||
		'same-height' == $settings['slider_vertical_align'] ) ) {

			$class .= ' slider-' . $settings['slider_vertical_align'];
		}

		return $class;
	}
}

if ( ! function_exists( 'alpha_get_slider_attrs' ) ) {

	/**
	 * Get slider data attribute from settings array
	 *
	 * @since 1.0
	 *
	 * @param array $settings Slider settings array from elementor widget.
	 * @param array $col_cnt  Columns count
	 * @param string $id      Hash string for element
	 *
	 * @return string slider data attribute
	 */
	function alpha_get_slider_attrs( $settings, $col_cnt, $id = '' ) {

		$max_breakpoints = alpha_get_breakpoints();

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$kit = get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 );
			if ( $kit ) {
				$site_settings = get_post_meta( get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 ), '_elementor_page_settings', true );
			}
		}

		$extra_options = array();

		if ( ! empty( $settings['slide_effect'] ) ) {
			$extra_options['effect'] = $settings['slide_effect'];
		}

		$extra_options['spaceBetween'] = ( isset( $settings['col_sp'] ) && '' !== $settings['col_sp'] ) ? alpha_get_grid_space( $settings['col_sp'] ) : ( ( isset( $settings['col_sp_custom']['size'] ) && '' !== $settings['col_sp_custom']['size'] ) ? $settings['col_sp_custom']['size'] : 20 );

		if ( isset( $settings['col_sp'] ) && '' !== $settings['col_sp'] ) {
			$extra_options['spaceBetween'] = alpha_get_grid_space( $settings['col_sp'] );
		} elseif ( isset( $settings['col_sp_custom']['size'] ) && '' !== $settings['col_sp_custom']['size'] ) {
			$extra_options['spaceBetween'] = $settings['col_sp_custom']['size'];
		} elseif ( isset( $settings['col_sp_custom_laptop']['size'] ) && '' !== $settings['col_sp_custom_laptop']['size'] ) {
			$extra_options['spaceBetween'] = $settings['col_sp_custom_laptop']['size'];
		} elseif ( isset( $settings['col_sp_custom_tablet_extra']['size'] ) && '' !== $settings['col_sp_custom_tablet_extra']['size'] ) {
			$extra_options['spaceBetween'] = $settings['col_sp_custom_tablet_extra']['size'];
		} elseif ( isset( $site_settings['gutter_space']['size'] ) && '' !== $site_settings['gutter_space']['size'] ) {
			$extra_options['spaceBetween'] = $site_settings['gutter_space']['size'];
		} elseif ( isset( $site_settings['gutter_space_laptop']['size'] ) && '' !== $site_settings['gutter_space_laptop']['size'] ) {
			$extra_options['spaceBetween'] = $site_settings['gutter_space_laptop']['size'];
		} elseif ( isset( $site_settings['gutter_space_tablet_extra']['size'] ) && '' !== $site_settings['gutter_space_tablet_extra']['size'] ) {
			$extra_options['spaceBetween'] = $site_settings['gutter_space_tablet_extra']['size'];
		} else {
			$extra_options['spaceBetween'] = 30;
		}

		if ( isset( $settings['centered'] ) && 'yes' == $settings['centered'] ) { // default is false
			$extra_options['centeredSlides'] = true;
		}

		if ( isset( $settings['loop'] ) && 'yes' == $settings['loop'] ) { // default is false
			$extra_options['loop'] = true;
		}

		// Auto play
		if ( isset( $settings['autoplay'] ) && 'yes' == $settings['autoplay'] ) { // default is false
			if ( isset( $settings['autoplay_timeout'] ) ) { // default is 5000
				$extra_options['autoplay'] = array(
					'delay'                => (int) $settings['autoplay_timeout'],
					'disableOnInteraction' => false,
				);
			}
		}

		if ( ! empty( $settings['show_dots'] ) && isset( $settings['enable_thumb'] ) && 'yes' == $settings['enable_thumb'] && $id ) {
			$extra_options['dotsContainer'] = '.slider-thumb-dots-' . $id;
		}
		if ( ! empty( $settings['show_nav'] ) ) {
			$extra_options['navigation'] = true;
		}
		if ( ! empty( $settings['show_dots'] ) ) {
			$extra_options['pagination'] = true;
		}
		if ( isset( $settings['autoheight'] ) && 'yes' == $settings['autoheight'] ) {
			$extra_options['autoHeight'] = true;
		}

		// Disable Touch Move
		if ( isset( $settings['disable_mouse_drag'] ) && 'yes' == $settings['disable_mouse_drag'] ) {
			$extra_options['allowTouchMove'] = false;
		}

		// Effect
		if ( isset( $settings['effect'] ) ) {
			$extra_options['effect'] = $settings['effect'];
		}
		if ( ! empty( $settings['scale_drag'] ) ) {
			$extra_options['speed'] = 800;
		}
		if ( ! empty( $settings['speed'] ) ) {
			$extra_options['speed'] = $settings['speed'];
		}

		$responsive = array();
		$w          = array(
			'min' => 'mobile',
			'sm'  => 'mobile_extra',
			'md'  => 'tablet',
			'lg'  => 'tablet_extra',
			'xl'  => 'laptop',
			'xlg' => '',
			'xxl' => 'widescreen',
		);

		$col_cnt = function_exists( 'alpha_get_responsive_cols' ) ? alpha_get_responsive_cols( $col_cnt ) : $col_cnt;

		if ( empty( $settings['col_sp'] ) ) {
			$breakpoints_config = array(
				'mobile'       => array(
					'value' => 767,
				),
				'mobile_extra' => array(
					'value' => 880,
				),
				'tablet'       => array(
					'value' => 991,
				),
				'tablet_extra' => array(
					'value' => 1199,
				),
				'laptop'       => array(
					'value' => 1439,
				),
				'widescreen'   => array(
					'value' => 2399,
				),
			);
			if ( class_exists( '\Elementor\Core\Breakpoints\Manager' ) ) {
				$breakpoints        = new Breakpoints_Manager();
				$breakpoints_value  = $breakpoints->get_breakpoints_config();
				$breakpoints_config = array_merge( $breakpoints_config, $breakpoints_value );
			}
			$max_breakpoints_fw = array_merge(
				$max_breakpoints,
				array(
					'min' => 0,
					'sm'  => $breakpoints_config['mobile']['value'] + 1,
					'md'  => $breakpoints_config['mobile_extra']['value'] + 1,
					'lg'  => $breakpoints_config['tablet']['value'] + 1,
					'xl'  => $breakpoints_config['tablet_extra']['value'] + 1,
					'xlg' => $breakpoints_config['laptop']['value'] + 1,
					'xxl' => $breakpoints_config['widescreen']['value'] + 1,
				)
			);
		}

		$parent_sp_custom = $extra_options['spaceBetween'];
		$parent_sp_global = $extra_options['spaceBetween'];
		foreach ( array_reverse( $w ) as $key => $device ) {
			if ( $device ) {
				$device = '_' . $device;
			}
			if ( ! empty( $col_cnt[ $key ] ) ) {
				$responsive[ $max_breakpoints[ $key ] ] = array(
					'slidesPerView' => $col_cnt[ $key ],
				);
			}
			if ( empty( $settings['col_sp'] ) ) {
				if ( isset( $settings[ 'col_sp_custom' . $device ]['size'] ) && '' !== $settings[ 'col_sp_custom' . $device ]['size'] ) {
					if ( ! isset( $responsive[ $max_breakpoints_fw[ $key ] ] ) ) {
						$responsive[ $max_breakpoints_fw[ $key ] ] = array();
					}
					$parent_sp_custom = $settings[ 'col_sp_custom' . $device ]['size'];
					$responsive[ $max_breakpoints_fw[ $key ] ]['spaceBetween'] = $settings[ 'col_sp_custom' . $device ]['size'];
				} elseif ( $parent_sp_custom != $extra_options['spaceBetween'] ) {
					if ( ! isset( $responsive[ $max_breakpoints_fw[ $key ] ] ) ) {
						$responsive[ $max_breakpoints_fw[ $key ] ] = array();
					}
					$responsive[ $max_breakpoints_fw[ $key ] ]['spaceBetween'] = $parent_sp_custom;
				} elseif ( isset( $site_settings[ 'gutter_space' . $device ]['size'] ) && '' !== $site_settings[ 'gutter_space' . $device ]['size'] ) {
					if ( ! isset( $responsive[ $max_breakpoints_fw[ $key ] ] ) ) {
						$responsive[ $max_breakpoints_fw[ $key ] ] = array();
					}
					$parent_sp_global = $site_settings[ 'gutter_space' . $device ]['size'];
					$responsive[ $max_breakpoints_fw[ $key ] ]['spaceBetween'] = $site_settings[ 'gutter_space' . $device ]['size'];
				} elseif ( $parent_sp_global != $extra_options['spaceBetween'] ) {
					if ( ! isset( $responsive[ $max_breakpoints_fw[ $key ] ] ) ) {
						$responsive[ $max_breakpoints_fw[ $key ] ] = array();
					}
					$responsive[ $max_breakpoints_fw[ $key ] ]['spaceBetween'] = $parent_sp_global;
				}

				if ( isset( $responsive[ $max_breakpoints_fw[ $key ] ] ) && '_tablet' == $device ) {
					$responsive[ $max_breakpoints_fw[ $key ] ]['slidesPerView'] = $col_cnt['md'];
				}
			}
		}

		if ( isset( $responsive[0]['spaceBetween'] ) && isset( $responsive[576] ) ) {
			$responsive[576]['spaceBetween'] = $responsive[0]['spaceBetween'];
		}

		if ( isset( $col_cnt['xlg'] ) ) {
			$extra_options['slidesPerView'] = $col_cnt['xlg'];
		} elseif ( isset( $col_cnt['xl'] ) ) {
			$extra_options['slidesPerView'] = $col_cnt['xl'];
		} elseif ( isset( $col_cnt['lg'] ) ) {
			$extra_options['slidesPerView'] = $col_cnt['lg'];
		}

		if ( isset( $settings['enable_thumb'] ) && 'yes' == $settings['enable_thumb'] && $id ) {
			$extra_options['pagination'] = false;
			foreach ( $responsive as $w => $c ) {
				$responsive[ $w ]['pagination'] = false;
			}
		}

		$extra_options['breakpoints'] = $responsive;

		$extra_options['statusClass'] = trim( ( empty( $settings['status_class'] ) ? '' : $settings['status_class'] ) . alpha_get_slider_status_class( $settings ) );
		return $extra_options;
	}
}

/**
 * Get search form
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_search_form' ) ) {
	function alpha_search_form( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'type'             => 'classic',
				'toggle_type'      => 'overlap',
				'search_align'     => 'start',
				'fullscreen_skin'  => 'light',
				'where'            => '',
				'live_search'      => (bool) alpha_get_option( 'live_search' ),
				'search_post_type' => alpha_get_option( 'search_post_type' ),
				'icon'             => ALPHA_ICON_PREFIX . '-icon-search',
				'label'            => '',
				'placeholder'      => '',
			)
		);

		extract( $args );

		ob_start();
		$class = '';
		if ( 'toggle' == $type ) {
			$class .= ' hs-toggle hs-' . $toggle_type;
		}

		if ( $search_align ) {
			$class .= ' hs-' . $search_align;
		}
		$class .= ' ' . $fullscreen_skin . '-style';
		?>

	<div class="search-wrapper <?php echo esc_attr( $class ); ?>">
					<?php if ( 'toggle' == $type ) : ?>
		<a href="#" class="search-toggle" aria-label="<?php esc_attr_e( 'Search', 'alpha' ); ?>">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
						<?php if ( $label ) : ?>
			<span><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
		</a>
		<?php endif; ?>

					<?php if ( 'fullscreen' == $toggle_type ) : ?>
		<div class="close-overlay"></div>
		<div class="search-form-wrapper">
			<div class="search-inner-wrapper">
				<div class="search-form">
		<?php endif; ?>
		<form action="<?php echo esc_url( home_url() ); ?>/" method="get" class="input-wrapper">
			<input type="hidden" name="post_type" value="<?php echo esc_attr( $search_post_type ); ?>"/>

			<input type="search" aria-label="<?php esc_attr_e( 'Search', 'alpha' ); ?>" class="form-control" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" required="" autocomplete="off">

					<?php if ( $live_search ) : ?>
				<div class="live-search-list"></div>
			<?php endif; ?>

			<button class="btn btn-search" aria-label="<?php esc_attr_e( 'Search Button', 'alpha' ); ?>" type="submit">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			</button>

					<?php if ( 'overlap' == $toggle_type ) : ?>
			<div class="hs-close">
				<a href="#">
					<span class="close-wrap">
						<span class="close-line close-line1"></span>
						<span class="close-line close-line2"></span>
					</span>
				</a>
			</div>
		<?php endif; ?>

		</form>
					<?php if ( 'fullscreen' == $toggle_type ) : ?>
					<div class="search-header">
						<?php esc_html_e( 'Hit enter to search or ESC to close', 'alpha' ); ?>
						<div class="hs-close">
							<a href="#">
								<span class="close-wrap">
									<span class="close-line close-line1"></span>
									<span class="close-line close-line2"></span>
								</span>
							</a>
						</div>
					</div>
				</div>
				<div class="search-container mt-8">
					<div class="scrollable">
						<div class="search-results">
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	</div>

		<?php

		echo apply_filters( 'get_search_form', ob_get_clean() );
	}
}

/**
 * Get excerpt
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_get_excerpt' ) ) {
	function alpha_get_excerpt( $post, $excerpt_length = 45, $excerpt_type = 'words', $readmore_btn = '' ) {
		if ( has_excerpt( $post ) ) {
			echo alpha_trim_description( wp_strip_all_tags( get_the_excerpt( $post ), true ), $excerpt_length, $excerpt_type ) . alpha_escaped( $readmore_btn );
		} elseif ( strpos( $post->post_content, '<!--more-->' ) ) {
			echo apply_filters( 'the_content', alpha_trim_description( get_the_content( '' ), $excerpt_length, $excerpt_type ) ) . alpha_escaped( $readmore_btn );
		} else {
			$content = alpha_trim_description( get_the_content(), $excerpt_length, $excerpt_type );
			if ( $content ) {
				echo alpha_escaped( $content . $readmore_btn );
			}
		}
	}
}

/**
 * Get responsive cols
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_get_responsive_cols' ) ) {
	function alpha_get_responsive_cols( $cols, $type = 'product' ) {
		$result = array();
		$base   = $cols['lg'] ? $cols['lg'] : ( ! empty( $cols['xl'] ) ? $cols['xl'] : 4 );

		if ( 6 < $base ) { // 7, 8
			if ( empty( $cols['lg'] ) ) {
				$result = array(
					'xl'  => $base,
					'lg'  => 6,
					'md'  => 4,
					'sm'  => 3,
					'min' => 2,
				);
			} else {
				$result = array(
					'lg'  => $base,
					'md'  => 6,
					'sm'  => 4,
					'min' => 3,
				);
			}
		} elseif ( 4 < $base ) { // 5, 6
			$result = array(
				'lg'  => $base,
				'md'  => 4,
				'sm'  => 3,
				'min' => 2,
			);

			if ( ! isset( $cols['xl'] ) ) {
				$result['xl'] = $base;
				$result['lg'] = 4;
			}
		} elseif ( 2 < $base ) { // 3, 4
			$result = array(
				'lg'  => $base,
				'md'  => 3,
				'sm'  => 2,
				'min' => 2,
			);

			if ( 'post' == $type ) {
				$result['min'] = 1;
			}
		} else { // 1, 2
			$result = array(
				'lg'  => $base,
				'md'  => $base,
				'sm'  => 1,
				'min' => 1,
			);
		}

		foreach ( $cols as $w => $c ) {
			if ( 'lg' != $w && $c > 0 ) {
				$result[ $w ] = $c;
			}
		}

		/**
		 * Filters responsive columns.
		 *
		 * @since 1.0
		 */
		return apply_filters( 'alpha_filter_reponsive_cols', $result, $cols );
	}
}

if ( ! function_exists( 'alpha_get_breakpoints' ) ) {

	/**
	 * Get breakpoints
	 *
	 * @since 1.0
	 *
	 * @param string $screen_mode Screen mode
	 *
	 * @return int|array Breakpoints array or given breakpoint number.
	 */
	function alpha_get_breakpoints( $screen_mode = '' ) {

		$breakpoints_config = array(
			'mobile'       => array(
				'value' => 767,
			),
			'tablet'       => array(
				'value' => 991,
			),
			'tablet_extra' => array(
				'value' => 1199,
			),
		);
		if ( class_exists( '\Elementor\Core\Breakpoints\Manager' ) ) {
			$breakpoints        = new Breakpoints_Manager();
			$breakpoints_value  = $breakpoints->get_breakpoints_config();
			$breakpoints_config = array_merge( $breakpoints_config, $breakpoints_value );
		}

		if ( 'min' == $screen_mode ) {
			return 0;
		} elseif ( 'sm' == $screen_mode ) {
			return 576;
		} elseif ( 'md' == $screen_mode ) {
			return $breakpoints_config['mobile']['value'] + 1;
		} elseif ( 'lg' == $screen_mode ) {
			return $breakpoints_config['tablet']['value'] + 1;
		} elseif ( 'xl' == $screen_mode ) {
			return 1200;
		}
		return array(
			'min' => 0,
			'sm'  => 576,
			'md'  => $breakpoints_config['mobile']['value'] + 1,
			'lg'  => $breakpoints_config['tablet']['value'] + 1,
			'xl'  => 1200,
		);
	}
}

if ( ! function_exists( 'alpha_setup_loop' ) ) {

	/**
	 * Sets up the alpha_loop global from the passed args or from the main query.
	 *
	 * @since 1.0
	 * @param array $args Args to pass into the global.
	 */
	function alpha_setup_loop( $args = array() ) {

		if ( ! is_array( $args ) ) {
			$args = array();
		}

		/**
		 * Filters the custom post types.
		 *
		 * @since 1.0
		 */
		if ( isset( $args['cpt'] ) && apply_filters( 'alpha_custom_post_types', array() ) ) {
			$cpt = $args['cpt'];
		} else {
			global $post;
			if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() && ALPHA_NAME . '_template' == $post->post_type && 'archive' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
				$cpt = Alpha_Template_Archive_Builder::get_instance()->preview_mode;
			} else {
				if ( is_search() && 'any' == get_query_var( 'post_type' ) ) {
					$cpt = 'post';
				} else {
					$cpt = get_post_type();
				}
			}
			if ( ALPHA_NAME == substr( $cpt, 0, strlen( ALPHA_NAME ) ) && in_array( substr( $cpt, strlen( ALPHA_NAME ) + 1 ), apply_filters( 'alpha_custom_post_types', array() ) ) ) {
				$cpt = substr( $cpt, strlen( ALPHA_NAME ) + 1 );
			} else {
				$cpt = 'post';
			}
		}

		$related = ! empty( $args['related'] );
		$widget  = ! empty( $args['widget'] );
		if ( is_home() || is_archive() || is_search() || ( is_single() && ! $related && ! $widget ) ) {
			$layout = $GLOBALS['alpha_layout'];
		}

		$default_args = apply_filters(
			'alpha_post_loop_default_args',
			array(
				'cpt'             => $cpt,
				'type'            => 'default',
				'widget'          => $widget,
				'related'         => $related,
				'image_size'      => 'alpha-post-small',
				'read_more_class' => 'btn-dark btn-link btn-underline',
				'read_more_label' => esc_html__( 'Read More', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>',
				'posts_layout'    => 'grid',
				'posts_column'    => 3,
				'excerpt_type'    => '',
				'excerpt_length'  => 10,
				'overlay'         => 'zoom',
				'loadmore_type'   => '',
				'loadmore_args'   => array(
					'cpt'  => $cpt,
					'blog' => ! $widget,
				),
				'loadmore_label'  => esc_html( 'Load More', 'alpha' ),
			)
		);
		if ( isset( $layout ) && is_array( $layout ) ) {
			$default_args = array_merge( $default_args, $layout );
		}

		if ( ! isset( $args['type'] ) ) {
			if ( isset( $_REQUEST['post_style_type'] ) ) {
				$args['type'] = $_REQUEST['post_style_type'];
			} elseif ( alpha_doing_ajax() && ! empty( $_REQUEST['only_posts'] ) ) {
				$args['type'] = '';
			}
		}

		if ( isset( $args['thumbnail_size'] ) ) {
			$args['image_size'] = $args['thumbnail_size'];
		}
		if ( alpha_doing_ajax() && ! empty( $_REQUEST['post_image'] ) ) {
			$args['image_size'] = wp_unslash( $_REQUEST['post_image'] );
		}

		if ( ! isset( $args['col_cnt'] ) ) {
			$args['col_cnt']        = alpha_get_responsive_cols(
				( ! isset( $args['posts_column'] ) && $default_args['posts_column'] > 3 ) ? array(
					'xl' => intval( $default_args['posts_column'] ),
					'lg' => 3,
				) : array(
					'lg' => intval(
						isset( $args['posts_column'] ) ? $args['posts_column'] : $default_args['posts_column']
					),
				)
			);
			$args['col_cnt']['min'] = 1;
		}

		if ( isset( $args['layout'] ) ) {
			$args['posts_layout'] = $args['layout'];
		}

		// Merge any existing values.
		if ( isset( $GLOBALS['alpha_loop'] ) && is_array( $GLOBALS['alpha_loop'] ) ) {
			$default_args = array_merge( $default_args, $GLOBALS['alpha_loop'] );
		}
		/**
		 * Filters post arguments.
		 *
		 * @since 1.0
		 */
		$GLOBALS['alpha_loop'] = wp_parse_args( apply_filters( 'alpha_post_args', $args ), $default_args );
		if ( $widget ) {
			$GLOBALS['alpha_post_idx'] = 0;
		}
	}
}
