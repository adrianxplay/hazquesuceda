<?php
/**
 * Alpha Header Elementor Account
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Header_Account_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_header_account';
	}

	public function get_title() {
		return esc_html__( 'Account', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-lock-user';
	}

	public function get_categories() {
		return array( 'alpha_header_widget' );
	}
	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-tab', alpha_core_framework_uri( '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-tab' );
	}

	public function get_keywords() {
		return array( 'header', 'alpha', 'account', 'login', 'register', 'sign' );
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' === $left ? 'right' : 'left';

		$this->start_controls_section(
			'section_account_content',
			array(
				'label' => esc_html__( 'Account', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'type',
				array(
					'label'   => esc_html__( 'Account Type', 'alpha-core' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'inline',
					'options' => array(
						'block'  => array(
							'title' => esc_html__( 'Block', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
						'inline' => array(
							'title' => esc_html__( 'Inline', 'alpha-core' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
				)
			);

			$this->add_control(
				'form_type',
				array(
					'label'   => esc_html__( 'Login Form Type', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						''          => esc_html__( 'Popup', 'alpha-core' ),
						'offcanvas' => esc_html__( 'Off Canvas', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'account_items',
				array(
					'label'       => esc_html__( 'Show Items', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'default'     => array(
						'icon',
						'login',
					),
					'options'     => array(
						'icon'     => esc_html__( 'User Icon', 'alpha-core' ),
						'login'    => esc_html__( 'Login/Logout Label', 'alpha-core' ),
						'register' => esc_html__( 'Register Label', 'alpha-core' ),
					),
					'description' => esc_html__( 'Select items to show in log-in and log-out.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'icon',
				array(
					'label'                  => esc_html__( 'Icon', 'alpha-core' ),
					'type'                   => Controls_Manager::ICONS,
					'default'                => array(
						'value'   => ALPHA_ICON_PREFIX . '-icon-account',
						'library' => 'alpha-icons',
					),
					'description'            => esc_html__( 'Choose log-in icon.', 'alpha-core' ),
					'skin'                   => 'inline',
					'exclude_inline_options' => array( 'svg' ),
					'label_block'            => false,
				)
			);

			$this->add_control(
				'account_login',
				array(
					'label'       => esc_html__( 'Login Text', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Log in', 'alpha-core' ),
					'description' => esc_html__( 'Set log-in text.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'account_register',
				array(
					'label'       => esc_html__( 'Register Text', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Register', 'alpha-core' ),
					'description' => esc_html__( 'Sets register text.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'account_delimiter',
				array(
					'label'       => esc_html__( 'Delimiter Text', 'alpha-core' ),
					'description' => esc_html__( 'Account Delimiter will be shown between Login and Register links', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '/',
					'conditions'  => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'login',
							),
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'register',
							),
						),
					),
				)
			);

			$this->add_control(
				'label_heading2',
				array(
					'label'     => esc_html__( 'When user is logged in...', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

		if ( class_exists( 'WooCommerce' ) ) {

			$this->add_control(
				'account_dropdown',
				array(
					'label'       => esc_html__( 'Menu Dropdown', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'default'     => '',
					'description' => esc_html__( 'Menu that is located in Account Menu will be shown.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'dropdown_align',
				array(
					'label'     => esc_html__( 'Dropdown Align', 'alpha-core' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'auto' => array(
							'title' => esc_html__( 'Left', 'alpha-core' ),
							'icon'  => 'eicon-h-align-left',
						),
						''     => array(
							'title' => esc_html__( 'Right', 'alpha-core' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors' => array(
						'.elementor-element-{{ID}} .dropdown-box' => "{$right}: {{VALUE}};",
					),
					'condition' => array(
						'account_dropdown' => 'yes',
					),
				)
			);

		}

			$this->add_control(
				'account_logout',
				array(
					'label'       => esc_html__( 'Logout Text', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => 'Log out',
					'description' => esc_html__( 'Please input %name% where you want to show current user name. ( ex: Hi, %name%! )', 'alpha-core' ),
				)
			);

			$this->add_control(
				'account_avatar',
				array(
					'label'   => esc_html__( 'Show Avatar', 'alpha-core' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'no',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_account_style',
			array(
				'label' => esc_html__( 'Account', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'account_typography',
					'selector' => '.elementor-element-{{ID}} .account a',
				)
			);

			$this->add_responsive_control(
				'account_icon',
				array(
					'label'       => esc_html__( 'Icon Size (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .account i' => 'font-size: {{SIZE}}px;',
					),
					'condition'   => array(
						'account_avatar!' => 'yes',
					),
					'description' => esc_html__( 'Control the size of account icon.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'account_icon_space',
				array(
					'label'       => esc_html__( 'Icon Space (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .block-type i + span, .elementor-element-{{ID}} .block-type .links' => 'margin-top: {{SIZE}}px;',
						'.elementor-element-{{ID}} .inline-type i + span' => "margin-{$left}: {{SIZE}}px;",
					),
					'condition'   => array(
						'account_avatar!' => 'yes',
					),
					'description' => esc_html__( 'Controls the space between icon and labels.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'account_avatar_size',
				array(
					'label'       => esc_html__( 'Avatar Size (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .account-avatar' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					),
					'condition'   => array(
						'account_avatar' => 'yes',
					),
					'description' => esc_html__( 'Controls the size of avatar.', 'alpha-core' ),
				)
			);

			$this->add_responsive_control(
				'account_avatar_space',
				array(
					'label'       => esc_html__( 'Avatar Space (px)', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'selectors'   => array(
						'.elementor-element-{{ID}} .inline-type .account-avatar' => "margin-{$right}: {{SIZE}}px;",
						'.elementor-element-{{ID}} .block-type .account-avatar'  => 'margin-bottom: {{SIZE}}px;',
					),
					'condition'   => array(
						'account_avatar' => 'yes',
					),
					'description' => esc_html__( 'Controls the space between avatar and lables.', 'alpha-core' ),
				)
			);

			$this->add_control(
				'account_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .account > a, .elementor-element-{{ID}} .block-type' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'account_hover_color',
				array(
					'label'     => esc_html__( 'Hover Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .account > a:hover, .elementor-element-{{ID}} .block-type .links > a:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'delimiter_heading',
				array(
					'label'     => esc_html__( 'Delimiter', 'alpha-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'conditions'  => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'login',
							),
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'register',
							),
							array(
								'name'     => 'account_delimiter',
								'operator' => '!=',
								'value'    => ''
							),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'deimiter_typography',
					'selector' => '.elementor-element-{{ID}} .account .delimiter',
					'conditions'  => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'login',
							),
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'register',
							),
							array(
								'name'     => 'account_delimiter',
								'operator' => '!=',
								'value'    => ''
							),
						),
					),
				)
			);

			$this->add_control(
				'delimiter_color',
				array(
					'label'     => esc_html__( 'Color', 'alpha-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'.elementor-element-{{ID}} .account .delimiter' => 'color: {{VALUE}};',
					),
					'conditions'  => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'login',
							),
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'register',
							),
							array(
								'name'     => 'account_delimiter',
								'operator' => '!=',
								'value'    => ''
							),
						),
					),
				)
			);

			$this->add_responsive_control(
				'account_delimiter_space',
				array(
					'label'      => esc_html__( 'Delimiter Space (px)', 'alpha-core' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'selectors'  => array(
						'.elementor-element-{{ID}} .account .delimiter' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px;',
					),
					'conditions'  => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'login',
							),
							array(
								'name'     => 'account_items',
								'operator' => 'contains',
								'value'    => 'register',
							),
							array(
								'name'     => 'account_delimiter',
								'operator' => '!=',
								'value'    => ''
							),
						),
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$atts     = array(
			'type'             => $settings['type'],
			'form_type'        => $settings['form_type'],
			'items'            => $settings['account_items'],
			'login_text'       => $settings['account_login'] ? $settings['account_login'] : esc_html__( 'Log in', 'alpha-core' ),
			'logout_text'      => $settings['account_logout'] ? $settings['account_logout'] : esc_html__( 'Log out', 'alpha-core' ),
			'register_text'    => $settings['account_register'] ? $settings['account_register'] : esc_html__( 'Register', 'alpha-core' ),
			'delimiter_text'   => $settings['account_delimiter'],
			'icon'             => ! empty( $settings['icon']['value'] ) ? $settings['icon']['value'] : ALPHA_ICON_PREFIX . '-icon-account',
			'account_dropdown' => class_exists( 'WooCommerce' ) && 'yes' === $settings['account_dropdown'],
			'account_avatar'   => 'yes' === $settings['account_avatar'],
		);
		require alpha_core_framework_path( ALPHA_BUILDERS . '/header/widgets/account/render-account-elementor.php' );
	}
}
