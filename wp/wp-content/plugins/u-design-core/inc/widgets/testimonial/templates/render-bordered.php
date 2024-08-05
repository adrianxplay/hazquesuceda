<?php
/**
 * The testimonial bordered render.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

$html .= '<blockquote class="testimonial testimonial-boxed testimonial-aside testimonial-bordered ' . esc_attr( $atts['h_align'] ) . '" data-rating=' . esc_attr( $rating ) . '>';
if( 'after' === $commenter_pos ) {
    $html .= '<div class="content">';
    $html .= $content;
    $html .= '</div>';       
}
$html .= '<div class="commentor">';
$html .= $avatar_html;
$html .= '<div class="commentor-info">';
$html .= $rating_html;
$html .= $commenter;
$html .= '</div></div>';
if( 'before' === $commenter_pos ) {
    $html .= '<div class="content">';
    $html .= $content;
    $html .= '</div>';
}

$html .= '</blockquote>';