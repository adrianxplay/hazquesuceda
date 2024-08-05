<?php
/**
 * The portfolio list template
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

alpha_get_template_part( 'posts/loop/portfolio', 'media' );
?>
<div class="post-details">
	<?php
	alpha_get_template_part( 'posts/loop/post', 'category' );
	alpha_get_template_part( 'posts/loop/post', 'title' );
	alpha_get_template_part( 'posts/loop/portfolio', 'meta' );
	alpha_get_template_part( 'posts/loop/post', 'readmore' );
	?>
</div>
