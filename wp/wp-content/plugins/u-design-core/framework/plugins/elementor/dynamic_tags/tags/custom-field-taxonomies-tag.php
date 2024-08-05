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

class Alpha_Core_Custom_Field_Taxonomies_Tag extends Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'alpha-custom-field-taxonomies';
	}

	public function get_title() {
		return esc_html__( 'Taxonomies', 'alpha-core' );
	}

	public function get_group() {
		return Alpha_Core_Dynamic_Tags::ALPHA_CORE_GROUP;
	}

	public function get_categories() {
		return array(
			Alpha_Core_Dynamic_Tags::TEXT_CATEGORY,
			Alpha_Core_Dynamic_Tags::NUMBER_CATEGORY,
			Alpha_Core_Dynamic_Tags::URL_CATEGORY,
			Alpha_Core_Dynamic_Tags::POST_META_CATEGORY,
			Alpha_Core_Dynamic_Tags::COLOR_CATEGORY,
		);
	}

	protected function register_controls() {
		$this->add_control(
			'dynamic_field_taxonomy',
			array(
				'label'   => esc_html__( 'Taxonomy Field', 'alpha-core' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => '',
				'groups'  => $this->get_taxonomy_fields(),
			)
		);
	}

	public function get_taxonomy_fields() {

		do_action( 'alpha_core_dynamic_before_render' );

		$taxonomy_array = get_taxonomies();
		$option_fields  = array();
		$result         = array();

		if ( $taxonomy_array && is_array( $taxonomy_array ) ) {
			$post_type = get_post_type();
			if ( count( $taxonomy_array ) > 1 ) {
				foreach ( $taxonomy_array as $value ) {
					$taxonomy_object = get_taxonomy( (string) $value );
					$taxonomy_type   = $taxonomy_object->object_type;

					if ( $post_type == $taxonomy_type[0] ) {
						$key                   = $taxonomy_object->name;
						$option_fields[ $key ] = $taxonomy_object->label;
					} else {
						continue;}
				}
			} else {
				$taxonomy_object = get_taxonomy( (string) $taxonomy_array[0] );
				$taxonomy_type   = $taxonomy_object->object_type;

				if ( $post_type == $taxonomy_type[0] ) {
					$key                   = $taxonomy_object->name;
					$option_fields[ $key ] = $taxonomy_object->label;
				}
			}
		}

		$result = array(
			array(
				'label'   => esc_html__( 'Taxonomies', 'alpha-core' ),
				'options' => $option_fields,
			),
		);

		do_action( 'alpha_core_dynamic_after_render' );

		return $result;
	}

	public function render() {

		if ( is_404() ) {
			return;
		}

		do_action( 'alpha_core_dynamic_before_render' );

		$post_id = get_the_ID();
		$atts    = $this->get_settings();
		$ret     = '';

		$tax = $atts['dynamic_field_taxonomy'];
		if ( $tax ) {
			$ret = get_the_term_list( $post_id, $tax, '', ', ', '' );
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
}
