<?php
/**
 * Template for displaying list orders in orders tab of user profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/orders/list.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.1
 */

defined( 'ABSPATH' ) || exit();

$profile = LP_Profile::instance();

$query_orders = $profile->query_orders( array( 'fields' => 'ids' ) );
if ( ! $query_orders->get_items() ) {
	learn_press_display_message( __( 'No orders!', 'alpha' ) );
	return;
}
?>

<h3 class="profile-heading"><?php esc_html_e( 'My Orders', 'alpha' ); ?></h3>

<table class="lp-list-table profile-list-orders profile-list-table">
	<thead>
		<tr class="order-row">
			<th class="column-order-number"><?php esc_html_e( 'Order', 'alpha' ); ?></th>
			<th class="column-order-date"><?php esc_html_e( 'Date', 'alpha' ); ?></th>
			<th class="column-order-status"><?php esc_html_e( 'Status', 'alpha' ); ?></th>
			<th class="column-order-total"><?php esc_html_e( 'Total', 'alpha' ); ?></th>
			<th class="column-order-actions"><?php esc_html_e( 'Actions', 'alpha' ); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php
		foreach ( $query_orders->get_items() as $order_id ) {
			$order = learn_press_get_order( $order_id );
			?>

			<tr class="order-row">
				<td class="column-order-number">
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
						<?php echo esc_html( $order->get_order_number() ); ?>
					</a>
				</td>
				<td class="column-order-date"><?php echo esc_html( $order->get_order_date() ); ?></td>
				<td class="column-order-status">
					<span class="lp-label label-<?php echo esc_attr( $order->get_status() ); ?>">
						<?php echo esc_html( $order->get_order_status_html() ); ?>
					</span>
				</td>
				<td class="column-order-total"><?php echo esc_html( $order->get_formatted_order_total() ); ?></td>
				<td class="column-order-actions">
					<?php
					$actions = $order->get_profile_order_actions();

					if ( $actions ) {
						foreach ( $actions as $action ) {
							printf( '<a href="%s">%s</a>', esc_url_raw( $action['url'] ), $action['text'] );
						}
					}
					?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
