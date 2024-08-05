<?php
/**
 * Alpha CPTS
 *
 * Custom Post Types for Alpha Core Framework
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_CPTS extends Alpha_Base {

	/**
	 * @var array $post_types
	 * @since 4.0
	 */
	private $post_types = array( 'portfolio', 'member' );

	function __construct() {

		// Post types initialize
		$GLOBALS['alpha_cpt'] = array();

		add_action( 'after_setup_theme', array( $this, 'load_content_types' ) );

		add_filter( 'alpha_custom_post_types', array( $this, 'add_custom_post_types' ) );

		// Correct templates
		add_filter( 'alpha_get_template_part', array( $this, 'correct_template' ), 5, 3 );
	}

	/**
	 * Load custom post types
	 *
	 * @since 4.0
	 */
	public function load_content_types() {
		foreach ( $this->post_types as $post_type ) {
			include ALPHA_CORE_INC . '/cpt/post_types/' . $post_type . '/class-alpha-' . $post_type . '.php';
		}
	}

	/**
	 * Add custom post types for template
	 *
	 * @since 4.0
	 */
	public function add_custom_post_types( $types ) {
		return array_merge( $types, $this->post_types );
	}

	/**
	 * Correct template part
	 *
	 * @since 4.0
	 * @param string $slug       Prefixed with ALPHA_PART ( "templates/" )
	 * @param string $name
	 * @param array $args
	 * @return string $template
	 */
	public function correct_template( $template, $slug, $name = '' ) {

		if ( ! $template ) {
			$post_type = get_post_type();
			if ( ALPHA_NAME == substr( $post_type, 0, strlen( ALPHA_NAME ) ) ) {
				$post_type = substr( $post_type, strlen( ALPHA_NAME ) + 1 );
				if ( $name ) {
					$fallback = ALPHA_CORE_PATH . "/inc/cpt/post_types/{$post_type}/{$slug}-{$name}.php";
					$template = file_exists( $fallback ) ? $fallback : '';
				}
				if ( ! $template ) {
					$fallback = ALPHA_CORE_PATH . "/inc/cpt/post_types/{$post_type}/{$slug}.php";
					$template = file_exists( $fallback ) ? $fallback : '';
				}
			}
		}
		return $template;
	}


	/**
	 * Get related posts by a custom post type category taxonomy.
	 *
	 * @param  integer $post_id      Current post id.
	 * @param  integer $number_posts Number of posts to fetch.
	 * @param  string  $post_type    The custom post type that should be used.
	 * @return object                Object with posts info.
	 */
	function related_posts( $post_id, $number_posts = 8, $post_type = 'portfolio', $tax = '' ) {

		$query = new WP_Query();

		$args = '';

		$post_type = ALPHA_NAME . '_' . $post_type;

		$number_posts = (int) $number_posts;
		if ( 0 === $number_posts || ! $number_posts ) {
			return $query;
		}

		$related_tax = $tax ? $tax : $post_type . '_category';

		$item_cats = get_the_terms( $post_id, $related_tax );

		$item_array = array();
		if ( $item_cats ) {
			foreach ( $item_cats as $item_cat ) {
				$item_array[] = $item_cat->term_id;
			}
		}

			$args = wp_parse_args(
				$args,
				array(
					'ignore_sticky_posts' => 0,
					'posts_per_page'      => $number_posts,
					'post__not_in'        => array( $post_id ),
					'post_type'           => $post_type,
				)
			);

		if ( ! empty( $item_array ) ) {
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'field'    => 'id',
					'taxonomy' => $related_tax,
					'terms'    => $item_array,
				),
			);
		}

		$query = apply_filters( 'alpha_related_posts_args', $args );

		return $query;
	}

}

Alpha_CPTS::get_instance();
