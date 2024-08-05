<?php
/**
 * Alpha Cart Coupons Elementor Widget
 *
 * @author     Andon
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Cart_Coupons_Elementor_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return ALPHA_NAME . '_cart_coupons';
	}

	public function get_title() {
		return esc_html__( 'Cart Coupons', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-cart-coupons';
	}

	public function get_categories() {
		return array( 'alpha_cart_widget' );
	}

	public function get_keywords() {
		return array( 'woo', 'alpha', 'cart', 'coupons', 'checkout' );
	}

	/**
	 * Get Script depends.
	 *
	 * @since 4.1
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-cart-coupons', alpha_core_framework_uri( '/builders/cart/widgets/coupons/cart-coupons' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-cart-coupons' );
	}

	protected function register_controls() {
		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' == $left ? 'right' : 'left';
		$this->start_controls_section(
			'section_coupons_content',
			array(
				'label' => esc_html__( 'General', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

			$this->add_control(
				'notice_is_coupon',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'Please enable coupons %1$shere%2$s.', 'alpha-core' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings' ) ) . '" target="_blank">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->add_control(
				'button_type',
				array(
					'type'    => Controls_Manager::HIDDEN,
					'default' => '',
				)
			);

			$this->add_control(
				'coupons_layout',
				array(
					'label'       => esc_html__( 'Layout', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'default'     => 'block',
					'description' => esc_html__( 'Select the layout of coupons.', 'alpha-core' ),
					'options'     => array(
						'block' => array(
							'title' => esc_html__( 'Block', 'alpha-core' ),
							'icon'  => 'eicon-v-align-bottom',
						),
						'flex'  => array(
							'title' => esc_html__( 'Inline', 'alpha-core' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .form-coupon' => 'display: {{VALUE}};',
					),
					'toggle'      => false,
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_coupons_style',
			array(
				'label' => esc_html__( 'Form Field', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'input_typography',
					'label'    => esc_html__( 'Typography', 'alpha-core' ),
					'selector' => '.elementor-element-{{ID}} .form-row .input-text',
				)
			);
			$this->add_control(
				'input_bottom_spacing',
				array(
					'label'       => esc_html__( 'Spacing (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the spacing of input box.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => array(
						'size' => 10,
					),
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .input-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					),
					'condition'   => array(
						'coupons_layout' => 'block',
					),
				)
			);
			$this->add_control(
				'input_right_spacing',
				array(
					'label'       => esc_html__( 'Spacing (px)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the spacing of input box.', 'alpha-core' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => array(
						'size' => 10,
					),
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'selectors'   => array(
						'.elementor-element-{{ID}} .input-text' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					),
					'condition'   => array(
						'coupons_layout' => 'flex',
					),
				)
			);

			$this->start_controls_tabs( 'input_tab_color' );
				$this->start_controls_tab(
					'tab_input_normal',
					array(
						'label' => esc_html__( 'Normal', 'alpha-core' ),
					)
				);

					$this->add_control(
						'input_color',
						array(
							'label'       => esc_html__( 'Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the text color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text, .elementor-element-{{ID}} .form-row .input-text::placeholder' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_bg_color',
						array(
							'label'       => esc_html__( 'Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the background color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_bd_color',
						array(
							'label'       => esc_html__( 'Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the border color of the form fields.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text' => 'border-color: {{VALUE}};',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_input_focus',
					array(
						'label' => esc_html__( 'Focus', 'alpha-core' ),
					)
				);
					$this->add_control(
						'input_focus_color',
						array(
							'label'       => esc_html__( 'Focus Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the text color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text:focus, .elementor-element-{{ID}} .form-row .input-text:focus::placeholder' => 'color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_focus_bg_color',
						array(
							'label'       => esc_html__( 'Focus Background Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the background color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text:focus' => 'background-color: {{VALUE}};',
							),
						)
					);
					$this->add_control(
						'input_focus_bd',
						array(
							'label'       => esc_html__( 'Focus Border Color', 'alpha-core' ),
							'description' => esc_html__( 'Controls the border color of the form fields on focus.', 'alpha-core' ),
							'type'        => Controls_Manager::COLOR,
							'selectors'   => array(
								'.elementor-element-{{ID}} .form-row .input-text:focus' => 'border-color: {{VALUE}};',
							),
						)
					);
				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();

		alpha_elementor_button_style_controls( $this, array(), '', '', false, true, true );
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
			<?php if ( wc_coupons_enabled() ) { ?>
				<div id="cart_coupon_box" class="expanded">
					<div class="form-row form-coupon">
						<input type="text" name="coupon_code" class="input-text form-control alpha_coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter coupon code here...', 'alpha-core' ); ?>">
						<button type="submit" name="apply_coupon" class="btn btn-outline btn-dark btn-border-thin alpha-apply-coupon" value="<?php esc_attr_e( 'Apply coupon', 'alpha-core' ); ?>"><?php esc_html_e( 'Apply coupon', 'alpha-core' ); ?></button>
						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				</div>
			<?php } ?>
		<?php
	}
}
