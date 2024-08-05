<?php
/**
 * Alpha Layout Builder Extend
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.1
 */

defined( 'ABSPATH' ) || die;

class Alpha_Layout_Builder_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'alpha_layout_get_controls', array( $this, 'customize_controls' ) );

		// Get layout options from theme option.
		add_action( 'wp', array( $this, 'setup_layout' ), 5 );
		add_filter( 'alpha_layout_default_args', array( $this, 'get_theme_options_map' ), 10, 2 );
	}

	/**
	 * Setup layout
	 *
	 * @since 1.0
	 */
	public function setup_layout() {
		global $alpha_layout;
		$alpha_layout = $this->get_layout();
	}
	/**
	 * Add layout builder controls
	 *
	 * @since 4.0
	 */
	public function customize_controls( $controls ) {
		return array_merge(
			$controls,
			array(
				'content_single_product' => array(
					'single_product_type'  => array(
						'type'    => 'select',
						'label'   => esc_html__( 'Single Product Type', 'alpha' ),
						'options' => apply_filters(
							'alpha_sp_types',
							array(
								''           => esc_html__( 'Default', 'alpha' ),
								'vertical'   => esc_html__( 'Vertical Thumbs', 'alpha' ),
								'horizontal' => esc_html__( 'Horizontal Thumbs', 'alpha' ),
								'builder'    => esc_html__( 'Use Builder', 'alpha' ),
							),
							'layout'
						),
					),
					'single_product_block' => array(
						'type'  => 'block_product_layout',
						'label' => esc_html__( 'Single Product Layout', 'alpha' ),
					),
				),
			)
		);
	}
	/**
	 * Get layout theme options map
	 *
	 * @since 4.0
	 */
	public function get_theme_options_map( $options_map, $layout_name ) {

		$res = array();
		if ( 'archive_product' == $layout_name ) {
			$res = array(
				'products_column' => 'products_column',
				'products_gap'    => 'products_gap',
				'loadmore_type'   => 'products_load',
			);
		} elseif ( 'single_product' == $layout_name ) {
			$res = array(
				'single_product_type'          => 'single_product_type',
				'single_product_sticky'        => 'single_product_sticky',
				'single_product_sticky_mobile' => 'single_product_sticky_mobile',
				'products_load'                => 'products_load',
				'product_data_type'            => 'product_data_type',
			);
		} elseif ( 'archive_' == substr( $layout_name, 0, 8 ) ) {
			$post_type = substr( $layout_name, 8 );
			if ( ALPHA_NAME == substr( $post_type, 0, strlen( ALPHA_NAME ) ) ) {
				$post_type = substr( $post_type, strlen( ALPHA_NAME ) + 1 );
			}
			$res = array(
				'type'            => $post_type . '_type',
				'overlay'         => $post_type . '_overlay',
				'posts_column'    => $post_type . 's_column',
				'posts_layout'    => $post_type . 's_layout',
				'posts_filter'    => $post_type . 's_filter',
				'excerpt_type'    => 'post' == $post_type ? 'excerpt_type' : $post_type . '_excerpt_type',
				'excerpt_length'  => 'post' == $post_type ? 'excerpt_length' : $post_type . '_excerpt_length',
				'read_more_label' => 'post' != $post_type ? alpha_get_option( $post_type . '_read_more_label' ) : esc_html__( 'Read More', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>',
				'loadmore_type'   => $post_type . 's_load',
			);
		} elseif ( 'single_' == substr( $layout_name, 0, 7 ) ) {
			$post_type = substr( $layout_name, 7 );
			if ( ALPHA_NAME == substr( $post_type, 0, strlen( ALPHA_NAME ) ) ) {
				$post_type = substr( $post_type, strlen( ALPHA_NAME ) + 1 );
			}
			$res = array(
				'posts_layout'      => $post_type . 's_layout',
				'excerpt_type'      => 'post' == $post_type ? 'excerpt_type' : $post_type . '_excerpt_type',
				'excerpt_length'    => 'post' == $post_type ? 'excerpt_length' : $post_type . '_excerpt_length',
				'read_more_label'   => 'post' != $post_type ? alpha_get_option( $post_type . '_read_more_label' ) : esc_html__( 'Read More', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>',
				'related_count'     => $post_type . '_related_count',
				'related_column'    => $post_type . '_related_column',
				'related_order'     => $post_type . '_related_order',
				'related_orderway'  => $post_type . 's_related_orderway',
				'single_image_size' => 'full', // for single post featured image.
			);
		}
		return apply_filters( 'alpha_get_options_map', $res, $options_map, $layout_name );
	}

	/**
	 * Get layout
	 *
	 * @since 4.0
	 */
	public function get_layout( $layout_name = '' ) {
		global $wp_query;
		$layout         = array(
			'alpha_panel_map' => array(),
		);
		$all_conditions = alpha_get_option( 'conditions' );
		$all_controls   = Alpha_Layout_Builder::get_instance()->get_controls();

		if ( ! $layout_name ) {
			$layout_name = alpha_get_page_layout();
		}

		// create layout value
		foreach ( $all_controls as $part => $controls ) {
			if ( 'content_' == substr( $part, 0, 8 ) ) {
				if ( 'content_' . $layout_name == $part ) {
					// create empty layout content value
					foreach ( $controls as $name => $control ) {
						$layout[ $name ]           = '';
						$layout['alpha_panel_map'] = array(
							$name => '',
						);
					}
				}
				continue;
			}
			foreach ( $controls as $name => $control ) {
				$layout[ $name ]           = '';
				$layout['alpha_panel_map'] = array(
					$name => '',
				);
			}
		}

		/**
		 * Filters the retrieving layout value from theme options.
		 *
		 * @since 4.0
		 */
		$options_map = apply_filters( 'alpha_layout_default_args', array(), $layout_name );
		foreach ( $options_map as $option => $name ) {
			$layout[ $option ] = alpha_get_option( $name, -1 );
			if ( -1 === $layout[ $option ] ) {
				unset( $layout[ $option ] );
			}
		}
		// Retrieve current term information in single or archive pages.
		$current_term_id    = false;
		$current_taxonomy   = false;
		$current_term       = false;
		$current_post_id    = (string) get_the_ID();
		$current_post_terms = null;
		if ( $wp_query->is_tax || $wp_query->is_category || $wp_query->is_tag ) {
			$current_term = $wp_query->get_queried_object();
			if ( $current_term ) {
				$current_term_id  = $current_term->term_id;
				$current_taxonomy = $current_term->taxonomy;
			}
		}

		/**
		 * Apply only site layout.
		 *
		 * Filters only applied in site layout.
		 */
		$apply_only_site_layout = apply_filters( 'alpha_apply_only_site_layout', apply_filters( 'alpha_is_vendor_store', false ) );

		// retrieve layout value from layout builder.

		if ( $all_conditions && is_array( $all_conditions ) ) {
			foreach ( $all_conditions as $category => $conditions ) {

				if ( 'site' != $category && $apply_only_site_layout ) {
					continue;
				}

				if ( is_front_page() && 'single_front' == $category  // if home layout
					|| 'site' == $category                          // if global layout
					|| $layout_name == $category                    // if current post type's single or archive layout
					|| is_search() && 'search' == $category         // if search layout
					|| function_exists( 'is_cart' ) && is_cart() && 'cart' == $category   // cart page
					|| ( class_exists( 'WooCommerce' ) && is_checkout() && 'checkout' == $category )
					) {

					$index = 0;
					foreach ( $conditions as $condition ) {
						$pass = false;

						if ( 'site' == $category || 'error' == $category || 'single_front' == $category || 'cart' == $category || 'checkout' == $category ) {

							// if no condition scheme exists
							$pass = true;

						} elseif ( ! empty( $condition['scheme'] ) ) {

							// check scheme

							$scheme = $condition['scheme'];

							if ( ! empty( $scheme['all'] ) && $scheme['all'] ) {

								// apply for all cases.
								$pass = true;

							} elseif ( is_search() && 'search' == $category ) {

								$type = get_query_var( 'post_type' );
								if ( 'any' == $type ) {
									$type = 'post';
								}

								if ( ! is_array( $scheme ) || ! count( $scheme ) || isset( $scheme[ $type ] ) && $scheme[ $type ] ) {
									$pass = true;
								}
							} elseif ( $current_term || function_exists( 'is_shop' ) && is_shop() || is_home() && 'archive_post' == $category ) { // Archive pages

								foreach ( $scheme as $scheme_key => $scheme_data ) {

									if (
									'category' == $scheme_key && $wp_query->is_category ||
									'post_tag' == $scheme_key && $wp_query->is_tag ||
									taxonomy_exists( $scheme_key ) && $wp_query->is_tax && $current_term->taxonomy == $scheme_key
									) {
										if ( is_array( $scheme_data ) && count( $scheme_data ) ) {
											if ( in_array( (string) $current_term->term_id, $scheme_data ) ) {
												$pass = true;
											}
										} elseif ( $scheme_data ) {
											$pass = true;
										}
									}
								}
							} else { // Single Pages

								foreach ( $scheme as $scheme_key => $scheme_data ) {

									if ( 'child' == $scheme_key ) {
										if ( is_array( $scheme_data ) && in_array( wp_get_post_parent_id( 0 ), $scheme_data ) ) {
											$pass = true;
										}
									} elseif ( taxonomy_exists( $scheme_key ) ) {

										// Has matched term of listed taxonomy

										$found_term = false;
										if ( ! $current_post_terms ) {
											$current_post_terms = get_terms();
										}

										foreach ( $current_post_terms as $term ) {
											if ( $term->taxonomy == $scheme_key ) {
												$found_term = true;
											}
										}

										if ( is_array( $scheme_data ) && count( $scheme_data ) ) {
											foreach ( $current_post_terms as $term ) {
												if ( in_array( (string) $term->term_id, $scheme_data ) ) {
													$pass = true;
												}
											}
										} elseif ( $scheme_data && $found_term ) {
											$pass = true;
										}
									} elseif ( post_type_exists( $scheme_key ) && is_singular( $scheme_key ) &&
									is_array( $scheme_data ) && count( $scheme_data ) &&
									in_array( $current_post_id, $scheme_data ) ) {

										// Pass only post's id exists

										$pass = true;
									}
								}
							}
						}

						// if pass
						if ( $pass && isset( $condition['options'] ) && is_array( $condition['options'] ) ) {
							foreach ( $condition['options'] as $name => $value ) {
								if ( $value ) {
									$layout[ $name ]                    = $value;
									$layout['alpha_panel_map'][ $name ] = array(
										'title'    => $condition['title'],
										'category' => $category,
										'index'    => $index,
									);
								}
							}
						}

						$index = $index + 1;
					}
				}
			}
		}

		/**
		 * Filters the layout.
		 *
		 * @param array  $layout      The layouts
		 * @param string $layout_name The layout name
		 * @since 4.0
		 */
		return apply_filters( 'alpha_get_layout', $layout, $layout_name );
	}
}

Alpha_Layout_Builder_Extend::get_instance();
