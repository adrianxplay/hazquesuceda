<?php
/**
 * Alpha Share class
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Share' ) ) {
	/**
	 * Alpha Share Class
	 *
	 * @since 1.0
	 */
	class Alpha_Share extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Initialize
		 *
		 * @since 1.0
		 */
		public function init() {

			// Woocommerce actions
			add_action( 'init', array( $this, 'setup_wc_share' ), 8 );

			// Social Login
			add_action( 'alpha_after_customer_login_form', array( $this, 'print_social_login_content' ) );
		}

		/**
		 * Setup WooCommerce share
		 *
		 * @since 1.0
		 */
		public function setup_wc_share() {
			add_action( 'woocommerce_share', 'alpha_print_share' );
		}

		/**
		 * Print social login content
		 *
		 * @since 1.0
		 */
		public function print_social_login_content() {
			$is_facebook_login = $this->nextend_social_login( 'facebook' );
			$is_google_login   = $this->nextend_social_login( 'google' );
			$is_twitter_login  = $this->nextend_social_login( 'twitter' );

			if ( ( $is_facebook_login || $is_google_login || $is_twitter_login ) && alpha_get_option( 'social_login' ) ) {
				?>

				<div class="social-login text-center">
					<p><?php esc_html_e( 'Sign in with social account', 'alpha-core' ); ?></p>
					<div class="social-icons">
					<?php do_action( 'alpha_before_login_social' ); ?>
					<?php if ( $is_facebook_login ) { ?>
						<a class="social-icon stacked social-facebook" href="<?php echo wp_login_url(); ?>?loginFacebook=1&redirect=<?php echo the_permalink(); ?>" onclick="window.location.href = '<?php echo wp_login_url(); ?>?loginFacebook=1&redirect='+window.location.href; return false">
							<i class="fab fa-facebook-f"></i></a>
					<?php } ?>
					<?php if ( $is_twitter_login ) { ?>
						<a class="social-icon stacked social-twitter" href="<?php echo wp_login_url(); ?>?loginSocial=twitter&redirect=<?php echo the_permalink(); ?>" onclick="window.location.href = '<?php echo wp_login_url(); ?>?loginSocial=twitter&redirect='+window.location.href; return false">
							<i class="fab fa-twitter"></i></a>
					<?php } ?>
					<?php if ( $is_google_login ) { ?>
						<a class="social-icon stacked social-google" href="<?php echo wp_login_url(); ?>?loginGoogle=1&redirect=<?php echo the_permalink(); ?>" onclick="window.location.href = '<?php echo wp_login_url(); ?>?loginGoogle=1&redirect='+window.location.href; return false">
							<i class="fab fa-google"></i></a>
					<?php } ?>
					<?php do_action( 'alpha_after_login_social' ); ?>
					</div>
				</div>

				<?php
			}
		}

		/**
		 * Get nextend social login
		 *
		 * @since 1.0
		 *
		 * @param string $social Social media
		 *
		 * @return boolean
		 */
		function nextend_social_login( $social ) {
			$res = '';
			if ( class_exists( 'NextendSocialLogin', false ) ) {
				$res = NextendSocialLogin::isProviderEnabled( $social );
			} else {
				if ( 'facebook' == $social ) {
					$res = defined( 'NEW_FB_LOGIN' );
				} elseif ( 'google' == $social ) {
					$res = defined( 'NEW_GOOGLE_LOGIN' );
				} elseif ( 'twitter' == $social ) {
					$res = defined( 'NEW_TWITTER_LOGIN' );
				}
			}
			/**
			 * Filters nextend social login.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_nextend_social_login', $res, $social );
		}
	}
}

/**
 * Create instance
 */
Alpha_Share::get_instance();

if ( ! function_exists( 'alpha_print_share' ) ) {
	/**
	 * Print Share
	 *
	 * @since 1.0
	 */
	function alpha_print_share() {
		if ( ! function_exists( 'alpha_get_option' ) || ! function_exists( 'alpha_get_social_shares' ) ) {
			return;
		}

		ob_start();
		?>
		<div class="social-icons">
			<?php

			$social_shares = alpha_get_social_shares();
			$icon_type     = 'framed';
			$custom        = ' use-hover';
			$share_icons   = array( 'facebook', 'twitter', 'pinterest', 'whatsapp', 'linkedin' );

			foreach ( $share_icons as $share ) {
				$permalink = apply_filters( 'the_permalink', get_permalink() );
				$title     = esc_attr( get_the_title() );
				$image     = wp_get_attachment_url( get_post_thumbnail_id() );

				if ( class_exists( 'YITH_WCWL' ) && is_user_logged_in() ) {
					if ( get_option( 'yith_wcwl_wishlist_page_id' ) == get_the_ID() ) {
						$wishlist_id = ( YITH_WCWL()->last_operation_token ) ? YITH_WCWL()->last_operation_token : YITH_WCWL()->details['wishlist_id'];
						$permalink  .= '/view/' . $wishlist_id;
						$permalink   = urlencode( $permalink );
					}
				}

				$permalink = esc_url( $permalink );

				if ( 'whatsapp' == $share ) {
					$title = rawurlencode( $title );
				} else {
					$title = urlencode( $title );
				}

				$link = strtr(
					$social_shares[ $share ]['link'],
					array(
						'$permalink' => $permalink,
						'$title'     => $title,
						'$image'     => $image,
					)
				);
				$link = 'whatsapp' == $share || 'email' == $share ? esc_attr( $link ) : esc_url( $link );
				$link = $link ? $link : '#';

				echo '<a href="' . alpha_escaped( $link ) . '" class="social-icon ' . esc_attr( $icon_type . $custom ) . ' social-' . $share . '" target="_blank" rel="noopener noreferrer" title="' . $social_shares[ $share ]['title'] . '">';
				echo '<i class="' . esc_attr( $social_shares[ $share ]['icon'] ) . '"></i>';
				echo '</a>';
			}
			?>
		</div>
		<?php
		echo ob_get_clean();
	}
}
