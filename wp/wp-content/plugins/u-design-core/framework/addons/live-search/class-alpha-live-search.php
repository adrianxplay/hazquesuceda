<?php
/**
 * Alpha Live Search
 *
 * Search posts or products, post types too.
 * Search products by sku, tag, categories.
 * Support relevanssi plugin for live search
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Live_Search' ) ) :

	class Alpha_Live_Search {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'add_script' ) );
			add_action( 'wp_ajax_alpha_ajax_search', array( $this, 'ajax_search' ) );
			add_action( 'wp_ajax_nopriv_alpha_ajax_search', array( $this, 'ajax_search' ) );
			add_filter( 'alpha_vars', array( $this, 'add_var' ) );
			/**
			 * Fires after setting up live search configuration.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_live_search', $this );
		}

		public function add_var( $vars ) {
			$vars['search_result'] = esc_html__( 'There are no results.', 'alpha-core' );
			return $vars;
		}

		/**
		 * Enqueue script
		 *
		 * @since 1.0
		 */
		public function add_script() {
			wp_enqueue_script( 'jquery-autocomplete', alpha_core_framework_uri( '/addons/live-search/jquery.autocomplete.min.js' ), array( 'jquery-core' ), false, true );
		}

		/**
		 * Ajax search
		 *
		 * @since 1.0
		 */
		public function ajax_search() {
			check_ajax_referer( 'alpha-nonce', 'nonce' );

			/**
			 * Filters ajax search query.
			 *
			 * @since 1.0
			 */
			$query  = apply_filters( 'alpha_live_search_query', sanitize_text_field( $_REQUEST['query'] ) );
			$posts  = array();
			$result = array();
			$args   = array(
				's'                   => $query,
				'orderby'             => '',
				'post_status'         => 'publish',
				'posts_per_page'      => 50,
				'ignore_sticky_posts' => 1,
				'post_password'       => '',
				'suppress_filters'    => false,
			);

			if ( empty( $_REQUEST['post_type'] ) ) {
				$posts = $this->search_posts( $args, $query );
			} elseif ( 'product' == $_REQUEST['post_type'] ) {
				// @start feature: fs_plugin_woocommerce
				if ( alpha_get_feature( 'fs_plugin_woocommerce' ) && class_exists( 'WooCommerce' ) ) {
					$posts = $this->search_products( 'product', $args );
					if ( empty( $posts ) ) {
						$posts = array();
					}
					$posts = array_merge( $posts, $this->search_products( 'sku', $args ) );
					$posts = array_merge( $posts, $this->search_products( 'tag', $args ) );
				}
				// @end feature: fs_plugin_woocommerce
			} else {
				$posts = $this->search_posts( $args, $query, array( sanitize_text_field( $_REQUEST['post_type'] ) ) );
			}

			if ( isset( $_REQUEST['is_full_screen'] ) ) {
				ob_start();
				if ( class_exists( 'WooCommerce' ) && ! empty( $_REQUEST['post_type'] ) && 'product' == $_REQUEST['post_type'] ) {
					wc_set_loop_prop(
						'col_cnt',
						alpha_get_responsive_cols(
							array(
								'xl'  => 5,
								'lg'  => 4,
								'min' => 1,
							)
						)
					);
					woocommerce_product_loop_start();
				} else {
					alpha_set_loop_prop(
						'col_cnt',
						alpha_get_responsive_cols(
							array(
								'xl'  => 5,
								'lg'  => 4,
								'min' => 1,
							)
						)
					);
					alpha_get_template_part( 'posts/post', 'loop-start' );
				}
				foreach ( $posts as $post_item ) {
					$GLOBALS['post'] = $post_item;
					setup_postdata( $post_item );
					if ( class_exists( 'WooCommerce' ) && ( 'product' == $post_item->post_type || 'product_variation' == $post_item->post_type ) ) {
						wc_set_loop_prop( 'is_live_search', true );
						wc_get_template_part( 'content', 'product' );
					} else {
						alpha_get_template_part( 'posts/post' );
					}
				}

				if ( class_exists( 'WooCommerce' ) && ! empty( $_REQUEST['post_type'] ) && 'product' == $_REQUEST['post_type'] ) {
					woocommerce_product_loop_end();
				} else {
					alpha_get_template_part( 'posts/post', 'loop-end' );
				}

				$result = ob_get_clean();
				wp_reset_postdata();
			} else {
				foreach ( $posts as $post ) {
					if ( class_exists( 'WooCommerce' ) && ( 'product' == $post->post_type || 'product_variation' == $post->post_type ) ) {
						$product       = wc_get_product( $post );
						$product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ) );
						$title         = $product->get_title();

						$result[] = array(
							'type'  => 'Product',
							'id'    => $product->get_id(),
							'value' => $title ? $title : esc_html__( '(no title)', 'alpha-core' ),
							'url'   => esc_url( $product->get_permalink() ),
							'img'   => esc_url( $product_image[0] ),
							'price' => $product->get_price_html(),
						);
					} else {
						$title = get_the_title( $post->ID );

						$result[] = array(
							'type'  => get_post_type( $post->ID ),
							'id'    => $post->ID,
							'value' => $title ? $title : esc_html__( '(no title)', 'alpha-core' ),
							'url'   => esc_url( get_the_permalink( $post->ID ) ),
							'img'   => esc_url( get_the_post_thumbnail_url( $post->ID, 'thumbnail' ) ),
							'price' => '',
						);
					}
				}
			}
			wp_send_json( array( 'suggestions' => count( $posts ) ? $result : '' ) );
		}

		/**
		 * Search posts
		 *
		 * @param array  $args      Query argument for searching.
		 * @param string $query     The value being searched for.
		 * @param string $post_type Post type being searched for.
		 * @since 1.0
		 */
		private function search_posts( $args, $query, $post_type = array( 'any' ) ) {
			$args['s'] = $query;
			/**
			 * Filters the post type when you search in.
			 *
			 * @since 1.0
			 */
			$args['post_type'] = apply_filters( 'alpha_live_search_post_type', $post_type );
			$args              = $this->search_add_category_args( $args );

			return $this->search( $args );
		}

		/**
		 * Search products
		 *
		 * @since 1.0
		 */
		private function search_products( $search_type, $args ) {
			$args['post_type']  = 'product';
			$args['meta_query'] = WC()->query->get_meta_query(); // WPCS: slow query ok.
			$args               = $this->search_add_category_args( $args );

			switch ( $search_type ) {
				case 'product':
					$args['s'] = apply_filters( 'alpha_live_search_products_query', sanitize_text_field( $_REQUEST['query'] ) );
					break;
				case 'sku':
					$query                = apply_filters( 'alpha_live_search_products_by_sku_query', sanitize_text_field( $_REQUEST['query'] ) );
					$args['s']            = '';
					$args['post_type']    = array( 'product', 'product_variation' );
					$args['meta_query'][] = array(
						'key'   => '_sku',
						'value' => $query,
					);
					break;
				case 'tag':
					$args['s']           = '';
					$args['product_tag'] = apply_filters( 'alpha_live_search_products_by_tag_query', sanitize_text_field( $_REQUEST['query'] ) );
					break;
			}
			return $this->search( $args );
		}

		/**
		 * Search
		 *
		 * @since 1.0
		 */
		private function search( $args ) {
			$search_query = http_build_query( $args );
			/**
			 * Filters functions using in search.
			 *
			 * @since 1.0
			 */
			$search_funtion = apply_filters( 'alpha_live_search_function', 'get_posts', $search_query, $args );

			if ( 'get_posts' == $search_funtion || ! function_exists( $search_funtion ) ) {

				if ( alpha_get_option( 'live_relevanssi' ) && function_exists( 'relevanssi_do_query' ) ) {

					$defaults = array(
						'numberposts'      => 5,
						'category'         => 0,
						'orderby'          => 'date',
						'order'            => 'DESC',
						'include'          => array(),
						'exclude'          => array(),
						'meta_key'         => '',
						'meta_value'       => '',
						'post_type'        => 'post',
						'suppress_filters' => true,
					);

					$parsed_args = wp_parse_args( $args, $defaults );
					if ( empty( $parsed_args['post_status'] ) ) {
						$parsed_args['post_status'] = ( 'attachment' === $parsed_args['post_type'] ) ? 'inherit' : 'publish';
					}
					if ( ! empty( $parsed_args['numberposts'] ) && empty( $parsed_args['posts_per_page'] ) ) {
						$parsed_args['posts_per_page'] = $parsed_args['numberposts'];
					}
					if ( ! empty( $parsed_args['category'] ) ) {
						$parsed_args['cat'] = $parsed_args['category'];
					}
					if ( ! empty( $parsed_args['include'] ) ) {
						$incposts                      = wp_parse_id_list( $parsed_args['include'] );
						$parsed_args['posts_per_page'] = count( $incposts );  // Only the number of posts included.
						$parsed_args['post__in']       = $incposts;
					} elseif ( ! empty( $parsed_args['exclude'] ) ) {
						$parsed_args['post__not_in'] = wp_parse_id_list( $parsed_args['exclude'] );
					}

					$parsed_args['ignore_sticky_posts'] = true;
					$parsed_args['no_found_rows']       = true;

					return relevanssi_do_query( new WP_Query( $parsed_args ) );
				}

				return get_posts( $args );

			} else {
				$search_funtion( $search_query, $args );
			}
		}

		/**
		 * Search add category args.
		 *
		 * @since 1.0
		 */
		private function search_add_category_args( $args ) {
			if ( isset( $_REQUEST['cat'] ) && $_REQUEST['cat'] && '0' != $_REQUEST['cat'] ) {
				if ( 'product' == alpha_get_option( 'search_post_type' ) ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => sanitize_text_field( $_REQUEST['cat'] ),
						),
					);
				} elseif ( 'post' == alpha_get_option( 'search_post_type' ) ) {
					$args['category'] = get_terms( array( 'slug' => sanitize_text_field( $_REQUEST['cat'] ) ) )[0]->term_id;
				}
			}
			return $args;
		}
	}
	new Alpha_Live_Search;
endif;
