<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Show Type Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */
extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'grid_icon' => '',
			'list_icon' => '',
		),
		$atts
	)
);

$mode = 'grid';
if ( ! empty( $_COOKIE[ ALPHA_NAME . '_gridcookie' ] ) ) {
	$mode = $_COOKIE[ ALPHA_NAME . '_gridcookie' ];
}
?>
<div class="toolbox-item">
	<a href="#" class="btn-showtype mode-grid <?php echo esc_attr( $grid_icon ); ?><?php echo 'grid' == $mode ? ' active' : ''; ?>"></a>
	<a href="#" class="btn-showtype mode-list <?php echo esc_attr( $list_icon ); ?><?php echo 'list' == $mode ? ' active' : ''; ?>"></a>
</div>
<?php
