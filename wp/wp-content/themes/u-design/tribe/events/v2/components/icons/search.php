<?php
/**
 * View: Search Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/v2/components/icons/search.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @var array<string> $classes Additional classes to add to the svg icon.
 *
 * @version 4.12.14
 *
 */
$svg_classes = [ 'tribe-common-c-svgicon', 'tribe-common-c-svgicon--search' ];

if ( ! empty( $classes ) ) {
	$svg_classes = array_merge( $svg_classes, $classes );
}
?>
<svg <?php tribe_classes( $svg_classes ); ?> viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><g>
	<path fill="#231F20" d="M49.221,56.647c1.829,0,3.553-0.596,4.99-1.723l0.411-0.323l0.186-0.193
		c1.613-1.538,2.501-3.605,2.501-5.82c0-4.444-3.629-8.06-8.087-8.06c-4.458,0-8.085,3.615-8.085,8.06S44.763,56.647,49.221,56.647z
		 M49.222,41.408c3.972,0,7.204,3.22,7.204,7.179c0,3.961-3.231,7.182-7.204,7.182c-3.972,0-7.203-3.221-7.203-7.182
		C42.019,44.628,45.25,41.408,49.222,41.408z"/>
	<path fill="#231F20" d="M58.231,59.23c0.147-0.007,0.311-0.091,0.429-0.223c0.09-0.099,0.139-0.21,0.134-0.306
		c-0.053-1.115-2.729-3.181-3.756-3.882c-0.071-0.051-0.164-0.074-0.268-0.068l-0.35,0.017l-0.056,0.347
		c-0.019,0.114,0.001,0.228,0.056,0.311c0.95,1.49,2.825,3.84,3.806,3.805l0.018,0.262l0,0L58.231,59.23z"/>
</g></svg>
