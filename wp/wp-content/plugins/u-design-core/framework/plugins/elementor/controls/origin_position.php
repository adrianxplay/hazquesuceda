<?php

defined( 'ABSPATH' ) || die;

/**
 * Alpha Origin Position Control
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Base_Data_Control;

if ( ! class_exists( 'Alpha_Control_Origin_Position' ) ) {
	class Alpha_Control_Origin_Position extends Base_Data_Control {
		public function get_type() {
			return 'origin_position';
		}

		public function content_template() {
			$control_uid = $this->get_control_uid( '{{value}}' );
			?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="elementor-choices elementor-origin-choices">
					<# _.each( data.options, function( options, value ) { #>
					<input id="<?php echo esc_attr( $control_uid ); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
					<label class="elementor-choices-label" for="<?php echo esc_attr( $control_uid ); ?>" title="{{{ options }}}">
						<span class="elementor-screen-only">{{{ options }}}</span>
					</label>
					<# } ); #>
				</div>
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
			<?php
		}

		protected function get_default_settings() {
			return array(
				'options' => array(),
				'toggle'  => true,
			);
		}
	}
}
