<?php
/**
 * Alpha Customizer
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Customizer_Extend' ) ) :

	class Alpha_Customizer_Extend extends Alpha_Base {

		/**
		 * Constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			add_filter( 'alpha_customize_page_links', array( $this, 'add_page_links' ) );
			add_filter( 'alpha_customize_panels', array( $this, 'extend_panels' ) );
			add_filter( 'alpha_customize_sections', array( $this, 'extend_sections' ) );
			add_filter( 'alpha_customize_fields', array( $this, 'extend_fields' ), 20 );
			add_action( 'customize_controls_print_styles', array( $this, 'load_extend_style' ), 30 );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_selective_assets' ) );
			add_action( 'customize_save_after', array( $this, 'save_permalinks' ) );
		}


		/**
		 * load selective refresh JS
		 *
		 * @since 4.0
		 */
		public function load_selective_assets() {
			wp_enqueue_script( 'alpha-selective-extend', ALPHA_INC_URI . '/admin/customizer/selective-refresh-extend' . ALPHA_JS_SUFFIX, array( 'jquery-core', 'alpha-selective' ), ALPHA_VERSION, true );
		}

		public function load_extend_style() {
			wp_enqueue_style( 'alpha-customizer-extend', ALPHA_INC_URI . '/admin/customizer/customizer-extend' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_VERSION, 'all' );
		}

		public function save_permalinks() {
			flush_rewrite_rules();
		}

		/**
		 * Add page links to each control
		 *
		 * @since 4.0.0
		 */
		public function add_page_links( $links ) {

			$archive_url                = esc_js( get_post_type_archive_link( ALPHA_NAME . '_portfolio' ) );
			$links['portfolio']         = array(
				'url'      => $archive_url,
				'is_panel' => true,
			);
			$links['portfolio_global']  = array(
				'url'      => $archive_url,
				'is_panel' => false,
			);
			$links['portfolio_archive'] = array(
				'url'      => $archive_url,
				'is_panel' => false,
			);

			$post = get_posts(
				array(
					'post_type'      => ALPHA_NAME . '_portfolio',
					'posts_per_page' => 1,
				)
			);
			if ( is_array( $post ) && count( $post ) ) {
				$links['portfolio_single'] = array(
					'url'      => esc_js( get_permalink( $post[0] ) ),
					'is_panel' => false,
				);
			}

			$archive_url             = esc_js( get_post_type_archive_link( ALPHA_NAME . '_member' ) );
			$links['member']         = array(
				'url'      => $archive_url,
				'is_panel' => true,
			);
			$links['member_global']  = array(
				'url'      => $archive_url,
				'is_panel' => false,
			);
			$links['member_archive'] = array(
				'url'      => $archive_url,
				'is_panel' => false,
			);

			$post = get_posts(
				array(
					'post_type'      => ALPHA_NAME . '_member',
					'posts_per_page' => 1,
				)
			);
			if ( is_array( $post ) && count( $post ) ) {
				$links['member_single'] = array(
					'url'      => esc_js( get_permalink( $post[0] ) ),
					'is_panel' => false,
				);
			}

			if ( class_exists( 'Woocommerce' ) ) {
				$shop_url = esc_url( wc_get_page_permalink( 'shop' ) );

				$links['ajax_filter'] = array(
					'url'      => $shop_url,
					'is_panel' => false,
				);
			}

			return $links;
		}


		/**
		 * Extend panels
		 *
		 * @since 4.0
		 */
		public function extend_panels( $panels ) {

			$panels['blog'] = array(
				'title'    => esc_html__( 'Blog', 'alpha' ),
				'priority' => 50,
			);

			$panels['portfolio'] = array(
				'title'    => esc_html__( 'Portfolio', 'alpha' ),
				'priority' => 53,
			);

			$panels['member'] = array(
				'title'    => esc_html__( 'Member', 'alpha' ),
				'priority' => 55,
			);

			$panels['page_header'] = array(
				'title'    => esc_html__( 'Page Header', 'alpha' ),
				'priority' => 35,
			);

			return $panels;
		}

		/**
		 * Extend sections
		 *
		 * @since 4.0
		 */
		public function extend_sections( $sections ) {

			$sections['color']['title'] = esc_html__( 'Skin & Color', 'alpha' );

			unset( $sections['blog'] );
			// Blog / Blog Global
			$sections['blog_global'] = array(
				'title'    => esc_html__( 'Blog Global', 'alpha' ),
				'panel'    => 'blog',
				'priority' => 10,
			);
			// Blog / Blog Page
			$sections['blog_archive'] = array(
				'title'    => esc_html__( 'Blog Page', 'alpha' ),
				'panel'    => 'blog',
				'priority' => 20,
			);
			// Blog / Single Post Page
			$sections['blog_single'] = array(
				'title'    => esc_html__( 'Single Post Page', 'alpha' ),
				'panel'    => 'blog',
				'priority' => 30,
			);
			// Portfolio / Portfolio Global
			$sections['portfolio_global'] = array(
				'title'    => esc_html__( 'Portfolio Global', 'alpha' ),
				'priority' => 10,
				'panel'    => 'portfolio',
			);
			// Portfolio / Archive Portfolio
			$sections['portfolio_archive'] = array(
				'title'    => esc_html__( 'Portfolios Page', 'alpha' ),
				'priority' => 20,
				'panel'    => 'portfolio',
			);
			// Portfolio / Single Portfolio
			$sections['portfolio_single'] = array(
				'title'    => esc_html__( 'Single Portfolio Page', 'alpha' ),
				'priority' => 30,
				'panel'    => 'portfolio',
			);

			// Member / Member Global
			$sections['member_global'] = array(
				'title'    => esc_html__( 'Member Global', 'alpha' ),
				'priority' => 10,
				'panel'    => 'member',
			);
			// Member / Archive Member
			$sections['member_archive'] = array(
				'title'    => esc_html__( 'Members Page', 'alpha' ),
				'priority' => 20,
				'panel'    => 'member',
			);
			// Member / Single Member
			$sections['member_single'] = array(
				'title'    => esc_html__( 'Single Member Page', 'alpha' ),
				'priority' => 30,
				'panel'    => 'member',
			);
			// Woocommerce / Category Type
			$sections['product_type']                = array(
				'title'    => esc_html__( 'Product Type', 'alpha' ),
				'panel'    => 'woocommerce',
				'priority' => 0,
			);
			$sections['category_type']               = array(
				'title'    => esc_html__( 'Category Type', 'alpha' ),
				'panel'    => 'woocommerce',
				'priority' => 10,
			);
			$sections['woo_compare']                 = array(
				'title'    => esc_html__( 'Compare', 'alpha' ),
				'panel'    => 'woocommerce',
				'priority' => 15,
			);
			$sections['woocommerce_store_notice']    = array(
				'title'    => esc_html__( 'Store Notice', 'alpha' ),
				'panel'    => 'woocommerce',
				'priority' => 15,
			);
			$sections['woocommerce_product_catalog'] = array(
				'title'    => esc_html__( 'Product Catalog', 'alpha' ),
				'panel'    => 'woocommerce',
				'priority' => 15,
			);
			// Page Header / Page Title Bar
			$sections['title_bar'] = array(
				'title'    => esc_html__( 'Page Title Bar', 'alpha' ),
				'panel'    => 'page_header',
				'priority' => 10,
			);

			// Page Header / Breadcrumb
			$sections['breadcrumb'] = array(
				'title'    => esc_html__( 'Breadcrumb', 'alpha' ),
				'priority' => 40,
				'panel'    => 'page_header',
			);

			unset( $sections['maintenance'] );

			return $sections;
		}

		/**
		 * Extend fields
		 *
		 * @since 4.0
		 */
		public function extend_fields( $fields ) {

			for ( $x = 1; $x <= 9; $x++ ) {
				$opts_dividers[ $x ] = ALPHA_ASSETS . '/images/shapes/shape' . $x . '.jpg';
			}

			$social_shares      = alpha_get_social_shares();
			$social_shares_list = array();
			foreach ( $social_shares as $share => $data ) {
				$social_shares_list[ $share ] = $data['title'];
			}

			$posts_load = 'timeline' == alpha_get_option( 'posts_layout' ) ? array(
				'button' => ALPHA_ASSETS . '/images/options/loadmore-btn.png',
				''       => ALPHA_ASSETS . '/images/options/loadmore-page.png',
			) : array(
				'button' => ALPHA_ASSETS . '/images/options/loadmore-btn.png',
				''       => ALPHA_ASSETS . '/images/options/loadmore-page.png',
				'scroll' => ALPHA_ASSETS . '/images/options/loadmore-scroll.png',
			);

			$fields = array_replace_recursive(
				$fields,
				array(
					// posts layout
					'cs_post_type'               => array(
						'section'  => 'blog_global',
						'type'     => 'custom',
						'label'    => '<h3 class="options-custom-title">' . esc_html__( 'Post Type', 'alpha' ) . '</h3>',
						'priority' => 5,
					),
					'post_type'                  => array(
						'section'  => 'blog_global',
						'type'     => 'radio_image',
						'label'    => '',
						'ms_label' => esc_html__( 'Post Type', 'alpha' ),
						'choices'  => apply_filters(
							'alpha_post_types',
							array(
								'default' => ALPHA_ASSETS . '/images/options/post/default.jpg',
								'mask'    => ALPHA_ASSETS . '/images/options/post/mask.jpg',
								'list'    => ALPHA_ASSETS . '/images/options/post/list.jpg',
							),
							'theme'
						),
						'priority' => 5,
					),
					'post_overlay'               => array(
						'section'  => 'blog_global',
						'type'     => 'select',
						'label'    => esc_html__( 'Hover Effect', 'alpha' ),
						'choices'  => array(
							''           => esc_html__( 'None', 'alpha' ),
							'light'      => esc_html__( 'Light', 'alpha' ),
							'dark'       => esc_html__( 'Dark', 'alpha' ),
							'zoom'       => esc_html__( 'Zoom', 'alpha' ),
							'zoom_light' => esc_html__( 'Zoom and Light', 'alpha' ),
							'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha' ),
						),
						'priority' => 5,
					),
					'cs_post_excerpt'            => array(
						'section' => 'blog_global',
						'type'    => 'custom',
						'label'   => '',
						'default' => '<h3 class="options-custom-title">' . esc_html__( 'Excerpt', 'alpha' ) . '</h3>',
					),
					'excerpt_type'               => array(
						'section'  => 'blog_global',
						'type'     => 'radio_buttonset',
						'label'    => esc_html__( 'Type', 'alpha' ),
						'ms_label' => esc_html__( 'Excerpt Type', 'alpha' ),
						'choices'  => array(
							''          => esc_html__( 'Word', 'alpha' ),
							'character' => esc_html__( 'Letter', 'alpha' ),
						),
					),
					'excerpt_length'             => array(
						'section'  => 'blog_global',
						'type'     => 'number',
						'label'    => esc_html__( 'Length', 'alpha' ),
						'ms_label' => esc_html__( 'Excerpt Length', 'alpha' ),
						'choices'  => array(
							'min' => 0,
							'max' => 250,
						),
					),

					// Blog / Blog Page
					'cs_posts_title'             => array(
						'section' => 'blog_archive',
						'type'    => 'custom',
						'label'   => '<h3 class="options-custom-title">' . esc_html__( 'Blog', 'alpha' ) . '</h3>',
					),
					'cs_posts_alert'             => array(
						'section' => 'blog_archive',
						'type'    => 'custom',
						'label'   => '<p class="options-description"><span>Warning: </span>' . sprintf( esc_html__( 'Layout builder\'s "%1$sBlog Page%2$s / Content / Options" is prior than this theme options in blog page.', 'alpha' ), '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder&layout=archive_post' ) ) . '">', '</a>' ) . '</p>',
					),
					'posts_layout'               => array(
						'section' => 'blog_archive',
						'type'    => 'radio_buttonset',
						'label'   => esc_html__( 'Layout', 'alpha' ),
						'tooltip' => esc_html__( 'Masonry layout will use uncropped images.', 'alpha' ),
						'choices' => array(
							'grid'     => esc_html__( 'Grid', 'alpha' ),
							'masonry'  => esc_html__( 'Masonry', 'alpha' ),
							'timeline' => esc_html__( 'Timeline', 'alpha' ),
						),
					),
					'posts_column'               => array(
						'section'         => 'blog_archive',
						'type'            => 'number',
						'label'           => esc_html__( 'Column', 'alpha' ),
						'choices'         => array(
							'min' => 1,
							'max' => 8,
						),
						'active_callback' => array(
							array(
								'setting'  => 'posts_layout',
								'operator' => '!=',
								'value'    => 'timeline',
							),
						),
					),
					'posts_filter'               => array(
						'section' => 'blog_archive',
						'type'    => 'toggle',
						'label'   => esc_html__( 'Filter By Category', 'alpha' ),
					),
					'posts_load'                 => array(
						'section' => 'blog_archive',
						'type'    => 'radio_image',
						'label'   => esc_html__( 'Load More', 'alpha' ),
						'choices' => array(
							'button' => ALPHA_ASSETS . '/images/options/loadmore-btn.png',
							''       => ALPHA_ASSETS . '/images/options/loadmore-page.png',
							'scroll' => ALPHA_ASSETS . '/images/options/loadmore-scroll.png',
						),
					),
					// Blog / Blog Single
					'cs_post_title'              => array(
						'section'  => 'blog_single',
						'type'     => 'custom',
						'label'    => '<h3 class="options-custom-title">' . esc_html__( 'Show Information', 'alpha' ) . '</h3>',
						'priority' => 4,
					),
					'post_show_info'             => array(
						'section'  => 'blog_single',
						'type'     => 'multicheck',
						'label'    => esc_html__( 'Items to show', 'alpha' ),
						'choices'  => array(
							'image'         => esc_html__( 'Media', 'alpha' ),
							'author'        => esc_html__( 'Meta Author', 'alpha' ),
							'date'          => esc_html__( 'Meta Date', 'alpha' ),
							'comment'       => esc_html__( 'Meta Comments Count', 'alpha' ),
							'category'      => esc_html__( 'Category', 'alpha' ),
							'tag'           => esc_html__( 'Tags', 'alpha' ),
							'author_info'   => esc_html__( 'Author Information', 'alpha' ),
							'share'         => esc_html__( 'Share', 'alpha' ),
							'navigation'    => esc_html__( 'Prev and Next', 'alpha' ),
							'related'       => esc_html__( 'Related Posts', 'alpha' ),
							'comments_list' => esc_html__( 'Comments', 'alpha' ),
						),
						'priority' => 5,
					),
					'cs_post_related_title'      => array(
						'section' => 'blog_single',
						'type'    => 'custom',
						'label'   => '<h3 class="options-custom-title">' . esc_html__( 'Related Posts', 'alpha' ) . '</h3>',
					),
					'post_related_count'         => array(
						'section'  => 'blog_single',
						'type'     => 'number',
						'label'    => esc_html__( 'Count', 'alpha' ),
						'ms_label' => esc_html__( 'Related Count', 'alpha' ),
						'choices'  => array(
							'min' => 1,
							'max' => 50,
						),
					),
					'post_related_column'        => array(
						'section'  => 'blog_single',
						'type'     => 'number',
						'label'    => esc_html__( 'Column', 'alpha' ),
						'ms_label' => esc_html__( 'Related Column', 'alpha' ),
						'choices'  => array(
							'min' => 1,
							'max' => 6,
						),
					),
					'post_related_order'         => array(
						'section' => 'blog_single',
						'type'    => 'select',
						'label'   => esc_html__( 'Order By', 'alpha' ),
						'choices' => array(
							''              => esc_html__( 'Default', 'alpha' ),
							'ID'            => esc_html__( 'ID', 'alpha' ),
							'title'         => esc_html__( 'Title', 'alpha' ),
							'date'          => esc_html__( 'Date', 'alpha' ),
							'modified'      => esc_html__( 'Modified', 'alpha' ),
							'author'        => esc_html__( 'Author', 'alpha' ),
							'comment_count' => esc_html__( 'Comment count', 'alpha' ),
						),
					),
					'posts_related_orderway'     => array(
						'section' => 'blog_single',
						'type'    => 'radio_buttonset',
						'label'   => esc_html__( 'Order Way', 'alpha' ),
						'choices' => array(
							'ASC' => esc_html__( 'ASC', 'alpha' ),
							''    => esc_html__( 'DESC', 'alpha' ),
						),
					),
					// General / Apperance
					'cs_page_transition_title'   => array(
						'section'   => 'appearance',
						'type'      => 'custom',
						'label'     => '',
						'default'   => '<h3 class="options-custom-title">' . esc_html__( 'Page Transition', 'alpha' ) . '</h3>',
						'transport' => 'postMessage',
					),
					'page_transition'            => array(
						'section' => 'appearance',
						'type'    => 'radio-buttonset',
						'label'   => esc_html__( 'Page Transition Effect', 'alpha' ),
						'tooltip' => esc_html__( 'Choose a type of transition between loading pages.', 'alpha' ),
						'choices' => array(
							''      => esc_html__( 'None', 'alpha' ),
							'fade'  => esc_html__( 'Fade', 'alpha' ),
							'slide' => esc_html__( 'Slide', 'alpha' ),
						),
					),
					'page_transition_bg'         => array(
						'section'         => 'appearance',
						'type'            => 'color',
						'label'           => esc_html__( 'Page Transition Background', 'alpha' ),
						'tooltip'         => esc_html__( 'Use this to define the color of your page transition background.', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'page_transition',
								'operator' => '!=',
								'value'    => '',
							),
						),
						'transport'       => 'postMessage',
					),
					'cs_preloader_title'         => array(
						'section'   => 'appearance',
						'type'      => 'custom',
						'label'     => '',
						'default'   => '<h3 class="options-custom-title">' . esc_html__( 'Preloader', 'alpha' ) . '</h3>',
						'transport' => 'postMessage',
					),
					'preloader'                  => array(
						'section' => 'appearance',
						'type'    => 'select',
						'label'   => esc_html__( 'Preloader', 'alpha' ),
						'tooltip' => esc_html__( 'Choose type and color of preload graphic animation', 'alpha' ),
						'choices' => array(
							''            => esc_html__( 'None', 'alpha' ),
							'preloader-1' => esc_html__( 'Loader 1', 'alpha' ),
							'preloader-2' => esc_html__( 'Loader 2', 'alpha' ),
							'preloader-3' => esc_html__( 'Loader 3', 'alpha' ),
							'preloader-4' => esc_html__( 'Loader 4', 'alpha' ),
							'preloader-5' => esc_html__( 'Loader 5', 'alpha' ),
						),
					),
					'preloader_color'            => array(
						'section'         => 'appearance',
						'type'            => 'color',
						'label'           => esc_html__( 'Preloader Color', 'alpha' ),
						'tooltip'         => esc_html__( 'Use this to define the color of your preloaders.', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'preloader',
								'operator' => '!=',
								'value'    => '',
							),
						),
						'transport'       => 'postMessage',
					),

					'cs_skin_title'              => array(
						'section'  => 'color',
						'type'     => 'custom',
						'label'    => '',
						'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Skin', 'alpha' ) . '</h3>',
						'priority' => 5,
					),
					'rounded_skin'               => array(
						'section'   => 'color',
						'type'      => 'toggle',
						'label'     => esc_html__( 'Rounded Skin', 'alpha' ),
						'tooltip'   => esc_html__( 'Enable rounded border skin for banner, posts and so on.', 'alpha' ),
						'transport' => 'postMessage',
						'priority'  => 7,
					),

					'dark_skin'                  => array(
						'section'   => 'color',
						'type'      => 'toggle',
						'label'     => esc_html__( 'Dark Skin', 'alpha' ),
						'tooltip'   => esc_html__( 'Enable dark skin throughout full site.', 'alpha' ),
						'transport' => 'postMessage',
						'priority'  => 8,
					),

					'cs_dark_skin_alert'         => array(
						'section'         => 'color',
						'type'            => 'custom',
						'label'           => '<p class="options-description description-danger"><span>' . esc_html__( 'Caution:', 'alpha' ) . '</span> ' . esc_html__( 'Page background color and heading color has been set as default preset color.', 'alpha' ) . '<br/>' . sprintf( esc_html__( '%1$sGeneral/Site Layout/Background Color%2$s%3$sStyle/Heading Typography%2$s', 'alpha' ), '<a class="customizer-nav-item" href="#" data-target="general" data-type="section">', '</a>', '<a class="customizer-nav-item" href="#" data-target="typo_heading" data-type="control">' ) . '</p>',
						'priority'        => 9,
						'active_callback' => array(
							array(
								'setting'  => 'dark_skin',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					'accent_color'               => array(
						'section'   => 'color',
						'type'      => 'color',
						'label'     => esc_html__( 'Accent Color', 'alpha' ),
						'choices'   => array(
							'alpha' => true,
						),
						'transport' => 'postMessage',
					),
					'success_color'              => array(
						'section'   => 'color',
						'type'      => 'color',
						'label'     => esc_html__( 'Success Color', 'alpha' ),
						'choices'   => array(
							'alpha' => true,
						),
						'transport' => 'postMessage',
					),
					'info_color'                 => array(
						'section'   => 'color',
						'type'      => 'color',
						'label'     => esc_html__( 'Info Color', 'alpha' ),
						'choices'   => array(
							'alpha' => true,
						),
						'transport' => 'postMessage',
					),
					'warning_color'              => array(
						'section'   => 'color',
						'type'      => 'color',
						'label'     => esc_html__( 'Warning Color', 'alpha' ),
						'choices'   => array(
							'alpha' => true,
						),
						'transport' => 'postMessage',
					),
					'danger_color'               => array(
						'section'   => 'color',
						'type'      => 'color',
						'label'     => esc_html__( 'Danger Color', 'alpha' ),
						'choices'   => array(
							'alpha' => true,
						),
						'transport' => 'postMessage',
					),

					'typo_h1_size'               => array(
						'section'   => 'typo',
						'type'      => 'text',
						'label'     => esc_html__( 'H1 Font Size', 'alpha' ),
						'transport' => 'postMessage',
					),
					'typo_h2_size'               => array(
						'section'   => 'typo',
						'type'      => 'text',
						'label'     => esc_html__( 'H2 Font Size', 'alpha' ),
						'transport' => 'postMessage',
					),
					'typo_h3_size'               => array(
						'section'   => 'typo',
						'type'      => 'text',
						'label'     => esc_html__( 'H3 Font Size', 'alpha' ),
						'transport' => 'postMessage',
					),
					'typo_h4_size'               => array(
						'section'   => 'typo',
						'type'      => 'text',
						'label'     => esc_html__( 'H4 Font Size', 'alpha' ),
						'transport' => 'postMessage',
					),
					'typo_h5_size'               => array(
						'section'   => 'typo',
						'type'      => 'text',
						'label'     => esc_html__( 'H5 Font Size', 'alpha' ),
						'transport' => 'postMessage',
					),
					'typo_h6_size'               => array(
						'section'   => 'typo',
						'type'      => 'text',
						'label'     => esc_html__( 'H6 Font Size', 'alpha' ),
						'transport' => 'postMessage',
					),

					'cs_typo_google_title'       => array(
						'priority' => 15,
					),
					'cs_typo_google_desc'        => array(
						'priority' => 15,
					),
					'typo_custom_part'           => array(
						'priority' => 15,
					),
					'typo_custom1'               => array(
						'priority' => 15,
					),
					'typo_custom2'               => array(
						'priority' => 15,
					),
					'typo_custom3'               => array(
						'priority' => 15,
					),
					'cs_typo_custom_title'       => array(
						'priority' => 15,
					),
					'cs_typo_custom_desc'        => array(
						'priority' => 15,
					),
					'typo_user_custom'           => array(
						'priority' => 15,
					),

					// 'mobile_menu_type'           => array(
					// 	'section' => 'mobile_menu',
					// 	'type'    => 'radio-buttonset',
					// 	'label'   => esc_html__( 'Mobile Menu Type', 'alpha' ),
					// 	'tooltip' => esc_html__( 'Controls the design of the mobile menu. Flyout design style only allows parent level menu items.', 'alpha' ),
					// 	'choices' => array(
					// 		'classic' => esc_html__( 'Classic', 'alpha' ),
					// 		'modern'  => esc_html__( 'Modern', 'alpha' ),
					// 	),
					// ),
					'mobile_menu_items'          => array(
						'priority' => 15,
					),

					'cs_pbt_bar_title_alert'     => array(
						'section'   => 'title_bar',
						'type'      => 'custom',
						'priority'  => 3,
						'label'     => '<p class="options-description" style="margin-bottom: -20px"><span>' . esc_html__( 'Warning:', 'alpha' ) . '</span> ' . esc_html__( 'Following options don\'t work if you have already set page header options in ', 'alpha' ) . '<a href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder' ) ) . '" target="_blank">' . esc_html__( 'Page Layouts', 'alpha' ) . '</a>' . '.</p>',
						'transport' => 'postMessage',
					),
					'cs_ptb_bar_title'           => array(
						'section'   => 'title_bar',
						'type'      => 'custom',
						'priority'  => 5,
						'label'     => '',
						'default'   => '<h3 class="options-custom-title">' . esc_html__( 'Title Bar', 'alpha' ) . '</h3>',
						'transport' => 'postMessage',
					),
					'ptb_content'                => array(
						'section'  => 'title_bar',
						'type'     => 'select',
						'priority' => 5,
						'label'    => esc_html__( 'Page Title Bar Content', 'alpha' ),
						'tooltip'  => esc_html__( 'Controls what displays in page title bar.', 'alpha' ),
						'choices'  => array(
							'label'      => esc_html__( 'Page Title', 'alpha' ),
							'subtitle'   => esc_html__( 'Page Title + Subtitle', 'alpha' ),
							'breadcrumb' => esc_html__( 'Page Title + Breadcrumb', 'alpha' ),
							'search'     => esc_html__( 'Page Title + Search', 'alpha' ),
						),
					),
					'ptb_parallax'               => array(
						'section'  => 'title_bar',
						'type'     => 'checkbox',
						'priority' => 5,
						'label'    => esc_html__( 'Parallax Effect', 'alpha' ),
						'tooltip'  => esc_html__( 'Turn on to use a parallax scrolling effect on the background image.', 'alpha' ),
					),
					'ptb_animation'              => array(
						'section'  => 'title_bar',
						'type'     => 'checkbox',
						'priority' => 5,
						'label'    => esc_html__( 'Fading Animation', 'alpha' ),
						'tooltip'  => esc_html__( 'Turn on to have the page title appear animate on page load.', 'alpha' ),
					),
					'ptb_divider'                => array(
						'section'  => 'title_bar',
						'type'     => 'radio_image',
						'priority' => 5,
						'class'    => 'alpha-opts-images alpha-opts-dividers',
						'label'    => esc_html__( 'Divider Style', 'alpha' ),
						'tooltip'  => esc_html__( 'Controls divider style of page title bar.', 'alpha' ),
						'choices'  => $opts_dividers,
					),
					'cs_ptb_bar_style_title'     => array(
						'priority' => 8,
					),
					'ptb_bg_color'               => array(
						'section'   => 'title_bar',
						'type'      => 'color',
						'priority'  => 8,
						'label'     => esc_html__( 'Background Color', 'alpha' ),
						'transport' => 'postMessage',
					),
					'ptb_bg_image'               => array(
						'section'  => 'title_bar',
						'type'     => 'image',
						'priority' => 8,
						'label'    => esc_html__( 'Background Image', 'alpha' ),
					),
					'ptb_top_space'              => array(
						'section'   => 'title_bar',
						'type'      => 'slider',
						'priority'  => 8,
						'label'     => esc_html__( 'Top Spacing', 'alpha' ),
						'tooltip'   => esc_html__( 'Controls the top padding of the page title bar.', 'alpha' ),
						'choices'   => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'transport' => 'postMessage',
					),
					'ptb_bottom_space'           => array(
						'section'   => 'title_bar',
						'type'      => 'slider',
						'priority'  => 8,
						'label'     => esc_html__( 'Bottom Spacing', 'alpha' ),
						'tooltip'   => esc_html__( 'Controls the bottom padding of the page title bar.', 'alpha' ),
						'choices'   => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'transport' => 'postMessage',
					),
					'ptb_search_width'           => array(
						'section'         => 'title_bar',
						'type'            => 'slider',
						'priority'        => 8,
						'label'           => esc_html__( 'Search width (px)', 'alpha' ),
						'tooltip'         => esc_html__( 'Controls the width of the page title bar search.', 'alpha' ),
						'choices'         => array(
							'min'  => 250,
							'step' => 1,
							'max'  => 900,
						),
						'active_callback' => array(
							array(
								'setting'  => 'ptb_content',
								'operator' => '==',
								'value'    => 'search',
							),
						),
					),
					'typo_ptb_title'             => array(
						'priority' => 15,
					),
					'typo_ptb_subtitle'          => array(
						'priority' => 18,
					),
					'cs_pbt_breadcrumb_alert'    => array(
						'section'   => 'breadcrumb',
						'type'      => 'custom',
						'priority'  => 3,
						'label'     => '<p class="options-description" style="margin-bottom: -20px"><span>' . esc_html__( 'Warning:', 'alpha' ) . '</span> ' . esc_html__( 'Following options don\'t work if you have already set page header options in ', 'alpha' ) . '<a href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder' ) ) . '" target="_blank">' . esc_html__( 'Page Layouts', 'alpha' ) . '</a>' . '.</p>',
						'transport' => 'postMessage',
					),

					// Portfolio / Portfolio Global
					'enable_portfolio'           => array(
						'section' => 'portfolio_global',
						'type'    => 'toggle',
						'label'   => esc_html__( 'Enable Portfolio', 'alpha' ),
						'tooltip' => esc_html__( 'Turn on to enable the UDesign Portfolio.', 'alpha' ),
					),
					'portfolio_slug'             => array(
						'section'         => 'portfolio_global',
						'type'            => 'text',
						'label'           => esc_html__( 'Portfolio Slug', 'alpha' ),
						'tooltip'         => esc_html__( 'The slug name cannot be the same name as a page name or the layout will break. This option changes the permalink when you use the permalink type as %postname%. After change please go to "Settings > Permalinks" and click "Save changes" button.', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolios_slug'            => array(
						'section'         => 'portfolio_global',
						'type'            => 'text',
						'label'           => esc_html__( 'Portfolio Plural', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_portfolio_type'          => array(
						'section'         => 'portfolio_global',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Portfolio Type', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_type'             => array(
						'section'         => 'portfolio_global',
						'type'            => 'radio_image',
						'class'           => 'alpha-opts-images',
						'label'           => esc_html__( 'Portfolio Type', 'alpha' ),
						'tooltip'         => esc_html__( 'Choose show type of portfolios in archive page.', 'alpha' ),
						'choices'         => array(
							'card'    => ALPHA_ASSETS . '/images/options/portfolios/portfolio-1.jpg',
							'list'    => ALPHA_ASSETS . '/images/options/portfolios/portfolio-2.jpg',
							'gallery' => ALPHA_ASSETS . '/images/options/portfolios/portfolio-3.jpg',
							'default' => ALPHA_ASSETS . '/images/options/portfolios/portfolio-4.jpg',
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_read_more_label'  => array(
						'section'         => 'portfolio_global',
						'type'            => 'text',
						'label'           => esc_html__( 'Read More Text', 'alpha' ),
						'tooltip'         => esc_html__( 'Controls text of portfolio link leads to single page. This option works for only \'Type 2\' and \'Type 4\'.', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_portfolio_excerpt'       => array(
						'section'         => 'portfolio_global',
						'type'            => 'custom',
						'label'           => '',
						'default'         => '<h3 class="options-custom-title">' . esc_html__( 'Excerpt', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_excerpt_type'     => array(
						'section'         => 'portfolio_global',
						'type'            => 'radio_buttonset',
						'label'           => esc_html__( 'Type', 'alpha' ),
						'ms_label'        => esc_html__( 'Excerpt Type', 'alpha' ),
						'choices'         => array(
							''          => esc_html__( 'Word', 'alpha' ),
							'character' => esc_html__( 'Letter', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_excerpt_length'   => array(
						'section'         => 'portfolio_global',
						'type'            => 'number',
						'label'           => esc_html__( 'Length', 'alpha' ),
						'ms_label'        => esc_html__( 'Excerpt Length', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					// Portfolio / Archive Portfolio
					'cs_portfolios_title'        => array(
						'section'         => 'portfolio_archive',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Portfolios', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_portfolios_alert'        => array(
						'section'         => 'portfolio_archive',
						'type'            => 'custom',
						'label'           => '<p class="options-description"><span>Warning: </span>' . sprintf( esc_html__( 'Layout builder\'s "%1$sPortfolios%2$s / Content / Options" is prior than this theme options in portfolios page.', 'alpha' ), '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder&layout=archive_' . ALPHA_NAME . '_portfolio' ) ) . '">', '</a>' ) . '</p>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolios_count'           => array(
						'section'         => 'portfolio_archive',
						'type'            => 'number',
						'label'           => esc_html__( 'Portfolios Per Page', 'alpha' ),
						'tooltip'         => esc_html__( 'Controls the count of portfolios per page. Set to -1 to display all. Set to 0 to use the number of posts from Settings > Reading', 'alpha' ),
						'choices'         => array(
							'min'  => -1,
							'max'  => 50,
							'step' => 1,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolios_layout'          => array(
						'section'         => 'portfolio_archive',
						'type'            => 'radio_buttonset',
						'label'           => esc_html__( 'Layout', 'alpha' ),
						'tooltip'         => esc_html__( 'Masonry layout will use uncropped images.', 'alpha' ),
						'choices'         => array(
							'grid'    => esc_html__( 'Grid', 'alpha' ),
							'masonry' => esc_html__( 'Masonry', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolios_column'          => array(
						'section'         => 'portfolio_archive',
						'type'            => 'number',
						'label'           => esc_html__( 'Column', 'alpha' ),
						'choices'         => array(
							'min' => 1,
							'max' => 8,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolios_filter'          => array(
						'section'         => 'portfolio_archive',
						'type'            => 'toggle',
						'label'           => esc_html__( 'Filter By Category', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolios_load'            => array(
						'section'         => 'portfolio_archive',
						'type'            => 'radio_image',
						'label'           => esc_html__( 'Load More', 'alpha' ),
						'tooltip'         => esc_html__( 'Controls the pagination type in portfolios page.', 'alpha' ),
						'choices'         => array(
							'button' => ALPHA_ASSETS . '/images/options/loadmore-btn.jpg',
							''       => ALPHA_ASSETS . '/images/options/loadmore-page.jpg',
							'scroll' => ALPHA_ASSETS . '/images/options/loadmore-scroll.jpg',
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					// Portfolio / Single Portfolio
					'cs_portfolio_title'         => array(
						'section'         => 'portfolio_single',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Show Information', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_show_info'        => array(
						'section'         => 'portfolio_single',
						'type'            => 'multicheck',
						'label'           => esc_html__( 'Items to show', 'alpha' ),
						'choices'         => array(
							'image'         => esc_html__( 'Media', 'alpha' ),
							'category'      => esc_html__( 'Category', 'alpha' ),
							'skill'         => esc_html__( 'Skill', 'alpha' ),
							'author'        => esc_html__( 'Author', 'alpha' ),
							'url'           => esc_html__( 'URL', 'alpha' ),
							'client'        => esc_html__( 'Client', 'alpha' ),
							'copyright'     => esc_html__( 'Copyright', 'alpha' ),
							'share'         => esc_html__( 'Share', 'alpha' ),
							'navigation'    => esc_html__( 'Portfolio Navigation', 'alpha' ),
							'related'       => esc_html__( 'Related Portfolios', 'alpha' ),
							'comments_list' => esc_html__( 'Comments', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_portfolio_related_title' => array(
						'section'         => 'portfolio_single',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Related Portfolios', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_related_title'    => array(
						'section'         => 'portfolio_single',
						'type'            => 'text',
						'label'           => esc_html__( 'Related Section\'s Title', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_related_count'    => array(
						'section'         => 'portfolio_single',
						'type'            => 'number',
						'label'           => esc_html__( 'Count', 'alpha' ),
						'ms_label'        => esc_html__( 'Related Count', 'alpha' ),
						'choices'         => array(
							'min' => 1,
							'max' => 50,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'portfolio_related_column'   => array(
						'section'         => 'portfolio_single',
						'type'            => 'number',
						'label'           => esc_html__( 'Column', 'alpha' ),
						'ms_label'        => esc_html__( 'Related Column', 'alpha' ),
						'choices'         => array(
							'min'  => 1,
							'max'  => 8,
							'step' => 1,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_portfolio',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					// Member / Member Global
					'enable_member'              => array(
						'section' => 'member_global',
						'type'    => 'toggle',
						'label'   => esc_html__( 'Enable Member', 'alpha' ),
						'tooltip' => esc_html__( 'Turn on to enable the UDesign Member.', 'alpha' ),
					),
					'member_slug'                => array(
						'section'         => 'member_global',
						'type'            => 'text',
						'label'           => esc_html__( 'Member Slug', 'alpha' ),
						'tooltip'         => esc_html__( 'The slug name cannot be the same name as a page name or the layout will break. This option changes the permalink when you use the permalink type as %postname%. After change please go to "Settings > Permalinks" and click "Save changes" button.', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'members_slug'               => array(
						'section'         => 'member_global',
						'type'            => 'text',
						'label'           => esc_html__( 'Member Plural', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_member_type'             => array(
						'section'         => 'member_global',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Member Type', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_type'                => array(
						'section'         => 'member_global',
						'type'            => 'radio_image',
						'class'           => 'alpha-opts-images',
						'label'           => esc_html__( 'Member Type', 'alpha' ),
						'tooltip'         => esc_html__( 'Choose show type of members in archive page.', 'alpha' ),
						'choices'         => array(
							'default' => ALPHA_ASSETS . '/images/options/members/member-1.jpg',
							'card'    => ALPHA_ASSETS . '/images/options/members/member-2.jpg',
							'gallery' => ALPHA_ASSETS . '/images/options/members/member-3.jpg',
							'circle'  => ALPHA_ASSETS . '/images/options/members/member-4.jpg',
							'boxed'   => ALPHA_ASSETS . '/images/options/members/member-5.jpg',
							'info'    => ALPHA_ASSETS . '/images/options/members/member-6.jpg',
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_overlay'             => array(
						'section'         => 'member_global',
						'type'            => 'select',
						'label'           => esc_html__( 'Hover Effect', 'alpha' ),
						'choices'         => array(
							''           => esc_html__( 'None', 'alpha' ),
							'light'      => esc_html__( 'Light', 'alpha' ),
							'dark'       => esc_html__( 'Dark', 'alpha' ),
							'zoom'       => esc_html__( 'Zoom', 'alpha' ),
							'zoom_light' => esc_html__( 'Zoom and Light', 'alpha' ),
							'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_member_excerpt'          => array(
						'section'         => 'member_global',
						'type'            => 'custom',
						'label'           => '',
						'default'         => '<h3 class="options-custom-title">' . esc_html__( 'Excerpt', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_excerpt_type'        => array(
						'section'         => 'member_global',
						'type'            => 'radio_buttonset',
						'label'           => esc_html__( 'Type', 'alpha' ),
						'ms_label'        => esc_html__( 'Excerpt Type', 'alpha' ),
						'choices'         => array(
							''          => esc_html__( 'Word', 'alpha' ),
							'character' => esc_html__( 'Letter', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_excerpt_length'      => array(
						'section'         => 'member_global',
						'type'            => 'number',
						'label'           => esc_html__( 'Length', 'alpha' ),
						'ms_label'        => esc_html__( 'Excerpt Length', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					// Member / Archive Member
					'cs_members_title'           => array(
						'section'         => 'member_archive',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Members', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_members_alert'           => array(
						'section'         => 'member_archive',
						'type'            => 'custom',
						'label'           => '<p class="options-description"><span>Warning: </span>' . sprintf( esc_html__( 'Layout builder\'s "%1$sMembers%2$s / Content / Options" is prior than this theme options in members page.', 'alpha' ), '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder&layout=archive_' . ALPHA_NAME . '_member' ) ) . '">', '</a>' ) . '</p>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'members_count'              => array(
						'section'         => 'member_archive',
						'type'            => 'number',
						'label'           => esc_html__( 'Members Per Page', 'alpha' ),
						'tooltip'         => esc_html__( 'Controls the count of members per page. Set to -1 to display all. Set to 0 to use the number of posts from Settings > Reading', 'alpha' ),
						'choices'         => array(
							'min'  => -1,
							'max'  => 50,
							'step' => 1,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'members_layout'             => array(
						'section'         => 'member_archive',
						'type'            => 'radio_buttonset',
						'label'           => esc_html__( 'Layout', 'alpha' ),
						'tooltip'         => esc_html__( 'Masonry layout will use uncropped images.', 'alpha' ),
						'choices'         => array(
							'grid'    => esc_html__( 'Grid', 'alpha' ),
							'masonry' => esc_html__( 'Masonry', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'members_column'             => array(
						'section'         => 'member_archive',
						'type'            => 'number',
						'label'           => esc_html__( 'Column', 'alpha' ),
						'choices'         => array(
							'min' => 1,
							'max' => 8,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'members_filter'             => array(
						'section'         => 'member_archive',
						'type'            => 'toggle',
						'label'           => esc_html__( 'Filter By Category', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'members_load'               => array(
						'section'         => 'member_archive',
						'type'            => 'radio_image',
						'label'           => esc_html__( 'Load More', 'alpha' ),
						'tooltip'         => esc_html__( 'Controls the pagination type in members page.', 'alpha' ),
						'choices'         => array(
							'button' => ALPHA_ASSETS . '/images/options/loadmore-btn.jpg',
							''       => ALPHA_ASSETS . '/images/options/loadmore-page.jpg',
							'scroll' => ALPHA_ASSETS . '/images/options/loadmore-scroll.jpg',
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),

					// Member / Single Member
					'cs_member_title'            => array(
						'section'         => 'member_single',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Show Information', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_show_info'           => array(
						'section'         => 'member_single',
						'type'            => 'multicheck',
						'label'           => esc_html__( 'Items to show', 'alpha' ),
						'choices'         => array(
							'image'       => esc_html__( 'Media', 'alpha' ),
							'title'       => esc_html__( 'Title', 'alpha' ),
							'category'    => esc_html__( 'Category', 'alpha' ),
							'share'       => esc_html__( 'Social Links', 'alpha' ),
							'contact'     => esc_html__( 'Contact Info', 'alpha' ),
							'appointment' => esc_html__( 'Appointment', 'alpha' ),
							'related'     => esc_html__( 'Related Members', 'alpha' ),
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_member_related_title'    => array(
						'section'         => 'member_single',
						'type'            => 'custom',
						'label'           => '<h3 class="options-custom-title">' . esc_html__( 'Related Members', 'alpha' ) . '</h3>',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_related_title'       => array(
						'section'         => 'member_single',
						'type'            => 'text',
						'label'           => esc_html__( 'Related Section\'s Title', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_related_count'       => array(
						'section'         => 'member_single',
						'type'            => 'number',
						'label'           => esc_html__( 'Count', 'alpha' ),
						'ms_label'        => esc_html__( 'Related Count', 'alpha' ),
						'choices'         => array(
							'min' => 1,
							'max' => 50,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_related_column'      => array(
						'section'         => 'member_single',
						'type'            => 'number',
						'label'           => esc_html__( 'Column', 'alpha' ),
						'ms_label'        => esc_html__( 'Related Column', 'alpha' ),
						'choices'         => array(
							'min'  => 1,
							'max'  => 8,
							'step' => 1,
						),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'cs_member_booking_title'    => array(
						'section'         => 'member_single',
						'type'            => 'custom',
						'label'           => '',
						'default'         => '<h3 class="options-custom-title">' . esc_html__( 'Booking Appointment', 'alpha' ) . '</h3>',
						'transport'       => 'postMessage',
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					'member_booking_email'       => array(
						'section'         => 'member_single',
						'type'            => 'text',
						'label'           => esc_html__( 'Email for Booking Request', 'alpha' ),
						'tooltip'         => esc_html__( 'Enter the email to which booking requests will be sent. If left empty, they will be sent to the member or the site super admin.', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'enable_member',
								'operator' => '==',
								'value'    => true,
							),
						),
					),
					// Woocommerce / Shop
					'cs_products_grid'           => array(
						'section'  => 'products_archive',
						'type'     => 'custom',
						'label'    => '<h3 class="options-custom-title">' . esc_html__( 'Shop Products', 'alpha' ) . '</h3>',
						'priority' => 5,
					),
					'cs_shop_page_alert'         => array(
						'section'  => 'products_archive',
						'type'     => 'custom',
						'label'    => '<p class="options-description"><span>Warning: </span>' . sprintf( esc_html__( 'Layout builder\'s "%1$sShop Page%2$s / Content / Options" is prior than this theme options in shop page.', 'alpha' ), '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=alpha-layout-builder&layout=archive_product' ) ) . '">', '</a>' ) . '</p>',
						'priority' => 5,
					),
					'products_column'            => array(
						'section'  => 'products_archive',
						'type'     => 'number',
						'label'    => esc_html__( 'Column', 'alpha' ),
						'choices'  => array(
							'min' => 1,
							'max' => 8,
						),
						'priority' => 5,
					),
					'products_gap'               => array(
						'section'  => 'products_archive',
						'type'     => 'radio_buttonset',
						'label'    => esc_html__( 'Gap Size', 'alpha' ),
						'tooltip'  => esc_html__( 'Choose gap size between products', 'alpha' ),
						'choices'  => array(
							'no' => esc_html__( 'No', 'alpha' ),
							'xs' => esc_html__( 'XS', 'alpha' ),
							'sm' => esc_html__( 'S', 'alpha' ),
							'md' => esc_html__( 'M', 'alpha' ),
							'lg' => esc_html__( 'L', 'alpha' ),
						),
						'priority' => 5,
					),
					'products_load'              => array(
						'section'  => 'products_archive',
						'type'     => 'radio_image',
						'label'    => esc_html__( 'Load More', 'alpha' ),
						'choices'  => array(
							'button' => ALPHA_ASSETS . '/images/options/loadmore-btn.jpg',
							''       => ALPHA_ASSETS . '/images/options/loadmore-page.jpg',
							'scroll' => ALPHA_ASSETS . '/images/options/loadmore-scroll.jpg',
						),
						'priority' => 5,
					),
					'products_load_label'        => array(
						'section'         => 'products_archive',
						'type'            => 'text',
						'label'           => esc_html__( 'Load Button Label', 'alpha' ),
						'active_callback' => array(
							array(
								'setting'  => 'products_load',
								'operator' => '==',
								'value'    => 'button',
							),
						),
						'priority'        => 5,
					),
					'products_count_select'      => array(
						'section'  => 'products_archive',
						'type'     => 'text',
						'label'    => esc_html__( 'Products Count Variations', 'alpha' ),
						'tooltip'  => esc_html__( 'Please input comma separated integers. Every integers will be shown as option of select box in product archive page. Integer with prefix "_" will be default count. e.g: 9, _12, 24, 36', 'alpha' ),
						'priority' => 5,
					),
					'cs_woo_ajax'                => array(
						'section' => 'products_archive',
						'type'    => 'custom',
						'label'   => '',
						'default' => '<h3 class="options-custom-title">' . esc_html__( 'Load more and category ajax filter', 'alpha' ) . '</h3>
						<p>' . sprintf( esc_html__( 'You can customize ajax filter options in %1$sFeatures/Ajax Filter%2$s panel.', 'alpha' ), '<b>', '</b>' ) . '</p>' .
						'<a class="button button-xlarge customizer-nav-item" data-target="ajax_filter" data-type="section" href="#">' . esc_html__( 'Go to Ajax Filter', 'alpha' ) . '</a>',
					),
					// Product Page / Product Layout
					'cs_product_layout'          => array(
						'section'  => 'product_detail',
						'type'     => 'custom',
						'label'    => '<h3 class="options-custom-title">' . esc_html__( 'Product Layout', 'alpha' ) . '</h3>',
						'priority' => 5,
					),
					'single_product_type'        => array(
						'section'  => 'product_detail',
						'type'     => 'select',
						'label'    => esc_html__( 'Single Product Layout', 'alpha' ),
						'choices'  => apply_filters(
							'alpha_sp_types',
							array(
								''              => esc_html__( 'Horizontal Thumbs', 'alpha' ),
								'vertical'      => esc_html__( 'Vertical Thumbs', 'alpha' ),
								'grid'          => esc_html__( 'Grid Images', 'alpha' ),
								'masonry'       => esc_html__( 'Masonry', 'alpha' ),
								'gallery'       => esc_html__( 'Gallery', 'alpha' ),
								'sticky-info'   => esc_html__( 'Sticky Information', 'alpha' ),
								'sticky-thumbs' => esc_html__( 'Sticky Thumbs', 'alpha' ),
								'sticky-both'   => esc_html__( 'Left &amp; Right Sticky', 'alpha' ),
							),
							'theme'
						),
						'tooltip'  => esc_html__( 'Layout builder\'s "Product Detail Layout/Content/Single Product Type" option is prior than this.', 'alpha' ),
						'priority' => 5,
					),
					// Product Page / Product Data / Custom Tab
					'cs_product_custom_tab'      => array(
						'section'  => 'product_detail',
						'type'     => 'custom',
						'label'    => '',
						'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Custom Tab', 'alpha' ) . '</h3>',
						'priority' => 15,
					),
					'product_tab_title'          => array(
						'section'  => 'product_detail',
						'type'     => 'text',
						'label'    => esc_html__( 'Custom Tab Title', 'alpha' ),
						'tooltip'  => esc_html__( 'Show custom tab in all product pages.', 'alpha' ),
						// 'transport' => 'postMessage',
						'priority' => 15,
					),
					'product_tab_block'          => array(
						'section'  => 'product_detail',
						'type'     => 'select',
						'label'    => esc_html__( 'Custom Tab Content ( Block Builder )', 'alpha' ),
						'choices'  => empty( $alpha_templates['block'] ) ? array() : $custom_tab_block,
						'priority' => 15,
					),
					// Product Page / Related Products
					'cs_product_related'         => array(
						'section'  => 'product_detail',
						'type'     => 'custom',
						'label'    => '',
						'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Related Products', 'alpha' ) . '</h3>',
						'priority' => 20,
					),
					'product_related_title'      => array(
						'section'  => 'product_detail',
						'type'     => 'text',
						'label'    => esc_html__( 'Title', 'alpha' ),
						'ms_label' => esc_html__( 'Related Title', 'alpha' ),
						// 'transport' => 'postMessage',
						'priority' => 20,
					),
					'product_related_count'      => array(
						'section'  => 'product_detail',
						'type'     => 'slider',
						'label'    => esc_html__( 'Count', 'alpha' ),
						'ms_label' => esc_html__( 'Related Count', 'alpha' ),
						'choices'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 50,
						),
						'priority' => 20,
					),
					'product_related_column'     => array(
						'section'  => 'product_detail',
						'type'     => 'slider',
						'label'    => esc_html__( 'Column', 'alpha' ),
						'ms_label' => esc_html__( 'Related Column', 'alpha' ),
						'choices'  => array(
							'min'  => 1,
							'step' => 1,
							'max'  => 6,
						),
						'priority' => 20,
					),
					// Product Page / Up-Sells Products
					'cs_product_upsells'         => array(
						'section'  => 'product_detail',
						'type'     => 'custom',
						'label'    => '',
						'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Up-Sells Products', 'alpha' ) . '</h3>',
						'priority' => 25,
					),
					'product_upsells_title'      => array(
						'section'  => 'product_detail',
						'type'     => 'text',
						'label'    => esc_html__( 'Title', 'alpha' ),
						'ms_label' => esc_html__( 'Up-Sells Title', 'alpha' ),
						// 'transport' => 'postMessage',
						'priority' => 25,
					),
					'product_upsells_count'      => array(
						'section'  => 'product_detail',
						'type'     => 'slider',
						'label'    => esc_html__( 'Count', 'alpha' ),
						'ms_label' => esc_html__( 'Up-Sells Count', 'alpha' ),
						'choices'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 50,
						),
						'priority' => 25,
					),
					// Woocommerce / Product Type
					'cs_product_type_title'      => array(
						'section'  => 'product_type',
						'type'     => 'custom',
						'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Product Type', 'alpha' ) . '</h3>',
						'priority' => 5,
					),
					'product_type'               => array(
						'section'  => 'product_type',
						'type'     => 'radio_image',
						'label'    => esc_html__( 'Product Type', 'alpha' ),
						'choices'  => apply_filters(
							'alpha_product_loop_types',
							array(),
							'theme'
						),
						'priority' => 10,
					),
					'hover_change'               => array(
						'section'  => 'product_type',
						'type'     => 'toggle',
						'label'    => esc_html__( 'Change Image on Hover', 'alpha' ),
						'tooltip'  => esc_html__( 'Enable to show second product image when mouse enters.', 'alpha' ),
						'priority' => 15,
					),
					'prod_open_click_mob'        => array(
						'section'  => 'product_type',
						'type'     => 'toggle',
						'label'    => esc_html__( 'Open product on second click on mobile', 'alpha' ),
						'tooltip'  => esc_html__( 'Enable to navigate to product detail page on second click. First click would work as hover effect on mobile.', 'alpha' ),
						'priority' => 15,
					),
					'cs_product_excerpt'         => array(
						'section'  => 'product_type',
						'type'     => 'custom',
						'default'  => '<h3 class="options-custom-title">' . esc_html__( 'Product Excerpt', 'alpha' ) . '</h3>',
						'priority' => 15,
					),
					'prod_excerpt_type'          => array(
						'section'  => 'product_type',
						'type'     => 'radio_buttonset',
						'label'    => esc_html__( 'Type', 'alpha' ),
						'ms_label' => esc_html__( 'Excerpt Type', 'alpha' ),
						'choices'  => array(
							''          => esc_html__( 'Word', 'alpha' ),
							'character' => esc_html__( 'Letter', 'alpha' ),
						),
						'priority' => 15,
					),
					'prod_excerpt_length'        => array(
						'section'  => 'product_type',
						'type'     => 'number',
						'label'    => esc_html__( 'Length', 'alpha' ),
						'ms_label' => esc_html__( 'Excerpt Length', 'alpha' ),
						'choices'  => array(
							'min' => 0,
							'max' => 250,
						),
						'priority' => 15,
					),
					// Woocommerce / Category Type
					'cs_category_type_title'     => array(
						'section' => 'category_type',
						'type'    => 'custom',
						'default' => '<h3 class="options-custom-title">' . esc_html__( 'Category Type', 'alpha' ) . ' </h3>',
					),
					'category_type'              => array(
						'section' => 'category_type',
						'type'    => 'radio-image',
						'label'   => esc_html__( 'Category Type', 'alpha' ),
						'choices' => apply_filters(
							'alpha_pc_types',
							array(
								''          => ALPHA_ASSETS . '/images/options/categories/category-1.jpg',
								'frame'     => ALPHA_ASSETS . '/images/options/categories/category-2.jpg',
								'banner'    => ALPHA_ASSETS . '/images/options/categories/category-3.jpg',
								'simple'    => ALPHA_ASSETS . '/images/options/categories/category-4.jpg',
								'icon'      => ALPHA_ASSETS . '/images/options/categories/category-5.jpg',
								'classic'   => ALPHA_ASSETS . '/images/options/categories/category-6.jpg',
								'classic-2' => ALPHA_ASSETS . '/images/options/categories/category-7.jpg',
								'ellipse'   => ALPHA_ASSETS . '/images/options/categories/category-8.jpg',
								'ellipse-2' => ALPHA_ASSETS . '/images/options/categories/category-9.jpg',
								'group'     => ALPHA_ASSETS . '/images/options/categories/category-10.jpg',
								'group-2'   => ALPHA_ASSETS . '/images/options/categories/category-11.jpg',
								'label'     => ALPHA_ASSETS . '/images/options/categories/category-12.jpg',
							),
							'theme'
						),
					),
					'subcat_cnt'                 => array(
						'section'         => 'category_type',
						'type'            => 'text',
						'label'           => esc_html__( 'Subcategory Count', 'alpha' ),
						'transport'       => 'refresh',
						'active_callback' => array(
							array(
								'setting'  => 'category_type',
								'operator' => 'in',
								'value'    => array( 'group', 'group-2' ),
							),
						),
					),
					'category_show_icon'         => array(
						'section'         => 'category_type',
						'type'            => 'toggle',
						'label'           => esc_html__( 'Show Icon', 'alpha' ),
						'transport'       => 'refresh',
						'active_callback' => array(
							array(
								'setting'  => 'category_type',
								'operator' => 'in',
								'value'    => array( 'icon', 'group', 'group-2' ),
							),
						),
					),
					'category_overlay'           => array(
						'section' => 'category_type',
						'type'    => 'select',
						'label'   => esc_html__( 'Hover Effect', 'alpha' ),
						'choices' => array(
							'no'         => esc_html__( 'None', 'alpha' ),
							'light'      => esc_html__( 'Light', 'alpha' ),
							'dark'       => esc_html__( 'Dark', 'alpha' ),
							'zoom'       => esc_html__( 'Zoom', 'alpha' ),
							'zoom_light' => esc_html__( 'Zoom and Light', 'alpha' ),
							'zoom_dark'  => esc_html__( 'Zoom and Dark', 'alpha' ),
						),
					),
					'cart_show_clear'            => array(
						'section'  => 'wc_cart',
						'type'     => 'toggle',
						'label'    => esc_html__( 'Show Clear Button', 'alpha' ),
						'tooltip'  => esc_html__( 'Show clear cart button on cart page.', 'alpha' ),
						'priority' => 0,
					),
					'cart_auto_update'           => array(
						'section'  => 'wc_cart',
						'type'     => 'toggle',
						'label'    => esc_html__( 'Auto Update Quantity', 'alpha' ),
						'tooltip'  => esc_html__( 'Automatically update on quantity change.', 'alpha' ),
						'priority' => 0,
					),
				)
			);

			// Remove framework options
			$black_list = array(
				'ptb_bg',
				'ptb_height',

				'cs_shop_title',
				'cs_sp_title',
				'cs_product_show_info',
				'show_info',
				'sold_by_label',

				'single_product_sticky',
				'single_product_sticky_mobile',
				'cs_product_data',
				'product_description_title',
				'product_specification_title',
				'product_reviews_title',
				'product_related_order',
				'product_related_orderway',
				'product_upsells_order',
				'product_upsells_orderway',
				'cs_product_reviews_form',
				'product_review_offcanvas',

				'loading_animation',

				'cs_blog_single_title',
				'cs_blog_archive_title',
			);
			foreach ( $black_list as $item ) {
				unset( $fields[ $item ] );
			}

			return $fields;
		}
	}
endif;

Alpha_Customizer_Extend::get_instance();
