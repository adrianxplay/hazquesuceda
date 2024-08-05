<?php
/**
 * Price Tables Shortcode Render
 *
 * @author     D-THEMES
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'tables'           => array(),
			'price_table_type' => 'default',
			'feature_divider'  => '',
		),
		$atts
	)
);

$wrapper_cls = 'price-tables';

$grid_space_class = alpha_get_grid_space_class( $atts );
$col_cnt          = alpha_elementor_grid_col_cnt( $atts );
if ( $grid_space_class ) {
	$wrapper_cls .= ' ' . $grid_space_class;
}
if ( $col_cnt ) {
	$wrapper_cls .= ' ' . alpha_get_col_class( $col_cnt );
}

$table_cls  = 'price-table';
$table_cls .= ' ' . $price_table_type . '-type';
if ( 'yes' == $feature_divider ) {
	$table_cls .= ' features-separated';
}

$html = '<div class="' . esc_attr( $wrapper_cls ) . '">';

foreach ( $tables as $key => $table ) {
	$repeater_cls = ' elementor-repeater-item-' . $table['_id'];
	$html        .= '<div class="grid-col">';
	$html        .= '<div class="' . esc_attr( $table_cls . $repeater_cls ) . ( 'yes' == $table['best_option'] ? ' featured' : ' standard' ) . '">';
	$html        .= '<div class="plan-header">';

	$repeater_setting_key = $this->get_repeater_setting_key( 'name', 'tables', $key );
	$this->add_render_attribute( $repeater_setting_key, 'class', 'plan-name' );
	$this->add_inline_editing_attributes( $repeater_setting_key );
	$html .= '<h3 ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $table['name'] ) . '</h3>';

	if ( $table['desc'] && 'mini' != $price_table_type ) {
		$repeater_setting_key = $this->get_repeater_setting_key( 'desc', 'tables', $key );
		$this->add_render_attribute( $repeater_setting_key, 'class', 'plan-desc' );
		$this->add_inline_editing_attributes( $repeater_setting_key );
		$html .= '<p ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $table['desc'] ) . '</p>';
	}
	$html .= '</div>';
	if ( $table['desc'] && 'mini' == $price_table_type ) {
		$repeater_setting_key = $this->get_repeater_setting_key( 'desc', 'tables', $key );
		$this->add_render_attribute( $repeater_setting_key, 'class', 'plan-desc' );
		$this->add_inline_editing_attributes( $repeater_setting_key );
		$html .= '<p ' . $this->get_render_attribute_string( $repeater_setting_key ) . '>' . alpha_strip_script_tags( $table['desc'] ) . '</p>';
	}
	$html .= '<div class="plan-price">';
	$html .= ! empty( $table['currency'] ) ? ( '<span class="currency">' . alpha_strip_script_tags( $table['currency'] ) . '</span>' ) : '';
	$html .= ! empty( $table['price_value'] ) ? alpha_strip_script_tags( $table['price_value'] ) : '29.99';
	$html .= ! empty( $table['price_suffix'] ) ? ( '<p class="price-suffix">' . alpha_strip_script_tags( $table['price_suffix'] ) . '</p>' ) : '';
	$html .= '</div>';

	$features = explode( "\n", $table['features_list'] );
	if ( count( $features ) ) {
		$html .= '<ul class="plan-features">';

		foreach ( $features as $feature ) {
			$html .= '<li class="plan-feature">';
			$html .= alpha_strip_script_tags( $feature );
			$html .= '</li>';
		}

		$html .= '</ul>';
	}

	$html .= '<div class="plan-footer">';

	$repeater_setting_key = $this->get_repeater_setting_key( 'button_label', 'tables', $key );
	$this->add_inline_editing_attributes( $repeater_setting_key );

	$button_label = alpha_widget_button_get_label( $table, $this, $table['button_label'], $repeater_setting_key );
	$btn_class    = 'btn ' . implode( ' ', alpha_widget_button_get_class( $table ) );

	$attrs           = [];
	$attrs['href']   = ! empty( $table['link']['url'] ) ? esc_url( $table['link']['url'] ) : '#';
	$attrs['target'] = ! empty( $table['link']['is_external'] ) ? '_blank' : '';
	$attrs['rel']    = ! empty( $table['link']['nofollow'] ) ? 'nofollow' : '';
	if ( ! empty( $table['link']['custom_attributes'] ) ) {
		foreach ( explode( ',', $table['link']['custom_attributes'] ) as $attr ) {
			$key   = explode( '|', $attr )[0];
			$value = implode( ' ', array_slice( explode( '|', $attr ), 1 ) );
			if ( isset( $attrs[ $key ] ) ) {
				$attrs[ $key ] .= ' ' . $value;
			} else {
				$attrs[ $key ] = $value;
			}
		}
	}
	$link_attrs = '';
	foreach ( $attrs as $key => $value ) {
		if ( ! empty( $value ) ) {
			$link_attrs .= $key . '="' . esc_attr( $value ) . '" ';
		}
	}

	$html .= sprintf( '<a class="' . esc_attr( $btn_class ) . '" %2$s>%1$s</a>', alpha_strip_script_tags( $button_label ), $link_attrs );

	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div>';

echo alpha_escaped( $html );
