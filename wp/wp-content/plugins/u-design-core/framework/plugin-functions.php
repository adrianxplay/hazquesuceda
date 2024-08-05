<?php
/**
 * Define functions using in Alpha Core Plugin
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

/**
 * The filtered term product counts
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_filtered_term_product_counts' ) ) :
	function alpha_filtered_term_product_counts( $term_ids, $taxonomy = false, $query_type = false ) {
		global $wpdb;

		if ( ! class_exists( 'WC_Query' ) ) {
			return false;
		}

		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		if ( 'product_brand' === $taxonomy ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) ) {
					if ( 'product_brand' === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );

						if ( preg_match( '/pa_/', $query['taxonomy'] ) ) {
							unset( $tax_query[ $key ] );
						}
					}
				}
			}
		}

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . '
			AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')
		';

		if ( $search = WC_Query::get_main_search_query_sql() ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query );
		$cache      = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			$results                      = $wpdb->get_results( $query, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}
endif;

/**
 * Get the exact parameters of each predefined layouts.
 *
 * @param int $index    The index of predefined creative layouts
 * @since 1.0
 */
if ( ! function_exists( 'alpha_creative_preset_imgs' ) ) {
	function alpha_creative_preset_imgs() {
		return apply_filters(
			'alpha_creative_preset_imgs',
			array(
				1  => 'assets/images/creative-grid/creative-1.jpg',
				2  => 'assets/images/creative-grid/creative-2.jpg',
				3  => 'assets/images/creative-grid/creative-3.jpg',
				4  => 'assets/images/creative-grid/creative-4.jpg',
				5  => 'assets/images/creative-grid/creative-5.jpg',
				6  => 'assets/images/creative-grid/creative-6.jpg',
				7  => 'assets/images/creative-grid/creative-7.jpg',
				8  => 'assets/images/creative-grid/creative-8.jpg',
				9  => 'assets/images/creative-grid/creative-9.jpg',
				10 => 'assets/images/creative-grid/creative-10.jpg',
				11 => 'assets/images/creative-grid/creative-11.jpg',
				12 => 'assets/images/creative-grid/creative-12.jpg',
			)
		);
	}
}

/**
 * Get the creative layout.
 *
 * @since 1.0
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
					'w'    => '1-4',
					'h'    => '1-2',
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
					'w'    => '1-2',
					'h'    => '1-2',
					'w-l'  => '1',
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
					'w'    => '1-2',
					'h'    => '1',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-4',
					'h'    => '1',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
			);
		} elseif ( 4 == (int) $index ) {
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
					'size' => 'medium',
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
					'size' => 'large',
				),
			);
		} elseif ( 5 == (int) $index ) {
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
					'h'    => '1-2',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 6 == (int) $index ) {
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
					'w-s'  => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-2',
					'w-s'  => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 7 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '2-3',
					'h'    => '1',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-3',
					'h'    => '1-3',
					'w-s'  => '1',
					'w-l'  => '1-3',
					'size' => 'medium',
				),
				array(
					'w'    => '1-3',
					'h'    => '1-3',
					'w-s'  => '1',
					'w-l'  => '1-3',
					'size' => 'medium',
				),
				array(
					'w'    => '1-3',
					'h'    => '1-3',
					'w-s'  => '1',
					'w-l'  => '1-3',
					'size' => 'medium',
				),
			);
		} elseif ( 8 == (int) $index ) {
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
		} elseif ( 9 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '2-3',
					'h'    => '2-3',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-3',
					'h'    => '2-3',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'w-s'  => '1',
					'size' => 'medium',
				),
			);
		} elseif ( 10 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-2',
					'h'    => '2-3',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-2',
					'h'    => '1-3',
					'w-l'  => '1',
					'size' => 'large',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-3',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
			);
		} elseif ( 11 == (int) $index ) {
			$layout = array(
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-s'  => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '5-12',
					'h'    => '1-2',
					'w-s'  => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-3',
					'h'    => '1',
					'w-s'  => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '5-12',
					'h'    => '1-2',
					'w-s'  => '1',
					'w-l'  => '1-2',
					'size' => 'medium',
				),
				array(
					'w'    => '1-4',
					'h'    => '1-2',
					'w-s'  => '1',
					'w-l'  => '1-2',
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
					'w-l'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '5-24',
					'h'    => '1-2',
					'w-l'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '5-12',
					'h'    => '2-3',
					'w-l'  => '1',
					'size' => 'medium',
				),
				array(
					'w'    => '9-24',
					'h'    => '1-2',
					'w-l'  => '1',
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

		/**
		 * Filters creative layout.
		 *
		 * @since 1.0
		 */
		return apply_filters( 'alpha_creative_layout_filter', $layout );
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
			'w-w' => array(),
			'w-g' => array(),
			'w-x' => array(),
			'w-l' => array(),
			'w-m' => array(),
			'w-s' => array(),
		);
		$hs    = array(
			'h'   => array(),
			'h-w' => array(),
			'h-g' => array(),
			'h-x' => array(),
			'h-l' => array(),
			'h-m' => array(),
			'h-s' => array(),
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

				if ( ( 'w' == $key || 'w-w' == $key || 'w-g' == $key || 'w-x' == $key || 'w-l' == $key || 'w-m' == $key || 'w-s' == $key ) && ! in_array( $value, $ws[ $key ] ) ) {
					$ws[ $key ][] = $value;
				}
				if ( ( 'h' == $key || 'h-w' == $key || 'h-g' == $key || 'h-x' == $key || 'h-l' == $key || 'h-m' == $key || 'h-s' == $key ) && ! in_array( $value, $hs[ $key ] ) ) {
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
			} elseif ( 'w-x' == $key ) { // xl
				echo '@media (max-width: ' . ( $breakpoints['xl'] - 1 ) . 'px) {';
			} elseif ( 'w-g' == $key ) { // xlg
				echo '@media (max-width: ' . ( $breakpoints['xlg'] - 1 ) . 'px) {';
			} elseif ( 'w-w' == $key ) { // xxl or widescreen
				echo '@media (max-width: ' . ( $breakpoints['xxl'] - 1 ) . 'px) {';
			}

			foreach ( $value as $item ) {
				$opts  = explode( '-', $item );
				$width = ( ! isset( $opts[1] ) ? 100 : round( 100 * $opts[0] / $opts[1], 4 ) );
				echo esc_attr( $wrapper ) . ' .grid-item.' . $key . '-' . $item . '{flex:0 0 ' . $width . '%;width:' . $width . '%}';
			}

			if ( 'w-w' == $key || 'w-g' == $key || 'w-x' == $key || 'w-l' == $key || 'w-m' == $key || 'w-s' == $key ) {
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
				} elseif ( 'h-w' == $key ) { // xxl
					echo '@media (max-width: ' . ( $breakpoints['xxl'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-w-' . $item . '{height:' . round( $h, 2 ) . 'px}';
					echo '}';
					echo '@media (max-width: ' . ( $breakpoints['xlg'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-w-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
					echo '}';
				} elseif ( 'h-g' == $key ) { // xlg
					echo '@media (max-width: ' . ( $breakpoints['xlg'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-g-' . $item . '{height:' . round( $h, 2 ) . 'px}';
					echo '}';
					echo '@media (max-width: ' . ( $breakpoints['xl'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-g-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
					echo '}';
				} elseif ( 'h-x' == $key ) { // xl
					echo '@media (max-width: ' . ( $breakpoints['xl'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-x-' . $item . '{height:' . round( $h, 2 ) . 'px}';
					echo '}';
					echo '@media (max-width: ' . ( $breakpoints['lg'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-x-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
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
					echo esc_attr( $wrapper ) . ' .h-m-' . $item . '{height:' . round( $h, 2 ) . 'px}';
					echo '}';
					echo '@media (max-width: ' . ( $breakpoints['sm'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-m-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
					echo '}';
				} elseif ( 'h-s' == $key ) {
					echo '@media (max-width: ' . ( $breakpoints['sm'] - 1 ) . 'px) {';
					echo esc_attr( $wrapper ) . ' .h-s-' . $item . '{height:' . round( $h * $ratio / 100, 2 ) . 'px}';
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
 * Get creative image sizes.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_get_creative_image_sizes' ) ) {
	function alpha_get_creative_image_sizes( $mode, $idx ) {
		if ( 1 == $mode && 0 == $idx % 7 ) {
			return 'large';
		}
		if ( 2 == $mode && 1 == $idx % 5 ) {
			return 'large';
		}
		if ( 3 == $mode && 0 == $idx % 5 ) {
			return 'large';
		}
		if ( 4 == $mode && 2 == $idx % 5 ) {
			return 'large';
		}
		if ( 5 == $mode && ( 0 == $idx % 4 || 1 == $idx % 4 ) ) {
			return 'large';
		}
		if ( 6 == $mode && ( 0 == $idx % 4 || 2 == $idx % 4 ) ) {
			return 'large';
		}
		if ( 7 == $mode && ( 0 == $idx % 4 || 1 == $idx % 4 ) ) {
			return 'large';
		}
		if ( 8 == $mode && ( 0 == $idx % 4 || 1 == $idx % 4 ) ) {
			return 'large';
		}
		if ( 9 == $mode && 0 == $idx % 10 ) {
			return 'large';
		}
		return '';
	}
}

/**
 * Get gcd of two numbers.
 *
 * @since 1.0
 */
function alpha_get_gcd( $a, $b ) {
	while ( $b ) {
		$r = $a % $b;
		$a = $b;
		$b = $r;
	}
	return $a;
}

/**
 * Get grid space classes.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_get_grid_space_class' ) ) {
	function alpha_get_grid_space_class( $settings ) {
		if ( empty( $settings['col_sp'] ) ) {
			return '';
		} else {
			return ' gutter-' . $settings['col_sp'];
		}
	}
}

/**
 * Check Units
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_check_units' ) ) {
	function alpha_check_units( $value ) {
		if ( ! preg_match( '/((^\d+(.\d+){0,1})|((-){0,1}.\d+))(px|%|em|rem|pt){0,1}$/', $value ) ) {
			if ( 'auto' == $value || 'inherit' == $value || 'initial' == $value || 'unset' == $value ) {
				return $value;
			}
			return false;
		} elseif ( is_numeric( $value ) ) {
			$value .= 'px';
		}
		return $value;
	}
}

/**
 * Print Alpha Block
 *
 * @param string $block_name The block name to print.
 * @param bool   $is_menu    Determines whether block is menu or not.
 * @since 1.0
 */
if ( ! function_exists( 'alpha_print_template' ) && 'u-design' != get_template() ) :
	function alpha_print_template( $block_name, $is_menu = false ) {
		if ( $block_name ) {
			$atts = array(
				'name'    => $block_name,
				'is_menu' => $is_menu,
			);
			require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/block/render-block.php' );
		}
	}
endif;

/**
 * Is elementor page builder preview?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_is_elementor_preview' ) ) {
	function alpha_is_elementor_preview() {
		return defined( 'ELEMENTOR_VERSION' ) && (
				( isset( $_REQUEST['action'] ) && ( 'elementor' == $_REQUEST['action'] || 'elementor_ajax' == $_REQUEST['action'] ) ) ||
				isset( $_REQUEST['elementor-preview'] )
			);
	}
}

/**
 * Is wpbakery page builder preview?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_is_wpb_preview' ) ) {
	function alpha_is_wpb_preview() {
		if ( defined( 'WPB_VC_VERSION' ) ) {
			if ( alpha_is_wpb_backend() || vc_is_inline() ) {
				return true;
			}
		}
		return false;
	}
}


/**
 * Is page builder preview?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_is_preview' ) ) {
	function alpha_is_preview() {
		return alpha_is_elementor_preview() || alpha_is_wpb_preview();
	}
}

/**
 * Is elementor dom optimization active?
 *
 * @since 1.3
 */
if ( ! function_exists( 'alpha_elementor_if_dom_optimization' ) ) :
	function alpha_elementor_if_dom_optimization() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}
		if ( version_compare( ELEMENTOR_VERSION, '3.18.9', '>' ) ) {
			return true;
		} elseif ( version_compare( ELEMENTOR_VERSION, '3.1.0', '>=' ) ) {
			return \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		} elseif ( version_compare( ELEMENTOR_VERSION, '3.0', '>=' ) ) {
			return ( ! \Elementor\Plugin::instance()->get_legacy_mode( 'elementWrappers' ) );
		}
		return false;
	}
endif;

/**
 * Is flex box container active?
 *
 * @since 1.3
 */
if ( ! function_exists( 'alpha_elementor_if_container_active' ) ) :
	function alpha_elementor_if_container_active() {
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return false;
		}
		return Elementor\Plugin::$instance->experiments->is_feature_active( 'container' );
		// return 'active' == get_option( 'elementor_experiment-container' );
	}
endif;

/**
 * Is wpb backend editor ?
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_is_wpb_backend' ) ) {
	function alpha_is_wpb_backend() {
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) && defined( 'WPB_VC_VERSION' ) ) {
			return true;
		}
		return false;
	}
}

/**
 * Returns true when viewing the compare page.
 *
 * @return bool
 * @since 1.2.0
 */

if ( ! function_exists( 'alpha_is_compare' ) ) {
	function alpha_is_compare() {
		$page_id = wc_get_page_id( 'compare' );

		return ( $page_id && is_page( $page_id ) ) && class_exists( 'WooCommerce' ) && class_exists( 'Alpha_Product_Compare' );
	}
}
/**
 * Remove all admin notices.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_remove_all_admin_notices' ) ) {
	function alpha_remove_all_admin_notices() {
		add_action(
			'network_admin_notices',
			function() {
				remove_all_actions( 'network_admin_notices' );
			},
			1
		);
		add_action(
			'user_admin_notices',
			function() {
				remove_all_actions( 'user_admin_notices' );
			},
			1
		);
		add_action(
			'admin_notices',
			function() {
				remove_all_actions( 'admin_notices' );
			},
			1
		);
		add_action(
			'all_admin_notices',
			function() {
				remove_all_actions( 'all_admin_notices' );
			},
			1
		);
	}
}

if ( ! function_exists( 'alpha_get_grid_space' ) ) {

	/**
	 * Get columns' gutter size value from size string
	 *
	 * @since 1.0
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
		} elseif ( 'lg' == $col_sp ) {
			return 30;
		} elseif ( 'xs' == $col_sp ) {
			return 2;
		} else {
			return 20;
		}
	}
}

/**
 * Get image sizes.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_get_image_sizes' ) ) {
	function alpha_get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array(
			esc_html__( 'Default', 'alpha-core' ) => '',
			esc_html__( 'Full', 'alpha-core' )    => 'full',
		);

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $_size . ' ( ' . get_option( "{$_size}_size_w" ) . 'x' . get_option( "{$_size}_size_h" ) . ( get_option( "{$_size}_crop" ) ? '' : ', false' ) . ' )' ] = $_size;
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size . ' ( ' . $_wp_additional_image_sizes[ $_size ]['width'] . 'x' . $_wp_additional_image_sizes[ $_size ]['height'] . ( $_wp_additional_image_sizes[ $_size ]['crop'] ? '' : ', false' ) . ' )' ] = $_size;
			}
		}
		return $sizes;
	}
}

/*******************************************
 ********* Render Core Functions ***********
 *******************************************/

/**
 * Get button widget class
 *
 * @since 1.0
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

			if ( isset( $settings[ $prefix . 'show_underline' ] ) && 'yes' == $settings[ $prefix . 'show_underline' ] ) {
				$class[] = 'btn-underline-show';
			}
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
					$class[] = 'btn-icon-left';
				} else {
					$class[] = 'btn-icon-right';
				}
				if ( ! empty( $settings[ $prefix . 'icon_hover_effect' ] ) ) {
					$class[] = $settings[ $prefix . 'icon_hover_effect' ];
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
					$label = $svg . $label;
				} else {
					$label = '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>' . $label;
				}
			} else {
				if ( isset( $svg ) ) {
					$label .= $svg;
				} else {
					$label .= '<i class="' . $settings[ $prefix . 'icon' ]['value'] . '"></i>';
				}
			}
		}
		return $label;
	}
}

/**
 * The elementor loadmore render html.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_elementor_loadmore_render_html' ) ) {
	function alpha_elementor_loadmore_render_html( $query, $atts ) {

		if ( $query->max_num_pages > 1 ) {

			if ( 'button' == $atts['loadmore_type'] ) {

				echo '<button class="btn btn-load btn-primary">';
				echo empty( $atts['loadmore_label'] ) ? esc_html__( 'Load More', 'alpha-core' ) : esc_html( $atts['loadmore_label'] );
				echo '</button>';

			} elseif ( 'page' == $atts['loadmore_type'] || '' == $atts['loadmore_type'] ) {
				echo alpha_get_pagination( $query, 'pagination-load' );
			}
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
			'xxl' => isset( $settings[ $key . '_widescreen' ] ) ? (int) $settings[ $key . '_widescreen' ] : 0,
			'xlg' => isset( $settings[ $key ] ) ? (int) $settings[ $key ] : 0,
			'xl'  => isset( $settings[ $key . '_laptop' ] ) ? (int) $settings[ $key . '_laptop' ] : 0,
			'lg'  => isset( $settings[ $key . '_tablet_extra' ] ) ? (int) $settings[ $key . '_tablet_extra' ] : 0,
			'md'  => isset( $settings[ $key . '_tablet' ] ) ? (int) $settings[ $key . '_tablet' ] : 0,
			'sm'  => isset( $settings[ $key . '_mobile_extra' ] ) ? (int) $settings[ $key . '_mobile_extra' ] : 0,
			'min' => isset( $settings[ $key . '_mobile' ] ) ? (int) $settings[ $key . '_mobile' ] : 0,
		);

		return function_exists( 'alpha_get_responsive_cols' ) ? alpha_get_responsive_cols( $col_cnt ) : $col_cnt;
	}
}

/**
 * Get framework title of elementor panel title.
 *
 * @since 1.2.0
 */
if ( ! function_exists( 'alpha_elementor_panel_heading' ) ) {
	function alpha_elementor_panel_heading( $title ) {
		return $title . '<i class="alpha-elementor-heading-logo" aria-hidden="true"></i>';
	}
}

/**
 * Get post id by name.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_get_post_id_by_name' ) ) {
	function alpha_get_post_id_by_name( $post_type, $name ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = %s AND post_name = %s", $post_type, $name ) );
	}
}

/**
 * The Wc product dropdown brands.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_wc_product_dropdown_brands' ) ) {
	function alpha_wc_product_dropdown_brands( $args = array() ) {
		global $wp_query;

		$args = wp_parse_args(
			$args,
			array(
				'pad_counts'         => 1,
				'show_count'         => 1,
				'hierarchical'       => 1,
				'hide_empty'         => 1,
				'show_uncategorized' => 1,
				'orderby'            => 'name',
				'selected'           => isset( $wp_query->query_vars['product_brand'] ) ? $wp_query->query_vars['product_brand'] : '',
				'show_option_none'   => __( 'Select a category', 'alpha-core' ),
				'option_none_value'  => '',
				'value_field'        => 'slug',
				'taxonomy'           => 'product_brand',
				'name'               => 'product_brand',
				'class'              => 'dropdown_product_brand',
			)
		);

		if ( 'order' === $args['orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'order'; // phpcs:ignore
		}

		wp_dropdown_categories( $args );
	}
}

/**
 * Get animations.
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_get_animations' ) ) {
	function alpha_get_animations( $type = '' ) {
		$animations_in = array(
			'none'               => esc_html__( 'Default Animation', 'alpha-core' ),
			'bounce'             => esc_html__( 'Bounce', 'alpha-core' ),
			'flash'              => esc_html__( 'Flash', 'alpha-core' ),
			'pulse'              => esc_html__( 'Pulse', 'alpha-core' ),
			'rubberBand'         => esc_html__( 'RubberBand', 'alpha-core' ),
			'shake'              => esc_html__( 'Shake', 'alpha-core' ),
			'headShake'          => esc_html__( 'HeadShake', 'alpha-core' ),
			'swing'              => esc_html__( 'Swing', 'alpha-core' ),
			'tada'               => esc_html__( 'Tada', 'alpha-core' ),
			'wobble'             => esc_html__( 'Wobble', 'alpha-core' ),
			'jello'              => esc_html__( 'Jello', 'alpha-core' ),
			'heartBeat'          => esc_html__( 'HearBeat', 'alpha-core' ),
			'blurIn'             => esc_html__( 'BlurIn', 'alpha-core' ),
			'bounceIn'           => esc_html__( 'BounceIn', 'alpha-core' ),
			'bounceInUp'         => esc_html__( 'BounceInUp', 'alpha-core' ),
			'bounceInDown'       => esc_html__( 'BounceInDown', 'alpha-core' ),
			'bounceInLeft'       => esc_html__( 'BounceInLeft', 'alpha-core' ),
			'bounceInRight'      => esc_html__( 'BounceInRight', 'alpha-core' ),
			'revealInUp'         => esc_html__( 'RevealInUp', 'alpha-core' ),
			'revealInDown'       => esc_html__( 'RevealInDown', 'alpha-core' ),
			'revealInLeft'       => esc_html__( 'RevealInLeft', 'alpha-core' ),
			'revealInRight'      => esc_html__( 'RevealInRight', 'alpha-core' ),
			'fadeIn'             => esc_html__( 'FadeIn', 'alpha-core' ),
			'fadeInUp'           => esc_html__( 'FadeInUp', 'alpha-core' ),
			'fadeInUpBig'        => esc_html__( 'FadeInUpBig', 'alpha-core' ),
			'fadeInUpShorter'    => esc_html__( 'FadeInUpShort', 'alpha-core' ),
			'fadeInDown'         => esc_html__( 'FadeInDown', 'alpha-core' ),
			'fadeInDownBig'      => esc_html__( 'FadeInDownBig', 'alpha-core' ),
			'fadeInDownShorter'  => esc_html__( 'FadeInDownShort', 'alpha-core' ),
			'fadeInLeft'         => esc_html__( 'FadeInLeft', 'alpha-core' ),
			'fadeInLeftBig'      => esc_html__( 'FadeInLeftBig', 'alpha-core' ),
			'fadeInLeftShorter'  => esc_html__( 'FadeInLeftShort', 'alpha-core' ),
			'fadeInRight'        => esc_html__( 'FadeInRight', 'alpha-core' ),
			'fadeInRightBig'     => esc_html__( 'FadeInRightBig', 'alpha-core' ),
			'fadeInRightShorter' => esc_html__( 'FadeInRightShort', 'alpha-core' ),
			'flip'               => esc_html__( 'Flip', 'alpha-core' ),
			'flipInX'            => esc_html__( 'FlipInX', 'alpha-core' ),
			'flipInY'            => esc_html__( 'FlipInY', 'alpha-core' ),
			'lightSpeedIn'       => esc_html__( 'LightSpeedIn', 'alpha-core' ),
			'rotateIn'           => esc_html__( 'RotateIn', 'alpha-core' ),
			'rotateInUpLeft'     => esc_html__( 'RotateInUpLeft', 'alpha-core' ),
			'rotateInUpRight'    => esc_html__( 'RotateInUpRight', 'alpha-core' ),
			'rotateInDownLeft'   => esc_html__( 'RotateInDownLeft', 'alpha-core' ),
			'rotateInDownRight'  => esc_html__( 'RotateInDownRight', 'alpha-core' ),
			'hinge'              => esc_html__( 'Hinge', 'alpha-core' ),
			'jackInTheBox'       => esc_html__( 'JackInTheBox', 'alpha-core' ),
			'rollIn'             => esc_html__( 'RollIn', 'alpha-core' ),
			'zoomIn'             => esc_html__( 'ZoomIn', 'alpha-core' ),
			'zoomInUp'           => esc_html__( 'ZoomInUp', 'alpha-core' ),
			'zoomInDown'         => esc_html__( 'ZoomInDown', 'alpha-core' ),
			'zoomInLeft'         => esc_html__( 'ZoomInLeft', 'alpha-core' ),
			'zoomInRight'        => esc_html__( 'ZoomInRight', 'alpha-core' ),
			'slideInUp'          => esc_html__( 'SlideInUp', 'alpha-core' ),
			'slideInDown'        => esc_html__( 'SlideInDown', 'alpha-core' ),
			'slideInLeft'        => esc_html__( 'SlideInLeft', 'alpha-core' ),
			'slideInRight'       => esc_html__( 'SlideInRight', 'alpha-core' ),
			'blurIn'             => esc_html__( 'BlurIn', 'alpha-core' ),
		);

		$animations_out = array(
			'blurOut'            => esc_html__( 'BlurOut', 'alpha-core' ),
			'bounceOut'          => esc_html__( 'BounceOut', 'alpha-core' ),
			'bounceOutUp'        => esc_html__( 'BounceOutUp', 'alpha-core' ),
			'bounceOutDown'      => esc_html__( 'BounceOutDown', 'alpha-core' ),
			'bounceOutLeft'      => esc_html__( 'BounceOutLeft', 'alpha-core' ),
			'bounceOutRight'     => esc_html__( 'BounceOutRight', 'alpha-core' ),
			'fadeOut'            => esc_html__( 'FadeOut', 'alpha-core' ),
			'fadeOutUp'          => esc_html__( 'FadeOutUp', 'alpha-core' ),
			'fadeOutUpBig'       => esc_html__( 'FadeOutUpBig', 'alpha-core' ),
			'fadeOutDown'        => esc_html__( 'FadeOutDown', 'alpha-core' ),
			'fadeOutDownBig'     => esc_html__( 'FadeOutDownBig', 'alpha-core' ),
			'fadeOutLeft'        => esc_html__( 'FadeOutLeft', 'alpha-core' ),
			'fadeOutLeftBig'     => esc_html__( 'FadeOutLeftBig', 'alpha-core' ),
			'fadeOutRight'       => esc_html__( 'FadeOutRight', 'alpha-core' ),
			'fadeOutRightBig'    => esc_html__( 'FadeOutRightBig', 'alpha-core' ),
			'flipOutX'           => esc_html__( 'FlipOutX', 'alpha-core' ),
			'flipOutY'           => esc_html__( 'FlipOutY', 'alpha-core' ),
			'lightSpeedOut'      => esc_html__( 'LightSpeedOut', 'alpha-core' ),
			'rotateOutUpLeft'    => esc_html__( 'RotateOutUpLeft', 'alpha-core' ),
			'rotateOutRight'     => esc_html__( 'RotateOutUpRight', 'alpha-core' ),
			'rotateOutDownLeft'  => esc_html__( 'RotateOutDownLeft', 'alpha-core' ),
			'rotateOutDownRight' => esc_html__( 'RotateOutDownRight', 'alpha-core' ),
			'rollOut'            => esc_html__( 'RollOut', 'alpha-core' ),
			'zoomOut'            => esc_html__( 'ZoomOut', 'alpha-core' ),
			'zoomOutUp'          => esc_html__( 'ZoomOutUp', 'alpha-core' ),
			'zoomOutDown'        => esc_html__( 'ZoomOutDown', 'alpha-core' ),
			'zoomOutLeft'        => esc_html__( 'ZoomOutLeft', 'alpha-core' ),
			'zoomOutRight'       => esc_html__( 'ZoomOutRight', 'alpha-core' ),
			'slideOutUp'         => esc_html__( 'SlideOutUp', 'alpha-core' ),
			'slideOutDown'       => esc_html__( 'SlideOutDown', 'alpha-core' ),
			'slideOutLeft'       => esc_html__( 'SlideOutLeft', 'alpha-core' ),
			'slideOutRight'      => esc_html__( 'SlideOutRight', 'alpha-core' ),
		);

		$animations_appear = apply_filters(
			'alpha_animation_appear',
			array(
				ALPHA_DISPLAY_NAME . ' Blur'   => array(
					'blurIn' => esc_html__( 'BlurIn', 'alpha-core' ),
				),
				ALPHA_DISPLAY_NAME . ' Fading' => array(
					'fadeInDownShorter'  => esc_html__( 'Fade In Down Shorter', 'alpha-core' ),
					'fadeInLeftShorter'  => esc_html__( 'Fade In Left Shorter', 'alpha-core' ),
					'fadeInRightShorter' => esc_html__( 'Fade In Right Shorter', 'alpha-core' ),
					'fadeInUpShorter'    => esc_html__( 'Fade In Up Shorter', 'alpha-core' ),
				),
				ALPHA_DISPLAY_NAME . ' Reveal' => array(
					'revealInDown'  => esc_html__( 'Reveal In Down', 'alpha-core' ),
					'revealInLeft'  => esc_html__( 'Reveal In Left', 'alpha-core' ),
					'revealInRight' => esc_html__( 'Reveal In Right', 'alpha-core' ),
					'revealInUp'    => esc_html__( 'Reveal In Up', 'alpha-core' ),
				),
			)
		);

		if ( 'appear' == $type ) {
			return $animations_appear;
		} elseif ( 'in' == $type ) {
			return $animations_in;
		} elseif ( 'out' == $type ) {
			return $animations_out;
		}

		return array(
			'sliderIn'  => $animations_in,
			'sliderOut' => $animations_out,
			'appear'    => $animations_appear,
		);
	}
}

/**
 * Remove filter callbacks
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_clean_filter' ) ) {
	function alpha_clean_filter( $hook, $callback, $priority = 10 ) {
		remove_filter( $hook, $callback, $priority );
	}
}

/**
 * alpha_get_elementor_addon_options
 *
 * gets floating options
 *
 * @return array options of floating effect
 *
 * @since 1.0
 */

if ( ! function_exists( 'alpha_get_elementor_addon_options' ) ) {
	function alpha_get_elementor_addon_options( $settings, $options = array() ) {
		/**
		 * Filters elementor addon options such as duplex and ribbon.
		 *
		 * @since 1.0
		 */
		return apply_filters( 'alpha_elementor_addon_options', $options, $settings );
	}
}

/**
 * Convert RGBA 8 hex values color to RGBA function color
 *
 * @param  string $color
 * @return array  options of floating effect
 *
 * @since 1.0
 */
if ( ! function_exists( 'alpha_rgba_hex_2_rgba_func' ) ) {
	function alpha_rgba_hex_2_rgba_func( $color ) {
		$output = $color;

		if ( empty( $color ) ) {
			return $output;
		}

		if ( '#' == $color[0] ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 8 ) { //ARGB
			$output  = 'rgba(0,0,0,1)';
			$opacity = round( hexdec( substr( $color, 6, 2 ) ) / 255, 2 );
			$hex     = array( substr( $color, 0, 2 ), substr( $color, 2, 2 ), substr( $color, 4, 2 ) );
			$rgb     = array_map( 'hexdec', $hex );
			$output  = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		}

		return $output;
	}
}
