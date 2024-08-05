<?php

$html .= '<blockquote class="testimonial testimonial-aside ' . ( 'yes' == $atts['testimonial_inverse'] ? ' inversed' : '' ) . '">';
$html .= '<div class="commentor">';
$html .= $image;
if ( 'after_avatar' == $aside_commenter_pos ) {
	$html .= $commenter;
}
$html .= '</div>';
$html .= '<div class="content">';
if ( 'before_title' == $rating_pos ) {
	$html .= $rating_html;
}
if ( ! empty( $title_escaped ) ) {
	$html .= ' <h5 class="comment-title">' . $title_escaped . '</h5>';
}
if ( 'after_title' == $rating_pos ) {
	$html .= $rating_html;
}
if ( 'before_comment' == $aside_commenter_pos ) {
	$html .= $commenter;
}
if ( 'before_comment' == $rating_pos ) {
	$html .= $rating_html;
}
$html .= $content;
if ( 'after_comment' == $rating_pos ) {
	$html .= $rating_html;
}
if ( 'after_comment' == $aside_commenter_pos ) {
	$html .= $commenter;
}
$html .= '</div>';
$html .= '</blockquote>';
