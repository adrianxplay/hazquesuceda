<?php
/**
 * Alpha Events class
 *
 * Plugin compatibility with the events calendar
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

defined( 'ABSPATH' ) || die;

if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
	return;
}

use Tribe\Events\Views\V2\View;


class Alpha_Core_Tribe_Events extends Alpha_Base {


	public $events         = array();
	public $container_data = null;

	/**
	 * Constructor
	 *
	 * @since 4.0
	 */
	public function __construct() {
		// my code

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ), 25 );

		add_action( 'alpha_before_event_featured_image', array( $this, 'before_event_featured_image' ) );
		add_action( 'alpha_event_featured_image', array( $this, 'event_featured_image' ) );
		add_action( 'alpha_before_event_content', array( $this, 'before_event_content' ) );
		add_action( 'alpha_event_content', array( $this, 'event_content' ) );
		add_action( 'alpha_after_event_content', array( $this, 'after_event_content' ) );

		add_filter( 'tribe_events_views_v2_assets_should_enqueue_frontend', '__return_true' );
		add_action( 'alpha_render_events_calendar', array( $this, 'render_events_calendar' ) );

		add_filter( 'tribe_get_map_link_html', array( $this, 'get_map_link_html' ) );
		add_filter( 'tribe_get_full_address', array( $this, 'get_full_address' ) );
		add_filter( 'tribe_meta_event_tags', array( $this, 'edit_event_meta_tags' ) );

		add_filter( 'tribe_event_featured_image', array( $this, 'edit_tribe_event_featured_image' ), 1 );

		// Compatiblity with optimize wizard
		add_filter( 'alpha_include_plugins', array( $this, 'include_plugins' ) );
	}


	/**
	 * Include tribe events calendar's js in merged js list
	 *
	 * @since 4.0
	 */
	public function include_plugins( $plugins ) {
		$plugins[] = 'tribe';
		return $plugins;
	}


	/**
	 * Load preview scripts
	 *
	 * @since 4.0
	 */
	public function enqueue_script() {
		// Dequeue default tribe event breakpointjs
		wp_deregister_script( 'tribe-events-views-v2-breakpoints' );
		wp_dequeue_script( 'tribe-events-views-v2-breakpoints' );

		// Enqueue alpha defined style & script
		wp_enqueue_style( 'alpha-event-style', ALPHA_CORE_INC_URI . '/plugins/tribe_events/tribe-events' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		wp_enqueue_script( 'alpha-tribe-event-script', ALPHA_CORE_INC_URI . '/plugins/tribe_events/tribe-events' . ALPHA_JS_SUFFIX, array( 'alpha-theme' ), ALPHA_CORE_VERSION, true );

		$wp_scripts  = wp_scripts();
		$script_deps = $wp_scripts->registered['tribe-events-views-v2-viewport']->deps;
		if ( is_array( $script_deps ) ) {
			foreach ( $script_deps as $key => $value ) {
				if ( 'tribe-events-views-v2-breakpoints' == $value ) {
					unset( $wp_scripts->registered['tribe-events-views-v2-viewport']->deps[ $key ] );
					$wp_scripts->registered['tribe-events-views-v2-viewport']->deps[] = 'alpha-tribe-event-script';
				}
			}
		}
		wp_enqueue_script( 'tribe-events-views-v2-viewport' );
		wp_enqueue_script( 'tribe-events-views-v2-events-bar' );
		wp_enqueue_script( 'tribe-events-views-v2-view-selector' );
		wp_enqueue_script( 'tribe-events-views-v2-month-mobile-events' );
	}

	/**
	 * Add element before event featured image is rendered
	 *
	 * @since 4.0.0
	 * @param {Array} $atts
	 */
	public function before_event_featured_image( $atts ) {
		if ( 'list-1' == $atts['event_type'] ) {
			echo '<div class="calendar-wrap">';
				$this->event_calendar_date( $atts );
			echo '</div>';
		}
	}


	/**
	 * Render event featured image
	 *
	 * @since 4.0.0
	 * @param {Array} $atts
	 */
	public function event_featured_image( $atts ) {
		global $post;
		echo '<figure class="post-media">';
		echo '<a href="' . esc_url( get_permalink() ) . '">';
		if ( 'custom' == $atts['thumbnail_size'] && isset( $atts['thumbnail_custom_size'] ) ) {
			the_post_thumbnail( $atts['thumbnail_custom_size'] );
		} else {
			the_post_thumbnail( $atts['thumbnail_size'] );
		}
		echo '</a>';

		if ( 'list-1' != $atts['event_type'] ) {
			$this->event_calendar_date( $atts );
		}
		echo '</figure>';
	}


	/**
	 * Render content before event content is rendered
	 *
	 * @since 4.0.0
	 * @param {Array} $atts
	 */
	public function before_event_content( $atts ) {

	}


	/**
	 * Render event content
	 *
	 * @since 4.0.0
	 * @param {Array} $atts
	 */
	public function event_content( $atts ) {
		global $post;
		?>
		<div class="event-content">

			<?php
			$schedule = tribe_events_event_schedule_details();

			if ( $schedule && ( 'list-1' == $atts['event_type'] || 'list-2' == $atts['event_type'] ) ) :
				?>
			<h5 class="event-schedule"><?php echo alpha_strip_script_tags( $schedule ); ?></h5>
				<?php
			endif;
			?>

			<h3 class="event-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( $post->post_title ); ?></a></h3>

			<?php
			if ( 'widget' != $atts['event_type'] ) :
				$venue = tribe_get_venue( $post->ID );
				if ( $venue ) :
					?>

			<h5 class="event-venue"><?php echo esc_html( $venue ); ?></h5>

					<?php
				endif;
			endif;

			if ( $schedule && ( 'list-1' != $atts['event_type'] && 'list-2' != $atts['event_type'] ) ) :
				?>
			<h5 class="event-schedule"><?php echo alpha_strip_script_tags( $schedule ); ?></h5>
				<?php
			endif;

			if ( 'list-1' == $atts['event_type'] || 'list-2' == $atts['event_type'] ) :
				echo '<div class="event-excerpt">';
				echo alpha_get_excerpt( $post, $atts['excerpt_length']['size'], $atts['excerpt_by'] );
				echo '</div>';
			endif;
			?>
		</div>
		<?php
	}


	/**
	 * Render content after event content is rendered
	 *
	 * @since 4.0.0
	 * @param {Array} $atts
	 */
	public function after_event_content( $atts ) {

	}


	/**
	 * Render event calendar data on event
	 *
	 * @since 4.0.0
	 * @param {Array} $atts
	 */
	public function event_calendar_date( $atts ) {
		global $post;
		$calendar_class  = 'post-calendar';
		$calendar_class .= ' skin-' . $atts['date_skin'];
		if ( 'list-1' != $atts['event_type'] ) {
			$calendar_class .= ' ' . $atts['date_position'];
		}
		?>
		<div class="<?php echo esc_attr( $calendar_class ); ?>">
			<span class="post-day"><?php echo esc_html( tribe_get_end_date( $post, false, 'd' ) ); ?></span>
			<span class="post-month"><?php echo esc_html( tribe_get_end_date( $post, false, 'M' ) ); ?></span>
		</div>
		<?php
	}


	/**
	 * Set events calendar template variables
	 *
	 * @since 4.0.0
	 * @var {Array} $template_vars
	 */
	public function set_events_calendar_template_vars( $template_vars ) {
		global $calendar_atts;

		$template_vars['is_widget']             = true;
		$template_vars['events_calendar_title'] = $calendar_atts['events_calendar_title'];
		array_push( $template_vars['container_classes'], 'tribe-events-calendar-widget' );

		return $template_vars;
	}


	/**
	 * Render events calendar
	 *
	 * @since 4.0.0
	 */
	public function render_events_calendar( $attr ) {

		global $calendar_atts;
		$calendar_atts = $attr;

		add_filter( 'tribe_events_views_v2_view_template_vars', array( $this, 'set_events_calendar_template_vars' ), 1 );

		echo View::make( 'month' )->get_html();
	}


	/**
	 * Get map link in single event
	 *
	 * @since 4.0.0
	 */
	public function get_map_link_html( $link ) {
		global $post;

		$map_link = esc_url( tribe_get_map_link( $post->ID ) );

		$link = '';

		if ( ! empty( $map_link ) ) {
			$link = sprintf(
				'<a class="tribe-events-gmap" href="%s" title="%s" target="_blank" rel="noreferrer noopener">%s</a>',
				$map_link,
				esc_html__( 'Click to view a Google Map', 'alpha-core' ),
				esc_html__( 'View on map', 'alpha-core' )
			);
		}

		return $link;
	}


	/**
	 * Get a full address of venue
	 *
	 * @since 4.0.0
	 */
	public function get_full_address( $address ) {
		$address = str_replace( '<br>', '<span class="tribe-delimiter">,</span>', $address );
		return $address;
	}


	/**
	 * Edit meta tags for an event
	 *
	 * @since 4.0.0
	 */
	public function edit_event_meta_tags( $list ) {
		$list = str_replace( ',', '', $list );
		return $list;
	}


	/**
	 * Edit event template data
	 *
	 * @since 4.0.0
	 */
	public function edit_tribe_event_featured_image( $featured_image ) {
		if ( empty( $featured_image ) ) {
			ob_start();
			?>
			<div class="tribe-events-event-image">
				<img src="<?php echo ALPHA_ASSETS . '/images/placeholders/event-placeholder.jpg'; ?>" alt="<?php esc_attr_e( 'Event placeholder', 'alpha-core' ); ?>">
			</div>
			<?php
			$featured_image = ob_get_clean();
		}
		return $featured_image;
	}
}

/**
 * Create instance
 */
Alpha_Core_Tribe_Events::get_instance();
