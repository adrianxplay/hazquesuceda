<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Show Type Widget
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;

class Alpha_Shop_Show_Type_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_shop_widget_show_type';
	}

	public function get_title() {
		return esc_html__( 'Grid / List Toggle', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_shop_widget' );
	}

	public function get_keywords() {
		return array( 'show-type', 'grid', 'list', 'shop', 'woocommerce' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-apps';
	}

	protected function register_controls() {

		$left = is_rtl() ? 'right' : 'left';

		$this->start_controls_section(
			'section_grid',
			array(
				'label' => esc_html__( 'Grid / List Toggle', 'alpha-core' ),
			)
		);

			$this->add_control(
				'grid_icon',
				array(
					'label'                  => esc_html__( 'Icon', 'alpha-core' ),
					'description'            => esc_html__( 'Set the grid toggle icon.​', 'alpha-core' ),
					'type'                   => Controls_Manager::ICONS,
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-grid',
						'library' => 'alpha-icons',
					),
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
				)
			);

			$this->add_control(
				'list_icon',
				array(
					'label'                  => esc_html__( 'Icon', 'alpha-core' ),
					'description'            => esc_html__( 'Set the list toggle icon.​', 'alpha-core' ),
					'type'                   => Controls_Manager::ICONS,
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-list',
						'library' => 'alpha-icons',
					),
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
				)
			);

			$this->add_control(
				'item_size',
				array(
					'label'     => esc_html__( 'Item Size(px)', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 50,
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .btn-showtype' => 'font-size: {{SIZE}}px',
					),
				)
			);

			$this->add_control(
				'item_space',
				array(
					'label'       => esc_html__( 'Item Spacing', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 0,
							'max'  => 20,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .btn-showtype + .btn-showtype' => "margin-{$left}: {{SIZE}}px",
					),
					'description' => esc_html__( 'Adjust spacing between each show type buttons.', 'alpha-core' ),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$atts     = array(
			'grid_icon' => isset( $settings['grid_icon']['value'] ) ? $settings['grid_icon']['value'] : '',
			'list_icon' => isset( $settings['list_icon']['value'] ) ? $settings['list_icon']['value'] : '',
		);

		/**
		 * Filters the preview for editor and template.
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_shop_builder_set_preview', false ) ) {
			require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/builders/shop/widgets/show-type/render-show-type-elementor.php' );
		}

		do_action( 'alpha_shop_builder_unset_preview' );
	}

	protected function content_template() {}
}
