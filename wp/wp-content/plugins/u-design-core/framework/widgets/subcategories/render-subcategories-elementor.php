<?php
defined( 'ABSPATH' ) || die;

/**
 * Alpha Subcategories Widget Render
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'list_type'            => 'pcat',
			'list_style'           => '',
			'menu_id'              => '',
			'category_ids'         => '',
			'product_category_ids' => '',
			'show_subcategories'   => '',
			'hide_empty'           => '',
			'count'                => '',
			'view_all'             => '',
		),
		$atts
	)
);

$tax = 'category';

if ( 'pcat' == $list_type ) {
	$category_ids = $product_category_ids;
	$tax          = 'product_cat';
}

if ( ! is_array( $category_ids ) ) {
	$category_ids = array_map( 'absint', explode( ',', $category_ids ) );
}

if ( isset( $count['size'] ) ) {
	$count = $count['size'];
}

if ( is_array( $category_ids ) ) {
	$cats = get_terms(
		array(
			'taxonomy'   => $tax,
			'include'    => empty( $category_ids ) ? false : implode( ',', $category_ids ),
			'hide_empty' => boolval( $hide_empty ),
		)
	);
	if ( $show_subcategories ) {
		?>
		<nav class="subcat-nav">
			<ul class="subcat-menu<?php echo 'underline' == $list_style ? ' subcat-underline' : ''; ?>">
				<?php foreach ( $cats as $cat ) { ?>
					<li>
						<h5 class="subcat-title"><?php echo esc_html( $cat->name ); ?></h5>
						<?php
						$sub_cats = get_terms(
							array(
								'taxonomy'   => $tax,
								'hide_empty' => boolval( $hide_empty ),
								'parent'     => $cat->term_id,
								'number'     => absint( $count ),
							)
						);
						foreach ( $sub_cats as $sub_cat ) {
							?>
							<a href="<?php echo esc_url( get_term_link( $sub_cat ) ); ?>"><?php echo esc_html( $sub_cat->name ); ?></a>
							<?php
						}
						if ( $view_all ) {
							?>
							<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $view_all ); ?></a>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</nav>
		<?php
	} else {
		foreach ( $cats as $cat ) {
			?>
			<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" title="<?php echo esc_attr( $cat->name ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php
		}
	}
}
