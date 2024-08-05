<?php

/**
 * Alpha GDPR Addons
 *
 *
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since    1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_GDPR' ) ) {
	class Alpha_GDPR extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			// Add theme options
			add_filter( 'alpha_customize_fields', array( $this, 'add_customize_fields' ) );
			if ( function_exists( 'alpha_set_default_option' ) ) {
				alpha_set_default_option( 'show_cookie_info', false );
				// translators: %1$s represents link url, %2$s represents represents a closing tag.
				alpha_set_default_option( 'cookie_text', sprintf( esc_html__( 'By browsing this website, you agree to our %1$sPrivacy Policy%2$s.', 'alpha-core' ), '<a href="#">', '</a>' ) );
				alpha_set_default_option( 'cookie_version', 1 );
				alpha_set_default_option( 'cookie_agree_btn', esc_html__( 'I Agree', 'alpha-core' ) );

			}
			add_filter(
				'alpha_customize_sections',
				function( $sections ) {
					$sections['cookie_law_info'] = array(
						'title'    => esc_html__( 'Privacy Setting (GDPR)', 'alpha-core' ),
						'panel'    => 'advanced',
						'priority' => 100,
					);
					return $sections;
				}
			);

			if ( alpha_get_option( 'show_cookie_info' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_filter( 'alpha_vars', array( $this, 'add_localized_vars' ) );
				add_action( 'alpha_after_page_wrapper', array( $this, 'print_cookie_popup' ) );
			}
		}

		/**
		 * Add fields for compare
		 *
		 * @param {Array} $fields
		 *
		 * @param {Array} $fields
		 *
		 * @since 1.0
		 */
		public function add_customize_fields( $fields ) {
			// Cookie Law Options
			$fields['cs_cookie_law_title'] = array(
				'section' => 'cookie_law_info',
				'type'    => 'custom',
				'label'   => '',
				'default' => '<h3 class="options-custom-title">' . esc_html__( 'Privacy Consent Setting', 'alpha-core' ) . '</h3>',
			);
			$fields['show_cookie_info']    = array(
				'section' => 'cookie_law_info',
				'label'   => esc_html__( 'Show Privacy Consent Info Bar', 'alpha-core' ),
				'tooltip' => esc_html__( 'Under GDPR(General Data Protection Regulation), websites must make it clear to visitors who are from EU to control over their personal data that is being store by website. This specifically includes cookie.', 'alpha-core' ),
				'type'    => 'toggle',
			);
			$fields['cookie_text']         = array(
				'section'         => 'cookie_law_info',
				'label'           => esc_html__( 'Content', 'alpha-core' ),
				'description'     => esc_html__( 'Place some text here for cookie usage', 'alpha-core' ),
				'type'            => 'textarea',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'show_cookie_info',
						'operator' => '==',
						'value'    => true,
					),
				),
			);
			$fields['cookie_version']      = array(
				'section'         => 'cookie_law_info',
				'label'           => esc_html__( 'Cookie Version', 'alpha-core' ),
				'type'            => 'text',
				'active_callback' => array(
					array(
						'setting'  => 'show_cookie_info',
						'operator' => '==',
						'value'    => true,
					),
				),
			);
			$fields['cookie_agree_btn']    = array(
				'section'         => 'cookie_law_info',
				'label'           => esc_html__( 'Privacy Agreement Button Label', 'alpha-core' ),
				'type'            => 'text',
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'show_cookie_info',
						'operator' => '==',
						'value'    => true,
					),
				),
			);

			return $fields;
		}


		/**
		 * Enqueue style and script.
		 *
		 * @since 1.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_style( 'alpha-gdpr', alpha_core_framework_uri( '/addons/gdpr/gdpr' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
			wp_enqueue_script( 'alpha-gdpr', alpha_core_framework_uri( '/addons/gdpr/gdpr' . ALPHA_JS_SUFFIX ), array( 'alpha-theme' ), ALPHA_CORE_VERSION, true );
		}

		/**
		 * Add localized vars
		 *
		 * @since 1.0
		 * @param array $vars
		 * @return $vars
		 */
		public function add_localized_vars( $vars ) {
			$vars['cookie_version'] = alpha_get_option( 'cookie_version' );
			return $vars;
		}

		/**
		 * Print Cookie law information popup
		 *
		 * @since 1.0
		 *
		 * @return template
		 */
		public function print_cookie_popup( $show = false ) {
			?>
			<div class="cookies-popup<?php echo ! $show ? '' : ' show'; ?>">
				<div class="cookies-popup-inner d-flex align-items-center">
					<div class="cookies-info">
						<?php echo alpha_strip_script_tags( alpha_get_option( 'cookie_text' ) ); ?>
					</div>
					<a href="#" rel="nofollow noopener" class="btn btn-sm accept-cookie-btn"><?php echo alpha_strip_script_tags( alpha_get_option( 'cookie_agree_btn' ) ); ?></a>
					<a href="#" class="btn close-cookie-btn decline-cookie-btn btn-close" aria-label="<?php esc_attr_e( 'Close Cookie Consent', 'alpha-core' ); ?>"><i class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-times-solid"></i></a>
				</div>
			</div>
			<?php
		}
	}
	Alpha_GDPR::get_instance();
}



