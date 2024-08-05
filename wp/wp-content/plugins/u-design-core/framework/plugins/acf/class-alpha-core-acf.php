<?php
/**
 * Alpha ACF plugin compatibility for dynamic tags.
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die;

class Alpha_Core_Acf extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'alpha_dynamic_tags', array( $this, 'acf_add_tags' ) );
		add_filter( 'alpha_dynamic_field_object', array( $this, 'acf_add_object' ) );
		add_filter( 'alpha_dynamic_extra_fields_content', array( $this, 'acf_render' ), 10, 3 );
		add_action( 'alpha_dynamic_extra_fields', array( $this, 'acf_add_control' ), 10, 3 );

		add_filter( 'alpha_gutenberg_editor_vars', array( $this, 'retrieve_acf_meta_fields' ), 10, 2 );
	}

	/**
	 * Returns Alpha support acf types
	 *
	 * @return array
	 */
	public function get_acf_types() {

		return array(
			'text'             => array( 'field', 'link' ),
			'textarea'         => array( 'field' ),
			'number'           => array( 'field' ),
			'range'            => array( 'field' ),
			'email'            => array( 'field', 'link' ),
			'url'              => array( 'field', 'link' ),
			'image'            => array( 'link', 'image' ),
			'select'           => array( 'field' ),
			'checkbox'         => array( 'field' ),
			'radio'            => array( 'field' ),
			'true_false'       => array( 'field' ),
			'link'             => array( 'field', 'link' ),
			'page_link'        => array( 'field', 'link' ),
			'post_object'      => array( 'field', 'link' ),
			'taxonomy'         => array( 'field', 'link' ),
			'date_picker'      => array( 'field' ),
			'date_time_picker' => array( 'field' ),
			'wysiwyg'          => array( 'field' ),
		);

	}

	public function acf_get_meta( $key ) {
		if ( ! $key ) {
			return null;
		}

		$post_id    = get_the_ID();
		$meta_value = get_post_meta( $post_id, $key, true );
		if ( ! $meta_value ) {
			return null;
		}

		return $meta_value;
	}

	/**
	 * Render ACF Field
	 *
	 * @since 1.0
	 */
	public function acf_render( $result, $settings, $widget = 'field' ) {
		if ( 'acf' == $settings['dynamic_field_source'] ) {
			$widget = 'dynamic_acf_' . $widget;
			$key    = isset( $settings[ $widget ] ) ? $settings[ $widget ] : false;

			if ( ! $key || ! preg_match( '/-/', $key ) ) {
				return null;
			}

			$keys = explode( '-', $key );
			$key  = $keys[1];

			return $this->acf_get_meta( $key );
		}

		return $result;
	}

	/**
	 * Add Dynamic Acf Tags
	 *
	 * @since 1.0
	 */
	public function acf_add_tags( $tags ) {
		array_push( $tags, 'Alpha_Core_Custom_Field_Acf_Tag', 'Alpha_Core_Custom_Image_Acf_Tag' );
		return $tags;
	}

	/**
	 * Add Acf object to Dynamic Field
	 *
	 * @since 1.0
	 */
	public function acf_add_object( $objects ) {
		$objects['acf'] = esc_html__( 'ACF', 'alpha-core' );
		return $objects;
	}

	/**
	 * Add control for ACF object
	 *
	 * @since 1.0
	 */
	public function acf_add_control( $object, $widget = 'field', $plugin = 'acf' ) {
		if ( 'acf' == $plugin ) {
			$control_key = 'dynamic_acf_' . $widget;
			$object->add_control(
				$control_key,
				array(
					'label'   => esc_html__( 'ACF Field', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'groups'  => $this->get_acf_fields( $widget ),
				)
			);
		}
	}

	/**
	 * Retrieve ACF Field groups
	 *
	 * @return array
	 * @since 1.0
	 */
	public function get_acf_groups( $widget, $object = null ) {
		$acf_groups = array();
		if ( function_exists( 'acf_get_field_groups' ) ) {
			global $post;
			if ( $object ) {
				if ( isset( $object->term_id ) && isset( $object->taxonomy ) ) {
					$acf_groups = acf_get_field_groups(
						array(
							'taxonomy' => $object->taxonomy,
						)
					);
				} else {
					$acf_groups = acf_get_field_groups(
						array(
							'post_id'   => $object->ID,
							'post_type' => $object->post_type,
						)
					);
				}
			} elseif ( $post && ALPHA_NAME . '_template' == get_post_type( $post ) && 'type' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) {
				$content_type = get_post_meta( $post->ID, 'content_type', true );
				if ( 'term' == $content_type ) {
					$term = get_post_meta( $post->ID, 'content_type_term', true );
					if ( $term ) {
						$acf_groups = acf_get_field_groups(
							array(
								'taxonomy' => $term,
							)
						);
					}
				}
			}

			if ( $post && empty( $acf_groups ) ) {
				$acf_groups = acf_get_field_groups(
					array(
						'post_id'   => $post->ID,
						'post_type' => $post->post_type,
					)
				);
			}
		} else {
			$acf_groups = apply_filters( 'acf/get_field_groups', array() );
		}

		$data      = array();
		$acf_types = $this->get_acf_types();

		foreach ( $acf_groups as $acf_group ) {

			if ( function_exists( 'acf_get_fields' ) ) {
				$fields = acf_get_fields( $acf_group['ID'] );
			} else {
				$fields = array();
			}

			if ( empty( $fields ) ) {
				continue;
			}

			$options = array();

			foreach ( $fields as $field ) {
				if ( ! isset( $acf_types[ $field['type'] ] ) || ! in_array( $widget, $acf_types[ $field['type'] ] ) ) {
					continue;
				}

				$key             = $field['ID'] . '-' . $field['name'];
				$options[ $key ] = array(
					'type'  => $field['type'],
					'label' => $field['label'],
				);
			}

			if ( empty( $options ) ) {
				continue;
			}

			$data[] = array(
				'label'   => $acf_group['title'],
				'options' => $options,
			);
		}

		return $data;

	}

	/**
	 * Retrieve ACF fields for each group
	 *
	 * @since 1.0
	 */
	public function get_acf_fields( $widget ) {

		$fields     = array();
		$group_data = $this->get_acf_groups( $widget );

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
	 * Retrieve ACF meta fields
	 *
	 * @return array
	 * @since 1.2.0
	 */
	public function retrieve_acf_meta_fields( $block_vars = array(), $object = null ) {

		$fields = array( 'field', 'image', 'link' );
		foreach ( $fields as $field_type ) {
			$meta_fields = array();
			$group_data  = $this->get_acf_groups( $field_type, $object );

			if ( empty( $group_data ) ) {
				continue;
			}

			foreach ( $group_data as $data ) {
				$field     = array();
				$data_temp = $data['options'];

				foreach ( $data_temp as $key => $value ) {
					$field[ $key ] = isset( $value['label'] ) ? $value['label'] : '';
				}

				$field = array_filter( $field );

				$meta_fields[] = array(
					'label'   => $data['label'],
					'options' => $field,
				);
			}

			if ( ! isset( $block_vars['acf'] ) ) {
				$block_vars['acf'] = array();
			}
			$block_vars['acf'][ $field_type ] = $meta_fields;
		}

		return $block_vars;
	}

}

Alpha_Core_Acf::get_instance();
