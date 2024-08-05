<?php
/**
 * Template for displaying top-bar in archive course page.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

$layouts = learn_press_courses_layouts();
$active  = learn_press_get_courses_layout();
$s       = LP_Request::get( 's' );

$default_order = apply_filters(
	'alpha_course_order_options',
	array(
		''           => esc_html__( 'Default sorting', 'alpha' ),
		'date'       => esc_html__( 'Sort by latest', 'alpha' ),
		'name'       => esc_html__( 'Sort by Name', 'alpha' ),
		'price_low'  => esc_html__( 'Sort by price: low to high', 'alpha' ),
		'price_high' => esc_html__( 'Sort by price: high to low', 'alpha' ),
	)
);


?>

<div class="lp-courses-bar toolbox <?php echo esc_attr( $active ); ?>">

	<div class="alpha-course-order toolbox-item select-box">
		<label><?php esc_html_e( 'Sort By :', 'alpha' ); ?></label>
		<select name="orderby" class="orderby form-control">
			<?php
			foreach ( $default_order as $k => $v ) {
				echo '<option value="' . esc_attr( $k ) . '"' . ( isset( $_GET['order'] ) && $k == $_GET['order'] ? ' selected' : '' ) . '>' . ( $v ) . '</option>';
			}
			?>
		</select>
	</div>
	<div class="switch-layout">
		<?php foreach ( $layouts as $layout ) : ?>
			<input type="radio" name="lp-switch-layout-btn" value="<?php echo esc_attr( $layout ); ?>" id="lp-switch-layout-btn-<?php echo esc_attr( $layout ); ?>" <?php checked( $layout, $active ); ?>>
			<label class="switch-btn <?php echo esc_attr( $layout ); ?>" title="<?php echo sprintf( esc_attr__( 'Switch to %s', 'alpha' ), $layout ); ?>" for="lp-switch-layout-btn-<?php echo esc_attr( $layout ); ?>"></label>
		<?php endforeach; ?>
	</div>
</div>
