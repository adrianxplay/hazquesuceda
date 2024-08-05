<?php
/**
 * Alpha Portfolio Custom Post Type
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
if ( ! function_exists( 'alpha_get_option' ) || ! alpha_get_option( 'enable_portfolio' ) ) {
	return;
}

/**
 * Alpha Portfolio Class
 *
 * @since 4.0.0
 */
class Alpha_Portfolio extends Alpha_Base {

	public $cpt;
	public $cpt_single;
	public $cpt_slug;
	public $archive_page_id;

	/**
	 * Constructor
	 *
	 * @since 4.0.0
	 */
	public function __construct() {

		$this->cpt        = esc_html__( 'Portfolios', 'alpha-core' );
		$this->cpt_single = esc_html__( 'Portfolio', 'alpha-core' );
		$this->cpt_slug   = 'portfolio';
		// $this->archive_page_id = get_option( 'portfolio_page_id' );

		add_action( 'init', array( $this, 'add_post_type' ) );
		// add_action( 'init', array( $this, 'add_shortcode_portfolio' ) );
		// add_action( 'init', array( $this, 'create_portfolio_page' ) );

		// Add menu active classes
		// add_filter( 'wp_nav_menu_objects', array( $this, 'nav_menu_item_classes' ) );

		// Add custom meta box
		add_filter( 'alpha_metabox_tabs', array( $this, 'add_meta_tab' ), 10, 2 );
		add_filter( 'alpha_metabox_fields', array( $this, 'add_meta_fields' ), 10, 2 );

		// Load styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 23 );

		// Reset portfolios count per page
		add_filter( 'pre_get_posts', array( $this, 'set_post_filters' ), 5 );
		add_action( 'alpha_before_posts_loop', array( $this, 'setup_posts_loop' ), 20 );

		// Add widgets to Elementor or WPBakery page builder
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widget' ) );
			add_filter( 'alpha_select_post_types', array( $this, 'add_elementor_ajax_post_types' ) );
			add_filter( 'alpha_select_taxonomies', array( $this, 'add_elementor_ajax_taxonomies' ) );
		}

		// Extend search content
		// add_filter( 'alpha_search_content_types', array( $this, 'add_to_search_content' ) );

		// Portfolio page breadcrumb
		// add_filter( 'alpha_get_archive_name', array( $this, 'portfolio_archive_page_title' ), 10, 2 );
		// add_filter( 'alpha_get_archive_link', array( $this, 'portfolio_archive_page_link' ), 10, 2 );
	}

	public function enqueue_styles() {
		$layout = alpha_get_page_layout();
		if ( 'portfolio' == substr( $layout, -9 ) ) {
			wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			wp_enqueue_style( 'alpha-portfolio', ALPHA_CORE_INC_URI . '/cpt/post_types/portfolio/assets/portfolio' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array( 'alpha-post' ), ALPHA_CORE_VERSION );
			if ( is_single() ) {
				wp_enqueue_style( 'alpha-single-portfolio', ALPHA_CORE_INC_URI . '/cpt/post_types/portfolio/assets/single-portfolio' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
			}
		}
	}

	public function add_post_type() {

		$slug  = alpha_get_option( 'portfolio_slug' );
		$slug2 = alpha_get_option( 'portfolios_slug' );

		$this->cpt = esc_html__( 'Portfolios', 'alpha-core' );
		if ( $slug ) {
			$this->cpt_single = ucfirst( $slug );
			$this->cpt_slug   = strtolower( $slug );
		}
		if ( $slug2 ) {
			$this->cpt = ucfirst( $slug2 );
		}

		$cpt_single_low = strtolower( $this->cpt_single );

		global $alpha_cpt;
		$alpha_cpt['cpt']              = array();
		$alpha_cpt['cpt']['portfolio'] = array(
			'archive' => $this->cpt,
			'single'  => $this->cpt_single,
			'slug'    => $this->cpt_slug,
		);

		$portfolio_labels = array(
			'name'               => $this->cpt,
			'singular_name'      => sprintf( esc_html__( '%s item', 'alpha-core' ), $this->cpt_single ),
			'search_items'       => sprintf( esc_html__( 'Search %s items', 'alpha-core' ), $cpt_single_low ),
			'all_items'          => sprintf( esc_html__( 'All %s', 'alpha-core' ), $this->cpt ),
			'parent_item'        => sprintf( esc_html__( 'Parent %s item', 'alpha-core' ), $cpt_single_low ),
			'edit_item'          => sprintf( esc_html__( 'Edit %s item', 'alpha-core' ), $cpt_single_low ),
			'update_item'        => sprintf( esc_html__( 'Update %s item', 'alpha-core' ), $cpt_single_low ),
			'add_new_item'       => sprintf( esc_html__( 'Add New %s item', 'alpha-core' ), $cpt_single_low ),
			'not_found'          => sprintf( esc_html__( 'No %s items found', 'alpha-core' ), $cpt_single_low ),
			'not_found_in_trash' => sprintf( esc_html__( 'No %s items found in trash', 'alpha-core' ), $cpt_single_low ),
		);

		$portfolio_menu_icon = 'dashicons-portfolio';

		$slug = alpha_get_option( 'portfolio_slug' );
		if ( ! $slug ) {
			$slug = $this->cpt_slug;
		}

		$args = array(
			'labels'             => $portfolio_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_position'      => 10,
			'menu_icon'          => $portfolio_menu_icon,
			'has_archive'        => true,
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'comments',
				'revisions',
			),
			'rewrite'            => array( 'slug' => $this->cpt_slug ),
			'show_in_rest'       => true,
		);

		register_post_type( ALPHA_NAME . '_portfolio', apply_filters( 'alpha_portfolio_args', $args ) );

		// Portfolio Taxonomies
		register_taxonomy( // Categories
			ALPHA_NAME . '_portfolio_category',
			ALPHA_NAME . '_portfolio',
			apply_filters(
				'alpha_portfolio_category_args',
				array(
					'hierarchical'      => true,
					'public'            => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'labels'            => array(
						'name'              => sprintf( esc_html__( '%s categories', 'alpha-core' ), $this->cpt_single ), /* name of the custom taxonomy */
						'singular_name'     => sprintf( esc_html__( '%s category', 'alpha-core' ), $cpt_single_low ), /* single taxonomy name */
						'search_items'      => sprintf( esc_html__( 'Search %s categories', 'alpha-core' ), $cpt_single_low ), /* search title for taxomony */
						'all_items'         => sprintf( esc_html__( 'All %s categories', 'alpha-core' ), $cpt_single_low ), /* all title for taxonomies */
						'parent_item'       => sprintf( esc_html__( 'Parent %s category', 'alpha-core' ), $cpt_single_low ), /* parent title for taxonomy */
						'parent_item_colon' => sprintf( esc_html__( 'Parent %s category:', 'alpha-core' ), $cpt_single_low ), /* parent taxonomy title */
						'edit_item'         => sprintf( esc_html__( 'Edit %s category', 'alpha-core' ), $cpt_single_low ), /* edit custom taxonomy title */
						'update_item'       => sprintf( esc_html__( 'Update %s category', 'alpha-core' ), $cpt_single_low ), /* update title for taxonomy */
						'add_new_item'      => sprintf( esc_html__( 'Add new %s category', 'alpha-core' ), $cpt_single_low ), /* add new title for taxonomy */
						'new_item_name'     => sprintf( esc_html__( 'New %s category name', 'alpha-core' ), $cpt_single_low ), /* name title for taxonomy */
					),
					'query_var'         => true,
					'rewrite'           => array( 'slug' => $this->cpt_slug . '_cat' ),
					// For Gutenberg
					'show_in_rest'      => true,
				)
			)
		);

		register_taxonomy( // Skills
			ALPHA_NAME . '_portfolio_skill',
			ALPHA_NAME . '_portfolio',
			apply_filters(
				'alpha_portfolio_skill_args',
				array(
					'hierarchical'      => true,
					'public'            => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'labels'            => array(
						'name'              => sprintf( esc_html__( '%s skills', 'alpha-core' ), $this->cpt_single ), /* name of the custom taxonomy */
						'singular_name'     => sprintf( esc_html__( '%s skill', 'alpha-core' ), $cpt_single_low ), /* single taxonomy name */
						'search_items'      => sprintf( esc_html__( 'Search %s skills', 'alpha-core' ), $cpt_single_low ), /* search title for taxomony */
						'all_items'         => sprintf( esc_html__( 'All %s skills', 'alpha-core' ), $cpt_single_low ), /* all title for taxonomies */
						'parent_item'       => sprintf( esc_html__( 'Parent %s skill', 'alpha-core' ), $cpt_single_low ), /* parent title for taxonomy */
						'parent_item_colon' => sprintf( esc_html__( 'Parent %s skill:', 'alpha-core' ), $cpt_single_low ), /* parent taxonomy title */
						'edit_item'         => sprintf( esc_html__( 'Edit %s skill', 'alpha-core' ), $cpt_single_low ), /* edit custom taxonomy title */
						'update_item'       => sprintf( esc_html__( 'Update %s skill', 'alpha-core' ), $cpt_single_low ), /* update title for taxonomy */
						'add_new_item'      => sprintf( esc_html__( 'Add new %s skill', 'alpha-core' ), $cpt_single_low ), /* add new title for taxonomy */
						'new_item_name'     => sprintf( esc_html__( 'New %s skill name', 'alpha-core' ), $cpt_single_low ), /* name title for taxonomy */
					),
					'rewrite'           => array( 'slug' => $this->cpt_slug . '_skill' ),
					// For Gutenberg
					'show_in_rest'      => true,
				)
			)
		);
	}

	/**
	 * Add meta box tab
	 *
	 * @since 4.0.0
	 * @param array $meta_tabs
	 * @param string $post_type
	 * @return array $meta_tabs
	 */
	public function add_meta_tab( $meta_tabs, $post_type ) {
		if ( ALPHA_NAME . '_portfolio' == $post_type ) {
			$meta_tabs['portfolio'] = array(
				'label'    => sprintf( __( '%s Options', 'alpha-core' ), $this->cpt_single ),
				'icon'     => 'dashicons-portfolio',
				'priority' => 5,
			);
		}

		return $meta_tabs;
	}

	/**
	 * Add meta box fields
	 *
	 * @since 4.0.0
	 * @param array $meta_fields
	 * @param string $post_type
	 * @return array $meta_fields
	 */
	public function add_meta_fields( $meta_fields, $post_type ) {

		if ( ALPHA_NAME . '_portfolio' == $post_type ) {
			$desc_label   = strtolower( $this->cpt_single );
			$meta_fields += array(
				'supported_images'         => array(
					'id'                => 'supported_images',
					'type'              => 'image_advanced',
					'name'              => sprintf( esc_html__( '%s Images', 'alpha-core' ), $this->cpt_single ),
					'label_description' => sprintf( esc_html__( 'Choose your %s images', 'alpha-core' ), $desc_label ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'featured_video'           => array(
					'id'                => 'featured_video',
					'type'              => 'textarea',
					'name'              => esc_html__( 'Video Embed Code', 'alpha-core' ),
					'label_description' => esc_html__( 'Insert Youtube, Vimeo, a self-hosted video URL.', 'alpha-core' ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'portfolio_text'           => array(
					'id'                => 'portfolio_text',
					'type'              => 'text',
					'name'              => sprintf( esc_html__( '%s Link Text', 'alpha-core' ), $this->cpt_single ),
					'label_description' => sprintf( esc_html__( 'The custom %s text that will link.', 'alpha-core' ), $desc_label ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'portfolio_link'           => array(
					'id'                => 'portfolio_link',
					'type'              => 'text',
					'name'              => sprintf( esc_html__( '%s Link Url', 'alpha-core' ), $this->cpt_single ),
					'label_description' => sprintf( esc_html__( 'The URL the %s text links to.', 'alpha-core' ), $desc_label ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'portfolio_client_text'    => array(
					'id'                => 'portfolio_client_text',
					'type'              => 'text',
					'name'              => esc_html__( 'Client Link Text', 'alpha-core' ),
					'label_description' => esc_html__( 'The custom client text that will link.', 'alpha-core' ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'portfolio_client_link'    => array(
					'id'                => 'portfolio_client_link',
					'type'              => 'text',
					'name'              => esc_html__( 'Client Link Url', 'alpha-core' ),
					'label_description' => esc_html__( 'The URL the client text links to.', 'alpha-core' ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'portfolio_copyright_text' => array(
					'id'                => 'portfolio_copyright_text',
					'type'              => 'text',
					'name'              => esc_html__( 'Copyright Link Text', 'alpha-core' ),
					'label_description' => esc_html__( 'The custom copyright text that will link.', 'alpha-core' ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
				'portfolio_copyright_link' => array(
					'id'                => 'portfolio_copyright_link',
					'type'              => 'text',
					'name'              => esc_html__( 'Copyright Link Url', 'alpha-core' ),
					'label_description' => esc_html__( 'The URL the copyright text links to.', 'alpha-core' ),
					'tab'               => 'portfolio',
					'columns'           => 6,
					'priority'          => 9,
				),
			);
		}

		return $meta_fields;
	}

	public function add_options_list( $options ) {
		$options[] = 'portfolio_show_info';
		return $options;
	}

	/**
	 * Modify the query params (using the 'pre_get_posts' filter)
	 *
	 * @param  object $query The WP Query object.
	 * @return  object
	 */
	public function set_post_filters( $query ) {

		if ( ! function_exists( 'alpha_get_option' ) ) {
			return;
		}

		if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( ALPHA_NAME . '_portfolio' ) || $query->is_tax( array( ALPHA_NAME . '_portfolio_category', ALPHA_NAME . '_portfolio_skill' ) ) ) ) {
			// If TO setting is set to 0, all items should show.
			$portfolios_count = alpha_get_option( 'portfolios_count' );
			$query->set( 'posts_per_page', $portfolios_count );
		}

		return $query;
	}

	/**
	 * Setup posts loop
	 *
	 * @param  object $query The WP Query object.
	 * @return  object
	 */
	public function setup_posts_loop( $query ) {

		$cpt = alpha_get_loop_prop( 'cpt' );
		if ( 'portfolio' == $cpt ) {
			$type = alpha_get_loop_prop( 'type' );

			if ( alpha_get_option( 'rollover' ) ) {
				alpha_set_loop_prop( 'rollover', true );

				$wrapper_class   = alpha_get_loop_prop( 'wrapper_class', array() );
				$wrapper_class[] = 'gallery-popup-container';
				alpha_set_loop_prop( 'wrapper_class', $wrapper_class );

				$loop_classes   = alpha_get_loop_prop( 'loop_classes', array() );
				$loop_classes[] = 'rollover-container';
				alpha_set_loop_prop( 'loop_classes', $loop_classes );
			}
			if ( ! alpha_get_loop_prop( 'read_more_label' ) ) {
				alpha_set_loop_prop( 'read_more_label', alpha_get_option( $cpt . '_read_more_label' ) ? alpha_get_option( $cpt . '_read_more_label' ) : esc_html__( 'View More', 'alpha-core' ) . '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i>' );
			}
		}
	}

	// Elementor functions
	public function register_elementor_widget( $self ) {
		include_once ALPHA_CORE_INC . '/cpt/post_types/portfolio/widgets/widget-portfolio-elementor.php';
		$class_name = 'Alpha_Portfolio_Elementor_Widget';
		$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
	}

	public function add_elementor_ajax_post_types( $post_types ) {
		$post_types[] = ALPHA_NAME . '_portfolio';
		return $post_types;
	}

	public function add_elementor_ajax_taxonomies( $taxonomies ) {
		$taxonomies[] = ALPHA_NAME . '_portfolio_category';
		$taxonomies[] = ALPHA_NAME . '_portfolio_skill';
		return $taxonomies;
	}

	// Add search content type
	public function add_to_search_content( $types ) {
		$types[ ALPHA_NAME . '_portfolio' ] = $this->cpt_single;
		return $types;
	}

	public function add_shortcode_portfolio( $atts, $content = null ) {

		global $wp_query, $post;

		if ( $post && is_post_type_archive( ALPHA_NAME . '_portfolio' ) ) {
			$post->post_type = ALPHA_NAME . '_portfolio';
		}

		$origin = $wp_query;

		$args = array(
			'post_type'      => ALPHA_NAME . '_portfolio',
			'posts_per_page' => alpha_get_option( 'portfolios_count' ),
		);

		$wp_query = new WP_Query( $args );

		ob_start();

		alpha_get_template_part( 'posts/archive' );

		$ret = ob_get_clean();

		$wp_query = $origin;
		wp_reset_postdata();

		return $ret;

	}

	// public function create_portfolio_page() {
	// 	if ( ( ! $this->archive_page_id || ! get_post( $this->archive_page_id ) ) && ! alpha_doing_ajax() ) { // Prevent when demo importing
	// 		alpha_create_page( esc_sql( $this->cpt_slug ), 'portfolio_page_id', $this->cpt, '<!-- wp:shortcode -->[' . ALPHA_NAME . '_portfolio' . ']<!-- /wp:shortcode -->', '' );
	// 		$this->archive_page_id = get_option( 'portfolio_page_id' );
	// 	}
	// }

	// public function nav_menu_item_classes( $menu_items ) {
	// 	/**
	// 	 * Fix active class in nav for shop page.
	// 	 *
	// 	 * @param array $menu_items Menu items.
	// 	 * @return array
	// 	 */

	// 	if ( ! empty( $menu_items ) && is_array( $menu_items ) ) {
	// 		foreach ( $menu_items as $key => $menu_item ) {
	// 			$classes = (array) $menu_item->classes;
	// 			$menu_id = (int) $menu_item->object_id;

	// 			// Unset active class for blog page.
	// 			if ( ( is_post_type_archive( ALPHA_NAME . '_portfolio' ) || is_page( $this->archive_page_id ) ) && $this->archive_page_id == $menu_id && 'page' == $menu_item->object ) {
	// 				// Set active state if this is the shop page link.
	// 				$menu_items[ $key ]->current = true;
	// 				$classes[]                   = 'current-menu-item';
	// 				$classes[]                   = 'current_page_item';

	// 			} elseif ( is_singular( ALPHA_NAME . '_portfolio' ) && $this->archive_page_id == $menu_id ) {
	// 				// Set parent state if this is a portfolio page.
	// 				$classes[] = 'current_page_parent';
	// 			}

	// 			$menu_items[ $key ]->classes = array_unique( $classes );
	// 		}
	// 	}

	// 	return $menu_items;
	// }

	// public function portfolio_archive_page_title( $title, $post_type ) {

	// 	// Compatiblity with breadcrumb

	// 	if ( ! $this->archive_page_id ) {
	// 		return $title;
	// 	}

	// 	$post_type_object = get_post_type_object( $post_type );
	// 	if ( is_object( $post_type_object ) ) {
	// 		if ( isset( $post_type_object->label ) && '' !== $post_type_object->label ) {
	// 			if ( $this->cpt == $post_type_object->label ) {
	// 				$title = $this->cpt;
	// 			}
	// 		} elseif ( isset( $post_type_object->labels->menu_name ) && '' !== $post_type_object->labels->menu_name ) {
	// 			if ( $this->cpt == $post_type_object->labels->menu_name ) {
	// 				$title = $this->cpt;
	// 			}
	// 		} else {
	// 			if ( $this->cpt == $post_type_object->name ) {
	// 				$title = $this->cpt;
	// 			}
	// 		}
	// 	}

	// 	return $title;
	// }

	// public function portfolio_archive_page_link( $link, $post_type ) {

	// 	// Compatiblity with breadcrumb

	// 	if ( ! $this->archive_page_id ) {
	// 		return $link;
	// 	}

	// 	if ( ALPHA_NAME . '_portfolio' == $post_type ) {
	// 		return get_permalink( $this->archive_page_id );
	// 	}

	// 	return $link;
	// }

}

Alpha_Portfolio::get_instance();

add_shortcode( ALPHA_NAME . '_portfolio', array( Alpha_Portfolio::get_instance(), 'add_shortcode_portfolio' ) );

