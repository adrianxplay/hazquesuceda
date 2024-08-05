<?php
/**
 * The post intro template
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

alpha_get_template_part( 'posts/loop/post', 'media' );
alpha_get_template_part( 'posts/single/post', 'date-in-category' );

?>
<div class="post-details">
	<?php
	alpha_get_template_part( 'posts/loop/post', 'title' );
	alpha_get_template_part( 'posts/single/post', 'meta' );
	if ( ! alpha_get_loop_prop( 'related' ) ) {
		alpha_get_template_part( 'posts/loop/post', 'content' );
	}
	alpha_get_template_part( 'posts/loop/post', 'readmore' );
	?>
</div>
