<?php
/**
 * Member Share
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

global $post;

$html   = '';
$shares = alpha_get_social_shares();
$type   = alpha_get_option( 'share_type' );
$custom = alpha_get_option( 'share_use_hover' ) ? '' : ' use-hover';

foreach ( $shares as $social => $info ) {
	$link = get_post_meta( $post->ID, 'member_' . $social, true );
	if ( $link ) {
		$html .= '<a href="' . esc_url( $link ) . '" class="social-icon ' . esc_attr( $type . $custom ) . ' ' . esc_attr( $info['icon'] ) . ' social-' . $social . '" target="_blank" title="' . $social . '" rel="noopener noreferrer"></a>';
	}
}
if ( $html ) {
	echo '<div class="social-icons">' . $html . '</div>';
}
