<?php
/**
 * post.php
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 */
defined( 'ABSPATH' ) || die;

// for timeline blog layout
global $prev_post_month, $prev_post_year, $post_count;

$type         = alpha_get_loop_prop( 'type' );
$posts_layout = alpha_get_loop_prop( 'posts_layout' );
$classes      = array_merge( get_post_class(), alpha_get_loop_prop( 'loop_classes', array() ) );
$wrap_class   = array();
$wrap_attrs   = '';

$post_timestamp = strtotime( get_the_date() );
$post_month     = date( 'n', $post_timestamp );
$post_year      = get_the_date( 'o' );
$current_date   = get_the_date( 'o-n' );

// Not for Post Grid Widget
if ( ! isset( $shortcode_type ) ) {
	if ( alpha_get_loop_prop( 'widget' ) ) {
		global $alpha_post_idx;
		++ $alpha_post_idx;

		if ( 'creative' == $posts_layout ) {
			$repeaters    = alpha_get_loop_prop( 'repeaters' );
			$wrap_class[] = 'grid-item';
			if ( ! empty( $repeaters ) && isset( $repeaters['ids'][ $alpha_post_idx ] ) ) {
				$wrap_class[] = $repeaters['ids'][ $alpha_post_idx ];
			}
			if ( ! empty( $repeaters ) && isset( $repeaters['ids'][0] ) ) {
				$wrap_class[] = $repeaters['ids'][0];
			}
			$wrap_attrs = ' data-grid-idx="' . (int) $alpha_post_idx . '"';

		} elseif ( 'slider' == $posts_layout && alpha_get_loop_prop( 'row_cnt' ) >= 2 && 1 == $alpha_post_idx % alpha_get_loop_prop( 'row_cnt', 2 ) ) {
			echo '<div class="post-col">';
		}
	} else {
		if ( 'masonry' == $posts_layout ) {
			$wrap_class[] = 'grid-item';
		}
	}
}

// Template & Widget
if ( 'timeline' == $posts_layout ) {

	if ( $prev_post_month != $post_month || ( $prev_post_month == $post_month && $prev_post_year != $post_year ) ) :
		$post_count = 1;
		?>
		<div class="timeline-date"><h3><?php echo get_the_date( 'F Y' ); ?></h3></div>
		<?php
	endif;

	$wrap_class[] = ( 1 == $post_count % 2 ? 'left' : 'right' );
	$wrap_class[] = 'timeline-box';
}

$wrap_class = apply_filters( 'alpha_post_wrap_class', $wrap_class );
$wrap_attrs = apply_filters( 'alpha_post_wrap_attrs', $wrap_attrs );

do_action( 'alpha_before_post_start' );

?>

<div class="post-wrap <?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>"<?php echo esc_attr( $wrap_attrs ); ?> data-post-image="<?php echo esc_attr( alpha_get_loop_prop( 'image_size' ) ); ?>">

	<?php
	if ( ! isset( $shortcode_type ) ) {
		do_action( 'alpha_post_loop_before_item', $type );
	}
	?>

	<article class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php
		if ( ! alpha_get_template_part( 'posts/type/' . alpha_get_loop_prop( 'cpt' ), $type ) ) {
			alpha_get_template_part( 'posts/type/post', 'default' );
		}
		?>
	</article>

	<?php
	if ( ! isset( $shortcode_type ) ) {
		do_action( 'alpha_post_loop_after_item', $type );
	}
	?>

</div>

<?php
if ( 'slider' == $posts_layout && isset( $GLOBALS['alpha_post_idx'] ) && alpha_get_loop_prop( 'row_cnt' ) >= 2 && 0 == $GLOBALS['alpha_post_idx'] % alpha_get_loop_prop( 'row_cnt' ) ) {
	echo '</div>';
}

if ( 'timeline' == $posts_layout ) {
	$prev_post_year  = $post_year;
	$prev_post_month = $post_month;

	$post_count++;
}

do_action( 'alpha_after_post_end' );
