<?php

/**
 * Alpha Product Data Addons
 *
 * Custom Labels
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Data_Addons' ) ) {
	class Alpha_Product_Data_Addons {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ), 101 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_data_panel' ), 99 );

			// Save 'Alpha Extra Options'
			add_action( 'wp_ajax_alpha_save_product_extra_options', array( $this, 'save_extra_options' ) );
			add_action( 'wp_ajax_nopriv_alpha_save_product_extra_options', array( $this, 'save_extra_options' ) );

			if ( is_admin() && ( ( isset( $_REQUEST['post'] ) && 'product' == get_post_type( $_REQUEST['post'] ) ) || ( 'post-new.php' == $GLOBALS['pagenow'] && ! empty( $_REQUEST['post_type'] ) && 'product' == $_REQUEST['post_type'] ) ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 1001 );
			}

			add_action( 'alpha_before_shop_loop_start', array( $this, 'enqueue_scripts' ), 35 );
			add_action( 'alpha_before_product_summary', array( $this, 'enqueue_scripts' ), 35 );

			/**
			 * Fires after setting up data addons.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_after_data_addons', $this );
		}

		/**
		 * Add product data tab.
		 *
		 * @since 1.0
		 */
		public function add_product_data_tab( $tabs ) {
			$tabs['alpha_data_addon'] = array(
				'label'    => ALPHA_DISPLAY_NAME . esc_html__( ' Extra Options', 'alpha-core' ),
				'target'   => 'alpha_data_addons',
				'priority' => 90,
			);
			return $tabs;
		}

		/**
		 * Add product data panel.
		 *
		 * @since 1.0
		 */
		public function add_product_data_panel() {
			global $thepostid;

			?>
			<div id="alpha_data_addons" class="panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
				<div class="options_group">
					<div class="wc-metabox wc-metabox-template" style="display: none;">
						<h3>
							<select class="custom_label_type" class="alpha_label_type" name="alpha_label_type" hidden>
								<option value=""><?php esc_html_e( 'Text', 'alpha-core' ); ?></option>
								<option value="image"><?php esc_html_e( 'Image', 'alpha-core' ); ?></option>
							</select>
							<div class="text-controls">
								<input type="text" placeholder="Label" class="label_text"  name="label_text">
								<label><?php esc_html_e( 'Color:', 'alpha-core' ); ?></label>
								<input type="text" class="color-picker label_color" name="label_color" value="">
								<label><?php esc_html_e( 'Bg Color:', 'alpha-core' ); ?></label>
								<input type="text" class="color-picker label_bgcolor" name="label_bgcolor" value="">
							</div>
							<div class="image-controls" style="display: none;">
								<input type="text" class="label_image" name="label_image" value="">
								<input class="btn_upload_img button" type="button" value="Upload Image">
								<input type="text" class="label_image_id" name="label_image_id" value="" hidden>
							</div>
							<a href="#" class="delete"><?php esc_html_e( 'Remove', 'alpha-core' ); ?></a>
						</h3>
					</div>
					<div class="form-field alpha_custom_labels">
						<label><?php esc_html_e( 'Custom Labels', 'alpha-core' ); ?></label>
						<button type="button" class="button add_custom_label" id="alpha_add_custom_label"><?php esc_html_e( 'Add a Label', 'alpha-core' ); ?></button>
						<?php echo wc_help_tip( __( 'Add custom labels for this product. Custom labels will be shown just after theme supported labels.', 'alpha-core' ) ); ?>
						<div class="wc-metaboxes ui-sortable">
						<?php
						$alpha_custom_labels = get_post_meta( $thepostid, 'alpha_custom_labels', true );
						if ( is_array( $alpha_custom_labels ) && count( $alpha_custom_labels ) ) :
							foreach ( $alpha_custom_labels as $custom_label ) :
								?>
								<div class="wc-metabox wc-metabox-template">
									<h3>
										<select class="custom_label_type" class="alpha_label_type" name="alpha_label_type" hidden>
											<option value="" <?php selected( $custom_label['type'], '' ); ?>><?php esc_html_e( 'Text', 'alpha-core' ); ?></option>
											<option value="image" <?php selected( $custom_label['type'], 'image' ); ?>><?php esc_html_e( 'Image', 'alpha-core' ); ?></option>
										</select>
										<div class="text-controls" <?php echo ( ! $custom_label['type'] ? '' : 'style="display: none;"' ); ?>>
											<input type="text" placeholder="Label" class="label_text"  name="label_text" value="<?php echo ( isset( $custom_label['label'] ) ? esc_attr( $custom_label['label'] ) : '' ); ?>">
											<label><?php esc_html_e( 'Color:', 'alpha-core' ); ?></label>
											<input type="text" class="color-picker" name="label_color" value="<?php echo ( isset( $custom_label['color'] ) ? esc_attr( $custom_label['color'] ) : '' ); ?>">
											<label><?php esc_html_e( 'Bg Color:', 'alpha-core' ); ?></label>
											<input type="text" class="color-picker" name="label_bgcolor" value="<?php echo ( isset( $custom_label['bgColor'] ) ? esc_attr( $custom_label['bgColor'] ) : '' ); ?>">
										</div>
										<div class="image-controls" <?php echo ( ! $custom_label['type'] ? 'style="display: none;"' : '' ); ?>>
											<input type="text" class="label_image" name="label_image" value="<?php echo ( isset( $custom_label['img_url'] ) ? esc_attr( $custom_label['img_url'] ) : '' ); ?>">
											<input class="btn_upload_img button" type="button" value="Upload Image">
											<input type="text" class="label_image_id" name="label_image_id" value="<?php echo ( isset( $custom_label['img_id'] ) ? esc_attr( $custom_label['img_id'] ) : '' ); ?>" hidden>
										</div>
										<a href="#" class="delete"><?php esc_html_e( 'Remove', 'alpha-core' ); ?></a>
									</h3>
								</div>
								<?php
							endforeach;
						endif;
						?>
						</div>
					</div>
				</div>

				<div class="options_group">
					<?php
					woocommerce_wp_text_input(
						array(
							'id'          => 'alpha_extra_info',
							'label'       => esc_html__( 'Extra Info', 'alpha-core' ),
							'desc_tip'    => true,
							'description' => esc_html__( 'Add extra info for this product. Extra info will be shown in product loop after price.', 'alpha-core' ),
						)
					);
					?>
				</div>

				<div class="toolbar">
					<button type="button" class="button save_alpha_product_options button-primary"><?php esc_html_e( 'Save options', 'alpha-core' ); ?></button>
				</div>
			</div>
			<?php
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @since 1.0
		 */
		public function enqueue_admin_scripts() {
			wp_enqueue_style( 'alpha-product-data-addons', alpha_core_framework_uri( '/addons/product-data-addons/product-data-addons-admin' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			wp_enqueue_script( 'alpha-product-data-addons', alpha_core_framework_uri( '/addons/product-data-addons/product-data-addons-admin' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
			wp_enqueue_script( 'alpha-admin-walker', alpha_core_framework_uri( '/addons/walker/walker-admin' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
			wp_localize_script(
				'alpha-product-data-addons',
				'alpha_product_data_addon_vars',
				apply_filters(
					'alpha_product_data_addon',
					array(
						'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
						'post_id'  => get_the_ID(),
						'nonce'    => wp_create_nonce( 'alpha-product-editor' ),
					)
				)
			);
		}

		/**
		 * Save extra options.
		 *
		 * @since 1.0
		 */
		public function save_extra_options() {
			if ( ! check_ajax_referer( 'alpha-product-editor', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}
			$post_id = $_POST['post_id'];

			// Save custom labels
			$alpha_custom_labels = isset( $_POST['alpha_custom_labels'] ) ? $_POST['alpha_custom_labels'] : '';
			if ( count( $alpha_custom_labels ) ) {
				update_post_meta( $post_id, 'alpha_custom_labels', $alpha_custom_labels );
			} else {
				delete_post_meta( $post_id, 'alpha_custom_labels' );
			}

			// Save Extra info
			$alpha_extra_info = isset( $_POST['alpha_extra_info'] ) ? $_POST['alpha_extra_info'] : '';
			if ( ! empty( $alpha_extra_info ) ) {
				update_post_meta( $post_id, 'alpha_extra_info', $alpha_extra_info );
			} else {
				delete_post_meta( $post_id, 'alpha_extra_info' );
			}

			wp_send_json_success();
			die();
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @since 1.2.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'alpha-product-data-addon', alpha_core_framework_uri( '/addons/product-data-addons/product-data.min.css' ), null, ALPHA_CORE_VERSION, 'all' );
		}
	}
}

new Alpha_Product_Data_Addons;
