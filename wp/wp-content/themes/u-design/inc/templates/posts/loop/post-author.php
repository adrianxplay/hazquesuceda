<?php
/**
 * Post Loop Author
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
?>
<span class="post-author">
	<?php
	// translators: %s represents author link tag.
	printf( esc_html__( 'by %s', 'alpha' ), get_the_author_posts_link() );
	?>
</span>
