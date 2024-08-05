<?php

/**
 * Alpha_Product_Custom_Tab_Admin class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Product_Custom_Tab_Admin' ) ) {
	class Alpha_Product_Custom_Tab_Admin {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ), 101 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_data_panel' ), 99 );

			add_action( 'wp_ajax_alpha_save_product_tabs', array( $this, 'save_product_tabs' ) );
			add_action( 'wp_ajax_nopriv_alpha_save_product_tabs', array( $this, 'save_product_tabs' ) );

			if ( ( isset( $_REQUEST['post'] ) && 'product' == get_post_type( $_REQUEST['post'] ) ) || ( 'post-new.php' == $GLOBALS['pagenow'] && ! empty( $_REQUEST['post_type'] ) && 'product' == $_REQUEST['post_type'] ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1001 );
			}

			/**
			 * Fires after setting up product custom tab admin configuration.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_custom_tab_admin', $this );
		}

		/**
		 * Add product data tab.
		 *
		 * @since 1.0
		 */
		public function add_product_data_tab( $tabs ) {
			$tabs['alpha_custom_tabs'] = array(
				'label'    => esc_html__( 'Custom Description Tab', 'alpha-core' ),
				'target'   => 'alpha_custom_tab_options',
				'priority' => 80,
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
			<div id="alpha_custom_tab_options" class="panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
				<div class="options_group" style="padding-bottom: 9px !important">
					<?php
					woocommerce_wp_text_input(
						array(
							'id'    => 'alpha_custom_tab_title_1st',
							'label' => esc_html__( 'Tab Title', 'alpha-core' ),
						)
					);
					?>
					<div class="form-field alpha_custom_tab_content_field">
						<label for="alpha_custom_tab_title_1st"><?php esc_html_e( 'Tab Content', 'alpha-core' ); ?></label>
						<?php
						$settings    = array(
							'textarea_name' => 'alpha_custom_tab_content_1st',
							'quicktags'     => array( 'buttons' => 'em,strong,link' ),
							'tinymce'       => array(
								'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
								'theme_advanced_buttons2' => '',
							),
						);
						$tab_content = get_post_meta( $thepostid, 'alpha_custom_tab_content_1st', true );
						/**
						 * Filters content of custom product tab by editor settings.
						 *
						 * @since 1.0
						 */
						wp_editor( wp_specialchars_decode( $tab_content, ENT_QUOTES ), 'alpha_custom_tab_content_1st', apply_filters( 'alpha_product_custom_tab_content_editor_settings', $settings ) );
						?>
					</div>
				</div>
				<div class="options_group" style="padding-bottom: 9px !important">
					<?php
					woocommerce_wp_text_input(
						array(
							'id'    => 'alpha_custom_tab_title_2nd',
							'label' => esc_html__( 'Tab Title', 'alpha-core' ),
						)
					);
					?>
					<div class="form-field alpha_custom_tab_content_field">
						<label for="alpha_custom_tab_title_2nd"><?php esc_html_e( 'Tab Content', 'alpha-core' ); ?></label>
						<?php
						$settings    = array(
							'textarea_name' => 'alpha_custom_tab_content_2nd',
							'quicktags'     => array( 'buttons' => 'em,strong,link' ),
							'tinymce'       => array(
								'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
								'theme_advanced_buttons2' => '',
							),
						);
						$tab_content = get_post_meta( $thepostid, 'alpha_custom_tab_content_2nd', true );
						/**
						 * Filters content of custom product tab by editor settings.
						 *
						 * @since 1.0
						 */
						wp_editor( wp_specialchars_decode( $tab_content, ENT_QUOTES ), 'alpha_custom_tab_content_2nd', apply_filters( 'alpha_product_custom_tab_content_editor_settings', $settings ) );
						?>
					</div>
				</div>
				<div class="toolbar clear">
					<button type="button" class="button-primary save_alpha_product_desc"><?php esc_html_e( 'Save tabs', 'alpha-core' ); ?></button>
				</div>
			</div>
			<?php
		}

		/**
		 * Enqueue script
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_media();
			wp_enqueue_style( 'alpha-product-custom-tab', alpha_core_framework_uri( '/addons/product-custom-tab/product-custom-tab-admin' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			wp_enqueue_script( 'alpha-product-custom-tab', alpha_core_framework_uri( '/addons/product-custom-tab/product-custom-tab-admin' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );
			wp_localize_script(
				'alpha-product-custom-tab',
				'alpha_product_custom_tab_vars',
				apply_filters(
					'alpha_product_custom_tab',
					array(
						'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
						'post_id'  => get_the_ID(),
						'nonce'    => wp_create_nonce( 'alpha-product-editor' ),
					)
				)
			);
		}

		/**
		 * Save product tabs.
		 *
		 * @since 1.0
		 */
		public function save_product_tabs() {

			if ( ! check_ajax_referer( 'alpha-product-editor', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			$post_id = $_POST['post_id'];

			$custom_tab = isset( $_POST['alpha_custom_tab_1st'] ) ? $_POST['alpha_custom_tab_1st'] : false;

			if ( ! $custom_tab ) {
				delete_post_meta( $post_id, 'alpha_custom_tab_title_1st' );
				delete_post_meta( $post_id, 'alpha_custom_tab_content_1st' );
			} else {
				update_post_meta( $post_id, 'alpha_custom_tab_title_1st', $custom_tab[0] );
				update_post_meta( $post_id, 'alpha_custom_tab_content_1st', $custom_tab[1] );
			}

			$custom_tab = isset( $_POST['alpha_custom_tab_2nd'] ) ? $_POST['alpha_custom_tab_2nd'] : false;
			if ( ! $custom_tab ) {
				delete_post_meta( $post_id, 'alpha_custom_tab_title_2nd' );
				delete_post_meta( $post_id, 'alpha_custom_tab_content_2nd' );
			} else {
				update_post_meta( $post_id, 'alpha_custom_tab_title_2nd', $custom_tab[0] );
				update_post_meta( $post_id, 'alpha_custom_tab_content_2nd', $custom_tab[1] );
			}

			wp_send_json_success();
			die();
		}
	}
}

new Alpha_Product_Custom_Tab_Admin;
