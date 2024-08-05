<?php
/**
 * Alpha Admin Meta Boxes
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_Admin_Meta_Boxes extends Alpha_Base {

	private $meta_boxes = array();

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		if ( is_admin() ) {
			// Load meta box extensions
			if ( ! class_exists( 'MB_Tabs' ) ) {
				require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/meta-box-tabs/meta-box-tabs.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/mb-rest-api/mb-rest-api.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/mb-settings-page/mb-settings-page.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/mb-term-meta/mb-term-meta.php' );
				require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/meta-box-columns/meta-box-columns.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/meta-box-conditional-logic/meta-box-conditional-logic.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/meta-box-group/meta-box-group.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/meta-box-include-exclude/meta-box-include-exclude.php' );
				// require_once alpha_core_framework_path( ALPHA_CORE_PLUGINS . '/meta-box/extensions/meta-box-show-hide/meta-box-show-hide.php' );
			}

			// Add video and more images to post
			add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_box' ) );
			// Avoid sanitizing css field
			add_filter( 'rwmb_sanitize', array( $this, 'rwmb_sanitize' ), 15, 4 );
			// Add product category icon meta form fields.
			if ( class_exists( 'WooCommerce' ) ) {
				add_action( 'product_cat_edit_form_fields', array( $this, 'add_product_cat_fields' ), 100 );
				add_action( 'product_cat_add_form_fields', array( $this, 'add_product_cat_fields' ), 100 );
				add_action( 'created_term', array( $this, 'save_term_meta_box' ), 10, 3 );
				add_action( 'edit_term', array( $this, 'save_term_meta_box' ), 100, 3 );
			}
		}

		// @start feature: fs_plugin_acf
		add_filter( 'alpha_dynamic_tags', array( $this, 'metabox_add_tags' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'get_meta_boxes' ), 99999 );
		add_filter( 'alpha_dynamic_extra_fields_content', array( $this, 'metabox_render' ), 10, 3 );
		add_filter( 'alpha_dynamic_field_object', array( $this, 'metabox_source' ) );
		add_action( 'alpha_dynamic_extra_fields', array( $this, 'metabox_add_control' ), 10, 3 );
		// @end feature: fs_plugin_acf
	}


	/**
	 * Comparison function for priority
	 *
	 * @since 1.0
	 */
	public function sort_priority( $a, $b ) {
		$ap = isset( $a['priority'] ) ? (int) $a['priority'] : 10;
		$bp = isset( $b['priority'] ) ? (int) $b['priority'] : 10;
		return $ap - $bp;
	}

	/**
	 * Add meta box.
	 *
	 * video and more images to post
	 *
	 * @since 1.0
	 */
	public function add_meta_box( $meta_boxes ) {
		// Get current edit page's post type.
		$post_type = '';
		if ( 'post-new.php' == $GLOBALS['pagenow'] ) {
			$post_type = empty( $_GET['post_type'] ) ? 'post' : $_GET['post_type'];
		} elseif ( 'post.php' == $GLOBALS['pagenow'] ) {
			if ( isset( $_GET['action'] ) && ! empty( $_GET['post'] ) ) {
				$post_type = get_post_type( (int) $_GET['post'] );
			}
		} else {
			return $meta_boxes;
		}

		if ( alpha_is_elementor_preview() && ALPHA_NAME . '_template' == $post_type && ( 'single' == get_post_meta( (int) $_GET['post'], ALPHA_NAME . '_template_type', true ) || 'archive' == get_post_meta( (int) $_GET['post'], ALPHA_NAME . '_template_type', true ) ) ) {
			do_action( 'alpha_core_dynamic_before_render', $post_type, (int) $_GET['post'] );
			$post_type = get_post_type();
		}

		// Define meta box tabs
		$meta_tabs = array(
			'titles'  => array(
				'label' => __( 'Page Titles', 'alpha-core' ),
				'icon'  => 'dashicons-heading',
			),
			'scripts' => array(
				'label' => __( 'Custom Scripts', 'alpha-core' ),
				'icon'  => 'dashicons-editor-code',
			),
		);

		// Define meta box fields
		$meta_fields = array(
			'page_title'    => array(
				'id'      => 'page_title',
				'name'    => __( 'Page Title', 'alpha-core' ),
				'desc'    => '',
				'type'    => 'text',
				'std'     => '',
				'columns' => 12,
				'tab'     => 'titles',
			),
			'page_subtitle' => array(
				'id'      => 'page_subtitle',
				'name'    => __( 'Page Subtitle', 'alpha-core' ),
				'desc'    => '',
				'type'    => 'text',
				'std'     => '',
				'columns' => 12,
				'tab'     => 'titles',
			),
			'page_css'      => array(
				'id'      => 'page_css',
				'name'    => __( 'Custom CSS', 'alpha-core' ),
				'type'    => 'textarea',
				'columns' => 12,
				'rows'    => 10,
				'tab'     => 'scripts',
			),
		);
		if ( current_user_can( 'unfiltered_html' ) ) {
			$meta_fields['page_js'] = array(
				'id'      => 'page_js',
				'name'    => __( 'Custom JS', 'alpha-core' ),
				'type'    => 'textarea',
				'columns' => 12,
				'rows'    => 10,
				'tab'     => 'scripts',
			);
		}

		// Fields for Posts
		if ( 'post' == $post_type ) {
			$meta_tabs['post'] = array(
				'label'    => __( 'Post Options', 'alpha-core' ),
				'icon'     => 'dashicons-admin-post',
				'priority' => 5,
			);
		}
		if ( 'post' == $post_type || ALPHA_NAME . '_template' == $post_type ) {

			$meta_fields['supported_images'] = array(
				'id'                => 'supported_images',
				'type'              => 'file_advanced',
				'name'              => esc_html__( 'Supported Images', 'alpha-core' ),
				'save_field'        => true,
				'label_description' => esc_html__( 'These images will be shown as slider with Featured Image.', 'alpha-core' ),
				'tab'               => 'post',
			);
			$meta_fields['featured_video']   = array(
				'id'                => 'featured_video',
				'type'              => 'textarea',
				'name'              => esc_html__( 'Featured Video', 'alpha-core' ),
				'save_field'        => true,
				'label_description' => esc_html__( 'Input embed code or use shortcodes. ex) iframe-tag or', 'alpha-core' ) . ' [video src="url.mp4"]',
				'tab'               => 'post',
			);
		}

		/**
		 * Filters metabox tabs.
		 *
		 * @since 1.0
		 */
		$meta_tabs = apply_filters( 'alpha_metabox_tabs', $meta_tabs, $post_type );
		/**
		 * Filters metabox fields.
		 *
		 * @since 1.0
		 */
		$meta_fields = apply_filters( 'alpha_metabox_fields', $meta_fields, $post_type );

		uasort( $meta_tabs, array( $this, 'sort_priority' ) );
		usort( $meta_fields, array( $this, 'sort_priority' ) );

		$meta_boxes[] = array(
			'title'      => sprintf( esc_html__( '%s Options', 'alpha-core' ), ALPHA_DISPLAY_NAME ),
			'post_types' => get_post_types(),
			'tabs'       => $meta_tabs,
			'tab_style'  => 'left',
			'fields'     => $meta_fields,
		);

		if ( alpha_is_elementor_preview() && ALPHA_NAME . '_template' == $post_type && ( 'single' == get_post_meta( (int) $_GET['post'], ALPHA_NAME . '_template_type', true ) || 'archive' == get_post_meta( (int) $_GET['post'], ALPHA_NAME . '_template_type', true ) ) ) {
			do_action( 'alpha_core_dynamic_after_render', $post_type, (int) $_GET['post'] );
		}

		return $meta_boxes;
	}

	/**
	 * Add more form fields to product category.
	 *
	 * @since 1.0
	 */
	public function add_product_cat_fields( $tag ) {
		if ( is_object( $tag ) ) : ?>
			<tr class="form-field">
				<th scope="row"><label for="product_cat_icon"><?php esc_html_e( 'Category Icon', 'alpha-core' ); ?></label></th>
				<td>
					<input name="product_cat_icon" id="product_cat_icon" type="text" value="<?php echo esc_attr( get_term_meta( $tag->term_id, 'product_cat_icon', true ) ); ?>" placeholder="<?php esc_attr_e( 'Input icon class here...', 'alpha-core' ); ?>">
				</td>
			</tr>
		<?php else : ?>
			<div class="form-field">
				<label for="product_cat_icon"><?php esc_html_e( 'Category Icon', 'alpha-core' ); ?></label>
				<input name="product_cat_icon" id="product_cat_icon" type="text" placeholder="<?php esc_attr_e( 'Input icon class here...', 'alpha-core' ); ?>">
			</div>
			<?php
		endif;
	}

	/**
	 * save form field meta box
	 *
	 * @since 1.0
	 */
	public function save_term_meta_box( $term_id, $tt_id, $taxonomy ) {
		if ( 'product_cat' == $taxonomy ) {
			if ( isset( $_POST['product_cat_icon'] ) ) {
				update_term_meta( $term_id, 'product_cat_icon', $_POST['product_cat_icon'] );
			} else {
				delete_term_meta( $term_id, 'product_cat_icon' );
			}
		}
	}

	/**
	 * Returns support acf types
	 *
	 * @return array
	 */
	public function get_metabox_types() {

		return array(
			'text'           => array( 'field', 'link' ),
			'input'          => array( 'field', 'link' ),
			'textarea'       => array( 'field' ),
			'number'         => array( 'field' ),
			'range'          => array( 'field' ),
			'email'          => array( 'field', 'link' ),
			'url'            => array( 'field', 'link' ),
			'image'          => array( 'link', 'image' ),
			'image_advanced' => array( 'link', 'image' ),
			'video'          => array( 'link', 'image' ),
			'file_advanced'  => array( 'link', 'image' ),
			'select'         => array( 'field' ),
			'checkbox'       => array( 'field' ),
			'radio'          => array( 'field' ),
			'true_false'     => array( 'field' ),
			'link'           => array( 'field', 'link' ),
			'page_link'      => array( 'field', 'link' ),
			'post_object'    => array( 'field', 'link' ),
			'taxonomy'       => array( 'field', 'link' ),
		);
	}

	/**
	 * Map images callback
	 *
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 */
	public function filter_images( $field ) {

		$whitelisted = $this->get_metabox_types();
		$type        = $field['type'];

		if ( ! isset( $whitelisted[ $type ] ) ) {
			return false;
		}

		if ( ! in_array( 'image', $whitelisted[ $type ] ) ) {
			return false;
		} else {
			return isset( $field['name'] ) ? $field['name'] : '';
		}
	}

	/**
	 * Map links callback
	 *
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 */
	public function filter_link( $field ) {

		$whitelisted = $this->get_metabox_types();
		$type        = $field['type'];

		if ( ! isset( $whitelisted[ $type ] ) ) {
			return false;
		}

		if ( ! in_array( 'link', $whitelisted[ $type ] ) ) {
			return false;
		} else {
			return isset( $field['name'] ) ? $field['name'] : '';
		}

	}

	/**
	 * Map fields callback
	 *
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 *
	 * @since 1.0
	 */
	public function filter_fields( $field ) {

		$whitelisted = $this->get_metabox_types();
		$type        = $field['type'];

		if ( ! isset( $whitelisted[ $type ] ) ) {
			return false;
		}

		if ( ! in_array( 'field', $whitelisted[ $type ] ) ) {
			return false;
		} else {
			return $field['name'];
		}

	}

	/**
	 * Store metbaxes list to use it in controls
	 *
	 * @param  array  $meta_boxes [description]
	 * @return [type]             [description]
	 * @since 1.0
	 */
	public function get_meta_boxes( $meta_boxes = array() ) {
		$raw = $meta_boxes;

		foreach ( $raw as $meta_box ) {

			$fields = array();

			if ( ! empty( $meta_box['fields'] ) ) {
				foreach ( $meta_box['fields'] as $field ) {
					$fields[ $field['id'] ] = $field;
				}
			}

			$meta_box['fields'] = $fields;
			$this->meta_boxes[] = $meta_box;

		}
		return $meta_boxes;
	}

	/**
	 * Add Dynamic Acf Tags
	 *
	 * @since 1.0
	 */
	public function metabox_add_tags( $tags ) {
		array_push( $tags, 'Alpha_Core_Custom_Field_Meta_Box_Tag', 'Alpha_Core_Custom_Image_Meta_Box_Tag', 'Alpha_Core_Custom_Gallery_Tag' );
		return $tags;
	}

	/**
	 * Add Acf object to Dynamic Field
	 *
	 * @since 1.0
	 */
	public function metabox_source( $objects ) {
		$objects['meta-box'] = esc_html__( 'Meta Box', 'alpha-core' );
		return $objects;
	}

	/**
	 * Add control for ACF object
	 *
	 * @since 1.0
	 */
	public function metabox_add_control( $object, $widget = 'field', $plugin = 'meta-box' ) {
		if ( 'meta-box' == $plugin ) {
			$control_key = 'dynamic_metabox_' . $widget;

			if ( 'image' == $widget ) {
				$object->add_control(
					'add_featured_image',
					array(
						'label' => esc_html__( 'Add Featured Image', 'alpha-core' ),
						'type'  => Elementor\Controls_Manager::SWITCHER,
					)
				);
			}

			$object->add_control(
				$control_key,
				array(
					'label'   => esc_html__( 'MetaBox Field', 'alpha-core' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => '',
					'groups'  => $this->metabox_fields( $widget ),
				)
			);
		}
	}

	/**
	 * Get metabox fields.
	 *
	 * @since 1.0
	 */
	public function metabox_fields( $group = 'field' ) {

		$fields_group = array(
			'field' => 'filter_fields',
			'image' => 'filter_images',
			'link'  => 'filter_link',
		);

		global $post;
		
		$result = array();

		if ( empty( $post->post_type ) ) {
			return $result;
		}

		$type   = $post->post_type;
		$groups = $this->meta_boxes;

		if ( empty( $groups ) ) {
			return $result;
		}

		$metabox_types = $this->get_metabox_types();

		foreach ( $groups as $data ) {
			if ( ALPHA_NAME . '_template' != $type && false === in_array( $type, $data['post_types'] ) ) {
				continue;
			}

			if ( ! $group ) {
				$fields = $data['fields'];
				foreach ( $fields as &$field ) {
					if ( isset( $metabox_types[ $field['type'] ] ) ) {
						$field['field_type'] = $metabox_types[ $field['type'] ];
					}
				}
			} else {
				$fields = array_filter( array_map( array( $this, $fields_group[ $group ] ), $data['fields'] ) );
			}

			if ( ! empty( $fields ) ) {
				$result[] = array(
					'post_types' => $data['post_types'],
					'label'      => $data['title'],
					'options'    => $fields,
				);
			}
		}

		return $result;

	}

	/**
	 * Get meta.
	 *
	 * @since 1.0
	 */
	public function metabox_get_meta( $key ) {
		if ( ! $key ) {
			return null;
		}

		$post_id = get_the_ID();

		if ( 'page_title' == $key || 'page_subtitle' == $key ) {
			global $alpha_layout;
			if ( class_exists( 'WooCommerce' ) && is_shop() ) { // Shop Page
				$post_id = wc_get_page_id( 'shop' );
			} elseif ( is_home() && get_option( 'page_for_posts' ) ) { // Blog Page
				$post_id = get_option( 'page_for_posts' );
			}

			if ( 'page_title' == $key ) {
				Alpha_Layout_Builder::get_instance()->setup_titles();
				$meta_value = get_post_meta( $post_id, 'page_title' );
				if ( ! $meta_value ) {
					$meta_value = array( $alpha_layout['title'] );
				}
			} elseif ( 'page_subtitle' == $key ) {
				Alpha_Layout_Builder::get_instance()->setup_titles();
				$meta_value = get_post_meta( $post_id, 'page_subtitle' );
				if ( ! $meta_value ) {
					$meta_value = array( $alpha_layout['subtitle'] );
				}
			}
		} else {
			$meta_value = get_post_meta( $post_id, $key );
		}
		if ( ! $meta_value ) {
			return null;
		}

		return $meta_value;
	}

	/**
	 * Render field
	 *
	 * @param  [type] $result   [description]
	 * @param  array  $settings [description]
	 * @return [type]           [description]
	 * @since 1.0
	 */
	public function metabox_render( $result, $settings, $widget = 'field' ) {
		if ( 'meta-box' == $settings['dynamic_field_source'] ) {
			$widget = 'dynamic_metabox_' . $widget;
			$key    = isset( $settings[ $widget ] ) ? $settings[ $widget ] : false;

			$result = array();

			if ( isset( $settings['add_featured_image'] ) && 'yes' == $settings['add_featured_image'] ) {
				$result[] = get_post_thumbnail_id();
			}

			if ( ! $key ) {
				return $result;
			}

			$meta_ids = $this->metabox_get_meta( $key );
			$result   = array_merge( $result, $meta_ids ? $meta_ids : array() );

			return $result;
		}
		return $result;
	}

	/**
	 * Sanitimze js and css.
	 *
	 * @since 1.0
	 */
	public function rwmb_sanitize( $value, $field, $old_value = null, $object_id = null ) {
		if ( current_user_can( 'administrator' ) && isset( $field['id'] ) ) {
			if ( 'page_css' == $field['id'] ) {
				return wp_strip_all_tags( htmlspecialchars_decode( $value ) );
			}
			if ( 'page_js' == $field['id'] ) {
				return htmlspecialchars_decode( $value );
			}
		}
		return $value;
	}
}

Alpha_Admin_Meta_Boxes::get_instance();
