<?php
/**
 * Template for displaying progress of single course.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$course_data       = $user->get_course_data( $course->get_id() );
$course_results    = $course_data->calculate_course_results();
$passing_condition = $course->get_passing_condition();
$quiz_false        = 0;

if ( ! empty( $course_results['items'] ) ) {
	$quiz_false = $course_results['items']['quiz']['completed'] - $course_results['items']['quiz']['passed'];
}
?>

<div class="course-results-progress">

	<div class="course-progress">

		<div class="lp-course-status">
		<?php esc_html_e( 'Course progress:', 'alpha' ); ?> <span class="number"><?php echo round( $course_results['result'], 2 ); ?><span class="percentage-sign">%</span></span>
		</div>

		<div class="learn-press-progress lp-course-progress <?php echo ! $course_data->is_passed() ? '' : ' passed'; ?>" data-value="<?php echo esc_attr( $course_results['result'] ); ?>" data-passing-condition="<?php echo esc_attr( $passing_condition ); ?>" title="<?php echo esc_attr( learn_press_translate_course_result_required( $course ) ); ?>">
			<div class="progress-bg lp-progress-bar">
				<div class="progress-active lp-progress-value" style="left: <?php echo esc_attr( $course_results['result'] ); ?>%;">
				</div>
			</div>
			<div class="lp-passing-conditional" data-content="<?php printf( esc_html__( 'Passing condition: %s%%', 'alpha' ), $passing_condition ); ?>" style="left: <?php echo esc_attr( $passing_condition ); ?>%;">
			</div>
		</div>
	</div>

</div>
