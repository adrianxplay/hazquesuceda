<?php
/**
 * Member Contact
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;
$bookworkdays   = '';
$start_time     = '';
$end_time       = '';
$booking_period = '';
?>
<div class="mini-basket-box offcanvas-type">
	<a href="#" class="btn btn-primary btn-appointment" title="<?php esc_attr_e( 'Make an Appointment', 'alpha-core' ); ?>">
		<span><?php esc_html_e( 'Make an Appointment', 'alpha-core' ); ?></span>
	</a>
	<div class="offcanvas-overlay"></div>
	<div class="dropdown-box scrollable">
		<div class="booking-form-wrap">
			<div class="offcanvas-header">
				<h4><?php esc_html_e( 'Appointment', 'alpha-core' ); ?></h4>
				<a class="btn btn-dark btn-link btn-icon-right btn-close" href="#"><?php esc_html_e( 'Close', 'alpha-core' ); ?><i class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-long-arrow-right"></i></a>
			</div>
			<form class="booking-form" method="POST">
				<input type="text" class="form-control" name="alpha_booking_member" placeholder="<?php esc_attr_e( 'Member', 'alpha-core' ); ?>" value="<?php the_title(); ?>" readonly="readonly">
				<input type="text" class="form-control" name="alpha_booking_name" placeholder="<?php esc_attr_e( 'Your Name', 'alpha-core' ); ?>" required>
				<input type="text" class="form-control" name="alpha_booking_contact" placeholder="<?php esc_attr_e( 'Phone', 'alpha-core' ); ?>" required>
				<div class="form-control-wrap">
					<input type="text" class="form-control form-date-control" name="alpha_booking_date" placeholder="<?php esc_attr_e( 'Date', 'alpha-core' ); ?>" required>
				</div>
				<div class="form-control-wrap">
					<input type="text" class="form-control form-time-control" name="alpha_booking_time" placeholder="<?php esc_attr_e( 'Time', 'alpha-core' ); ?>" required>
				</div>
				<textarea class="form-control" name="alpha_booking_message" placeholder="<?php esc_attr_e( 'Message', 'alpha-core' ); ?>" rows="8" required></textarea>
				<input type="hidden" name="alpha_booking_member_id" value="<?php echo get_the_ID(); ?>">
				<div class="booking-form-submit mt-4 mb-4">
					<input type="submit" class="btn btn-primary btn-block" value="<?php esc_attr_e( 'Book Now', 'alpha-core' ); ?>">
				</div>
			</form>
		</div>
	</div>
</div>
