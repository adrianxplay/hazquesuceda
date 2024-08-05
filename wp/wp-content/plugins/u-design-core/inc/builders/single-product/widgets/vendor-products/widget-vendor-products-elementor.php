<?php
/**
 * Alpha Elementor Single Product Linked Products Widget
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;

class Alpha_Single_Product_Vendor_Products_Elementor_Widget extends Alpha_Products_Elementor_Widget {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_vendor_products';
	}

	public function get_title() {
		return esc_html__( 'Vendor Products', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-stock';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'vendor', 'product', 'woocommerce', 'shop', 'store', 'vendor-products' );
	}

	public function get_script_depends() {
		$depends = array();
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {
		parent::register_controls();

		$this->remove_control( 'ids' );
		$this->remove_control( 'categories' );

		$this->update_control(
			'status',
			array(
				'label'       => esc_html__( 'Product Status', 'alpha-core' ),
				'type'        => Controls_Manager::SELECT,
				'description' => esc_html__( 'Product Status is this.', 'alpha-core' ),
				'default'     => 'vendor',
				'options'     => array(
					'vendor' => esc_html__( 'Vendor Products', 'alpha-core' ),
				),
			)
		);
	}

	protected function render() {
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
			global $product, $post, $alpha_layout;
			$author_id = get_post_field( 'post_author', $product->get_id() );
			wc_set_loop_prop( 'name', 'vendor_products' );
			?>
			<section class="more-seller-product products">
				<?php
				$function_product_author = function( $query_args ) use ( $author_id ) {
					$query_args['author'] = $author_id;
					return $query_args;
				};
				add_filter( 'woocommerce_shortcode_products_query', $function_product_author );
				parent::render();
				remove_filter( 'woocommerce_shortcode_products_query', $function_product_author );
			?>
			</section>
			<?php
			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
