<?php
/**
 * Post Content
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
defined( 'ABSPATH' ) || die;

?>
<div class="post-content">
	<?php
	echo alpha_get_excerpt(
		$GLOBALS['post'],
		alpha_get_loop_prop( 'excerpt_length' ),
		alpha_get_loop_prop( 'excerpt_type' )
	);
	?>
</div>
