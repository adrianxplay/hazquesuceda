<?php
/**
 * The testimonial simple render.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

$html .= '<blockquote class="testimonial testimonial-simple ' . ( 'yes' == $atts['testimonial_inverse'] ? ' inversed' : '' ) . '" data-rating=' . esc_attr( $rating ) . '>';
$html .= '<div class="content">' . $content . '</div>';
$html .= '<div class="commenter">';
$html .= $avatar_html;
$html .= '<div class="commentor-info">';
$html .= $rating_html;
$html .= $commenter;
$html .= '</div></div>';
$html .= '</blockquote>';