<?php
/**
 * Alpha Studio
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Alpha_Studio' ) ) :

	/**
	 * The Alpha Studio class
	 *
	 * @since 1.0
	 */
	class Alpha_Studio extends Alpha_Base {

		/**
		 * Total blocks per page
		 *
		 * @since 1.0
		 */
		private $limit = 20;

		/**
		 * Block update period
		 *
		 * @since 1.0
		 */
		private $update_period = HOUR_IN_SECONDS * 24 * 7; // a week

		/**
		 * Page Builder Type
		 *
		 * 'e' => Elementor Page Builder
		 * 'w' => WPBakery Page Builder
		 */
		private $page_type = false;

		/**
		 * New Template Mode
		 *
		 * @since 1.0
		 */
		public $new_template_mode = false;

		/**
		 * Sort By
		 */
		private $sort_by = 'latest';

		/**
		 * Main Categories
		 *
		 * @since 1.0
		 */
		private $big_categories      = array();
		private $has_children        = array();
		private $block_categories    = array();
		private $template_categories = array();
		private $front_categories    = array();

		/**
		 * Get category title
		 *
		 * @since 1.0
		 */
		public function get_category_title( $title ) {
			$titles = apply_filters(
				'alpha_studio_category',
				array(
					'block'          => esc_html__( 'Block', 'alpha-core' ), // Blocks
					'demo'           => esc_html__( 'Demo', 'alpha-core' ),
					'about'          => esc_html__( 'About', 'alpha-core' ),
					'banner'         => esc_html__( 'Banner', 'alpha-core' ),
					'slider'         => esc_html__( 'Slider', 'alpha-core' ),
					'contact'        => esc_html__( 'Contact', 'alpha-core' ),
					'counters'       => esc_html__( 'Counters', 'alpha-core' ),
					'info_boxes'     => esc_html__( 'Info Boxes', 'alpha-core' ),
					'testimonial'    => esc_html__( 'Testimonial', 'alpha-core' ),
					'video'          => esc_html__( 'Video', 'alpha-core' ),
					'megamenu'       => esc_html__( 'Megamenu', 'alpha-core' ),
					'pricing'        => esc_html__( 'Pricing', 'alpha-core' ),
					'other'          => esc_html__( 'Other', 'alpha-core' ),
					'header'         => esc_html__( 'Header', 'alpha-core' ),
					'footer'         => esc_html__( 'Footer', 'alpha-core' ),
					'template'       => esc_html__( 'Template', 'alpha-core' ), // Templates
					'shop'           => esc_html__( 'Shop', 'alpha-core' ),
					'product_layout' => esc_html__( 'Single Product', 'alpha-core' ),
					'single'         => esc_html__( 'Single', 'alpha-core' ),
					'archive'        => esc_html__( 'Archive', 'alpha-core' ),
					'popup'          => esc_html__( 'Popup', 'alpha-core' ),
					'cart'           => esc_html__( 'Cart', 'alpha-core' ),
					'checkout'       => esc_html__( 'Checkout', 'alpha-core' ),
					'error-404'      => esc_html__( 'Error 404', 'alpha-core' ),
					'type'           => esc_html__( 'Post Types', 'alpha-core' ),
					'favourites'     => esc_html__( 'Favourites', 'alpha-core' ),
					'my-templates'   => esc_html__( 'My Templates', 'alpha-core' ),
				)
			);
			return isset( $titles[ $title ] ) ? $titles[ $title ] : '';
		}

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {

			$this->big_categories      = apply_filters( 'alpha_studio_big_category', array( 'block', 'header', 'footer', 'popup', 'template', 'favourites', 'my-templates' ) );
			$this->has_children        = apply_filters( 'alpha_studio_has_children', array( 'block', 'template' ) );
			$this->block_categories    = apply_filters( 'alpha_studio_block_category', array( 'demo', 'about', 'banner', 'slider', 'contact', 'counters', 'info_boxes', 'testimonial', 'video', 'megamenu', 'pricing', 'other' ) );
			$this->template_categories = apply_filters( 'alpha_studio_template_category', array( 'shop', 'product_layout', 'cart', 'checkout', 'single', 'archive', 'error-404', 'type' ) );
			$this->front_categories    = apply_filters( 'alpha_studio_front_category', array( 'block', 'header', 'footer', 'product_layout', 'shop', 'single', 'archive', 'popup', 'type', 'error-404', 'cart', 'checkout', 'favourites', 'my-templates' ) );

			if ( isset( $_REQUEST['vc_editable'] ) && $_REQUEST['vc_editable'] && isset( $_POST['block_id'] ) ) {
				$vc_template_option_name = '';
				try {
					$vc_template_option_name = vc_manager()->vc()->templatesPanelEditor()->getOptionName();
					add_filter( 'pre_option_' . $vc_template_option_name, array( $this, 'render_frontend_block' ), 10, 3 );
				} catch ( Exception $e ) {
				}
				return;
			}

			if ( wp_doing_ajax() && isset( $_POST['type'] ) ) {
				$this->page_type         = sanitize_text_field( $_POST['type'] );
				$this->new_template_mode = isset( $_POST['new_template'] );
			}

			add_action( 'wp_ajax_alpha_studio_import', array( $this, 'import' ) );
			add_action( 'wp_ajax_alpha_studio_filter_category', array( $this, 'filter_category' ) );
			add_action( 'wp_ajax_alpha_studio_favour_block', array( $this, 'favour_block' ) );
			add_action( 'wp_ajax_alpha_studio_save', array( $this, 'update_custom_meta_fields_in_fronteditor' ) );

			if ( 'post.php' == $GLOBALS['pagenow'] || 'post-new.php' == $GLOBALS['pagenow'] ) {
				if ( defined( 'ELEMENTOR_VERSION' ) && function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {
					$this->page_type = 'e';
					add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ), 30 );
					add_action( 'elementor/editor/footer', array( $this, 'get_page_content' ) );
					add_filter(
						'alpha_builder_addon_html',
						function( $html ) {
							$html[] = array(
								'elementor' => '<li id="alpha-elementor-panel-alpha-studio"><i class="fas fa-layer-group"></i>' . sprintf( esc_html__( '%s Studio', 'alpha-core' ), ALPHA_DISPLAY_NAME ) . '</li>',
							);
							return $html;
						},
						9
					);
				} elseif ( defined( 'WPB_VC_VERSION' ) && function_exists( 'alpha_is_wpb_preview' ) && alpha_is_wpb_preview() ) {
					$this->page_type = 'w';
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
					add_action( 'admin_footer', array( $this, 'get_page_content' ) );
				} elseif ( 'post.php' != $GLOBALS['pagenow'] || 'edit' == $_REQUEST['action'] ) {
					$this->page_type = 'g';
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );
					add_action( 'admin_footer', array( $this, 'get_page_content' ) );
				}

				/**
				 * Fires after setting configuration for page builder.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_studio_for_builder', $this );
			} elseif ( ! wp_doing_ajax() || ! isset( $_POST['type'] ) ) {
				$this->new_template_mode = true;

				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					$this->page_type = 'e';
				} elseif ( defined( 'WPB_VC_VERSION' ) ) {
					$this->page_type = 'w';
				} else {
					$this->page_type = 'g';
				}

				add_action( 'admin_footer', array( $this, 'get_page_content' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 1001 );

				/**
				 * Fires to determine page builder.
				 *
				 * @since 1.0
				 */
				do_action( 'alpha_determine_builder', $this );
			}
		}

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0
		 */
		public function enqueue() {
			if ( doing_action( 'elementor/editor/after_enqueue_styles' ) ) {
				wp_enqueue_style( 'alpha-admin-fonts', apply_filters( 'alpha_admin_fonts', '//fonts.googleapis.com/css?family=Poppins%3A400%2C500%2C600%2C700' ) );
			}
			if ( defined( 'ALPHA_VERSION' ) ) {
				wp_enqueue_style( 'alpha-magnific-popup', ALPHA_ASSETS . '/vendor/jquery.magnific-popup/magnific-popup' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_VERSION );
				wp_enqueue_script( 'isotope-pkgd', ALPHA_ASSETS . '/vendor/isotope/isotope.pkgd.min.js', array( 'jquery-core', 'imagesloaded' ), '3.0.6', true );
			}
			wp_enqueue_style( 'alpha-studio', alpha_core_framework_uri( '/addons/studio/studio' . ( is_rtl() ? '-rtl' : '' ) . '.min.css' ) );
			wp_enqueue_script( 'alpha-studio', alpha_core_framework_uri( '/addons/studio/studio' . ALPHA_JS_SUFFIX ), array(), ALPHA_CORE_VERSION, true );

			$studio_vars = array(
				'wpnonce'                 => wp_create_nonce( 'alpha_studio_nonce' ),
				'limit'                   => $this->limit,
				'texts'                   => array(
					'loading_failed'     => esc_html__( 'Loading failed!', 'alpha-core' ),
					'importing_error'    => esc_html__( 'There was an error when importing block. Please try again later!', 'alpha-core' ),
					'coming_soon'        => esc_html__( 'Coming Soon...', 'alpha-core' ),
					'theme_display_name' => ALPHA_DISPLAY_NAME,
					'studio_tooltip'     => ALPHA_DISPLAY_NAME . esc_html__( ' Studio', 'alpha-core' ),
				),
				'alpus_flexbox_installed' => class_exists( 'Alpus_Flexbox' ),
			);

			if ( $this->new_template_mode ) {
				$studio_vars['page_type'] = '';

				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					$studio_vars['page_type'] = 'e';
				} elseif ( defined( 'WPB_VC_VERSION' ) ) {
					$studio_vars['page_type'] = 'w';
				} else {
					$studio_vars['page_type'] = 'g';
				}
			} elseif ( isset( $_GET['post'] ) || isset( $_GET['post_id'] ) ) {

				// Add start template at start
				if ( isset( $_GET['post'] ) && (int) $_GET['post'] ) {
					$post_id = (int) $_GET['post'];
				} else {
					$post_id = (int) $_GET['post_id'];
				}
				$start_template = get_post_meta( $post_id, 'alpha_start_template', true );
				if ( $start_template && isset( $start_template['id'] ) && isset( $start_template['type'] ) ) {

					$id   = (int) $start_template['id'];
					$type = $start_template['type'];

					if ( ( 'e' == $type && defined( 'ELEMENTOR_VERSION' ) && function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) || ( 'w' == $type && defined( 'WPB_VC_VERSION' ) && function_exists( 'alpha_is_wpb_preview' ) && alpha_is_wpb_preview() ) ||
					'g' == $type ) {

						$studio_vars['start_template'] = $id;

					} elseif ( 'my' == $type ) {
						// @start feature: fs_pb_elementor
						if ( alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $id, '_elementor_edit_mode', true ) && function_exists( 'alpha_is_elementor_preview' ) && alpha_is_elementor_preview() ) {

							$studio_vars['start_template_content'] = array(
								'content' => json_decode( get_post_meta( $id, '_elementor_data', true ) ),
								'meta'    => array(
									'page_css' => get_post_meta( $id, 'page_css', true ),
								),
							);
						}
						// @end feature: fs_pb_elementor
					}
				}
				delete_post_meta( $post_id, 'alpha_start_template' );
			}
			/**
			 * Fiters styles and scripts in studio.
			 *
			 * @since 1.0
			 */
			wp_localize_script( 'alpha-studio', 'alpha_studio', apply_filters( 'alpha_studio_vars', $studio_vars ) );
		}

		/**
		 * Import Alpha blocks in WPB frontend editor
		 *
		 * @since 1.0
		 */
		public function render_frontend_block( $flag, $option, $default ) {
			if ( isset( $_POST['meta'] ) ) {
				$GLOBALS['alpha_studio_meta'] = $_POST['meta'];
				if ( isset( $_POST['meta']['page_js'] ) ) {
					add_filter( 'print_footer_scripts', array( $this, 'print_custom_js_frontend_editor' ) );
				}
			}
			if ( isset( $_POST['content'] ) && $_POST['content'] ) {
				return array( '1' => array( 'template' => wp_unslash( $_POST['content'] ) ) );
			}
			return '';
		}

		/**
		 * Print custom js frontend editor.
		 *
		 * @since 1.0
		 */
		public function print_custom_js_frontend_editor( $flag ) {
			global $alpha_studio_meta;
			if ( $alpha_studio_meta && isset( $alpha_studio_meta['page_js'] ) ) {
				echo '<script data-type="alpha-studio-custom-js">';
					echo trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', wp_unslash( $alpha_studio_meta['page_js'] ) ) );
				echo '</script>';
			}
			unset( $GLOBALS['alpha_studio_meta'] );
			return $flag;
		}


		/**
		 * Import alpha blocks in Elementor, Visual Composer backend editor, WPBakery
		 *
		 * @since 1.0
		 */
		public function import( $pure_return = false ) {
			check_ajax_referer( 'alpha_studio_nonce', 'wpnonce' );

			if ( isset( $_POST['block_id'] ) ) {
				if ( isset( $_POST['mine'] ) && 'true' == $_POST['mine'] ) {
					$id     = (int) $_POST['block_id'];
					$result = array();

					// @start feature: fs_pb_elementor
					if ( 'e' == $_POST['type'] && alpha_get_feature( 'fs_pb_elementor' ) && defined( 'ELEMENTOR_VERSION' ) && get_post_meta( $id, '_elementor_edit_mode', true ) ) {
						$result = array(
							'content' => json_decode( get_post_meta( $id, '_elementor_data', true ) ),
							'meta'    => array(
								'page_css' => get_post_meta( $id, 'page_css', true ),
							),
						);
					}
					// @end feature: fs_pb_elementor

					if ( 'g' == $_POST['type'] && use_block_editor_for_post( $id ) ) {
						$result = array(
							'content' => rawurldecode( get_post( $id )->post_content ),
							'meta'    => array(
								'page_css' => get_post_meta( $id, 'page_css', true ),
								'page_js'  => get_post_meta( $id, 'page_js', true ),
							),
						);
					}

					/**
					 * Filters the import result.
					 *
					 * @param array $result The import result
					 * @since 1.0
					 */
					return wp_send_json( apply_filters( 'alpha_import_result', $result ) );
				}

				require_once alpha_core_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
				$importer_api = new Alpha_Importer_API();
				$args         = $importer_api->generate_args( false );
				$url          = add_query_arg( $args, $importer_api->get_url( 'studio_block_content' ) );
				$url          = add_query_arg( array( 'block_id' => ( (int) $_POST['block_id'] ) ), $url );
				$block        = $importer_api->get_response( $url );
				if ( is_wp_error( $block ) || ! $block || ! isset( $block['content'] ) ) {
					if ( $pure_return ) {
						return false;
					}
					echo json_encode( array( 'error' => esc_js( __( 'Security issue found! Please try again later.', 'alpha-core' ) ) ) );
					die();
				}
				$block_content = $block['content'];

				// process attachments
				if ( isset( $block['images'] ) ) {
					$block_content = $this->process_posts( $block_content, $block['images'] );
				}
				// process contact forms
				if ( isset( $block['posts'] ) ) {
					$block_content = $this->process_posts( $block_content, $block['posts'], false );
				}
				// replace urls
				$block_content = str_replace( $block['url'], get_home_url(), $block_content );

				if ( 'e' == $this->page_type ) {
					$block_content = json_decode( $block_content, true );
				}

				$result = array( 'content' => $block_content );
				if ( isset( $block['meta'] ) && $block['meta'] ) {
					$result['meta'] = json_decode( $block['meta'], true );
				}
				/**
				 * Filters the import result.
				 *
				 * @param array $result The import result
				 * @param array $block  The import block
				 * @since 1.0
				 */
				$result = apply_filters( 'alpha_import_result', $result, $block );
				if ( $pure_return ) {
					return $result;
				}
				return wp_send_json( $result );
			}
		}

		/**
		 * Filter category.
		 *
		 * @since 1.0
		 */
		public function filter_category() {
			check_ajax_referer( 'alpha_studio_nonce', 'wpnonce' );

			if ( isset( $_POST['type'] ) ) {
				$this->page_type = $_POST['type'];
			}
			if ( isset( $_POST['sort_by'] ) ) {
				$this->sort_by = $_POST['sort_by'];
			}

			if ( isset( $_POST['full_wrapper'] ) && $_POST['full_wrapper'] ) {
				$this->get_page_content();
			} else {
				$page   = isset( $_POST['page'] ) && $_POST['page'] ? (int) $_POST['page'] : 1;
				$blocks = $this->get_blocks();

				if ( 'downloads' == $this->sort_by ) {
					usort(
						$blocks,
						function( $comp1, $comp2 ) {
							if ( $comp1['l'] == $comp2['l'] ) {
								return 0;
							}
							return ( $comp1['l'] < $comp2['l'] ) ? 1 : -1;
						}
					);
				} elseif ( 'likes' == $this->sort_by ) {
					usort(
						$blocks,
						function( $comp1, $comp2 ) {
							if ( $comp1['f'] == $comp2['f'] ) {
								return 0;
							}
							return ( $comp1['f'] < $comp2['f'] ) ? 1 : -1;
						}
					);
				}

				$category_blocks = array();
				$favourites_map  = array();
				$favourites_key  = '';
				if ( 'e' == $this->page_type ) {
					$favourites_key = 'alpha_studio_favourites_e';
				} elseif ( 'w' === $this->page_type ) {
					$favourites_key = 'alpha_studio_favourites_w';
				} else {
					$favourites_key = 'alpha_studio_favourites_g';
				}
				/**
				 * For theme developers
				 * Filters favourite key in terms of page builder.
				 *
				 * @param string $favourites_key  The favourite key
				 * @param string $page_type       The page builder
				 * @since 1.0
				 */
				$favourites_key = apply_filters( 'alpha_favourites_key', $favourites_key, $this->page_type );
				if ( $favourites_key ) {
					$favourites = get_option( $favourites_key );
					if ( $favourites ) {
						foreach ( $favourites as $favourite ) {
							$favourites_map[ $favourite['block_id'] ] = true;
						}
					}
				}

				if ( ! empty( $_POST['search'] ) ) {
					// Filtered blocks
					$search = $_POST['search'];
					$blocks = array_filter(
						$blocks,
						function ( $block ) use ( $search ) {
							$pattern = '/' . $search . '/i';
							return  false != preg_match( $pattern, $block['d'] ) || false != preg_match( $pattern, $block['t'] );
						}
					);
				}

				$all_blocks = $blocks;
				$all_limit  = $this->limit;
				if ( isset( $_POST['category_id'] ) ) {
					$category_id = $_POST['category_id'];

					if ( (int) $category_id ) {
						// Templates in given category
						foreach ( $blocks as $block ) {
							$categories = explode( ',', $block['c'] ?? '' );
							if ( in_array( $category_id, $categories ) ) {
								$category_blocks[] = $block;
							}
						}
						$all_blocks      = $category_blocks;
						$category_blocks = array_slice( $category_blocks, ( $page - 1 ) * $this->limit, $this->limit );

					} elseif ( '*' == $category_id ) {
						$all_blocks = $favourites;
						if ( isset( $_POST['current_count'] ) ) {
							$current_count   = (int) $_POST['current_count'];
							$category_blocks = array_slice( $favourites, $current_count, $this->limit );
							$all_limit       = $current_count;
						} else {
							$category_blocks = array_slice( $favourites, ( $page - 1 ) * $this->limit, $this->limit );
						}
					} elseif ( 'my' == $category_id ) {
						$posts           = get_posts(
							array(
								'post_type'   => ALPHA_NAME . '_template',
								'numberposts' => -1,
							)
						);
						$all_blocks      = $posts;
						$category_blocks = array_slice( $posts, ( $page - 1 ) * $this->limit, $this->limit );

					} elseif ( 0 == $category_id ) {
						// All blocks
						$all_blocks      = $blocks;
						$category_blocks = array_slice( $blocks, ( $page - 1 ) * $this->limit, $this->limit );
					}
				}

				echo '<div id="total_pages">' . ceil( count( $all_blocks ) / $all_limit ) . '</div>';

				if ( ! empty( $category_blocks ) ) {
					$args = array(
						'blocks'         => $category_blocks,
						'studio'         => $this,
						'favourites_map' => $favourites_map,
					);
					require alpha_core_framework_path( ALPHA_CORE_ADDONS . '/studio/blocks.php' );
				}
			}
			die;
		}

		/**
		 * Favour block
		 *
		 * @since 1.0
		 */
		public function favour_block() {
			check_ajax_referer( 'alpha_studio_nonce', 'wpnonce' );

			$block_id = isset( $_POST['block_id'] ) && $_POST['block_id'] ? (int) $_POST['block_id'] : 0;

			if ( $block_id > 0 ) {
				$favourites_key = '';
				if ( 'e' == $this->page_type ) {
					$favourites_key = 'alpha_studio_favourites_e';
				} elseif ( 'w' == $this->page_type ) {
					$favourites_key = 'alpha_studio_favourites_w';
				} else {
					$favourites_key = 'alpha_studio_favourites_g';
				}
				/**
				 * For theme developers
				 * Filters favourite key in terms of page builder.
				 *
				 * @param string $favourites_key  The favourite key
				 * @param string $page_type       The page builder
				 * @since 1.0
				 */
				$favourites_key = apply_filters( 'alpha_favourites_key', $favourites_key, $this->page_type );
				if ( $favourites_key ) {
					$favourites = get_option( $favourites_key );
					if ( ! $favourites ) {
						$favourites = array();
					}

					if ( isset( $_POST['active'] ) ) {
						if ( $_POST['active'] ) { // Add
							$blocks = $this->get_blocks();
							foreach ( $blocks as $block ) {
								if ( $block_id == $block['block_id'] ) {
									$favourites[] = $block;
									update_option( $favourites_key, $favourites );
									break;
								}
							}
						} else { // Remove
							foreach ( $favourites as $i => $favourite ) {
								if ( $block_id == $favourite['block_id'] ) {
									unset( $favourites[ $i ] );
									update_option( $favourites_key, $favourites );

									if ( isset( $_POST['current_count'] ) ) {
										$current_count   = (int) $_POST['current_count'];
										$category_blocks = array_slice( $favourites, $current_count, 1 );
										$favourites_map  = array();
										foreach ( $favourites as $favourite ) {
											$favourites_map[ $favourite['block_id'] ] = true;
										}

										if ( ! empty( $category_blocks ) ) {
											$args = array(
												'blocks' => $category_blocks,
												'studio' => $this,
												'favourites_map' => $favourites_map,
											);
											require alpha_core_framework_path( ALPHA_CORE_ADDONS . '/studio/blocks.php' );
										}
									}

									break;
								}
							}
						}

						require_once alpha_core_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
						$importer_api = new Alpha_Importer_API();
						$args         = $importer_api->generate_args( false );
						$url          = add_query_arg( $args, $importer_api->get_url( 'studio_block_content' ) );
						$url          = add_query_arg(
							array(
								'block_id'    => $block_id,
								'like_status' => $_POST['active'],
							),
							$url
						);
						$block        = $importer_api->get_response( $url );
					}
				}
			}
			die;
		}

		/**
		 * Save post meta fields such as custom css and js in frontend editor
		 *
		 * @since 1.0
		 */
		public function update_custom_meta_fields_in_fronteditor() {
			check_ajax_referer( 'alpha_studio_nonce', 'nonce' );
			if ( isset( $_POST['fields'] ) && isset( $_POST['post_id'] ) ) {
				$post_id = intval( $_POST['post_id'] );
				foreach ( $_POST['fields'] as $key => $value ) {
					if ( ! $value ) {
						continue;
					}
					$original_value = get_post_meta( $post_id, $key, true );
					if ( strpos( $original_value, $value ) === false ) {
						if ( strpos( $value, $original_value ) !== false ) {
							$original_value = '';
						}
						update_post_meta( $post_id, $key, $original_value . wp_strip_all_tags( wp_unslash( $value ) ) );
					}
				}
				wp_send_json_success();
			}
		}

		/**
		 * Get page content.
		 *
		 * @since 1.0
		 */
		public function get_page_content() {
			$block_categories  = $this->get_block_categories();
			$blocks            = $this->get_blocks();
			$only_blocks_count = 0;

			if ( is_array( $blocks ) ) {
				foreach ( $blocks as $block ) {
					$categories = explode( ',', $block['c'] ?? '' );
					foreach ( $categories as $category ) {
						if ( (int) $category < 8 || (int) $category > 12 ) {
							++ $only_blocks_count;
							break;
						}
					}
				}
			}
			if ( is_array( $block_categories ) ) {
				$args = apply_filters(
					'alpha_studio_blocks_args',
					array(
						'categories'          => $block_categories,
						'page_type'           => $this->page_type,
						'studio'              => $this,
						'total_pages'         => ceil( count( $blocks ) / $this->limit ),
						'total_count'         => count( $blocks ),
						'big_categories'      => $this->big_categories,
						'has_children'        => $this->has_children,
						'block_categories'    => $this->block_categories,
						'template_categories' => $this->template_categories,
						'front_categories'    => $this->front_categories,
						'blocks_pages'        => ceil( $only_blocks_count / $this->limit ),
					)
				);
				require alpha_core_framework_path( ALPHA_CORE_ADDONS . '/studio/blocks-wrapper.php' );
			}
		}

		/**
		 * Import related posts such as attachments and contact forms
		 *
		 * @since 1.0
		 */
		public function process_posts( $block_content, $posts, $is_attachment = true ) {
			if ( ! trim( $posts ) ) {
				return $block_content;
			}
			$posts = json_decode( trim( $posts ), true );

			if ( empty( $posts ) ) {
				return $block_content;
			}

			if ( isset( $posts['revslider'] ) ) {
				if ( class_exists( 'RevSlider' ) ) {
					// Importer remote API
					require_once alpha_core_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
					$importer_api   = new Alpha_Importer_API( $posts['revslider'][0] );
					$demo_file_path = $importer_api->get_remote_demo();
					if ( $demo_file_path && ! is_wp_error( $demo_file_path ) ) {
						$slider   = new RevSlider();
						$imported = $slider->importSliderFromPost( true, false, $demo_file_path . '/' . $posts['revslider'][1] );
						$importer_api->delete_temp_dir();
						if ( is_array( $imported ) && $imported['success'] && isset( $imported['sliderID'] ) ) {
							$block_content = str_replace( '{{{' . $posts['revslider'][2] . '}}}', $imported['sliderID'], $block_content );
						}
					}
				}
				unset( $posts['revslider'] );
			}

			// Check if image is already imported by its ID.
			$id_arr = array();
			foreach ( array_keys( $posts ) as $old_id ) {
				$id_arr[] = ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id );
			}
			$args = array(
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => '_alpha_studio_id',
						'value'   => $id_arr,
						'compare' => 'IN',
					),
				),
			);
			if ( $is_attachment ) {
				$args['post_type']   = 'attachment';
				$args['post_status'] = 'inherit';
			} else {
				$args['post_type']   = 'wpcf7_contact_form';
				$args['post_status'] = 'publish';
			}
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post ) {
					$old_id = str_replace( ( (int) $_POST['block_id'] ) . '-', '', get_post_meta( $post->ID, '_alpha_studio_id', true ) );

					if ( 'wpcf7_contact_form' == $post->post_type ) {
						if ( 'w' == $this->page_type ) {
							$new_content = '[contact-form-7 id="' . $post->ID . '"]';
						} else {
							$new_content = '[contact-form-7 id=\\"' . $post->ID . '\\"]';
						}
					} else { // attachment
						$new_content = $post->ID;
					}

					$block_content = str_replace( '"cf7_form":"{{{' . ( (int) $old_id ) . '}}}"', '"cf7_form":"' . ( (int) $post->ID ) . '"', $block_content );
					$block_content = str_replace( '"form_id":"{{{' . ( (int) $old_id ) . '}}}"', '"form_id":"' . ( (int) $post->ID ) . '"', $block_content );
					$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', $new_content, $block_content );

					unset( $posts[ $old_id ] );
				}
			}

			if ( ! empty( $posts ) ) {

				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
					define( 'WP_LOAD_IMPORTERS', true ); // we are loading importers
				}

				if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
					require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				}

				if ( ! class_exists( 'WP_Import' ) ) { // if WP importer doesn't exist
					require_once alpha_core_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/wordpress-importer.php' );
				}

				if ( current_user_can( 'edit_posts' ) && class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {

					$importer                    = new WP_Import();
					$importer->fetch_attachments = true;

					if ( $is_attachment ) {

						add_filter( 'upload_mimes', array( $this, 'enable_svg_import' ), 99 );

						foreach ( $posts as $old_id => $image_url ) {
							$post_data = array(
								'post_title'   => substr( $image_url, strrpos( $image_url, '/' ) + 1, -4 ),
								'post_content' => '',
								'upload_date'  => date( 'Y-m-d H:i:s' ),
								'post_status'  => 'inherit',
							);

							if ( 0 === strpos( $image_url, '//' ) ) {
								$image_url = 'https:' . $image_url;
							}
							$import_id = $importer->process_attachment( $post_data, $image_url );
							if ( is_wp_error( $import_id ) ) {
								// if image does not exist
								if ( 'e' == $this->page_type ) {
									// replace for svg media
									$block_content = str_replace( '"id":{{{' . ( (int) $old_id ) . '}}},"url":"', '"id":"","url":"', $block_content );
									$new_content   = '"id":"","url":""';
								} else {
									$new_content = '';

									$GLOBALS['alpha_studio_attachment'] = $import_id;
									$block_content                      = preg_replace_callback(
										'|\{\{\{' . ( (int) $old_id ) . ':([^\}]*)\}\}\}|',
										function( $match ) {
											return '"' . $match[1] . '":""';
										},
										$block_content
									);
									unset( $GLOBALS['alpha_studio_attachment'] );
								}
							} else {
								update_post_meta( $import_id, '_alpha_studio_id', ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ) );
								$new_content = $import_id;
							}
							$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', $new_content, $block_content );
						}
					} else {
						foreach ( $posts as $old_id => $old_post_data ) {
							$post_data = array(
								'post_title'   => sanitize_text_field( $old_post_data['title'] ),
								'post_type'    => sanitize_text_field( $old_post_data['post_type'] ),
								'post_content' => ! empty( $old_post_data['post_content'] ) ? $old_post_data['post_content'] : '',
								'upload_date'  => date( 'Y-m-d H:i:s' ),
								'post_status'  => 'publish',
							);
							$post_data = wp_slash( $post_data );
							$import_id = wp_insert_post( $post_data, true );
							if ( is_wp_error( $import_id ) ) {
								// if post does not exist
								$new_content = '';
							} else {
								update_post_meta( $import_id, '_alpha_studio_id', ( (int) $_POST['block_id'] ) . '-' . ( (int) $old_id ) );
								if ( isset( $old_post_data['meta'] ) ) {
									foreach ( $old_post_data['meta'] as $meta_key => $meta_value ) {
										update_post_meta( $import_id, $meta_key, $meta_value );
									}
								}
								if ( 'w' == $this->page_type ) {
									$new_content = '[contact-form-7 id="' . $import_id . '"]';
								} else {
									$new_content = '[contact-form-7 id=\\"' . $import_id . '\\"]';
								}
							}
							$block_content = str_replace( '"cf7_form":"{{{' . ( (int) $old_id ) . '}}}"', '"cf7_form":"' . ( (int) $import_id ) . '"', $block_content );
							$block_content = str_replace( '"form_id":"{{{' . ( (int) $old_id ) . '}}}"', '"form_id":"' . ( (int) $import_id ) . '"', $block_content );
							$block_content = str_replace( '{{{' . ( (int) $old_id ) . '}}}', $new_content, $block_content );
						}
					}
				}
			}
			/**
			 * Filters styles and scripts for related posts such as attachments and contact forms.
			 *
			 * @since 1.0
			 */
			return apply_filters( 'alpha_studio_block_content', $block_content );
		}

		/**
		 * Get block categories.
		 *
		 * @since 1.0
		 */
		private function get_block_categories() {
			// get block categories
			if ( 'e' == $this->page_type ) {
				$transient_key = 'alpha_block_categories_e';
			} elseif ( 'w' == $this->page_type ) {
				$transient_key = 'alpha_block_categories_w';
			} else {
				$transient_key = 'alpha_block_categories_g';
			}
			/**
			 * For theme developers
			 * Filters transient key in terms of page builder.
			 *
			 * @param string $transient_key   The transient key
			 * @param string $page_type       The page builder
			 * @since 1.0
			 */
			$transient_key = apply_filters( 'alpha_transient_key', $transient_key, $this->page_type );

			$block_categories = get_site_transient( $transient_key );

			if ( ! $block_categories ) {
				require_once alpha_core_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
				$importer_api = new Alpha_Importer_API();

				$args          = $importer_api->generate_args( false );
				$args['limit'] = $this->limit;
				$args['type']  = $this->page_type;
				if ( 'e' == $this->page_type ) {
					$args['is_container'] = alpha_elementor_if_container_active() ? 1 : 0;
				}
				$block_categories = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_block_categories' ) ) );

				if ( is_wp_error( $block_categories ) || ! $block_categories ) {
					return esc_html__( 'Could not connect to the API Server! Please try again later.', 'alpha-core' );
				}
				set_site_transient( $transient_key, $block_categories, $this->update_period );
			}

			// Add favourite block category
			if ( 'e' == $this->page_type ) {
				$favourite_key = 'alpha_studio_favourites_e';
			} elseif ( 'w' == $this->page_type ) {
				$favourite_key = 'alpha_studio_favourites_w';
			} else {
				$favourite_key = 'alpha_studio_favourites_g';
			}
			/**
			 * For theme developers
			 * Filters favourite key in terms of page builder.
			 *
			 * @param string $favourites_key  The favourite key
			 * @param string $page_type       The page builder
			 * @since 1.0
			 */
			$favourite_key = apply_filters( 'alpha_favourites_key', $favourite_key, $this->page_type );

			$favourites = get_option( $favourite_key );
			if ( ! $favourites ) {
				$favourites = array();
			}

			$block_categories[] = array(
				'id'    => '*',
				'title' => 'favourites',
				'count' => count( $favourites ),
				'total' => ceil( count( $favourites ) / $this->limit ),
			);

			$templates_count = wp_count_posts( ALPHA_NAME . '_template' )->publish;

			$block_categories[] = array(
				'id'    => 'my',
				'title' => 'my-templates',
				'count' => $templates_count,
				'total' => ceil( $templates_count / $this->limit ),
			);

			return $block_categories;
		}

		/**
		 * Get blocks.
		 *
		 * @since 1.0
		 */
		private function get_blocks() {
			// get blocks
			if ( 'e' == $this->page_type ) {
				$transient_key = 'alpha_blocks_e';
			} elseif ( 'w' == $this->page_type ) {
				$transient_key = 'alpha_blocks_wpb';
			} else {
				$transient_key = 'alpha_blocks_g';
			}
			/**
			 * For theme developers
			 * Filters transient key in terms of page builder.
			 *
			 * @param string $transient_key   The transient key
			 * @param string $page_type       The page builder
			 * @since 1.0
			 */
			$transient_key = apply_filters( 'alpha_transient_key', $transient_key, $this->page_type );

			$blocks = get_site_transient( $transient_key );

			if ( ! $blocks ) {
				if ( ! isset( $importer_api ) ) {
					require_once alpha_core_framework_path( ALPHA_FRAMEWORK_ADMIN . '/importer/importer-api.php' );
					$importer_api = new Alpha_Importer_API();
					$args         = $importer_api->generate_args( false );
					$args['type'] = $this->page_type;

					if ( 'e' == $this->page_type ) {
						$args['is_container'] = alpha_elementor_if_container_active() ? 1 : 0;
					}
				}
				$blocks = $importer_api->get_response( add_query_arg( $args, $importer_api->get_url( 'studio_blocks' ) ) );
				if ( is_wp_error( $blocks ) || ! $blocks ) {
					return esc_html__( 'Could not connect to the API Server! Please try again later.', 'alpha-core' );
				}
				set_site_transient( $transient_key, $blocks, $this->update_period );
			}
			return $blocks;
		}

		public function enable_svg_import( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		}
	}

	new Alpha_Studio;

endif;
