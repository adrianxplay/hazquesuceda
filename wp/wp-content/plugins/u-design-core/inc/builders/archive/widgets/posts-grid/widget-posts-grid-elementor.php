<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Elementor Shop Products Grid Widget to display products using type builder
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.1
 */

use Elementor\Controls_Manager;
use Elementor\Alpha_Controls_Manager;
use Elementor\Group_Control_Typography;

class Alpha_Archive_Posts_Grid_Elementor_Widget extends Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_archive_posts_grid';
	}

	public function get_title() {
		return esc_html__( 'Post Grid Archives', 'alpha-core' );
	}

	public function get_categories() {
		return array( 'alpha_shop_widget', 'alpha_archive_widget' );
	}

	public function get_keywords() {
		return array( 'products', 'shop', 'builder', 'template', 'post type', 'mini type', 'card', 'custom', 'portfolio', 'member', 'event', 'project' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-post-grid';
	}

	public function get_script_depends() {
		$depends = array( 'isotope-pkgd' );
		if ( alpha_is_elementor_preview() ) {
			$depends[] = 'alpha-elementor-js';
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

			if ( ! wp_style_is( 'alpha-product', 'registered' ) ) {
				wp_register_style( 'alpha-product', alpha_core_framework_uri( '/widgets/products/product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-theme-single-product', 'registered' ) ) {
				wp_register_style( 'alpha-theme-single-product', ALPHA_ASSETS . '/css/pages/single-product' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-product-category', 'registered' ) ) {
				wp_register_style( 'alpha-product-category', alpha_core_framework_uri( '/widgets/categories/category' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			if ( ! wp_style_is( 'alpha-post', 'registered' ) ) {
				wp_register_style( 'alpha-post', alpha_core_framework_uri( '/widgets/posts/post' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			}

			$depends = array( 'alpha-tab', 'alpha-product', 'alpha-theme-single-product', 'alpha-post', 'alpha-product-category', 'alpha-product-compare' );
		}
		if ( ! wp_style_is( 'alpha-type-builder', 'registered' ) ) {
			wp_register_style( 'alpha-type-builder', alpha_core_framework_uri( '/builders/type/type-builder' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
		}
		$depends[] = 'alpha-type-builder';
		return $depends;
	}

	protected function register_controls() {
		$is_shop_builder = function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() && 'shop_layout' === get_post_meta( get_the_ID(), ALPHA_NAME . '_template_type', true );

		$left  = is_rtl() ? 'right' : 'left';
		$right = 'left' === $left ? 'right' : 'left';

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
				'add_default' => true,
				'label_block' => true,
			)
		);

		if ( $is_shop_builder ) {
			$this->add_control(
				'list_builder_id',
				array(
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'label'       => __( 'Post Layout for List View', 'alpha-core' ),
					'description' => __( 'This is used in shop only.', 'alpha-core' ),
					'options'     => 'type',
					'label_block' => true,
					'add_default' => true,
				)
			);
		} else {
			$this->add_control(
				'list_builder_id',
				array(
					'type'  => Alpha_Controls_Manager::HIDDEN,
					'label' => __( 'Post Layout for List View', 'alpha-core' ),
				)
			);
		}

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
			)
		);

		$this->add_control(
			'orderway',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => __( 'Order', 'alpha-core' ),
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
					'creative' => array(
						'title' => esc_html__( 'Creative Grid', 'alpha-core' ),
						'icon'  => 'eicon-inner-section',
					),
					'masonry'  => array(
						'title' => esc_html__( 'Masonry', 'alpha-core' ),
						'icon'  => 'eicon-posts-masonry',
					),
				),
			)
		);

		alpha_elementor_grid_layout_controls( $this, 'view', true, 'posts-grid' );

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`
				'exclude' => array( 'custom' ),
				'default' => 'alpha-post-small',
			)
		);

		$this->add_control(
			'list_col_cnt',
			array(
				'label'       => esc_html__( 'Columns on List View', 'alpha-core' ),
				'description' => esc_html__( 'Select number of columns to display on desktop( >= 992px ). ', 'alpha-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					''  => 1,
					'2' => 2,
					'3' => 3,
				),
				'condition'   => array(
					'list_builder_id!' => '',
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
			$this->add_control(
				'loadmore_type',
				array(
					'label'   => esc_html__( 'Pagination Type', 'alpha-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => array(
						''       => esc_html__( 'Default', 'alpha-core' ),
						'button' => esc_html__( 'By button', 'alpha-core' ),
						'scroll' => esc_html__( 'By scroll', 'alpha-core' ),
					),
				)
			);

			$this->add_control(
				'loadmore_label',
				array(
					'label'       => esc_html__( 'Load More Label', 'alpha-core' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => esc_html__( 'Load More', 'alpha-core' ),
					'condition'   => array(
						'loadmore_type' => 'button',
					),
				)
			);
		}

		$this->add_control(
			'filter_cat',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Filter by Category', 'alpha-core' ),
				'description' => esc_html__( 'Defines whether to show or hide category filters above products.', 'alpha-core' ),
			)
		);

		if ( $is_shop_builder ) { // shop builder
			$this->add_control(
				'filter_cat_tax',
				array(
					'label'   => esc_html__( 'Taxonomy', 'alpha-core' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'product_cat',
				)
			);
		} else { // archive builder
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
			$this->add_control(
				'post_type',
				array(
					'type'        => Controls_Manager::SELECT,
					'label'       => __( 'Filter Post Type', 'alpha-core' ),
					'description' => __( 'Please select a post type of posts to select filter taxonomy.', 'alpha-core' ),
					'options'     => $post_types,
					'condition'   => array(
						'filter_cat' => 'yes',
					),
				)
			);
			$this->add_control(
				'filter_cat_tax',
				array(
					'type'        => Alpha_Controls_Manager::AJAXSELECT2,
					'label'       => __( 'Filter Taxonomy', 'alpha-core' ),
					'description' => __( 'Please select a post taxonomy to be used as category filter.', 'alpha-core' ),
					'options'     => '%post_type%_alltax',
					'label_block' => true,
					'condition'   => array(
						'filter_cat' => 'yes',
						'post_type!' => '',
					),
				)
			);
		}

		$this->add_control(
			'show_all_filter',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Show "All" Filter', 'alpha-core' ),
				'description' => __( 'You can display all filters.', 'alpha-core' ),
				'default'     => 'yes',
				'condition'   => array(
					'filter_cat' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_advanced_section',
			array(
				'label' => esc_html__( 'Advanced', 'alpha-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_found_nothing',
			array(
				'type'    => Controls_Manager::TEXTAREA,
				'label'   => esc_html__( 'Nothing Found Message', 'alpha-core' ),
				'default' => __( 'It seems we can\'t find what you\'re looking for.', 'alpha-core' ),
			)
		);

		$this->end_controls_section();

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
					'loadmore_type!' => array( 'button', 'scroll' ),
				),
			)
		);

		$this->add_control(
			'page_number_heading',
			array(
				'label' => esc_html__( 'Numbers', 'alpha-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'page_number_typography',
				'label'    => esc_html__( 'Typography', 'alpha-pro' ),
				'selector' => '{{WRAPPER}} .pagination .page-numbers:not(.prev, .next)',
			)
		);

		$this->add_responsive_control(
			'page_numbers_spacing',
			array(
				'label'      => esc_html__( 'Space Between', 'alpha-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.elementor-element-{{ID}} .pagination .page-numbers:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'page_numbers_bw',
			array(
				'label'      => esc_html__( 'Border Width', 'alpha-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'page_numbers_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_page_numbers' );

		$this->start_controls_tab(
			'tab_number_normal',
			array(
				'label' => esc_html__( 'Normal', 'alpha-pro' ),
			)
		);

		$this->add_control(
			'page_number_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next)' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_number_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_number_bd_color',
			array(
				'label'     => esc_html__( 'Border Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next)' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_number_hover',
			array(
				'label' => esc_html__( 'Hover/Active', 'alpha-pro' ),
			)
		);

		$this->add_control(
			'page_number_color_active',
			array(
				'label'     => esc_html__( 'Active Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next):hover, {{WRAPPER}} .pagination .page-numbers.current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_number_bg_color_active',
			array(
				'label'     => esc_html__( 'Active Background Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next):hover, {{WRAPPER}} .pagination .page-numbers.current' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_number_bd_color_active',
			array(
				'label'     => esc_html__( 'Active Border Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .page-numbers:not(.prev, .next):hover, {{WRAPPER}} .pagination .page-numbers.current' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'page_nav_heading',
			array(
				'label'     => esc_html__( 'Nav Icons', 'alpha-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'page_nav_size',
			array(
				'label'      => esc_html__( 'Size', 'alpha-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pagination .prev, {{WRAPPER}} .pagination .next' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bw',
			array(
				'label'      => esc_html__( 'Border Width', 'alpha-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pagination .prev, {{WRAPPER}} .pagination .next' => 'border: 0px solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'page_nav_br',
			array(
				'label'      => esc_html__( 'Border Radius', 'alpha-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pagination .prev, {{WRAPPER}} .pagination .next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_page_navs' );

		$this->start_controls_tab(
			'tab_nav_normal',
			array(
				'label' => esc_html__( 'Normal', 'alpha-pro' ),
			)
		);

		$this->add_control(
			'page_nav_color',
			array(
				'label'     => esc_html__( 'Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .prev, {{WRAPPER}} .pagination .next' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .prev, {{WRAPPER}} .pagination .next' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bd_color',
			array(
				'label'     => esc_html__( 'Border Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .prev, {{WRAPPER}} .pagination .next' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_hover',
			array(
				'label' => esc_html__( 'Hover', 'alpha-pro' ),
			)
		);

		$this->add_control(
			'page_nav_color_hover',
			array(
				'label'     => esc_html__( 'Hover Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .prev:hover, {{WRAPPER}} .pagination .next:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bg_color_hover',
			array(
				'label'     => esc_html__( 'Hover Background Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .prev:hover, {{WRAPPER}} .pagination .next:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bd_color_hover',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .prev:hover, {{WRAPPER}} .pagination .next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_disabled',
			array(
				'label' => esc_html__( 'Disabled', 'alpha-pro' ),
			)
		);

		$this->add_control(
			'page_nav_color_disabled',
			array(
				'label'     => esc_html__( 'Disabled Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .disabled' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bg_color_disabled',
			array(
				'label'     => esc_html__( 'Disabled Background Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .disabled' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_nav_bd_color_disabled',
			array(
				'label'     => esc_html__( 'Disabled Border Color', 'alpha-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pagination .disabled' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$this->add_responsive_control(
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

		$this->start_controls_section(
			'post_advanced_style',
			array(
				'label'     => esc_html__( 'Found Nothing', 'alpha-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_found_nothing!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nothing_msg_typography',
				'label'    => esc_html__( 'Typography', 'alpha-core' ),
				'selector' => '.elementor-element-{{ID}} .nothing-found-message',
			)
		);

		$this->add_control(
			'nothing_msg_color',
			array(
				'label'       => esc_html__( 'Color', 'alpha-core' ),
				'description' => esc_html__( 'Controls the color of the button.', 'alpha-core' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .nothing-found-message' => 'color: {{VALUE}};',
				),
			)
		);

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

		if ( is_singular( ALPHA_NAME . '_template' ) || ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] && ! empty( $_REQUEST['editor_post_id'] ) ) ) {
			$builder_id = is_singular( ALPHA_NAME . '_template' ) ? get_the_ID() : (int) $_REQUEST['editor_post_id'];
			$is_shop    = 'shop_layout' === get_post_meta( $builder_id, ALPHA_NAME . '_template_type', true ) ? true : false;

			if ( $is_shop ) {
				$atts['post_type'] = 'product';
				if ( empty( $atts['orderby'] ) ) {
					$atts['orderby'] = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
				}
				if ( empty( $atts['orderway'] ) ) {
					$atts['orderway'] = 'ASC';
				}
				if ( empty( $atts['count'] ) ) {
					$atts['count'] = apply_filters( 'loop_shop_per_page', get_option( 'posts_per_page', 12 ) );
				}
				if ( empty( $atts['loadmore_type'] ) || ! alpha_get_option( 'archive_ajax' ) ) {
					$atts['loadmore_type'] = 'no';
				}
			} else {
				$preview_post_type = get_post_meta( $builder_id, 'preview', true );
				$atts['post_type'] = $preview_post_type ? sanitize_text_field( $preview_post_type ) : 'post';
				$count             = get_option( 'posts_per_page', 12 );
				if ( function_exists( 'alpha_get_option' ) ) {
					if ( ALPHA_NAME . '_portfolio' == $atts['post_type'] ) {
						$count = alpha_get_option( 'portfolios_count' );
					} elseif ( ALPHA_NAME . '_member' == $atts['post_type'] ) {
						$count = alpha_get_option( 'members_count' );
					}
				}
				if ( empty( $atts['count'] ) ) {
					$atts['count'] = $count;
				}
				if ( ! alpha_get_option( 'archive_ajax' ) ) {
					$atts['loadmore_type'] = 'page';
				}
			}
			if ( empty( $atts['loadmore_type'] ) ) {
				$atts['loadmore_type'] = 'page';
			}
		} else {
			$atts['shortcode_type'] = 'archive';

			global $alpha_layout;
			if ( ! empty( $alpha_layout['shop_block'] ) && ( empty( $atts['loadmore_type'] ) || ! alpha_get_option( 'archive_ajax' ) ) ) {
				$atts['loadmore_type'] = 'no';
			}

			// Show pagination if it is not shop archive when archive ajax disabled.
			if ( empty( $alpha_layout['shop_block'] ) && ! alpha_get_option( 'archive_ajax' ) ) {
				$atts['loadmore_type'] = 'page';
			}
		}

		require alpha_core_framework_path( ALPHA_CORE_FRAMEWORK_PATH . '/widgets/posts-grid/render-posts-grid.php' );
	}

	protected function content_template() {}
}
