<?php
/**
 * The testimonial standard render.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

$html .= '<blockquote class="testimonial testimonial-standard ' . esc_attr( $atts['h_align'] ) . '" data-rating=' . esc_attr( $rating ) . '>';
if( 'top' === $avatar_pos ) {
    $html .= $avatar_html;
}
if( 'before_comment' === $rating_pos ) {
    $html .= $rating_html;
}
if( 'before' === $commenter_pos ) {
    $html .= $commenter;
}
$html .= '<div class="content">' . $content . '</div>';

if( 'after_comment' === $rating_pos ) {
    $html .= $rating_html;
}
if( 'after' === $commenter_pos ) {
    $html .= $commenter;
}
if( 'bottom' === $avatar_pos ) {
    $html .= $avatar_html;
}
$html .= '</blockquote>';