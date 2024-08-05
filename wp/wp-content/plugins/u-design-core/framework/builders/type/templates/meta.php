<?php

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'field'       => 'author',
			'date_format' => '',
			'icon_cls'    => '',
			'icon_pos'    => '',
			'el_class'    => '',
			'className'   => '',
		),
		$atts
	)
);

$wrap_cls = 'alpha-tb-meta tb-meta-' . $field;
if ( $el_class && wp_is_json_request() ) {
	$wrap_cls .= ' ' . trim( $el_class );
}
if ( $className ) {
	$wrap_cls .= ' ' . trim( $className );
}

$icon_html = '';
if ( $icon_cls ) {
	$icon_html .= '<i class="alpha-tb-icon ' . esc_attr( $icon_cls ) . '"></i>';
	$wrap_cls  .= ' alpha-tb-icon-' . ( $icon_pos ? $icon_pos : 'left' );
}

if ( ! empty( $GLOBALS['post'] ) && ! in_array( $field, array( 'author', 'published_date', 'modified_date', 'comments', 'comments_number', 'sku' ) ) ) {
	$terms        = get_the_terms( $GLOBALS['post']->ID, $field );
	$term_classes = '';
	foreach ( $terms as $term ) {
		$term_classes .= ' ' . $term->slug;
	}
	$wrap_cls .= $term_classes;
}

echo '<span class="' . esc_attr( apply_filters( ALPHA_GUTENBERG_BLOCK_CLASS_FILTER, $wrap_cls, $atts, ALPHA_NAME . '-tb/' . ALPHA_NAME . '-meta' ) ) . '">';

if ( $icon_html && ! $icon_pos ) {
	echo alpha_escaped( $icon_html );
}

if ( ! empty( $GLOBALS['post'] ) ) {
	switch ( $field ) {
		case 'author':
			the_author_posts_link();
			break;
		case 'published_date':
			if ( ! $date_format ) {
				$date_format = get_option( 'date_format' );
			}
			echo get_the_time( $date_format );
			break;
		case 'modified_date':
			if ( ! $date_format ) {
				$date_format = get_option( 'date_format' );
			}
			echo get_the_modified_date( $date_format );
			break;
		case 'comments':
			comments_popup_link( esc_html__( 'No Comments', 'alpha-core' ), esc_html__( '1 Comment', 'alpha-core' ), esc_html__( '% Comments', 'alpha-core' ) );
			break;
		case 'comments_number':
			echo (int) get_comments_number();
			break;
		case 'sku':
			global $product;
			if ( is_object( $product ) && '' !== $product->get_sku() ) {
				echo esc_html( $product->get_sku() );
			}
			break;
		default:
			$post_type = get_post_type();
			if ( 'post_tag' == $field ) {
				if ( 'post' == $post_type ) {
					$tags = get_the_tag_list( '', ', ', '' );
					if ( $tags && ! is_wp_error( $tags ) ) {
						echo alpha_escaped( $tags );
					}
				}
			} else {
				$terms = get_the_term_list( get_the_ID(), $field, '', ', ', '' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					echo alpha_escaped( $terms );
				}
			}
	}
}

if ( $icon_html && $icon_pos ) {
	echo alpha_escaped( $icon_html );
}

echo '</span>';
