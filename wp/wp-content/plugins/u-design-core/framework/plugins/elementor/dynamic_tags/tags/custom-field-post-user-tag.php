<?php
/**
 * Alpha Dynamic Tags class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Alpha_Core_Custom_Field_Post_User_Tag extends Elementor\Core\DynamicTags\Tag {

	protected $post_id;

	protected $is_archive;

	public function get_name() {
		return 'alpha-custom-field-post-user';
	}

	public function get_title() {
		return esc_html__( 'Posts / Author', 'alpha-core' );
	}

	public function get_group() {
		return Alpha_Core_Dynamic_Tags::ALPHA_CORE_GROUP;
	}

	public function get_categories() {
		return array(
			Alpha_Core_Dynamic_Tags::TEXT_CATEGORY,
			Alpha_Core_Dynamic_Tags::NUMBER_CATEGORY,
			Alpha_Core_Dynamic_Tags::POST_META_CATEGORY,
			Alpha_Core_Dynamic_Tags::COLOR_CATEGORY,
		);
	}

	protected function register_controls() {
		$this->add_control(
			'dynamic_field_post_object',
			array(
				'label'   => esc_html__( 'Object Field', 'alpha-core' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'post_title',
				'groups'  => $this->get_object_fields(),
			)
		);

		$this->add_control(
			'dynamic_field_post_date_format',
			array(
				'label'     => esc_html__( 'Format', 'alpha-core' ),
				'type'      => Elementor\Controls_Manager::SELECT,
				'options'   => array(
					''      => esc_html__( 'Default', 'alpha-core' ),
					'M d Y' => gmdate( 'M d Y' ),
					'd M Y' => gmdate( 'd M Y' ),
				),
				'condition' => array(
					'dynamic_field_post_object' => 'post_date',
				),
			)
		);

	}

	public function get_object_fields() {
		$fields = array(
			array(
				'label'   => esc_html__( 'Post', 'alpha-core' ),
				'options' => array(
					'post_id'          => esc_html__( 'Post ID', 'alpha-core' ),
					'post_title'       => esc_html__( 'Title', 'alpha-core' ),
					'post_date'        => esc_html__( 'Date', 'alpha-core' ),
					'post_content'     => esc_html__( 'Content', 'alpha-core' ),
					'post_excerpt'     => esc_html__( 'Excerpt', 'alpha-core' ),
					'post_status'      => esc_html__( 'Post Status', 'alpha-core' ),
					'comment_count'    => esc_html__( 'Comments Count', 'alpha-core' ),
					'alpha_post_likes' => esc_html__( 'Like Posts Count', 'alpha-core' ),
				),
			),
			array(
				'label'   => esc_html__( 'Author', 'alpha-core' ),
				'options' => array(
					'ID'    => esc_html__( 'Author ID', 'alpha-core' ),
					'url'   => esc_html__( 'Author URL', 'alpha-core' ),
					'email' => esc_html__( 'Author E-mail', 'alpha-core' ),
					'login' => esc_html__( 'Author Login', 'alpha-core' ),
					'name'  => esc_html__( 'Author Name', 'alpha-core' ),
				),
			),
		);

		return $fields;
	}

	public function render() {
		do_action( 'alpha_core_dynamic_before_render' );

		$this->post_id = get_the_ID();
		$atts          = $this->get_settings();
		$ret           = '';

		$property = $atts['dynamic_field_post_object'];

		$ret = (string) $this->get_prop( $property );

		if ( 'post_content' === $property ) {

			if ( ! empty( $this->post_id ) && Elementor\Plugin::$instance->documents->get( $this->post_id )->is_built_with_elementor() ) {

				$editor       = Elementor\Plugin::$instance->editor;
				$is_edit_mode = $editor->is_edit_mode();

				$editor->set_edit_mode( false );

				global $post;
				$temp = $post;
				$post = '';

				$ret = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $this->post_id, $is_edit_mode );

				$post = $temp;

				$editor->set_edit_mode( $is_edit_mode );

			} else {
				$ret = apply_filters( 'the_content', $ret );
			}
		}

		if ( 'alpha_post_likes' == $property ) {
			$ret = get_post_meta( $this->post_id, $property, true );
		}

		if ( is_array( $ret ) ) {
			$temp_content = '';
			if ( count( $ret ) > 1 ) {
				foreach ( $ret as $value ) {
					$temp_content .= (string) $value;
				}
			} else {
				$temp_content .= (string) $ret[0];
			}
			$ret = $temp_content;
		}

		if ( ! is_wp_error( $ret ) ) {
			echo alpha_strip_script_tags( $ret );
		}

		do_action( 'alpha_core_dynamic_after_render' );
	}

	// helper functions
	public function get_post_object() {
		$post_object = false;

		global $post;
		if ( is_singular() ) {
			$post_object = $post;
		} elseif ( is_tax() || is_category() || is_tag() || is_author() || is_home() ) {
			$post_object = get_queried_object();
		} elseif ( wp_doing_ajax() ) {
			$post_object = get_post( $this->post_id );
		} elseif ( class_exists( 'Woocommerce' ) && is_shop() ) {
			$post_object = get_post( (int) get_option( 'woocommerce_shop_page_id' ) );
		} elseif ( is_archive() || is_post_type_archive() ) {
			$this->is_archive = true;
			$post_object      = get_queried_object();
		}

		return $post_object;
	}

	public function get_prop( $property = null, $object = null ) {

		$author_properties = array(
			'ID',
			'url',
			'email',
			'login',
			'name',
		);

		if ( $author_properties && in_array( $property, $author_properties ) ) {
			if ( 'name' == $property ) {
				$value = get_the_author();
			} else {
				$value = get_the_author_meta( $property );
			}
			return wp_kses_post( $value );
		} else {
			$this->is_archive = false;
			$object           = $this->get_post_object();
			$vars             = $object ? get_object_vars( $object ) : array();

			if ( 'post_id' === $property ) {
				$vars['post_id'] = isset( $vars['ID'] ) ? $vars['ID'] : false;
			} elseif ( 'post_title' == $property ) {
				if ( $this->is_archive ) {
					$vars['post_title'] = isset( $vars['label'] ) ? $vars['label'] : false;
				}
				global $alpha_layout;
				if ( class_exists( 'Alpha_Layout_Builder' ) ) {
					Alpha_Layout_Builder::get_instance()->setup_titles();
					if ( ! empty( $alpha_layout['is_page_header'] ) && $alpha_layout['title'] ) {
						$vars['post_title'] = $alpha_layout['title'];
					}
				}
			} elseif ( 'post_date' == $property ) {
				$atts = $this->get_settings();
				if ( $atts['dynamic_field_post_date_format'] ) {
					$vars[ $property ] = get_post_time( $atts['dynamic_field_post_date_format'], false, $object );
				}
			}
		}

		return isset( $vars[ $property ] ) ? $vars[ $property ] : false;
	}
}
