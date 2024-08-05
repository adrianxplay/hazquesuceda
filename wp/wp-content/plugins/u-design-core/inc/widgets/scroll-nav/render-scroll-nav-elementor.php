<?php
/**
 * Render template for scroll navigation widget.
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.4.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'slider_item'            => 1,
			'slider_direction'       => 'vertical',
			'slider_height'          => 'full_screen',
			'slider_speed'           => 300,
			'navigator_content_list' => array(),
		),
		$atts
	)
);

$this->add_render_attribute( 'swiperslider_area_attr', 'class', 'slider-wrapper scroll-nav' );

$widget_id = $this->get_data( 'id' );

$slider_settings = [
	'slidesPerView'  => $slider_item,
	'direction'      => $slider_direction,
	'mousewheel'     => array(
		'enabled'        => true,
		'releaseOnEdges' => true,
		'allowInTarget'  => 'full_screen' == $slider_height ? false : true,
	),
	// 'freeMode'       => true,
	'arrow'          => false,
	'pagination'     => false,
	'speed'          => absint( $slider_speed ),
	'allowTouchMove' => false,
	'dotsContainer'  => '.slider-scroll-nav-dots-' . $widget_id,
];
$this->add_render_attribute( 'swiperslider_area_attr', 'data-slider-options', wp_json_encode( $slider_settings ) );

$pagination_html = '';
$index           = 0;
?>
	<!-- Swiper -->
	<div class="scroll-nav-wrapper">
		<div <?php echo $this->get_render_attribute_string( 'swiperslider_area_attr' ); ?>>
			<?php foreach ( $navigator_content_list as  $navigatorcontent ) : ?>
				<div class="swiper-slide">
					<div class="scroll-navigation-inner">
						<?php
							alpha_print_template( $navigatorcontent['navigator_block'] );
						?>
					</div>
				</div>
				<?php
				$pagination_html .= '<button class="slider-pagination-bullet' . ( ! $index ? ' active' : '' ) . '" data-tooltip="' . esc_attr( $navigatorcontent['navigator_tooltip'] ) . '"></button>';
				$index ++;
				?>
			<?php endforeach; ?>

		</div>
	</div>
	<div class="slider-pagination swiper-pagination-clickable slider-pagination-bullets <?php echo 'slider-scroll-nav-dots-' . esc_attr( $widget_id ); ?>">
		<?php echo alpha_strip_script_tags( $pagination_html ); ?>
	</div>
<?php
