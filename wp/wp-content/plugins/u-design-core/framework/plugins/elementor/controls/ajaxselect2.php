<?php
/**
 * Alpha Ajax Select2 Control
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @version    1.0
 */
defined( 'ABSPATH' ) || die;

use Elementor\Base_Data_Control;
if ( ! class_exists( 'Alpha_Control_Ajaxselect2' ) ) {
	class Alpha_Control_Ajaxselect2 extends Base_Data_Control {

		/**
		 * Get select2 control type.
		 *
		 * @since 1.0
		 * @access public
		 *
		 * @return string Control type.
		 */
		public function get_type() {
			return 'ajaxselect2';
		}

		/**
		 * Get select2 control default settings.
		 *
		 * @since 1.0
		 * @access protected
		 *
		 * @return array Control default settings.
		 */
		protected function get_default_settings() {
			return array(
				'options'        => array(),
				'multiple'       => false,
				'select2options' => array(),
			);
		}

		/**
		 * Enqueue control scripts and styles.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function enqueue() {
			wp_enqueue_script( 'alpha-ajax-select2-control', alpha_core_framework_uri( '/plugins/elementor/controls/ajaxselect2-editor' . ALPHA_JS_SUFFIX ) );
		}

		/**
		 * Render select2 control output in the editor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function content_template() {
			$control_uid = $this->get_control_uid();
			$rest_api    = get_site_url( '' ) . '/wp-json/ajaxselect2/v1';
			?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<#
					var multiple = ( data.multiple ) ? 'multiple' : '', condition_name = '', condition_val = '', add_default = data.add_default ? '1' : '';
					if ( -1 !== data.options.indexOf( '%' ) ) {
						condition_name = data.options.split( '%' )[1];
						if (-1 !== condition_name.indexOf( '__' )) {
							condition_val = condition_name.split('__')[1];
							condition_name = condition_name.split('__')[0];
						}
						data.options = data.options.replace( '%', '' ).replace( '%', '' );
					}
				#>
				<select 
					id="<?php echo esc_attr( $control_uid ); ?>"
					class="elementor-ajaxselect2" 
					type="ajaxselect2" {{ multiple }} 
					data-setting="{{ data.name }}"
					data-ajax-url="<?php echo esc_url( $rest_api ) . '/{{data.options}}/'; ?>"
					data-condition="{{ condition_name }}"
					data-condition-val="{{ condition_val }}"
					data-add_default="{{ add_default }}"
				>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
			<?php
		}
	}
}
