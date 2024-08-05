<?php

/**
 * Alpha Custom Fonts Addons
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since    1.2.1
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Custom_Fonts' ) ) {
	class Alpha_Custom_Fonts extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 1.2.1
		 */
		public function __construct() {
			// Add theme options
			add_filter( 'alpha_customize_fields', array( $this, 'add_customize_fields' ) );

			// Allow uploading font file types
			if ( current_user_can( 'manage_options' ) ) {
				add_filter( 'upload_mimes', array( $this, 'mime_types' ) );
				add_filter( 'wp_check_filetype_and_ext', array( $this, 'update_mime_types' ), 10, 3 );
			}

			// Merge with dynamic styles
			add_filter( 'alpha_dynamic_style', array( $this, 'add_font_css' ) );

			// Add custom fonts to kirki control
			add_filter( 'alpha_kirki_typo_control_choices', array( $this, 'add_kirki_font_params' ) );

			// Add custom fonts to elementor control
			add_filter( 'elementor/fonts/groups', array( $this, 'add_elementor_font_groups' ) );
			add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_elementor_font_params' ) );

		}

		/**
		 * Add fields for custom fonts
		 *
		 * @param {Array} $fields
		 *
		 * @since 1.2.1
		 */
		public function add_customize_fields( $fields ) {
			// Custom Fonts Upload
			$fields['cs_typo_custom_title'] = array(
				'section' => 'typo',
				'type'    => 'custom',
				'default' => '<h3 class="options-custom-title">' . esc_html__( 'Custom Fonts', 'alpha-core' ) . '</h3>',
			);
			$fields['cs_typo_custom_desc']  = array(
				'section' => 'typo',
				'type'    => 'custom',
				'default' => '<p style="margin: 0;">' . esc_html__( 'Upload custom font. All files are not necessary but are recommended for full browser support. You can upload as many custom fonts as you need. Click the "Add new custom font" button for additional upload boxes.', 'alpha-core' ) . '</p>',
			);
			$fields['typo_user_custom']     = array(
				'section'   => 'typo',
				'type'      => 'repeater',
				'ms_label'  => esc_html__( 'Custom Fonts', 'alpha-core' ),
				'row_label' => array(
					'type'  => 'field',
					'value' => esc_attr__( 'custom font', 'alpha-core' ),
					'field' => 'name',
				),
				'default'   => array(),
				'fields'    => array(
					'name'   => array(
						'type'  => 'text',
						'label' => esc_html__( 'Font Name', 'alpha-core' ),
					),
					'woff2'  => array(
						'type'  => 'upload',
						'label' => esc_html__( 'WOFF2', 'alpha-core' ),
					),
					'woff'   => array(
						'type'  => 'upload',
						'label' => esc_html__( 'WOFF', 'alpha-core' ),
					),
					'ttf'    => array(
						'type'  => 'upload',
						'label' => esc_html__( 'TTF', 'alpha-core' ),
					),
					'SVG'    => array(
						'type'  => 'upload',
						'label' => esc_html__( 'SVG', 'alpha-core' ),
					),
					'weight' => array(
						'type'  => 'text',
						'label' => esc_html__( 'Font Weight', 'alpha-core' ),
					),
				),
			);

			return $fields;
		}

		/**
		 * Add options to kirki typography control
		 *
		 * @param {Array} $params
		 *
		 * @since 1.2.1
		 */
		public function add_kirki_font_params( $params ) {

			if ( ! function_exists( 'alpha_get_option' ) ) {
				return $params;
			}

			$custom_fonts = alpha_get_option( 'typo_user_custom' );

			if ( is_array( $custom_fonts ) && count( $custom_fonts ) ) {
				$choices_map = array();
				foreach ( $custom_fonts as $font ) {
					if ( isset( $font['name'] ) ) {
						$choices_map[] = array(
							'id'   => $font['name'],
							'text' => $font['name'],
						);
					}
				}

				if ( $choices_map ) {
					$params['fonts'] = array(
						'families' => array(
							'custom' => array(
								'text'     => esc_html__( 'Custom Fonts', 'alpha-core' ),
								'children' => $choices_map,
							),
						),
					);
				}
			}

			return $params;
		}

		/**
		 * Add 'Custom' group to elementor typography control
		 *
		 * @param {Array} $groups
		 *
		 * @since 1.2.1
		 */
		public function add_elementor_font_groups( $groups ) {

			if ( ! function_exists( 'alpha_get_option' ) ) {
				return $groups;
			}

			$groups['custom'] = esc_html__( 'Custom', 'alpha-core' );
			return $groups;
		}

		/**
		 * Add options to elementor typography control
		 *
		 * @param {Array} $params
		 *
		 * @since 1.2.1
		 */
		public function add_elementor_font_params( $params ) {

			if ( ! function_exists( 'alpha_get_option' ) ) {
				return $params;
			}

			$custom_fonts = alpha_get_option( 'typo_user_custom' );

			if ( is_array( $custom_fonts ) && count( $custom_fonts ) ) {
				foreach ( $custom_fonts as $font ) {
					if ( isset( $font['name'] ) ) {
						$params[ $font['name'] ] = 'custom';
					}
				}
			}

			return $params;
		}

		/**
		 * Allow uploading font file types.
		 *
		 * @param array $mimes The mime types allowed.
		 * @access public
		 */
		public function mime_types( $mimes ) {

			$mimes['woff']  = 'application/x-font-woff';
			$mimes['woff2'] = 'application/x-font-woff2';
			$mimes['ttf']   = 'application/x-font-ttf';
			$mimes['svg']   = 'image/svg+xml';
			$mimes['eot']   = 'application/vnd.ms-fontobject';
			$mimes['otf']   = 'font/otf';

			return $mimes;
		}

		/**
		 * Correct the mime types and extension for the font types.
		 *
		 * @param array  $defaults File data array containing 'ext', 'type', and
		 *                                          'proper_filename' keys.
		 * @param string $file                      Full path to the file.
		 * @param string $filename                  The name of the file (may differ from $file due to
		 *                                          $file being in a tmp directory).
		 * @return Array File data array containing 'ext', 'type', and
		 *
		 * @since 1.0
		 */
		public function update_mime_types( $defaults, $file, $filename ) {
			if ( 'ttf' === pathinfo( $filename, PATHINFO_EXTENSION ) ) {
				$defaults['type'] = 'application/x-font-ttf';
				$defaults['ext']  = 'ttf';
			}

			if ( 'otf' === pathinfo( $filename, PATHINFO_EXTENSION ) ) {
				$defaults['type'] = 'application/x-font-otf';
				$defaults['ext']  = 'otf';
			}

			return $defaults;
		}

		/**
		 * Merge font style with dynamic styles
		 *
		 * @param {String} $style
		 *
		 * @since 1.2.1
		 */
		public function add_font_css( $style ) {
			$font_face = '';

			$custom_fonts = get_theme_mod( 'typo_user_custom' );

			if ( ! is_array( $custom_fonts ) || ! count( $custom_fonts ) ) {
				return $style;
			}

			foreach ( $custom_fonts as $font ) {
				$urls = array();
				if ( ! empty( $font['woff2'] ) ) {
					if ( is_int( $font['woff2'] ) ) {
						$font_att = get_post( $font['woff2'] );
						if ( 'attachment' == $font_att->post_type && ! empty( $font_att->guid ) ) {
							$urls[] = 'url(' . esc_url( $font_att->guid ) . ')';
						}
					} else {
						$urls[] = 'url(' . esc_url( $font['woff2'] ) . ')';
					}
				}
				if ( ! empty( $font['woff'] ) ) {
					if ( is_int( $font['woff'] ) ) {
						$font_att = get_post( $font['woff'] );
						if ( 'attachment' == $font_att->post_type && ! empty( $font_att->guid ) ) {
							$urls[] = 'url(' . esc_url( $font_att->guid ) . ')';
						}
					} else {
						$urls[] = 'url(' . esc_url( $font['woff'] ) . ')';
					}
				}
				if ( ! empty( $font['ttf'] ) ) {
					if ( is_int( $font['ttf'] ) ) {
						$font_att = get_post( $font['ttf'] );
						if ( 'attachment' == $font_att->post_type && ! empty( $font_att->guid ) ) {
							$urls[] = 'url(' . esc_url( $font_att->guid ) . ')';
						}
					} else {
						$urls[] = 'url(' . esc_url( $font['ttf'] ) . ')';
					}
				}
				if ( ! empty( $font['svg'] ) ) {
					if ( is_int( $font['svg'] ) ) {
						$font_att = get_post( $font['svg'] );
						if ( 'attachment' == $font_att->post_type && ! empty( $font_att->guid ) ) {
							$urls[] = 'url(' . esc_url( $font_att->guid ) . ')';
						}
					} else {
						$urls[] = 'url(' . esc_url( $font['svg'] ) . ')';
					}
				}

				$font_face .= '@font-face {' . "\n";
				$font_face .= 'font-family:"' . esc_attr( $font['name'] ) . '";' . "\n";
				$font_face .= 'src:';
				$font_face .= implode( ', ', $urls ) . ';';
				if ( ! empty( $font['weight'] ) ) {
					$font_face .= 'font-weight:' . esc_attr( $font['weight'] ) . ';' . "\n";
				}
				if ( alpha_get_option( 'font_face_display' ) ) {
					$font_face .= 'font-display:swap;' . "\n";
				}
				$font_face .= '}' . "\n";

			}

			return $font_face . $style;
		}
	}
	Alpha_Custom_Fonts::get_instance();
}
