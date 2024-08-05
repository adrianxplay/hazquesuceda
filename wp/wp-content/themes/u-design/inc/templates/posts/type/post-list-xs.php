<?php
/**
 * The post list xs template ( calendar )
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
	alpha_get_template_part( 'posts/single/post', 'date-in-category' );
	alpha_get_template_part( 'posts/loop/post', 'title' );
	?>
</div>
