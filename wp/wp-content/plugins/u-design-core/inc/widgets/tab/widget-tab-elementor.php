<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Tab Widget Addon
 *
 * Alpha Tab Widget Addon using Elementor Section/Column Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Alpha_Controls_Manager;

class Alpha_Tab_Elementor_Widget_Addon extends Alpha_Base {
	/**
	 * Constructor
	 *
	 * @since 4.1
	 */
	public function __construct() {
		add_action( 'alpha_before_enqueue_custom_css', array( $this, 'enqueue_scripts' ) );

		add_filter( 'alpha_elementor_section_addons', array( $this, 'register_section_addon' ) );
		add_action( 'alpha_elementor_section_addon_controls', array( $this, 'add_section_controls' ), 10, 2 );
		add_action( 'alpha_elementor_section_addon_content_template', array( $this, 'section_addon_content_template' ) );
		add_filter( 'alpha_elementor_section_addon_render_attributes', array( $this, 'section_addon_attributes' ), 10, 3 );
		add_action( 'alpha_elementor_section_render', array( $this, 'section_addon_render' ), 10, 2 );
		add_action( 'alpha_elementor_section_after_render', array( $this, 'section_addon_after_render' ), 10, 2 );

		add_filter( 'alpha_elementor_column_addons', array( $this, 'register_column_addon' ) );
		add_action( 'alpha_elementor_column_addon_controls', array( $this, 'add_column_controls' ), 10, 2 );
		add_action( 'alpha_elementor_column_addon_content_template', array( $this, 'column_addon_content_template' ) );
		add_filter( 'alpha_elementor_column_addon_render_attributes', array( $this, 'column_addon_attributes' ), 10, 3 );
	}


	/**
	 * Enqueue component css
	 *
	 * @since 4.1
	 */
	public function enqueue_scripts() {
		if ( alpha_is_elementor_preview() ) {
			wp_enqueue_style( 'alpha-tab', ALPHA_CORE_INC_URI . '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		}
	}

	/**
	 * Register tab addon to section element
	 *
	 * @since 4.1
	 */
	public function register_section_addon( $addons ) {
		$addons['tab'] = esc_html__( 'Tab', 'alpha-core' );

		return $addons;
	}

	/**
	 * Add tab controls to section element
	 *
	 * @since 4.1
	 */
	public function add_section_controls( $self, $condition_value ) {
		$self->add_control(
			'section_tab_description',
			array(
				'raw'             => sprintf( esc_html__( 'Use %1$schild columns%2$s as %1$stab content%2$s by using %1$s%3$s settings%2$s.', 'alpha-core' ), '<b>', '</b>', ALPHA_DISPLAY_NAME, ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'alpha-notice notice-warning',
				'condition'       => array(
					$condition_value => 'tab',
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
			'section_tab',
			array(
				'label'     => alpha_elementor_panel_heading( esc_html__( 'Tab', 'alpha-core' ) ),
				'tab'       => Controls_Manager::TAB_LAYOUT,
				'condition' => array(
					$condition_value => 'tab',
				),
			)
		);

			alpha_elementor_tab_layout_controls( $self, $condition_value );

		$self->end_controls_section();

		alpha_elementor_tab_style_controls( $self, $condition_value );
	}

	/**
	 * Print tab content in elementor section content template function
	 *
	 * @since 4.1
	 */
	public function section_addon_content_template( $self ) {
		?>
		<#
		if ( 'tab' == settings.use_as ) {
			extra_class = 'tab';

			if ( 'vertical' == settings.tab_type ) {
				extra_class += ' tab-vertical';

				switch ( settings.tab_v_type ) { // vertical tab type
					case '':
						extra_class += ' tab-nav-default';
						break;
					case 'solid':
						extra_class += ' tab-nav-default tab-nav-solid';
						break;
					case 'underline':
						extra_class += ' tab-nav-underline';
						break;
				} // in
			} else {
				switch ( settings.tab_h_type ) { // horizontal tab type
					case '':
						extra_class += ' tab-nav-default';
						break;
					case 'solid':
						extra_class += ' tab-nav-default tab-nav-solid';
						break;
					case 'simple':
						extra_class += ' tab-nav-simple';
						break;
					case 'underline1':
						extra_class += ' tab-nav-underline';
						break;
					case 'underline2':
						extra_class += ' tab-nav-underline underline-lg';
						break;
					case 'underline3':
						extra_class += ' tab-nav-underline underline-sm';
						break;
				}

				if ( 'yes' == settings.show_separator ) {
					extra_class += ' tab-nav-separated';
				}

				switch ( settings.tab_navs_pos ) { // nav position
					case 'left':
						extra_class += ' tab-nav-left';
						break;
					case 'center': 
						extra_class += ' tab-nav-center';
						break;
					case 'right':
						extra_class += ' tab-nav-right';
				}
				switch ( settings.tab_navs_pos_mobile ) { // nav position
					case 'left':
						extra_class += ' tab-nav-sm-left';
						break;
					case 'center':
						extra_class += ' tab-nav-sm-center';
						break;
					case 'right':
						extra_class += ' tab-nav-sm-right';
				}
				switch ( settings.tab_navs_pos_tablet ) { // nav position
					case 'left':
						extra_class += ' tab-nav-md-left';
						break;
					case 'center':
						extra_class += ' tab-nav-md-center';
						break;
					case 'right':
						extra_class += ' tab-nav-md-right';
				}
			}
			
			addon_html += '<!-- Begin .elementor-container --><div class="elementor-container' + content_width + ' elementor-column-gap-no ' + extra_class + '">';
			addon_html += '<ul class="nav nav-tabs" role="tablist"></ul>';
			#>

			<?php if ( $self->legacy_mode ) { ?>
				<#
				addon_html += '<!-- Begin .elementor-row --><div class="elementor-row tab-content"></div><!-- End .elementor-row -->';
				#>
			<?php } else { ?>
				<#
					addon_html += '<div class="tab-content"></div>';
				#>
			<?php } ?>

			<#
			addon_html += '</div><!-- End .elementor-container -->';
		}
		#>
		<?php
	}

	/**
	 * Add render attributes for tab
	 *
	 * @since 4.1
	 */
	public function section_addon_attributes( $options, $self, $settings ) {
		if ( 'tab' == $settings['use_as'] ) {
			global $alpha_section;

			$alpha_section = array(
				'section'  => 'tab',
				'index'    => 0,
				'tab_data' => array(),
			);
		}

		return $options;
	}

	/**
	 * Render tab HTML
	 *
	 * @since 4.1
	 */
	public function section_addon_render( $self, $settings ) {
		if ( 'tab' == $settings['use_as'] ) {
			wp_enqueue_style( 'alpha-tab', alpha_core_framework_uri( '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );

			global $alpha_section;
			$extra_class = ' tab';

			if ( 'vertical' == $settings['tab_type'] ) {
				$extra_class .= ' tab-vertical';

				switch ( $settings['tab_v_type'] ) { // vertical tab type
					case '':
						$extra_class .= ' tab-nav-default';
						break;
					case 'solid':
						$extra_class .= ' tab-nav-default tab-nav-solid';
						break;
					case 'underline':
						$extra_class .= ' tab-nav-underline';
						break;
				}
			} else {
				switch ( $settings['tab_h_type'] ) { // horizontal tab type
					case '':
						$extra_class .= ' tab-nav-default';
						break;
					case 'simple':
						$extra_class .= ' tab-nav-simple';
						break;
					case 'solid':
						$extra_class .= ' tab-nav-default tab-nav-solid';
						break;
					case 'underline1':
						$extra_class .= ' tab-nav-underline';
						break;
					case 'underline2':
						$extra_class .= ' tab-nav-underline underline-lg';
						break;
					case 'underline3':
						$extra_class .= ' tab-nav-underline underline-sm';
						break;
				}
				if ( 'yes' == $settings['show_separator'] ) {
					$extra_class .= ' tab-nav-separated';
				}
				if ( isset( $settings['tab_navs_pos'] ) ) {
					if ( 'left' == $settings['tab_navs_pos'] ) {
						$extra_class .= ' tab-nav-left';
					} elseif ( 'center' == $settings['tab_navs_pos'] ) {
						$extra_class .= ' tab-nav-center';
					} elseif ( 'right' == $settings['tab_navs_pos'] ) {
						$extra_class .= ' tab-nav-right';
					}
				}
				if ( isset( $settings['tab_navs_pos_tablet'] ) ) {
					if ( 'left' == $settings['tab_navs_pos_tablet'] ) {
						$extra_class .= ' tab-nav-md-left';
					} elseif ( 'center' == $settings['tab_navs_pos_tablet'] ) {
						$extra_class .= ' tab-nav-md-center';
					} elseif ( 'right' == $settings['tab_navs_pos_tablet'] ) {
						$extra_class .= ' tab-nav-md-right';
					}
				}
				if ( isset( $settings['tab_navs_pos_mobile'] ) ) {
					if ( 'left' == $settings['tab_navs_pos_mobile'] ) {
						$extra_class .= ' tab-nav-sm-left';
					} elseif ( 'center' == $settings['tab_navs_pos_mobile'] ) {
						$extra_class .= ' tab-nav-sm-center';
					} elseif ( 'right' == $settings['tab_navs_pos_mobile'] ) {
						$extra_class .= ' tab-nav-sm-right';
					}
				}
			}

			/**
			 * Fires after rendering effect addons such as duplex and ribbon.
			 *
			 * @since 1.0
			 */
			do_action( 'alpha_elementor_addon_render', $settings, $self->get_ID() );
			?>

			<div class="<?php echo esc_attr( 'yes' == $settings['section_content_type'] ? 'elementor-container container-fluid' : 'elementor-container' ); ?> elementor-column-gap-no<?php echo esc_attr( $extra_class ); ?>">
				<ul class="nav nav-tabs">
				<?php foreach ( $alpha_section['tab_data'] as $idx => $data ) : ?>
					<?php
					$html      = '';
					$icon_html = '';
					if ( isset( $data['icon']['library'] ) && 'svg' === $data['icon']['library'] ) {
						ob_start();
						\ELEMENTOR\Icons_Manager::render_icon(
							array(
								'library' => 'svg',
								'value'   => array( 'id' => absint( isset( $data['icon']['value']['id'] ) ? $data['icon']['value']['id'] : 0 ) ),
							),
							array( 'aria-hidden' => 'true' )
						);
						$icon_html = ob_get_clean();
					} elseif ( $data['icon']['value'] ) {
						$icon_html = '<i class="' . $data['icon']['value'] . '"></i>';
					}
					if ( $data['icon'] && ( 'left' === $data['icon_pos'] || 'up' === $data['icon_pos'] ) ) {
						$html .= $icon_html;
					}
					$html .= $data['title'];
					if ( $data['icon'] && ( 'down' === $data['icon_pos'] || 'right' === $data['icon_pos'] ) ) {
						$html .= $icon_html;
					}
					if ( ! $data['icon'] && ! $data['title'] ) {
						$html .= esc_html__( 'Tab Title', 'alpha-core' );
					}
					?>
					<li class="nav-item<?php echo ! $data['icon']['value'] ? '' : ' nav-icon-' . esc_attr( $data['icon_pos'] ); ?>"><a class="nav-link<?php echo esc_attr( 0 === $idx ? ' active' : '' ); ?>" href="<?php echo esc_attr( $data['id'] ); ?>"><?php echo alpha_strip_script_tags( $html ); ?></a></li>
				<?php endforeach; ?>
				</ul>

				<?php if ( $self->legacy_mode ) : ?>
					<div class="elementor-row tab-content">
				<?php else : ?>
					<div class="tab-content">
					<?php
				endif;
		}
	}

	/**
	 * Render tab HTML after elementor section render
	 *
	 * @since 4.1
	 */
	public function section_addon_after_render( $self, $settings ) {
		if ( 'tab' == $settings['use_as'] ) {
					echo '</div>';
				echo '</div>';
			?>
			</<?php echo esc_html( $self->get_html_tag() ); ?>>
			<?php
			unset( $GLOBALS['alpha_section'] );
		}
	}

	/**
	 * Register tab content addon to column element
	 *
	 * @since 4.1
	 */
	public function register_column_addon( $addons ) {
		$addons['tab_content'] = esc_html__( 'Tab Content', 'alpha-core' );

		return $addons;
	}

	/**
	 * Add tab content controls to column element
	 *
	 * @since 4.1
	 */
	public function add_column_controls( $self, $condition_value ) {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$self->start_controls_section(
			'column_tab',
			array(
				'label'     => alpha_elementor_panel_heading( esc_html__( 'Tab Content', 'alpha-core' ) ),
				'tab'       => Controls_Manager::TAB_LAYOUT,
				'condition' => array(
					$condition_value => 'tab_content',
				),
			)
		);

			$self->add_control(
				'tab_title',
				array(
					'type'    => Controls_Manager::TEXT,
					'label'   => esc_html__( 'Nav Title', 'alpha-core' ),
					'default' => esc_html__( 'Nav Title', 'alpha-core' ),
				)
			);

			$self->add_control(
				'tab_icon',
				array(
					'label'       => esc_html__( 'Nav Icon', 'alpha-core' ),
					'description' => esc_html__( 'Choose icon for title of each tab item.', 'alpha-core' ),
					'type'        => Controls_Manager::ICONS,
				)
			);

			$self->add_control(
				'tab_icon_pos',
				array(
					'label'       => esc_html__( 'Nav Type With Icon', 'alpha-core' ),
					'description' => esc_html__( 'Choose icon position of each tab nav. Choose from Left, Up, Right, Bottom.', 'alpha-core' ),
					'default'     => 'left',
					'label_block' => 'true',
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						'up'    => array(
							'title' => esc_html__( 'Up', 'alpha-core' ),
							'icon'  => 'eicon-v-align-top',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-h-align-right',
						),
						'down'  => array(
							'title' => esc_html__( 'Down', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
						'left'  => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-h-align-left',
						),
					),
				)
			);

			$self->add_responsive_control(
				'tab_navs_up_icon_pos',
				array(
					'label'       => esc_html__( 'Navs Position', 'alpha-core' ),
					'description' => esc_html__( 'Controls alignment of tab titles. Choose from Start, Center, End.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => array(
						'left'   => array(
							'title' => esc_html__( 'Start', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'End', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors'   => array(
						'.tab .nav-link[href="{{ID}}"]' => 'text-align: -webkit-{{VALUE}};',
					),
					'conditions'  => array(
						'relation' => 'or',
						'terms'    => array(
							array(
								'name'     => 'tab_icon_pos',
								'operator' => '=',
								'value'    => 'up',
							),
							array(
								'name'     => 'tab_icon_pos',
								'operator' => '=',
								'value'    => 'down',
							),
						),
					),
				)
			);

			$self->add_control(
				'tab_icon_space',
				array(
					'label'       => esc_html__( 'Nav Icon Spacing (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls spacing between icon and label in nav item.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 30,
						),
					),
					'selectors'   => array(
						'.tab .nav-link[href="{{ID}}"]' => '--alpha-tab-icon-space: {{SIZE}}px;',
					),
				)
			);

			$self->add_control(
				'tab_icon_size',
				array(
					'label'       => esc_html__( 'Nav Icon Size (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls icon size of tab item header.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'selectors'   => array(
						'.tab .nav-link[href="{{ID}}"] i' => 'font-size: {{SIZE}}px;',
						'.tab .nav-link[href="{{ID}}"] svg' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					),
				)
			);

		$self->end_controls_section();
	}

	/**
	 * Print tab content in elementor column content template function
	 *
	 * @since 4.1
	 */
	public function column_addon_content_template( $self ) {
		?>
		<#

		if( 'tab_content' == settings.use_as ) {
			let wrapper_attrs = '';
			let icon_html = '';

			wrapper_attrs += ' data-role="tab-pane"';
			wrapper_attrs += ' data-tab-title="' + settings.tab_title + '"';
			if ( settings.tab_icon && settings.tab_icon.value ) {
				if( settings.tab_icon.library && 'svg' == settings.tab_icon.library ) {
					var svgString = '' + elementor.helpers.renderIcon( view, settings.tab_icon, { 'aria-hidden': true } ).value;
					icon_html = svgString.replaceAll('\"', '\~');
				} else {
					icon_html += '<i class=~' + settings.tab_icon.value + '~></i>';
				}
			}
			wrapper_attrs += ' data-tab-icon="' + (settings.tab_icon ? icon_html : '') + '"';
			wrapper_attrs += ' data-tab-icon-pos="' + settings.tab_icon_pos + '"';
			#>

			<?php if ( ! alpha_elementor_if_dom_optimization() ) { ?>
				<# wrapper_element = 'column'; #>
			<?php } else { ?>
				<# wrapper_element = 'widget'; #>
			<?php } ?>

			<#
			addon_html += '<!-- Start .elementor-column-wrap(optimize mode => .elementor-widget-wrap) --><div class="elementor-' + wrapper_element + '-wrap" ' + wrapper_attrs + '>';
			addon_html += '<div class="elementor-background-overlay"></div>';
			#>

			<?php if ( ! alpha_elementor_if_dom_optimization() ) { ?>
				<# addon_html += '<!-- Start .elementor-widget-wrap --><div class="elementor-widget-wrap"></div>'; #>
			<?php } ?>

			<#
			addon_html += '<!-- End .elementor-column-wrap(optimize mode => .elementor-widget-wrap) --></div>';
		}

		#>
		<?php
	}

	/**
	 * Add render attributes for tab content
	 *
	 * @since 4.1
	 */
	public function column_addon_attributes( $options, $self, $settings ) {
		if ( 'tab_content' == $settings['use_as'] ) {
			global $alpha_section;

			$options['classes'][]                 = ' tab-pane';
			$options['wrapper_args']['data-role'] = ' tab-pane';
			$options['wrapper_args']['id']        = $self->get_data( 'id' );
			if ( isset( $alpha_section['section'] ) ) {
				$alpha_section['tab_data'][] = array(
					'title'    => $settings['tab_title'],
					'icon'     => $settings['tab_icon'],
					'icon_pos' => $settings['tab_icon_pos'],
					'id'       => $self->get_data( 'id' ),
				);

				if ( 'tab' == $alpha_section['section'] && 0 == $alpha_section['index'] ) {
					$options['classes'][] = 'active';
				}
			}
			$alpha_section['index']            = ++$alpha_section['index'];
			$options['wrapper_args']['class'] .= ' ' . implode( ' ', $options['classes'] );
		}

		return $options;
	}
}

/**
 * Create instance
 *
 * @since 4.1
 */
Alpha_Tab_Elementor_Widget_Addon::get_instance();
