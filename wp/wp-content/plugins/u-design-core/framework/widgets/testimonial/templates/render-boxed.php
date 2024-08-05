<?php
$html .= '<blockquote class="testimonial testimonial-boxed ' . ( 'top' == $avatar_pos ? 'avatar-top' : 'avatar-bottom' ) . '">';
if ( 'top' == $avatar_pos ) {
	$html .= $image;
}
if ( 'before_title' == $rating_pos ) {
	$html .= $rating_html;
}
if ( ! empty( $title_escaped ) ) {
	$html .= ' <h5 class="comment-title">' . $title_escaped . '</h5>';
}
if ( 'after_title' == $rating_pos ) {
	$html .= $rating_html;
}
if ( 'before' == $commenter_pos ) {
	$html .= '<div class="commentor">' . $commenter . '</div>';
}
if ( 'before_comment' == $rating_pos ) {
	$html .= $rating_html;
}

$html .= '<div class="content">' . $content . '</div>';

if ( 'after_comment' == $rating_pos ) {
	$html .= $rating_html;
}
if ( 'after' == $commenter_pos ) {
	$html .= $commenter;
}
if ( 'bottom' == $avatar_pos ) {
	$html .= $image;
}
$html .= '</blockquote>';
