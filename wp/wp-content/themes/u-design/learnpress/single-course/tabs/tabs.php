<?php
/**
 * Template for displaying tab nav of single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/tabs/tabs.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$tabs = learn_press_get_course_tabs();

if ( empty( $tabs ) ) {
	return;
}

$active_tab = learn_press_cookie_get( 'course-tab' );

if ( ! $active_tab ) {
	$tab_keys   = array_keys( $tabs );
	$active_tab = reset( $tab_keys );
}

// Show status course
$lp_user = learn_press_get_current_user();

if ( $lp_user && ! $lp_user instanceof LP_User_Guest ) {
	$can_view_course = $lp_user->can_view_content_course( get_the_ID() );

	if ( ! $can_view_course->flag ) {
		if ( LP_BLOCK_COURSE_FINISHED === $can_view_course->key ) {
			learn_press_display_message(
				esc_html__( 'You finished this course. This course has been blocked', 'alpha' ),
				'warning'
			);
		} elseif ( LP_BLOCK_COURSE_DURATION_EXPIRE === $can_view_course->key ) {
			learn_press_display_message(
				esc_html__( 'This course has been blocked reason by expire', 'alpha' ),
				'warning'
			);
		}
	}
}
?>

<div id="learn-press-course-tabs" class="course-tabs tab tab-nav-underline tab-nav-left">
	<?php foreach ( $tabs as $key => $tab ) : ?>
		<input type="radio" name="learn-press-course-tab-radio" id="tab-<?php echo esc_attr( $key ); ?>-input"
			<?php checked( $active_tab === $key ); ?> value="<?php echo esc_attr( $key ); ?>"/>
	<?php endforeach; ?>

	<ul class="learn-press-nav-tabs course-nav-tabs nav nav-tabs" data-tabs="<?php echo count( $tabs ); ?>">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<?php
			$classes = array( 'course-nav course-nav-tab-' . esc_attr( $key ), 'nav-link' );

			if ( $active_tab === $key ) {
				$classes[] = 'active';
			}
			?>

			<li class="<?php echo implode( ' ', $classes ); ?>">
				<label for="tab-<?php echo esc_attr( $key ); ?>-input"><?php echo alpha_strip_script_tags( $tab['title'] ); ?></label>
			</li>
		<?php endforeach; ?>

	</ul>

	<div class="course-tab-panels">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="course-tab-panel-<?php echo esc_attr( $key ); ?> course-tab-panel"
				 id="<?php echo esc_attr( $tab['id'] ); ?>">
				<?php
				if ( is_callable( $tab['callback'] ) ) {
					call_user_func( $tab['callback'], $key, $tab );
				} else {
					do_action( 'learn-press/course-tab-content', $key, $tab );
				}
				?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
