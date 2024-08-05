<?php
/**
 * The post widget template
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

alpha_get_template_part( 'posts/loop/post', 'media' );
?>
<div class="post-details">
	<?php
	alpha_get_template_part( 'posts/loop/post', 'date' );
	alpha_get_template_part( 'posts/loop/post', 'title' );
	alpha_get_template_part( 'posts/loop/post', 'author' );
	?>
</div>
