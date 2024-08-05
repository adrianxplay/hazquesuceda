<?php
/**
 * Alpha Elementor Parallax Addon
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @version    4.2
 */

defined( 'ABSPATH' ) || exit;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Alpha_Controls_Manager;


if ( ! class_exists( 'Alpha_Parallax_Elementor' ) ) {
	/**
	 * Alpha Elementor Parallax Addon
	 *
	 * @since 4.2
	 */
	class Alpha_Parallax_Elementor extends Alpha_Base {

		/**
		 * The Constructor.
		 *
		 * @since 4.2
		 */
		public function __construct() {
			// Enqueue component css
			add_action( 'alpha_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );

			// Add controls to section background style tab
			add_action( 'elementor/element/section/section_background/before_section_end', array( $this, 'add_section_controls' ), 30, 2 );

			// Add controls to flexbox container background style tab
			add_action( 'elementor/element/container/section_background/before_section_end', array( $this, 'add_section_controls' ), 30, 2 );

			// Add renderer in elementor preview
			add_filter( 'alpha_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ), 30, 2 );

			// Add renderer in elementor preview for flexbox container
			add_filter( 'alpha_elementor_container_addon_content_template', array( $this, 'container_addon_content_template' ), 30, 2 );

			// Addon renderer
			add_filter( 'alpha_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 30, 3 );

			// Addon renderer
			add_filter( 'alpha_elementor_container_addon_render_attributes', array( $this, 'section_addon_attributes' ), 30, 3 );
		}

		/**
		 * Enqueue component css
		 *
		 * @since 4.2
		 */
		public function enqueue_scripts() {
			if ( alpha_is_elementor_preview() ) {
				wp_enqueue_script( 'jquery-skrollr' );
			}
		}

		/**
		 * Add controls to section background style tab.
		 *
		 * @since 4.2
		 */
		public function add_section_controls( $self, $source = '' ) {
			$self->add_control(
				'alpha_section_parallax',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => alpha_elementor_panel_heading( esc_html__( 'Enable Parallax', 'alpha-core' ) ),
					'description' => esc_html__( 'Set to enable parallax effect for banner.', 'alpha-core' ),
					'condition'   => array(
						'background_background'  => array( 'classic' ),
						'background_image[url]!' => '',
					),
				)
			);

			$self->add_control(
				'alpha_section_parallax_direction',
				array(
					'label'       => esc_html__( 'Direction', 'alpha-core' ),
					'description' => esc_html__( 'Choose moving direction of background when scroll down.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						'up'    => array(
							'title' => esc_html__( 'Up', 'alpha-core' ),
							'icon'  => 'eicon-arrow-up',
						),
						'down'  => array(
							'title' => esc_html__( 'Down', 'alpha-core' ),
							'icon'  => 'eicon-arrow-down',
						),
						'left'  => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-arrow-left',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-arrow-right',
						),
					),
					'condition'   => array(
						'background_background'  => array( 'classic' ),
						'background_image[url]!' => '',
						'alpha_section_parallax' => 'yes',
					),
					'default'     => 'down',
					'toggle'      => false,
				)
			);

			$self->add_control(
				'alpha_section_parallax_speed',
				array(
					'type'        => Controls_Manager::SLIDER,
					'label'       => esc_html__( 'Parallax Speed', 'alpha-core' ),
					'description' => esc_html__( 'Change speed of banner parallax effect.', 'alpha-core' ),
					'default'     => array(
						'size' => 3,
						'unit' => 'px',
					),
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
					),
					'condition'   => array(
						'background_background'  => array( 'classic' ),
						'background_image[url]!' => '',
						'alpha_section_parallax' => 'yes',
					),
				)
			);
		}
		/**
		 * Add renderer in elementor preview.
		 *
		 * @since 4.2
		 */

		public function section_addon_content_template( $self ) {
			?>
			<#
			if ( settings.alpha_section_parallax ) {
				let parallax_options = {
					'direction'      : settings.alpha_section_parallax_direction,
					'speed'          : settings.alpha_section_parallax_speed.size && 10 != settings.alpha_section_parallax_speed.size ? 10 / ( 10 - settings.alpha_section_parallax_speed.size ) : 1.5,
				};

				extra_attrs += " data-parallax-image='" + settings.background_image.url + "' data-parallax-options='" + JSON.stringify(parallax_options) + "'";

				<?php if ( $self->legacy_mode ) { ?>
					addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-' + settings.gap + '">';
				<?php } else { ?>
					addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-' + settings.gap + ' ' + extra_class + '" ' + extra_attrs + '>';
				<?php } ?>

					<?php if ( $self->legacy_mode ) { ?>
						addon_html += '<!-- Begin .elementor-row --><div class="elementor-row' + extra_class + '" ' + extra_attrs + '>';
					<?php } ?>

					<?php if ( $self->legacy_mode ) { ?>
						addon_html += '</div>';
					<?php } ?>

				addon_html += '</div>';
			}
			#>
			<?php
		}

		/**
		 * Add renderer in elementor preview for flexbox container.
		 *
		 * @since 5.0
		 */

		public function container_addon_content_template( $self ) {
			?>
			<#
			if ( settings.alpha_section_parallax ) {
				var parallax_options = {
					'direction'      : settings.alpha_section_parallax_direction,
					'speed'          : settings.alpha_section_parallax_speed.size && 10 != settings.alpha_section_parallax_speed.size ? 10 / ( 10 - settings.alpha_section_parallax_speed.size ) : 1.5,
				};
				view.addRenderAttribute( 'con-data', 'data-parallax-image', settings.background_image.url );
				view.addRenderAttribute( 'con-data', 'data-parallax-options', JSON.stringify(parallax_options) );
			}
			#>
			<?php
		}

		/**
		 * Section and Flexbox Container Addon renderer.
		 *
		 * @since 4.2
		 */
		public function section_addon_attributes( $options, $self, $settings ) {
			if ( $settings['alpha_section_parallax'] ) {
				wp_enqueue_script( 'jquery-skrollr' );

				$options['class']                 = 'parallax' . ( 'left' == $settings['alpha_section_parallax_direction'] || 'right' == $settings['alpha_section_parallax_direction'] ? ' parallax-horizontal' : ' parallax-vertical' );
				$options['data-parallax-image']   = esc_url( $settings['background_image']['url'] );
				$parallax_options                 = array(
					'direction' => $settings['alpha_section_parallax_direction'],
					'speed'     => $settings['alpha_section_parallax_speed']['size'] && 10 != $settings['alpha_section_parallax_speed']['size'] ? 10 / ( 10 - $settings['alpha_section_parallax_speed']['size'] ) : 1.5,
				);
				$options['data-parallax-options'] = json_encode( $parallax_options );
			}
			return $options;
		}

	}
	Alpha_Parallax_Elementor::get_instance();
}
