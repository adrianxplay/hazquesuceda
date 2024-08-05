<?php
/**
 * LearnPress Compatibility
 *
 * @since 4.0.0
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Core_LearnPress' ) ) :
	class Alpha_Core_LearnPress extends Alpha_Base {

		/**
		 * Constructor
		 */
		public function __construct() {
			// enqueue styles
			add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ), 1999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 25 );

			// enable overriding template files
			add_filter( 'learn-press/override-templates', '__return_true' );

			// layout builder options
			add_filter( 'alpha_get_layout', array( $this, 'update_alpha_layout' ) );
			add_filter( 'learn_press_page_title', '__return_false' );

			add_filter( 'alpha_sidebar_widgets', array( $this, 'add_widgets' ), 20 );

			// Extend search content
			// add_filter( 'alpha_search_content_types', array( $this, 'add_to_search_content' ) );

			/**
			 * template actions
			 */
			// LP()->template( 'general' )->remove( 'learn-press/before-main-content', array( '<div class="lp-archive-courses">', 'lp-archive-courses-open' ), -100 );
			remove_action( 'learn-press/before-main-content', LP()->template( 'general' )->func( 'breadcrumb' ), 10 );
			// LP()->template( 'general' )->remove( 'learn-press/after-main-content', array( '</div>', 'lp-archive-courses-close' ), 100 );
			// Single course
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/meta-primary', 10 );
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/title', 10 );
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/meta-secondary', 10 );
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/meta-secondary', 10 );
			LP()->template( 'course' )->remove_callback( 'learn-press/course-content-summary', 'single-course/sidebar', 85 );
			remove_action( 'learn-press/course-summary-sidebar', LP()->template( 'course' )->func( 'course_sidebar_preview' ), 10 );
			LP()->template( 'course' )->remove( 'learn-press/course-content-summary', array( '<div class="entry-content-left">', 'entry-content-left-open' ), 35 );
			LP()->template( 'course' )->remove( 'learn-press/course-content-summary', array( '<!-- end entry content left --> </div>', 'entry-content-left-close' ), 80 );
			// Profile
			LP()->template( 'profile' )->remove( 'learn-press/user-profile-account', array( ' <div class="lp-profile-left">', 'user-profile-account-left-open' ), 5 );

			remove_action( 'learn-press/user-profile-account', LP()->template( 'profile' )->func( 'avatar' ), 10 );
			remove_action( 'learn-press/user-profile-account', LP()->template( 'profile' )->func( 'socials' ), 10 );
			LP()->template( 'profile' )->remove( 'learn-press/user-profile-account', array( ' </div>', 'user-profile-account-left-close' ), 15 );
			// Archive course
			LP()->template( 'course' )->remove_callback( 'learn-press/before-courses-loop-item', 'loop/course/categories', 1010 );
			//LP()->template( 'course' )->remove_callback( 'learn-press/after-courses-loop', 'loop/course/pagination.php', 10 );
			// remove_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->text( '<!-- START .course-content-meta --> <div class="course-wrap-meta">', 'course-wrap-meta-open' ), 20 );

			add_action( 'learn-press/before-main-content', 'alpha_print_layout_before', -9999 );
			add_action( 'learn-press/after-main-content', 'alpha_print_layout_after', 9999 );

			add_action( 'learn-press/user-profile-socials', array( $this, 'replace_profile_socials' ), 10, 3 );
			// Single Course
			add_action( 'learn-press/course-content-summary', LP()->template( 'course' )->callback( 'single-course/title' ), 10 );
			add_action( 'learn-press/course-content-summary', LP()->template( 'course' )->callback( 'single-course/meta-primary' ), 10 );
			add_action( 'learn-press/course-content-summary', LP()->template( 'course' )->callback( 'single-course/meta-secondary' ), 10 );
			add_action( 'learn-press/course-meta-primary-left', LP()->template( 'course' )->func( 'user_progress' ), 30 );
			add_action( 'learn-press/course-content-summary', LP()->template( 'course' )->func( 'course_sidebar_preview' ), 55 );
			// Profile
			add_action( 'learn-press/user-profile-tabs', LP()->template( 'profile' )->text( ' <div class="lp-profile-left">', 'user-profile-account-left-open' ), 5 );
			add_action( 'learn-press/user-profile-tabs', LP()->template( 'profile' )->func( 'avatar' ), 5 );
			add_action( 'learn-press/user-profile-tabs', LP()->template( 'profile' )->func( 'socials' ), 5 );
			add_action( 'learn-press/user-profile-tabs', LP()->template( 'profile' )->text( ' </div>', 'user-profile-account-left-close' ), 5 );
			add_action( 'learn-press/user-profile-tabs', LP()->template( 'profile' )->func( 'header' ), 5 );
			// Archive course
			add_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->callback( 'single-course/meta/duration' ), 40 );
			/*add_action(
				'learn-press/after-courses-loop',
				function() {
					global $wp_query;
					echo alpha_get_pagination( $wp_query, 'learn-press-pagination' );
				},
				10
			);*/
			add_filter(
				'learn_press_pagination_args',
				function( $args ) {
					unset( $args['type'] );
					$args['end_size']  = 1;
					$args['mid_size']  = 2;
					$args['prev_text'] = '<i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-left"></i> ' . esc_html__( 'Prev', 'alpha' );
					$args['next_text'] = esc_html__( 'Next', 'alpha' ) . ' <i class="' . ALPHA_ICON_PREFIX . '-icon-long-arrow-right"></i>';
					return $args;
				}
			);
			add_action( 'learn-press/after-courses-loop', array( $this, 'no_results_found' ), 20 );
			add_filter( 'learn_press_course_instructor_html', array( $this, 'get_instructor_html' ), 10, 3 );
			add_action( 'pre_get_posts', array( $this, 'filter_courses_args' ), 150 );

			LP()->template( 'course' )->remove(
				'learn-press/after-courses-loop-item',
				array(
					'<!-- START .course-content-meta --> <div class="course-wrap-meta">',
					'course-wrap-meta-open',
				),
				20
			);
			LP()->template( 'course' )->remove_callback( 'learn-press/after-courses-loop-item', 'single-course/meta/duration', 20 );
			LP()->template( 'course' )->remove_callback( 'learn-press/after-courses-loop-item', 'single-course/meta/level', 20 );

			remove_action( 'learn-press/after-courses-loop-item', LP()->template( 'course' )->func( 'count_object' ), 20 );
			LP()->template( 'course' )->remove(
				'learn-press/after-courses-loop-item',
				array(
					'</div> <!-- END .course-content-meta -->',
					'course-wrap-meta-close',
				),
				20
			);

			// Profile page
			add_filter( 'learn-press/profile-tabs', array( $this, 'profile_tabs' ) );
		}

		public function dequeue_styles() {
			// dequeue LearnPress styles
			wp_dequeue_style( 'lp-font-awesome-5' );
			wp_dequeue_style( 'lp-bundle' );
			wp_dequeue_style( 'learnpress' );
			wp_deregister_style( 'lp-font-awesome-5' );
			wp_deregister_style( 'lp-bundle' );
			wp_deregister_style( 'learnpress' );
		}

		public function enqueue_styles() {
			wp_enqueue_style( 'alpha-tab', alpha_core_framework_uri( '/widgets/tab/tab' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ), array(), ALPHA_CORE_VERSION );
			wp_enqueue_style( 'alpha-learnpress', ALPHA_CORE_URI . '/inc/plugins/learnpress/learnpress' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), '4.0' );
		}

		public function replace_profile_socials( $socials, $id, $self ) {
			$icons     = alpha_get_option( 'share_icons' );
			$icon_type = alpha_get_option( 'share_type' );
			$custom    = alpha_get_option( 'share_use_hover' ) ? '' : ' use-hover';

			$ret = array();
			foreach ( $socials as $key => $social ) {
				$ret[ $key ] = str_replace( '<a href', '<a ' . ( 'class="social-icon ' . esc_attr( $icon_type . $custom ) . ' social-' . esc_attr( $key ) . '"' ) . ' href', $social );
			}
			return $ret;
		}

		public function update_alpha_layout( $layout ) {
			if ( learn_press_is_course_archive() ) {
				if ( is_search() ) {
					$page_title = sprintf( __( 'You searched for: &ldquo;%s&rdquo;', 'alpha-core' ), get_search_query() );

					if ( get_query_var( 'paged' ) ) {
						$page_title .= sprintf( __( '&nbsp;&ndash; Page %s', 'alpha-core' ), get_query_var( 'paged' ) );
					}
				} elseif ( is_tax() ) {

					$page_title = single_term_title( '', false );

				} else {

					$courses_page_id = learn_press_get_page_id( 'courses' );
					$page_title      = get_the_title( $courses_page_id );

				}
				$layout['title'] = $page_title;
			}
			return $layout;
		}

		public function add_widgets( $widgets ) {
			$add_widget = array( 'lms_course_categories', 'lms_instructors', 'lms_course_prices' );
			$widgets    = array_merge( $widgets, $add_widget );
			return $widgets;
		}

		public function get_instructor_html( $html, $author, $id ) {
			global $course_loop;
			if ( $course_loop || learn_press_is_profile() ) {
				$html = '<figure>' . get_avatar( $author, 70 ) . '</figure>' . $html;
			}
			return $html;
		}

		public function filter_courses_args( $query ) {
			if ( ! $query->is_main_query() ) {
				return;
			}

			if ( ! is_post_type_archive( 'lp_course' ) && ! is_tax( 'course_category' ) ) {
				return;
			}

			if ( isset( $_GET['author'] ) ) {
				$user = get_user_by( 'login', $_GET['author'] );
				if ( $user ) {
					$query->set( 'author__in', $user->ID );
				}
			}

			if ( isset( $_GET['lp_price'] ) && ( 'paid' == $_GET['lp_price'] || 'free' == $_GET['lp_price'] ) ) {
				$query->set(
					'meta_query',
					array(
						array(
							'key'     => '_lp_price',
							'compare' => 'paid' == $_GET['lp_price'] ? '!=' : '==',
							'value'   => '',
						),
					)
				);
			}

			if ( isset( $_GET['order'] ) ) {
				if ( 'name' == $_GET['order'] ) {
					$query->set( 'orderby', 'name' );
					$query->set( 'order', 'ASC' );
				} elseif ( 'date' == $_GET['order'] ) {
					$query->set( 'orderby', 'date' );
					$query->set( 'order', 'DESC' );
				} elseif ( 'price_low' == $_GET['order'] ) {
					$query->set( 'orderby', 'meta_value' );
					$query->set( 'order', 'ASC' );
					$query->set( 'meta_key', '_lp_price' );
				} elseif ( 'price_high' == $_GET['order'] ) {
					$query->set( 'orderby', 'meta_value' );
					$query->set( 'order', 'DESC' );
					$query->set( 'meta_key', '_lp_price' );
				}
			}
		}

		/**
		 * Extend Search Content Types
		 *
		 * @since 4.0
		 */
		public function add_to_search_content( $types ) {
			$types['lp_course'] = esc_html__( 'Course', 'alpha-core' );
			return $types;
		}

		/**
		 * Replace profile tab icons
		 *
		 * @since 4.0
		 */
		public function profile_tabs( $tabs ) {
			if ( isset( $tabs['overview'] ) ) {
				$tabs['overview']['icon'] = '<i class="' . THEME_ICON_PREFIX . '-icon-user-overview"></i>';
			}
			if ( isset( $tabs['courses'] ) ) {
				$tabs['courses']['icon'] = '<i class="' . THEME_ICON_PREFIX . '-icon-book"></i>';
			}
			if ( isset( $tabs['quizzes'] ) ) {
				$tabs['quizzes']['icon'] = '<i class="' . THEME_ICON_PREFIX . '-icon-plugin"></i>';
			}
			if ( isset( $tabs['orders'] ) ) {
				$tabs['orders']['icon'] = '<i class="' . THEME_ICON_PREFIX . '-icon-cart2"></i>';
			}
			if ( isset( $tabs['settings'] ) ) {
				$tabs['settings']['icon'] = '<i class="' . ALPHA_ICON_PREFIX . '-icon-cog"></i>';
			}
			if ( isset( $tabs['logout'] ) ) {
				$tabs['logout']['icon'] = '<i class="' . THEME_ICON_PREFIX . '-icon-logout"></i>';
			}
			return $tabs;
		}

		/**
		 * No results found function
		 *
		 * @since 4.0
		 */
		public function no_results_found() {
			if ( ! have_posts() ) {
				do_action( 'alpha_template_nothing_found', '', '' );
			}
		}
	}
endif;

Alpha_Core_LearnPress::get_instance();
