<?php
/**
 * Post Related
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

if ( 'attachment' == get_post_type() ) {
	return;
}

global $alpha_layout;

if ( ! isset( $args ) ) {
	$args = array();
}

$related_posts = new WP_Query( Alpha_CPTS::get_instance()->related_posts( get_the_ID(), $alpha_layout['related_count'], 'portfolio' ) );

if ( $related_posts && $related_posts->have_posts() ) :

	$col_cnt = alpha_get_responsive_cols(
		( ! empty( $alpha_layout['related_column'] ) && $alpha_layout['related_column'] > 3 ) ?
		array(
			'xl' => $alpha_layout['related_column'],
			'lg' => 3,
		) :
		array( 'lg' => empty( $alpha_layout['related_column'] ) ? 4 : $alpha_layout['related_column'] ),
		'post'
	);

	?>
	<section class="related-posts">
		<h3 class="title title-simple"><?php echo alpha_get_option( 'portfolio_related_title' ); ?></h3>
		<div>
			<?php
			$props = array(
				'posts_layout' => 'slider',
				'col_cnt'      => $col_cnt,
				'related'      => true,
			);

			do_action( 'alpha_before_posts_loop', $props );

			alpha_get_template_part( 'posts/post', 'loop-start' );

			while ( $related_posts->have_posts() ) :
				$related_posts->the_post();
				alpha_get_template_part( 'posts/post' );
			endwhile;

			alpha_get_template_part( 'posts/post', 'loop-end' );

			do_action( 'alpha_after_posts_loop' );

			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
endif;
