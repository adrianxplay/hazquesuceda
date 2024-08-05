<?php
/**
 * Single Member
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */


// Show Info
$show_info = alpha_get_option( 'member_show_info', array() );
$classes   = get_post_class();

if ( in_array( 'meta', $show_info ) ) {
	$show_info[] = 'author';
	$show_info[] = 'date';
	$show_info[] = 'comment_count';
}
if ( ! in_array( 'post', $classes ) ) {
	$classes[] = 'post';
}

$classes[] = 'row';
$classes[] = 'member-single';

// The Post
the_post();

if ( ! apply_filters( 'alpha_run_single_builder', false ) ) {
	?>

	<div class="<?php echo esc_attr( implode( ' ', apply_filters( 'alpha_post_single_class', array( 'post-single-wrap' ) ) ) ); ?>">

		<?php do_action( 'alpha_post_loop_before_item', 'default', 'single' ); ?>

		<article class="<?php echo esc_attr( implode( ' ', apply_filters( 'alpha_post_single_classes', $classes ) ) ); ?>">
			<div class="col-md-4 member-profile">
				<?php
				if ( in_array( 'image', $show_info ) ) {
					alpha_get_template_part( 'posts/single/post', 'media' );
				}
				if ( in_array( 'share', $show_info ) ) {
					alpha_get_template_part( 'posts/loop/member', 'share' );
				}
				if ( in_array( 'contact', $show_info ) ) {
					alpha_get_template_part( 'posts/single/member', 'contact' );
				}
				if ( in_array( 'appointment', $show_info ) ) {
					alpha_get_template_part( 'posts/single/member', 'appointment' );
				}
				?>
			</div>
			<div class="col-md-8">
				<?php
				if ( in_array( 'title', $show_info ) ) {
					alpha_get_template_part( 'posts/single/post', 'title' );
				}
				if ( in_array( 'category', $show_info ) ) {
					alpha_get_template_part( 'posts/loop/post', 'category' );
				}
				alpha_get_template_part( 'posts/single/post', 'content' );
				?>
			</div>
		</article>

		<?php
		do_action( 'alpha_post_loop_after_item', 'default', 'single' );

		if ( in_array( 'related', $show_info ) ) {
			alpha_get_template_part( 'posts/single/member', 'related' );
		}
		?>
	</div>
	<?php
}
