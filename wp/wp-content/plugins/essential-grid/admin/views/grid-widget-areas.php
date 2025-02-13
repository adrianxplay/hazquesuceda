<?php
/**
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2024 ThemePunch
 * @since 1.0.6
 */
 
if( !defined( 'ABSPATH') ) exit();

?>
<h2 class="topheader"><?php esc_html_e('Custom Widgets', ESG_TEXTDOMAIN); ?></h2>

<div id="eg-grid-widget-areas-wrapper">
	<?php
	$wa = new Essential_Grid_Widget_Areas();
	$sidebars = $wa->get_all_sidebars();
	
	if(is_array($sidebars) && !empty($sidebars)){
		foreach($sidebars as $handle => $name){
			?>
			<div class="eg-pbox esg-box esg-box-grid-widget-area">
				<h3 class="box-closed"><span class="esg-font-w-400"><?php esc_html_e('Handle:', ESG_TEXTDOMAIN); ?></span><span>eg-<?php echo $handle; ?> </span><span class="eg-pbox-arrow"></span></h3>
				<div class="esg-box-inside">
					<input type="hidden" name="esg-widget-area-handle[]" value="<?php echo $handle; ?>" />
					<div class="eg-custommeta-row">
						<div class="eg-cus-row-l"><label for="esg-widget-area-name"><?php esc_html_e('Name:', ESG_TEXTDOMAIN); ?></label><input type="text" id="esg-widget-area-name" name="esg-widget-area-name[]" value="<?php echo @$name; ?>"></div>
					</div>
					
					<div class="eg-widget-area-save-wrap-settings">
						<a class="esg-btn esg-blue eg-widget-area-edit" href="javascript:void(0);"><?php esc_html_e('Edit', ESG_TEXTDOMAIN); ?></a>
						<a class="esg-btn  eg-widget-area-delete" href="javascript:void(0);"><?php esc_html_e('Remove', ESG_TEXTDOMAIN); ?></a>
					</div>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>
<a class="esg-btn esg-blue" id="eg-widget-area-add" href="javascript:void(0);"><?php esc_html_e('Add New Widget Area', ESG_TEXTDOMAIN); ?></a>
<?php Essential_Grid_Dialogs::widget_areas_dialog(); ?>
<script type="text/javascript">
	window.ESG ??={};
	ESG.F ??= {};
	ESG.E ??= {};
	ESG.V ??= {};
	ESG.S ??= {};
	ESG.C ??= {};
	ESG.LIB = ESG.LIB===undefined ? { nav_skins:[], item_skins:{}, nav_originals:{}} : ESG.LIB;
	ESG.CM = ESG.CM===undefined ? {apiJS:null, ajaxCSS:null, navCSS:null} : ESG.CM;
	ESG.WIN ??=jQuery(window);
	ESG.DOC ??=jQuery(document);

	ESG.E.waitTptFunc ??= [];
	ESG.E.waitTptFunc.push(function(){
		jQuery(function(){ AdminEssentials.initWidgetAreas(); });
	});
</script>
