<?php
/**
 * Alpha Elementor Hotspot Widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Alpha_Controls_Manager;

class Alpha_Hotspot_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_hotspot';
	}

	public function get_title() {
		return esc_html__( 'Hotspot', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-hotspot';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'hotspot', 'dot', 'banner' );
	}

	/**
	 * Get Style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-hotspot', alpha_core_framework_uri( '/widgets/hotspot/hotspot' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-hotspot' );
	}

	protected function register_controls() {
		alpha_elementor_hotspot_layout_controls( $this );
		alpha_elementor_hotspot_style_controls( $this );
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/hotspot/render-hotspot-elementor.php' );
	}
}
