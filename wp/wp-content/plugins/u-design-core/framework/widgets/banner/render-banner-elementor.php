<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Banner Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'stretch_height'             => '',
			'banner_item_list'           => array(),
			'banner_origin'              => '',
			'banner_wrap'                => '',
			'parallax'                   => '',
			'_content_animation'         => '',
			'content_animation_duration' => '',
			'_content_animation_delay'   => '',

			// For elementor inline editing
			'self'                       => null,
		),
		$atts
	)
);

$banner_class         = array( 'banner', 'banner-fixed' );
$banner_overlay_class = array( 'overlay-effect' );

if ( $atts['overlay'] ) {
	if ( 'effect-' === substr( $atts['overlay'], 0, -1 ) ) {
		$banner_overlay_class[] = alpha_get_overlay_class( $atts['overlay'] );
		$banner_class[]         = 'overlay-wrapper';
	} else {
		$banner_class[] = alpha_get_overlay_class( $atts['overlay'] );
	}
}

$wrapper_class = array( 'banner-content' );

if ( 'yes' == $parallax || $atts['background_effect'] ) {
	$banner_class[] = 'banner-img-hidden';
}

// Banner Origin
$wrapper_class[] = $banner_origin;

if ( 'yes' == $stretch_height ) {
	$banner_class[] = 'banner-stretch';
}

// Parallax
if ( 'yes' == $parallax ) {
	wp_enqueue_script( 'jquery-skrollr' );
	$banner_class[]   = 'parallax';
	$parallax_img     = esc_url( $atts['banner_background_image']['url'] );
	$parallax_options = array(
		'direction' => $atts['parallax_direction'],
		'speed'     => $atts['parallax_speed']['size'] && 10 != $atts['parallax_speed']['size'] ? 10 / ( 10 - $atts['parallax_speed']['size'] ) : 1.5,
	);
	$parallax_options = "data-parallax-options='" . json_encode( $parallax_options ) . "'";
	echo '<div class="' . esc_attr( implode( ' ', $banner_class ) ) . '" data-parallax-image="' . $parallax_img . '" ' . $parallax_options . '>';
} else {
	echo  '<div class="' . esc_attr( implode( ' ', $banner_class ) ) . '">';
}

// Background Effect
if ( $atts['background_effect'] || $atts['particle_effect'] ) {
	echo '<div class="background-effect-wrapper">';

	if ( ! empty( $atts['banner_background_image'] ) ) {
		if ( $atts['particle_effect'] && '' == $atts['background_effect'] ) {
			$background_img = '';
		} elseif ( 'yes' != $parallax ) {
			$background_img = esc_url( $atts['banner_background_image']['url'] );
		}

		// Background Effect
		$background_class[] = '';
		if ( $atts['background_effect'] && 'yes' != $parallax ) {
			$background_class[] = $atts['background_effect'];
		}

		// Particle Effect
		$particle_class[] = '';
		if ( $atts['particle_effect'] ) {
			$particle_class[] = $atts['particle_effect'];
		}

		echo '<div class="background-effect' . esc_attr( implode( ' ', $background_class ) ) . '"' . ( ! empty( $background_img ) ? ( ' style="background-image: url(' . $background_img . '); background-size: cover;">' ) : '>' );

		if ( $atts['particle_effect'] ) {
			echo '<div class="particle-effect' . esc_attr( implode( ' ', $particle_class ) ) . '"></div>';
		}

		echo '</div>';
	}

	echo '</div>';
}

if ( count( $banner_overlay_class ) > 1 ) {
	echo '<div class="' . esc_attr( implode( ' ', $banner_overlay_class ) ) . '"></div>';
}

/* Image */

if ( isset( $atts['banner_background_image']['id'] ) && $atts['banner_background_image']['id'] ) {
	$banner_img_id = $atts['banner_background_image']['id'];
	?>
	<figure class="banner-img">
		<?php
		$attr = array();
		if ( $atts['banner_background_color'] ) {
			$attr['style'] = 'background-color:' . $atts['banner_background_color'];
		}
		// Display full image for wide banner (width > height * 3).
		$image = wp_get_attachment_image_src( $banner_img_id, 'full' );
		if ( ! empty( $image[1] ) && ! empty( $image[2] ) && $image[2] && $image[1] / $image[2] > 3 ) {
			$attr['srcset'] = $image[0];
		}
		echo wp_get_attachment_image( $banner_img_id, 'full', false, $attr );
		?>
	</figure>
	<?php
} elseif ( isset( $atts['banner_background_image']['url'] ) && $atts['banner_background_image']['url'] ) {
	?>
	<figure class="banner-img">
		<?php echo '<img src="' . esc_url( $atts['banner_background_image']['url'] ) . '" alt="' . esc_html__( 'Default Image', 'alpha-core' ) . '" width="1400" height="753">'; ?>
	</figure>
	<?php
}
/* ---- Hotspot part ----*/
foreach ( $banner_item_list as $key => $item ) {
	if ( 'hotspot' == $item['banner_item_type'] ) {
		extract( // @codingStandardsIgnoreLine
			shortcode_atts(
				array(
					// Global Options
					'_id'                    => '',
					'_animation'             => '',
					'animation_duration'     => '',
					'_animation_delay'       => '',
					'banner_item_type'       => '',
					// Hotspot Options
					'hotspot_type'           => 'html',
					'hotspot_html'           => '',
					'hotspot_block'          => '',
					'hotspot_link'           => '#',
					'hotspot_product'        => '',
					'hotspot_image'          => array(),
					'hotspot_icon'           => '',
					'hotspot_popup_position' => 'top',
					'hotspot_el_class'       => '',
					'hotspot_effect'         => '',
					'hotspot_image_size'     => '',
				),
				$item
			)
		);
		$class = array( 'elementor-repeater-item-' . $_id );
		if ( ! empty( $hotspot_el_class ) ) {
			$class[] = $hotspot_el_class;
		}
		$atts['type']           = $hotspot_type;
		$atts['html']           = $hotspot_html;
		$atts['block']          = $hotspot_block;
		$atts['link']           = $hotspot_link;
		$atts['product']        = $hotspot_product;
		$atts['image']          = $hotspot_image;
		$atts['icon']           = $hotspot_icon;
		$atts['popup_position'] = $hotspot_popup_position;
		$atts['el_class']       = implode( ' ', $class );
		$atts['effect']         = $hotspot_effect;
		$atts['image_size']     = $hotspot_image_size;
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/hotspot/render-hotspot-elementor.php' );
	}
}

if ( $banner_wrap ) {
	echo '<div class="' . esc_attr( $banner_wrap ) . '">'; // Start banner-wrap: container, container-fluid
}

/* Showing Items */
echo '<div class="' . esc_attr( implode( ' ', $wrapper_class ) ) . '">'; // Start banner-content

/* Content Animation */
$settings = array( '' );
if ( $_content_animation ) {
	$settings = array(
		'_animation'       => $_content_animation,
		'_animation_delay' => $_content_animation_delay ? $_content_animation_delay : 0,
	);
	$settings = " data-settings='" . esc_attr( json_encode( $settings ) ) . "'";
	echo '<div class="appear-animate' . ( empty( $content_animation_duration ) ? '' : ' animated-' . esc_attr( $content_animation_duration ) ) . '" ' . $settings . '>';
}
foreach ( $banner_item_list as $key => $item ) {

	$class = array( 'banner-item' );

	extract( // @codingStandardsIgnoreLine
		shortcode_atts(
			array(
				// Global Options
				'_id'                    => '',
				'banner_item_display'    => '',
				'banner_item_aclass'     => '',
				'_animation'             => '',
				'animation_duration'     => '',
				'_animation_delay'       => '',

				// Text Options
				'banner_item_type'       => '',
				'banner_text_tag'        => 'h2',
				'banner_text_content'    => '',

				// Image Options
				'banner_image'           => '',
				'banner_image_size'      => 'full',
				'img_link'               => esc_html__( 'https://your-link.com', 'alpha-core' ),

				// Button Options
				'banner_btn_text'        => '',
				'banner_btn_link'        => '',
				'banner_btn_aclass'      => '',

				// Hotspot Options
				'hotspot_type'           => 'html',
				'hotspot_html'           => '',
				'hotspot_block'          => '',
				'hotspot_link'           => '#',
				'hotspot_product'        => '',
				'hotspot_image'          => array(),
				'hotspot_icon'           => '',
				'hotspot_popup_position' => 'top',
				'hotspot_el_class'       => '',
				'hotspot_effect'         => '',
				'hotspot_image_size'     => '',
			),
			$item
		)
	);
	$class[] = 'elementor-repeater-item-' . $_id;

	// Custom Class
	if ( $banner_item_aclass ) {
		$class[] = $banner_item_aclass;
	}

	// Animation
	$settings = '';
	if ( $_animation ) {
		$class[] = 'appear-animate';
		if ( ! empty( $animation_duration ) ) {
			$class[] = 'animated-' . $animation_duration;
		}
		$settings = array(
			'_animation'       => $_animation,
			'_animation_delay' => $_animation_delay ? $_animation_delay : 0,
		);
		$settings = " data-settings='" . esc_attr( json_encode( $settings ) ) . "'";
	}

	// Item display type
	if ( 'yes' != $banner_item_display && 'hotspot' != $banner_item_type ) {
		$class[] = 'item-block';
	} else {
		$class[] = 'item-inline';
	}

	$floating_options = alpha_get_elementor_addon_options( $item );
	$floating_attrs   = '';
	if ( ! empty( $floating_options ) ) {

		if ( 1 < count( $floating_options ) ) { // Scroll, Track Effect
			$settings_attrs = array(
				'type'          => $item['alpha_floating'],
				'm_track_dir'   => ! empty( $item['alpha_m_track_dir'] ) ? $item['alpha_m_track_dir'] : '',
				'm_track_speed' => ! empty( $item['alpha_m_track_speed']['size'] ) ? $item['alpha_m_track_speed']['size'] : 0.5,
				'scroll_size'   => ! empty( $item['alpha_scroll_size']['size'] ) ? $item['alpha_scroll_size']['size'] : 50,
				'scroll_stop'   => $item['alpha_scroll_stop'],
			);

			foreach ( $floating_options as $key => $value ) {
				$floating_attrs .= $key . "='" . $value . "' ";
			}
		} elseif ( ! empty( $floating_options['class'] ) ) { // Transform Effect
			$class[] = $floating_options['class'];
		}

		echo '<div class="' . esc_attr( implode( ' ', $class ) ) . '" ' . $settings . ( isset( $settings_attrs ) ? ' data-floating=' . json_encode( $settings_attrs ) : '' ) . '>'; // Start Banner Item Wrapper if floating
		$class     = array();
		$setttings = '';

		echo '<div class="floating-wrapper layer-wrapper elementor-repeater-item-' . $_id . '-wrapper" ' . $floating_attrs . '>'; // Start floating-wrapper

		if ( 0 === strpos( $item['alpha_floating'], 'mouse_tracking' ) ) {
			echo '<div class="layer">'; // Start layer.
		}
	}
	if ( 'text' == $banner_item_type ) { // Text

		$class[] = 'text';

		if ( $self ) {
			$repeater_setting_key = $self->get_repeater_setting_key( 'banner_text_content', 'banner_item_list', $key );
			$self->add_render_attribute( $repeater_setting_key, 'class', $class );
			if ( ALPHA_NAME . '_widget_banner' == $self->get_name() && '' == $item['alpha_floating'] ) {
				$self->add_inline_editing_attributes( $repeater_setting_key );
			}
		}

		printf(
			'<%1$s ' . ( $self ? $self->get_render_attribute_string( $repeater_setting_key ) : '' ) . $settings . '>%2$s</%1$s>',
			esc_attr( $banner_text_tag ),
			do_shortcode( alpha_strip_script_tags( $banner_text_content ) )
		);
	} elseif ( 'image' == $banner_item_type ) { // Image
		echo '<div class="' . esc_attr( implode( ' ', $class ) ) . ' image" ' . $settings . '>';
		if ( ! empty( $img_link['url'] ) ) {
			$attrs           = [];
			$attrs['href']   = ! empty( $img_link['url'] ) ? esc_url( $img_link['url'] ) : '#';
			$attrs['target'] = ! empty( $img_link['is_external'] ) ? '_blank' : '';
			$attrs['rel']    = ! empty( $img_link['nofollow'] ) ? 'nofollow' : '';
			if ( ! empty( $img_link['custom_attributes'] ) ) {
				foreach ( explode( ',', $img_link['custom_attributes'] ) as $attr ) {
					$key   = explode( '|', $attr )[0];
					$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
					if ( isset( $attrs[ $key ] ) ) {
						$attrs[ $key ] .= ' ' . $value;
					} else {
						$attrs[ $key ] = $value;
					}
				}
			}
			$link_attrs = '';
			foreach ( $attrs as $key => $value ) {
				if ( ! empty( $value ) ) {
					$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
				}
			}

			echo '<a ' . $link_attrs . '>';
		}
		if ( empty( $banner_image['id'] ) ) {
			echo '<img src="' . esc_url( $banner_image['url'] ) . '" alt="' . esc_attr__( 'Default Image', 'alpha-core' ) . '" width="1200" height="800">';
		} else {
			echo wp_get_attachment_image(
				$banner_image['id'],
				$banner_image_size,
				false,
				''
			);
		}
		if ( ! empty( $img_link['url'] ) ) {
			echo '</a>';
		}
		echo '</div>';

	} elseif ( 'button' == $banner_item_type ) { // Button

		$class[] = ' btn';
		if ( $banner_btn_aclass ) {
			$class[] = $banner_btn_aclass;
		}
		if ( ! $banner_btn_text ) {
			$banner_btn_text = esc_html__( 'Click here', 'alpha-core' );
		}

		if ( $self ) {
			$repeater_setting_key = $self->get_repeater_setting_key( 'banner_btn_text', 'banner_item_list', $key );
			if ( ALPHA_NAME . '_widget_banner' == $self->get_name() && '' == $item['alpha_floating'] ) {
				$self->add_inline_editing_attributes( $repeater_setting_key );
			}
			$banner_btn_text = alpha_widget_button_get_label( $item, $self, $banner_btn_text, $repeater_setting_key );
		}

		$class[] = implode( ' ', alpha_widget_button_get_class( $item ) );

		$attrs           = [];
		$attrs['href']   = ! empty( $banner_btn_link['url'] ) ? esc_url( $banner_btn_link['url'] ) : '#';
		$attrs['target'] = ! empty( $banner_btn_link['is_external'] ) ? '_blank' : '';
		$attrs['rel']    = ! empty( $banner_btn_link['nofollow'] ) ? 'nofollow' : '';
		if ( ! empty( $banner_btn_link['custom_attributes'] ) ) {
			foreach ( explode( ',', $banner_btn_link['custom_attributes'] ) as $attr ) {
				$key   = explode( '|', $attr )[0];
				$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
				if ( isset( $attrs[ $key ] ) ) {
					$attrs[ $key ] .= ' ' . $value;
				} else {
					$attrs[ $key ] = $value;
				}
			}
		}
		$link_attrs = '';
		foreach ( $attrs as $key => $value ) {
			if ( ! empty( $value ) ) {
				$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
			}
		}

		printf( '<a class="' . esc_attr( implode( ' ', $class ) ) . '" ' . $link_attrs . $settings . '>%1$s</a>', alpha_strip_script_tags( $banner_btn_text ) );
	} elseif ( 'hotspot' == $banner_item_type ) {
		wp_enqueue_style( 'alpha-hotspot', alpha_core_framework_uri( '/widgets/hotspot/hotspot' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
	} else {
		$class[] = 'divider-wrap';
		echo '<div class="' . esc_attr( implode( ' ', $class ) ) . '" ' . $settings . '><hr class="divider" /></div>';
	}

	if ( ! empty( $floating_options ) ) {
		if ( 0 === strpos( $item['alpha_floating'], 'mouse_tracking' ) ) {
			echo '</div>'; // End layer.
		}
		echo '</div>'; // End floating-wrapper
		echo '</div>'; // End Banner Item Wrapper if floating
	}
}
if ( $_content_animation ) {
	echo '</div>';
}
echo '</div>'; // End banner-content

if ( $banner_wrap ) {
	echo '</div>'; // End banner-wrap
}

echo  '</div>';
