<?php
/**
 * Template for displaying wrap start of archive course within the loop.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/loop/course/loop-begin.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$layout_mode = isset( $layout ) ? $layout : learn_press_get_courses_layout();
$column      = alpha_get_responsive_cols(
	array(
		'lg'  => isset( $cols ) ? $cols : 3,
		'min' => 1,
	)
);

global $course_loop;
$course_loop = true;

if ( 'slider' == $layout_mode ) {
	$wrapper_class[] = alpha_get_slider_class();
	$wrapper_attrs   = ' data-slider-options="' . esc_attr(
		json_encode(
			alpha_get_slider_attrs(
				array(
					'show_nav'  => 'yes',
					'nav_pos'   => 'outer',
					'show_dots' => 'yes',
					'dots_pos'  => 'outer',
					'dots_skin' => 'dark',
				),
				$column
			)
		)
	) . '"';
	$wrapper_class[] = alpha_get_col_class( $column );

	echo '<ul class="learn-press-courses ' . esc_attr( implode( ' ', $wrapper_class ) ) . '"' . $wrapper_attrs . ' data-layout="grid">';
} else {
	echo apply_filters( 'learn_press_course_loop_begin', '<ul class="learn-press-courses' . alpha_get_col_class( $column ) . '" data-layout="' . $layout_mode . '">' );
}


