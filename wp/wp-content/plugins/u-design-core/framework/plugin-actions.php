<?php
/**
 * Plugin Actions, Filters
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

add_filter( 'template_include', 'alpha_control_maintenance', 9999 );
add_action( 'admin_bar_menu', 'alpha_add_maintenance_notice', 999 );
add_action( 'after_setup_theme', 'alpha_setup_make_script_async' );
add_action( 'wp_enqueue_scripts', 'alpha_enqueue_core_framework_scripts', 2 );
add_action( 'admin_print_footer_scripts', 'alpha_print_footer_scripts', 30 );
add_action( 'wp_ajax_alpha_load_creative_layout', 'alpha_load_creative_layout' );
add_action( 'wp_ajax_nopriv_alpha_load_creative_layout', 'alpha_load_creative_layout' );
add_action( 'alpha_importer_before_import_dummy', 'alpha_before_import_dummy' );

// update image srcset meta
add_filter( 'wp_calculate_image_srcset', 'alpha_image_srcset_filter_sizes', 10, 2 );

// post filter
add_filter( 'posts_clauses', 'alpha_cpt_query_post_clauses', 10, 2 );

// search custom post types
add_filter( 'alpha_search_content_types', 'alpha_search_content_types' );

// Clear studio blocks transient when demo import
add_action( 'alpha_demo_imported', 'alpha_on_change_flexbox_feature' );
add_action( 'elementor/experiments/feature-state-change/container', 'alpha_on_change_flexbox_feature', 20, 0 );

if ( ! function_exists( 'alpha_control_maintenance' ) ) {
	/**
	 * Control the maintenance mode
	 *
	 * @since 1.3.0
	 */
	function alpha_control_maintenance( $template ) {
		if ( is_user_logged_in() && current_user_can( 'edit_published_posts' ) ) {
			return $template;
		}

		if ( ! function_exists( 'alpha_get_option' ) || empty( alpha_get_option( 'is_maintenance' ) ) ) {
			return $template;
		}

		$abspath        = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );
		$included_files = get_included_files();
		if ( in_array( $abspath . 'wp-login.php', $included_files ) ||
			in_array( $abspath . 'wp-register.php', $included_files ) ||
			( isset( $_GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'wp-login.php' ) ||
			$_SERVER['PHP_SELF'] == '/wp-login.php' ) {
			return $template;
		}
		$fallback  = '<h2>';
		$fallback .= esc_html__( 'This is default maintenance page. Please create a new page and set it as \'Maintenance Page\' in Theme Option.', 'alpha-core' );
		$fallback .= '</h2>';

		$maintenance_page = alpha_get_option( 'maintenance_page' );

		if ( $maintenance_page ) {
			if ( is_page( $maintenance_page ) ) {
				status_header( 503 ); // Common causes are a server that is down for maintenance or that is overloaded.
				nocache_headers();
				return $template;
			}
			if ( wp_redirect( get_permalink( $maintenance_page ) ) ) {
				exit;
			}
		}

		/**
		 * Output a fallback message if redirect failed or no page selected
		 */
		status_header( 503 );
		nocache_headers();
		exit( $fallback );
	}
}

if ( ! function_exists( 'alpha_add_maintenance_notice' ) ) {
	/**
	 * Add info to admin bar about maintenance notice
	 *
	 * @since 1.3.0
	 */
	function alpha_add_maintenance_notice( $admin_bar ) {

		if ( function_exists( 'alpha_get_option' ) && ! empty( alpha_get_option( 'is_maintenance' ) ) ) {
			$admin_bar->add_menu(
				array(
					'id'     => 'alpha-maintenance',
					'parent' => 'top-secondary',
					'title'  => sprintf( __( '%1$sMaintenance Mode Enabled%2$s', 'alpha-core' ), '<span style="color: red">', '</span>' ),
					'href'   => '#',
				)
			);
		}

		return $admin_bar;
	}
}

if ( ! function_exists( 'alpha_print_footer_scripts' ) ) {
	/**
	 * Print footer scripts
	 *
	 * @since 1.0
	 */
	function alpha_print_footer_scripts() {
		echo '<script id="alpha-core-admin-js-extra">';
		echo 'var alpha_core_vars = ' . json_encode(
			apply_filters(
				'alpha_core_admin_localize_vars',
				array(
					'admin_url'  => esc_url( admin_url() ),
					'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonce'      => wp_create_nonce( 'alpha-core-nonce' ),
					'assets_url' => ALPHA_CORE_URI,
					'theme'      => ALPHA_NAME,
				)
			)
		) . ';';
		echo '</script>';
	}
}

if ( ! function_exists( 'alpha_enqueue_core_framework_scripts' ) ) {

	/**
	 * Enqueue framework required scripts
	 *
	 * @since 1.2.0
	 */
	function alpha_enqueue_core_framework_scripts() {
		wp_register_script( 'jquery-floating', alpha_core_framework_uri( '/assets/js/jquery.floating.min.js' ), array( 'jquery-core' ), false, true );
		wp_register_script( 'jquery-skrollr', alpha_core_framework_uri( '/assets/js/skrollr.min.js' ), array(), '0.6.30', true );
		wp_register_script( 'alpha-chart-lib', alpha_core_framework_uri( '/assets/js/chart.min.js' ), array(), false, true );
		wp_register_script( 'three-sixty', alpha_core_framework_uri( '/assets/js/threesixty.min.js' ), array(), false, true );
		wp_register_script( 'jquery-countdown', alpha_core_framework_uri( '/assets/js/jquery.countdown.min.js' ), array(), false, true );
	}
}

if ( ! function_exists( 'alpha_setup_make_script_async' ) ) {
	/**
	 * Add a filter to make scripts async.
	 *
	 * @since 1.0
	 */
	function alpha_setup_make_script_async() {
		// Set scripts as async
		if ( ! alpha_is_wpb_preview() && function_exists( 'alpha_get_option' ) && alpha_get_option( 'resource_async_js' ) ) {
			add_filter( 'script_loader_tag', 'alpha_make_script_async', 10, 2 );
		}
	}
}

if ( ! function_exists( 'alpha_make_script_async' ) ) {
	/**
	 * Set scripts as async
	 *
	 * @since 1.0
	 *
	 * @param string $tag
	 * @param string $handle
	 * @return string Async script tag
	 */
	function alpha_make_script_async( $tag, $handle ) {
		$async_scripts = apply_filters(
			'alpha_async_scripts',
			array(
				'jquery-autocomplete',
				'jquery-countdown',
				'alpha-magnific-popup',
				'jquery-cookie',
				'alpha-framework-async',
				'alpha-theme',
				'alpha-shop',
				'alpha-woocommerce',
				'alpha-single-product',
				'alpha-ajax',
				'alpha-countdown',
				'alpha-shop-show-type',
			)
		);

		if ( in_array( $handle, $async_scripts ) ) {
			return str_replace( ' src', ' async="async" src', $tag );
		}
		return $tag;
	}
}

add_filter(
	'alpha_core_filter_doing_ajax',
	function() {
		// check ajax doing on others
		return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && mb_strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ? true : false;
	}
);

if ( ! function_exists( 'alpha_load_creative_layout' ) ) {
	function alpha_load_creative_layout() {
		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification

		$mode = isset( $_POST['mode'] ) ? $_POST['mode'] : 0;

		if ( $mode ) {
			echo json_encode( alpha_creative_layout( $mode ) );
		} else {
			echo json_encode( array() );
		}

		exit();

		// phpcs:enable
	}
}

if ( ! function_exists( 'alpha_image_srcset_filter_sizes' ) ) {
	/**
	 * Remove srcset in img tag.
	 *
	 * @since 1.2.0
	 */
	function alpha_image_srcset_filter_sizes( $sources, $size_array ) {
		foreach ( $sources as $width => $source ) {
			if ( isset( $source['descriptor'] ) && 'w' == $source['descriptor'] && ( $width < apply_filters( 'alpha_mini_screen_size', 320 ) || (int) $width > (int) $size_array[0] ) ) {
				unset( $sources[ $width ] );
			}
		}
		return $sources;
	}
}

if ( ! function_exists( 'alpha_cpt_query_post_clauses' ) ) {

	/**
	 * Add extra clauses to the cpt query.
	 *
	 * @param array    $args CPT query clauses.
	 * @param WP_Query $wp_query The current cpt query.
	 * @return array The updated cpt query clauses array.
	 */
	function alpha_cpt_query_post_clauses( $args, $wp_query ) {
		global $wpdb;

		if ( ! $wp_query->is_main_query() ) {
			return $args;
		}

		$attributes = array();
		if ( ! empty( $_GET ) ) {
			foreach ( $_GET as $key => $value ) {
				if ( 0 === strpos( $key, 'filter_' ) ) {
					$taxonomy     = urldecode( sanitize_title( urldecode( str_replace( 'filter_', '', $key ) ?? '' ) ) );
					$filter_terms = ! empty( $value ) ? explode( ',', wp_unslash( $value ) ) : array();

					if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) ) {
						continue;
					}

					$query_type                            = ! empty( $_GET[ 'query_type_' . $taxonomy ] ) && in_array( $_GET[ 'query_type_' . $taxonomy ], array( 'and', 'or' ), true ) ? wp_unslash( $_GET[ 'query_type_' . $taxonomy ] ) : '';
					$attributes[ $taxonomy ]['terms']      = array_map( 'sanitize_title', $filter_terms ); // Ensures correct encoding.
					$attributes[ $taxonomy ]['query_type'] = $query_type;
				}
			}
		}

		// The extra derived table ("SELECT object_id FROM") is needed for performance
		// (causes the filtering subquery to be executed only once).
		$clause_root = " {$wpdb->posts}.ID IN ( SELECT object_id FROM (";

		$attribute_ids_for_and_filtering = array();

		foreach ( $attributes as $taxonomy => $data ) {
			$all_terms                  = get_terms( $taxonomy, array( 'hide_empty' => false ) );
			$term_ids_by_slug           = wp_list_pluck( $all_terms, 'term_id', 'slug' );
			$term_ids_to_filter_by      = array_values( array_intersect_key( $term_ids_by_slug, array_flip( $data['terms'] ) ) );
			$term_ids_to_filter_by      = array_map( 'absint', $term_ids_to_filter_by );
			$term_ids_to_filter_by_list = '(' . join( ',', $term_ids_to_filter_by ) . ')';
			$is_and_query               = 'and' === $data['query_type'];

			$count = count( $term_ids_to_filter_by );

			if ( 0 !== $count ) {
				if ( $is_and_query && $count > 1 ) {
					$attribute_ids_for_and_filtering = array_merge( $attribute_ids_for_and_filtering, $term_ids_to_filter_by );
				} else {
					$clauses[] = "
							{$clause_root}
							SELECT object_id
							FROM {$wpdb->term_relationships} lt
							WHERE term_taxonomy_id in {$term_ids_to_filter_by_list}
						)";
				}
			}
		}

		if ( ! empty( $attribute_ids_for_and_filtering ) ) {
			$count                      = count( $attribute_ids_for_and_filtering );
			$term_ids_to_filter_by_list = '(' . join( ',', $attribute_ids_for_and_filtering ) . ')';
			$clauses[]                  = "
				{$clause_root}
				SELECT object_id
				FROM {$wpdb->term_relationships} lt
				WHERE term_taxonomy_id in {$term_ids_to_filter_by_list}
				GROUP BY object_id
				HAVING COUNT(object_id)={$count}
			)";
		}

		if ( ! empty( $clauses ) ) {
			// "temp" is needed because the extra derived tables require an alias.
			$args['where'] .= ' AND (' . join( ' temp ) AND ', $clauses ) . ' temp ))';
		} elseif ( ! empty( $attributes ) ) {
			$args['where'] .= ' AND 1=0';
		}

		return $args;
	}
}

if ( ! function_exists( 'alpha_before_import_dummy' ) ) {
	/**
	 * Before import demo dummy content
	 *
	 * @since 1.2.0
	 */
	function alpha_before_import_dummy() {
		add_filter(
			'upload_mimes',
			function( $mimes ) {
				$mimes['svg'] = 'image/svg+xml';
				return $mimes;
			},
			99
		);

		if ( defined( 'ELEMENTOR_VERSION' ) && ! extension_loaded( 'simplexml' ) ) {
			add_filter(
				'wp_update_attachment_metadata',
				function( $data, $id ) {
					$attachment = get_post( $id ); // Filter makes sure that the post is an attachment.
					$mime_type  = $attachment->post_mime_type;

					// If the attachment is an svg
					if ( 'image/svg+xml' === $mime_type ) {
						if ( empty( $data ) || empty( $data['width'] ) || empty( $data['height'] ) ) {
							if ( empty( $data ) ) {
								$data = array();
							}
							$data['width']  = 100;
							$data['height'] = 100;
						}
					}
					return $data;
				},
				8,
				2
			);
		}

		$demo = ( isset( $_POST['demo'] ) && $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : 'landing';

		$demos = Alpha_Setup_Wizard::get_instance()->demo_types();

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			if ( ! empty( $demos[ $demo ]['is_container'] ) ) {
				update_option( 'elementor_experiment-container', 'active' );
				update_option( 'elementor_experiment-nested-elements', 'active' );
				Elementor\Plugin::$instance->experiments->set_feature_default_state( 'container', 'active' );
			} else {
				update_option( 'elementor_experiment-container', 'inactive' );
				update_option( 'elementor_experiment-nested-elements', 'inactive' );
				Elementor\Plugin::$instance->experiments->set_feature_default_state( 'container', 'inactive' );
			}
			if ( ! empty( $demos[ $demo ]['grid_container'] ) ) {
				update_option( 'elementor_experiment-container_grid', 'active' );
				Elementor\Plugin::$instance->experiments->set_feature_default_state( 'container_grid', 'active' );
			} else {
				update_option( 'elementor_experiment-container_grid', 'inactive' );
				Elementor\Plugin::$instance->experiments->set_feature_default_state( 'container_grid', 'inactive' );
			}
		}
	}
}

if ( ! function_exists( 'alpha_search_content_types' ) ) {
	/**
	 * Search content types
	 *
	 * @since 1.3.0
	 */
	function alpha_search_content_types( $post_types ) {
		// Get post types to search.
		$post_types = array(
			'' => esc_html__( 'All', 'alpha-core' ),
		);
		/**
		 * Filters the exclude post type.
		 *
		 * @param array The post type array.
		 * @since 1.0
		 */
		$post_types_exclude   = apply_filters( 'alpha_condition_exclude_post_types', array( 'page', 'e-landing-page', ALPHA_NAME . '_template', 'attachment', 'elementor_library' ) );
		$available_post_types = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $available_post_types as $post_type_slug => $post_type ) {
			if ( ! in_array( $post_type_slug, $post_types_exclude ) ) {
				$post_types[ $post_type_slug ] = $post_type->labels->name;
			}
		}

		return $post_types;
	}
}

if ( ! function_exists( 'alpha_on_change_flexbox_feature' ) ) {
	/**
	 * Clear studio blocks transient on flexbox container feature is changed
	 *
	 * @since 1.3.0
	 */
	function alpha_on_change_flexbox_feature() {
		if ( ! class_exists( 'Alpha_Tools' ) ) {
			return;
		}
		Alpha_Tools::get_instance()->execute_tool( 'clear_studio_transient' );
	}
}
