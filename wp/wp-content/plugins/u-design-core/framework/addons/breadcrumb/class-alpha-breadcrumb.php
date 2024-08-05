<?php
/**
 * Alpha_Breadcrumb class.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

if ( ! class_exists( 'Alpha_Breadcrumb' ) ) {

	/**
	 * Breadcrumb class.
	 */
	class Alpha_Breadcrumb extends Alpha_Base {

		/**
		 * Breadcrumb elements.
		 * @var array Array of title and link.
		 */
		private $breadcrumbs = array();

		/**
		 * Breadcrumb structured data.
		 * @var array
		 */
		private $structured_data = array();

		/**
		 * Breadcrumb builder options.
		 * @var array
		 */
		private $args = array();

		/**
		 * Constructor.
		 */
		public function __construct() {

			// Run breadcrumb functions
			add_action( 'alpha_breadcrumb', array( $this, 'print' ) );

		}

		/**
		 * Run breadcrumb functions
		 */
		public function print( $atts ) {

			// Set breadcrumb args
			$this->set_args( $atts );

			// Get breadcrumb elements and display them.
			$this->get_breadcrumbs();
			$this->print_breadcrumbs();

			// Get breadcrumb structured data and display them.
			$this->get_structured_data();
			$this->print_structured_data();

		}

		/**
		 * Get breadcrumb builder options.
		 */
		private function set_args( $atts ) {

			$extra_class = '';
			$args        = array();

			if ( ! empty( $atts ) ) { // breadcrumb widget option
				if ( isset( $atts['delimiter_icon'] ) && ! is_array( $atts['delimiter_icon'] ) ) {
					$atts['delimiter_icon'] = json_decode( $atts['delimiter_icon'], true );
				}
				if ( isset( $atts['delimiter_icon'] ) && is_array( $atts['delimiter_icon'] ) && $atts['delimiter_icon']['value'] ) {

					if ( class_exists( 'Elementor\Icons_Manager' ) && isset( $atts['delimiter_icon']['library'] ) && 'svg' == $atts['delimiter_icon']['library'] ) {
						ob_start();
						Elementor\Icons_Manager::render_icon( $atts['delimiter_icon'], array( 'aria-hidden' => 'true' ) );
						$delimiter = ob_get_clean();
					} else {
						$delimiter = '<i class="' . esc_attr( $atts['delimiter_icon']['value'] ) . '"></i>';
					}
				} elseif ( ! empty( $atts['delimiter'] ) ) {
					$delimiter = alpha_strip_script_tags( $atts['delimiter'] );
				} else {
					$delimiter = '/';
				}

				if ( 'yes' == $atts['home_icon'] ) {
					$args['home'] = '<i class="' . ALPHA_ICON_PREFIX . '-icon-home"></i>';
					$extra_class .= ' home-icon';
				}
			} else { // theme option
				$delimiter = alpha_strip_script_tags( alpha_get_option( 'ptb_delimiter' ) );
				if ( ! $delimiter ) {
					$delimiter = '<i class="' . ALPHA_ICON_PREFIX . '-icon-angle-right"></i>';
				}
			}

			$args['delimiter']   = '<li class="delimiter">' . $delimiter . '</li>';
			$args['wrap_before'] = '<ul class="breadcrumb' . $extra_class . '">';
			$args['wrap_after']  = '</ul>';

			/**
			 * Filters breadcrumb builder option.
			 *
			 * @since 1.0
			 */
			$this->args = apply_filters( 'alpha_breadcrumb_args', $args );
		}

		/**
		 * Get breadcrumb elements
		 */
		private function get_breadcrumbs() {

			$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

			// Home Link
			if ( ! is_front_page() ) {
				$this->breadcrumbs[ esc_html__( 'Home', 'alpha-core' ) ] = apply_filters( 'woocommerce_breadcrumb_home_url', home_url() );
			}

			// Shop Link
			if ( class_exists( 'WooCommerce' ) && ( ( is_woocommerce() && is_archive() && ! is_shop() ) || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
				$shop = array();
				$shop = $this->_get_shop_title_link();
				if ( ! empty( $shop ) && $shop[1] != get_site_url() && $shop[1] != get_site_url() . '/' ) {
					$this->breadcrumbs[ $shop[0] ] = $shop[1];
				}
			}

			if ( ( isset( $post->post_type ) && get_post_type_archive_link( $post->post_type ) && 'post' != $post->post_type ||
				( is_archive() && ! ( is_home() && ! is_front_page() && 'page' == get_option( 'show_on_front' ) ) ) ) && ! is_search() ) {
				$archive = $this->_get_archive_title_link();
				if ( ! empty( $archive ) ) {
					$this->breadcrumbs[ $archive[0] ] = $archive[1];
				}
			}

			// Singular
			if ( is_singular() ) {
				if ( isset( $post->post_type ) && 'product' !== $post->post_type && get_post_type_archive_link( $post->post_type )
				) {
					$archive = $this->_get_archive_title_link();

					if ( ! empty( $archive ) ) {
						$this->breadcrumbs[ $archive[0] ] = $archive[1];
					}
				} elseif ( isset( $post->post_type ) && 'post' == $post->post_type && get_option( 'show_on_front' ) == 'page' ) {
					$this->breadcrumbs[ get_the_title( get_option( 'page_for_posts', true ) ) ] = get_permalink( get_option( 'page_for_posts' ) );
				}

				if ( isset( $post->post_parent ) && 0 == $post->post_parent ) {
					$this->breadcrumbs['terms'] = $this->_get_terms_title_link();
				} else {
					$this->breadcrumbs['ancestors'] = $this->_get_ancestors_title_link();
				}

				$this->breadcrumbs[ $this->_get_leaf_title() ] = '';
			} else { // not singular
				/**
				 * Filters whether current page is vendor or not.
				 *
				 * @since 1.0
				 */
				if ( apply_filters( 'alpha_is_vendor_store', false ) ) {
					$vendors = apply_filters( 'woocommerce_get_breadcrumb', array(), null );
					$index   = 0;
					if ( ! empty( $vendors ) ) {
						foreach ( $vendors as $key => $value ) {
							if ( $index == count( $vendors ) - 1 ) {
								$this->breadcrumbs[ $value[0] ] = '';
							} else {
								$this->breadcrumbs[ $value[0] ] = $value[1];
							}
							$index++;
						}
					}
				} elseif ( is_post_type_archive() ) {
					if ( is_search() ) {
						$archive = $this->_get_archive_title_link();
						if ( ! empty( $archive ) ) {
							$title                       = $this->_get_leaf_title( 'search' );
							$this->breadcrumbs[ $title ] = '';
						}
					} else {
						$archive = $this->_get_archive_title_link();
						if ( ! empty( $archive ) && $archive[1] != get_site_url() && $archive[1] != get_site_url() . '/' ) {
							$this->breadcrumbs[ $archive[0] ] = '';
						}
					}
				} elseif ( is_tax() || is_tag() || is_category() ) {
					$taxonomy = $this->_get_taxonomies_title_link();
					if ( ! empty( $taxonomy ) ) {
						foreach ( $taxonomy as $key => $value ) {
							$this->breadcrumbs[ $value[0] ] = $value[1];
						}
					}
					$title = $this->_get_leaf_title( 'term' );

					if ( is_tag() ) {
						if ( get_option( 'show_on_front' ) == 'page' ) {
							$this->breadcrumbs[ get_the_title( get_option( 'page_for_posts', true ) ) ] = get_permalink( get_option( 'page_for_posts' ) );
						}
						$title                       = sprintf( esc_html__( 'Tag - %s', 'alpha-core' ), $title );
						$this->breadcrumbs[ $title ] = '';
					} elseif ( is_tax( 'product_tag' ) ) {
						$title                       = sprintf( esc_html__( 'Product Tag - %s', 'alpha-core' ), $title );
						$this->breadcrumbs[ $title ] = '';
					} else {
						$this->breadcrumbs[ $title ] = '';
					}
				} elseif ( is_date() ) {
					global $wp_locale;

					if ( get_option( 'show_on_front' ) == 'page' ) {
						$this->breadcrumbs[ get_the_title( get_option( 'page_for_posts', true ) ) ] = get_permalink( get_option( 'page_for_posts' ) );
					}

					$year = esc_html( get_query_var( 'year' ) );
					if ( is_month() || is_day() ) {
						$month      = get_query_var( 'monthnum' );
						$month_name = $wp_locale->get_month( $month );
					}

					if ( is_year() ) {
						$title                       = $this->_get_leaf_title( 'year' );
						$this->breadcrumbs[ $title ] = '';
					} elseif ( is_month() ) {
						$this->breadcrumbs[ $year ]  = get_year_link( $year );
						$title                       = $this->_get_leaf_title( 'month' );
						$this->breadcrumbs[ $title ] = '';
					} elseif ( is_day() ) {
						$this->breadcrumbs[ $year ]       = get_year_link( $year );
						$this->breadcrumbs[ $month_name ] = get_month_link( $year, $month );
						$title                            = $this->_get_leaf_title( 'day' );
						$this->breadcrumbs[ $title ]      = '';
					}
				} elseif ( is_author() ) {
					$title                       = $this->_get_leaf_title( 'author' );
					$this->breadcrumbs[ $title ] = '';
				} elseif ( is_search() ) {
					$title                       = $this->_get_leaf_title( 'search' );
					$this->breadcrumbs[ $title ] = '';
				} elseif ( is_404() ) {
					$title                       = $this->_get_leaf_title( '404' );
					$this->breadcrumbs[ $title ] = '';
				} else {
					if ( is_home() && ! is_front_page() ) {
						if ( get_option( 'show_on_front' ) == 'page' ) {
							$this->breadcrumbs[ get_the_title( get_option( 'page_for_posts', true ) ) ] = '';
						}
					}
				}
			}
			$this->breadcrumbs = apply_filters( 'alpha_breadcrumb_items', $this->breadcrumbs );
		}

		/**
		 * Get shop page name and link.
		 */
		private function _get_shop_title_link() {

			$post_type        = 'product';
			$post_type_object = get_post_type_object( $post_type );
			$link             = '';

			if ( is_object( $post_type_object ) && class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
				$shop_page_id   = wc_get_page_id( 'shop' );
				$shop_page_name = $shop_page_id ? get_the_title( $shop_page_id ) : '';

				if ( ! $shop_page_name ) {
					$shop_page_name = $post_type_object->labels->name;
				}

				$link = -1 !== $shop_page_id ? get_permalink( $shop_page_id ) : get_post_type_archive_link( $post_type );

				return array( $shop_page_name, esc_url( $link ) );
			}

			return array();
		}

		/**
		 * Get archive page name and link.
		 */
		private function _get_archive_title_link() {

			$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

			if ( null !== $post ) {
				$post_type        = $post->post_type;
				$post_type_object = get_post_type_object( $post_type );
			} else {
				if ( ! empty( $_REQUEST['post_type'] ) ) {
					$post_type        = $_REQUEST['post_type'];
					$post_type_object = get_post_type_object( $post_type );
				} else {
					$post_type = get_query_var( 'post_type' );
					if ( ! $post_type ) {
						$post_type = 'post';
					}
					if ( 'product' == $post_type && function_exists( 'wc_get_page_id' ) && ! empty( wc_get_page_id( 'shop' ) ) ) {
						$shop_page_id   = wc_get_page_id( 'shop' );
						$shop_page_name = $shop_page_id ? get_the_title( $shop_page_id ) : '';
						return array( $shop_page_name, '' );
					} else {
						return array( $this->_get_archive_name( $post_type ), get_post_type_archive_link( $post_type ) );
					}
				}
			}

			$link          = '';
			$archive_title = '';

			if ( is_object( $post_type_object ) ) {

				// woocommerce
				if ( 'product' == $post_type ) {
					return $this->_get_shop_title_link();
				}

				// default
				$archive_title = $this->_get_archive_name( $post_type );
			}

			/**
			 * Filters the archive link.
			 *
			 * @since 1.0
			 */
			$link = apply_filters( 'alpha_get_archive_link', get_post_type_archive_link( $post_type ), $post_type );

			if ( $archive_title ) {
				return array( $archive_title, $link );
			}

			return '';
		}

		/**
		 * Get archive page name.
		 *
		 * @since 1.0
		 */
		private function _get_archive_name( $post_type ) {

			$page_id       = 0;
			$archive_title = '';

			if ( $page_id && ( get_post( $page_id ) == $post ) ) {
				$archive_title = $post->post_title;
			} else {
				$post_type_object = get_post_type_object( $post_type );

				if ( is_object( $post_type_object ) ) {
					$show_on_front  = get_option( 'show_on_front' );
					$page_for_posts = get_option( 'page_for_posts', true );
					if ( isset( $post_type_object->label ) && '' !== $post_type_object->label ) {
						if ( 'Posts' == $post_type_object->label ) {
							if ( 'page' == $show_on_front && 0 != $page_for_posts ) {
								$archive_title = get_the_title( $page_for_posts );
							} else {
								$archive_title = '';
							}
						} else {
							$archive_title = $post_type_object->label;
						}
					} elseif ( isset( $post_type_object->labels->menu_name ) && '' !== $post_type_object->labels->menu_name ) {
						if ( 'Posts' == $post_type_object->labels->menu_name ) {
							if ( 'page' == $show_on_front && 0 != $page_for_posts ) {
								$archive_title = get_the_title( $page_for_posts );
							} else {
								$archive_title = '';
							}
						} else {
							$archive_title = $post_type_object->labels->menu_name;
						}
					} else {
						if ( 'Posts' == $post_type_object->name ) {
							if ( 'page' == $show_on_front && 0 != $page_for_posts ) {
								$archive_title = get_the_title( $page_for_posts );
							} else {
								$archive_title = '';
							}
						} else {
							$archive_title = $post_type_object->name;
						}
					}
				}
			}

			/**
			 * Filters the archive name.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_get_archive_name', $archive_title, $post_type );

		}

		/**
		 * Get terms name and link.
		 */
		private function _get_terms_title_link() {

			$post   = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
			$result = array();

			if ( 'post' == $post->post_type ) {
				$taxonomy = 'category';
			} elseif ( 'product' == $post->post_type && class_exists( 'WooCommerce' ) && is_woocommerce() ) {
				$taxonomy = 'product_cat';
			} else {
				return $result;
			}

			$terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'orderby' => 'term_id' ) );

			if ( empty( $terms ) ) {
				return $result;
			}

			$terms_by_id = array();
			foreach ( $terms as $term ) {
				$terms_by_id[ $term->term_id ] = $term;
			}

			foreach ( $terms as $term ) {
				unset( $terms_by_id[ $term->parent ] );
			}

			if ( count( $terms_by_id ) == 1 ) {
				unset( $terms );
				$terms[0] = array_shift( $terms_by_id );
			}

			if ( count( $terms ) == 1 ) {
				$term_parent = $terms[0]->parent;

				if ( $term_parent ) {
					$term_tree   = get_ancestors( $terms[0]->term_id, $taxonomy );
					$term_tree   = array_reverse( $term_tree );
					$term_tree[] = get_term( $terms[0]->term_id, $taxonomy );

					foreach ( $term_tree as $term_id ) {
						$term_object                  = get_term( $term_id, $taxonomy );
						$result[ $term_object->name ] = get_term_link( $term_object );
					}
				} else {
					$result[ $terms[0]->name ] = get_term_link( $terms[0] );
				}
			} else {
				foreach ( $terms as $term ) {
					$result[ $term->name ] = get_term_link( $term );
				}
			}

			return $result;

		}

		/**
		 * Get ancestors name and link.
		 */
		private function _get_ancestors_title_link() {
			$result = array();

			$post              = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
			$post_ancestor_ids = array_reverse( get_post_ancestors( $post ) );

			foreach ( $post_ancestor_ids as $post_ancestor_id ) {
				$post_ancestor                        = get_post( $post_ancestor_id );
				$result[ $post_ancestor->post_title ] = get_permalink( $post_ancestor->ID );
			}

			return $result;
		}

		/**
		 * Get breadcrumb leaf name.
		 */
		private function _get_leaf_title( $object_type = '' ) {

			global $wp_query, $wp_locale;
			$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

			switch ( $object_type ) {
				case 'term':
					$term  = $wp_query->get_queried_object();
					$title = $term->name;
					break;
				case 'year':
					$title = esc_html( get_query_var( 'year' ) );
					break;
				case 'month':
					$title = $wp_locale->get_month( get_query_var( 'monthnum' ) );
					break;
				case 'day':
					$title = get_query_var( 'day' );
					break;
				case 'author':
					$user  = $wp_query->get_queried_object();
					$title = $user->display_name;
					break;
				case 'search':
					$search = esc_html( get_search_query() );
					if ( $product_cat = get_query_var( 'product_cat' ) ) {
						$product_cat = get_term_by( 'slug', $product_cat, 'product_cat' );
						$search      = '<a href="' . esc_url( get_term_link( $product_cat, 'product_cat' ) ) . '">' . esc_html( $product_cat->name ) . '</a>' . ( $search ? ' / ' : '' ) . $search;
					}
					/* translators: %s: Search query */
					$title = sprintf( esc_html__( 'Search - %s', 'alpha-core' ), $search );
					break;
				case '404':
					$title = esc_html__( '404', 'alpha-core' );
					break;
				default:
					$title = get_the_title( $post->ID );
					break;
			}

			return $title;

		}

		/**
		 * Get taxonomies name and link.
		 */
		private function _get_taxonomies_title_link() {
			global $wp_query;
			$term   = $wp_query->get_queried_object();
			$result = array();
			if ( $term && 0 != $term->parent && isset( $term->taxonomy ) && isset( $term->term_id ) && is_taxonomy_hierarchical( $term->taxonomy ) ) {
				$term_ancestors = get_ancestors( $term->term_id, $term->taxonomy );
				$term_ancestors = array_reverse( $term_ancestors );
				foreach ( $term_ancestors as $term_ancestor ) {
					$term_object = get_term( $term_ancestor, $term->taxonomy );
					$tax_link    = get_term_link( $term_object->term_id, $term->taxonomy );
					$result[]    = array( $term_object->name, $tax_link );
				}
			}
			return $result;
		}

		/**
		 * Display breadcrumb elements with builder option.
		 */
		private function print_breadcrumbs() {
			if ( empty( $this->breadcrumbs ) ) {
				return;
			}
			$output  = '';
			$output .= $this->args['wrap_before'];
			$index   = 0;

			foreach ( $this->breadcrumbs as $title => $link ) {
				if ( 0 < $index && ! empty( $this->args['delimiter'] ) ) {
					if ( is_array( $link ) && 0 == count( $link ) ) {
						$output .= '';
					} else {
						$output .= $this->args['delimiter'];
					}
				} elseif ( 0 == $index ) {
					if ( ! empty( $this->args['home'] ) ) {
						$title = '';
					}
				}

				$index ++;

				if ( is_array( $link ) ) { // comma seperated terms
					if ( 0 == count( $link ) ) {
						$output .= '';
					} else {
						$t_i     = 0;
						$output .= '<li>';
						foreach ( $link as $t_title => $t_link ) {
							if ( 0 == $t_i++ ) {
								$output .= sprintf( '<a href="%s">%s</a>', esc_url( $t_link ), wp_strip_all_tags( $t_title ) );
							} else {
								$output .= '<span class="breadcrumb-comma">, </span>';
								$output .= sprintf( '<a href="%s">%s</a>', esc_url( $t_link ), wp_strip_all_tags( $t_title ) );
							}
						}
						$output .= '</li>';
					}
				} elseif ( '' == $link ) { // leaf
					$output .= sprintf( '<li>%s</li>', wp_strip_all_tags( $title ) );
				} else { // normal
					$output .= '<li>';
					$output .= sprintf( '<a href="%s">%s</a>', esc_url( $link ), wp_strip_all_tags( $title ) );
					$output .= '</li>';
				}
			}

			$output .= $this->args['wrap_after'];

			echo alpha_strip_script_tags( $output );
		}

		/**
		 * Get breadcrumblist data.
		 */
		private function get_structured_data() {
			if ( empty( $this->breadcrumbs ) ) {
				return;
			}

			$microdata                    = array();
			$microdata['@type']           = 'BreadcrumbList';
			$microdata['itemListElement'] = array();
			$index                        = 0;

			$breadcrumb_items = array();
			foreach ( $this->breadcrumbs as $title => $link ) {
				if ( is_array( $link ) ) {
					foreach ( $link as $title => $item_link ) {
						$breadcrumb_items[ $title ] = $item_link;
					}
				} else {
					$breadcrumb_items[ $title ] = $link;
				}
			}

			foreach ( $breadcrumb_items as $title => $link ) {
				$microdata['itemListElement'][ $index ] = array(
					'@type'    => 'ListItem',
					'position' => $index + 1,
					'item'     => array(
						'name' => $title,
					),
				);

				if ( ! empty( $link ) ) {
					$microdata['itemListElement'][ $index ]['item'] += array( '@id' => $link );
				} elseif ( isset( $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) {
					$current_url = set_url_scheme( 'http://' . wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) );

					$microdata['itemListElement'][ $index ]['item'] += array( '@id' => $current_url );
				}

				$index ++;
			}

			if ( preg_match( '|^[a-zA-Z]{1,20}$|', $microdata['@type'] ) ) {
				/**
				 * Filters data which structured in breadcrumb.
				 *
				 * @since 1.0
				 */
				$this->structured_data = apply_filters( 'alpha_breadcrumb_structured_data', $microdata, $this->breadcrumbs );
			} else {
				$this->structured_data = array();
				return;
			}

			$this->structured_data = array_merge( array( '@context' => 'https://schema.org/' ), $this->structured_data );
		}

		/**
		 * Display breadcrumb structured data.
		 */
		private function print_structured_data() {
			if ( empty( $this->structured_data ) ) {
				return;
			}

			echo '<script type="application/ld+json">' . _wp_specialchars( wp_json_encode( $this->structured_data ), ENT_NOQUOTES ) . '</script>';
		}
	}
}

Alpha_Breadcrumb::get_instance();
