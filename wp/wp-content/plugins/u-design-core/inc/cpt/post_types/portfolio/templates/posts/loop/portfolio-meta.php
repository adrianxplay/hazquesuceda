<?php
/**
 * Portfolio Meta
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */

global $post, $alpha_cpt;

$html = '';

if ( is_single() && ! alpha_get_loop_prop( 'widget' ) && ( ! isset( $show_info ) || in_array( 'category', $show_info ) ) ) {
	if ( taxonomy_exists( ALPHA_NAME . '_portfolio_category' ) ) {
		$cats = get_the_term_list( 0, ALPHA_NAME . '_portfolio_category', '', ', ' );
	}
	if ( $cats ) {
		$cpt_label = '';
		if ( isset( $alpha_cpt['cpt']['portfolio']['label'] ) ) {
			$cpt_label = $alpha_cpt['cpt']['portfolio']['label'];
		}

		$html     .= '<div class="portfolio-meta">';
			$html .= '<label>' . $cpt_label . ' ' . esc_html__( 'Category', 'alpha-core' ) . '</label>';
			$html .= alpha_strip_script_tags( $cats );
		$html     .= '</div>';
	}
}

if ( is_single() && ! alpha_get_loop_prop( 'widget' ) && ( ! isset( $show_info ) || in_array( 'skill', $show_info ) ) ) {
	if ( taxonomy_exists( ALPHA_NAME . '_portfolio_skill' ) ) {
		$cats = get_the_term_list( 0, ALPHA_NAME . '_portfolio_skill', '', ', ' );
	}
	if ( $cats ) {
		$cpt_label = '';
		if ( isset( $alpha_cpt['cpt']['portfolio']['label'] ) ) {
			$cpt_label = $alpha_cpt['cpt']['portfolio']['label'];
		}

		$html     .= '<div class="portfolio-meta">';
			$html .= '<label>' . $cpt_label . ' ' . esc_html__( 'Skill', 'alpha-core' ) . '</label>';
			$html .= alpha_strip_script_tags( $cats );
		$html     .= '</div>';
	}
}


if ( ! isset( $show_info ) || in_array( 'url', $show_info ) ) {
	$url      = get_post_meta( $post->ID, 'portfolio_link', true );
	$url_text = get_post_meta( $post->ID, 'portfolio_text', true );
	if ( $url_text ) {
		$cpt_label = '';
		if ( isset( $alpha_cpt['cpt']['portfolio']['label'] ) ) {
			$cpt_label = $alpha_cpt['cpt']['portfolio']['label'];
		}

		$html     .= '<div class="portfolio-meta">';
			$html .= '<label>' . $cpt_label . ' ' . esc_html__( 'URL', 'alpha-core' ) . '</label>';
			$html .= '<a href="' . esc_url( $url ? $url : '#' ) . '">' . esc_html( $url_text ) . '</a>';
		$html     .= '</div>';
	}
}

if ( ! isset( $show_info ) || in_array( 'client', $show_info ) ) {
	$client      = get_post_meta( $post->ID, 'portfolio_client_link', true );
	$client_text = get_post_meta( $post->ID, 'portfolio_client_text', true );
	if ( $client_text ) {
		$html     .= '<div class="portfolio-meta">';
			$html .= '<label>' . esc_html__( 'Client', 'alpha-core' ) . '</label>';
			$html .= '<a href="' . esc_url( $client ? $client : '#' ) . '">' . esc_html( $client_text ) . '</a>';
		$html     .= '</div>';
	}
}

if ( ! isset( $show_info ) || in_array( 'copyright', $show_info ) ) {
	$copyright      = get_post_meta( $post->ID, 'portfolio_copyright_link', true );
	$copyright_text = get_post_meta( $post->ID, 'portfolio_copyright_text', true );
	if ( $copyright_text ) {
		$html     .= '<div class="portfolio-meta">';
			$html .= '<label>' . esc_html__( 'Copyright', 'alpha-core' ) . '</label>';
			$html .= '<a href="' . esc_url( $copyright ? $copyright : '#' ) . '">' . esc_html( $copyright_text ) . '</a>';
		$html     .= '</div>';
	}
}

if ( ! isset( $show_info ) || in_array( 'author', $show_info ) ) {
	$html     .= '<div class="portfolio-meta">';
		$html .= '<label>' . esc_html__( 'By', 'alpha-core' ) . '</label>';
		$html .= '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a>';
	$html     .= '</div>';
}

if ( $html ) {
	echo '<div class="meta-group">' . alpha_escaped( $html ) . '</div>';
}
