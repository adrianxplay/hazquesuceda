<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Banner Widget
 *
 * Alpha Widget to display banner.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

class Alpha_Banner_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_banner';
	}

	public function get_title() {
		return esc_html__( 'Banner', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-banner';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'banner' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'jquery-floating';
			$depends[] = 'jquery-skrollr';
		}
		return $depends;
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-banner', alpha_core_framework_uri( '/widgets/banner/banner' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-banner' );
	}

	protected function register_controls() {
		alpha_elementor_banner_controls( $this );
	}

	public function get_repeater_setting_key( $setting_key, $repeater_key, $repeater_item_index ) {
		return parent::get_repeater_setting_key( $setting_key, $repeater_key, $repeater_item_index );
	}

	public function add_inline_editing_attributes( $key, $toolbar = 'basic' ) {
		parent::add_inline_editing_attributes( $key, $toolbar );
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/banner/render-banner-elementor.php' );
	}

	public function before_render() {
		$atts = $this->get_settings_for_display();
		if ( 'yes' == $atts['stretch_height'] ) {
			$this->add_render_attribute( '_wrapper', 'class', 'elementor-widget-alpha_banner_stretch' );
		}
		?>
		<div <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
		<?php
	}

	protected function content_template() {
		?>
		<#

		let effectClass = '';

		view.addRenderAttribute( 'banner_wrapper', 'class', 'banner banner-fixed' );
		view.addRenderAttribute( 'banner_content', 'class', 'banner-content' );
		view.addRenderAttribute( 'banner_content', 'class', settings.banner_origin );

		if ('yes' == settings.parallax ||  settings.background_effect ) {
			view.addRenderAttribute( 'banner_wrapper', 'class', 'banner-img-hidden' );
		}

		if ( 'yes' == settings.parallax ) { // Parallax
			let parallax_img     = settings.banner_background_image.url;
			let parallax_options = {
				'direction'      : settings.parallax_direction,
				'speed'          : settings.parallax_speed.size && 10 != settings.parallax_speed.size ? 10 / ( 10 - settings.parallax_speed.size ) : 1.5,
			};
			view.addRenderAttribute( 'banner_wrapper', 'class', 'parallax' );
			view.addRenderAttribute( 'banner_wrapper', 'data-parallax-image', parallax_img );
			view.addRenderAttribute( 'banner_wrapper', 'data-parallax-options', JSON.stringify( parallax_options ) );
		} else {

			// Overlay
			if ( settings.overlay ) {

				let overlayClass = '';

				if ( 'light' == settings.overlay || 'dark' == settings.overlay || 'zoom' == settings.overlay ) {
					overlayClass = 'overlay-' + settings.overlay;
				} else if ( 'zoom_light' == settings.overlay ) {
					overlayClass = 'overlay-zoom overlay-light';
				} else if ( 'zoom_dark' == settings.overlay ) {
					overlayClass = 'overlay-zoom overlay-dark';
				} else if ( '' !== settings.overlay ) {
					overlayClass = 'overlay-wrapper';
					effectClass = 'overlay-' + settings.overlay;
				}

				view.addRenderAttribute( 'banner_wrapper', 'class', overlayClass );
			}
		}

		// Stretch Height
		if ( 'yes' == settings.stretch_height ) {
			view.addRenderAttribute( 'banner_wrapper', 'class', 'banner-stretch' );
		}

		#><div {{{ view.getRenderAttributeString( 'banner_wrapper' ) }}}><#

		if ( settings.background_effect || settings.particle_effect ) {

			// Particle Effect
			let particle_effectClass   = 'particle-effect ';
			if ( settings.particle_effect ) {
				particle_effectClass += settings.particle_effect;
			}
			view.addRenderAttribute( 'particleClass', 'class', particle_effectClass );

			// Background Effect
			if ('yes' != settings.parallax) {
				let background_effectClass = 'background-effect ';
				if ( settings.background_effect ) {
					background_effectClass += settings.background_effect;
				}
				view.addRenderAttribute( 'backgroundClass', 'class', background_effectClass );
			}

			if ( settings.banner_background_image ) {
				let background_img = '';
				if ( settings.particle_effect && !settings.background_effect ) {
					background_img = '';
				} else if ('yes' != settings.parallax) {
					background_img = 'background-image: url(' + settings.banner_background_image.url + '); background-size: cover;';
				}
				view.addRenderAttribute( 'backgroundClass', 'style', background_img );
			}

			#>
			<div class="background-effect-wrapper">
			<div {{{ view.getRenderAttributeString( 'backgroundClass' ) }}}>
			<# if ( settings.particle_effect ) { #>
				<div {{{ view.getRenderAttributeString( 'particleClass' ) }}}></div>
			<# } #> 
			</div>
			</div>
			<#
		}

		if( '' != effectClass ) {
			effectClass += ' overlay-effect';
			#>
			<div class="{{ effectClass }}"></div>
			<#
		}

		if ( settings.banner_background_image.url ) {
			#>
			<figure class="banner-img">
				<img src="{{ settings.banner_background_image.url }}" />
			</figure>
			<#
		}

		// hotspot wrapper
		_.each( settings.banner_item_list, function( item, index ) {
			if( item.banner_item_type == 'hotspot' ) {
				view.renderAttributes['hs_wrapper'] = {};
				view.addRenderAttribute( 'hs_wrapper', 'class', 'hotspot-wrapper' );
				view.addRenderAttribute( 'hs_wrapper', 'class', 'elementor-repeater-item-' + item._id );
				if( item.hotspot_el_class ) {
					view.addRenderAttribute( 'hs_wrapper', 'class', item.hotspot_el_class );
				}
				view.addRenderAttribute( 'hs_wrapper', 'class', 'hotspot-' + item.hotspot_type );
				if( item.hotspot_effect ) {
					view.addRenderAttribute( 'hs_wrapper', 'class', 'hotspot-' + item.hotspot_effect );
				}
				#>
				<div {{{ view.getRenderAttributeString( 'hs_wrapper' ) }}}>
					<a href="#" class="hotspot">
					<#
					if ( item.hotspot_icon && item.hotspot_icon.library == 'svg' ) {
						#> {{{ elementor.helpers.renderIcon( view, item.hotspot_icon, { 'aria-hidden': true } ).value }}} <#
					} else if ( item.hotspot_icon && item.hotspot_icon.value ) {
						#> <i class="{{{ item.hotspot_icon.value }}}"></i> <#
					}
					#> </a>
					<#
						if( 'none' != item.hotspot_popup_position ) {
					#>
						<div class="hotspot-box hotspot-box-{{{ item.hotspot_popup_position }}}">
							<#
								if( 'html' == item.hotspot_type ) {
							#> {{{ item.hotspot_html }}} <#
								} else {
							#>
							<div><?php esc_html_e( 'Please refer to real site.', 'alpha-core' ); ?></div>
							<#
								}
							#>
						</div>
					<#
						}
					#>
				</div>
				<#
			}
		});

		if ( settings.banner_wrap ) {
			#><div class="{{ settings.banner_wrap }}"><#
		}

		// Showing Items
		#><div {{{ view.getRenderAttributeString( 'banner_content' ) }}}><#

		if ( settings._content_animation ) {
			view.addRenderAttribute( 'banner_content_inner', 'class', 'appear-animate' );
			if( settings.content_animation_duration ) {
				view.addRenderAttribute( 'banner_content_inner', 'class', 'animated-' + settings.content_animation_duration );
			}
			let contentSettings       = {
				'_animation'       : settings._content_animation,
				'_animation_delay' : settings._content_animation_delay ? settings._content_animation_delay : 0,
			};
			view.addRenderAttribute( 'banner_content_inner', 'data-settings', JSON.stringify( contentSettings ) );
			#><div {{{ view.getRenderAttributeString( 'banner_content_inner' ) }}}><#
		}

		_.each( settings.banner_item_list, function( item, index ) {

			let item_key = 'banner_item';
			if ( item.banner_item_type == 'text' ) { // Text
				item_key = view.getRepeaterSettingKey( 'banner_text_content', 'banner_item_list', index );
			}

			view.renderAttributes[item_key] = {};
			view.addRenderAttribute( item_key, 'class', 'banner-item' );
			view.addRenderAttribute( item_key, 'class', 'elementor-repeater-item-' + item._id );

			// Custom Class
			if ( item.banner_item_aclass ) {
				view.addRenderAttribute( item_key, 'class', item.banner_item_aclass );
			}

			// Animation
			let itemSettings = '';
			if ( item._animation ) {
				view.addRenderAttribute( item_key, 'class', 'appear-animate' );
				if( settings.animation_duration ) {
					view.addRenderAttribute( item_key, 'class', 'animated-' + settings.animation_duration );
				}
				let itemSettings = {
					'_animation'       : settings._animation,
					'_animation_delay' : settings._animation_delay ? settings._animation_delay : 0,
				};
				view.addRenderAttribute( item_key, 'data-settings', JSON.stringify( itemSettings ) );
			}

			// Item display type
			if ( 'yes' != item.banner_item_display ) {
				view.addRenderAttribute( item_key, 'class', 'item-block' );
			} else {
				view.addRenderAttribute( item_key, 'class', 'item-inline' );
			}

			// Floating
			let floating = {
				type: item.alpha_floating,
				m_track_dir: item.alpha_m_track_dir,
				m_track_speed: item.alpha_m_track_speed.size ? item.alpha_m_track_speed.size : 0.5,
				scroll_size: item.alpha_scroll_size.size ? item.alpha_scroll_size.size : 50,
				scroll_stop: item.alpha_scroll_stop,
			};			
			if ( ( item.alpha_floating && item.alpha_floating != '' ) || item.alpha_enable_transform_effect ) {
				if (item.alpha_enable_transform_effect) {
					view.addRenderAttribute( item_key, 'class', 'alpha-transform-animating' );
				}
				if (item.alpha_floating && item.alpha_floating != '') {
					view.addRenderAttribute( item_key, 'data-floating', JSON.stringify(floating) );
				}
				#>
				<div {{{ view.getRenderAttributeString( item_key ) }}}>
					<div class="floating-wrapper layer-wrapper elementor-repeater-item-wrapper">
				<#
				if ( 0 === item.alpha_floating.indexOf('mouse_tracking') ) {
					#>
					<div class="layer">
					<#
				}
				view.renderAttributes[item_key] = {};
			}

			if ( item.banner_item_type == 'text' ) { // Text

				view.addRenderAttribute( item_key, 'class', 'elementor-banner-item-text text' );

				if ( item.alpha_floating == '' ) {
					view.addInlineEditingAttributes( item_key );
				}
				#><{{item.banner_text_tag}} {{{ view.getRenderAttributeString( item_key ) }}}>{{{ item.banner_text_content }}}</{{item.banner_text_tag}}><#

			} else if ( item.banner_item_type == 'button' ) { // Button

				btn_class = [];
				if ( item.button_type ) {
					btn_class.push(item.button_type);
				}
				if ( 'btn-link' == item.button_type && item.link_hover_type ) {
					btn_class.push(item.link_hover_type);
					
					if ( 'yes' ==  item.show_underline ) {
						btn_class.push('btn-underline-show');
					}
				}
				if ( item.button_size ) {
					btn_class.push(item.button_size);
				}
				if ( item.shadow ) {
					btn_class.push(item.shadow);
				}
				if ( item.button_border ) {
					btn_class.push(item.button_border);
				}
				if ( 'btn-gradient' != item.button_type && item.button_skin ) {
					btn_class.push(item.button_skin);
				}
				if( 'btn-gradient' == item.button_type && item.button_gradient_skin ) {
					btn_class.push(item.button_gradient_skin);
				}
				if ( item.btn_class ) {
					btn_class.push(item.btn_class);
				}
				if ( 'yes' == item.icon_hover_effect_infinite ) {
					btn_class.push('btn-infinite');
				}

				if ( 'yes' == item.show_icon && item.icon && item.icon.value ) {
					if ( 'before' == item.icon_pos ) {
						btn_class.push('btn-icon-left');
					} else {
						btn_class.push('btn-icon-right');
					}
					if ( item.icon_hover_effect ) {
						btn_class.push(item.icon_hover_effect);
					}
				}
				if( item.banner_btn_link.url && item.banner_btn_link.url != '' ) {
					view.addRenderAttribute( item_key, 'href', item.banner_btn_link.url );

					if( item.banner_btn_link.is_external ) {
						view.addRenderAttribute( item_key, 'target', '_blank' );
					}
					if( item.banner_btn_link.nofollow ) {
						view.addRenderAttribute( item_key, 'rel', 'nofollow' );
					}
				}
				view.addRenderAttribute( item_key, 'class', 'btn' );
				if ( item.banner_btn_aclass ) {
					view.addRenderAttribute( item_key, 'class', item.banner_btn_aclass );
				}
				view.addRenderAttribute( item_key, 'class', btn_class );
					#>
				<a {{{ view.getRenderAttributeString( item_key ) }}}>
					<#
					let btn_text_key = view.getRepeaterSettingKey( 'banner_btn_text', 'banner_item_list', index );
					let btn_text = '';

					btn_text = item.banner_btn_text;

					view.addRenderAttribute( btn_text_key, 'class', 'elementor-banner-item-btn' );
					view.addInlineEditingAttributes( btn_text_key );

					if ( item.text_hover_effect ) {
						view.addRenderAttribute( btn_text_key, 'data-text', btn_text );
					}

					if ( item.icon && item.icon.value && 'yes' == item.show_icon ) {
						var btnIconHtml = '';
						if( item.icon.library == 'svg' ) {
							btnIconHtml = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true } ).value;
						} else {
							btnIconHtml = '<i class="' + item.icon.value + '"></i>';
						}
						if ( 'before' == item.icon_pos ) {
							#>
							{{{ btnIconHtml }}}
							<span {{{ view.getRenderAttributeString( btn_text_key ) }}}>{{{ btn_text }}}</span>
							<#
						} else {
							#>
							<span {{{ view.getRenderAttributeString( btn_text_key ) }}}>{{{ btn_text }}}</span>
							{{{ btnIconHtml }}}
							<#
						}
					} else {
						#>
					<span {{{ view.getRenderAttributeString( btn_text_key ) }}}>{{{ btn_text }}}</span>
						<#
					}
					#>
				</a>
					<#
			} else if (item.banner_item_type == 'image') { // Image
				let image = {
					id: item.banner_image.id,
					url: item.banner_image.url,
					size: item.banner_image_size,
					dimension: item.banner_image_custom_dimension,
					model: view.getEditModel()
				};
				let image_url = elementor.imagesManager.getImageUrl( image );
				view.addRenderAttribute( item_key, 'class', 'image' );
				<!-- view.addRenderAttribute( item_key, 'src', image_url ); -->
				if( item.img_link && item.img_link.url ) {
					view.renderAttributes['image_link'] = {};
					view.addRenderAttribute( 'image_link', 'href', item.img_link.url );
					if( item.img_link.is_external ) {
						view.addRenderAttribute( 'image_link', 'target', '_blank' );
					}
					if( item.img_link.nofollow ) {
						view.addRenderAttribute( 'image_link', 'rel', 'nofollow' );
					}
				}
				#>
				<div {{{ view.getRenderAttributeString( item_key ) }}}>
					<#
					if( item.img_link && item.img_link.url ) {
					#>
						<a {{{ view.getRenderAttributeString( 'image_link' ) }}} >
					<#
						}
					#>
					<img src="{{{ image_url }}}" />
					<#
					if( item.img_link && item.img_link.url ) {
					#>
						</a>
					<#
						}
					#>
				</div>
				<#
			} else if (item.banner_item_type == 'hotspot') { // hotspot
			} else { // Divider
				view.addRenderAttribute( item_key, 'class', 'divider-wrap' );
				#><div {{{ view.getRenderAttributeString( item_key ) }}}><hr class="divider"></div><#
			}

			if ( ( item.alpha_floating && item.alpha_floating != '' ) || item.alpha_enable_transform_effect ) {
				if ( 0 === item.alpha_floating.indexOf('mouse_tracking') ) {
					#>
					</div>
					<#
				}
				#>
				</div>
				</div>
				<#
			}
		} );
		if ( settings._content_animation ) {
			#></div><#
		}
		#></div><#
		if ( settings.banner_wrap ) {
			#></div><#
		}
		#></div><#
		#>
		<?php
	}
}
