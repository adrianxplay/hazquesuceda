<?php
/**
 * Header contact template
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || die;

use Elementor\Icons_Manager;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'live_chat'      => '',
			'live_chat_link' => '#',
			'tel_num'        => '',
			'tel_num_link'   => '#',
			'delimiter'      => '',
			'icon'           => null,
			'self'           => '',
		),
		$atts
	)
);

$live_chat_attrs           = [];
$live_chat_attrs['href']   = ! empty( $live_chat_link['url'] ) ? $live_chat_link['url'] : 'mailto:#';
$live_chat_attrs['target'] = ! empty( $live_chat_link['is_external'] ) ? '_blank' : '';
$live_chat_attrs['rel']    = ! empty( $live_chat_link['nofollow'] ) ? 'nofollow' : '';
if ( ! empty( $live_chat_link['custom_attributes'] ) ) {
	foreach ( explode( ',', $live_chat_link['custom_attributes'] ) as $attr ) {
		$key   = explode( '|', $attr )[0];
		$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
		if ( isset( $live_chat_attrs[ $key ] ) ) {
			$live_chat_attrs[ $key ] .= ' ' . $value;
		} else {
			$live_chat_attrs[ $key ] = $value;
		}
	}
}
$live_chat_link_attrs = '';
foreach ( $live_chat_attrs as $key => $value ) {
	if ( ! empty( $value ) ) {
		$live_chat_link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
	}
}

$tel_num_attrs           = [];
$tel_num_attrs['href']   = ! empty( $tel_num_link['url'] ) ? $tel_num_link['url'] : 'tel:#';
$tel_num_attrs['target'] = ! empty( $tel_num_link['is_external'] ) ? '_blank' : '';
$tel_num_attrs['rel']    = ! empty( $tel_num_link['nofollow'] ) ? 'nofollow' : '';
if ( ! empty( $tel_num_link['custom_attributes'] ) ) {
	foreach ( explode( ',', $tel_num_link['custom_attributes'] ) as $attr ) {
		$key   = explode( '|', $attr )[0];
		$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
		if ( isset( $tel_num_attrs[ $key ] ) ) {
			$tel_num_attrs[ $key ] .= ' ' . $value;
		} else {
			$tel_num_attrs[ $key ] = $value;
		}
	}
}
$tel_num_link_attrs = '';
foreach ( $tel_num_attrs as $key => $value ) {
	if ( ! empty( $value ) ) {
		$tel_num_link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
	}
}

$live_chat = esc_html( $live_chat );
$delimiter = esc_html( $delimiter );
$tel_num   = esc_html( $tel_num );

if ( $self && alpha_is_elementor_preview() ) {
	$self->add_inline_editing_attributes( 'contact_link_text' );
	$self->add_inline_editing_attributes( 'contact_delimiter' );
	$self->add_inline_editing_attributes( 'contact_telephone' );

	$live_chat = '<span ' . $self->get_render_attribute_string( 'contact_link_text' ) . '>' . $live_chat . '</span>';
	$delimiter = '<span ' . $self->get_render_attribute_string( 'contact_delimiter' ) . '>' . $delimiter . '</span>';
	$tel_num   = '<span ' . $self->get_render_attribute_string( 'contact_telephone' ) . '>' . $tel_num . '</span>';
}
?>
<div class="contact">
	<a class="d-flex" href="<?php echo esc_url( $tel_num_attrs['href'] ); ?>" aria-label="<?php esc_attr_e( 'Contact', 'alpha-core' ); ?>">
	<?php
	if ( is_array( $icon ) && $icon['value'] ) {
		if ( isset( $icon['library'] ) && 'svg' == $icon['library'] ) {
			ob_start();
			Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
			$icon = ob_get_clean();
		} else {
			$icon = '<i class="' . esc_attr( $icon['value'] ) . '"></i>';
		}

		echo alpha_escaped( $icon );
	}
	?>
	</a>
	<div class="contact-content">
		<?php
		printf( '<a %1$sclass="live-chat">%2$s</a>', $live_chat_link_attrs, $live_chat );
		echo ' <span class="contact-delimiter">' . $delimiter . '</span> ';
		printf( '<a %1$sclass="telephone">%2$s</a>', $tel_num_link_attrs, $tel_num );
		?>
	</div>
</div>



