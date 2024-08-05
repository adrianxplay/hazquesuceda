<?php
/**
 * Alpha filter clean sidebar widget
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

// direct load is not allowed
defined( 'ABSPATH' ) || die;

class Alpha_Filter_Clean_Sidebar_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget-filter-clean',
			'description' => esc_html__( 'Display filter clean button in shop sidebar.', 'alpha-core' ),
		);

		$control_ops = array( 'id_base' => 'filter-clean-widget' );

		parent::__construct( 'filter-clean-widget', ALPHA_DISPLAY_NAME . esc_html__( ' - Filter Clean', 'alpha-core' ), $widget_ops, $control_ops );
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args     The Arguments.
	 * @param array $instance The Widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! function_exists( 'alpha_is_shop' ) || ! alpha_is_shop() ) {
			return;
		}

		global $alpha_layout;
		if ( ! empty( $alpha_layout['top_sidebar'] ) && 'hide' != $alpha_layout['top_sidebar'] ) {
			// show Clean_All button
			add_filter(
				'alpha_shop_filter_clean_show',
				function() {
					return true;
				}
			);
			return;
		}
		$show_clean = true;
		$url        = parse_url( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$shop_url   = str_replace( array( 'https://', 'http://', 'www.' ), '', get_permalink( wc_get_page_id( 'shop' ) ) );
		if ( $shop_url == $url['path'] && ( empty( $url['query'] ) || 'only_posts=1' == $url['query'] ) ) {
			$show_clean = false;
		}

		wp_enqueue_style( 'alpha-filter-clean', ALPHA_CORE_URI . '/framework/builders/sidebar/widgets/filter-clean/filter-clean' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', null, ALPHA_CORE_VERSION, 'all' );
		extract( $args ); // @codingStandardsIgnoreLine
		?>

		<?php if ( $show_clean || alpha_get_option( 'archive_ajax' ) ) : ?>
			<div class="filter-actions">
				<label><?php esc_html_e( 'Filter :', 'alpha-core' ); ?></label>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="filter-clean"><?php esc_html_e( 'Clean All', 'alpha-core' ); ?></a>
			</div>
		<?php endif; ?>

		<?php
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget->form
	 *
	 * @param array $instance The Instance.
	 */
	public function form( $instance ) {
		?>
		<p><?php esc_html_e( 'Display filter clean button in shop sidebar.', 'alpha-core' ); ?></p>
		<?php
	}
}
