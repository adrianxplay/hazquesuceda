<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Section Banner
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Embed;
use Elementor\Plugin;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Alpha_Controls_Manager;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

if ( ! class_exists( 'Alpha_Section_Banner_Elementor_Widget_Addon' ) ) {
	class Alpha_Section_Banner_Elementor_Widget_Addon extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 4.1
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
		}

		/**
		 * Register banner addon to section element
		 *
		 * @since 4.1
		 */
		public function register_section_addon( $addons ) {
			$addons['banner'] = esc_html__( 'Banner', 'alpha-core' );
			return $addons;
		}

		/**
		 * Add banner controls to section element
		 *
		 * @since 4.1
		 */
		public function add_section_controls( $self, $condition_value ) {
			// Update Elementor Controls
			$self->update_control(
				'section_background',
				array(
					'condition' => array(
						$condition_value . '!' => 'banner',
					),
				),
				array( 'recursive' => true )
			);
			$self->update_control(
				'section_background_overlay',
				array(
					'condition' => array(
						$condition_value . '!' => 'banner',
					),
				),
				array( 'recursive' => true )
			);
			$self->update_control(
				'gap',
				array(
					'condition' => array(
						$condition_value . '!' => 'banner',
					),
				)
			);

			// Add Banner Controls
			$self->add_control(
				'section_banner_description',
				array(
					'raw'             => sprintf( esc_html__( 'Use %1$schild columns%2$s as %1$sbanner layer%2$s by using %1$s%3$s settings%2$s.', 'alpha-core' ), '<b>', '</b>', ALPHA_DISPLAY_NAME, ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'alpha-notice notice-warning',
					'condition'       => array(
						$condition_value => 'banner',
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
				'section_banner',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Banner', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'banner',
					),
				)
			);
				alpha_elementor_banner_layout_controls( $self, false, $condition_value );

				$self->add_control(
					'video_banner_switch',
					array(
						'label'       => esc_html__( 'Enable Video', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'description' => esc_html__( 'Use video as banner background or popup.', 'alpha-core' ),
						'condition'   => array(
							$condition_value => 'banner',
						),
						'separator'   => 'before',
					)
				);

			$self->end_controls_section();

			// Section Banner Style Options
			$self->start_controls_section(
				'alpha_video_section',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Video', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value      => 'banner',
						'video_banner_switch' => 'yes',
					),
				)
			);

				$self->add_control(
					'video_type',
					array(
						'label'   => esc_html__( 'Source', 'alpha-core' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'youtube',
						'options' => array(
							'youtube'     => esc_html__( 'YouTube', 'alpha-core' ),
							'vimeo'       => esc_html__( 'Vimeo', 'alpha-core' ),
							'dailymotion' => esc_html__( 'Dailymotion', 'alpha-core' ),
							'hosted'      => esc_html__( 'Self Hosted', 'alpha-core' ),
						),
					)
				);

				$self->add_control(
					'youtube_url',
					array(
						'label'       => esc_html__( 'Link', 'alpha-core' ),
						'type'        => Controls_Manager::TEXT,
						'dynamic'     => array(
							'active'     => true,
							'categories' => array(
								TagsModule::POST_META_CATEGORY,
								TagsModule::URL_CATEGORY,
							),
						),
						'placeholder' => esc_html__( 'Enter your URL (YouTube)', 'alpha-core' ),
						'default'     => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
						'label_block' => true,
						'condition'   => array(
							'video_type' => 'youtube',
						),
					)
				);

				$self->add_control(
					'vimeo_url',
					array(
						'label'       => esc_html__( 'Link', 'alpha-core' ),
						'type'        => Controls_Manager::TEXT,
						'dynamic'     => array(
							'active'     => true,
							'categories' => array(
								TagsModule::POST_META_CATEGORY,
								TagsModule::URL_CATEGORY,
							),
						),
						'placeholder' => esc_html__( 'Enter your URL (Vimeo)', 'alpha-core' ),
						'default'     => 'https://vimeo.com/235215203',
						'label_block' => true,
						'condition'   => array(
							'video_type' => 'vimeo',
						),
					)
				);

				$self->add_control(
					'dailymotion_url',
					array(
						'label'       => esc_html__( 'Link', 'alpha-core' ),
						'type'        => Controls_Manager::TEXT,
						'dynamic'     => array(
							'active'     => true,
							'categories' => array(
								TagsModule::POST_META_CATEGORY,
								TagsModule::URL_CATEGORY,
							),
						),
						'placeholder' => esc_html__( 'Enter your URL (Dailymotion)', 'alpha-core' ),
						'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
						'label_block' => true,
						'condition'   => array(
							'video_type' => 'dailymotion',
						),
					)
				);

				$self->add_control(
					'insert_url',
					array(
						'label'     => esc_html__( 'External URL', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'condition' => array(
							'video_type' => 'hosted',
						),
					)
				);

				$self->add_control(
					'hosted_url',
					array(
						'label'      => esc_html__( 'Choose File', 'alpha-core' ),
						'type'       => Controls_Manager::MEDIA,
						'dynamic'    => array(
							'active'     => true,
							'categories' => array(
								TagsModule::MEDIA_CATEGORY,
							),
						),
						'media_type' => 'video',
						'condition'  => array(
							'video_type' => 'hosted',
							'insert_url' => '',
						),
					)
				);

				$self->add_control(
					'external_url',
					array(
						'label'        => esc_html__( 'URL', 'alpha-core' ),
						'type'         => Controls_Manager::URL,
						'autocomplete' => false,
						'options'      => false,
						'label_block'  => true,
						'show_label'   => false,
						'dynamic'      => array(
							'active'     => true,
							'categories' => array(
								TagsModule::POST_META_CATEGORY,
								TagsModule::URL_CATEGORY,
							),
						),
						'media_type'   => 'video',
						'placeholder'  => esc_html__( 'Enter your URL', 'alpha-core' ),
						'condition'    => array(
							'video_type' => 'hosted',
							'insert_url' => 'yes',
						),
					)
				);

				$self->add_control(
					'video_options',
					array(
						'label'     => esc_html__( 'Video Options', 'alpha-core' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'before',
					)
				);

				$self->add_control(
					'video_autoplay',
					array(
						'label'     => esc_html__( 'Autoplay', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'default'   => 'yes',
						'condition' => array(
							'show_image_overlay!' => 'yes',
						),
					)
				);

				$self->add_control(
					'video_mute',
					array(
						'label' => esc_html__( 'Mute', 'alpha-core' ),
						'type'  => Controls_Manager::SWITCHER,
					)
				);

				$self->add_control(
					'video_loop',
					array(
						'label'     => esc_html__( 'Loop', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'condition' => array(
							'video_type!' => 'dailymotion',
						),
					)
				);

				$self->add_control(
					'video_controls',
					array(
						'label'     => esc_html__( 'Player Controls', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'  => esc_html__( 'Show', 'alpha-core' ),
						'default'   => 'yes',
						'condition' => array(
							'video_type!' => 'vimeo',
						),
					)
				);

				$self->add_control(
					'showinfo',
					array(
						'label'     => esc_html__( 'Video Info', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'  => esc_html__( 'Show', 'alpha-core' ),
						'default'   => 'yes',
						'condition' => array(
							'video_type' => array( 'dailymotion' ),
						),
					)
				);

				$self->add_control(
					'modestbranding',
					array(
						'label'     => esc_html__( 'Modest Branding', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'condition' => array(
							'video_type' => array( 'youtube' ),
							'controls'   => 'yes',
						),
					)
				);

				$self->add_control(
					'logo',
					array(
						'label'     => esc_html__( 'Logo', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'  => esc_html__( 'Show', 'alpha-core' ),
						'default'   => 'yes',
						'condition' => array(
							'video_type' => array( 'dailymotion' ),
						),
					)
				);

				$self->add_control(
					'control_color',
					array(
						'label'     => esc_html__( 'Controls Color', 'alpha-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'condition' => array(
							'video_type' => array( 'vimeo', 'dailymotion' ),
						),
					)
				);

				// YouTube.
				$self->add_control(
					'yt_privacy',
					array(
						'label'       => esc_html__( 'Privacy Mode', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'description' => esc_html__( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'alpha-core' ),
						'condition'   => array(
							'video_type' => 'youtube',
						),
					)
				);

				$self->add_control(
					'rel',
					array(
						'label'     => esc_html__( 'Suggested Videos', 'alpha-core' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => array(
							''    => esc_html__( 'Current Video Channel', 'alpha-core' ),
							'yes' => esc_html__( 'Any Video', 'alpha-core' ),
						),
						'condition' => array(
							'video_type' => 'youtube',
						),
					)
				);

				// Vimeo.
				$self->add_control(
					'vimeo_title',
					array(
						'label'     => esc_html__( 'Intro Title', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'  => esc_html__( 'Show', 'alpha-core' ),
						'default'   => 'yes',
						'condition' => array(
							'video_type' => 'vimeo',
						),
					)
				);

				$self->add_control(
					'vimeo_portrait',
					array(
						'label'     => esc_html__( 'Intro Portrait', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'  => esc_html__( 'Show', 'alpha-core' ),
						'default'   => 'yes',
						'condition' => array(
							'video_type' => 'vimeo',
						),
					)
				);

				$self->add_control(
					'vimeo_byline',
					array(
						'label'     => esc_html__( 'Intro Byline', 'alpha-core' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'  => esc_html__( 'Show', 'alpha-core' ),
						'default'   => 'yes',
						'condition' => array(
							'video_type' => 'vimeo',
						),
					)
				);

				$self->add_control(
					'show_image_overlay',
					array(
						'label'       => esc_html__( 'Image Overlay', 'alpha-core' ),
						'type'        => Controls_Manager::SWITCHER,
						'description' => esc_html__( 'Enable to show banner image as video overlay.', 'alpha-core' ),
						'label_off'   => esc_html__( 'Hide', 'alpha-core' ),
						'label_on'    => esc_html__( 'Show', 'alpha-core' ),
						'separator'   => 'before',
						'default'     => 'yes',
					)
				);

				$self->add_control(
					'lightbox',
					array(
						'label'              => esc_html__( 'Lightbox', 'alpha-core' ),
						'type'               => Controls_Manager::SWITCHER,
						'description'        => esc_html__( 'Enable to play video with popup.', 'alpha-core' ),
						'frontend_available' => true,
						'label_off'          => esc_html__( 'Off', 'alpha-core' ),
						'label_on'           => esc_html__( 'On', 'alpha-core' ),
						'condition'          => array(
							'show_image_overlay' => 'yes',
						),
					)
				);

			$self->end_controls_section();

			// Section Banner Style Options
			alpha_elementor_banner_style_controls( $self, $condition_value, false );
		}

		/**
		 * Print banner content in elementor section content template function
		 *
		 * @since 4.1
		 */
		public function section_addon_content_template( $self ) {
			?>
			<#
			if ( 'banner' == settings.use_as ) {
				extra_class += ' banner banner-fixed';
				overlay_class = '';
				settings.gap = 'no';

				if ( 'yes' == settings.parallax ||  settings.background_effect ) {
					extra_class += ' banner-img-hidden';
				}

				if ( 'yes' == settings.parallax ) {
					extra_class += ' parallax';
					let parallax_options = {
						'direction'      : settings.parallax_direction,
						'speed'          : settings.parallax_speed.size && 10 != settings.parallax_speed.size ? 10 / ( 10 - settings.parallax_speed.size ) : 1.5,
					};
					extra_attrs += " data-parallax-image='" + ( settings.banner_background_image.id ? settings.banner_background_image.url : (settings.background_image.url ? settings.background_image.url : settings.banner_background_image.url) ) + "' data-parallax-options='" + JSON.stringify(parallax_options) + "'";
				} else if ( 'yes' != settings.video_banner_switch ) {
					if ( settings.overlay ) {
						if ( 'light' == settings.overlay || 'dark' == settings.overlay || 'zoom' == settings.overlay ) {
							extra_class += ' overlay-' + settings.overlay;
						} else if ( 'zoom_light' == settings.overlay ) {
							extra_class += ' overlay-zoom overlay-light';
						} else if ( 'zoom_dark' == settings.overlay ) {
							extra_class += ' overlay-zoom overlay-dark';
						} else if ( '' !== settings.overlay ) {
							extra_class += ' overlay-wrapper';
							overlay_class = 'overlay-' + settings.overlay;
						}
					}
				}

				if ( 'yes' == settings.video_banner_switch ) {
					extra_class += ' video-banner';
				}

				<?php if ( $self->legacy_mode ) { ?>
					addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no" ' + wrapper_attrs + '>';
				<?php } else { ?>
					addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no ' + extra_class + '" ' + extra_attrs + '>';
				<?php } ?>

					<?php if ( $self->legacy_mode ) { ?>
						addon_html += '<!-- Begin .elementor-row --><div class="elementor-row' + extra_class + '" ' + extra_attrs + '>';
					<?php } ?>

					if ( 'yes' != settings.video_banner_switch && ( settings.background_effect || settings.particle_effect ) ){
						// Particle Effect
						let particle_effectClass   = 'particle-effect ';
						if ( settings.particle_effect ) {
							particle_effectClass += settings.particle_effect;
						}
						view.addRenderAttribute( 'particleClass', 'class', particle_effectClass );

						// Background Effect
						if ( 'yes' != settings.parallax ) {
							let background_effectClass = 'background-effect ';
							if ( settings.background_effect ) {
								background_effectClass += settings.background_effect;
							}

							view.addRenderAttribute( 'backgroundClass', 'class', background_effectClass );
						}

						if ( settings.banner_background_image.url || settings.background_image.url ) {
							let background_img = '';
							if ( settings.particle_effect && !settings.background_effect ) {
								background_img = '';
							} else if ( 'yes' != settings.parallax ) {
								background_img = 'background-image: url(' + ( settings.banner_background_image.id ? settings.banner_background_image.url : (settings.background_image.url ? settings.background_image.url : settings.banner_background_image.url) ) + '); background-size: cover;';
							}
							view.addRenderAttribute( 'backgroundClass', 'style', background_img );
						}
						addon_html += '<!-- Begin .background-effect-wrapper --><div class="background-effect-wrapper">';
						addon_html += '<!-- Begin .background-effect --><div ' + view.getRenderAttributeString( 'backgroundClass' ) + '>';

						if ( settings.particle_effect ) {
							addon_html += '<div ' + view.getRenderAttributeString( 'particleClass' ) + '></div>';
						}
						addon_html += '</div><!-- End .background-effect -->';
						addon_html += '</div><!-- End .background-effect-wrapper -->';
					}

					if ( overlay_class ) {
						addon_html += '<div class="' + overlay_class + ' overlay-effect"></div>';
					}

					if ( settings.banner_background_image.url || settings.background_image.url ) {
						addon_html += '<figure class="banner-img">' ;
							addon_html += '<img src="' + ( settings.banner_background_image.id ? settings.banner_background_image.url : (settings.background_image.url ? settings.background_image.url : settings.banner_background_image.url) ) + '" alt="<?php esc_attr_e( 'Banner', 'alpha-core' ); ?>">';
						addon_html += '</figure>';
					}

					if ( 'yes' == settings.video_banner_switch ) {
						view.addRenderAttribute( 'video_widget_wrapper', 'class', 'elementor-element elementor-widget-video alpha-section-video' );
						view.addRenderAttribute( 'video_widget_wrapper', 'data-element_type', 'widget' );
						view.addRenderAttribute( 'video_widget_wrapper', 'data-widget_type', 'video.default' );
						view.addRenderAttribute( 'video_widget_wrapper', 'data-settings', JSON.stringify( settings ) );

						view.addRenderAttribute( 'video_wrapper', 'class', 'elementor-wrapper' );
						if ( settings.show_image_overlay && settings.lightbox ) {
							view.addRenderAttribute( 'video_widget_wrapper', 'style', 'position: absolute; left: 0; right: 0; top: 0; bottom: 0;' );
							view.addRenderAttribute( 'video_wrapper', 'style', 'width: 100%; height: 100%;' );
						}
						view.addRenderAttribute( 'video_wrapper', 'class', 'elementor-open-' + ( settings.show_image_overlay && settings.lightbox ? 'lightbox' : 'inline' ) );

						addon_html += '<!-- Begin .elementor-widget-video --><div ' + view.getRenderAttributeString( 'video_widget_wrapper' ) + ' style="position: absolute;">';
							addon_html += '<!-- Begin .elementor-video --><div ' + view.getRenderAttributeString( 'video_wrapper' ) + '>';

						let urls = {
							'youtube': settings.youtube_url,
							'vimeo': settings.vimeo_url,
							'dailymotion': settings.dailymotion_url,
							'hosted': settings.hosted_url,
							'external': settings.external_url
						};

						let video_url = urls[settings.video_type],
							video_html = '';

						if ( 'hosted' == settings.video_type ) {
							if ( settings.insert_url ) {
								video_url = urls['external']['url'];
							} else {
								video_url = urls['hosted']['url'];
							}

							if ( video_url ) {
								if ( settings.start || settings.end ) {
									video_url += '#t=';
								}

								if ( settings.start ) {
									video_url += settings.start;
								}

								if ( settings.end ) {
									video_url += ',' + settings.end;
								}
							}
						}
						if ( video_url ) {

							view.addRenderAttribute( 'video_tag', 'class', 'elementor-video' );

							if ( 'hosted' == settings.video_type ) {
								var video_params = {},
									options = [ 'autoplay', 'loop', 'controls' ];

								for ( let i = 0; i < options.length; i ++ ) {
									if ( settings[ 'video_' + options[i] ] ) {
										video_params[ options[i] ] = '';
									}
								}

							if ( ! settings.show_image_overlay && settings.video_autoplay ) {
									video_params['autoplay'] = '';
								}
								if ( settings.video_loop ) {
									video_params['loop'] = '';
								}
								if ( settings.video_controls ) {
									video_params['controls'] = '';
								}

								if ( settings.video_mute ) {
									video_params.muted = 'muted';
								}

								view.addRenderAttribute( 'video_tag', 'src', video_url );

								let param_keys = Object.keys( video_params );

								for ( let i = 0; i < param_keys.length; i ++ ) {
									view.addRenderAttribute( 'video_tag', param_keys[i], video_params[param_keys[i]] );
								}
								if ( ! settings.show_image_overlay || ! settings.lightbox ) {
									addon_html += '<video ' + view.getRenderAttributeString( 'video_tag' ) + '></video>';
								}

							} else {
								view.addRenderAttribute( 'video_tag', 'src', video_url );
								if ( ! settings.show_image_overlay || ! settings.lightbox ) {
									addon_html += '<iframe ' + view.getRenderAttributeString( 'video_tag' ) + '></iframe>';
								}
							}

							if ( ( settings.background_image.url || settings.banner_background_image.url ) && 'yes' == settings.show_image_overlay ) {
									view.addRenderAttribute( 'image-overlay', 'class', 'elementor-custom-embed-image-overlay' );

									if ( settings.show_image_overlay && settings.lightbox ) {
										let lightbox_url = video_url,
											lightbox_options = {};

										lightbox_options = {
											'type'        : 'video',
											'videoType'   : settings.video_type,
											'url'         : lightbox_url,
											'modalOptions': {
												'entranceAnimation'       : settings.lightbox_content_animation,
												'entranceAnimation_tablet': settings.lightbox_content_animation_tablet,
												'entranceAnimation_mobile': settings.lightbox_content_animation_mobile,
												'videoAspectRatio'        : settings.aspect_ratio,
											},
										};

										if ( 'hosted' == settings.video_type ) {
											lightbox_options['videoParams'] = video_params;
										}

										view.addRenderAttribute( 'image-overlay', 'data-elementor-open-lightbox', 'yes' );
										view.addRenderAttribute( 'image-overlay', 'data-elementor-lightbox', JSON.stringify( lightbox_options ) );
										if ( settings.banner_background_image.url ) {
											view.addRenderAttribute( 'image-overlay-lightbox', 'src', settings.banner_background_image.url );
										} else {
											view.addRenderAttribute( 'image-overlay-lightbox', 'src', settings.background_image.url );
										}

									} else {
										view.addRenderAttribute( 'image-overlay', 'style', 'background-image: url(' + ( settings.banner_background_image.id ? settings.banner_background_image.url : (settings.background_image.url ? settings.background_image.url : settings.banner_background_image.url) ) + ');' );
									}

									addon_html += '<div ' + view.getRenderAttributeString( 'image-overlay' ) + '>';
										if ( settings.show_image_overlay && settings.lightbox ) {
											addon_html += '<img ' + view.getRenderAttributeString( 'image-overlay-lightbox' ) + '>';
										}
										if ( 'yes' == settings.show_play_icon ) {
											addon_html += '<div class="elementor-custom-embed-play" role="button">';
												addon_html += '<i class="eicon-play" aria-hidden="true"></i>';
												addon_html += '<span class="elementor-screen-only"></span>';
											addon_html += '</div>';
										}
									addon_html += '</div>';
								}
							}
							addon_html += '</div><!-- End .elementor-video -->';
						addon_html += '</div><!-- End .elementor-widget-video -->';
					}

					<?php if ( $self->legacy_mode ) { ?>
						addon_html += '</div>';
					<?php } ?>

				addon_html += '</div>';
			}
			#>
			<?php
		}

		/**
		 * Add render attributes for banner
		 *
		 * @since 4.1
		 */
		public function section_addon_attributes( $options, $self, $settings ) {
			if ( 'banner' == $settings['use_as'] ) {
				if ( 'yes' == $settings['video_banner_switch'] ) {
					global $alpha_section;
					$alpha_section['video'] = true;
					if ( 'yes' == $settings['lightbox'] ) {

						$video_url = $settings[ $settings['video_type'] . '_url' ];

						if ( 'hosted' == $settings['video_type'] ) {
							$video_url = $self->get_hosted_video_url();
						}
						if ( 'hosted' != $settings['video_type'] ) {
							$embed_params  = $self->get_embed_params();
							$embed_options = $self->get_embed_options();
						}
						if ( 'hosted' == $settings['video_type'] ) {
							$lightbox_url = $video_url;
						} else {
							$lightbox_url = Embed::get_embed_url( $video_url, $embed_params, $embed_options );
						}

						$lightbox_options = array(
							'type'         => 'video',
							'videoType'    => $settings['video_type'],
							'url'          => $lightbox_url,
							'modalOptions' => array(
								'id'                       => 'elementor-lightbox-' . $self->get_id(),
								'entranceAnimation'        => $settings['lightbox_content_animation'],
								'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
								'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
								'videoAspectRatio'         => $settings['aspect_ratio'],
							),
						);

						if ( 'hosted' == $settings['video_type'] ) {
							$lightbox_options['videoParams'] = $self->get_hosted_params();
						}
						$alpha_section['lightbox'] = $lightbox_options;
					}
				}
			}
			return $options;
		}

		/**
		 * Render banner HTML
		 *
		 * @since 4.1
		 */
		public function section_addon_render( $self, $settings ) {
			if ( 'banner' == $settings['use_as'] ) { // if using as banner

				wp_enqueue_style( 'alpha-banner', alpha_core_framework_uri( '/widgets/banner/banner' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

				$extra_class     = ' banner banner-fixed';
				$settings['gap'] = 'no';

				if ( 'yes' == $settings['parallax'] || $settings['background_effect'] ) {
					$extra_class .= ' banner-img-hidden';
				}

				$extra_attr    = '';
				$overlay_class = '';
				if ( 'yes' == $settings['parallax'] ) { // if parallax
					wp_enqueue_script( 'jquery-skrollr' );
					$extra_class     .= ' parallax';
					$extra_attr      .= ' data-parallax-image=' . esc_url( $settings['banner_background_image']['id'] ? $settings['banner_background_image']['url'] : ( $settings['background_image']['url'] ? $settings['background_image']['url'] : $settings['banner_background_image']['url'] ) ) . '';
					$parallax_options = array(
						'direction' => $settings['parallax_direction'],
						'speed'     => $settings['parallax_speed']['size'] && 10 != $settings['parallax_speed']['size'] && 10 != $settings['parallax_speed']['size'] ? 10 / ( 10 - $settings['parallax_speed']['size'] ) : 1.5,
					);
					$extra_attr      .= ' data-parallax-options=' . json_encode( $parallax_options );
				} elseif ( 'yes' != $settings['video_banner_switch'] ) {
					// Banner Overlay
					if ( 'effect-' != substr( $settings['overlay'], 0, -1 ) ) {
						$extra_class .= ' ' . alpha_get_overlay_class( $settings['overlay'] );
					} else {
						$extra_class  .= ' overlay-wrapper';
						$overlay_class = alpha_get_overlay_class( $settings['overlay'] );
					}
				}

				if ( 'yes' == $settings['video_banner_switch'] ) {
					$extra_class .= ' video-banner';
				}

				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );

				if ( $self->legacy_mode ) :
					?>
					<!-- Begin .elementor-container -->
					<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no">
				<?php else : ?>
					<!-- Begin .elementor-container -->
					<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no <?php echo esc_attr( $extra_class ); ?>"<?php echo ! $extra_attr ? '' : esc_attr( $extra_attr ); ?>>
				<?php endif; ?>

				<?php if ( $self->legacy_mode ) : ?>
					<!-- Begin .elementor-row -->
					<div class="elementor-row <?php echo esc_attr( $extra_class ); ?>"<?php echo ! $extra_attr ? '' : esc_attr( $extra_attr ); ?>>
					<?php
				endif;

				// Background Effect
				if ( 'yes' != $settings['video_banner_switch'] && ( $settings['background_effect'] || $settings['particle_effect'] ) ) {
					echo '<div class="background-effect-wrapper">';

					if ( ! empty( $settings['banner_background_image'] ) || ! empty( $settings['background_image'] ) ) {
						if ( $settings['particle_effect'] && '' == $settings['background_effect'] ) {
							$background_img = '';
						} elseif ( 'yes' != $settings['parallax'] ) {
							$background_img = esc_url( $settings['banner_background_image']['id'] ? $settings['banner_background_image']['url'] : ( $settings['background_image']['url'] ? $settings['background_image']['url'] : $settings['banner_background_image']['url'] ) );
						}

						// Background Effect
						$background_class = '';
						if ( $settings['background_effect'] && 'yes' != $settings['parallax'] ) {
							$background_class = $settings['background_effect'];
						}

						// Particle Effect
						$particle_class = '';
						if ( $settings['particle_effect'] ) {
							$particle_class = $settings['particle_effect'];
						}

						echo '<div class="background-effect ' . esc_attr( $background_class ) . '"' . ( ! empty( $background_img ) ? ( ' style="background-image: url(' . $background_img . '); background-size: cover;">' ) : '>' );

						if ( $settings['particle_effect'] ) {
							echo '<div class="particle-effect ' . esc_attr( $particle_class ) . '"></div>';
						}

						echo '</div>';
					}

					echo '</div>';
				}

				if ( $overlay_class ) {
					echo '<div class="' . esc_attr( $overlay_class ) . ' overlay-effect"></div>';
				}

				// Banner Image
				if ( 'banner' == $settings['use_as'] && ( isset( $settings['banner_background_image'] ) || isset( $settings['background_image'] ) ) ) {
					$banner_img_id = $settings['banner_background_image']['id'] ? $settings['banner_background_image']['id'] : $settings['background_image']['id'];

					if ( $banner_img_id ) {
						$attr = array();
						if ( $settings['banner_background_color'] ) {
							$attr['style'] = 'background-color:' . $settings['banner_background_color'];
						}

						// Generate 'srcset' and 'sizes'
						$image_meta         = wp_get_attachment_metadata( $banner_img_id );
						$breakpoints        = new Breakpoints_Manager();
						$active_breakpoints = array_keys( $breakpoints->get_active_breakpoints() );
						$breakpoints_config = $breakpoints->get_breakpoints_config();
						$min_heights        = array();

						foreach ( $active_breakpoints as $breakpoint ) {
							if ( isset( $settings[ 'banner_min_height_' . $breakpoint ] ) && 'px' == $settings[ 'banner_min_height_' . $breakpoint ]['unit'] && $settings[ 'banner_min_height_' . $breakpoint ]['size'] ) {
								$min_heights[ $breakpoints_config[ $breakpoint ]['value'] + 1 ] = $settings[ 'banner_min_height_' . $breakpoint ]['size'];
							}
						}

						if ( isset( $settings['banner_min_height'] ) && 'px' == $settings['banner_min_height']['unit'] && $settings['banner_min_height']['size'] ) {
							$min_heights[9999] = $settings['banner_min_height']['size'];
						}

						if ( is_array( $image_meta ) && is_array( $image_meta['sizes'] ) && ! empty( $image_meta['width'] ) ) {
							$ratio = $image_meta['height'] / $image_meta['width'];
							foreach ( $min_heights as $width => $min_height ) {
								foreach ( $image_meta['sizes'] as $key => $size ) {
									if ( $width >= $size['width'] && $size['width'] * (float) $ratio < (int) $min_height ) {
										unset( $image_meta['sizes'][ $key ] );
									}
								}
							}
						}

						$srcset = wp_get_attachment_image_srcset( $banner_img_id, 'full', $image_meta );
						$sizes  = wp_get_attachment_image_sizes( $banner_img_id, 'full', $image_meta );

						if ( $srcset ) {
							$attr['srcset'] = $srcset;
							$attr['sizes']  = $sizes;
						} else {
							$attr['srcset'] = 'alpha_banner_empty_srcset';
						}
						?>
					<figure class="banner-img">
						<?php
						$content = wp_get_attachment_image( $banner_img_id, 'full', false, $attr );
						echo class_exists( 'Alpha_LazyLoad_Images' ) ? Alpha_LazyLoad_Images::add_image_placeholders( $content ) : $content;
						?>
					</figure>
						<?php
					} elseif ( ! empty( $settings['banner_background_image']['url'] ) || ! empty( $settings['background_image']['url'] ) ) {
						?>
						<figure class="banner-img">
							<?php echo '<img src="' . esc_url( $settings['banner_background_image']['id'] ? $settings['banner_background_image']['url'] : ( $settings['background_image']['url'] ? $settings['background_image']['url'] : $settings['banner_background_image']['url'] ) ) . '" alt="' . esc_attr__( 'Default Image', 'alpha-core' ) . '" width="1400" height="753"' . ( $settings['banner_background_color'] ? ( ' style="background-color:' . $settings['banner_background_color'] ) . '"' : '' ) . '>'; ?>
						</figure>
						<?php
					}
				}

				if ( 'yes' == $settings['video_banner_switch'] ) :

					$video_url = $settings[ $settings['video_type'] . '_url' ];

					if ( 'hosted' == $settings['video_type'] ) {
						$video_url = $self->get_hosted_video_url();
					}

					if ( empty( $video_url ) ) {
						return;
					}

					if ( 'hosted' == $settings['video_type'] ) {
						ob_start();

						$self->render_hosted_video();

						$video_html = ob_get_clean();
					} else {
						$embed_params = $self->get_embed_params();

						$embed_options = $self->get_embed_options();

						$video_html = Embed::get_embed_html( $video_url, $embed_params, $embed_options );
					}

					if ( empty( $video_html ) ) {
						echo esc_url( $video_url );

						return;
					}

					$self->add_render_attribute( 'video_widget_wrapper', 'class', 'elementor-element elementor-widget-video alpha-section-video' );
					$self->add_render_attribute( 'video_widget_wrapper', 'data-element_type', 'widget' );
					$self->add_render_attribute( 'video_widget_wrapper', 'data-widget_type', 'video.default' );
					$self->add_render_attribute( 'video_widget_wrapper', 'data-settings', wp_json_encode( $self->get_frontend_settings() ) );

					$self->add_render_attribute( 'video_wrapper', 'class', 'elementor-wrapper' );

					$self->add_render_attribute( 'video_wrapper', 'class', 'elementor-open-' . ( $settings['lightbox'] ? 'lightbox' : 'inline' ) );
					?>

					<!-- Begin .background-effect-wrapper -->
					<div <?php $self->print_render_attribute_string( 'video_widget_wrapper' ); ?>>
						<!-- Begin .background-effect -->
						<div <?php $self->print_render_attribute_string( 'video_wrapper' ); ?>>
							<?php
							if ( ! $settings['lightbox'] ) {
								echo alpha_escaped( $video_html ); // XSS ok.
							}
							global $alpha_section;
							if ( $self->has_image_overlay() ) {
								if ( ! $settings['lightbox'] && isset( $alpha_section['video_btn'] ) ) {
									$self->add_render_attribute( 'background_image', 'class', 'elementor-custom-embed-image-overlay no-event' );
								} else {
									$self->add_render_attribute( 'background_image', 'class', 'elementor-custom-embed-image-overlay' );
								}

								if ( $settings['lightbox'] ) {
									if ( ! isset( $alpha_section['video_btn'] ) ) {
										if ( 'hosted' == $settings['video_type'] ) {
											$lightbox_url = $video_url;
										} else {
											$lightbox_url = Embed::get_embed_url( $video_url, $embed_params, $embed_options );
										}

										$lightbox_options = $alpha_section['lightbox'];

										$self->add_render_attribute(
											'background_image',
											array(
												'data-elementor-open-lightbox' => 'yes',
												'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
											)
										);

										if ( Plugin::$instance->editor->is_edit_mode() ) {
											$self->add_render_attribute(
												'background_image',
												array(
													'class' => 'elementor-clickable',
												)
											);
										}
									}
								} else {
									$image_overlay = wp_get_attachment_image_src( $settings['banner_background_image']['id'] ? $settings['banner_background_image']['id'] : $settings['background_image']['id'], 'full' );
									$image_overlay = $image_overlay ? $image_overlay[0] : ( $settings['background_image']['url'] ? $settings['background_image']['url'] : $settings['banner_background_image']['url'] );
									$self->add_render_attribute( 'background_image', 'style', 'background-image: url(' . $image_overlay . ');' );
								}
								?>
								<div <?php $self->print_render_attribute_string( 'background_image' ); ?>>
									<?php
									if ( $settings['lightbox'] && ! isset( $alpha_section['video_btn'] ) ) {
										if ( ! empty( $settings['banner_background_image']['url'] ) ) {
											echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'banner_background_image' );
										} else {
											echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'background_image' );
										}
									}
									?>
								</div>
							<?php } ?>
					<?php
				endif;
			}
		}

		/**
		 * Render banner HTML after elementor section render
		 *
		 * @since 4.1
		 */
		public function section_addon_after_render( $self, $settings ) {
			if ( 'banner' == $settings['use_as'] ) {
				if ( $self->legacy_mode ) :
					?>
				</div><!-- End Banner Wrapper & .elementor-row -->
					<?php
				endif;
				?>
				</div><!-- End .elementor-container -->
				<?php

				if ( 'yes' == $settings['video_banner_switch'] && $self->get_render_attributes( 'video_widget_wrapper', 'class' ) ) {
					?>
					</div><!-- End .background-effect -->
					</div><!-- End .background-effect-wrapper -->
					<?php
				}

				?>
				</<?php echo esc_html( $self->get_html_tag() ); ?>>
				<?php
				unset( $GLOBALS['alpha_section'] );
			}
		}

		/**
		 * Register banner addon to column element
		 *
		 * @since 4.1
		 */
		public function register_column_addon( $addons ) {
			$addons['banner_layer'] = esc_html__( 'Banner Layer', 'alpha-core' );
			return $addons;
		}

		/**
		 * Add banner controls to column element
		 *
		 * @since 4.1
		 */
		public function add_column_controls( $self, $condition_value ) {

			// Update Elementor Controls
			$self->update_control(
				'_inline_size',
				array(
					'condition' => array(
						$condition_value . '!' => 'banner_layer',
					),
				)
			);
			$self->update_control(
				'content_position',
				array(
					'condition' => array(
						$condition_value . '!' => 'banner_layer',
					),
				)
			);
			$self->update_control(
				'align',
				array(
					'condition' => array(
						$condition_value . '!' => 'banner_layer',
					),
				)
			);

			alpha_elementor_banner_layer_layout_controls( $self, $condition_value );
		}

		/**
		 * Print banner in elementor column content template function
		 *
		 * @since 4.1
		 */
		public function column_addon_content_template( $self ) {
			$is_legacy_mode_active = ! alpha_elementor_if_dom_optimization();
			?>
			<#
			if ( 'banner_layer' == settings.use_as ) {
				wrapper_attrs += ' data-banner-class="banner-content ' + (settings.banner_origin ? settings.banner_origin : '') + '"';
				#>
				<?php if ( $is_legacy_mode_active ) { ?>
					<# addon_html += '<div class="elementor-column-wrap" ' + wrapper_attrs + '>'; #>
				<?php } else { ?>
					<# addon_html += '<div class="elementor-widget-wrap" ' + wrapper_attrs + '>'; #>
				<?php } ?>
				<#
				addon_html += '<div class="elementor-background-overlay"></div>';
				#>
				<?php if ( $is_legacy_mode_active ) { ?>
					<#
					addon_html += '<div class="elementor-widget-wrap"></div>';
					#>
				<?php } ?>
				<#
				addon_html += '</div>';
			}
			#>
			<?php
		}
	}
}

/**
 * Create instance
 *
 * @since 4.1
 */
Alpha_Section_Banner_Elementor_Widget_Addon::get_instance();
