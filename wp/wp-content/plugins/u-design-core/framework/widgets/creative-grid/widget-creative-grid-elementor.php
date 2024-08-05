<?php
defined( 'ABSPATH' ) || die;


/**
 * Alpha Creative Grid Widget Addon
 *
 * Alpha Creative Grid Widget Addon using Elementor Section/Column Element
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Alpha_Controls_Manager;

if ( ! class_exists( 'Alpha_Creative_Grid_Elementor_Widget_Addon' ) ) {
	class Alpha_Creative_Grid_Elementor_Widget_Addon  extends Alpha_Base {
		/**
		 * Constructor
		 *
		 * @since 1.2.0
		 */
		public function __construct() {

			add_filter( 'alpha_elementor_section_addons', array( $this, 'register_section_addon' ) );
			add_action( 'alpha_elementor_section_addon_controls', array( $this, 'add_section_controls' ), 10, 2 );
			add_action( 'alpha_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ) );
			add_filter( 'alpha_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 10, 3 );
			add_action( 'alpha_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );
			add_action( 'alpha_elementor_section_after_render', array( $this, 'section_addon_after_render' ), 10, 2 );

			add_action( 'alpha_elementor_column_addon_controls', array( $this, 'add_column_controls' ), 10, 2 );
			add_action( 'alpha_elementor_column_addon_content_template', array( $this, 'column_addon_content_template' ), 1 );
			add_filter( 'alpha_elementor_column_addon_render_attributes', array( $this, 'column_addon_attributes' ), 10, 3 );
		}

		/**
		 * Register creative grid addon to section element
		 *
		 * @since 1.2.0
		 */
		public function register_section_addon( $addons ) {
			$addons['creative'] = esc_html__( 'Creative', 'alpha-core' );
			return $addons;
		}

		/**
		 * Add creative grid controls to section element
		 *
		 * @since 1.2.0
		 */
		public function add_section_controls( $self, $condition_value ) {
			$self->add_control(
				'section_creative_description',
				array(
					'raw'             => sprintf( esc_html__( 'Use %1$schild columns%2$s as %1$sgrid item%2$s by using %1$s%3$s settings%2$s.', 'alpha-core' ), '<b>', '</b>', ALPHA_DISPLAY_NAME ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'alpha-notice notice-warning',
					'condition'       => array(
						$condition_value => 'creative',
					),
				),
				array(
					'position' => array(
						'at' => 'after',
						'of' => $condition_value,
					),
				)
			);

			$self->start_controls_section(
				'section_creative_grid',
				array(
					'label'     => alpha_elementor_panel_heading( esc_html__( 'Creative Grid', 'alpha-core' ) ),
					'tab'       => Controls_Manager::TAB_LAYOUT,
					'condition' => array(
						$condition_value => 'creative',
					),
				)
			);

			alpha_elementor_creative_layout_controls( $self, $condition_value, 'section' );

			$self->end_controls_section();
		}

		/**
		 * Print creative grid in elementor section content template function
		 *
		 * @since 1.2.0
		 */
		public function section_addon_content_template( $self ) {
			?>
		<#
		if ( 'creative' == settings.use_as ) {
			let height = settings.creative_height.size;
			let mode = settings.creative_mode;
			let height_ratio = settings.creative_height_ratio.size;
			extra_class = '';
			extra_attrs = '';

			if ( '' == height ) {
				height = 600;
			}
			if ( '' == mode ) {
				mode = 0;
			}
			if ( ! Number(height_ratio) ) {
				height_ratio = 75;
			}

			extra_class = ' grid gutter-' + settings.creative_col_sp;

			if ( settings.grid_float ) {
				extra_class += ' grid-float';
			} else {
				extra_class += ' creative-grid' + ' grid-mode-' + mode;
				let creative_breaks = {
					'sm': typeof elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile.value != 'undefined' ? elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile.value + 1 : '576',
					'md': typeof elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile_extra != 'undefined' && elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile_extra.value ? elementor.breakpoints.responsiveConfig.activeBreakpoints.mobile_extra.value + 1 : '768',
					'lg': elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet.value ? elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet.value + 1 : '992',
					'xl': typeof elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet_extra != 'undefined' && elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet_extra.value ? elementor.breakpoints.responsiveConfig.activeBreakpoints.tablet_extra.value + 1 : '1199',
					'xlg': typeof elementor.breakpoints.responsiveConfig.activeBreakpoints.laptop != 'undefined' && elementor.breakpoints.responsiveConfig.activeBreakpoints.laptop.value ? elementor.breakpoints.responsiveConfig.activeBreakpoints.laptop.value + 1 : '1399',
					'xxl': typeof elementor.breakpoints.responsiveConfig.activeBreakpoints.widescreen != 'undefined' && elementor.breakpoints.responsiveConfig.activeBreakpoints.widescreen.value ? elementor.breakpoints.responsiveConfig.activeBreakpoints.widescreen.value + 1 : '2399',
				};
				extra_attrs += ' data-creative-breaks=' + JSON.stringify( creative_breaks );
			}

			extra_attrs += ' data-creative-mode=' + mode;
			extra_attrs += ' data-creative-height=' + height;
			extra_attrs += ' data-creative-height-ratio=' + height_ratio;

			/** Start .elementor-container */ 
			addon_html += '<div class="elementor-container' + content_width + ' elementor-column-gap-no">';
			/** Start .elementor-row */ 
			addon_html += '<div class="elementor-row ' + extra_class + '" ' + extra_attrs + '></div>';
			/** End .elementor-row */ 
			addon_html += '</div>';
		}
		#>
			<?php
		}

		/**
		 * Add render attributes for creative grid
		 *
		 * @since 1.2.0
		 */
		public function section_addon_attributes( $options, $self, $settings ) {
			if ( 'creative' == $settings['use_as'] ) {
				global $alpha_section;

				$alpha_section = array(
					'section' => 'creative',
					'preset'  => alpha_creative_layout( $settings['creative_mode'] ),
					'layout'  => array(), // layout of children
					'index'   => 0, // index of children
					'top'     => $self->get_data( 'isInner' ), // check if the column is direct child of this section
				);
			}

			return $options;
		}

		/**
		 * Render creative grid HTML
		 *
		 * @since 1.2.0
		 */
		public function section_addon_render( $self, $settings ) {
			if ( 'creative' == $settings['use_as'] ) {
				global $alpha_section;
				$extra_class = ' grid gutter-' . $settings['creative_col_sp'];
				$extra_attrs = '';

				if ( 'yes' == $settings['grid_float'] ) {
					$extra_class .= ' grid-float';
				} else {
					wp_enqueue_script( 'isotope-pkgd' );
					$extra_class .= ' creative-grid' . ' grid-mode-' . $settings['creative_mode'];
					$extra_attrs .= " data-creative-breaks='" . json_encode(
						array(
							'sm'  => alpha_get_breakpoints( 'sm' ),
							'md'  => alpha_get_breakpoints( 'md' ),
							'lg'  => alpha_get_breakpoints( 'lg' ),
							'xl'  => alpha_get_breakpoints( 'xl' ),
							'xlg' => alpha_get_breakpoints( 'xlg' ),
							'xxl' => alpha_get_breakpoints( 'xxl' ),
						)
					) . "'";
				}
				alpha_creative_layout_style(
					'.elementor-element-' . $self->get_data( 'id' ),
					$alpha_section['layout'],
					$settings['creative_height']['size'] ? $settings['creative_height']['size'] : 600,
					$settings['creative_height_ratio']['size'] ? $settings['creative_height_ratio']['size'] : 75
				);

				/**
				 * Fires after rendering effect addons such as duplex and ribbon.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );

				?>
				<!-- Start .elementor-container -->
				<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no">
					<!-- Start .elementor-row -->
					<div class="elementor-row<?php echo esc_attr( $extra_class ); ?>"<?php echo alpha_escaped( $extra_attrs ); ?>>
				<?php

			}
		}

		/**
		 * Render creative grid HTML after elementor section render
		 *
		 * @since 1.2.0
		 */
		public function section_addon_after_render( $self, $settings ) {
			if ( 'creative' == $settings['use_as'] ) :
				unset( $GLOBALS['alpha_section'] );
				echo '<div class="grid-space"></div>';
					echo '</div>'; /** End .elementor-row */
				echo '</div>'; /* End .elementor-container */
				?>
				</<?php echo esc_html( $self->get_html_tag() ); ?>>
				<?php
			endif;
		}

		/**
		 * Add creative grid item controls to column element
		 *
		 * @since 1.2.0
		 */
		public function add_column_controls( $self, $condition_value ) {
			$self->add_responsive_control(
				'creative_width',
				array(
					'label'       => esc_html__( 'Grid Item Width (%)', 'alpha-core' ),
					'type'        => Controls_Manager::NUMBER,
					'description' => esc_html__( 'This Option will be applied when only parent section is used for creative grid. Empty Value will be set from preset of parent creative grid.', 'alpha-core' ),
					'min'         => 1,
					'max'         => 100,
				),
				array(
					'position' => array(
						'at' => 'before',
						'of' => $condition_value,
					),
				)
			);

			$self->add_responsive_control(
				'creative_height',
				array(
					'label'       => esc_html__( 'Grid Item Height', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'preset',
					'options'     => array(
						'1'      => '1',
						'1-2'    => '1/2',
						'1-3'    => '1/3',
						'2-3'    => '2/3',
						'1-4'    => '1/4',
						'3-4'    => '3/4',
						'child'  => esc_html__( 'Depending on Children', 'alpha-core' ),
						'preset' => esc_html__( 'Use From Preset', 'alpha-core' ),
					),
					'label_block' => 'true',
					'description' => esc_html__( 'This Option will be applied when only parent section is used for creative grid.', 'alpha-core' ),
				),
				array(
					'position' => array(
						'at' => 'before',
						'of' => $condition_value,
					),
				)
			);
			$self->add_responsive_control(
				'creative_order',
				array(
					'label'       => esc_html__( 'Grid Item Order', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'separator'   => 'after',
					'default'     => '1',
					'options'     => array(
						'1'  => esc_html__( '1', 'alpha-core' ),
						'2'  => '2',
						'3'  => '3',
						'4'  => '4',
						'5'  => '5',
						'6'  => '6',
						'7'  => '7',
						'8'  => '8',
						'9'  => '9',
						'10' => '10',
					),
					'description' => esc_html__( 'This Option will be applied when only parent section is used for creative grid not float grid.', 'alpha-core' ),
				),
				array(
					'position' => array(
						'at' => 'before',
						'of' => $condition_value,
					),
				)
			);
		}

		/**
		 * Print creative grid item in elementor column content template function
		 *
		 * @since 1.2.0
		 */
		public function column_addon_content_template( $self ) {
			?>
			<#
			let grid_item = {};

			if ( settings.creative_width ) {
				grid_item['w'] = settings.creative_width;
			}
			if ( settings.creative_width_widescreen ) {
				grid_item['w-w'] = settings.creative_width_widescreen;
			}
			if ( settings.creative_width_laptop ) {
				grid_item['w-g'] = settings.creative_width_laptop;
			}
			if ( settings.creative_width_tablet_extra ) {
				grid_item['w-x'] = settings.creative_width_tablet_extra;
			}
			if ( settings.creative_width_tablet ) {
				grid_item['w-l'] = settings.creative_width_tablet;
			}
			if ( settings.creative_width_mobile_extra ) {
				grid_item['w-m'] = settings.creative_width_mobile_extra;
			}
			if ( settings.creative_width_mobile ) {
				grid_item['w-s'] = settings.creative_width_mobile;
			}
			if ( 'child' != settings.creative_height ) {
				grid_item['h'] = settings.creative_height;
			}
			if ( settings.creative_height_widescreen && 'preset' != settings.creative_height_widescreen && 'child' != settings.creative_height_widescreen ) {
				grid_item['h-w'] = settings.creative_height_widescreen;
			}
			if ( settings.creative_height_laptop && 'preset' != settings.creative_height_laptop && 'child' != settings.creative_height_laptop ) {
				grid_item['h-g'] = settings.creative_height_laptop;
			}
			if ( settings.creative_height_tablet_extra && 'preset' != settings.creative_height_tablet_extra && 'child' != settings.creative_height_tablet_extra ) {
				grid_item['h-x'] = settings.creative_height_tablet_extra;
			}
			if ( settings.creative_height_tablet && 'preset' != settings.creative_height_tablet && 'child' != settings.creative_height_tablet ) {
				grid_item['h-l'] = settings.creative_height_tablet;
			}
			if ( settings.creative_height_mobile_extra && 'preset' != settings.creative_height_mobile_extra && 'child' != settings.creative_height_mobile_extra ) {
				grid_item['h-m'] = settings.creative_height_mobile_extra;
			}
			if ( settings.creative_height_mobile && 'preset' != settings.creative_height_mobile && 'child' != settings.creative_height_mobile ) {
				grid_item['h-s'] = settings.creative_height_mobile;
			}

			if ( settings.creative_order ) {
				wrapper_attrs += ' data-creative-order="' + settings.creative_order + '"';
			}
			if ( settings.creative_order_widescreen ) {
				wrapper_attrs += ' data-creative-order-xxl="' + settings.creative_order_widescreen + '"';
			}
			if ( settings.creative_order_laptop ) {
				wrapper_attrs += ' data-creative-order-xlg="' + settings.creative_order_laptop + '"';
			}
			if ( settings.creative_order_tablet_extra ) {
				wrapper_attrs += ' data-creative-order-xl="' + settings.creative_order_tablet_extra + '"';
			}
			if ( settings.creative_order_tablet ) {
				wrapper_attrs += ' data-creative-order-lg="' + settings.creative_order_tablet + '"';
			}
			if ( settings.creative_order_mobile_extra ) {
				wrapper_attrs += ' data-creative-order-md="' + settings.creative_order_mobile_extra + '"';
			}
			if ( settings.creative_order_mobile ) {
				wrapper_attrs += ' data-creative-order-sm="' + settings.creative_order_mobile + '"';
			}
			wrapper_attrs += 'data-creative-item=' + JSON.stringify(grid_item);

			#>
			<?php
		}

		/**
		 * Add render attributes for creative grid item
		 *
		 * @since 1.2.0
		 */
		public function column_addon_attributes( $options, $self, $settings ) {
			global $alpha_section;
			if ( isset( $alpha_section['section'] ) && 'creative' == $alpha_section['section'] && $alpha_section['top'] == $self->get_data( 'isInner' ) ) {
				$idx       = $alpha_section['index'];
				$classes[] = 'grid-item';
				$grid      = array();
				if ( $idx < count( $alpha_section['preset'] ) ) {
					foreach ( $alpha_section['preset'][ $idx ] as $key => $value ) {
						if ( 'h' == $key ) {
							continue;
						}

						$grid[ $key ] = $value;
					}
				} else {
					$grid['w']   = '1-4';
					$grid['w-l'] = '1-2';
				}

				if ( isset( $settings['creative_width_widescreen'] ) && $settings['creative_width_widescreen'] ) {
					$grid['w-w'] = $grid['w'] = $grid['w-g'] = $grid['w-x'] = $grid['w-l'] = $grid['w-m'] = $grid['w-s'] = $settings['creative_width_widescreen'];
				}
				if ( isset( $settings['creative_width'] ) && $settings['creative_width'] ) {
					$grid['w'] = $grid['w-g'] = $grid['w-x'] = $grid['w-l'] = $grid['w-m'] = $grid['w-s'] = $settings['creative_width'];
				}
				if ( isset( $settings['creative_width_laptop'] ) && $settings['creative_width_laptop'] ) {
					$grid['w-g'] = $grid['w-x'] = $grid['w-l'] = $grid['w-m'] = $grid['w-s'] = $settings['creative_width_laptop'];
				}
				if ( isset( $settings['creative_width_tablet_extra'] ) && $settings['creative_width_tablet_extra'] ) {
					$grid['w-x'] = $grid['w-l'] = $grid['w-m'] = $grid['w-s'] = $settings['creative_width_tablet_extra'];
				}
				if ( isset( $settings['creative_width_tablet'] ) && $settings['creative_width_tablet'] ) {
					$grid['w-l'] = $grid['w-m'] = $grid['w-s'] = $settings['creative_width_tablet'];
				}
				if ( isset( $settings['creative_width_mobile_extra'] ) && $settings['creative_width_mobile_extra'] ) {
					$grid['w-m'] = $grid['w-s'] = $settings['creative_width_mobile_extra'];
				}
				if ( isset( $settings['creative_width_mobile'] ) && $settings['creative_width_mobile'] ) {
					$grid['w-s'] = $settings['creative_width_mobile'];
				}

				if ( isset( $settings['creative_height'] ) && 'preset' == $settings['creative_height'] ) {
					$grid['h'] = $idx < count( $alpha_section['preset'] ) ? $alpha_section['preset'][ $idx ]['h'] : '1-3';
				} elseif ( isset( $settings['creative_height'] ) && 'child' != $settings['creative_height'] ) {
					$grid['h'] = $settings['creative_height'];
				}
				if ( isset( $settings['creative_height_widescreen'] ) && $settings['creative_height_widescreen'] && 'preset' != $settings['creative_height_widescreen'] && 'child' != $settings['creative_height_widescreen'] ) {
					$grid['h-w'] = $settings['creative_height_widescreen'];
				}
				if ( isset( $settings['creative_height_laptop'] ) && $settings['creative_height_laptop'] && 'preset' != $settings['creative_height_laptop'] && 'child' != $settings['creative_height_laptop'] ) {
					$grid['h-g'] = $settings['creative_height_laptop'];
				}
				if ( isset( $settings['creative_height_tablet_extra'] ) && $settings['creative_height_tablet_extra'] && 'preset' != $settings['creative_height_tablet_extra'] && 'child' != $settings['creative_height_tablet_extra'] ) {
					$grid['h-x'] = $settings['creative_height_tablet_extra'];
				}
				if ( isset( $settings['creative_height_tablet'] ) && $settings['creative_height_tablet'] && 'preset' != $settings['creative_height_tablet'] && 'child' != $settings['creative_height_tablet'] ) {
					$grid['h-l'] = $settings['creative_height_tablet'];
				}
				if ( isset( $settings['creative_height_mobile_extra'] ) && $settings['creative_height_mobile_extra'] && 'preset' != $settings['creative_height_mobile_extra'] && 'child' != $settings['creative_height_mobile_extra'] ) {
					$grid['h-m'] = $settings['creative_height_mobile_extra'];
				}
				if ( isset( $settings['creative_height_mobile'] ) && $settings['creative_height_mobile'] && 'preset' != $settings['creative_height_mobile'] && 'child' != $settings['creative_height_mobile'] ) {
					$grid['h-s'] = $settings['creative_height_mobile'];
				}

				if ( isset( $settings['creative_order'] ) && $settings['creative_order'] ) {
					$options['wrapper_args']['data-creative-order'] = $settings['creative_order'];
				} else {
					$options['wrapper_args']['data-creative-order'] = 1;
				}
				if ( isset( $settings['creative_order_widescreen'] ) && $settings['creative_order_widescreen'] ) {
					$options['wrapper_args']['data-creative-order-xxl'] = $settings['creative_order_widescreen'];
				} else {
					$options['wrapper_args']['data-creative-order-xxl'] = 1;
				}
				if ( isset( $settings['creative_order_laptop'] ) && $settings['creative_order_laptop'] ) {
					$options['wrapper_args']['data-creative-order-xlg'] = $settings['creative_order_laptop'];
				} else {
					$options['wrapper_args']['data-creative-order-xlg'] = 1;
				}
				if ( isset( $settings['creative_order_tablet_extra'] ) && $settings['creative_order_tablet_extra'] ) {
					$options['wrapper_args']['data-creative-order-xl'] = $settings['creative_order_tablet_extra'];
				} else {
					$options['wrapper_args']['data-creative-order-xl'] = 1;
				}
				if ( isset( $settings['creative_order_tablet'] ) && $settings['creative_order_tablet'] ) {
					$options['wrapper_args']['data-creative-order-lg'] = $settings['creative_order_tablet'];
				} else {
					$options['wrapper_args']['data-creative-order-lg'] = 1;
				}
				if ( isset( $settings['creative_order_mobile_extra'] ) && $settings['creative_order_mobile_extra'] ) {
					$options['wrapper_args']['data-creative-order-md'] = $settings['creative_order_mobile_extra'];
				} else {
					$options['wrapper_args']['data-creative-order-md'] = 1;
				}
				if ( isset( $settings['creative_order_mobile'] ) && $settings['creative_order_mobile'] ) {
					$options['wrapper_args']['data-creative-order-sm'] = $settings['creative_order_mobile'];
				} else {
					$options['wrapper_args']['data-creative-order-sm'] = 1;
				}

				foreach ( $grid as $key => $value ) {
					if ( false !== strpos( $key, 'w' ) && is_numeric( $value ) && 1 != $value ) {
						if ( 0 == 100 % $value ) {
							if ( 100 == $value ) {
								$grid[ $key ] = '1';
							} else {
								$grid[ $key ] = '1-' . ( 100 / $value );
							}
						} else {
							for ( $i = 1; $i <= 100; ++$i ) {
								$val       = $value * $i;
								$val_round = round( $val );
								if ( abs( round( $val - $val_round, 2, PHP_ROUND_HALF_UP ) ) <= 0.01 ) {
									$g            = alpha_get_gcd( 100, $val_round );
									$grid[ $key ] = ( $val_round / $g ) . '-' . ( $i * 100 / $g );
									break;
								}
							}
						}
					}
				}

				$alpha_section['layout'][ $idx ] = $grid;
				foreach ( $grid as $key => $value ) {
					if ( $value ) {
						$classes[] = $key . '-' . $value;
					}
				}
				$alpha_section['index'] = ++$idx;

				$options['wrapper_args']['class'] .= implode( ' ', $classes );
			}

			return $options;
		}
	}
}

/**
 * Create instance
 *
 * @since 1.2.0
 */
Alpha_Creative_Grid_Elementor_Widget_Addon::get_instance();
