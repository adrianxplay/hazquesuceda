<?php

// direct load is not allowed
defined( 'ABSPATH' ) || die;

class Alpha_Product_Status_Sidebar_Widget extends WC_Widget {

	public $widget_cssclass    = '';
	public $widget_description = '';
	public $widget_id          = '';
	public $widget_name        = '';
	public $settings           = '';

	public function __construct() {
		$this->widget_cssclass    = 'widget widget_product_status woocommerce widget_layered_nav woocommerce-widget-layered-nav';
		$this->widget_description = esc_html__( 'A list of product status.', 'alpha-core' );
		$this->widget_id          = 'alpha_woo_product_status';
		$this->widget_name        = ALPHA_DISPLAY_NAME . esc_html__( ' - Product Status', 'alpha-core' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Product Status', 'alpha-core' ),
				'label' => esc_html__( 'Title', 'alpha-core' ),
			),
		);

		parent::__construct();

		add_filter( 'woocommerce_product_query', array( $this, 'filter_sale_products' ) );
		add_filter( 'woocommerce_product_query_tax_query', array( $this, 'filter_featured_products' ) );
	}

	public function widget( $args, $instance ) {

		$this->widget_start( $args, $instance );

		$statuses       = array(
			'featured' => esc_html__( 'Featured', 'alpha-core' ),
			'sale'     => esc_html__( 'On Sale', 'alpha-core' ),
		);
		$base_link      = remove_query_arg( 'paged', remove_query_arg( 'product_status' ) );
		$current_status = empty( $_GET['product_status'] ) ? '' : wp_unslash( $_GET['product_status'] );
		$li_class       = 'woocommerce-widget-layered-nav-list__item wc-layered-nav-term';

		echo '<ul class="woocommerce-widget-layered-nav-list">';
		printf(
			'<li class="%s"><a rel="nofollow" href="%s">%s</a></li>',
			$li_class . ( ! $current_status || ! isset( $statuses[ $current_status ] ) ? ' chosen' : '' ),
			esc_url( $base_link ),
			esc_html__( 'All', 'alpha-core' )
		);
		foreach ( $statuses as $status => $status_string ) {
			printf(
				'<li class="%s"><a rel="nofollow" href="%s">%s</a></li>',
				$li_class . ( $current_status == $status ? ' chosen' : '' ),
				esc_url( $current_status == $status ? $base_link : add_query_arg( 'product_status', $status, $base_link ) ),
				$status_string
			);
		}
		echo '</ul>';

		$this->widget_end( $args );
	}

	public function filter_sale_products( $q ) {
		if ( isset( $_GET['product_status'] ) ) {
			if ( 'sale' == $_GET['product_status'] ) {
				$q->set( 'post__in', wc_get_product_ids_on_sale() );
			}
		}
	}
	public function filter_featured_products( $tax_query ) {
		if ( isset( $_GET['product_status'] ) ) {
			if ( 'featured' == $_GET['product_status'] ) {
				if ( ! is_array( $tax_query ) ) {
					$tax_query = array();
				}
				$tax_query[] = array(
					'taxonomy'         => 'product_visibility',
					'terms'            => 'featured',
					'field'            => 'name',
					'operator'         => 'IN',
					'include_children' => false,
				);
			}
		}
		return  $tax_query;
	}
}
