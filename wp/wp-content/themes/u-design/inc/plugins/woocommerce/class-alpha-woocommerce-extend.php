<?php
/**
 * Extend WooCommerce
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

class Alpha_WooCommerce_Extend extends Alpha_Base {

	/**
	 * Constructor
	 *
	 * @since 4.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 30 );

		// Customize
		add_filter( 'alpha_customize_fields', array( $this, 'set_woocommerce_customize_fields' ) );

		// Show weight unit attribute for all product types
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'alpha_product_unit_attribute' ), 10 );

		// Cart Text
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'set_single_add_to_cart_text' ) );

		// Add "Card" product category type.
		add_filter( 'alpha_get_category_classes', array( $this, 'extend_category_class' ), 10, 2 );
		add_filter( 'alpha_pc_types', array( $this, 'extend_category_types' ), 10, 2 );
		add_filter( 'alpha_wc_category_show_infos', array( $this, 'extend_category_show_info' ) );
		add_action( 'elementor/element/' . ALPHA_NAME . '_widget_categories/section_category_type/after_section_end', array( $this, 'extend_elementor_category_type' ) );

		add_action( 'alpha_before_shop_loop_start', array( $this, 'set_category_show_icon' ) );

		require_once ALPHA_INC . '/plugins/woocommerce/woo-functions-extend.php';
		require_once ALPHA_INC . '/plugins/woocommerce/product-loop-extend.php';
		require_once ALPHA_INC . '/plugins/woocommerce/product-single-extend.php';
		require_once ALPHA_INC . '/plugins/woocommerce/product-archive-extend.php';
		require_once ALPHA_INC . '/plugins/woocommerce/product-category-extend.php';

		add_theme_support( 'wc-product-gallery-zoom' );

		// Yith WooCommece Ajax Search Premium plugin compatibility in product edit save
		if ( class_exists( 'YITH_WCAS_Data_Index_Updater' ) ) {
			remove_action( 'woocommerce_variation_set_stock_status', array( YITH_WCAS_Data_Index_Updater::get_instance(), 'check_out_of_stock' ) );
			remove_action( 'woocommerce_product_set_stock_status', array( YITH_WCAS_Data_Index_Updater::get_instance(), 'check_out_of_stock' ) );
		}
	}

	/**
	 * Custom style for WPForms
	 *
	 * @since 1.0
	 */
	public function enqueue_style() {
		if ( defined( 'ALPHA_CORE_VERSION' ) ) {
			wp_enqueue_style( 'alpha-alert', ALPHA_CORE_INC_URI . '/widgets/alert/alert' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );

			if ( alpha_is_product() ) {
				wp_enqueue_style( 'alpha-banner', alpha_core_framework_uri( '/widgets/banner/banner' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}
		}
	}

	/**
	 * Display attributes for all product types
	 *
	 * @since 4.0
	 */
	public function alpha_product_unit_attribute( $attributes = '' ) {
		global $product;

		if ( 'variable' == $product->get_type() || ! $product->is_purchasable() ) {
			return;
		}

		// Get attributes for loop product
		if ( empty( $attributes ) ) {
			$attributes = array();
			foreach ( $product->get_attributes() as $attribute ) {
				$attributes = array_merge(
					$attributes,
					array(
						wc_attribute_label( isset( $attribute['name'] ) ? $attribute['name'] : '', $product ) => $this->get_attribute_options( $product->get_id(), $attribute ),
					)
				);
			}
		}

		// Print attributes
		foreach ( $attributes as $attribute_name => $options ) {

			if ( 'Unit' != $attribute_name ) {
				continue;
			}

			$option = $options;
			$terms  = wc_get_product_terms(
				$product->get_id(),
				$attribute_name,
				array(
					'fields' => 'all',
				)
			);

			echo '<div class="product-attr ' . esc_attr( $terms ? $attribute_name : 'pa_custom_' . strtolower( $attribute_name ) ) . '">';

			if ( ! empty( $option ) ) {

				$term = get_term_by( is_numeric( $option[0] ) ? 'id' : 'slug', $option[0], $attribute_name );
				if ( $term ) {
					$attr_label = sanitize_text_field( get_term_meta( $term->term_id, 'attr_label', true ) );
				} else {
					$attr_label = $option[0];
				}

				printf( '<span>(%s)</span>', $attr_label ? $attr_label : $term->name );

			}
			echo '</div>';
		}
	}

	/**
	 * Get attribute options.
	 *
	 * @param int $product_id
	 * @param array $attribute
	 * @return array
	 */
	protected function get_attribute_options( $product_id, $attribute ) {
		if ( isset( $attribute['is_taxonomy'] ) && $attribute['is_taxonomy'] ) {
			return wc_get_product_terms( $product_id, $attribute['name'], array( 'fields' => 'names' ) );
		} elseif ( isset( $attribute['value'] ) ) {
			return array_map( 'trim', explode( '|', $attribute['value'] ) );
		}

		return array();
	}

	/**
	 * Return single add to cart text
	 *
	 * @param string
	 * @since 4.0
	 */
	public function set_single_add_to_cart_text() {
		return esc_html__( 'Add to Cart', 'alpha' );
	}

	/**
	 * Extend category types.
	 *
	 * @param array  $types
	 * @param string $where
	 * @since 4.0
	 */
	public function extend_category_types( $types, $where ) {
		if ( 'wpb' == $where ) {
			$ret = array(
				'default'   => array(
					'image' => ALPHA_CORE_URI . '/assets/images/categories/category-1.jpg',
					'title' => esc_html__( 'Default', 'alpha' ),
				),
				'banner'    => array(
					'image' => ALPHA_CORE_URI . '/assets/images/categories/category-2.jpg',
					'title' => esc_html__( 'Banner', 'alpha' ),
				),
				'ellipse-2' => array(
					'image' => ALPHA_CORE_URI . '/assets/images/categories/category-3.jpg',
					'title' => esc_html__( 'Ellipse', 'alpha' ),
				),
				'card'      => array(
					'image' => ALPHA_CORE_URI . '/assets/images/categories/category-4.jpg',
					'title' => esc_html__( 'Card', 'alpha' ),
				),
				'classic'   => array(
					'image' => ALPHA_CORE_URI . '/assets/images/categories/category-5.jpg',
					'title' => esc_html__( 'Classic', 'alpha' ),
				),
			);
		} elseif ( 'elementor' == $where ) {
			$ret = array(
				''          => 'assets/images/categories/category-1.jpg',
				'banner'    => 'assets/images/categories/category-2.jpg',
				'ellipse-2' => 'assets/images/categories/category-3.jpg',
				'card'      => 'assets/images/categories/category-4.jpg',
			);
		} elseif ( 'hooks' == $where ) {
			$ret = array(
				'default'   => true,
				'banner'    => true,
				'ellipse-2' => true,
				'card'      => true,
			);
		} else {
			$ret = array(
				''          => ALPHA_ASSETS . '/images/options/categories/category-1.jpg',
				'banner'    => ALPHA_ASSETS . '/images/options/categories/category-2.jpg',
				'ellipse-2' => ALPHA_ASSETS . '/images/options/categories/category-3.jpg',
				'card'      => ALPHA_ASSETS . '/images/options/categories/category-4.jpg',
			);
		}
		return $ret;
	}

	/**
	 * Set category show icon
	 *
	 * @param array $types
	 * @since 4.0
	 */
	public function set_category_show_icon( $types ) {
		// Set category type
		if ( ! alpha_wc_get_loop_prop( 'widget' ) || 'yes' == alpha_wc_get_loop_prop( 'follow_theme_option' ) ) {
			wc_set_loop_prop( 'show_icon', in_array( alpha_get_option( 'category_type' ), array( 'icon', 'group', 'group-2', 'card' ) ) && alpha_get_option( 'category_show_icon' ) );
		}
	}

	/**
	 * Extend category show info
	 *
	 * @param array  $cat_options
	 * @since 4.0
	 */
	public function extend_category_show_info( $cat_options ) {
		$cat_options['card'] = array(
			'link'  => '',
			'count' => '',
		);
		return $cat_options;
	}

	/**
	 * Extend category classes
	 *
	 * @param array $category_class    Array of category classes
	 * @param string $category_type    Type of category
	 * @return array $category_class
	 * @since 4.0
	 */
	public function extend_category_class( $category_class, $category_type ) {
		if ( 'card' == $category_type ) {
			$category_class = 'cat-type-card';
		}
		return $category_class;
	}

	/**
	 * Extend card elementor category type.
	 *
	 * @since 4.0
	 */
	public function extend_elementor_category_type( $self ) {

		$self->update_control(
			'show_icon',
			array(
				'label'     => esc_html__( 'Show Icon', 'alpha' ),
				'type'      => Elementor\Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'category_type'       => array( 'group', 'group-2', 'label', 'card' ),
					'follow_theme_option' => '',
				),
			)
		);
	}

	/**
	 * Set woocommerce fields
	 *
	 * @param array $fields
	 * @since 4.0
	 */
	public function set_woocommerce_customize_fields( $fields ) {
		$fields['category_show_icon'] = array(
			'section'         => 'category_type',
			'type'            => 'toggle',
			'label'           => esc_html__( 'Show Icon', 'alpha' ),
			'transport'       => 'refresh',
			'active_callback' => array(
				array(
					'setting'  => 'category_type',
					'operator' => 'in',
					'value'    => array( 'icon', 'group', 'group-2', 'card' ),
				),
			),
		);
		unset( $fields['show_hover_shadow'] );
		unset( $fields['show_media_shadow'] );
		unset( $fields['show_in_box'] );

		return $fields;
	}
}

Alpha_WooCommerce_Extend::get_instance();
