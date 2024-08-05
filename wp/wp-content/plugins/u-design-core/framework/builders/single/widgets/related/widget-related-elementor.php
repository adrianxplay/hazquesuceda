<?php
/**
 * Alpha Elementor Single Builder Related Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.2.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;

class Alpha_Single_Related_Elementor_Widget extends Alpha_Posts_Grid_Elementor_Widget {

	public function get_name() {
		return ALPHA_NAME . '_single_related';
	}

	public function get_title() {
		return esc_html__( 'Related Posts', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-posts-carousel';
	}

	public function get_categories() {
		return array( 'alpha_single_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'post', 'portfolio', 'related', 'linked', 'grid' );
	}

	protected function register_controls() {
		if ( apply_filters( 'alpha_single_builder_set_preview', false ) ) {
			global $post;
		}

		parent::register_controls();

		$this->remove_control( 'source' );
		$this->update_control(
			'orderby',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Order by', 'alpha-core' ),
				'options' => array(
					''              => esc_html__( 'Default', 'alpha-core' ),
					'ID'            => esc_html__( 'ID', 'alpha-core' ),
					'title'         => esc_html__( 'Name', 'alpha-core' ),
					'date'          => esc_html__( 'Date', 'alpha-core' ),
					'modified'      => esc_html__( 'Modified', 'alpha-core' ),
					'price'         => esc_html__( 'Price', 'alpha-core' ),
					'rand'          => esc_html__( 'Random', 'alpha-core' ),
					'rating'        => esc_html__( 'Rating', 'alpha-core' ),
					'comment_count' => esc_html__( 'Comment count', 'alpha-core' ),
				),
			)
		);

		$this->update_control(
			'post_tax',
			array(
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'label'       => esc_html__( 'Taxonomy', 'alpha-core' ),
				'description' => esc_html__( 'Please select a post taxonomy to pull posts from.', 'alpha-core' ),
				'options'     => '%post_type__' . $post->post_type . '%_alltax',
				'label_block' => true,
				'condition'   => array(),
			)
		);
		$this->remove_control( 'post_filter_section' );
		$this->remove_control( 'pagination_style' );
	}

	protected function render() {

		$atts = $this->get_settings_for_display();
		if ( is_array( $atts['count'] ) ) {
			if ( ! empty( $atts['count']['size'] ) ) {
				$atts['count'] = $atts['count']['size'];
			} else {
				$atts['count'] = '4';
			}
		}

		if ( is_array( $atts['col_cnt'] ) ) {
			if ( isset( $atts['col_cnt']['size'] ) ) {
				$atts['col_cnt'] = $atts['col_cnt']['size'];
			} else {
				$atts['col_cnt'] = '';
			}
		}
		if ( apply_filters( 'alpha_single_builder_set_preview', false ) ) {
			global $post;
			if ( $post ) {
				$atts['post_type']  = $post->post_type;
				$atts['is_related'] = $post->ID;
				require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/posts-grid/render-posts-grid.php' );
			}
			do_action( 'alpha_single_builder_unset_preview' );
		}
	}
}
