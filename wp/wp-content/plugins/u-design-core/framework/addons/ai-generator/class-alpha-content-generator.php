<?php

/**
 * Alpha Content Generator - generate description, excerpt, meta infos and outline by using GPT-3
 * 
 * @author     D-THEMES
 * @category   WP Alpha Framework
 * @subpackage Theme
 * @since      1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Alpha_Content_Generator' ) ) :
	class Alpha_Content_Generator {

		public function __construct() {
			$post_types = array( 'attachment', 'nav_menu_item', 'revision', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'wp_template', 'wp_template_part', 'wp_global_styles', 'wp_navigation', 'product_variation', 'shop_order', 'shop_order_refund', 'shop_order_placehold', 'shop_coupon', ALPHA_NAME . '_template', 'cptm', 'ptu', 'yith-wcbm-badge' );
			if ( ( 'post-new.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ! in_array( $_REQUEST['post_type'], $post_types ) ) || ( 'post.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post'] ) && ! in_array( get_post_type( $_REQUEST['post'] ), $post_types ) ) ) {
				add_filter( 'alpha_admin_vars', array( $this, 'add_ai_vars' ) );
				add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_boxes' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ), 20 );
			}

			// Add options section
			add_filter(
				'alpha_customize_sections',
				function( $sections ) {
					$sections['ai_generator'] = array(
						'title'    => esc_html__( 'AI Generator', 'alpha' ),
						'panel'    => 'features',
						'priority' => 5,
					);
					return $sections;
				}
			);

			// Add theme options
			add_filter( 'alpha_customize_fields', array( $this, 'add_customize_fields' ) );

			// Change default option			
			add_filter( 'alpha_theme_option_default_values', array( $this, 'extend_theme_options_default_values' ) );
		}

		/**
		 * Add AI meta Fields
		 * Except page of plugin which adds custom post type - Post Type Unlimted, Custom Post Type Maker
		 * 
		 * @since 1.3.0
		 */
		public function add_meta_boxes( $meta_boxes ) {
			if ( isset( $_REQUEST['post_type'] ) ) {
				$post_type = $_REQUEST['post_type'];
			} else if ( isset( $_REQUEST['post'] ) ) {
				$post_type = get_post_type( $_REQUEST['post'] );
			}
			$meta_boxes[] = array(
				'id'         => 'alpha-api-engine',
				'title'      =>  sprintf( esc_html__( '%s AI Engine', 'alpha-core' ), ALPHA_DISPLAY_NAME ),
				'post_types' =>  array( $post_type ),
				'context'    => 'side',
				'priority'   => 'high',
				'fields'     => array(
					array(
						'id'    => 'ai_desc_none',
						'class' => 'ai-desc-none',
						'type'  => 'heading',
						'name'  => esc_html__( 'Please input AI key in Theme Option > Features > AI Generator.', 'alpha-core' ),
						'desc'  => esc_html__( 'You can generate the descriptions and meta infos easily, correctly and in details with AI engine. ', 'alpha-core' )
					),
					array(
						'id'          => 'prompt_topic',
						'class'       => 'prompt-topic',
						'type'        => 'textarea',
						'placeholder' => esc_html__( 'Please leave empty to generate with the title.', 'alpha-core' ),
						'name'        => esc_html__( 'Alternative Topic ( Instead of Title )', 'alpha-core' ),
						'desc'        => esc_html__( 'You can generate with this topic instead of the title.', 'alpha-core' )
					),
					array(
						'id'      => 'ai_content_type',
						'type'    => 'select',
						'class'   => 'ai-content-type',
						'name'    => esc_html__('Generate Type', 'alpha-core'),
						'options' => array(
							'description' => esc_html__( 'Description', 'alpha-core' ),
							'excerpt'     => esc_html__( 'Excerpt', 'alpha-core' ),
							'outline'     => esc_html__( 'Outline', 'alpha-core' ),
							'meta_title'  => esc_html__( 'Meta Title', 'alpha-core' ),
							'meta_desc'   => esc_html__( 'Meta Description', 'alpha-core' ),
							'meta_key'    => esc_html__( 'Meta Keywords', 'alpha-core' ),
						),
					),
					array(
						'id'      => 'ai_write_style',
						'type'    => 'select',
						'class'   => 'ai-write-style',
						'name'    => esc_html__( 'Writing Style', 'alpha-core' ),
						'options' => array(
							''              => esc_html__( 'Normal', 'alpha-core' ),
							'persuasive'    => esc_html__( 'Persuasive', 'alpha-core'),
							'infromative'   => esc_html__( 'Infromative', 'alpha-core'),
							'descriptive'   => esc_html__( 'Descriptive', 'alpha-core'),
							'creative'      => esc_html__( 'Creative', 'alpha-core'),
							'narrative'     => esc_html__( 'Narrative', 'alpha-core'),
							'argumentative' => esc_html__( 'Argumentative', 'alpha-core'),
							'analytical'    => esc_html__( 'Analytical', 'alpha-core'),
							'evaluative'    => esc_html__( 'Evaluative', 'alpha-core'),
						),
					),
					array(
						'id'          => 'user_word',
						'class'       => 'user-word',
						'type'        => 'textarea',
						'name'        => esc_html__( 'Additional Prompt', 'alpha-core'),
						'placeholder' => esc_html__( 'Ex: Please write at least 10 sentences in Italian.', 'alpha-core' ),
						'desc'        => esc_html__( 'You can improve the prompt for generating with this additional prompt.', 'alpha-core' ),
					),
					'generate_btn' => array(
						'id'    => 'generate_btn',
						'type'  => 'button',
						'std'   => esc_html__( 'Generate', 'alpha-core' ),
					),
				),
			);
			return $meta_boxes;
		}

		/**
		 * Enqueue js for Ai engine
		 * 
		 * @since 1.3.0
		 */
		public function enqueue_script() {
			if ( function_exists( 'alpha_get_option' ) && ! empty( alpha_get_option( 'ai_generate_key' ) ) ) {
				wp_enqueue_script( 'alpha-ai-engine', alpha_core_framework_uri( '/addons/ai-generator/ai-generator' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
			}
			$this->add_style();
		}

		/**
		 * Add vars to alpha_admin_vars
		 * 
		 * @since 1.3.0
		 */
		public function add_ai_vars( $vars ) {
			if ( function_exists( 'alpha_get_option' ) && ! empty( alpha_get_option( 'ai_generate_key' ) ) ) {
				$vars['ai_key'] = alpha_get_option( 'ai_generate_key' );
				$screen = get_current_screen();
				if ( 'post' == $screen->id ) {
					$vars['post_type'] = 'blog';
				} else {
					$vars['post_type'] = $screen->id;
				}
				$vars[ 'ai_refer_url' ] = '';
				$vars[ 'ai_logo' ] = '';
			}
			return $vars;
		}

		/**
		 * Add styles for metabox and dialog
		 * 
		 * @since 1.3.0
		 */
		public function add_style() {
			?>
				<style>
					#alpha-api-engine h4 { padding-top: 0 !important; margin: 0 0 10px 0 !important; font-size: 13px; text-transform: capitalize; }
					#alpha-api-engine .inside { margin-top: 12px !important; }
					#alpha-api-engine .postbox-header { background-color: #2271b1 !important; }
					#alpha-api-engine .postbox-header > *,
					#alpha-api-engine .postbox-header .button,
					#alpha-api-engine .postbox-header button,
					#alpha-api-engine .postbox-header span{color: #fff !important;}
			<?php
			if ( function_exists( 'alpha_get_option' ) && ! empty( alpha_get_option( 'ai_generate_key' ) ) ) {
				?>
					.alpha-dialog-wrapper:not(.complete) .alpha-dialog-footer,
					.alpha-dialog-wrapper:not(.complete) .alpha-dialog-close {
						display: none;
					}
					.alpha-dialog-content { position: relative; }
					.alpha-dialog-wrapper .output {
						width: 100%;
						height: 100%;
						display: block;
						max-height: 300px;
					}
					.alpha-dialog-wrapper .alpha-dialog {
						width: 500px;
					}
					.alpha-dialog-wrapper:not(.complete) .output {
						visibility: hidden;
    					opacity: 0;
					}
					.alpha-dialog-wrapper.complete .d-loading {
						display: none;
					}
					.alpha-dialog-wrapper button { font-size: 13px; }
					#alpha-api-engine select { box-sizing: border-box; }
					#alpha-api-engine #generate_btn { width: 100%; }
					#alpha-api-engine .ai-desc-none { display: none; }
					#user_word { height: 100px; }

					/* Seo Plugin */
					.ai-plugin-gen { margin: 0 0 5px 10px !important; height: 100% !important; }
					.ai-plugin-gen svg { margin-right: 3px; }
					#aioseo-post-settings-meta-description-row .ai-plugin-gen { margin: 0 0 0 auto !important; }
					.aioseo-post-settings-modal #aioseo-post-settings-meta-description-row .add-tags {
						position: static;
						margin-bottom: 10px;
					}
					/* Rank Math Seo */
					.rank-math-editor-general [for="rank-math-editor-description"] {
						display: inline-flex !important;
					}
					.rank-math-editor-general .is-primary { height: 24px !important; vertical-align: middle; }
				<?php
			} else {
				?>
					#alpha-api-engine #ai_desc_none { font-size: 16px; }
					#alpha-api-engine .rwmb-field:not(.ai-desc-none) { display: none; }
				<?php
			}
			?>
			</style>
			<?php
		}
		
		/**
		 * Add fields for OpenAPI
		 *
		 * @param {Array} $fields
		 *
		 * @param {Array} $fields
		 *
		 * @since 1.3.0
		 */
		public function add_customize_fields( $fields ) {
			
			// Feature AI field
			$fields['ai_generate_about_title'] = array(
				'section' => 'ai_generator',
				'type'    => 'custom',
				'label'   => '',
				'default' => '<h3 class="options-custom-title option-feature-title">' . esc_html__( 'About This Feature', 'alpha' ) . '</h3>',
			);
			$fields[ 'ai_generate_key_desc' ] = array(
                'section' => 'ai_generator',
                'type'    => 'custom',
                'label'   => sprintf( __( 'You can get your API Key in your %1$sOpenAI Account%2$s. We use the text-davinci-003 model. You must install %3$sMeta Box%4$s plugin.', 'alpha' ), '<a href="https://platform.openai.com/account/api-keys" target="_blank">', '</a>', '<b>', '</b>' ),
                'default' => '<p class="options-custom-description option-feature-description"><img class="description-image" src="' . ALPHA_ASSETS . '/images/admin/customizer/ai-option.jpg' . '" alt="' . esc_html__( 'AI Content Generator', 'alpha' ) . '"></p>',
			);
			$fields[ 'ai_generate_key_title' ] = array(
				'section' => 'ai_generator',
				'type'    => 'custom',
				'label'   => '',
				'default' => '<h3 class="options-custom-title option-feature-title">' . esc_html__( 'OpenAI Key', 'alpha' ) . '</h3>',
			);
			$fields[ 'ai_generate_key' ] = array(
				'section' => 'ai_generator',
				'type'    => 'text',
				'label'   => esc_html__( 'Input AI Key', 'alpha' ),
				'tooltip' => esc_html__( 'You can gerenate the description, excerpt, meta infos and outline for various post type.', 'alpha' ),
			);
			return $fields;
		}
		

		/**
		 * Extend default theme options
		 *
		 * @since 4.0.0
		 */
		public function extend_theme_options_default_values( $options ) {

			$options['navigator_items'] = array_merge(
				$options['navigator_items'],
				array(
					'ai_generator'   => array( esc_html__( 'Features / AI Generator', 'alpha' ), 'section' ),
				)
			);

			return $options;
		}
	}

	new Alpha_Content_Generator();
endif;