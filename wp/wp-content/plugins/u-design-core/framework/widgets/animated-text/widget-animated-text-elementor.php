<?php
/**
 * Alpha Animated Text Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.2.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

class Alpha_Animated_Text_Elementor_Widget extends Elementor\Widget_Heading {

	public function get_name() {
		return ALPHA_NAME . '_widget_animated_text';
	}

	public function get_title() {
		return esc_html__( 'Animated Text', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-animate';
	}

	public function get_keywords() {
		return array( 'animation', 'animated', 'heading', 'text', 'alpha' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 1.2.0
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-animated-text', alpha_core_framework_uri( '/widgets/animated-text/animated-text' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		return array( 'alpha-animated-text' );
	}

	/**
	 * Get the script depends.
	 *
	 * @since 1.2.0
	 */
	public function get_script_depends() {
		wp_register_script( 'alpha-animated-text', alpha_core_framework_uri( '/widgets/animated-text/animated-text' . ALPHA_JS_SUFFIX ), array( 'jquery-core' ), ALPHA_CORE_VERSION, true );
		return array( 'alpha-animated-text' );
	}

	protected function register_controls() {

		parent::register_controls();

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Text', 'alpha-core' ),
				'description' => esc_html__( 'Type a certain heading you want to display.', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => alpha_get_option( 'primary_color' ),
				'selectors'   => array(
					'.elementor-element-{{ID}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'custom_class',
			array(
				'label'       => esc_html__( 'Custom Class', 'alpha-core' ),
				'description' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'title_after',
			array(
				'label'       => esc_html__( 'After Texts', 'alpha-core' ),
				'description' => esc_html__( 'Enter after text.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Theme', 'alpha-core' ),
				'dynamic'     => array(
					'active' => true,
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title',
				),
			)
		);

		$presets = array(
			array(
				'text' => esc_html__( 'Business', 'alpha-core' ),
			),
			array(
				'text' => esc_html__( 'Portfolio', 'alpha-core' ),
			),
			array(
				'text' => esc_html__( 'Education', 'alpha-core' ),
			),
			array(
				'text' => esc_html__( 'E-Commerce', 'alpha-core' ),
			),
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Animating Texts', 'alpha-core' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ text }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => $presets,
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title',
				),
			)
		);

		$this->add_control(
			'title_before',
			array(
				'label'       => esc_html__( 'Before Texts', 'alpha-core' ),
				'description' => esc_html__( 'Enter before text.', 'alpha-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Truly', 'alpha-core' ),
				'dynamic'     => array(
					'active' => true,
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title',
				),
			)
		);

		$this->add_responsive_control(
			'texts_align',
			array(
				'label'     => esc_html__( 'Texts Alignment', 'alpha-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .animating-item' => 'justify-content: {{VALUE}};',
				),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title_after',
				),
			)
		);

		$this->update_control(
			'header_size',
			array(
				'label' => esc_html__( 'Title HTML Tag', 'alpha-core' ),
			)
		);

		$this->update_responsive_control(
			'align',
			array(
				'label'   => esc_html__( 'Title Alignment', 'alpha-core' ),
				'options' => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'alpha-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'alpha-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'alpha-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
			)
		);

		$this->remove_control( 'title' );
		$this->remove_control( 'link' );
		$this->remove_control( 'size' );

		$this->start_controls_section(
			'section_animation',
			array(
				'label' => esc_html__( 'Animation Options', 'alpha-core' ),
			)
		);

			$this->add_control(
				'animation_type',
				array(
					'label'       => esc_html__( 'Animation Type', 'alpha-core' ),
					'description' => esc_html__( 'Select the style for animated text.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'joke',
					'options'     => array(
						'joke'     => esc_html__( 'Joke', 'alpha-core' ),
						'fall'     => esc_html__( 'Fall', 'alpha-core' ),
						'rise'     => esc_html__( 'Rise', 'alpha-core' ),
						'rotation' => esc_html__( 'Rotation', 'alpha-core' ),
						'croco'    => esc_html__( 'Croco', 'alpha-core' ),
						'scaling'  => esc_html__( 'Scaling', 'alpha-core' ),
						'typing'   => esc_html__( 'Typing', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'reveal_effect',
				array(
					'label'       => esc_html__( 'Reveal Effect', 'alpha-core' ),
					'description' => esc_html__( 'Allow to show text in it\'s avaiable space.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_off'   => esc_html__( 'Off', 'alpha-core' ),
					'label_on'    => esc_html__( 'On', 'alpha-core' ),
					'condition'   => array(
						'animation_type' => array( 'joke', 'fall', 'rise' ),
					),
				),
			);

			$this->add_control(
				'each_duration',
				array(
					'label'       => esc_html__( 'Duration of Letter/Word (ms)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the duration of each letter or word in animating item. In milliseconds, 1000 = 1 second.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => '250',
				)
			);

			$this->add_control(
				'each_delay',
				array(
					'label'       => esc_html__( 'Delay of Letter/Word (ms)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the delay of each letter or word in animating item. In milliseconds, 1000 = 1 second.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => '25',
				)
			);

			$this->add_control(
				'animation_delay',
				array(
					'label'       => esc_html__( 'Delay Between Items (ms)', 'alpha-core' ),
					'description' => esc_html__( 'Controls the delay of animation between each animating item. In milliseconds, 1000 = 1 second.', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => '3000',
				)
			);

			$this->add_control(
				'split_type',
				array(
					'label'       => esc_html__( 'Split Type', 'alpha-core' ),
					'description' => esc_html__( 'Split the animated text into animating pieces such as letters or words.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'letter',
					'options'     => array(
						'letter' => esc_html__( 'Letters', 'alpha-core' ),
						'word'   => esc_html__( 'Words', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'animate_infinite',
				array(
					'label'       => esc_html__( 'Animate Infinitely', 'alpha-core' ),
					'description' => esc_html__( 'Allow to animate infinitely.', 'alpha-core' ),
					'type'        => Controls_Manager::SWITCHER,
					'label_off'   => esc_html__( 'Off', 'alpha-core' ),
					'label_on'    => esc_html__( 'On', 'alpha-core' ),
					'default'     => 'yes',
				),
			);

			$this->add_control(
				'typing_color',
				array(
					'label'       => esc_html__( 'Typing Color', 'alpha-core' ),
					'description' => esc_html__( 'Control color of split focus line in typing animation.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .animating-text-typing .animating-item:after' => 'color: {{VALUE}}',
					),
					'condition'   => array(
						'animation_type' => 'typing',
					),
				),
				array(
					'position' => array(
						'at' => 'after',
						'of' => 'blend_mode',
					),
				)
			);

		$this->end_controls_section();
	}

	protected function render() {
		$atts         = $this->get_settings_for_display();
		$atts['self'] = $this;
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/animated-text/render-animated-text-elementor.php' );
	}

	protected function content_template() {
	}
}
