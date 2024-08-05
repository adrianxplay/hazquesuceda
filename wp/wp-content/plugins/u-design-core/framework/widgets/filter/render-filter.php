<?php
/**
 * Alpha Filter Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0.0
 */

defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'post_type'    => '',
			'filter_items' => array(),
			'button_label' => esc_html__( 'Search', 'alpha-core' ),
			'query_opt'    => 'and',
		),
		$atts
	)
);

?>

	<div class="alpha-filters" data-post-type="<?php echo esc_attr( $post_type ); ?>" data-filter-query="<?php echo esc_attr( $query_opt ); ?>">
		<?php
		if ( is_array( $filter_items ) && count( $filter_items ) ) {
			foreach ( $filter_items as $filter_item ) {
				?>
				<div class="filter-form-field <?php echo esc_attr( 'elementor-repeater-item-' . $filter_item['_id'] ); ?>">
				<?php
				if ( 'search' == $filter_item['filter_type'] ) {
					?>
					<input type="search" aria-label="Search" class="form-control" name="s" placeholder="<?php echo esc_attr( $filter_item['search_placeholder'] ); ?>" autocomplete="off">
					<?php
				} elseif ( $filter_item['post_tax'] ) {

					$args  = array(
						'taxonomy'   => sanitize_text_field( $filter_item['post_tax'] ), // taxonomy name
						'hide_empty' => false,
						'fields'     => 'id=>name',
					);
					$terms = get_terms( $args );

					$options = array();

					$options[] = array(
						'id'   => '',
						'text' => $filter_item['dropdown_title'] ? $filter_item['dropdown_title'] : get_taxonomy( $filter_item['post_tax'] )->label,
					);

					foreach ( $terms as $term_id => $term_name ) {
						$options[] = array(
							'id'   => get_term( $term_id )->slug,
							'text' => esc_html( $term_name ),
						);
					}

					if ( is_array( $options ) && count( $options ) ) {
						?>
						<select name="<?php echo esc_attr( $filter_item['post_tax'] ); ?>">
							<?php foreach ( $options as $option ) : ?>
							<option value="<?php echo esc_attr( $option['id'] ); ?>"><?php echo esc_html( $option['text'] ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php
					}
				}
				?>
				</div>
				<?php
			}
		}

		$btn_class    = array();
		$button_label = alpha_widget_button_get_label( $atts, $this, $button_label, 'button_label' );
		$btn_class[]  = 'btn';
		$btn_class[]  = implode( ' ', alpha_widget_button_get_class( $atts ) );

		$this->add_inline_editing_attributes( 'button_label' );
		printf( '<div class="filter-form-field form-field-button"><a class="' . esc_attr( implode( ' ', $btn_class ) ) . '" href="' . esc_url( home_url() ) . '">%1$s</a></div>', alpha_strip_script_tags( $button_label ) );
		?>
	</div>
