<?php
/**
 * Alpha Product Advanced Swatch for Frontend: image, color, label swatch
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Advanced_Swatch' ) ) {
	class Alpha_Advanced_Swatch extends Alpha_Base {

		/**
		 * The swatch options
		 *
		 * @var string
		 */
		public $swatch_options = '';

		/**
		 * The swatch type
		 * @var string
		 */
		public $type = '';

		/**
		 * The attribute taxonomies
		 *
		 * @var array
		 * @since 1.0
		 */
		public $attribute_taxonomies;
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'alpha_customize_fields', array( $this, 'add_customize_fields' ) );
			if ( function_exists( 'alpha_set_default_option' ) ) {
				alpha_set_default_option( 'advanced_swatch', true );
			}
			add_filter(
				'alpha_customize_sections',
				function( $sections ) {
					$sections['advanced_swatch'] = array(
						'title'    => esc_html__( 'Advanced Swatch', 'alpha-core' ),
						'panel'    => 'features',
						'priority' => 40,
					);
					return $sections;
				}
			);
			if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'advanced_swatch' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 35 );
				add_filter( 'alpha_check_product_variation_type', array( $this, 'check_variation_type' ), 10, 3 );
				add_filter( 'alpha_wc_product_listed_attribute_attr', array( $this, 'variation_list_attr' ), 10, 3 );

				// Product Loop
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_loop_attributes' ), 20 );
				// Product Listed Attributes (in archive loop and single)
				add_action( 'alpha_wc_product_listed_attributes', array( $this, 'wc_product_listed_attributes_html' ) );
				add_filter( 'woocommerce_dropdown_variation_attribute_options_args', array( $this, 'wc_dropdown_variation_attribute_options_arg' ) );
				add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'wc_dropdown_variation_attribute_options_html' ), 10, 2 );
			}
		}

		/**
		 * Add fields for Advanced swatch
		 *
		 * @param {Array} $fields
		 *
		 * @param {Array} $fields
		 *
		 * @since 1.0
		 */
		public function add_customize_fields( $fields ) {
			$fields['cs_shop_advanced_swatch_about_title'] = array(
				'section' => 'advanced_swatch',
				'type'    => 'custom',
				'label'   => '',
				'default' => '<h3 class="options-custom-title option-feature-title">' . esc_html__( 'About This Feature', 'alpha-core' ) . '</h3>',
			);
			$fields['cs_shop_advanced_swatch_desc']        = array(
				'section' => 'advanced_swatch',
				'type'    => 'custom',
				'label'   => esc_html__( 'Instead of attributes of traditional select box type, clients could see vivid and beautiful swatches of images.', 'alpha-core' ),
				'default' => '<p class="options-custom-description option-feature-description"><img class="description-image" src="' . ALPHA_ASSETS . '/images/admin/customizer/image-attribute.jpg' . '" alt="' . esc_html__( 'Theme Option Descrpition Image', 'alpha-core' ) . '"></p>',
			);
			$fields['cs_shop_advanced_swatch_title']       = array(
				'section' => 'advanced_swatch',
				'type'    => 'custom',
				'label'   => '',
				'default' => '<h3 class="options-custom-title">' . esc_html__( 'Advanced Swatch', 'alpha-core' ) . '</h3>',
			);
			$fields['advanced_swatch']                     = array(
				'type'    => 'toggle',
				'label'   => esc_html__( 'Enable Advanced Swatch', 'alpha-core' ),
				'section' => 'advanced_swatch',
			);
			$fields['new_product_period']                  = array(
				'type'    => 'number',
				'label'   => esc_html__( 'New Product Period', 'alpha-core' ),
				'tooltip' => esc_html__( 'How many days to show new label for new products.', 'alpha-core' ),
				'section' => 'shop_notice',
			);
			return $fields;
		}

		/**
		 * Check variation type
		 *
		 * @since 1.0
		 */
		public function check_variation_type( $result, $attr_name ) {
			global $product;

			$this->type           = '';
			$this->swatch_options = $product->get_meta( 'swatch_options', true );

			if ( 'variable' == $product->get_type() && $this->swatch_options ) {
				$this->swatch_options = json_decode( $this->swatch_options, true );

				if ( isset( $this->swatch_options[ $attr_name ] ) && 'image' == $this->swatch_options[ $attr_name ]['type'] ) {
					$this->type = 'image';
				}
			}

			return ( 'image' == $this->type ) || $result;
		}

		/**
		 * Enqueue script
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			wp_register_script( 'alpha-advanced-swatch', alpha_core_framework_uri( '/addons/product-advanced-swatch/swatch' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );

			if ( alpha_is_elementor_preview() ) {
				wp_enqueue_script( 'alpha-advanced-swatch' );
			}
		}

		/**
		 * Variation list attribute
		 *
		 * @since 1.0
		 */
		public function variation_list_attr( $attr, $attribute_name, $term_id_or_name ) {
			$swatch_attachment_id = get_term_meta( $term_id_or_name, 'attr_image', true );
			if ( isset( $this->swatch_options[ $attribute_name ] ) ) {
				$swatch_option = $this->swatch_options[ $attribute_name ];
				if ( ! empty( $swatch_option[ $term_id_or_name ] ) ) {
					$swatch_attachment_id = $swatch_option[ $term_id_or_name ];
				}
			}
			if ( ! empty( $swatch_attachment_id ) ) {
				$swatch_attachment_src = wp_get_attachment_image_src( $swatch_attachment_id, array( 32, 32 ) );
				if ( $swatch_attachment_src ) {
					// display image
					if ( 'image' == $this->type ) {
						if ( class_exists( 'Alpha_LazyLoad_Images' ) ) {
							$attr = ' class="image" data-lazy="' . esc_url( $swatch_attachment_src[0] ) . '"';
						} else {
							$attr = ' class="image" style="background-image:url(' . esc_url( $swatch_attachment_src[0] ) . ');"';
						}
					}

					// set image attribute
					$attr .= ' data-image="' . esc_html( alpha_wc_get_gallery_image_html( $swatch_attachment_id, true, false, false ) ) . '"';
				}
			}

			return $attr;
		}

		/**
		 * The product loop attributes.
		 *
		 * @since 1.0
		 */
		public function product_loop_attributes() {
			// $show_info = alpha_wc_get_loop_prop( 'show_info' );
			// if ( empty( wc_get_loop_prop( 'is_live_search' ) ) && ( ! is_array( $show_info ) || in_array( 'attribute', $show_info ) ) ) {
				// $this->wc_product_listed_attributes_html();
			// }
		}

		/**
		 * Product Listed Attributes (in archive loop and single)
		 *
		 * @since 1.0
		 */
		public function wc_product_listed_attributes_html( $attributes = '' ) {

			global $product;

			if ( 'variable' != $product->get_type() || ! $product->is_purchasable() ) {
				return;
			}

			wp_enqueue_script( 'alpha-advanced-swatch' );

			$show_attrs      = '';
			$is_product_loop = false;

			if ( '' == $attributes ) {
				$attributes      = $product->get_variation_attributes();
				$is_product_loop = true;
			}

			// Print attributes
			$theme_option_attrs = array();
			foreach ( wc_get_attribute_taxonomies() as $key => $value ) {
				$theme_option_attrs[] = 'pa_' . $value->attribute_name;
			}
			ob_start();
			foreach ( $attributes as $attribute_name => $options ) {
				/**
				 * Filters product variation type exist.
				 *
				 * @since 1.0
				 */
				if ( in_array( $attribute_name, $theme_option_attrs ) && apply_filters( 'alpha_check_product_variation_type', true, $attribute_name ) ) {

					if ( 'pa_' == substr( $attribute_name, 0, 3 ) ) {
						$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
					} else {
						$attribute_id = '';
					}

					if ( $attribute_id ) {
						$attribute_type = wc_get_attribute( $attribute_id )->type;
					} else {
						$attribute_type = 'select';
					}

					$terms = wc_get_product_terms(
						$product->get_id(),
						$attribute_name,
						array(
							'fields' => 'all',
						)
					);

					echo '<div class="product-variations ' . esc_attr( 'list' == $attribute_type ? 'list-type ' : 'dropdown-type ' ) . esc_attr( $terms ? $attribute_name : 'pa_custom_' . strtolower( $attribute_name ) ) . '" data-attr="' . esc_attr( $terms ? $attribute_name : 'pa_custom_' . strtolower( $attribute_name ) ) . '">';

					if ( ! empty( $options ) ) {
						if ( 'list' == $attribute_type ) {
							foreach ( $options as $term_id_or_slug ) {
								$term = get_term_by( is_numeric( $term_id_or_slug ) ? 'id' : 'slug', $term_id_or_slug, $attribute_name );
								if ( $term ) {
									$attr_label = sanitize_text_field( get_term_meta( $term->term_id, 'attr_label', true ) );
									$attr_color = sanitize_hex_color( get_term_meta( $term->term_id, 'attr_color', true ) );
								} else {
									$attr_label = $term_id_or_slug;
									$attr_color = '';
								}
								printf(
									'<button type="button" name="%s"%s title="%s">%s</button>',
									esc_attr( $term ? $term->slug : $term_id_or_slug ),
									apply_filters(
										'alpha_wc_product_listed_attribute_attr',
										$attr_color ? ' class="color" style="background-color:' . esc_attr( $attr_color ) . '"' : ' class="label"',
										$attribute_name,
										$term ? $term->term_id : $term_id_or_slug
									),
									esc_attr( $term ? $term->name : $term_id_or_slug ),
									$attr_label ? $attr_label : $term->name
								);
							}
						} elseif ( true == $is_product_loop ) {
							wc_dropdown_variation_attribute_options(
								array(
									'options'   => $options,
									'attribute' => $attribute_name,
									'product'   => $product,
									'type'      => $attribute_type,
								)
							);
						}
						do_action( 'alpha_after_product_variation', $options, $attribute_name, $terms );
					}
					echo '</div>';
				}
			}
			$html = ob_get_clean();

			if ( $html && ( alpha_is_shop() || alpha_wc_get_loop_prop( 'name' ) ) ) {
				$variations_json = wp_json_encode( $product->get_available_variations() );
				$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
				echo '<div class="product-variation-wrapper" data-product_variations="' . esc_attr( $variations_attr ) . '">' . alpha_escaped( $html ) . '</div>';
			} else {
				echo alpha_escaped( $html );
			}
		}

		/**
		 * wc_dropdown_variation_attribute_options_arg
		 *
		 * @param array $args
		 * @return array
		 *
		 * @since 1.0
		 */
		public function wc_dropdown_variation_attribute_options_arg( $args ) {
			// Select Box
			if ( 'select' == $args['type'] ) {
				$args['class'] = isset( $args['class'] ) ? $args['class'] . ' form-control' : 'form-control';
			}
			return $args;
		}

		/**
		 * wc_dropdown_variation_attribute_options_html
		 *
		 * Return variation attribute option HTML.
		 *
		 * @param string $html
		 * @param array $args
		 * @return string
		 *
		 * @since 1.0
		 */
		public function wc_dropdown_variation_attribute_options_html( $html, $args ) {
			if ( 'select' == $args['type'] ) {
				$html = '<div class="select-box">' . $html . '</div>';
			}

			$html = str_replace( '<select id="pa_', '<select data-id="pa_', $html );
			return $html;
		}
	}
}

Alpha_Advanced_Swatch::get_instance();
