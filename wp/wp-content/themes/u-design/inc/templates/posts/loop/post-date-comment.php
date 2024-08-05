<?php
/**
 * Post Meta
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;
?>
<div class="post-meta">
	<a class="post-date" href="<?php echo esc_url( get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ) ); ?>"><?php echo esc_html( get_the_date() ); ?></a>

	<?php
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		// translators: %1$s and %2$s are opening and closing of mark tag.
		$zero = sprintf( esc_html__( '%1$s0%2$s Comments', 'alpha' ), '<mark>', '</mark>' ); //phpcs:ignore
		// translators: %1$s and %2$s are opening and closing of mark tag.
		$one = sprintf( esc_html__( '%1$s1%2$s Comment', 'alpha' ), '<mark>', '</mark>' ); //phpcs:ignore
		// translators: %1$s and %3$s are opening and closing of mark tag, %2$s is %.
		$more = sprintf( esc_html__( '%1$s%2$s%3$s Comment', 'alpha' ), '<mark>', '%', '</mark>' ); //phpcs:ignore
		comments_popup_link( $zero, $one, $more, 'comments-link scroll-to local' );
	}
	?>
</div>
