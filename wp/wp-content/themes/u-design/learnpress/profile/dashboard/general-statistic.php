<?php
/**
 * Template for displaying general statistic in user profile overview.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $statistic ) ) {
	return;
}

$user = LP_Profile::instance()->get_user();
?>

<div id="dashboard-general-statistic">

	<?php do_action( 'learn-press/before-profile-dashboard-general-statistic-row' ); ?>

	<div class="dashboard-general-statistic__row">

		<?php do_action( 'learn-press/before-profile-dashboard-user-general-statistic' ); ?>

		<div class="statistic-box">
			<p class="statistic-box__text"><?php esc_html_e( 'Enrolled Courses', 'alpha' ); ?></p>
			<span class="statistic-box__number"><?php echo alpha_escaped( $statistic['enrolled_courses'] ); ?></span>
		</div>
		<div class="statistic-box">
			<p class="statistic-box__text"><?php esc_html_e( 'Active Courses', 'alpha' ); ?></p>
			<span class="statistic-box__number"><?php echo alpha_escaped( $statistic['active_courses'] ); ?></span>
		</div>
		<div class="statistic-box">
			<p class="statistic-box__text"><?php esc_html_e( 'Completed Courses', 'alpha' ); ?></p>
			<span class="statistic-box__number"><?php echo alpha_escaped( $statistic['completed_courses'] ); ?></span>
		</div>

		<?php do_action( 'learn-press/after-profile-dashboard-user-general-statistic' ); ?>

		<?php do_action( 'learn-press/profile-dashboard-general-statistic-row' ); ?>

	<?php if ( $user->can_create_course() ) : ?>

			<?php do_action( 'learn-press/before-profile-dashboard-instructor-general-statistic' ); ?>
			
			<div class="statistic-box">
				<p class="statistic-box__text"><?php esc_html_e( 'Total Courses', 'alpha' ); ?></p>
				<span class="statistic-box__number"><?php print_r( $statistic['total_courses'] ); ?></span>
			</div>
			<div class="statistic-box">
				<p class="statistic-box__text"><?php esc_html_e( 'Total Students', 'alpha' ); ?></p>
				<span class="statistic-box__number"><?php echo alpha_escaped( $statistic['total_users'] ); ?></span>
			</div>

			<?php do_action( 'learn-press/after-profile-dashboard-instructor-general-statistic' ); ?>

	<?php endif; ?>
	</div>

	<?php do_action( 'learn-press/after-profile-dashboard-general-statistic-row' ); ?>
</div>
