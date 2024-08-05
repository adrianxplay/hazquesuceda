<?php
/**
 * Member Contact
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

global $post;

$profile = get_post_meta( $post->ID, 'member_profile', true );
$email   = get_post_meta( $post->ID, 'member_email_addr', true );
$phone   = get_post_meta( $post->ID, 'member_phone', true );

if ( $profile && $email && $phone ) {
	?>
	<div class="member-contact">
		<h4><?php esc_html_e( 'Contact Info', 'alpha-core' ); ?></h4>
		<?php
		if ( $profile ) {
			echo '<p>' . alpha_strip_script_tags( $profile ) . '</p>';
		}
		if ( $email ) {
			echo '<label>' . esc_html__( 'E-mail Address', 'alpha-core' ) . '</label>';
			echo '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>';
		}
		if ( $phone ) {
			echo '<label>' . esc_html__( 'Phone Number', 'alpha-core' ) . '</label>';
			echo '<a href="' . esc_url( 'tel:' . $phone ) . '" class="telephone">' . esc_html( $phone ) . '</a>';
		}
		?>
	</div>
	<?php
}
