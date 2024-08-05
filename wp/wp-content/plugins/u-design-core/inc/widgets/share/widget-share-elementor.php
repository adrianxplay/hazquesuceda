<?php
/**
 * Share Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Alpha_Controls_Manager;


class Alpha_Share_Elementor_Widget extends \Elementor\Widget_Base {
	public $share_icons = array(
		'facebook'  => array( 'fab fa-facebook-f', 'https://www.facebook.com/sharer.php?u=$permalink' ),
		'twitter'   => array( 'fab fa-twitter', 'https://twitter.com/intent/tweet?text=$title&amp;url=$permalink' ),
		'linkedin'  => array( 'fab fa-linkedin-in', 'https://www.linkedin.com/shareArticle?mini=true&amp;url=$permalink&amp;title=$title' ),
		'email'     => array( 'far fa-envelope', 'mailto:?subject=$title&amp;body=$permalink' ),
		'instagram' => array( 'fab fa-instagram', '' ),
		'youtube'   => array( 'fab fa-youtube', '' ),
		'google'    => array( 'fab fa-google-plus-g', 'https://plus.google.com/share?url=$permalink' ),
		'pinterest' => array( 'fab fa-pinterest', 'https://pinterest.com/pin/create/button/?url=$permalink&amp;media=$image' ),
		'reddit'    => array( 'fab fa-reddit-alien', 'http://www.reddit.com/submit?url=$permalink&amp;title=$title' ),
		'tumblr'    => array( 'fab fa-tumblr', 'http://www.tumblr.com/share/link?url=$permalink&amp;name=$title&amp;description=$excerpt' ),
		'vk'        => array( 'fab fa-vk', 'https://vk.com/share.php?url=$permalink&amp;title=$title&amp;image=$image&amp;noparse=true' ),
		'whatsapp'  => array( 'fab fa-whatsapp', 'whatsapp://send?text=$title - $permalink' ),
		'xing'      => array( 'fab fa-xing', 'https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=$permalink' ),
	);

	public function get_name() {
		return ALPHA_NAME . '_widget_share';
	}

	public function get_title() {
		return esc_html__( 'Social Icons', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'Share', 'Social', 'link' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-social-icons';
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-share', ALPHA_CORE_INC_URI . '/widgets/share/share' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		return array( 'alpha-share' );
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_share_content',
			array(
				'label' => esc_html__( 'Share Icons', 'alpha-core' ),
			)
		);

		$options = array();

		foreach ( $this->share_icons as $key => $value ) {
			$options[ $key ] = $key;
		}

			$repeater = new Repeater();

			$repeater->add_control(
				'site',
				array(
					'label'   => esc_html__( 'Icon', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $options,
					'default' => 'facebook',
				)
			);

			$repeater->add_control(
				'link',
				array(
					'label'       => esc_html__( 'Link', 'alpha-core' ),
					'type'        => Controls_Manager::URL,
					'description' => esc_html__( 'Please leave it blank to share this page or Input URL for a custom link', 'alpha-core' ),
					'options'     => false,
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$this->add_control(
				'share_buttons',
				array(
					'label'       => esc_html__( 'Share Icons', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => array(
						array(
							'site' => 'facebook',
							'link' => '',
						),
						array(
							'site' => 'twitter',
							'link' => '',
						),
						array(
							'site' => 'linkedin',
							'link' => '',
						),
					),
					'title_field' => '{{{ site }}}',
				)
			);

			$this->add_control(
				'type',
				array(
					'label'     => esc_html__( 'Type', 'alpha-core' ),
					'type'      => Alpha_Controls_Manager::IMAGE_CHOOSE,
					'default'   => 'stacked',
					'separator' => 'before',
					'options'   => array(
						''                     => 'assets/images/share/share-1.jpg',
						'framed'               => 'assets/images/share/share-3.jpg',
						'boxed boxed-advanced' => 'assets/images/share/share-6.jpg',
						'boxed'                => 'assets/images/share/share-5.jpg',
						'full'                 => 'assets/images/share/share-2.jpg',
						'stacked'              => 'assets/images/share/share-4.jpg',
					),
					'width'     => 2,
				)
			);

			$this->add_control(
				'border',
				array(
					'label'     => esc_html__( 'Border Style', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'0'   => esc_html__( 'Rectangle', 'alpha-core' ),
						'3px' => esc_html__( 'Rounded', 'alpha-core' ),
						'50%' => esc_html__( 'Circle', 'alpha-core' ),
					),
					'default'   => '0',
					'selectors' => array(
						'.elementor-element-{{ID}} .social-icon' => 'border-radius: {{VALUE}}',
					),
					'condition' => array(
						'type!' => '',
					),
				)
			);

			$this->add_control(
				'border_width',
				array(
					'label'     => esc_html__( 'Border Width', 'alpha-core' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => array(
						'px' => 2,
					),
					'range'     => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 10,
						),
					),
					'condition' => array(
						'type' => 'framed',
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .social-icons .social-icon' => 'border-width: {{SIZE}}px',
					),
				)
			);

			$this->add_control(
				'share_direction',
				array(
					'type'    => Controls_Manager::CHOOSE,
					'label'   => esc_html__( 'Direction', 'alpha-core' ),
					'options' => array(
						'flex'  => array(
							'title' => esc_html__( 'Row', 'alpha-core' ),
							'icon'  => 'eicon-arrow-right',
						),
						'block' => array(
							'title' => esc_html__( 'Column', 'alpha-core' ),
							'icon'  => 'eicon-arrow-down',
						),
					),
					'toggle'  => false,
					'default' => 'flex',
				)
			);

			$this->add_responsive_control(
				'share_align',
				array(
					'label'     => esc_html__( 'Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start'    => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'        => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'flex-end'      => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
						'space-between' => array(
							'title' => esc_html__( 'Justify', 'alpha-core' ),
							'icon'  => 'eicon-text-align-justify',
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .social-icons' => 'justify-content: {{VALUE}};',
					),
					'condition' => array(
						'share_direction' => 'flex',
					),
				)
			);

			$this->add_control(
				'share_v_align',
				array(
					'label'     => esc_html__( 'Alignment', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'me-auto' => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-text-align-left',
						),
						'mx-auto' => array(
							'title' => esc_html__( 'Center', 'alpha-core' ),
							'icon'  => 'eicon-text-align-center',
						),
						'ms-auto' => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'default'   => 'me-auto',
					'condition' => array(
						'share_direction' => 'block',
					),
				)
			);

			$this->add_control(
				'show_divider',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Show Vertical Divider', 'alpha-core' ),
					'condition' => array(
						'share_direction' => 'flex',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_share_style',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'button_size',
				array(
					'label'      => esc_html__( 'Size', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 300,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .social-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_size',
				array(
					'label'      => esc_html__( 'Icon Size', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .social-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'icon_space',
				array(
					'label'      => esc_html__( 'Icon Spacing', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .social-icon.full i' => "margin-{$right}: {{SIZE}}{{UNIT}};",
						'.elementor-element-{{ID}} .social-icon.boxed-advanced i' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'type' => array( 'full', 'boxed boxed-advanced' ),
					),
				)
			);

			$this->add_responsive_control(
				'col_space',
				array(
					'label'      => esc_html__( 'Column Gap', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'default'    => array(
						'size' => 8,
						'unit' => 'px',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .social-icon' => 'margin: calc({{SIZE}}{{UNIT}} / 2);',
						'.elementor-element-{{ID}} .social-icons' => 'margin: calc(-{{SIZE}}{{UNIT}} / 2);',
						'.elementor-element-{{ID}} .social-icons .social-icon:after' => "{$right}: calc(-{{SIZE}}{{UNIT}} / 2);",
					),
					'condition'  => array(
						'share_direction' => 'flex',
					),
				)
			);

			$this->add_responsive_control(
				'row_space',
				array(
					'label'      => esc_html__( 'Row Gap', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'step' => 1,
							'min'  => 1,
							'max'  => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors'  => array(
						'.elementor-element-{{ID}} .social-icon + .social-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'share_direction' => 'block',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_share_color',
			array(
				'label' => esc_html__( 'Color', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'custom_color',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Use Custom Color', 'alpha-core' ),
			)
		);

		$this->start_controls_tabs(
			'tabs_bg_color',
			array(
				'condition' => array(
					'custom_color' => 'yes',
				),
			)
		);

			$this->start_controls_tab(
				'tab_color_normal',
				array(
					'label' => esc_html__( 'Normal', 'alpha-core' ),
				)
			);

			$this->add_control(
				'color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor .elementor-element-{{ID}} .use-hover:not(:hover)' => 'color: {{VALUE}}',
						'.elementor .elementor-element-{{ID}} .use-hover:not(:hover) span' => 'color: inherit',
					),
					'condition' => array(
						'custom_color' => 'yes',
					),
				)
			);

			$this->add_control(
				'border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor .elementor-element-{{ID}} .use-hover:not(:hover)' => 'border-color: {{VALUE}}',
					),
					'condition' => array(
						'custom_color' => 'yes',
						'type'         => 'framed',
					),
				)
			);

			$this->add_control(
				'background_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor .elementor-element-{{ID}} .use-hover:not(:hover)' => 'background: {{VALUE}};',
					),
					'condition' => array(
						'custom_color' => 'yes',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_color_hover',
				array(
					'label' => esc_html__( 'Hover', 'alpha-core' ),
				)
			);

			$this->add_control(
				'hover_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor .elementor-element-{{ID}} .use-hover:hover' => 'color: {{VALUE}}',
						'.elementor .elementor-element-{{ID}} .use-hover:hover span' => 'color: inherit',
					),
					'condition' => array(
						'custom_color' => 'yes',
					),
				)
			);

			$this->add_control(
				'hover_border_color',
				array(
					'label'     => esc_html__( 'Border Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor .elementor-element-{{ID}} .use-hover:hover' => 'border-color: {{VALUE}}',
					),
					'condition' => array(
						'custom_color' => 'yes',
						'type'         => 'framed',
					),
				)
			);

			$this->add_control(
				'hover_bg_color',
				array(
					'label'     => esc_html__( 'Background Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor .elementor-element-{{ID}} .use-hover:hover' => 'background: {{VALUE}};',
					),
					'condition' => array(
						'custom_color' => 'yes',
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_divider_style',
			array(
				'label'     => esc_html__( 'Divider', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_divider'    => 'yes',
					'share_direction' => 'flex',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.elementor-element-{{ID}} .social-icons .social-icon:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'divider_height',
			array(
				'label'      => esc_html__( 'Height', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .social-icons .social-icon:after' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'divider_width',
			array(
				'label'      => esc_html__( 'Width', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .social-icons .social-icon:after' => 'width: {{SIZE}}{{UNIT}}; transform: translate(50%, -50%);',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		require ALPHA_CORE_INC . '/widgets/share/render-share-elementor.php';
	}

	protected function content_template() {
		?>

		<#
		let wrapper_class = 'social-icons',
			html = '',
			share_icons = {
				'facebook'  : [ 'fab fa-facebook-f', 'https://www.facebook.com/sharer.php?u=$permalink' ],
				'twitter'   : [ 'fab fa-twitter', 'https://twitter.com/intent/tweet?text=$title&amp;url=$permalink' ],
				'linkedin'  : [ 'fab fa-linkedin-in', 'https://www.linkedin.com/shareArticle?mini=true&amp;url=$permalink&amp;title=$title' ],
				'email'     : [ 'far fa-envelope', 'mailto:?subject=$title&amp;body=$permalink' ],
				'instagram' : [ 'fab fa-instagram', '' ],
				'youtube'   : [ 'fab fa-youtube', '' ],
				'google'    : [ 'fab fa-google-plus-g', 'https://plus.google.com/share?url=$permalink' ],
				'pinterest' : [ 'fab fa-pinterest', 'https://pinterest.com/pin/create/button/?url=$permalink&amp;media=$image' ],
				'reddit'    : [ 'fab fa-reddit-alien', 'http://www.reddit.com/submit?url=$permalink&amp;title=$title' ],
				'tumblr'    : [ 'fab fa-tumblr', 'http://www.tumblr.com/share/link?url=$permalink&amp;name=$title&amp;description=$excerpt' ],
				'vk'        : [ 'fab fa-vk', 'https://vk.com/share.php?url=$permalink&amp;title=$title&amp;image=$image&amp;noparse=true' ],
				'whatsapp'  : [ 'fab fa-whatsapp', 'whatsapp://send?text=$title - $permalink' ],
				'xing'      : [ 'fab fa-xing', 'https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=$permalink' ],
			};

		if ( 'block' == settings.share_direction ) {
			wrapper_class += ' social-icons-vertical';
		} else if ( 'yes' == settings.show_divider ) {
			wrapper_class += ' social-icons-separated';
		}
		#> 

		<div class="{{{ wrapper_class }}}"> 

		<# let custom = 'yes' == settings.custom_color ? ' use-hover ' : '';

			if ( settings.share_buttons ) {
				_.each( settings.share_buttons, function( share, index ) {
					let link  = share['link']['url'];
					let site = share['site'];

					#>

					<a href="{{{ link ? link : '#' }}}" class="{{{ 'social-icon ' + settings.type + ' ' + custom + ( 'block' === settings.share_direction ? settings.share_v_align : '' ) + ' social-' + site }}}" target="_blank" title="{{{ site }}}" rel="noopener noreferrer">
						<i class="{{{ share_icons[ site ][0] }}}"></i>

					<# if ( 'full' == settings.type || -1 != settings.type.indexOf( 'boxed-advanced' ) ) { #>
						<span>{{{ site }}}</span>
					<# } #>
					</a>
					<#
				});
			}
			#>
		</div>

		<?php
	}
}
