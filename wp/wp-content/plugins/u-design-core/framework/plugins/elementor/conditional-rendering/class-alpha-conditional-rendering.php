<?php
/**
 * Conditional Rendering
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.2.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Automattic\Jetpack\Device_Detection;
use Elementor\Alpha_Controls_Manager;
/**
 * Conditional Rendering Class
 *
 * @since 1.2.1
 */
if ( ! class_exists( 'Alpha_Conditional_Rendering' ) ) {
	class Alpha_Conditional_Rendering extends Alpha_Base {
		/**
		 * The device object
		 *
		 * @since 1.2.1
		 */
		public $device;

		/**
		 * Get Post Types
		 *
		 * @since 1.3.0
		 */
		public static $post_types;

		/**
		 * Get Shop page id
		 *
		 * @since 1.3.0
		 */
		public static $shop_id = -2;

		/**
		 * The Constructor.
		 *
		 * @since 1.2.1
		 */
		public function __construct() {
			if ( class_exists( 'WooCommerce' ) ) {
				self::$shop_id = (int) get_option( 'woocommerce_shop_page_id' );
			}
			add_action( 'init', array( $this, 'get_post_type' ) );

			add_action( 'elementor/element/section/section_structure/after_section_end', array( $this, 'add_condition_system' ), 10, 2 );
			add_filter( 'elementor/frontend/section/should_render', array( $this, 'should_render' ), 10, 2 );

			add_action( 'elementor/element/container/section_layout_additional_options/after_section_end', array( $this, 'add_condition_system' ), 10, 2 );
			add_filter( 'elementor/frontend/container/should_render', array( $this, 'should_render' ), 10, 2 );

			add_action( 'elementor/element/column/layout/after_section_end', array( $this, 'add_condition_system' ), 10, 2 );
			add_filter( 'elementor/frontend/column/should_render', array( $this, 'should_render' ), 10, 2 );
		}

		/**
		 * Get post types.
		 *
		 * @since 1.3.0
		 */
		public function get_post_type() {
			self::$post_types    = get_post_types(
				array(
					'public'            => true,
					'show_in_nav_menus' => true,
				),
				'objects',
				'and'
			);
			$disabled_post_types = array( 'attachment' );
			foreach ( $disabled_post_types as $disabled ) {
				unset( self::$post_types[ $disabled ] );
			}
			foreach ( self::$post_types as $key => $p_type ) {
				self::$post_types[ $key ] = esc_html( $p_type->label );
			}
		}

		/**
		 * Add Control
		 *
		 * @since 1.2.1
		 */
		public function add_condition_system( $self ) {
			$self->start_controls_section(
				'section_conditional',
				array(
					'label' => alpha_elementor_panel_heading( esc_html__( 'Conditional Rendering System', 'alpha-core' ) ),
					'tab'   => Controls_Manager::TAB_LAYOUT,
				)
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'condition_a',
				array(
					'label'       => esc_html__( 'Condition A', 'alpha-core' ),
					'description' => esc_html__( 'Select condition type.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'device'       => esc_html__( 'Device', 'alpha-core' ),
						'login_status' => esc_html__( 'Login Status', 'alpha-core' ),
						'user_role'    => esc_html__( 'User Role', 'alpha-core' ),
						'post_page'    => esc_html__( 'Post & Page', 'alpha-core' ),
					),
				)
			);
			$repeater->add_control(
				'comparative_operator',
				array(
					'label'   => esc_html__( 'Comparative Operator', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'equal'     => esc_html__( '==', 'alpha-core' ),
						'not_equal' => esc_html__( '!=', 'alpha-core' ),
					),
				)
			);
			$repeater->add_control(
				'value_device',
				array(
					'label'     => esc_html__( 'Device', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'desktop'       => esc_html__( 'Desktop', 'alpha-core' ),
						'tablet_mobile' => esc_html__( 'Tablet & Mobile', 'alpha-core' ),
						'tablet'        => esc_html__( 'Tablet', 'alpha-core' ),
						'mobile'        => esc_html__( 'Mobile', 'alpha-core' ),
					),
					'condition' => array(
						'condition_a' => 'device',
					),
				)
			);
			$repeater->add_control(
				'value_login',
				array(
					'label'     => esc_html__( 'Status', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'login'  => esc_html__( 'Logged In', 'alpha-core' ),
						'logout' => esc_html__( 'Logged Out', 'alpha-core' ),
					),
					'condition' => array(
						'condition_a' => 'login_status',
					),
				)
			);
			$repeater->add_control(
				'value_role',
				array(
					'label'     => esc_html__( 'Role', 'alpha-core' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $this->get_roles(),
					'condition' => array(
						'condition_a' => 'user_role',
					),
				)
			);
			$repeater->add_control(
				'post_type',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => __( 'Post Type', 'alpha-core' ),
					'options'   => self::$post_types,
					'condition' => array(
						'condition_a' => 'post_page',
					),
				)
			);
			$repeater->add_control(
				'value_page_ids',
				array(
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'label'       => __( 'Page/Post', 'alpha-core' ),
					'options'     => '%post_type%_particularpage',
					'label_block' => true,
					'multiple'    => true,
					'condition'   => array(
						'condition_a' => 'post_page',
					),
				)
			);
			$repeater->add_control(
				'condition_operator',
				array(
					'label'       => esc_html__( 'Operator', 'alpha-core' ),
					'description' => esc_html__( 'The selected value is used to operate on the conditions below.', 'alpha-core' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'and' => esc_html__( 'And', 'alpha-core' ),
						'or'  => esc_html__( 'Or', 'alpha-core' ),
					),
				)
			);

			$self->add_control(
				'description_conditional_render',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Only when these conditions are matched, will this section be rendered.', 'alpha-core' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$self->add_control(
				'conditional_render',
				array(
					'label'         => esc_html__( 'Conditional Render', 'alpha-core' ),
					'type'          => Controls_Manager::REPEATER,
					'fields'        => $repeater->get_controls(),
					'prevent_empty' => false,
					'title_field'   => '{{{ condition_a }}}',
				)
			);

			$self->end_controls_section();
		}

		/**
		 * Returns the roles.
		 *
		 * @since 1.2.1
		 */
		public function get_roles() {
			global $wp_roles;
			$roles = array();
			if ( is_array( $wp_roles->roles ) ) {
				foreach ( $wp_roles->roles as $key => $role ) {
					$roles[ $key ] = $role['name'];
				}
			}
			return $roles;
		}

		/**
		 * Get the device
		 *
		 * @since 1.2.1
		 */
		public function get_device( $is_tablet_mobile = false ) {
			if ( ! class_exists( 'Automattic\Jetpack\Device_Detection' ) && ! defined( 'JETPACK__VERSION' ) ) {
				require_once 'jetpack-device-detection/class-device-detection.php';
				require_once 'jetpack-device-detection/class-user-agent-info.php';
			}
			$critial_mobile = ! empty( $_REQUEST['mobile_url'] );
			if ( ( $critial_mobile || Device_Detection::is_phone() ) && ! $is_tablet_mobile ) {
				return 'mobile';
			} elseif ( Device_Detection::is_tablet() && ! $is_tablet_mobile ) {
				return 'tablet';
			} elseif ( ! wp_is_mobile() ) {
				return 'desktop';
			} elseif ( wp_is_mobile() ) {
				return 'tablet_mobile';
			}
			return '';
		}

		/**
		 * Check if the element should be rendered or not.
		 *
		 * @since 1.2.1
		 */
		public function should_render( $should_render, $self ) {
			$atts = $self->get_settings_for_display();
			if ( function_exists( 'alpha_is_elementor_preview' ) && ! alpha_is_elementor_preview() && ! $this->is_render( $atts ) ) {
				return false;
			}
			return $should_render;
		}

		/**
		 * Is rendering?
		 *
		 * @since 1.2.1
		 */
		public function is_render( $atts ) {

			if ( ! empty( $atts['conditional_render'] ) && is_array( $atts['conditional_render'] ) ) {
				foreach ( $atts['conditional_render'] as $condition ) {
					if ( ! empty( $condition['condition_a'] ) ) {
						switch ( $condition['condition_a'] ) {
							case 'device':
								if ( ! empty( $condition['value_device'] ) ) {
									$right = $condition['value_device'];
								}
								$left = $this->get_device( isset( $right ) && 'tablet_mobile' == $right ? true : false );
								break;
							case 'login_status':
								$left = is_user_logged_in();
								if ( ! empty( $condition['value_login'] ) ) {
									$right = ( 'login' == $condition['value_login'] ? true : false );
								}
								break;
							case 'user_role':
								$left = wp_get_current_user();
								$left = ( 0 !== $left->ID ) ? $left->roles : array();
								if ( ! empty( $condition['value_role'] ) ) {
									$right = $condition['value_role'];
								}
								break;
							case 'post_page':
								if ( ! empty( $condition['value_page_ids'] ) ) {
									$left = is_array( $condition['value_page_ids'] ) ? $condition['value_page_ids'] : explode( ',', $condition['value_page_ids'] );
								}
								$right = get_the_ID();
								if ( is_home() || is_archive() ) {
									$right = get_queried_object_id();
								}
								if ( class_exists( 'WooCommerce' ) && is_shop() ) {
									$right = self::$shop_id;
								}
								if ( is_category() || is_tax() || is_tag() ) {
									$right = -1;
								}
								break;
						}
						if ( ! empty( $condition['comparative_operator'] ) ) {
							$operator = $condition['comparative_operator'];
						}
						if ( ! empty( $condition['condition_operator'] ) ) {
							$condition_operator = $condition['condition_operator'];
						}
						if ( isset( $left ) && isset( $right ) && isset( $operator ) ) {
							if ( 'equal' == $operator ) {
								if ( is_array( $left ) ) {
									$res = in_array( $right, $left );
								} else {
									$res = ( $left == $right );
								}
							} else {
								if ( is_array( $left ) ) {
									$res = ! in_array( $right, $left );
								} else {
									$res = ( $left != $right );
								}
							}
							if ( isset( $render ) ) {
								if ( isset( $prev_operator ) && 'or' == $prev_operator ) {
									$render = $render || $res;
								} else {
									$render = $render && $res;
								}
							} else {
								$render = $res;
							}
							if ( isset( $condition_operator ) ) {
								$prev_operator = $condition_operator;
							} else { // not select
								$prev_operator = 'and';
							}
						}
						unset( $left, $right, $operator );
					}
				}
			}

			return isset( $render ) ? $render : true;
		}
	}
}

Alpha_Conditional_Rendering::get_instance();
