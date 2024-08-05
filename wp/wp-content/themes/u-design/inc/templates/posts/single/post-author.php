<?php
/**
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

$author_description = get_the_author_meta( 'description' );
if ( ! $author_description ) {
	return;
}

$author_name = get_the_author_meta( 'display_name' );
$author_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
?>
<div class="post-author-detail">
	<figure class="author-avatar">
		<?php echo get_avatar( get_the_ID(), 50 ); ?>
	</figure>
	<div class="author-body">
		<h4 class="author-name">
			<?php echo esc_html( $author_name ); ?>
		</h4>
		<div class="author-content">
			<?php echo alpha_strip_script_tags( $author_description ); ?>
		</div>
		<a class="author-link btn btn-link btn-underline" href="<?php echo esc_url( $author_link ); ?>">
			<?php
			// translators: %s represents author name.
			printf( esc_html__( 'View all posts by %s', 'alpha' ), esc_html( $author_name ) );
			?>
			<i class="<?php echo ALPHA_ICON_PREFIX; ?>-icon-long-arrow-right"></i>
		</a>
	</div>
</div>
