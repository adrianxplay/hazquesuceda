<?php
/**
 * Alpha Studio Extend
 *
 * @author     Andon
 * @package    UDesign Core
 * @subpackage Core
 * @since      4.7
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Content_Generator_Extend' ) ) :
	class Alpha_Content_Generator_Extend {

		public function __construct() {
			$post_types = array( 'attachment', 'nav_menu_item', 'revision', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'wp_template', 'wp_template_part', 'wp_global_styles', 'wp_navigation', 'product_variation', 'shop_order', 'shop_order_refund', 'shop_order_placehold', 'shop_coupon', ALPHA_NAME . '_template', 'cptm', 'ptu' );

			if ( ( 'post-new.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post_type'] ) && ! in_array( $_REQUEST['post_type'], $post_types ) ) || ( 'post.php' == $GLOBALS['pagenow'] && isset( $_REQUEST['post'] ) && ! in_array( get_post_type( $_REQUEST['post'] ), $post_types ) ) ) {
				add_filter( 'alpha_admin_vars', array( $this, 'add_ai_vars' ) , 40 );
				add_filter( 'rwmb_meta_boxes', array( $this, 'add_meta_boxes' ), 50 );
			}

            add_filter( 'alpha_customize_fields', array( $this, 'add_customize_fields' ), 20 );
		}

        /**
         * Change the meta fields for Content Generator
         * 
         * @since 4.7
         */
        function add_meta_boxes( $meta_boxes ) {
            foreach ( $meta_boxes as $key => $meta_fields ) {
                if ( ! empty( $meta_fields['id'] ) && 'alpha-api-engine' == $meta_fields['id'] && ! empty( $meta_fields['fields'] ) && is_array( $meta_fields['fields'] ) ) {
                    foreach ( $meta_fields['fields'] as $field_key => $field ) {
                        if ( 'prompt_topic' == $field['id'] ) {
                            $meta_boxes[$key]['fields'][$field_key]['desc'] = sprintf( esc_html__( 'You can generate with this topic instead of the title. %1$sLearn More%2$s', 'alpha-core' ), '<a href="https://d-themes.com/wordpress/udesign/documentation/2023/03/20/openapi/" target="_blank">', '</a>' );
                        } else if( 'user_word' == $field['id'] ) {
                            $meta_boxes[$key]['fields'][$field_key]['desc'] = sprintf( esc_html__( 'You can improve the prompt for generating with this additional prompt. %1$sLearn More%2$s', 'alpha-core' ), '<a href="https://d-themes.com/wordpress/udesign/documentation/2023/03/20/openapi/" target="_blank">', '</a>' );
                        }
                    }
                }
            }
            return $meta_boxes;
        }
        
        /**
		 * Add vars to alpha_admin_vars
		 * 
		 * @since 4.7
		 */
		public function add_ai_vars( $vars ) {
			if ( function_exists( 'alpha_get_option' ) && ! empty( alpha_get_option( 'ai_generate_key' ) ) ) {
				$vars[ 'ai_refer_url' ] = 'https://d-themes.com/wordpress/udesign/documentation/2023/03/20/openapi/';
				$vars[ 'ai_logo' ] = esc_html__( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 15 15" version="1.1"><g><path style="fill:#ffffffcc" d="M 1.925781 0.148438 C 1.460938 0.257812 1.042969 0.679688 0.941406 1.144531 C 0.921875 1.226562 0.910156 2.519531 0.910156 4.777344 C 0.910156 8.503906 0.914062 8.683594 1.054688 9.367188 C 1.335938 10.761719 1.964844 11.96875 2.917969 12.945312 C 3.308594 13.347656 3.582031 13.582031 4 13.863281 C 5.695312 15.011719 7.847656 15.253906 9.742188 14.519531 C 11.300781 13.910156 12.554688 12.730469 13.332031 11.144531 C 13.761719 10.269531 13.992188 9.425781 14.0625 8.4375 C 14.101562 7.921875 14.101562 5.621094 14.058594 5.453125 C 13.953125 4.976562 13.539062 4.5625 13.0625 4.457031 C 12.375 4.292969 11.648438 4.757812 11.488281 5.453125 C 11.472656 5.527344 11.457031 6.082031 11.457031 6.796875 C 11.457031 8.410156 11.414062 8.804688 11.171875 9.503906 C 10.746094 10.730469 9.785156 11.726562 8.644531 12.117188 C 8.242188 12.253906 7.984375 12.292969 7.488281 12.289062 C 7.117188 12.289062 6.960938 12.273438 6.75 12.226562 C 5.847656 12.015625 5.089844 11.527344 4.511719 10.777344 C 4.058594 10.191406 3.773438 9.542969 3.613281 8.738281 C 3.566406 8.503906 3.5625 8.148438 3.542969 4.804688 L 3.527344 1.125 L 3.464844 0.96875 C 3.285156 0.546875 2.9375 0.246094 2.515625 0.148438 C 2.359375 0.109375 2.078125 0.113281 1.925781 0.148438 Z M 1.925781 0.148438 "/><path style="fill:#ffffffcc" d="M 12.585938 0.152344 C 12.179688 0.207031 11.777344 0.511719 11.601562 0.886719 C 11.167969 1.8125 11.941406 2.859375 12.957031 2.71875 C 13.367188 2.667969 13.765625 2.367188 13.945312 1.984375 C 14.382812 1.054688 13.605469 0.0117188 12.585938 0.152344 Z M 12.585938 0.152344 "/><path style="fill:#ffffffcc" d="M 7.21875 0.425781 C 5.964844 0.589844 4.996094 1.628906 4.832031 2.996094 C 4.816406 3.152344 4.804688 4.15625 4.816406 5.769531 C 4.824219 8.570312 4.816406 8.421875 5.019531 8.964844 C 5.289062 9.683594 5.871094 10.300781 6.527344 10.574219 C 6.878906 10.71875 7.128906 10.769531 7.511719 10.765625 C 8.210938 10.753906 8.820312 10.492188 9.320312 9.988281 C 9.664062 9.640625 9.949219 9.132812 10.078125 8.660156 C 10.1875 8.230469 10.199219 7.839844 10.1875 4.308594 L 10.183594 0.925781 L 10.089844 0.761719 C 10.027344 0.652344 9.953125 0.574219 9.855469 0.515625 L 9.714844 0.421875 L 8.511719 0.421875 C 7.851562 0.417969 7.273438 0.421875 7.21875 0.425781 Z M 7.21875 0.425781 "/></g></svg>', 'alpha-core' );
			}
			return $vars;
		}

		/**
		 * Add fields for OpenAPI
		 *
		 * @param {Array} $fields
		 *
		 * @param {Array} $fields
		 *
		 * @since 4.7
		 */
        public function add_customize_fields( $fields ) {
            
			$fields[ 'ai_generate_key_desc' ] = array(
                'section' => 'ai_generator',
                'type'    => 'custom',
                'label'   => sprintf( __( 'You can get your API Key in your %1$sOpenAI Account%2$s. We use the text-davinci-003 model. %5$sRead more%6$s about how to use. You must install %3$sMeta Box%4$s plugin.', 'alpha' ), '<a href="https://platform.openai.com/account/api-keys" target="_blank">', '</a>', '<b>', '</b>', '<a href="https://d-themes.com/wordpress/udesign/documentation/2023/03/20/openapi/" target="_blank">', '</a>' ),
                'default' => '<p class="options-custom-description option-feature-description"><img class="description-image" src="' . ALPHA_ASSETS . '/images/admin/customizer/ai-option.jpg' . '" alt="' . esc_html__( 'AI Content Generator', 'alpha' ) . '"></p>',
            );

            return $fields;

        }
    }
endif;

new Alpha_Content_Generator_Extend();