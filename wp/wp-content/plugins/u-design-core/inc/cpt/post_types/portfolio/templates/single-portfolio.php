<?php
/**
 * Single Portfolio
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */

// Show Info
$show_info = alpha_get_option( 'portfolio_show_info', array() );
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
$classes[] = 'portfolio-single';

// The Post
the_post();

if ( ! apply_filters( 'alpha_run_single_builder', false ) ) {
	?>

	<div class="<?php echo esc_attr( implode( ' ', apply_filters( 'alpha_post_single_class', array( 'post-single-wrap' ) ) ) ); ?>">

		<?php do_action( 'alpha_post_loop_before_item', 'default', 'single' ); ?>

		<article class="<?php echo esc_attr( implode( ' ', apply_filters( 'alpha_post_single_classes', $classes ) ) ); ?>">
			<div class="col-md-6">
				<div>
				<?php
				if ( in_array( 'image', $show_info ) ) {
					alpha_get_template_part( 'posts/single/post', 'media' );
				}
				?>
				</div>
				<?php

				if ( in_array( 'share', $show_info ) && function_exists( 'alpha_print_share' ) ) {
					alpha_print_share();
				}
				?>
			</div>
			<div class="col-md-6">
				<?php
				alpha_get_template_part( 'posts/single/post', 'title' );
				alpha_get_template_part( 'posts/single/post', 'content' );

				alpha_get_template_part( 'posts/loop/portfolio', 'meta', array( 'show_info' => $show_info ) );
				?>
			</div>
		</article>

		<?php

		do_action( 'alpha_post_loop_after_item', 'default', 'single' );

		if ( in_array( 'navigation', $show_info ) ) {
			?>
			<hr class="mt-0 mb-6">
			<?php
			alpha_get_template_part( 'posts/single/post-navigation' );
			?>
			<hr class="mt-6 mb-0">
			<?php
		}

		if ( in_array( 'related', $show_info ) ) {
			alpha_get_template_part( 'posts/single/portfolio', 'related' );
		}

		if ( in_array( 'comments_list', $show_info ) ) {
			?>
			<hr class="mt-0 <?php echo get_comments_number() ? 'mb-0' : 'mb-10 pb-5'; ?>">
			<?php
			comments_template();
		}
		?>
	</div>
	<?php
}
