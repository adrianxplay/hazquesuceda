<?php
/**
 * Alpha Essential Grid Class
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0.0
 * @version    4.0.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

class Alpha_Core_Essential_Grid_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {

		add_filter( 'alpha_dynamic_tags', array( $this, 'esg_add_tags' ) );
		add_filter( 'alpha_dynamic_field_object', array( $this, 'esg_add_object' ) );
		add_filter( 'alpha_dynamic_extra_fields_content', array( $this, 'esg_render' ), 10, 3 );
		add_action( 'alpha_dynamic_extra_fields', array( $this, 'esg_add_control' ), 10, 3 );

		add_filter( 'essgrid_getVar', array( $this, 'custom_esg_getVar' ), 10, 3 );

		add_action( 'alpha_sidebar_content_start', array( $this, 'before_sidebar_content' ) );
		add_action( 'alpha_sidebar_content_end', array( $this, 'after_sidebar_content' ) );

	}

	/**
	 * Returns Udesign support esg types
	 *
	 * @return array
	 */
	public function get_esg_types() {

		return array(
			'text'         => array( 'field', 'link' ),
			'multi_select' => array( 'field' ),
			'select'       => array( 'field' ),
			'image'        => array( 'link', 'image' ),
		);

	}

	public function esg_get_meta( $key ) {
		$post_id    = get_the_ID();
		$meta_value = get_post_meta( $post_id, 'eg-' . $key, true );
		if ( ! $meta_value ) {
			return null;
		}

		return $meta_value;
	}

	/**
	 * Render ESG Field
	 *
	 * @since 4.0
	 */
	public function esg_render( $result, $settings, $widget = 'field' ) {
		if ( 'esg' == $settings['dynamic_field_source'] ) {
			$widget = 'dynamic_esg_' . $widget;
			$key    = isset( $settings[ $widget ] ) ? $settings[ $widget ] : false;

			if ( ! $key ) {
				return null;
			}

			return $this->esg_get_meta( $key );
		}

		return $result;
	}

	/**
	 * Add Dynamic ESG Tags
	 *
	 * @since 4.0
	 */
	public function esg_add_tags( $tags ) {
		array_push( $tags, 'Alpha_Core_Custom_Field_Esg_Tag', 'Alpha_Core_Custom_Image_Esg_Tag' );
		return $tags;
	}

	/**
	 * Add ESG object to Dynamic Field
	 *
	 * @since 4.0
	 */
	public function esg_add_object( $objects ) {
		$objects['esg'] = esc_html__( 'Essential Grid', 'alpha-core' );
		return $objects;
	}


	/**
	 * Add control for ESG object
	 *
	 * @since 4.0
	 */
	public function esg_add_control( $object, $widget = 'field', $plugin = 'esg' ) {
		if ( 'esg' == $plugin ) {
			$control_key = 'dynamic_esg_' . $widget;
			$object->add_control(
				$control_key,
				array(
					'label'   => esc_html__( 'ESG Field', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'groups'  => $this->get_esg_fields( $widget ),
				)
			);
		}
	}

	/**
	 * Retrieve ESG Field groups
	 *
	 * @return array
	 * @since 4.0
	 */
	public function get_esg_groups( $widget ) {

		$metas = new Essential_Grid_Meta();

		$esg_fields = $metas->get_all_meta( false );

		global $post;
		$type = $post->post_type;

		$data      = array();
		$esg_types = $this->get_esg_types();

		foreach ( $esg_fields as $esg_field ) {

			$options = array();

			if ( ! isset( $esg_field['type'] ) || ! in_array( $widget, $esg_types[ $esg_field['type'] ] ) ) {
				continue;
			}

			$key             = $esg_field['handle'];
			$options[ $key ] = array(
				'type'  => $esg_field['type'],
				'label' => $esg_field['name'],
			);

			if ( empty( $options ) ) {
				continue;
			}

			$data[] = array(
				'label'   => $esg_field['name'],
				'options' => $options,
			);
		}

		return $data;

	}

	/**
	 * Retrieve ESG fields for each group
	 *
	 * @since 4.0
	 */
	public function get_esg_fields( $widget ) {

		$fields     = array();
		$group_data = $this->get_esg_groups( $widget );

		if ( empty( $group_data ) ) {
			return $fields;
		}

		foreach ( $group_data as $data ) {
			$field     = array();
			$data_temp = $data['options'];

			foreach ( $data_temp as $key => $value ) {
				$field[ $key ] = isset( $value['label'] ) ? $value['label'] : '';
			}

			$field = array_filter( $field );

			$fields[] = array(
				'label'   => $data['label'],
				'options' => $field,
			);
		}

		return $fields;
	}

	/**
	 * Retrieve custom ESG args
	 *
	 * @since 4.0
	 */
	public function custom_esg_getVar( $val, $arr, $key ) {
		if ( 'post_category' == $key ) {
			if ( is_archive() ) {
				global $is_sidebar;
				if ( ! $is_sidebar ) {
					$term = get_queried_object();
					if ( ! empty( $term->taxonomy ) && ! empty( $term->term_id ) ) {
						return $term->taxonomy . '_' . $term->term_id;
					}
				}
			}
		}

		if ( 'lazy-loading' == $key ) {
			if ( alpha_get_option( 'lazyload' ) ) {
				return 'on';
			}
			return 'off';
		}
		return $val;
	}

	/**
	 * Before Sidebar content starts
	 *
	 * @since 4.0
	 */
	public function before_sidebar_content() {
		global $is_sidebar;
		$is_sidebar = true;
	}


	/**
	 * After Sidebar content ends
	 *
	 * @since 4.0
	 */
	public function after_sidebar_content() {
		unset( $GLOBALS['is_sidebar'] );
	}

}

Alpha_Core_Essential_Grid_Extend::get_instance();
