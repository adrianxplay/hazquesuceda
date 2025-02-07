<?php

/**
 * Setup wizard
 */

// Plugins
add_filter( 'alpha_plugin', 'alpha_additional_plugins' );

// Demos
add_filter( 'alpha_demo_types', 'alpha_addtional_demos' );

if ( ! function_exists( 'alpha_additional_plugins' ) ) {
	function alpha_additional_plugins( $plugins ) {
		$plugins[2]['required'] = false;
		$plugins                = array_merge(
			$plugins,
			array(
				array(
					'name'       => 'Advanced Custom Fields',
					'slug'       => 'advanced-custom-fields',
					'required'   => false,
					'url'        => 'advanced-custom-fields/acf.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/acf.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'Essential Grid',
					'slug'       => 'essential-grid',
					'required'   => false,
					'url'        => 'essential-grid/essential-grid.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/essential-grid.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'LearnPress',
					'slug'       => 'learnpress',
					'required'   => false,
					'url'        => 'learnpress/learnpress.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/learnpress.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'Post Types Unlimited',
					'slug'       => 'post-types-unlimited',
					'required'   => false,
					'url'        => 'post-types-unlimited/post-types-unlimited.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/unlimited.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'Slider Revolution',
					'slug'       => 'revslider',
					'required'   => false,
					'url'        => 'revslider/revslider.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/revslider.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'The Events Calendar',
					'slug'       => 'the-events-calendar',
					'required'   => false,
					'url'        => 'the-events-calendar/the-events-calendar.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/events-calendar.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'WPForms Lite',
					'slug'       => 'wpforms-lite',
					'required'   => false,
					'url'        => 'wpforms-lite/wpforms.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/wpform.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'Customizer Search',
					'slug'       => 'customizer-search',
					'required'   => false,
					'url'        => 'customizer-search/customizer-search.php',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/custom-search.png',
					'visibility' => 'setup_wizard',
				),
				array(
					'name'       => 'YITH Woocommerce Wishlist',
					'slug'       => 'yith-woocommerce-wishlist',
					'required'   => false,
					'version'    => '3.0.20',
					'image_url'  => ALPHA_ASSETS . '/images/admin/plugins/yith_wishlist.png',
					'url'        => 'yith-woocommerce-wishlist/init.php',
					'visibility' => 'setup_wizard',
				),
			)
		);
		return $plugins;
	}
}

if ( ! function_exists( 'alpha_addtional_demos' ) ) {
	function alpha_addtional_demos() {
		return array(
			'blog-2'                => array(
				'alt'          => 'Blog 2',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/blog-2.jpg',
				'filter'       => 'other',
				'plugins'      => array( 'wpforms-lite' ),
				'editors'      => array(),
				'status'       => 'new',
				'is_container' => true,
			),
			'app-2'                 => array(
				'alt'          => 'App Landing 2',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/app-2.jpg',
				'filter'       => 'other',
				'plugins'      => array( 'alpus-flexbox', 'wpforms-lite' ),
				'editors'      => array(),
				'status'       => 'new',
				'is_container' => true,
			),
			'main'                  => array(
				'alt'     => 'Main',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/main.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'corporate'             => array(
				'alt'     => 'Corporate 1',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/corporate.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'corporate-2'           => array(
				'alt'     => 'Corporate 2',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/corporate-2.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'corporate-3'           => array(
				'alt'     => 'Corporate 3',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/corporate-3.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'corporate-4'           => array(
				'alt'     => 'Corporate 4',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/corporate-4.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'corporate-5'           => array(
				'alt'     => 'Corporate 5',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/corporate-5.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'farm-store'            => array(
				'alt'     => 'Farm Store',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/farm-store.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'health-coach'          => array(
				'alt'       => 'Health Coach',
				'img'       => ALPHA_ASSETS . '/images/admin/setup-wizard/health-coach.jpg',
				'filter'    => 'fitness',
				'plugins'   => array( 'woocommerce', 'revslider', 'the-events-calendar', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'revslider' => array( 'slider-1.zip' ),
				'editors'   => array(),
			),
			'photography'           => array(
				'alt'       => 'Photography 1',
				'img'       => ALPHA_ASSETS . '/images/admin/setup-wizard/photography.jpg',
				'filter'    => 'other',
				'plugins'   => array( 'revslider', 'wpforms-lite' ),
				'revslider' => array( 'slider-1.zip' ),
				'editors'   => array(),
			),
			'photography-2'         => array(
				'alt'     => 'Photography 2',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/photography-2.jpg',
				'filter'  => 'other',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'yoga'                  => array(
				'alt'     => 'Yoga',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/yoga.jpg',
				'filter'  => 'fitness',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'modern-events-calendar-lite', 'wpforms-lite' ),
				'editors' => array(),
			),
			'build'                 => array(
				'alt'       => 'Build',
				'img'       => ALPHA_ASSETS . '/images/admin/setup-wizard/build.jpg',
				'filter'    => 'business',
				'plugins'   => array( 'revslider', 'wpforms-lite' ),
				'revslider' => array( 'slider-3.zip' ),
				'editors'   => array(),
				'status'    => 'popular',
			),
			'fashion'               => array(
				'alt'     => 'Fashion',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/fashion.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'cannabis'              => array(
				'alt'     => 'Cannabis',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/cannabis.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'medical'               => array(
				'alt'     => 'Clinic',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/medical.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'clean-home'            => array(
				'alt'     => 'Clean Home',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/clean-home.jpg',
				'filter'  => 'clean',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'education'             => array(
				'alt'       => 'Education',
				'img'       => ALPHA_ASSETS . '/images/admin/setup-wizard/education.jpg',
				'filter'    => 'other',
				'plugins'   => array( 'learnpress', 'revslider', 'the-events-calendar', 'wpforms-lite' ),
				'revslider' => array( 'slider-1.zip' ),
				'editors'   => array(),
			),
			'real-estate'           => array(
				'alt'     => 'Real Estate',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/real-estate.jpg',
				'filter'  => 'other',
				'plugins' => array( 'advanced-custom-fields', 'essential-grid', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'resume'                => array(
				'alt'     => 'Resume',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/resume.jpg',
				'filter'  => 'other',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'restaurant'            => array(
				'alt'     => 'Restaurant',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/restaurant.jpg',
				'filter'  => 'food',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'restaurant-2'          => array(
				'alt'            => 'Restaurant 2',
				'img'            => ALPHA_ASSETS . '/images/admin/setup-wizard/restaurant-2.jpg',
				'filter'         => 'food',
				'plugins'        => array( 'alpus-flexbox', 'advanced-custom-fields', 'contact-form-7' ),
				'editors'        => array(),
				'is_container'   => true,
				'grid_container' => true,
			),
			'dentist'               => array(
				'alt'     => 'Dentist',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/dentist.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite', 'post-types-unlimited' ),
				'editors' => array(),
			),
			'furniture'             => array(
				'alt'     => 'Furniture',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/furniture.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'app'                   => array(
				'alt'     => 'App Landing',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/app.jpg',
				'filter'  => 'other',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			// 'gym'                   => array(
			// 	'alt'     => 'Gym',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/gym.jpg',
			// 	'filter'  => 'fitness',
			// 	'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// 	'status'  => 'popular',
			// ),
			'shoes'                 => array(
				'alt'     => 'Shoes',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/shoes.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'tea'                   => array(
				'alt'     => 'Tea',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/tea.jpg',
				'filter'  => 'food',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'jewelry'               => array(
				'alt'     => 'Jewelry',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/jewelry.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'fashion-2'             => array(
				'alt'     => 'Fashion 2',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/fashion-2.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'landing-product'       => array(
				'alt'     => 'Landing Product',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/landing-product.jpg',
				'filter'  => 'other',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			// 'beauty'                => array(
			// 	'alt'     => 'Beauty',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/beauty.jpg',
			// 	'filter'  => 'shop',
			// 	'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			// 'babycare'              => array(
			// 	'alt'     => 'Babycare',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/babycare.jpg',
			// 	'filter'  => 'shop',
			// 	'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			// 'wine'                  => array(
			// 	'alt'     => 'Wine',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/wine.jpg',
			// 	'filter'  => 'shop',
			// 	'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			'business-consulting'   => array(
				'alt'     => 'Business Consulting',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/business-consulting.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'business-consulting-2' => array(
				'alt'     => 'Business Consulting 2',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/business-consulting-2.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'business-consulting-3' => array(
				'alt'     => 'Business Consulting 3',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/business-consulting-3.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'business-consulting-4' => array(
				'alt'     => 'Business Consulting 4',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/business-consulting-4.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'wpforms-lite' ),
				'editors' => array(),
			),
			'business-consulting-5' => array(
				'alt'          => 'Business Consulting 5',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/business-consulting-5.jpg',
				'filter'       => 'business',
				'plugins'      => array( 'alpus-flexbox', 'advanced-custom-fields', 'wpforms-lite' ),
				'editors'      => array(),
				'is_container' => true,
			),
			// 'burger'                => array(
			// 	'alt'     => 'Burger',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/burger.jpg',
			// 	'filter'  => 'shop',
			// 	'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// 	'status'  => 'popular',
			// ),
			'law-firm'              => array(
				'alt'     => 'Law Firm',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/law-firm.jpg',
				'filter'  => 'business',
				'plugins' => array( 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'insurance'             => array(
				'alt'     => 'Insurance',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/insurance.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'cafe'                  => array(
				'alt'     => 'Cafe',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/cafe.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'bicycle'               => array(
				'alt'     => 'Bicycle',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/bicycle.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
			),
			'finance'               => array(
				'alt'     => 'Finance',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/finance.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'electronics'           => array(
				'alt'     => 'Electronics',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/electronics.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			// 'sunglass'              => array(
			// 	'alt'     => 'Sunglass',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/sunglass.jpg',
			// 	'filter'  => 'shop',
			// 	'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			// 'watch'                 => array(
			// 	'alt'     => 'Watch',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/watch.jpg',
			// 	'filter'  => 'shop',
			// 	'plugins' => array( 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			'auto-services'         => array(
				'alt'     => 'Auto Services',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/auto-services.jpg',
				'filter'  => 'business',
				'plugins' => array( 'post-types-unlimited', 'woocommerce', 'yith-woocommerce-wishlist', 'wpforms-lite', 'contact-form-7' ),
				'editors' => array(),
			),
			'hotel'                 => array(
				'alt'     => 'Hotel',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/hotel.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'contact-form-7' ),
				'editors' => array(),
			),
			'digital-agency'        => array(
				'alt'     => 'Digital Agency',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/digital-agency.jpg',
				'filter'  => 'business',
				'plugins' => array( 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'psychology'            => array(
				'alt'     => 'Psychology',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/psychology.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite', 'contact-form-7' ),
				'editors' => array(),
			),
			'plumber'               => array(
				'alt'     => 'Plumber',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/plumber.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'barber'                => array(
				'alt'     => 'Barber\'s Shop',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/barber.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors' => array(),
			),
			'startup-agency'        => array(
				'alt'     => 'Startup Agency',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/startup-agency.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'interior-design'       => array(
				'alt'     => 'Interior Design',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/interior-design.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'loan'                  => array(
				'alt'     => 'Loan',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/loan.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite', 'contact-form-7' ),
				'editors' => array(),
			),
			'transport'             => array(
				'alt'     => 'Transport',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/transport.jpg',
				'filter'  => 'business',
				'plugins' => array( 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			// 'makeup'                => array(
			// 	'alt'     => 'Makeup',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/makeup.jpg',
			// 	'filter'  => 'business',
			// 	'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			'pet'                   => array(
				'alt'     => 'Pet Care',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/pet.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors' => array(),
			),
			'environmental-ngo'     => array(
				'alt'     => 'Environmental NGO',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/environmental-ngo.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			// 'cryptocurrency'        => array(
			// 	'alt'     => 'Cryptocurrency',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/cryptocurrency.jpg',
			// 	'filter'  => 'business',
			// 	'plugins' => array( 'post-types-unlimited', 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			'accountant'            => array(
				'alt'     => 'Accountant',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/accountant.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'it-services'           => array(
				'alt'     => 'IT Services',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/it.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			// 'hosting'               => array(
			// 	'alt'     => 'Hosting',
			// 	'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/hosting.jpg',
			// 	'filter'  => 'business',
			// 	'plugins' => array( 'wpforms-lite' ),
			// 	'editors' => array(),
			// ),
			'gardener'              => array(
				'alt'     => 'Gardener',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/gardener.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'travel'                => array(
				'alt'     => 'Travel Agency',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/travel.jpg',
				'filter'  => 'business',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'seo'                   => array(
				'alt'     => 'Seo',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/seo.jpg',
				'filter'  => 'business',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'festival'              => array(
				'alt'     => 'Festival',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/festival.jpg',
				'filter'  => 'other',
				'plugins' => array( 'contact-form-7', 'the-events-calendar' ),
				'editors' => array(),
			),
			'taxi'                  => array(
				'alt'     => 'Taxi',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/taxi.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'wpforms-lite' ),
				'editors' => array(),
			),
			'tools'                 => array(
				'alt'     => 'Tools',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/tools.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors' => array(),
				'status'  => 'popular',
			),
			'nutritionist'          => array(
				'alt'     => 'Nutritionist',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/nutritionist.jpg',
				'filter'  => 'food',
				'plugins' => array( 'advanced-custom-fields', 'post-types-unlimited', 'wpforms-lite' ),
				'editors' => array(),
			),
			'sports'                => array(
				'alt'     => 'Sports',
				'img'     => ALPHA_ASSETS . '/images/admin/setup-wizard/sports.jpg',
				'filter'  => 'shop',
				'plugins' => array( 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors' => array(),
			),
			'podcast'               => array(
				'alt'          => 'Podcast',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/podcast.jpg',
				'filter'       => 'business',
				'plugins'      => array( 'alpus-flexbox', 'advanced-custom-fields', 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors'      => array(),
				'is_container' => true,
			),
			'author'                => array(
				'alt'          => 'Author',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/author.jpg',
				'filter'       => 'other',
				'plugins'      => array( 'alpus-flexbox', 'advanced-custom-fields', 'the-events-calendar', 'woocommerce', 'wpforms-lite', 'yith-woocommerce-wishlist' ),
				'editors'      => array(),
				'is_container' => true,
			),
			'blog-1'                => array(
				'alt'          => 'Blog 1',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/blog-1.jpg',
				'filter'       => 'other',
				'plugins'      => array( 'advanced-custom-fields', 'wpforms-lite' ),
				'editors'      => array(),
				'status'       => 'popular',
				'is_container' => true,
			),
			'videographer'          => array(
				'alt'          => 'Videographer',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/videographer.jpg',
				'filter'       => 'other',
				'plugins'      => array( 'alpus-flexbox', 'advanced-custom-fields', 'wpforms-lite' ),
				'editors'      => array(),
				'is_container' => true,
			),
			'influencer'            => array(
				'alt'          => 'Influencer',
				'img'          => ALPHA_ASSETS . '/images/admin/setup-wizard/influencer.jpg',
				'filter'       => 'other',
				'plugins'      => array( 'alpus-flexbox', 'advanced-custom-fields', 'wpforms-lite' ),
				'editors'      => array(),
				'is_container' => true,
			),
		);
	}
}
