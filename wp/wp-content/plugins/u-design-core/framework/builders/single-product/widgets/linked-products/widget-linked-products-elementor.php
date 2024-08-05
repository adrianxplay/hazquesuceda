<?php
/**
 * Alpha Elementor Single Product Linked Products Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

class Alpha_Product_Linked_Products_Elementor_Widget extends Alpha_Posts_Grid_Elementor_Widget {

	public function get_name() {
		return ALPHA_NAME . '_widget_product_linked_products';
	}

	public function get_title() {
		return esc_html__( 'Linked Products', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-related';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget', 'alpha_cart_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'linked_products', 'cart', 'cross', 'upsell', 'related' );
	}

	protected function register_controls() {
		parent::register_controls();

		$this->remove_control( 'source' );
		$this->update_control(
			'orderby',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Order by', 'alpha-core' ),
				'options'     => array(
					''               => esc_html__( 'Default', 'alpha-core' ),
					'ID'             => esc_html__( 'ID', 'alpha-core' ),
					'title'          => esc_html__( 'Name', 'alpha-core' ),
					'date'           => esc_html__( 'Date', 'alpha-core' ),
					'modified'       => esc_html__( 'Modified', 'alpha-core' ),
					'price'          => esc_html__( 'Price', 'alpha-core' ),
					'rand'           => esc_html__( 'Random', 'alpha-core' ),
					'rating'         => esc_html__( 'Rating', 'alpha-core' ),
					'comment_count'  => esc_html__( 'Comment count', 'alpha-core' ),
					'popularity'     => esc_html__( 'Total Sales', 'alpha-core' ),
					'wishqty'        => esc_html__( 'Wish', 'alpha-core' ),
					'sale_date_to'   => esc_html__( 'Sale End Date', 'alpha-core' ),
					'sale_date_from' => esc_html__( 'Sale Start Date', 'alpha-core' ),
				),
				'description' => esc_html__( 'Price, Rating, Total Sales, Wish, Sale End Date and Sale Start Date values work for only product post type.', 'alpha-core' ),
				'condition'   => array(),
			)
		);
		global $post;
		if ( ( $post && ( is_cart() || 'cart' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) ) {
				$this->update_control(
					'post_type',
					array(
						'type'        => Controls_Manager::HIDDEN,
						'label'       => esc_html__( 'Product Type', 'alpha-core' ),
						'description' => esc_html__( 'Please select a product type of products to display related or upsell products', 'alpha-core' ),
						'default'     => 'crosssell',
						'options'     => array(
							'crosssell' => esc_html__( 'Cross-sells Products', 'alpha-core' ),
						),
						'condition'   => array(),
					)
				);
		} elseif ( alpha_is_product() || ( $post && 'product_layout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) {
			$this->update_control(
				'post_type',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => esc_html__( 'Product Type', 'alpha-core' ),
					'description' => esc_html__( 'Please select a product type of products to display related or upsell products', 'alpha-core' ),
					'default'     => 'related',
					'options'     => array(
						'related' => esc_html__( 'Related Products', 'alpha-core' ),
						'upsell'  => esc_html__( 'Upsells Products', 'alpha-core' ),
					),
					'condition'   => array(),
				)
			);
		}
		
		$this->add_control(
			'linked_description',
			array(
				'raw'             => esc_html__( 'If there are no linked products, the widget will show products at random.', 'alpha-core' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'alpha-notice notice-warning',
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'orderway',
				),
			)
		);

		$this->remove_control( 'post_tax' );
		$this->remove_control( 'post_filter_section' );
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
		global $post;
		if ( ( $post && ( is_cart() || 'cart' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) ) {
			// Cart builder - only use cross-sells
			$atts['shortcode_type'] = 'cart';
			require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/builders/single-product/widgets/linked-products/render-linked-products-elementor.php' );
		} elseif ( alpha_is_product() || ( $post && 'product_layout' == get_post_meta( $post->ID, ALPHA_NAME . '_template_type', true ) ) ) {
			/**
			 * Filters post products in single product builder
			 *
			 * @since 1.0
			 */
			apply_filters( 'alpha_single_product_builder_set_preview', false );
			require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/builders/single-product/widgets/linked-products/render-linked-products-elementor.php' );
			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
