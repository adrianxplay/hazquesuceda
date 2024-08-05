<?php
/**
 * Template for displaying own courses in courses tab of user profile page.
 * Edit by Nhamdv
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$profile       = learn_press_get_profile();
$user          = LP_Profile::instance()->get_user();
$filter_status = LP_Request::get_string( 'filter-status' );
$query         = $profile->query_courses( 'own', array( 'status' => $filter_status ) );
$counts        = $query['counts'];
$filters       = $profile->get_own_courses_filters( $filter_status );
?>

<div class="learn-press-subtab-content">
	<?php if ( $filters ) : ?>
		<div class="learn-press-filters tab tab-nav-simple tab-nav-separated tab-nav-left">
			<ul class="nav nav-tabs">
				<?php
				foreach ( $filters as $class => $link ) {
					$count = ! empty( $counts[ $class ] ) ? absint( $counts[ $class ] ) : false;

					if ( $count ) {
						?>
						<li class="<?php echo esc_attr( 'nav-item ' . $class ); ?>">
							<?php
							// printf( '%s <span class="count">%s</span>', $link, $count );
							echo alpha_strip_script_tags( $link );
							?>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( ! $query['total'] ) : ?>
		<?php learn_press_display_message( esc_html__( 'No courses!', 'alpha' ) ); ?>
	<?php else : ?>
		<div class="lp-archive-courses">
			<ul <?php lp_item_course_class( array( 'profile-courses-list' ) ); ?> id="learn-press-profile-created-courses" data-layout="grid" data-size="3">
				<?php
				global $post;

				foreach ( $query['items'] as $item ) {
					$course = learn_press_get_course( $item );
					$post   = get_post( $item );
					setup_postdata( $post );
					learn_press_get_template( 'content-course.php' );
				}

				wp_reset_postdata();
				?>
			</ul>
		</div>

		<?php
		$num_pages    = $query->get_pages();
		$current_page = $query->get_paged();
		?>

		<?php if ( $num_pages > 1 && $current_page < $num_pages ) : ?>
			<button data-container="learn-press-profile-created-courses"
					data-pages="<?php echo (int) $num_pages; ?>"
					data-paged="<?php echo (int) $current_page; ?>"
					class="lp-button btn-load-more-courses btn-ajax-off">
				<i class="fas fa-spinner icon"></i>
				<?php esc_html_e( 'View More', 'alpha' ); ?></button>
		<?php endif; ?>
	<?php endif; ?>
</div>
