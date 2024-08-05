<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Posts Grid Widget
 *
 * Alpha Elementor widget to display posts or terms with the type built by post type builder.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpha_Posts_Grid_Elementor_Widget extends Elementor\Widget_Base {

	public function get_name() {
		return  ALPHA_NAME . '_widget_posts_grid';
	}

	public function get_title() {
		return __( 'Posts Grid', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'post', 'product', 'shop', 'term', 'category', 'taxonomy', 'type', 'card', 'builder', 'custom', 'portfolio', 'member', 'event', 'project' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-post';
	}

	public function get_script_depends() {
		$depends = array( 'swiper' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
			if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'compare_available' ) ) {
				wp_register_script( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ALPHA_JS_SUFFIX ), array( 'alpha-framework-async' ), ALPHA_CORE_VERSION, true );
				$depends[] = 'alpha-product-compare';
			}
		}
		return $depends;
	}

	/**
	 * Get Style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		$depends = array();
		if ( function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
			if ( ! wp_style_is( 'alpha-tab', 'registered' ) ) {
				wp_register_style( 'alpha-tab', alpha_core_framework_uri( '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-accordion', 'registered' ) ) {
				wp_register_style( 'alpha-accordion', alpha_core_framework_uri( '/widgets/accordion/accordion' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-product', 'registered' ) ) {
				wp_register_style( 'alpha-product', alpha_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-theme-single-product', 'registered' ) ) {
				wp_register_style( 'alpha-theme-single-product', ALPHA_ASSETS . '/css/pages/single-product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-post', 'registered' ) ) {
				wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-product-category', 'registered' ) ) {
				wp_register_style( 'alpha-product-category', alpha_core_framework_uri( '/widgets/categories/category' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( function_exists( 'alpha_get_option' ) && alpha_get_option( 'compare_available' ) ) {
				wp_register_style( 'alpha-product-compare', alpha_core_framework_uri( '/addons/product-compare/product-compare' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_VERSION );
			}

			$depends = array( 'alpha-tab', 'alpha-accordion', 'alpha-product', 'alpha-theme-single-product', 'alpha-post', 'alpha-product-category', 'alpha-product-compare' );
		}
		if ( ! wp_style_is( 'alpha-type-builder', 'registered' ) ) {
			wp_register_style( 'alpha-type-builder', alpha_core_framework_uri( '/builders/type/type-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		}
		$depends[] = 'alpha-type-builder';

		return $depends;
	}

	protected function register_controls() {
		$post_types          = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			),
			'objects',
			'and'
		);
		$disabled_post_types = array( 'attachment', ALPHA_NAME . '_template', 'page', 'e-landing-page' );
		foreach ( $disabled_post_types as $disabled ) {
			unset( $post_types[ $disabled ] );
		}
		foreach ( $post_types as $key => $p_type ) {
			$post_types[ $key ] = esc_html( $p_type->label );
		}
		$post_types = apply_filters( 'alpha_posts_grid_post_types', $post_types );

		$taxes = get_taxonomies( array(), 'objects' );
		unset( $taxes['post_format'], $taxes['product_visibility'] );
		foreach ( $taxes as $tax_name => $tax ) {
			$taxes[ $tax_name ] = esc_html( $tax->label );
		}
		$taxes = apply_filters( 'alpha_posts_grid_taxonomies', $taxes );

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' === $left ? 'right' : 'left';

		$status_values = array(
			''         => __( 'All', 'alpha-core' ),
			'featured' => __( 'Featured', 'alpha-core' ),
			'on_sale'  => __( 'On Sale', 'alpha-core' ),
			'viewed'   => __( 'Recently Viewed', 'alpha-core' ),
		);

		$this->start_controls_section(
			'section_selector',
			array(
				'label' => __( 'Posts Selector', 'alpha-core' ),
			)
		);

		$this->add_control(
			'builder_id',
			array(
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'label'       => __( 'Post Layout', 'alpha-core' ),
				/* translators: starting and ending A tag which redirects to post type builder. */
				'description' => sprintf( __( 'Please select a saved Post Layout template which was built using post type builder. Please create a new Post Layout template in %1$sTemplates Builder%2$s', 'alpha-core' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=' . ALPHA_NAME . '_template&' . ALPHA_NAME . '_template_type=type' ) ) . '">', '</a>' ),
				'options'     => 'type',
				'label_block' => true,
				'add_default' => true,
			)
		);

		$this->add_control(
			'source',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Content Source', 'alpha-core' ),
				'options'     => array(
					''      => __( 'Posts', 'alpha-core' ),
					'terms' => __( 'Terms', 'alpha-core' ),
				),
				'description' => __( 'Please select the content type which you would like to show.', 'alpha-core' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'post_type',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Post Type', 'alpha-core' ),
				'description' => __( 'Please select a post type of posts to display.', 'alpha-core' ),
				'options'     => $post_types,
				'condition'   => array(
					'source' => '',
				),
			)
		);

		$this->add_control(
			'product_status',
			array(
				'label'     => __( 'Product Status', 'alpha-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $status_values,
				'condition' => array(
					'source'    => '',
					'post_type' => 'product',
				),
			)
		);

		$this->add_control(
			'post_tax',
			array(
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'label'       => __( 'Taxonomy', 'alpha-core' ),
				'description' => __( 'Please select a post taxonomy to pull posts from.', 'alpha-core' ),
				'options'     => '%post_type%_alltax',
				'label_block' => true,
				'add_default' => true,
				'condition'   => array(
					'source' => '',
				),
			)
		);

		$this->add_control(
			'post_terms',
			array(
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'label'       => __( 'Terms', 'alpha-core' ),
				'description' => __( 'Please select a post terms to pull posts from.', 'alpha-core' ),
				'options'     => '%post_tax%_allterm',
				'multiple'    => 'true',
				'label_block' => true,
				'condition'   => array(
					'source'    => '',
					'post_tax!' => '',
				),
			)
		);

		$this->add_control(
			'tax',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Taxonomy', 'alpha-core' ),
				'description' => __( 'Please select a taxonomy to use.', 'alpha-core' ),
				'options'     => $taxes,
				'condition'   => array(
					'source' => 'terms',
				),
				'default'     => '',
			)
		);

		$this->add_control(
			'terms',
			array(
				'type'        => Alpha_Controls_Manager::AJAXSELECT2,
				'label'       => __( 'Terms', 'alpha-core' ),
				'description' => __( 'Please select terms to display.', 'alpha-core' ),
				'options'     => '%tax%_allterm',
				'multiple'    => 'true',
				'label_block' => true,
				'condition'   => array(
					'source' => 'terms',
					'tax!'   => '',
				),
			)
		);

		$this->add_control(
			'count',
			array(
				'type'  => Controls_Manager::SLIDER,
				'label' => __( 'Count', 'alpha-core' ),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 100,
					),
				),
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => __( 'Hide empty', 'alpha-core' ),
				'condition' => array(
					'source' => 'terms',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => __( 'Order by', 'alpha-core' ),
				'options'     => array(
					''               => esc_html__( 'Default', 'alpha-core' ),
					'ID'             => esc_html__( 'ID', 'alpha-core' ),
					'title'          => esc_html__( 'Name', 'alpha-core' ),
					'date'           => esc_html__( 'Date', 'alpha-core' ),
					'modified'       => esc_html__( 'Modified', 'alpha-core' ),
					'price'          => esc_html__( 'Price', 'alpha-core' ),
					'rand'           => esc_html__( 'Random', 'alpha-core' ),
					'rating'         => esc_html__( 'Rating', 'alpha-core' ),
					'comment_count'  => esc_html__( 'Comment count', 'alpha-core' ),
					'popularity'     => esc_html__( 'Total Sales', 'alpha-core' ),
					'wishqty'        => esc_html__( 'Wish', 'alpha-core' ),
					'sale_date_to'   => esc_html__( 'Sale End Date', 'alpha-core' ),
					'sale_date_from' => esc_html__( 'Sale Start Date', 'alpha-core' ),
				),
				'description' => __( 'Price, Rating, Total Sales, Wish, Sale End Date and Sale Start Date values work for only product post type.', 'alpha-core' ),
				'condition'   => array(
					'source' => '',
				),
			)
		);

		$this->add_control(
			'orderby_term',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Order by', 'alpha-core' ),
				'options'   => array(
					''            => __( 'Default', 'alpha-core' ),
					'name'        => __( 'Title', 'alpha-core' ),
					'term_id'     => __( 'ID', 'alpha-core' ),
					'count'       => __( 'Post Count', 'alpha-core' ),
					'none'        => __( 'None', 'alpha-core' ),
					'parent'      => __( 'Parent', 'alpha-core' ),
					'description' => __( 'Description', 'alpha-core' ),
					'term_group'  => __( 'Term Group', 'alpha-core' ),
				),
				'default'   => '',
				'condition' => array(
					'source' => 'terms',
				),
			)
		);

		$this->add_control(
			'orderway',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => __( 'Order', 'alpha-core' ),
				'default'     => 'ASC',
				'options'     => array(
					'ASC'  => array(
						'title' => esc_html__( 'Ascending', 'alpha-core' ),
						'icon'  => 'alpha-order-asc alpha-choose-type',
					),
					'DESC' => array(
						'title' => esc_html__( 'Descending', 'alpha-core' ),
						'icon'  => 'alpha-order-desc alpha-choose-type',
					),
				),
				/* translators: %s: Wordpres codex page */
				'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'alpha-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Posts Layout', 'alpha-core' ),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => __( 'View', 'alpha-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'grid',
				'toggle'  => false,
				'options' => array(
					'grid'     => array(
						'title' => esc_html__( 'Grid', 'alpha-core' ),
						'icon'  => 'eicon-column',
					),
					'slider'   => array(
						'title' => esc_html__( 'Slider', 'alpha-core' ),
						'icon'  => 'eicon-slider-3d',
					),
					'creative' => array(
						'title' => esc_html__( 'Creative Grid', 'alpha-core' ),
						'icon'  => 'eicon-inner-section',
					),
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
				'exclude' => [ 'custom' ],
				'default' => 'alpha-post-small',
			)
		);

		alpha_elementor_grid_layout_controls( $this, 'view', true, 'posts-grid' );

		alpha_elementor_slider_layout_controls( $this, 'view' );

		$this->add_control(
			'hover_full_image',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Show Full Image on Hover', 'alpha-core' ),
				'description' => esc_html__( 'Show featured image in full container.', 'alpha-core' ),
				'condition'   => array(
					'builder_id!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_filter_section',
			array(
				'label' => esc_html__( 'Post Load', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! alpha_get_option( 'archive_ajax' ) ) {
			$this->add_control(
				'notice_post_ajax',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'Please enable Ajax Filter option %1$sTheme Options / Features / Ajax Filter%2$s.', 'alpha-core' ), '<a href="' . esc_url( admin_url( 'customize.php#ajax_filter' ) ) . '" target="_blank">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		} else {
			alpha_elementor_loadmore_layout_controls( $this, 'view' );
		}

			$this->add_control(
				'filter_cat',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Filter by Category', 'alpha-core' ),
					'description' => esc_html__( 'Defines whether to show or hide category filters above products.', 'alpha-core' ),
					'condition'   => array(
						'source' => '',
					),
				)
			);

			$this->add_control(
				'filter_cat_tax',
				array(
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'label'       => __( 'Taxonomy', 'alpha-core' ),
					'description' => __( 'Please select a post taxonomy to be used as category filter.', 'alpha-core' ),
					'options'     => '%post_type%_alltax',
					'label_block' => true,
					'add_default' => true,
					'condition'   => array(
						'source'     => '',
						'filter_cat' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_all_filter',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Show "All" Filter', 'alpha-core' ),
					'default'   => 'yes',
					'condition' => array(
						'source'     => '',
						'filter_cat' => 'yes',
					),
				)
			);

		$this->end_controls_section();

		alpha_elementor_slider_style_controls( $this, 'view' );

		$this->start_controls_section(
			'filter_style',
			array(
				'label'     => esc_html__( 'Filters Style', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'filter_cat' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography',
				'label'    => esc_html__( 'Typography', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .nav-filters .nav-filter',
			)
		);

		$this->add_control(
			'filter_align',
			array(
				'label'       => esc_html__( 'Alignment', 'alpha-core' ),
				'description' => esc_html__( 'Controls filters\'s alignment. Choose from Left, Center, Right.', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
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
				'selectors'   => array(
					'.elementor-element-{{ID}} .nav-filters' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'nav_padding',
			array(
				'label'       => esc_html__( 'Padding', 'alpha-core' ),
				'description' => esc_html__( 'Set custom padding of tab navs.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
					'em',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .post-filters .nav-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'nav_bd_width',
			array(
				'label'       => esc_html__( 'Border Width', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border width of navs.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'em',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .post-filters .nav-filter' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'filter_cats' );

		$this->start_controls_tab(
			'filter_normal',
			array(
				'label' => esc_html__( 'Normal', 'alpha-core' ),
			)
		);

			$this->add_control(
				'filter_normal_color',
				array(
					'label'       => esc_html__( 'Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'filter_normal_bg_color',
				array(
					'label'       => esc_html__( 'Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the background color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'filter_normal_bd_color',
				array(
					'label'       => esc_html__( 'Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the border color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter' => 'border-color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_hover',
			array(
				'label' => esc_html__( 'Hover', 'alpha-core' ),
			)
		);

			$this->add_control(
				'filter_hover_color',
				array(
					'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'filter_hover_bg_color',
				array(
					'label'       => esc_html__( 'Hover Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover background color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'filter_hover_bd_color',
				array(
					'label'       => esc_html__( 'Hover Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the hover border color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_active',
			array(
				'label' => esc_html__( 'Active', 'alpha-core' ),
			)
		);

			$this->add_control(
				'filter_active_color',
				array(
					'label'       => esc_html__( 'Active Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the active color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter.active' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'filter_active_bg_color',
				array(
					'label'       => esc_html__( 'Active Background Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the active background color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter.active' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'filter_active_bd_color',
				array(
					'label'       => esc_html__( 'Active Border Color', 'alpha-core' ),
					'description' => esc_html__( 'Controls the active border color of the filters.', 'alpha-core' ),
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array(
						'.elementor-element-{{ID}} .nav-filters .nav-filter.active' => 'border-color: {{VALUE}};',
					),
				)
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'filter_between_spacing',
			array(
				'label'       => esc_html__( 'Space Between (px)', 'alpha-core' ),
				'description' => esc_html__( 'Controls the spacing between filters.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} ul.nav-filters li:not(:last-child)' => "margin-{$right}: {{SIZE}}{{UNIT}};",
					'.elementor-element-{{ID}} .nav-filters li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_spacing',
			array(
				'label'       => esc_html__( 'Bottom Spacing (px)', 'alpha-core' ),
				'description' => esc_html__( 'Controls the spacing of the filters.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} ul.nav-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'pagination_style',
			array(
				'label'     => esc_html__( 'Pagination Style', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'loadmore_type' => '',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_align',
			array(
				'label'       => esc_html__( 'Horizontal Align', 'alpha-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'description' => esc_html__( 'Control the horizontal align of pagination part.', 'alpha-core' ),
				'options'     => array(
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
				'selectors'   => array(
					'.elementor-element-{{ID}} .pagination' => 'justify-content:{{VALUE}}',
				),
				'condition'   => array(
					'loadmore_type!' => 'button',
				),
			)
		);

		$this->add_control(
			'pagination_margin',
			array(
				'label'       => esc_html__( 'Margin', 'alpha-core' ),
				'description' => esc_html__( 'Set custom margin of pagination part.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
					'em',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .pagination, .elementor-element-{{ID}} .btn.btn-load' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'load_more_style',
			array(
				'label'     => esc_html__( 'Load More Button Style', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'loadmore_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'load_more_typography',
				'label'    => esc_html__( 'Typography', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .btn.btn-load',
			)
		);

		$this->add_control(
			'load_more_padding',
			array(
				'label'       => esc_html__( 'Padding', 'alpha-core' ),
				'description' => esc_html__( 'Controls padding value of button.', 'alpha-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'%',
					'em',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'load_more_spacing',
			array(
				'label'       => esc_html__( 'Spacing (px)', 'alpha-core' ),
				'description' => esc_html__( 'Controls the spacing of load more button.', 'alpha-core' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_btn_cat' );

		$this->start_controls_tab(
			'tab_btn_normal',
			array(
				'label' => esc_html__( 'Normal', 'alpha-core' ),
			)
		);

		$this->add_control(
			'load_more_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_back_color',
			array(
				'label'       => esc_html__( 'Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the background color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_border_color',
			array(
				'label'       => esc_html__( 'Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the border color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			array(
				'label' => esc_html__( 'Hover', 'alpha-core' ),
			)
		);

		$this->add_control(
			'load_more_color_hover',
			array(
				'label'       => esc_html__( 'Hover Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the hover color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_back_color_hover',
			array(
				'label'       => esc_html__( 'Hover Background Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the hover background color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_border_color_hover',
			array(
				'label'       => esc_html__( 'Hover Border Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the hover border color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .btn.btn-load:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		if ( is_array( $atts['count'] ) ) {
			if ( isset( $atts['count']['size'] ) ) {
				$atts['count'] = $atts['count']['size'];
			} else {
				$atts['count'] = '';
			}
		}

		if ( is_array( $atts['col_cnt'] ) ) {
			if ( isset( $atts['col_cnt']['size'] ) ) {
				$atts['col_cnt'] = $atts['col_cnt']['size'];
			} else {
				$atts['col_cnt'] = '';
			}
		}
		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/posts-grid/render-posts-grid.php' );
	}
}
