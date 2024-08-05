<?php
/**
 * Core Functions
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

// Breakpoints_Manager Class for alpha_get_breakpoints - function
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! function_exists( 'alpha_print_share' ) ) {
	/**
	 * Print Share
	 *
	 * @since 4.0
	 */
	function alpha_print_share() {
		if ( ! function_exists( 'alpha_get_option' ) || ! function_exists( 'alpha_get_social_shares' ) ) {
			return;
		}

		ob_start();
		?>
		<div class="social-icons">
			<?php

			$social_shares = alpha_get_social_shares();
			$icon_type     = alpha_get_option( 'share_type' );
			$custom        = alpha_get_option( 'share_use_hover' ) ? '' : ' use-hover';

			foreach ( alpha_get_option( 'share_icons' ) as $share ) {
				$permalink = apply_filters( 'the_permalink', get_permalink() );
				$title     = esc_attr( get_the_title() );
				$image     = wp_get_attachment_url( get_post_thumbnail_id() );

				if ( class_exists( 'YITH_WCWL' ) && is_user_logged_in() ) {
					if ( get_option( 'yith_wcwl_wishlist_page_id' ) == get_the_ID() ) {
						$wishlist_id = ( YITH_WCWL()->last_operation_token ) ? YITH_WCWL()->last_operation_token : YITH_WCWL()->details['wishlist_id'];
						$permalink  .= '/view/' . $wishlist_id;
						$permalink   = urlencode( $permalink );
					}
				}

				$permalink = esc_url( $permalink );

				if ( 'whatsapp' == $share ) {
					$title = rawurlencode( $title );
				} else {
					$title = urlencode( $title );
				}

				$link = strtr(
					$social_shares[ $share ]['link'],
					array(
						'$permalink' => $permalink,
						'$title'     => $title,
						'$image'     => $image,
					)
				);
				$link = 'whatsapp' == $share || 'email' == $share ? esc_attr( $link ) : esc_url( $link );
				$link = $link ? $link : '#';

				echo '<a href="' . alpha_escaped( $link ) . '" class="social-icon ' . esc_attr( $icon_type . $custom ) . ' social-' . $share . '" target="_blank" rel="noopener noreferrer" title="' . $social_shares[ $share ]['title'] . '">';
				echo '<i class="' . esc_attr( $social_shares[ $share ]['icon'] ) . '"></i>';
				echo '</a>';
			}
			?>
		</div>
		<?php
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'alpha_get_grid_space' ) ) {

	/**
	 * Get columns' gutter size value from size string
	 *
	 * @since 4.0
	 *
	 * @param string $col_sp Columns gutter size string
	 *
	 * @return int Gutter size value
	 */
	function alpha_get_grid_space( $col_sp ) {
		if ( 'no' == $col_sp ) {
			return 0;
		} elseif ( 'sm' == $col_sp ) {
			return 10;
		} elseif ( 'md' == $col_sp ) {
			return 20;
		} elseif ( 'xs' == $col_sp ) {
			return 2;
		} elseif ( 'lg' == $col_sp ) {
			return 30;
		} elseif ( 'xl' == $col_sp ) {
			return 60;
		} else {
			return 30;
		}
	}
}

/**
 * Get the grid col cnt for elementor page builder.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_grid_col_cnt' ) ) {
	function alpha_elementor_grid_col_cnt( $settings, $key = 'col_cnt' ) {
		$col_cnt = array(
			'xl'  => isset( $settings[ $key . '_xl' ] ) ? (int) $settings[ $key . '_xl' ] : 0,
			'lg'  => isset( $settings[ $key ] ) ? (int) $settings[ $key ] : 0,
			'md'  => isset( $settings[ $key . '_tablet' ] ) ? (int) $settings[ $key . '_tablet' ] : 0,
			'sm'  => isset( $settings[ $key . '_mobile' ] ) ? (int) $settings[ $key . '_mobile' ] : 0,
			'min' => isset( $settings[ $key . '_min' ] ) ? (int) $settings[ $key . '_min' ] : 0,
		);

		return function_exists( 'alpha_get_responsive_cols' ) ? alpha_get_responsive_cols( $col_cnt ) : $col_cnt;
	}
}


/**
 * Elementor content-template for slider.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_slider_template' ) ) {
	function alpha_elementor_slider_template() {
		$max_breakpoints = alpha_get_breakpoints();
		$kit             = get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 );
		if ( $kit ) {
			$site_settings = get_post_meta( get_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, 0 ), '_elementor_page_settings', true );
			$gutters       = array(
				'min' => ! empty( $site_settings['gutter_space_mobile']['size'] ) ? $site_settings['gutter_space_mobile']['size'] : '',
				'sm'  => ! empty( $site_settings['gutter_space_mobile_extra']['size'] ) ? $site_settings['gutter_space_mobile_extra']['size'] : '',
				'md'  => ! empty( $site_settings['gutter_space_tablet']['size'] ) ? $site_settings['gutter_space_tablet']['size'] : '',
				'lg'  => ! empty( $site_settings['gutter_space_tablet_extra']['size'] ) ? $site_settings['gutter_space_tablet_extra']['size'] : '',
				'xl'  => ! empty( $site_settings['gutter_space_laptop']['size'] ) ? $site_settings['gutter_space_laptop']['size'] : '',
				'xlg' => ! empty( $site_settings['gutter_space']['size'] ) ? $site_settings['gutter_space']['size'] : '',
				'xxl' => ! empty( $site_settings['gutter_space_widescreen']['size'] ) ? $site_settings['gutter_space_widescreen']['size'] : '',
			);
		}
		wp_enqueue_script( 'swiper' );
		?>
		var breakpoints = <?php echo json_encode( $max_breakpoints ); ?>;
		var gutters = <?php echo json_encode( $gutters ); ?>;
		var extra_options = {};

		extra_class += ' slider-wrapper';

		// Layout
		if ( 'lg' == settings.col_sp || 'xs' == settings.col_sp || 'sm' == settings.col_sp || 'no' == settings.col_sp ) {
			extra_class += ' gutter-' + settings.col_sp;
		}

		var col_cnt = 'function' == typeof alpha_get_responsive_cols ? alpha_get_responsive_cols({
			xl: settings.col_cnt_xl,
			lg: settings.col_cnt,
			md: settings.col_cnt_tablet,
			sm: settings.col_cnt_mobile,
			min: settings.col_cnt_min,
		}) : {
			xl: settings.col_cnt_xl,
			lg: settings.col_cnt,
			md: settings.col_cnt_tablet,
			sm: settings.col_cnt_mobile,
			min: settings.col_cnt_min,
		};
		extra_class += ' ' + alpha_get_col_class( col_cnt );

		// Nav & Dot

		var statusClass = '';

		if ( 'full' == settings.nav_type ) {
			statusClass += ' slider-nav-full';
		} else {
			if ( 'circle' == settings.nav_type ) {
				statusClass += ' slider-nav-circle';
			}
			if ( 'top' == settings.nav_pos ) {
				statusClass += ' slider-nav-top';
			} else if ( 'bottom' == settings.nav_pos ) {
				statusClass += ' slider-nav-bottom';
			} else if ( 'inner' != settings.nav_pos ) {
				statusClass += ' slider-nav-outer';
			}
		}
		if ( 'yes' == settings.nav_hide ) {
			statusClass += ' slider-nav-fade';
		}
		if ( settings.dots_type ) {
			statusClass += ' slider-dots-' + settings.dots_type;
		}
		if ( settings.dots_skin ) {
			statusClass += ' slider-dots-' + settings.dots_skin;
		}
		if ( 'inner' == settings.dots_pos ) {
			statusClass += ' slider-dots-inner';
		}
		if ( 'outer' == settings.dots_pos ) {
			statusClass += ' slider-dots-outer';
		}
		if ( 'yes' == settings.fullheight ) {
			statusClass += ' slider-full-height';
		}
		if ( 'yes' == settings.box_shadow_slider ) {
			statusClass += ' slider-shadow';
		}
		if ( 'yes' == settings.scale_drag && 'yes' != settings.disable_mouse_drag ) {
			statusClass += ' slider-scale-shrink';
		}
		if ( 'yes' == settings.focus_on_active ) {
			if ( 'scale' == settings.focus_effect ) {
				statusClass += ' slider-zoom-in-active-slide';
			} else if ( 'opacity' == settings.focus_effect ) {
				statusClass += ' slider-active-slide-opacity';
			} else if ( 'scale_opacity' == settings.focus_effect ) {
				statusClass += ' slider-zoom-in-active-slide slider-active-slide-opacity';
			}
		}

		if ( 'top' == settings.slider_vertical_align ||
			'middle' == settings.slider_vertical_align ||
			'bottom' == settings.slider_vertical_align ||
			'same-height' == settings.slider_vertical_align ) {
			statusClass += ' slider-' + settings.slider_vertical_align;
		}

		extra_options['navigation'] = 'yes' == settings.show_nav;
		extra_options['pagination'] = 'yes' == settings.show_dots;
		if ( settings.col_sp ) {
			if ( 'sm' == settings.col_sp ) {
				extra_options['spaceBetween'] = 10;
			}
			else if ( 'md' == settings.col_sp ) {
				extra_options['spaceBetween'] = 20;
			}
			else if ( 'xs' == settings.col_sp ) {
				extra_options['spaceBetween'] = 2;
			}
			else if ( 'lg' == settings.col_sp ) {
				extra_options['spaceBetween'] = 30;
			}
			else if ( 'xl' == settings.col_sp ) {
				extra_options['spaceBetween'] = 60;
			} else {
				extra_options['spaceBetween'] = 0;
			}
		} else if ('undefined' != typeof settings.col_sp_custom && settings.col_sp_custom.size !== '') {
			extra_options['spaceBetween'] = settings.col_sp_custom.size;
		} else if ('undefined' != typeof settings.col_sp_custom_laptop && settings.col_sp_custom_laptop.size !== '') {
			extra_options['spaceBetween'] = settings.col_sp_custom_laptop.size;
		} else if ('undefined' != typeof settings.col_sp_custom_tablet_extra && settings.col_sp_custom_tablet_extra.size !== '') {
			extra_options['spaceBetween'] = settings.col_sp_custom_tablet_extra.size;
		} else if ('undefined' != typeof gutters.xlg && gutters.xlg !== '') {
			extra_options['spaceBetween'] = gutters.xlg;
		} else if ('undefined' != typeof gutters.xl && gutters.xl !== '') {
			extra_options['spaceBetween'] = gutters.xl;
		} else if ('undefined' != typeof gutters.lg && gutters.lg !== '') {
			extra_options['spaceBetween'] = gutters.lg;
		} else {
			extra_options['spaceBetween'] = 30;
		}
		extra_options['spaceBetween'] = elementorFrontend.hooks.applyFilters('alpha_slider_gap', extra_options['spaceBetween'], settings.col_sp);

		if ( 'yes' == settings.centered ) {
			extra_options['centeredSlides'] = true;
		}
		if( 'yes' == settings.loop ) {
			extra_options['loop'] = true;
		}
		if ( 'yes' == settings.autoplay ) {
			extra_options['autoplay'] = true;
			extra_options['autoplayHoverPause'] = true;
		}
		if ( 5000 != settings.autoplay_timeout ) {
			extra_options['autoplayTimeout'] = settings.autoplay_timeout;
		}
		if ( 'yes' == settings.autoheight) {
			extra_options['autoHeight'] = true;
		}
		if ( 'yes' == settings.autoheight) {
			extra_options['autoHeight'] = true;
		}
		if( 'yes' == settings.disable_mouse_drag ) {
			extra_options['allowTouchMove'] = false;
		}
		if ( settings.effect ) {
			extra_options['effect'] = settings.effect;
		}
		if ( settings.speed ) {
			extra_options['speed'] = settings.speed;
		}

		if ( 'yes' == settings.enable_thumb ) {
			extra_options['dotsContainer'] = 'preview';
		}

		var responsive = {};
		var w = {
			min: 'mobile',
			sm: 'mobile_extra',
			md: 'tablet',
			lg: 'tablet_extra',
			xl: 'laptop',
			xlg: '',
			xxl: 'widescreen',
		};
		if (settings.col_sp) {
		#>
		<?php
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
		?>
		<#
		}
		var breakpointsFW = <?php echo json_encode( $max_breakpoints_fw ); ?>;

		for ( var key in w ) {
			if ('undefined' != typeof col_cnt[key] && 'undefined' != typeof breakpoints[ key ]) {
				responsive[ breakpoints[ key ] ] = {
					slidesPerView: col_cnt[key]
				}
			}
			var device = w[key];
			if ( w[key] ) {
				device = '_' + device;
			}
			if ( !settings.col_sp ) {
				if ( 'undefined' != typeof settings['col_sp_custom' + device] && settings['col_sp_custom' + device].size !== '' ) {
					if ( 'undefined' == typeof responsive[ breakpointsFW[ key ] ] ) {
						responsive[ breakpointsFW[ key ] ] = {spaceBetween: ''};
					}
					responsive[ breakpointsFW[ key ] ]['spaceBetween'] = settings['col_sp_custom' + device].size;
				} else if ( 'undefined' != typeof gutters[ device ] && gutters[ device ] !== '' ) {
					if ( 'undefined' == typeof responsive[ breakpointsFW[ key ] ] ) {
						responsive[ breakpointsFW[ key ] ] = {spaceBetween: ''};
					}
					responsive[ breakpointsFW[ key ] ]['spaceBetween'] = gutters[ device ];
				}
			}
		}
		extra_options['statusClass'] = statusClass;

		if ( col_cnt.xl ) {
			extra_options['slidesPerView'] = col_cnt.xl;
		} else if ( col_cnt.lg ) {
			extra_options['slidesPerView'] = col_cnt.lg;
		}
		extra_options.breakpoints = responsive;

		extra_attrs += ' data-slider-options="' + JSON.stringify( extra_options ).replaceAll('"', '\'') + '"';
		<?php
	}
}


/**
 * Get the exact parameters of each predefined layouts.
 *
 * @param int $index    The index of predefined creative layouts
 * @since 4.0
 */
if ( ! function_exists( 'alpha_creative_preset_imgs' ) ) {
	function alpha_creative_preset_imgs() {
		return apply_filters(
			'alpha_creative_preset_imgs',
			array(
				1  => '/assets/images/creative-grid/creative-1.jpg',
				2  => '/assets/images/creative-grid/creative-2.jpg',
				3  => '/assets/images/creative-grid/creative-3.jpg',
				4  => '/assets/images/creative-grid/creative-4.jpg',
				5  => '/assets/images/creative-grid/creative-5.jpg',
				6  => '/assets/images/creative-grid/creative-6.jpg',
				7  => '/assets/images/creative-grid/creative-7.jpg',
				8  => '/assets/images/creative-grid/creative-8.jpg',
				9  => '/assets/images/creative-grid/creative-9.jpg',
				10 => '/assets/images/creative-grid/creative-10.jpg',
				11 => '/assets/images/creative-grid/creative-11.jpg',
				12 => '/assets/images/creative-grid/creative-12.jpg',
			)
		);
	}
}

/**
 * The creative layout style.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_creative_layout_style' ) ) {
	function alpha_creative_layout_style( $wrapper, $layout, $height = 600, $ratio = 75 ) {
		$hs    = array( 'h-1', 'h-1-2', 'h-1-3', 'h-2-3', 'h-1-4', 'h-3-4' );
		$deno  = array();
		$numer = array();
		$ws    = array(
			'w'   => array(),
			'w-l' => array(),
			'w-m' => array(),
			'w-s' => array(),
		);
		$hs    = array(
			'h'   => array(),
			'h-l' => array(),
			'h-m' => array(),
		);

		$breakpoints = alpha_get_breakpoints();
		ob_start();
		echo '<style>';
		foreach ( $layout as $grid_item ) {
			foreach ( $grid_item as $key => $value ) {
				if ( 'size' == $key ) {
					continue;
				}
				$num = explode( '-', $value );
				if ( isset( $num[1] ) && ! in_array( $num[1], $deno ) ) {
					$deno[] = $num[1];
				}
				if ( ! in_array( $num[0], $numer ) ) {
					$numer[] = $num[0];
				}

				if ( ( 'w' == $key || 'w-l' == $key || 'w-m' == $key || 'w-s' == $key ) && ! in_array( $value, $ws[ $key ] ) ) {
					$ws[ $key ][] = $value;
				}
				if ( ( 'h' == $key || 'h-l' == $key || 'h-m' == $key ) && ! in_array( $value, $hs[ $key ] ) ) {
					$hs[ $key ][] = $value;
				}
			}
		}
		foreach ( $ws as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}

			if ( 'w-l' == $key ) {
				echo '@media (max-width: ' . ( $breakpoints['lg'] - 1 ) . 'px) {';
			} elseif ( 'w-m' == $key ) {
				echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
			} elseif ( 'w-s' == $key ) {
				echo '@media (max-width: ' . ( $breakpoints['sm'] - 1 ) . 'px) {';
			}

			foreach ( $value as $item ) {
				$opts  = explode( '-', $item );
				$width = ( ! isset( $opts[1] ) ? 100 : round( 100 * $opts[0] / $opts[1], 4 ) );
				echo esc_attr( $wrapper ) . ' .grid-item.' . $key . '-' . $item . '{flex:0 0 ' . $width . '%;width:' . $width . '%}';
			}

			if ( 'w-l' == $key || 'w-m' == $key || 'w-s' == $key ) {
				echo '}';
			}
		};
		foreach ( $hs as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}

			foreach ( $value as $item ) {
				$opts = explode( '-', $item );

				if ( isset( $opts[1] ) ) {
					$h = $height * $opts[0] / $opts[1];
				} else {
					$h = $height;
				}
				if ( 'h' == $key ) {
					echo esc_attr( $wrapper ) . ' .h-' . $item . '{height:' . round( $h, 2 ) . 'px}';
					echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
					echo '}';
				} elseif ( 'h-l' == $key ) {
					echo '@media (max-width: ' . ( $breakpoints['lg'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-l-' . $item . '{height:' . round( $h, 2 ) . 'px}';
					echo '}';
					echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-l-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
					echo '}';
				} elseif ( 'h-m' == $key ) {
					echo '@media (max-width: ' . ( $breakpoints['md'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-m-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
					echo '}';
				}
			}
		};
		$lcm = 1;
		foreach ( $deno as $value ) {
			$lcm = $lcm * $value / alpha_get_gcd( $lcm, $value );
		}
		$gcd = $numer[0];
		foreach ( $numer as $value ) {
			$gcd = alpha_get_gcd( $gcd, $value );
		}
		$sizer          = floor( 100 * $gcd / $lcm * 10000 ) / 10000;
		$space_selector = ' .grid>.grid-space';
		if ( false !== strpos( $wrapper, 'wpb_' ) ) {
			$space_selector = '>.grid-space';
		}
		echo esc_attr( $wrapper ) . $space_selector . '{flex: 0 0 ' . ( $sizer < 0.01 ? 100 : $sizer ) . '%;width:' . ( $sizer < 0.01 ? 100 : $sizer ) . '%}';
		echo '</style>';
		alpha_filter_inline_css( ob_get_clean() );
	}
}

/**
 * Get button widget class
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_widget_button_get_class' ) ) {
	function alpha_widget_button_get_class( $settings, $prefix = '' ) {
		$class = array();
		if ( ! empty( $prefix ) ) {
			$class[] = 'btn-' . $prefix;
		}
		if ( isset( $settings[ $prefix . 'button_type' ] ) && $settings[ $prefix . 'button_type' ] ) {
			$class[] = $settings[ $prefix . 'button_type' ];
		}
		if ( isset( $settings[ $prefix . 'link_hover_type' ] ) && $settings[ $prefix . 'link_hover_type' ] ) {
			$class[] = $settings[ $prefix . 'link_hover_type' ];
		}
		if ( isset( $settings[ $prefix . 'button_size' ] ) && $settings[ $prefix . 'button_size' ] ) {
			$class[] = $settings[ $prefix . 'button_size' ];
		}
		if ( ! empty( $settings[ $prefix . 'text_hover_effect' ] ) ) {
			$class[] = 'btn-text-hover-effect ' . $settings[ $prefix . 'text_hover_effect' ];
		}
		if ( ! empty( $settings[ $prefix . 'bg_hover_effect' ] ) ) {
			$class[] = 'btn-bg-hover-effect ' . $settings[ $prefix . 'bg_hover_effect' ];
			if ( ! empty( $settings[ $prefix . 'bg_hover_color' ] ) ) {
				$class[] = $settings[ $prefix . 'bg_hover_color' ];
			}
			if ( ! empty( $settings[ $prefix . 'hover_outline_color' ] ) ) {
				$class[] = $settings[ $prefix . 'hover_outline_color' ];
			}
		}
		if ( isset( $settings[ $prefix . 'shadow' ] ) && $settings[ $prefix . 'shadow' ] ) {
			$class[] = $settings[ $prefix . 'shadow' ];
		}
		if ( isset( $settings[ $prefix . 'button_border' ] ) && $settings[ $prefix . 'button_border' ] ) {
			$class[] = $settings[ $prefix . 'button_border' ];
		}
		if ( ( ! isset( $settings[ $prefix . 'button_type' ] ) || 'btn-gradient' != $settings[ $prefix . 'button_type' ] ) && isset( $settings[ $prefix . 'button_skin' ] ) && $settings[ $prefix . 'button_skin' ] ) {
			$class[] = $settings[ $prefix . 'button_skin' ];
		}
		if ( isset( $settings[ $prefix . 'button_type' ] ) && 'btn-gradient' == $settings[ $prefix . 'button_type' ] && isset( $settings[ $prefix . 'button_gradient_skin' ] ) && $settings[ $prefix . 'button_gradient_skin' ] ) {
			$class[] = $settings[ $prefix . 'button_gradient_skin' ];

			if ( isset( $settings[ $prefix . 'gradient_apply' ] ) && 'yes' == $settings[ $prefix . 'gradient_apply' ] ) {
				$class[] = 'btn-link-gradient';
			}
		}
		if ( ! empty( $settings[ $prefix . 'btn_class' ] ) ) {
			$class[] = $settings[ $prefix . 'btn_class' ];
		}
		if ( isset( $settings[ $prefix . 'icon_hover_effect_infinite' ] ) && 'yes' == $settings[ $prefix . 'icon_hover_effect_infinite' ] ) {
			$class[] = 'btn-infinite';
		}

		if ( ! empty( $settings[ $prefix . 'icon' ] ) ) {
			if ( empty( $settings['self'] ) || is_array( $settings[ $prefix . 'icon' ] ) && $settings[ $prefix . 'icon' ]['value'] ) {
				if ( 'before' === $settings[ $prefix . 'icon_pos' ] ) {
					$class[] = 'btn-icon btn-icon-left';
				} else {
					$class[] = 'btn-icon btn-icon-right';
				}
				if ( ! empty( $settings[ $prefix . 'icon_hover_effect' ] ) ) {
					$class[] = $settings[ $prefix . 'icon_hover_effect' ];
				}
				if ( ! empty( $settings[ $prefix . 'svg_hover_effect' ] ) ) {
					$class[] = 'btn-icon-draw ' . $settings[ $prefix . 'svg_hover_effect' ];
				}
			}
		}
		return $class;
	}
}

/**
 * Get button widget label
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_widget_button_get_label' ) ) {
	function alpha_widget_button_get_label( $settings, $self, $label, $inline_key = '', $prefix = '' ) {
		$label = sprintf( '<span %1$s%2$s>%3$s</span>', $inline_key ? $self->get_render_attribute_string( $inline_key ) : '', ! empty( $settings[ $prefix . 'text_hover_effect' ] ) ? ' data-text="' . esc_attr( $label ) . '"' : '', esc_html( $label ) );

		if ( isset( $settings[ $prefix . 'icon' ]['library'] ) && 'svg' == $settings[ $prefix . 'icon' ]['library'] ) {
			ob_start();
			\ELEMENTOR\Icons_Manager::render_icon(
				array(
					'library' => 'svg',
					'value'   => array( 'id' => absint( isset( $settings[ $prefix . 'icon' ]['value']['id'] ) ? $settings[ $prefix . 'icon' ]['value']['id'] : 0 ) ),
				),
				array( 'aria-hidden' => 'true' )
			);
			$svg = ob_get_clean();
		}
		if ( isset( $settings[ $prefix . 'icon' ] ) && is_array( $settings[ $prefix . 'icon' ] ) && $settings[ $prefix . 'icon' ]['value'] ) {
			if ( 'before' == $settings[ $prefix . 'icon_pos' ] ) {
				if ( isset( $svg ) ) {
					$label = ( isset( $settings[ $prefix . 'show_label' ] ) && 'yes' === $settings[ $prefix . 'show_label' ] ) ? $svg . $label : $svg;
				} else {
					$label = ( isset( $settings[ $prefix . 'show_label' ] ) && 'yes' === $settings[ $prefix . 'show_label' ] ) ? '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>' . $label : '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>';
				}
			} else {
				if ( isset( $svg ) ) {
					$label = ( isset( $settings[ $prefix . 'show_label' ] ) && 'yes' === $settings[ $prefix . 'show_label' ] ) ? $label . $svg : $svg;
				} else {
					$label = ( isset( $settings[ $prefix . 'show_label' ] ) && 'yes' === $settings[ $prefix . 'show_label' ] ) ? $label . '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>' : '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>';
				}
			}
		}
		return $label;
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
 * Get the creative layout.
 *
 * @since 4.0
 */
if ( ! function_exists( 'alpha_creative_layout' ) ) {
	function alpha_creative_layout( $index ) {
		$layout = array();
		if ( 1 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '1',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-l'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'large',
				),
			);
		} elseif ( 2 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '1',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 3 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-4',
					'h'    => '1',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
			);
		} elseif ( 4 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '2-3',
					'h'    => '1',
					'w-l'  => '1',
					'w-s'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-3',
					'h'    => '1-3',
					'w-l'  => '1-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-3',
					'h'    => '1-3',
					'w-l'  => '1-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-3',
					'h'    => '1-3',
					'w-l'  => '1-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
			);
		} elseif ( 5 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '2-3',
					'h'    => '2-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-3',
					'h'    => '2-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'size' => 'medium',
				),
			);
		} elseif ( 6 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-5',
					'h'    => '1-2',
					'w-l'  => '1-3',
					'w-s'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '2-5',
					'h'    => '1-2',
					'w-l'  => '1-3',
					'w-s'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '2-5',
					'h'    => '1',
					'w-l'  => '1-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '2-5',
					'h'    => '1-2',
					'w-l'  => '1-3',
					'w-s'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-5',
					'h'    => '1-2',
					'w-l'  => '1-3',
					'w-s'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 7 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-l'  => '1',
					'size' => 'medium',
				),
			);
		} elseif ( 8 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '1',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-4',
					'h'    => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'large',
				),
			);
		} elseif ( 9 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '1',
					'w-m'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 10 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '2-3',
					'w-s'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '2-3',
					'w-s'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'w-s'  => '1',
					'size' => 'medium',
				),
			);
		} elseif ( 11 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '2-3',
					'w-m'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'w-m'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-m'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 12 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '7-12',
					'h'    => '2-3',
					'w-l'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '5-24',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '5-24',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '5-12',
					'h'    => '2-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '9-24',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '5-24',
					'h'    => '1-2',
					'w-l'  => '1',
					'size' => 'medium',
				),
			);
		}

		return apply_filters( 'alpha_creative_layout_filter', $layout );
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


/**
 * Create a page and store the ID in an option.
 *
 * @param mixed  $slug Slug for the new page.
 * @param string $option Option name to store the page's ID.
 * @param string $page_title (default: '') Title for the new page.
 * @param string $page_content (default: '') Content for the new page.
 * @param int    $post_parent (default: 0) Parent for the new page.
 * @return int page ID.
 */
function alpha_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;

	$option_value = get_option( $option );

	if ( $option_value > 0 ) {
		$page_object = get_post( $option_value );

		if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ), true ) ) {
			// Valid page is already in place.
			return $page_object->ID;
		}
	}

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode).
		$shortcode        = str_replace( array( '<!-- wp:shortcode -->', '<!-- /wp:shortcode -->' ), '', $page_content );
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$shortcode}%" ) );
	} else {
		// Search for an existing page with the specified page slug.
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	}

	$valid_page_found = apply_filters( 'alpha_create_page_id', $valid_page_found, $slug, $page_content );

	if ( $valid_page_found ) {
		if ( $option ) {
			update_option( $option, $valid_page_found );
		}
		return $valid_page_found;
	}

	// Search for a matching valid trashed page.
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode).
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug.
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'          => $page_id,
			'post_status' => 'publish',
		);
		wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $page_data );
	}

	if ( $option ) {
		update_option( $option, $page_id );
	}

	return $page_id;
}
