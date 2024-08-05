<?php
/**
 * Share Shortcode Render
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'share_buttons'   => array(
				array(
					'site' => 'facebook',
					'link' => '',
				),
				array(
					'site' => 'twitter',
					'link' => '',
				),
				array(
					'site' => 'linkedin',
					'link' => '',
				),
			),
			'type'            => 'stacked',
			'share_direction' => 'flex',
			'show_divider'    => '',
			'custom_color'    => '',
			'share_align'     => '',
			'share_v_align'   => '',
		),
		$atts
	)
);

$wrapper_class = 'social-icons';
if ( 'block' == $share_direction ) {
	$wrapper_class .= ' social-icons-vertical';
} elseif ( 'yes' == $show_divider ) {
	$wrapper_class .= ' social-icons-separated';
}

?>

<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	$custom = 'yes' == $custom_color ? ' use-hover ' : '';

	if ( $share_buttons ) {
		foreach ( $share_buttons as $share ) {
			$link  = $share['link']['url'];
			$share = $share['site'];

			if ( '' == $link ) {
				$permalink = esc_url( apply_filters( 'the_permalink', get_permalink() ) );
				$title     = esc_attr( get_the_title() );
				$image     = wp_get_attachment_url( get_post_thumbnail_id() );

				if ( 'whatsapp' == $share ) {
					$title = rawurlencode( $title );
				} else {
					$title = urlencode( $title );
				}

				$link = strtr(
					$this->share_icons[ $share ][1],
					array(
						'$permalink' => $permalink,
						'$title'     => $title,
						'$image'     => $image,
					)
				);
			}

			echo '<a href="' . ( $link ? esc_url( $link ) : '#' ) . '" class="social-icon ' . esc_attr( $type . $custom ) . ( 'block' === $share_direction ? $share_v_align : '' ) . ' social-' . $share . '" target="_blank" title="' . $share . '" rel="noopener noreferrer">';
			echo '<i class="' . esc_attr( $this->share_icons[ $share ][0] ) . '"></i>';
			if ( 'full' == $type || false !== strpos( $type, 'boxed-advanced' ) ) {
				echo '<span>' . $share . '</span>';
			}
			echo '</a>';
		}
	}
	?>
</div>

<?php
