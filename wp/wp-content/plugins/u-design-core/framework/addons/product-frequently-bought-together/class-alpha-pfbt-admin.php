<?php
/**
 * Alpha Product Frequently Bought Together Admin Class
 *
 * @author     D-THEMES
 * @package    Alpha Framework
 * @subpackage Core
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Frequently_Bought_Together_Admin' ) ) {

	/**
	 * Alpha Product Frequently Bought Together Admin Class
	 *
	 * @since 1.0
	 */
	class Alpha_Product_Frequently_Bought_Together_Admin extends Alpha_Base {

		/**
		 * Main Class construct
		 *
		 * @since 1.0
		 */
		public function __construct() {
			if ( is_admin() && ( ( isset( $_REQUEST['post'] ) && 'product' == get_post_type( $_REQUEST['post'] ) ) || ( 'post-new.php' == $GLOBALS['pagenow'] && ! empty( $_REQUEST['post_type'] ) && 'product' == $_REQUEST['post_type'] ) ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
			}

			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ), 101 );
			add_action( 'wp_ajax_alpha_woocommerce_json_search_fbt', array( $this, 'ajax_json_search_products' ) );
			add_action( 'wp_ajax_nopriv_alpha_woocommerce_json_search_fbt', array( $this, 'ajax_json_search_products' ) );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_data_panel' ), 99 );
			add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_meta' ), 1, 2 );
		}

		/**
		 * Enqueue admin script
		 *
		 * @since 1.0
		 */
		public function admin_enqueue_script() {
			wp_enqueue_style( 'alpha-product-fbt-admin', alpha_core_framework_uri( '/addons/product-frequently-bought-together/fbt-admin.min.css' ), array(), ALPHA_CORE_VERSION );
			wp_enqueue_script( 'alpha-product-fbt-admin', alpha_core_framework_uri( '/addons/product-frequently-bought-together/fbt-admin' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		}

		/**
		 * insert the Frequently Bought Together to tab array.
		 *
		 * @since 1.0
		 */
		public function add_product_data_tab( $tabs ) {
			$tabs['alpha_fbt_tab'] = array(
				'label'    => esc_html__( 'Frequently Bought Together', 'alpha-core' ),
				'target'   => 'alpha_fbt_options',
				'priority' => 80,
			);
			return $tabs;
		}

		/**
		 * Search products
		 *
		 * @since 1.0
		 */
		public function ajax_json_search_products() {
			add_filter( 'woocommerce_json_search_found_products', array( $this, 'remove_variable_products' ) );
			WC_AJAX::json_search_products_and_variations();
		}

		/**
		 * Remove variable products.
		 *
		 * @param array $products The products
		 * @since 1.0
		 */
		public function remove_variable_products( $products ) {
			$new_products = array();
			foreach ( $products as $i => $product ) {
				$product_object = wc_get_product( $i );
				if ( ! $product_object->is_type( 'variable' ) ) {
					$new_products[ $i ] = $product;
				}
			}
			return $new_products;
		}

		/**
		 * Add options to product data properties
		 *
		 * @since 1.0
		 */
		public function get_panel_options() {
			$panel_options = array(
				'fbt_products'                => array(
					'label' => esc_html__( 'Select Products', 'alpha-core' ),
					'tip'   => __( 'Select products for "Frequently Bought Together" group', 'alpha-core' ),
					'type'  => 'select-products',
					'name'  => 'alpha_fbt_ids',
				),
				'fbt_discount_enable'         => array(
					'label' => esc_html__( 'Apply discount', 'alpha-core' ),
					'type'  => 'checkbox',
					'name'  => 'alpha_fbt_discount_enabled',
				),
				'fbt_discount_type'           => array(
					'label'   => esc_html__( 'Set discount type', 'alpha-core' ),
					'type'    => 'selectbox',
					'options' => array(
						'fixed'   => get_woocommerce_currency_symbol(),
						'percent' => '%',
					),
					'name'    => 'alpha_fbt_discount_type',
					'data'    => array(
						'events' => 'alpha_fbt_discount_enabled',
						'value'  => 'yes',
					),
				),
				'fbt_discount_fixed'          => array(
					'label' => esc_html__( 'Discount amount', 'alpha-core' ),
					'desc'  => get_woocommerce_currency_symbol(),
					'name'  => 'alpha_fbt_discount_fixed',
					'type'  => 'text',
					'class' => 'wc_input_price fbt_input',
					'data'  => array(
						'events' => 'alpha_fbt_discount_enabled,alpha_fbt_discount_type',
						'value'  => 'yes,fixed',
					),
				),
				'fbt_discount_percentage'     => array(
					'label' => esc_html__( 'Discount amount', 'alpha-core' ),
					'desc'  => '%',
					'name'  => 'alpha_fbt_discount_percentage',
					'type'  => 'number',
					'class' => 'wc-product-number fbt_input',
					'attr'  => array(
						'min' => 0,
						'max' => 100,
					),
					'data'  => array(
						'events' => 'alpha_fbt_discount_enabled,alpha_fbt_discount_type',
						'value'  => 'yes,percent',
					),
				),
				'fbt_discount_condition'      => array(
					'label' => esc_html__( 'Discount condition', 'alpha-core' ),
					'type'  => 'checkbox',
					'name'  => 'alpha_fbt_discount_condition',
					'data'  => array(
						'events' => 'alpha_fbt_discount_enabled',
						'value'  => 'yes',
					),
				),
				'fbt_discount_spend'          => array(
					'label' => esc_html__( 'User spend at least', 'alpha-core' ),
					'desc'  => get_woocommerce_currency_symbol(),
					'name'  => 'alpha_fbt_discount_spend',
					'type'  => 'text',
					'class' => 'wc_input_price fbt_input',
					'data'  => array(
						'events' => 'alpha_fbt_discount_enabled,alpha_fbt_discount_condition',
						'value'  => 'yes,yes',
					),
				),
				'fbt_discount_products_count' => array(
					'label' => esc_html__( 'User choose at least', 'alpha-core' ),
					'desc'  => esc_html__( 'products', 'alpha-core' ),
					'name'  => 'alpha_fbt_discount_products_count',
					'type'  => 'number',
					'class' => 'wc-product-number fbt_input',
					'attr'  => array(
						'min' => 2,
						'max' => 100,
					),
					'data'  => array(
						'events' => 'alpha_fbt_discount_enabled,alpha_fbt_discount_condition',
						'value'  => 'yes,yes',
					),
				),
			);

			return $panel_options;
		}


		/**
		 * create Frequently Bought Together panel
		 *
		 * @since 1.0
		 */
		public function add_product_data_panel() {
			global $thepostid, $product_object;
			$fbt_options = $this->get_panel_options();
			$fbt_metas   = $this->alpha_fbt_get_meta( $product_object );
			?>
				<div class="col-12">
					<div id="alpha_fbt_options" class="panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
						<div class="options-group">
							<?php
							foreach ( $fbt_options as $option_key => $option_value ) :

								if ( ! is_array( $option_value ) ) {
									continue;
								}

								if ( ! is_array( $fbt_metas ) ) {
									$fbt_metas = explode( ',', $fbt_metas );
								}

								$desc        = ! empty( $option_value['desc'] ) ? $option_value['desc'] : '';
								$option_name = ! empty( $option_value['name'] ) ? $option_value['name'] : '';
								$class       = ! empty( $option_value['class'] ) ? $option_value['class'] : '';
								$tip         = ! empty( $option_value['tip'] ) ? $option_value['tip'] : '';

								$value = $fbt_metas[ $option_key ];

								$attribute = '';
								if ( isset( $option_value['attr'] ) && is_array( $option_value['attr'] ) ) {
									foreach ( $option_value['attr'] as $attr_key => $attr_value ) {
										$attribute .= $attr_key . '=' . $attr_value . ' ';
									}
								}

								$data = '';
								if ( isset( $option_value['data'] ) && is_array( $option_value['data'] ) ) {
									foreach ( $option_value['data'] as $data_key => $data_value ) {
										$data .= 'data-' . $data_key . '=' . $data_value . ' ';
									}
								}

								if ( 'wc_input_price' == $class ) {
									$value = wc_format_localized_price( $value );
								}

								?>
								<p class="form-field" <?php echo esc_html( $data ); ?>>
									<label for="<?php echo esc_attr( $option_name ); ?>"><?php echo esc_html( $option_value['label'] ); ?></label>

									<?php
									switch ( $option_value['type'] ) {
										case 'select-products':
											?>
											<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="alpha_fbt_ids" name="alpha_fbt_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'alpha-core' ); ?>" data-action="alpha_woocommerce_json_search_fbt" data-exclude="<?php echo intval( $thepostid ); ?>">
												<?php
												$product_ids = $value;
												if ( is_array( $product_ids ) ) {
													foreach ( $product_ids as $product_id ) {
														$product = wc_get_product( $product_id );
														if ( is_object( $product ) && ! $product->is_type( 'variable' ) ) {
															echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . htmlspecialchars( alpha_strip_script_tags( $product->get_formatted_name() ) ) . '</option>';
														}
													}
												}
												?>
											</select>
											<?php
											break;
										case 'checkbox':
											?>
											<span class='alpha-fbt-checkbox-container'>
												<span class="alpha-fbt-checkbox">
													<input type="checkbox" id='<?php echo esc_attr( $option_name ); ?>' 
													name="<?php echo esc_attr( $option_name ); ?>" value="<?php echo esc_attr( $value ); ?>" 
													<?php checked( 'yes', $value ); ?> class="fbt_checkbox">
													<span class="alpha_fbt_desc"></span>
												</span>
											</span>
											<?php
											break;
										case 'selectbox':
											?>
											<select id = "<?php echo esc_attr( $option_name ); ?>"
												name = "<?php echo esc_attr( $option_name ); ?>" >
											<?php
											if ( is_array( $option_value['options'] ) ) {
												foreach ( $option_value['options'] as $sub_key => $sub_value ) {
													$select = 'no';
													if ( $sub_key == $value ) {
														$select = 'yes';
													}
													?>
														<option value="<?php echo esc_attr( $sub_key ); ?>" <?php selected( 'yes', $select ); ?>>
														<?php echo esc_html( $sub_value ); ?>
														</option>														
														<?php
												}
											}
											?>
											</select>
											<?php
											break;
										default:
											?>
											<input type='<?php echo esc_attr( $option_value['type'] ); ?>' class="<?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $option_name ); ?>"
											name="<?php echo esc_attr( $option_name ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_html( $attribute ); ?>>
											<span class="alpha_fbt_text"><?php echo esc_html( $option_value['desc'] ); ?></span>
											<?php
											break;
									}
									if ( $tip ) {
										echo wc_help_tip( $tip );
									}
									?>
								</p>	
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php
		}

		/**
		 * get the frequently bought together options
		 *
		 * @since 1.0
		 *
		 * @param mixed $object Post object or post id
		 * @param string $key key name stored in database
		 * @return array|null
		 */
		public function alpha_fbt_get_meta( $object, $key = '' ) {
			$is_store = false;

			( $object instanceof WC_Product ) || wc_get_product( intval( $object ) );

			if ( $object ) {
				$object_id = $object->get_id();
			}

			$data_key = $key ? $key : 'alpha_fbt_metas';
			$metas    = get_post_meta( $object_id, $data_key, true );

			if ( empty( $metas ) ) {
				$metas    = array();
				$is_store = true;
			}

			$metas = array_filter( $metas );

			$metas = array_merge(
				array(
					'fbt_products'                => array(),
					'fbt_discount_enable'         => 'no',
					'fbt_discount_type'           => get_woocommerce_currency_symbol(),
					'fbt_discount_fixed'          => '',
					'fbt_discount_percentage'     => '',
					'fbt_discount_condition'      => 'no',
					'fbt_discount_spend'          => '20',
					'fbt_discount_products_count' => 2,
				),
				$metas
			);

			$is_store && add_post_meta( $object_id, $data_key, json_encode( $metas ) );

			if ( ! empty( $metas ) ) {
				update_post_meta( $object_id, $data_key, $metas );
			}

			return $metas;
		}

		/**
		 * save the product meta in database.
		 *
		 * @since 1.0
		 * @param int $post_id
		 */
		public function save_product_meta( $post_id ) {
			$product  = wc_get_product( $post_id );
			$new_meta = array();
			foreach ( $this->get_panel_options() as $key => $settings ) {
				if ( ! is_array( $settings ) ) {
					continue;
				}

				$value = '';
				$name  = $settings['name'];
				switch ( $settings['type'] ) {
					case 'select-products':
						$value = array();
						if ( isset( $_POST[ $name ] ) ) {
							$value = is_array( $_POST[ $name ] ) ? $_POST[ $name ] : explod( ',', $_POST[ $name ] );
							$value = array_filter( array_map( 'intval', $value ) );
						}
						break;
					case 'checkbox':
						$value = isset( $_POST[ $name ] ) ? $_POST[ $name ] : 'no';
						break;
					case 'selectbox':
						$value = isset( $_POST[ $name ] ) ? $_POST[ $name ] : get_woocommerce_currency_symbol();
						break;
					default:
						if ( isset( $_POST[ $name ] ) && $_POST[ $name ] ) {
							$value = intval( $_POST[ $name ] );
						}
						break;
				}
				$new_meta[ $key ] = $value;
			}

			if ( ! empty( $new_meta ) ) {
				update_post_meta( $post_id, 'alpha_fbt_metas', $new_meta );
			}
		}
	}
}

Alpha_Product_Frequently_Bought_Together_Admin::get_instance();
