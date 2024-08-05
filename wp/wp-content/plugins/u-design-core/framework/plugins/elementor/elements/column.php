<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Column Element
 *
 * Extended Element_Column Class
 * Added Slider, Banner Layer
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;

add_action( 'elementor/frontend/column/before_render', 'alpha_column_render_attributes', 10, 1 );

if ( ! class_exists( 'Alpha_Element_Column' ) ) {
	class Alpha_Element_Column extends Elementor\Element_Column {


		public function __construct( array $data = array(), array $args = null ) {
			parent::__construct( $data, $args );
		}

		public function get_html_tag() {
			$html_tag = $this->get_settings( 'html_tag' );

			if ( empty( $html_tag ) ) {
				$html_tag = 'div';
			}

			return Elementor\Utils::validate_html_tag( $html_tag );
		}

		protected function register_controls() {
			$left  = is_rtl() ? 'right' : 'left';
			$right = 'left' == $left ? 'right' : 'left';
			parent::register_controls();

			$this->start_controls_section(
				'column_additional',
				array(
					'label' => alpha_elementor_panel_heading( esc_html__( 'Settings', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_LAYOUT,
				)
			);

			/**
			 * Filters column element which add on by theme.
			 *
			 * @since 1.0
			 */
			$column_addons = apply_filters( 'alpha_elementor_column_addons', array() );

			if ( ! empty( $column_addons ) ) {
				$column_addons = array_merge( array( '' => esc_html__( 'Default', 'alpha-core' ) ), $column_addons );

				$this->add_control(
					'use_as',
					array(
						'type'    => Controls_Manager::SELECT,
						'label'   => esc_html__( 'Use Column For', 'alpha-core' ),
						'default' => '',
						'options' => $column_addons,
						// 'options' => array(
						// 	''          => esc_html__( 'Default', 'alpha-core' ),
						// 	'banner'    => esc_html__( 'Banner', 'alpha-core' ),
						// ),
					)
				);
			}

			$this->add_control(
				'sticky_column',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Sticky Column', 'alpha-core' ),
					'default'     => 'no',
					'description' => esc_html__( 'Controls column to make sticky or not in a section.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'sticky_column_top',
				array(
					'type'      => Controls_Manager::NUMBER,
					'label'     => esc_html__( 'Top Space on Sticky', 'alpha-core' ),
					'default'   => '0',
					'selectors' => array(
						'{{WRAPPER}}.elementor-column' . ( alpha_elementor_if_dom_optimization() ? ' > .elementor-widget-wrap' : ' > .elementor-column-wrap' ) => 'top: {{SIZE}}px',
					),
					'condition' => array(
						'sticky_column' => 'yes',
					),
				)
			);

			$this->end_controls_section();

			/**
			 * Fires after add controls to column element.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_column_addon_controls', $this, 'use_as' );
			alpha_elementor_addon_controls( $this, 'column' );
		}

		protected function content_template() {
			$is_legacy_mode_active = ! alpha_elementor_if_dom_optimization();
			$wrapper_element       = $is_legacy_mode_active ? 'column' : 'widget';
			?>

			<#
				let wrapper_class = '';
				let wrapper_attrs = '';
				let extra_class = '';
				let extra_attrs = '';

				if ( settings.css_classes ) {
					wrapper_attrs += ' data-css-classes="' + settings.css_classes + '"';
				}

				if('yes' == settings.sticky_column) {
					wrapper_attrs += ' data-sticky-column="true"';
				}
				
				if('yes' == settings.mask_reveal) {
					wrapper_attrs += ' data-mask-reveal="true"';
				}

				let addon_html = '';
				#>

				<?php
				/**
				 * Fires after print column output in elementor column content template function.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_column_addon_content_template', $this );
				?>

				<# if ( addon_html ) { #>
					{{{ addon_html }}}
				<# } else { #>

					<div class="elementor-<?php echo $wrapper_element; ?>-wrap" {{{ wrapper_attrs }}}>
						<div class="elementor-background-overlay"></div>
						<?php if ( $is_legacy_mode_active ) { ?>
							<div class="elementor-widget-wrap"></div>
						<?php } ?>
					</div>

				<# } #>

			<?php
		}

		public function before_render() {
			$settings = $this->get_settings_for_display();

			$overlay_background = $settings['background_overlay_background'] ?? '';
			$overlay_hover_background = $settings['background_overlay_hover_background'] ?? '';

			$has_background_overlay = in_array( $overlay_background, [ 'classic', 'gradient' ], true ) ||
									in_array( $overlay_hover_background, [ 'classic', 'gradient' ], true );

			$is_legacy_mode_active    = ! alpha_elementor_if_dom_optimization();
			$wrapper_attribute_string = $is_legacy_mode_active ? '_inner_wrapper' : '_widget_wrapper';

			$column_wrap_classes = $is_legacy_mode_active ? array( 'elementor-column-wrap' ) : array( 'elementor-widget-wrap' );

			if ( $this->get_children() ) {
				$column_wrap_classes[] = 'elementor-element-populated';
			}

			$this->add_render_attribute(
				array(
					'_wrapper'            => array(
						'class' => ( ( 'yes' == $settings['sticky_column'] ) ? 'alpha-sticky-column' : '' ) . ( ( 'yes' == $settings['mask_reveal'] ) ? 'alpha-entrance-reveal' : '' ),
					),
					'_inner_wrapper'      => array(
						'class' => $column_wrap_classes,
					),
					'_widget_wrapper'     => array(
						'class' => $is_legacy_mode_active ? 'elementor-widget-wrap' : $column_wrap_classes,
					),
					'_background_overlay' => array(
						'class' => array( 'elementor-background-overlay' ),
					),
				)
			);

			ob_start();
			/**
			 * Fires before rendering column html.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_column_render', $this, $settings, $has_background_overlay, $is_legacy_mode_active );
			$addon_html = ob_get_clean();

			if ( ! $addon_html ) {
				?>
				<<?php echo $this->get_html_tag() . ' ' . $this->get_render_attribute_string( '_wrapper' ); ?>>

				<?php
				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $this->get_ID() );
				?>

				<div <?php $this->print_render_attribute_string( '_inner_wrapper' ); ?>>
					<?php if ( $has_background_overlay ) : ?>
						<div <?php $this->print_render_attribute_string( '_background_overlay' ); ?>></div>
					<?php endif; ?>
					<?php if ( $is_legacy_mode_active ) : ?>
						<div <?php $this->print_render_attribute_string( '_widget_wrapper' ); ?>>
					<?php endif; ?>

				<?php
			} else {
				echo alpha_escaped( $addon_html );
			}
		}


		public function after_render() {
			$settings              = $this->get_settings_for_display();
			$is_legacy_mode_active = ! alpha_elementor_if_dom_optimization();

			ob_start();
			/**
			 * Fires after rendering column html.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_column_after_render', $this, $settings, $is_legacy_mode_active );
			$addon_html = ob_get_clean();

			if ( ! $addon_html ) {
				if ( $is_legacy_mode_active ) {
					?>
					</div>
				<?php } ?>
				</div>
				</<?php echo esc_html( $this->get_html_tag() ); ?>>
					<?php
			} else {
				echo $addon_html;
			}
		}

	}
}

if ( ! function_exists( 'alpha_column_render_attributes' ) ) {
	/**
	 * Add render attributes for columns.
	 *
	 * @since 1.0
	 */
	function alpha_column_render_attributes( $self ) {

		global $alpha_section;

		$settings = $self->get_settings_for_display();

		$inner_args   = array();
		$widget_args  = array();
		$wrapper_args = array( 'class' => '' );

		$is_legacy_mode_active = ! alpha_elementor_if_dom_optimization();

		if ( 'slider' == $settings['use_as'] ) { // if using as slider
		} elseif ( 'banner_layer' == $settings['use_as'] ) { // if banner content
			$wrapper_args['class'] .= ' banner-content';
			if ( $settings['banner_origin'] ) {
				$wrapper_args['class'] .= ' ' . $settings['banner_origin'];
			}
		} elseif ( 'tab_content' == $settings['use_as'] ) { // tab content
		} elseif ( 'accordion_content' == $settings['use_as'] ) {
		}

		$options = apply_filters(
			'alpha_elementor_column_addon_render_attributes',
			array(
				'wrapper_args' => $wrapper_args,
				'inner_args'   => $inner_args,
				'widget_args'  => $widget_args,
			),
			$self,
			$settings
		);

		$self->add_render_attribute(
			array(
				'_wrapper'        => alpha_get_elementor_addon_options( $settings, $options['wrapper_args'] ),
				'_inner_wrapper'  => $options['inner_args'],
				'_widget_wrapper' => $options['widget_args'],
			)
		);

		if ( $settings['background_image'] && $settings['background_image']['url'] && function_exists( 'alpha_get_option' ) && alpha_get_option( 'lazyload' ) ) {
			if ( ! is_admin() && ! is_customize_preview() && ! alpha_doing_ajax() ) {
				$data = array(
					'data-lazy' => esc_url( $settings['background_image']['url'] ),
				);
				if ( ! $settings['background_color'] ) {
					$data['style'] = 'background-color: ' . alpha_get_option( 'lazyload_bg' ) . ';';
				}
				$self->add_render_attribute( array( '_inner_wrapper' => $data ) );
			}
		}
	}
}
