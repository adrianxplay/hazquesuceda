<?php
/**
 * Default Theme Options
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 *
 * @var array $alpha_option
 */
defined( 'ABSPATH' ) || die;

$default_conditions = array(
	'site'                                => array(
		array(
			'title' => esc_html__( 'Global Layout', 'alpha' ),
		),
	),
	'archive_product'                     => array(
		array(
			'title'   => esc_html__( 'Shop Layout', 'alpha' ),
			'scheme'  => array(
				'all' => true,
			),
			'options' => array(
				'left_sidebar'    => 'shop-sidebar',
				'ptb'             => 'hide',
				'show_breadcrumb' => 'yes',
			),
		),
	),
	'single_product'                      => array(
		array(
			'title'   => esc_html__( 'Product Page Layout', 'alpha' ),
			'scheme'  => array(
				'all' => true,
			),
			'options' => array(
				'right_sidebar'   => 'product-sidebar',
				'ptb'             => 'hide',
				'show_breadcrumb' => 'yes',
			),
		),
	),
	'archive_post'                        => array(
		array(
			'title'   => esc_html__( 'Blog Layout', 'alpha' ),
			'scheme'  => array(
				'all' => true,
			),
			'options' => array(
				'right_sidebar' => 'blog-sidebar',
			),
		),
	),
	'single_post'                         => array(
		array(
			'title'   => esc_html__( 'Post Page Layout', 'alpha' ),
			'scheme'  => array(
				'all' => true,
			),
			'options' => array(
				'ptb'           => 'hide',
				'right_sidebar' => 'blog-sidebar',
			),
		),
	),
	'single_' . ALPHA_NAME . '_portfolio' => array(
		array(
			'title'   => esc_html__( 'Portfolio Item Page Layout', 'alpha' ),
			'scheme'  => array(
				'all' => true,
			),
			'options' => array(
				'ptb' => 'hide',
			),
		),
	),
	'single_' . ALPHA_NAME . '_member'    => array(
		array(
			'title'   => esc_html__( 'Member Page Layout', 'alpha' ),
			'scheme'  => array(
				'all' => true,
			),
			'options' => array(
				'ptb' => 'hide',
			),
		),
	),
	'error'                               => array(
		array(
			'title'   => esc_html__( '404 Page Layout', 'alpha' ),
			'options' => array(
				'wrap'            => 'full',
				'ptb'             => 'hide',
				'show_breadcrumb' => 'no',
			),
		),
	),
);

$alpha_option = array(

	// Navigator
	'navigator_items'              => array(
		'custom_css_js'    => array( esc_html__( 'Style / Additional CSS & Script', 'alpha' ), 'section' ),
		'color'            => array( esc_html__( 'Style / Color & Skin', 'alpha' ), 'section' ),
		'blog_global'      => array( esc_html__( 'Blog / Blog Global', 'alpha' ), 'section' ),
		'portfolio_global' => array( esc_html__( 'Portfolio / Portfolio Global', 'alpha' ), 'section' ),
		'lazyload'         => array( esc_html__( 'Features / Lazy Load', 'alpha' ), 'section' ),
		'search'           => array( esc_html__( 'Features / Search', 'alpha' ), 'section' ),
	),

	// Conditions
	'conditions'                   => array(
		'site'                                => array(
			array(
				'title' => esc_html__( 'Global Layout', 'alpha' ),
			),
		),
		'archive_product'                     => array(
			array(
				'title'   => esc_html__( 'Shop Layout', 'alpha' ),
				'scheme'  => array(
					'all' => true,
				),
				'options' => array(
					'left_sidebar' => 'shop-sidebar',
				),
			),
		),
		'archive_post'                        => array(
			array(
				'title'   => esc_html__( 'Blog Layout', 'alpha' ),
				'scheme'  => array(
					'all' => true,
				),
				'options' => array(
					'right_sidebar' => 'blog-sidebar',
					// 'post_type'     => 'list',
				),
			),
		),
		'single_post'                         => array(
			array(
				'title'   => esc_html__( 'Post Page Layout', 'alpha' ),
				'scheme'  => array(
					'all' => true,
				),
				'options' => array(
					'right_sidebar' => 'blog-sidebar',
				),
			),
		),
		'single_' . ALPHA_NAME . '_portfolio' => array(
			array(
				'title'   => esc_html__( 'Portfolio Item Page Layout', 'alpha' ),
				'scheme'  => array(
					'all' => true,
				),
				'options' => array(
					'ptb' => 'hide',
				),
			),
		),
		'single_' . ALPHA_NAME . '_member'    => array(
			array(
				'title'   => esc_html__( 'Member Page Layout', 'alpha' ),
				'scheme'  => array(
					'all' => true,
				),
				'options' => array(
					'ptb' => 'hide',
				),
			),
		),
		'error'                               => array(
			array(
				'title'   => esc_html__( '404 Page Layout', 'alpha' ),
				'options' => array(
					'wrap' => 'full',
					'ptb'  => 'hide',
				),
			),
		),
	),

	// General
	'logo_width'                   => 136,
	'site_icon'                    => array( 'url' => ALPHA_URI . '/assets/images/favicon.png' ),
	'page_transition'              => '',
	'preloader'                    => '',
	'preloader_color'              => '#fd7800',
	'change_cursor_type'           => false,
	'cursor_style'                 => 'dot_circle',
	'cursor_size'                  => 6,
	'cursor_inner_color'           => '',
	'cursor_outer_color'           => '',
	'cursor_outer_bg_color'        => '',
	'bg_grid_line'                 => false,
	'grid_line_width'              => 'full',
	'grid_width_offset'            => 0,
	'grid_columns'                 => 6,
	'grid_line_color'              => '#eee',
	'grid_line_weight'             => 1,
	'smart_sticky'                 => false,
	'back_to_top'                  => true,

	'lazyload_bg'                  => '#f4f4f4',
	'page_peel'                    => true,
	'rollover'                     => true,

	// Layout
	'site_type'                    => 'full',
	'site_width'                   => '1260',
	'site_gap'                     => '20',
	'container'                    => '1260',
	'container_fluid'              => '1820',

	// Skin
	'primary_color'                => '#fd7800',
	'secondary_color'              => '#9ab35e',
	'dark_color'                   => '#323334',
	'light_color'                  => '#ccc',
	'accent_color'                 => '#2265cd',
	'success_color'                => '#9AB35D',
	'info_color'                   => '#62A8EA',
	'warning_color'                => '#F2A654',
	'danger_color'                 => '#F96868',

	'rounded_skin'                 => true,
	'dark_skin'                    => false,

	'typo_default'                 => array(
		'color'          => '#888',
		'google'         => true,
		'font-weight'    => '400',
		'font-family'    => 'Poppins',
		'font-size'      => '14px',
		'line-height'    => '1.86',
		'letter-spacing' => '-.01em',
	),
	'typo_heading'                 => array(
		'color'          => '#323334',
		'font-weight'    => '600',
		'font-family'    => 'inherit',
		'line-height'    => '1.2',
		'letter-spacing' => '-0.025em',
	),
	'typo_custom1'                 => array(
		'font-family' => 'inherit',
		'font-weight' => '600',
	),
	'typo_custom2'                 => array(
		'font-family' => 'inherit',
		'font-weight' => '600',
	),
	'typo_custom3'                 => array(
		'font-family' => 'inherit',
		'font-weight' => '600',
	),

	// Icon Fonts
	'legacy_icons'                 => count( get_theme_mods() ) < 5 ? false : true,
	'icon_weight'                  => 'regular',

	// Page Title Bar
	'ptb_show'                     => true,
	'ptb_content'                  => 'label',
	'ptb_parallax'                 => false,
	'ptb_animation'                => true,
	'ptb_top_space'                => 46,
	'ptb_bottom_space'             => 46,
	'ptb_align'                    => 'center',
	'ptb_search_width'             => 350,
	'ptb_bg_color'                 => '#eee',
	'typo_ptb_title'               => array(
		'font-family'    => 'inherit',
		'variant'        => '',
		'font-size'      => '28px',
		'line-height'    => '',
		'letter-spacing' => '',
		'text-transform' => 'capitalize',
		'color'          => '#323334',
	),
	'typo_ptb_subtitle'            => array(
		'font-family'    => 'inherit',
		'variant'        => '',
		'font-size'      => '18px',
		'line-height'    => '',
		'letter-spacing' => '',
		'color'          => '',
	),
	'typo_ptb_breadcrumb'          => array(
		'font-family'    => 'inherit',
		'font-size'      => '12px',
		'line-height'    => '',
		'letter-spacing' => '',
		'text-transform' => '',
		'color'          => '#323334',
	),

	// Breadcrumb
	'show_breadcrumb'              => true,
	'breadcrumb_separator'         => '',
	'breadcrumb_home_icon'         => false,

	// Mobile Bar
	'mobile_bar_icons'             => array( 'home', 'shop', 'account', 'cart', 'search' ),
	'mobile_bar_menu_label'        => esc_html__( 'Menu', 'alpha' ),
	'mobile_bar_menu_icon'         => ALPHA_ICON_PREFIX . '-icon-bars',
	'mobile_bar_home_label'        => esc_html__( 'Home', 'alpha' ),
	'mobile_bar_home_icon'         => ALPHA_ICON_PREFIX . '-icon-home',
	'mobile_bar_shop_label'        => esc_html__( 'Categories', 'alpha' ),
	'mobile_bar_shop_icon'         => ALPHA_ICON_PREFIX . '-icon-category',
	'mobile_bar_wishlist_label'    => esc_html__( 'Wishlist', 'alpha' ),
	'mobile_bar_wishlist_icon'     => ALPHA_ICON_PREFIX . '-icon-heart',
	'mobile_bar_account_label'     => esc_html__( 'Account', 'alpha' ),
	'mobile_bar_account_icon'      => ALPHA_ICON_PREFIX . '-icon-account',
	'mobile_bar_compare_label'     => esc_html__( 'Compare', 'alpha' ),
	'mobile_bar_compare_icon'      => ALPHA_ICON_PREFIX . '-icon-compare',
	'mobile_bar_cart_label'        => esc_html__( 'Cart', 'alpha' ),
	'mobile_bar_cart_icon'         => THEME_ICON_PREFIX . '-icon-cart',
	'mobile_bar_search_label'      => esc_html__( 'Search', 'alpha' ),
	'mobile_bar_search_icon'       => ALPHA_ICON_PREFIX . '-icon-search',
	'mobile_bar_top_label'         => esc_html__( 'To Top', 'alpha' ),
	'mobile_bar_top_icon'          => ALPHA_ICON_PREFIX . '-icon-long-arrow-up',

	// Menu
	'menu_labels'                  => '',
	'mobile_menu_items'            => array(),
	'mobile_menu_skin'             => 'dark',

	'top_button_size'              => '100',
	'top_button_pos'               => 'right',

	// Blog
	'posts_layout'                 => 'grid',
	'posts_column'                 => 1,
	'posts_filter'                 => false,
	'posts_load'                   => '',
	'posts_show_info'              => array(
		'image',
		'category',
		'author',
		'date',
		'content',
		'comment',
	),
	'post_type'                    => 'default',
	'post_overlay'                 => 'zoom',
	'excerpt_type'                 => '',
	'excerpt_length'               => 15,
	'post_related_count'           => 3,
	'post_related_column'          => 2,
	'post_show_info'               => array(
		'image',
		'author',
		'date',
		'like',
		'category',
		'comment',
		'tag',
		'author_info',
		'share',
		'navigation',
		'related',
		'comments_list',
	),

	// Portfolio
	'enable_portfolio'             => true,
	'portfolios_count'             => 12,
	'portfolios_layout'            => 'grid',
	'portfolios_column'            => 4,
	'portfolios_load'              => '',
	'portfolio_show_info'          => array(
		'image',
		'category',
		'skill',
		'author',
		'url',
		'client',
		'copyright',
		'share',
		'related',
		'comments_list',
	),
	'portfolio_related_title'      => esc_html__( 'Related Portfolios', 'alpha' ),
	'portfolio_related_count'      => 4,
	'portfolio_related_column'     => 4,
	'portfolio_type'               => 'default',
	'portfolio_show_excerpt'       => true,
	'portfolio_excerpt_type'       => '',
	'portfolio_excerpt_length'     => 15,
	'portfolio_read_more_label'    => esc_html__( 'View More', 'alpha' ) . '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-' . ( is_rtl() ? 'left' : 'right' ) . '"></i>',

	// Member
	'enable_member'                => false,
	'members_count'                => 12,
	'members_layout'               => 'grid',
	'members_column'               => 4,
	'members_load'                 => '',
	'member_show_info'             => array(
		'image',
		'title',
		'category',
		'contact',
		'share',
		'appointment',
		'navigation',
		'related',
	),
	'member_related_title'         => esc_html__( 'Related Members', 'alpha' ),
	'member_related_count'         => 4,
	'member_related_column'        => 4,
	'member_type'                  => 'default',
	'member_overlay'               => 'zoom_dark',
	'member_show_excerpt'          => true,
	'member_excerpt_type'          => '',
	'member_excerpt_length'        => 15,

	// Products
	'products_column'              => 4,
	'products_gap'                 => 'lg',
	'products_load'                => '',

	// Single Product
	'single_product_type'          => 'vertical',
	'product_data_type'            => 'tab',
	'single_product_sticky'        => true,
	'single_product_sticky_mobile' => true,
	'product_description_title'    => esc_html__( 'Description', 'alpha' ),
	'product_specification_title'  => esc_html__( 'Additional', 'alpha' ),
	'product_reviews_title'        => esc_html__( 'Reviews', 'alpha' ),
	'show_buy_now_btn'             => false,
	'buy_now_text'                 => esc_html__( 'Buy Now', 'alpha' ),

	'product_vendor_info_title'    => esc_html__( 'Vendor Info', 'alpha' ),
	'product_upsells_count'        => 4,
	'product_related_count'        => 4,
	'product_type_new_period'      => 7,
	'product_more_title'           => esc_html__( 'More Products From This Vendor', 'alpha' ),
	'product_more_order'           => 'rand',
	'products_count_select'        => '9, _12, 24, 36',

	// GDPR Options
	'show_cookie_info'             => true,
	// translators: %1$s represents link url, %2$s represents represents a closing tag.
	'cookie_text'                  => sprintf( esc_html__( 'By browsing this website, you agree to our %1$sprivacy policy%2$s.', 'alpha' ), '<a href="#">', '</a>' ),
	'cookie_version'               => 1,
	'cookie_agree_btn'             => esc_html__( 'I Agree', 'alpha' ),

	'product_hide_vendor_tab'      => false,

	// Product Excerpt
	'prod_excerpt_type'            => '',
	'prod_excerpt_length'          => 20,

	// Shop Advanced
	'new_product_period'           => 7,
	'shop_ajax'                    => false,
	'prod_open_click_mob'          => true,
	'catalog_mode'                 => false,
	'catalog_price'                => true,
	'catalog_cart'                 => false,
	'catalog_review'               => false,

	// layouts
	'layout_default_wrap'          => 'container',
	'archive_layout_right_sidebar' => 'blog-sidebar',
	'single_layout_right_sidebar'  => 'blog-sidebar',
	'shop_layout_left_sidebar'     => 'shop-sidebar',
	'error_layout_wrap'            => 'full',
	'error_layout_ptb'             => 'hide',

	// Vendor related options
	'vendor_products_column'       => 3,
	'vendor_style'                 => 'default',
	'vendor_style_option'          => 'theme',
	'vendor_soldby_style_option'   => 'theme',

	// Shop / Product Type
	'product_type'                 => '',
	'classic_hover'                => '',
	'addtocart_pos'                => '',
	'quickview_pos'                => 'bottom',
	'wishlist_pos'                 => '',
	'show_info'                    => array(
		'label',
		'custom_label',
		'price',
		'rating',
		'addtocart',
		'quickview',
		'wishlist',
		'compare',
	),
	'sold_by_label'                => esc_html__( 'Sold By', 'alpha' ),
	'hover_change'                 => true,
	'quickview_type'               => '',
	'quickview_thumbs'             => 'horizontal',
	'content_align'                => 'left',
	'split_line'                   => false,
	'product_show_attrs'           => array(),

	// Shop / Category Type
	'category_type'                => '',
	'subcat_cnt'                   => '5',
	'category_show_icon'           => '',
	'category_overlay'             => '',

	// WooCommerce
	'cart_show_clear'              => true,

	// Advanced / Lazyload
	'skeleton_screen'              => false,
	'lazyload'                     => false,
	'lazyload_bg'                  => '#f4f4f4',
	'loading_animation'            => false,

	// Advanced / Search
	'live_search'                  => true,
	'search_post_type'             => class_exists( 'Woocommerce' ) ? 'product' : 'post',
	'sales_popup'                  => '',
	'sales_popup_title'            => esc_html__( 'Someone Purchased', 'alpha' ),
	'sales_popup_count'            => 5,
	'sales_popup_start_delay'      => 60,
	'sales_popup_interval'         => 60,
	'sales_popup_category'         => '',
	'sales_popup_mobile'           => true,
	'custom_image_sizes'           => array(
		'size_name' => '',
		'width'     => '',
		'height'    => '',
	),
	'image_quality'                => 82,
	'big_image_threshold'          => 2560,

	// optimize wizard
	'google_webfont'               => false,
	'lazyload_menu'                => false,
	'menu_last_time'               => 0,
	'popup_last_time'              => 0,
	'mobile_disable_slider'        => false,
	'mobile_disable_animation'     => false,

	'resource_disable_gutenberg'   => false,
	'resource_disable_wc_blocks'   => false,
	'resource_disable_elementor'   => false,
	'resource_disable_rev'         => false,
	'resource_async_js'            => true,
	'resource_split_tasks'         => true,
	'resource_after_load'          => true,

	// Custom CSS & JS
	'custom_css'                   => '',
	'custom_js'                    => '',

	// Share
	'social_login'                 => true,
	'share_type'                   => 'framed',
	'share_icons'                  => array( 'facebook', 'twitter', 'pinterest', 'instagram', 'linkedin' ),
	'share_use_hover'              => false,

	'custom_image_size'            => array(
		'Width'  => '',
		'Height' => '',
	),

	//
	'mobile_bar_icons'             => array(),
);

if ( class_exists( 'WooCommerce' ) ) {
	$alpha_option['navigator_items'] = array_merge(
		$alpha_option['navigator_items'],
		array(
			'product_type'   => array( esc_html__( 'Shop / Product Type', 'alpha' ), 'section' ),
			'category_type'  => array( esc_html__( 'Shop / Category Type', 'alpha' ), 'section' ),
			'product_detail' => array( esc_html__( 'WooCommerce / Product Page', 'alpha' ), 'section' ),
		)
	);
}

$alpha_option['menu_labels'] = json_encode(
	array(
		'new' => get_theme_mod( 'primary_color', $alpha_option['primary_color'] ),
		'hot' => get_theme_mod( 'secondary_color', $alpha_option['secondary_color'] ),
	)
);

$social_shares = alpha_get_social_shares();

foreach ( $social_shares as $key => $data ) {
	$alpha_option[ 'social_addr_' . $key ] = '';
}

$alpha_option = apply_filters( 'alpha_theme_option_default_values', $alpha_option );

/**
 * Fires after setting default options. Here you can change default options,
 * add or remove.
 *
 * @since 4.0
 */
do_action( 'alpha_after_default_options' );

