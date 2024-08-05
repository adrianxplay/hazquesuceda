<?php
/**
 * Alpha Elementor Single Product Image Widget
 *
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 * @since      1.2.0 Added thumbnail columns, spacing option
 */
defined( 'ABSPATH' ) || die;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

class Alpha_Single_Product_Image_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_sproduct_image';
	}

	public function get_title() {
		return esc_html__( 'Product Images', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon eicon-product-images';
	}

	public function get_categories() {
		return array( 'alpha_single_product_widget' );
	}

	public function get_keywords() {
		return array( 'single', 'custom', 'layout', 'product', 'woocommerce', 'shop', 'store', 'image', 'thumbnail', 'gallery' );
	}

	public function get_script_depends() {
		$depends = array( 'swiper', 'zoom' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
		}
		return $depends;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_gallery_content',
			array(
				'label' => esc_html__( 'Content', 'alpha-core' ),
			)
		);

			$this->add_control(
				'sp_type',
				array(
					'label'   => esc_html__( 'Type', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						''              => esc_html__( 'Featured', 'alpha-core' ),
						'horizontal'    => esc_html__( 'Horizontal', 'alpha-core' ),
						'vertical'      => esc_html__( 'Vertical', 'alpha-core' ),
						'grid'          => esc_html__( 'Grid', 'alpha-core' ),
						'masonry'       => esc_html__( 'Masonry', 'alpha-core' ),
						'gallery'       => esc_html__( 'Gallery', 'alpha-core' ),
						'sticky-thumbs' => esc_html__( 'Sticky Thumbs', 'alpha-core' ),
					),
				)
			);

			// $this->add_control(
			// 	'col_cnt_xl',
			// 	array(
			// 		'label'     => esc_html__( 'Columns ( >= 1200px )', 'alpha-core' ),
			// 		'type'      => Controls_Manager::SELECT,
			// 		'options'   => array(
			// 			'1' => 1,
			// 			'2' => 2,
			// 			'3' => 3,
			// 			'4' => 4,
			// 			'5' => 5,
			// 			'6' => 6,
			// 			'7' => 7,
			// 			'8' => 8,
			// 			''  => esc_html__( 'Default', 'alpha-core' ),
			// 		),
			// 		'condition' => array(
			// 			'sp_type' => array( 'grid', 'gallery' ),
			// 		),
			// 	)
			// );

			$this->add_responsive_control(
				'col_cnt',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Columns', 'alpha-core' ),
					'options'   => array(
						'1' => 1,
						'2' => 2,
						'3' => 3,
						'4' => 4,
						'5' => 5,
						'6' => 6,
						'7' => 7,
						'8' => 8,
						''  => esc_html__( 'Default', 'alpha-core' ),
					),
					'condition' => array(
						'sp_type' => array( 'grid', 'gallery' ),
					),
				)
			);

			$this->add_control(
				'thumb_col_cnt',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Thumbnail Columns', 'alpha-core' ),
					'options'   => array(
						'1' => 1,
						'2' => 2,
						'3' => 3,
						'4' => 4,
						'5' => 5,
						'6' => 6,
						'7' => 7,
						'8' => 8,
						''  => esc_html__( 'Default', 'alpha-core' ),
					),
					'condition' => array(
						'sp_type' => array( 'horizontal' ),
					),
				)
			);

			// $this->add_control(
			// 	'col_cnt_min',
			// 	array(
			// 		'label'     => esc_html__( 'Columns ( < 576px )', 'alpha-core' ),
			// 		'type'      => Controls_Manager::SELECT,
			// 		'options'   => array(
			// 			'1' => 1,
			// 			'2' => 2,
			// 			'3' => 3,
			// 			'4' => 4,
			// 			'5' => 5,
			// 			'6' => 6,
			// 			'7' => 7,
			// 			'8' => 8,
			// 			''  => esc_html__( 'Default', 'alpha-core' ),
			// 		),
			// 		'condition' => array(
			// 			'sp_type' => array( 'grid', 'gallery' ),
			// 		),
			// 	)
			// );

			$this->add_control(
				'col_sp',
				array(
					'label'       => esc_html__( 'Spacing', 'alpha-core' ),
					'description' => esc_html__( 'Select the amount of spacing between items.', 'alpha-core' ),
					'type'        => Controls_Manager::CHOOSE,
					'options'     => apply_filters(
						'alpha_col_sp',
						array(
							'no' => array(
								'title' => esc_html__( 'No space', 'alpha-core' ),
								'icon'  => 'eicon-ban',
							),
							'xs' => array(
								'title' => esc_html__( 'Extra Small', 'alpha-core' ),
								'icon'  => 'alpha-size-xs alpha-choose-type',
							),
							'sm' => array(
								'title' => esc_html__( 'Small', 'alpha-core' ),
								'icon'  => 'alpha-size-sm alpha-choose-type',
							),
							'md' => array(
								'title' => esc_html__( 'Medium', 'alpha-core' ),
								'icon'  => 'alpha-size-md alpha-choose-type',
							),
							'lg' => array(
								'title' => esc_html__( 'Large', 'alpha-core' ),
								'icon'  => 'alpha-size-lg alpha-choose-type',
							),
						),
						'elementor'
					),
					'condition'   => array(
						'sp_type!' => '',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_gallery_style',
			array(
				'label' => esc_html__( 'Style', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '.woocommerce .elementor-element-{{ID}} .woocommerce-product-gallery__image img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.woocommerce .elementor-element-{{ID}} .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_thumbs_style',
			array(
				'label'     => esc_html__( 'Thumbnails', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'thumbs_border',
				'selector' => '.woocommerce .elementor-element-{{ID}} .product-thumb img',
			)
		);

		$this->add_responsive_control(
			'thumbs_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.woocommerce .elementor-element-{{ID}} .product-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'spacing_thumbs',
			array(
				'label'      => esc_html__( 'Spacing', 'alpha-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'.woocommerce .elementor-element-{{ID}} .product-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
	}

	public function get_gallery_type() {
		return $this->get_settings_for_display( 'sp_type' );
	}

	public function extend_gallery_main_class( $classes ) {
		$settings              = $this->get_settings_for_display();
		$single_product_layout = $settings['sp_type'];
		$classes[]             = 'pg-custom';

		if ( 'grid' == $single_product_layout || 'masonry' == $single_product_layout ) {

			foreach ( $classes as $i => $class ) {
				if ( 'cols-sm-2' == $class ) {
					array_splice( $classes, $i, 1 );
				}
			}
			$classes[]        = alpha_get_col_class( alpha_elementor_grid_col_cnt( $settings ) );
			$grid_space_class = alpha_get_grid_space_class( $settings );
			if ( $grid_space_class ) {
				$classes[] = $grid_space_class;
			}
		}

		return $classes;
	}

	public function extend_gallery_class( $class ) {
		$settings              = $this->get_settings_for_display();
		$single_product_layout = $settings['sp_type'];

		if ( 'gallery' == $single_product_layout ) {
			$class            = ' ' . alpha_get_col_class( alpha_elementor_grid_col_cnt( $settings ) );
			$grid_space_class = alpha_get_grid_space_class( $settings );
			if ( $grid_space_class ) {
				$class .= ' ' . $grid_space_class;
			}
		}
		return $class;
	}

	public function extend_gallery_attr( $attr ) {
		$settings              = $this->get_settings_for_display();
		$single_product_layout = $settings['sp_type'];

		if ( 'gallery' == $single_product_layout ) {
			$settings['show_nav']  = 'yes';
			$settings['show_dots'] = 'yes';
			$attr                  = array_merge( $attr, alpha_get_slider_attrs( $settings, alpha_elementor_grid_col_cnt( $settings ) ) );
		}

		if ( 'horizontal' == $single_product_layout || 'vertical' == $single_product_layout ) {
			$attr = array_merge( $attr, alpha_get_slider_attrs( $settings, array( 'lg' => 1 ) ) );
		}
		return $attr;
	}

	public function extend_thumb_class( $classes ) {
		$settings              = $this->get_settings_for_display();
		$single_product_layout = $settings['sp_type'];

		if ( 'horizontal' == $single_product_layout && $settings['thumb_col_cnt'] && 4 != $settings['thumb_col_cnt'] ) {
			$col_cnt = alpha_get_responsive_cols( array( 'xlg' => $settings['thumb_col_cnt'] ) );
			$classes = alpha_get_col_class( $col_cnt );
		}
		return $classes;
	}


	public function extend_thumb_attr( $attrs ) {
		$settings              = $this->get_settings_for_display();
		$single_product_layout = $settings['sp_type'];

		if ( 'horizontal' == $single_product_layout && $settings['thumb_col_cnt'] && 4 != $settings['thumb_col_cnt'] ) {
			$max_breakpoints = alpha_get_breakpoints();
			$col_cnt         = alpha_get_responsive_cols( array( 'xlg' => $settings['thumb_col_cnt'] ) );
			foreach ( $col_cnt as $w => $c ) {
				$attrs['breakpoints'][ $max_breakpoints[ $w ] ] = array(
					'slidesPerView' => $c,
				);
			}
		}
		$attrs['spaceBetween'] = alpha_get_grid_space( isset( $settings['col_sp'] ) ? $settings['col_sp'] : '' );
		return $attrs;
	}

	public function extend_thumb_wrap_class( $classes ) {
		$settings = $this->get_settings_for_display();
		$classes  = ' gutter-' . ( $settings['col_sp'] ? $settings['col_sp'] : 'md' );
		return $classes;
	}

	public function extend_gallery_wrap_class( $classes, $layout ) {
		if ( 'horizontal' == $layout || 'vertical' == $layout || 'sticky-thumbs' == $layout ) {
			$settings  = $this->get_settings_for_display();
			$classes[] = 'gutter-' . ( $settings['col_sp'] ? $settings['col_sp'] : 'md' );
		}
		return $classes;
	}

	public function before_render() {
		// Add `elementor-widget-theme-post-content` class to avoid conflicts that figure gets zero margin.
		$this->add_render_attribute(
			array(
				'_wrapper' => array(
					'class' => 'elementor-widget-theme-post-content',
				),
			)
		);

		parent::before_render();
	}

	protected function render() {
		/**
		 * Filters post products in single product builder
		 *
		 * @since 1.0
		 */
		if ( apply_filters( 'alpha_single_product_builder_set_preview', false ) ) {
			$sp_type = $this->get_settings_for_display( 'sp_type' );

			add_filter( 'alpha_single_product_layout', array( $this, 'get_gallery_type' ), 99 );
			add_filter( 'alpha_single_product_gallery_main_classes', array( $this, 'extend_gallery_main_class' ), 20 );
			add_filter( 'alpha_single_product_gallery_class', array( $this, 'extend_gallery_class' ), 20 );
			add_filter( 'alpha_single_product_gallery_slider_attrs', array( $this, 'extend_gallery_attr' ), 20 );
			add_filter( 'alpha_single_product_thumbs_slider_classes', array( $this, 'extend_thumb_class' ), 20 );
			add_filter( 'alpha_single_product_thumbs_slider_attrs', array( $this, 'extend_thumb_attr' ), 20 );
			add_filter( 'alpha_single_product_thumbs_wrap_class', array( $this, 'extend_thumb_wrap_class' ), 20 );
			add_filter( 'alpha_single_product_gallery_classes', array( $this, 'extend_gallery_wrap_class' ), 20, 2 );

			woocommerce_show_product_images();

			remove_filter( 'alpha_single_product_layout', array( $this, 'get_gallery_type' ), 99 );
			remove_filter( 'alpha_single_product_gallery_main_classes', array( $this, 'extend_gallery_main_class' ), 20 );
			remove_filter( 'alpha_single_product_gallery_class', array( $this, 'extend_gallery_class' ), 20 );
			remove_filter( 'alpha_single_product_gallery_slider_attrs', array( $this, 'extend_gallery_attr' ), 20 );
			remove_filter( 'alpha_single_product_thumbs_slider_classes', array( $this, 'extend_thumb_class' ), 20 );
			remove_filter( 'alpha_single_product_thumbs_slider_attrs', array( $this, 'extend_thumb_attr' ), 20 );
			remove_filter( 'alpha_single_product_thumbs_wrap_class', array( $this, 'extend_thumb_wrap_class' ), 20 );
			remove_filter( 'alpha_single_product_gallery_classes', array( $this, 'extend_gallery_wrap_class' ), 20 );

			do_action( 'alpha_single_product_builder_unset_preview' );
		}
	}
}
