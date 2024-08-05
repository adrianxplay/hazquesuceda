<?php
/**
 * Post Tag
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */

$tags = get_the_tag_list();

if ( $tags ) :
	?>
	<div class="post-tags">
		<?php echo alpha_strip_script_tags( $tags ); ?>
	</div>
	<?php
endif;
