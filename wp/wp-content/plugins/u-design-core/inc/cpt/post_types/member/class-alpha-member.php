<?php
/**
 * Alpha Member Custom Post Type
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0.0
 */
if ( ! function_exists( 'alpha_get_option' ) || ! alpha_get_option( 'enable_member' ) ) {
	return;
}

/**
 * Alpha Member Class
 *
 * @since 4.0.0
 */
class Alpha_Member extends Alpha_Base {

	public $cpt;
	public $cpt_single;
	public $cpt_slug;
	public $archive_page_id;
	public $preview_mode = '';

	/**
	 * Constructor
	 *
	 * @since 4.0.0
	 */
	public function __construct() {

		$this->cpt             = esc_html__( 'Members', 'alpha-core' );
		$this->cpt_single      = esc_html__( 'Member', 'alpha-core' );
		$this->cpt_slug        = 'member';
		$this->archive_page_id = get_option( 'member_page_id' );

		add_action( 'init', array( $this, 'add_post_type' ) );
		// add_action( 'init', array( $this, 'add_shortcode_member' ) );
		// add_action( 'init', array( $this, 'create_member_page' ) );

		// Add menu active classes
		// add_filter( 'wp_nav_menu_objects', array( $this, 'nav_menu_item_classes' ) );

		// Add custom meta box
		add_filter( 'alpha_metabox_tabs', array( $this, 'add_meta_tab' ), 10, 2 );
		add_filter( 'alpha_metabox_fields', array( $this, 'add_meta_fields' ), 10, 2 );

		// Load styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 23 );

		// Reset members count per page
		add_filter( 'pre_get_posts', array( $this, 'set_post_filters' ), 5 );

		// Add widgets to Elementor or WPBakery page builder
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widget' ) );
			add_filter( 'alpha_select_post_types', array( $this, 'add_elementor_ajax_post_types' ) );
			add_filter( 'alpha_select_taxonomies', array( $this, 'add_elementor_ajax_taxonomies' ) );
		}

		// Set image size
		add_filter( 'alpha_post_loop_default_args', array( $this, 'reset_default_args' ) );

		// Booking appointment
		add_action( 'wp_ajax_alpha_member_book_appointment', array( $this, 'alpha_member_book_appointment' ) );
		add_action( 'wp_ajax_nopriv_alpha_member_book_appointment', array( $this, 'alpha_member_book_appointment' ) );

		// Extend search content
		// add_filter( 'alpha_search_content_types', array( $this, 'add_to_search_content' ) );

		// Member page breadcrumb
		// add_filter( 'alpha_get_archive_name', array( $this, 'member_archive_page_title' ), 10, 2 );
		// add_filter( 'alpha_get_archive_link', array( $this, 'member_archive_page_link' ), 10, 2 );
	}


	/**
	 * Enqueue styles
	 *
	 * @since 4.0.0
	 */
	public function enqueue_styles() {
		global $post;
		$layout = alpha_get_page_layout();
		if ( 'member' == substr( $layout, -6 ) ) {
			wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			wp_register_style( 'alpha-share', ALPHA_CORE_INC_URI . '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );

			wp_enqueue_style( 'alpha-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/member.min.css', array( 'alpha-post', 'alpha-share' ), ALPHA_CORE_VERSION );
			wp_enqueue_script( 'alpha-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/member' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_VERSION, true );

			if ( is_singular( ALPHA_NAME . '_member' ) ) {
				wp_enqueue_style( 'alpha-single-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/single-member' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );

				wp_enqueue_style( 'bootstrap-datepicker', ALPHA_CORE_URI . '/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css', array(), ALPHA_CORE_VERSION );
				wp_enqueue_script( 'bootstrap-datepicker', ALPHA_CORE_URI . '/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_VERSION, true );
				wp_enqueue_style( 'bootstrap-timepicker', ALPHA_CORE_URI . '/assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.min.css', array(), ALPHA_CORE_VERSION );
				wp_enqueue_script( 'bootstrap-timepicker', ALPHA_CORE_URI . '/assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker' . ALPHA_JS_SUFFIX, array( 'jquery-core' ), ALPHA_VERSION, true );
			}
		}
	}


	/**
	 * Register a new post type named member
	 *
	 * @since 4.0.0
	 */
	public function add_post_type() {

		$slug  = alpha_get_option( 'member_slug' );
		$slug2 = alpha_get_option( 'members_slug' );

		$this->cpt = esc_html__( 'Members', 'alpha-core' );
		if ( $slug ) {
			$this->cpt_single = ucfirst( $slug );
			$this->cpt_slug   = strtolower( $slug );
		}
		if ( $slug2 ) {
			$this->cpt = ucfirst( $slug2 );
		}

		$cpt_single_low = strtolower( $this->cpt_single );
		$cpt_low        = strtolower( $this->cpt );

		global $alpha_cpt;
		$alpha_cpt['cpt']           = array();
		$alpha_cpt['cpt']['member'] = array(
			'archive' => $this->cpt,
			'single'  => $this->cpt_single,
			'slug'    => $this->cpt_slug,
		);

		$member_labels = array(
			'name'               => $this->cpt,
			'singular_name'      => sprintf( esc_html__( '%s item', 'alpha-core' ), $this->cpt_single ),
			'search_items'       => sprintf( esc_html__( 'Search %s', 'alpha-core' ), $cpt_low ),
			'all_items'          => sprintf( esc_html__( 'All %s', 'alpha-core' ), $this->cpt ),
			'parent_item'        => sprintf( esc_html__( 'Parent %s', 'alpha-core' ), $cpt_single_low ),
			'edit_item'          => sprintf( esc_html__( 'Edit %s', 'alpha-core' ), $cpt_single_low ),
			'update_item'        => sprintf( esc_html__( 'Update %s', 'alpha-core' ), $cpt_single_low ),
			'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'alpha-core' ), $cpt_single_low ),
			'not_found'          => sprintf( esc_html__( 'No %s found', 'alpha-core' ), $cpt_low ),
			'not_found_in_trash' => sprintf( esc_html__( 'No %s found in trash', 'alpha-core' ), $cpt_low ),
		);

		$member_menu_icon = 'dashicons-businessman';

		$args = array(
			'labels'             => $member_labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_position'      => 10,
			'menu_icon'          => $member_menu_icon,
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

		register_post_type( ALPHA_NAME . '_member', apply_filters( 'alpha_member_args', $args ) );

		// Member Taxonomies
		register_taxonomy( // Categories
			ALPHA_NAME . '_member_category',
			ALPHA_NAME . '_member',
			apply_filters(
				'alpha_member_category_args',
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
		if ( ALPHA_NAME . '_member' == $post_type ) {
			$meta_tabs['member'] = array(
				'label'    => sprintf( __( '%s Options', 'alpha-core' ), $this->cpt_single ),
				'icon'     => 'dashicons-admin-users',
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

		if ( ALPHA_NAME . '_member' == $post_type ) {
			$desc_label   = strtolower( $this->cpt_single );
			$meta_fields += array(
				'member_email_addr' => array(
					'id'       => 'member_email_addr',
					'type'     => 'text',
					'name'     => esc_html__( 'E-mail Address', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 9,
				),
				'member_phone'      => array(
					'id'       => 'member_phone',
					'type'     => 'text',
					'name'     => esc_html__( 'Phone Number', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 9,
				),
				'member_profile'    => array(
					'id'       => 'member_profile',
					'type'     => 'textarea',
					'name'     => esc_html__( 'Short Description', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 9,
				),

				'member_facebook'   => array(
					'id'       => 'member_facebook',
					'type'     => 'text',
					'name'     => esc_html__( 'Facebook Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_twitter'    => array(
					'id'       => 'member_twitter',
					'type'     => 'text',
					'name'     => esc_html__( 'Twitter Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_linkedin'   => array(
					'id'       => 'member_linkedin',
					'type'     => 'text',
					'name'     => esc_html__( 'LinkedIn Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_email'      => array(
					'id'       => 'member_email',
					'type'     => 'text',
					'name'     => esc_html__( 'Email Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_google'     => array(
					'id'       => 'member_google',
					'type'     => 'text',
					'name'     => esc_html__( 'Google Plus Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_pinterest'  => array(
					'id'       => 'member_pinterest',
					'type'     => 'text',
					'name'     => esc_html__( 'Pinterest Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_vk'         => array(
					'id'       => 'member_vk',
					'type'     => 'text',
					'name'     => esc_html__( 'VK Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_xing'       => array(
					'id'       => 'member_xing',
					'type'     => 'text',
					'name'     => esc_html__( 'Xing Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_tumblr'     => array(
					'id'       => 'member_tumblr',
					'type'     => 'text',
					'name'     => esc_html__( 'Tumblr Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_reddit'     => array(
					'id'       => 'member_reddit',
					'type'     => 'text',
					'name'     => esc_html__( 'Reddit Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_vimeo'      => array(
					'id'       => 'member_vimeo',
					'type'     => 'text',
					'name'     => esc_html__( 'Vimeo Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_instagram'  => array(
					'id'       => 'member_instagram',
					'type'     => 'text',
					'name'     => esc_html__( 'Instagram Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
				'member_whatsapp'   => array(
					'id'       => 'member_whatsapp',
					'type'     => 'text',
					'name'     => esc_html__( 'WhatsApp Url', 'alpha-core' ),
					'tab'      => 'member',
					'columns'  => 6,
					'priority' => 11,
				),
			);
		}

		return $meta_fields;
	}

	/**
	 * Add options
	 *
	 * @since 4.0.0
	 */
	public function add_options_list( $options ) {
		$options[] = 'member_show_info';
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

		if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( ALPHA_NAME . '_member' ) || $query->is_tax( ALPHA_NAME . '_member_category' ) ) ) {
			// If TO setting is set to 0, all items should show.
			$members_count = alpha_get_option( 'members_count' );
			$query->set( 'posts_per_page', $members_count );
		}

		return $query;
	}


	/**
	 * Add member elementor widget
	 *
	 * @since 4.0.0
	 */
	public function register_elementor_widget( $self ) {
		global $post;

		// Book Now Button Widget for Single Builder
		global $post, $alpha_layout;

		$register = $post && ALPHA_NAME . '_template' == $post->post_type && 'single' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true );

		if ( ! $register ) {
			global $alpha_layout;
			$register           = ! empty( $alpha_layout['single_block'] ) && is_numeric( $alpha_layout['single_block'] );
			$this->preview_mode = true;
		}

		if ( $register && ALPHA_NAME . '_member' == Alpha_Template_Single_Builder::get_instance()->preview_mode ) {
			wp_enqueue_style( 'alpha-single-member', ALPHA_CORE_INC_URI . '/cpt/post_types/member/assets/single-member' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
			include_once ALPHA_CORE_INC . '/cpt/post_types/member/widgets/book/widget-book-elementor.php';
			$class_name = 'Alpha_Single_Book_Elementor_Widget';
			$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
		}

		include_once ALPHA_CORE_INC . '/cpt/post_types/member/widgets/member/widget-member-elementor.php';
		$class_name = 'Alpha_Member_Elementor_Widget';
		$self->register( new $class_name( array(), array( 'widget_name' => $class_name ) ) );
	}


	/**
	 * Add elementor ajax post type
	 *
	 * @since 4.0.0
	 */
	public function add_elementor_ajax_post_types( $post_types ) {
		$post_types[] = ALPHA_NAME . '_member';
		return $post_types;
	}


	/**
	 * Add elementor ajax taxonomy
	 *
	 * @since 4.0.0
	 */
	public function add_elementor_ajax_taxonomies( $taxonomies ) {
		$taxonomies[] = ALPHA_NAME . '_member_category';
		return $taxonomies;
	}

	/**
	 * Set default image size in archive
	 *
	 * @since 4.0.0
	 */
	public function reset_default_args( $args ) {
		if ( 'member' == $args['cpt'] ) {
			$args['image_size'] = 'medium';
		}
		return $args;
	}

	/**
	 * Handle ajax request to book appointment
	 *
	 * @since 1.0
	 */
	public function alpha_member_book_appointment() {

		if ( empty( $_POST['data'] ) ) {
			wp_send_json_error( esc_html__( 'Please review your enquiry and send again', 'alpha-core' ) );
		} else {
			$data  = $_POST['data'];
			$nonce = $data['nonce'];

			if ( ! wp_verify_nonce( $nonce, 'alpha-nonce' ) ) {
			} elseif ( empty( $data['contact'] ) ) {
				wp_send_json_error( esc_html__( 'Please insert valid phone', 'alpha-core' ) );
			} elseif ( empty( $data['name'] ) ) {
				wp_send_json_error( esc_html__( 'Please insert valid name', 'alpha-core' ) );
			} elseif ( empty( $data['time'] ) ) {
				wp_send_json_error( esc_html__( 'Please choose a valid time', 'alpha-core' ) );
			} elseif ( empty( $data['date'] ) ) {
				wp_send_json_error( esc_html__( 'Please choose a valid date', 'alpha-core' ) );
			} elseif ( empty( $data['member'] ) ) {
				wp_send_json_error( sprintf( esc_html__( 'Please select a %s', 'alpha-core' ), strtolower( $this->cpt_single ) ) );
			} else {
				$mail_to = alpha_get_option( 'member_booking_email' );
				if ( empty( $mail_to ) ) {
					if ( ! empty( $data['member_id'] ) && (int) $data['member_id'] ) {
						$mail_to = get_post_meta( (int) $data['member_id'], 'member_email_addr', true );
					}
					if ( empty( $mail_to ) ) {
						$mail_to = get_option( 'admin_email' );
					}
				}

				$message  = esc_html__( 'From', 'alpha-core' ) . ': ' . $data['name'] . "\r\n";
				$message .= esc_html__( 'Phone', 'alpha-core' ) . ': ' . $data['contact'] . "\r\n\n";
				$message .= esc_html__( 'Member', 'alpha-core' ) . ': ' . $data['member'] . "\r\n\n";
				$message .= esc_html__( 'Requested date', 'alpha-core' ) . ': ' . $data['date'] . "\r\n";
				$message .= esc_html__( 'Requested time', 'alpha-core' ) . ': ' . $data['time'] . "\r\n\n";
				$message .= esc_html__( 'Message', 'alpha-core' ) . ': ' . $message . "\r\n\n";

				//Send Mail and response
				if ( wp_mail( $mail_to, esc_html__( 'New Booking Request', 'alpha-core' ), $message ) ) {
					wp_send_json_success( esc_html__( 'Booking request sent successfully', 'alpha-core' ) );
				} else {
					wp_send_json_error( esc_html__( 'Booking request failed. Please try later.', 'alpha-core' ) );
				}
			}
		}

		exit();
	}

	// Add search content type
	public function add_to_search_content( $types ) {
		$types[ ALPHA_NAME . '_member' ] = $this->cpt_single;
		return $types;
	}

	public function add_shortcode_member( $atts, $content = null ) {

		global $wp_query, $post;

		if ( $post && is_post_type_archive( ALPHA_NAME . '_member' ) ) {
			$post->post_type = ALPHA_NAME . '_member';
		}

		$origin = $wp_query;

		$args = array(
			'post_type'      => ALPHA_NAME . '_member',
			'posts_per_page' => alpha_get_option( 'members_count' ),
		);

		$wp_query = new WP_Query( $args );

		ob_start();

		alpha_get_template_part( 'posts/archive' );

		$ret = ob_get_clean();

		$wp_query = $origin;
		wp_reset_postdata();

		return $ret;

	}

	// public function create_member_page() {
	// 	if ( ( ! $this->archive_page_id || ! get_post( $this->archive_page_id ) ) && ! alpha_doing_ajax() ) { // Prevent when demo importing
	// 		alpha_create_page( esc_sql( $this->cpt_slug ), 'member_page_id', $this->cpt, '<!-- wp:shortcode -->[' . ALPHA_NAME . '_member' . ']<!-- /wp:shortcode -->', '' );
	// 		$this->archive_page_id = get_option( 'member_page_id' );
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
	// 			if ( ( is_post_type_archive( ALPHA_NAME . '_member' ) || is_page( $this->archive_page_id ) ) && $this->archive_page_id == $menu_id && 'page' == $menu_item->object ) {
	// 				// Set active state if this is the shop page link.
	// 				$menu_items[ $key ]->current = true;
	// 				$classes[]                   = 'current-menu-item';
	// 				$classes[]                   = 'current_page_item';

	// 			} elseif ( is_singular( ALPHA_NAME . '_member' ) && $this->archive_page_id == $menu_id ) {
	// 				// Set parent state if this is a member page.
	// 				$classes[] = 'current_page_parent';
	// 			}

	// 			$menu_items[ $key ]->classes = array_unique( $classes );
	// 		}
	// 	}

	// 	return $menu_items;
	// }

	// public function member_archive_page_title( $title, $post_type ) {

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

	// public function member_archive_page_link( $link, $post_type ) {

	// 	// Compatiblity with breadcrumb

	// 	if ( ! $this->archive_page_id ) {
	// 		return $link;
	// 	}

	// 	if ( ALPHA_NAME . '_member' == $post_type ) {
	// 		return get_permalink( $this->archive_page_id );
	// 	}

	// 	return $link;
	// }

}

Alpha_Member::get_instance();

add_shortcode( ALPHA_NAME . '_member', array( Alpha_Member::get_instance(), 'add_shortcode_member' ) );

