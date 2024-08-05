<?php
/**
 * Dynamic vars
 *
 * @author     Andon
 * @package    Alpha Framework
 * @subpackage Theme
 * @since      4.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once alpha_framework_path( ALPHA_FRAMEWORK_PATH . '/admin/customizer/dynamic/dynamic-color-lib.php' );

$dynamic_vars = array(
	'html'                        => array(
		// Layout
		'--alpha-container-width'           => alpha_get_option( 'container' ) . 'px',
		'--alpha-container-fluid-width'     => alpha_get_option( 'container_fluid' ) . 'px',
		// Color
		'--alpha-primary-color'             => alpha_get_option( 'primary_color' ),
		'--alpha-secondary-color'           => alpha_get_option( 'secondary_color' ),
		'--alpha-white-color'               => '#fff',
		'--alpha-dark-color'                => alpha_get_option( 'dark_color' ),
		'--alpha-light-color'               => alpha_get_option( 'light_color' ),
		'--alpha-accent-color'              => alpha_get_option( 'accent_color' ),
		'--alpha-success-color'             => alpha_get_option( 'success_color' ),
		'--alpha-info-color'                => alpha_get_option( 'info_color' ),
		'--alpha-alert-color'               => alpha_get_option( 'warning_color' ),
		'--alpha-danger-color'              => alpha_get_option( 'danger_color' ),

		'--alpha-primary-color-hover'       => AlphaColorLib::lighten( alpha_get_option( 'primary_color' ), 10 ),
		'--alpha-secondary-color-hover'     => AlphaColorLib::lighten( alpha_get_option( 'secondary_color' ), 10 ),
		'--alpha-dark-color-hover'          => AlphaColorLib::lighten( alpha_get_option( 'dark_color' ), 10 ),
		'--alpha-light-color-hover'         => AlphaColorLib::lighten( alpha_get_option( 'light_color' ), 10 ),
		'--alpha-accent-color-hover'        => AlphaColorLib::lighten( alpha_get_option( 'accent_color' ), 10 ),
		'--alpha-success-color-hover'       => AlphaColorLib::lighten( alpha_get_option( 'success_color' ), 10 ),
		'--alpha-info-color-hover'          => AlphaColorLib::lighten( alpha_get_option( 'info_color' ), 10 ),
		'--alpha-alert-color-hover'         => AlphaColorLib::lighten( alpha_get_option( 'warning_color' ), 10 ),
		'--alpha-danger-color-hover'        => AlphaColorLib::lighten( alpha_get_option( 'danger_color' ), 10 ),

		'--alpha-primary-color-light'       => AlphaColorLib::lighten( alpha_get_option( 'primary_color' ), 40 ),
		'--alpha-secondary-color-light'     => AlphaColorLib::lighten( alpha_get_option( 'secondary_color' ), 40 ),
		'--alpha-dark-color-light'          => AlphaColorLib::lighten( alpha_get_option( 'dark_color' ), 40 ),
		'--alpha-light-color-light'         => AlphaColorLib::lighten( alpha_get_option( 'light_color' ), 40 ),
		'--alpha-accent-color-light'        => AlphaColorLib::lighten( alpha_get_option( 'accent_color' ), 40 ),
		'--alpha-success-color-light'       => AlphaColorLib::lighten( alpha_get_option( 'success_color' ), 40 ),
		'--alpha-info-color-light'          => AlphaColorLib::lighten( alpha_get_option( 'info_color' ), 40 ),
		'--alpha-alert-color-light'         => AlphaColorLib::lighten( alpha_get_option( 'warning_color' ), 40 ),
		'--alpha-danger-color-light'        => AlphaColorLib::lighten( alpha_get_option( 'danger_color' ), 40 ),

		'--alpha-primary-gradient-1'        => AlphaColorLib::darken( alpha_get_option( 'primary_color' ), 0.6 ),
		'--alpha-primary-gradient-2'        => AlphaColorLib::lighten( alpha_get_option( 'primary_color' ), 10 ),

		'--alpha-dark-body-color'           => '#666',
		'--alpha-grey-color'                => '#999',
		'--alpha-grey-color-light'          => '#aaa',
		'--alpha-traffic-white-color'       => '#f9f9f9',
		'--alpha-change-border-color'       => '#e1e1e1',
		'--alpha-change-border-color-light' => '#eee',
		'--alpha-change-color-light-1'      => '#fff',
		'--alpha-change-color-light-2'      => '#f4f4f4',
		'--alpha-change-color-light-3'      => '#ccc',
		'--alpha-change-color-dark-1'       => alpha_get_option( 'dark_color' ),
		'--alpha-change-color-dark-1-hover' => AlphaColorLib::lighten( alpha_get_option( 'dark_color' ), 10 ),
		'--alpha-border-radius-form'        => alpha_get_option( 'rounded_skin' ) ? '3px' : '0px'
	),
	'.page-wrapper'               => array(),
	'.page-header'                => array(),
	'.page-header .page-title'    => array(),
	'.page-header .page-subtitle' => array(),
	'.page-title-bar'             => array(
		'--alpha-ptb-height' => alpha_get_option( 'ptb_height' ) . 'px',
	),
	'.breadcrumb'                 => array(),
	'.d-lazyload'                 => array(
		'--alpha-lazy-load-bg' => alpha_get_option( 'lazyload_bg' ),
	),
);

// Dark Skin
$dark_skin = alpha_get_option( 'dark_skin' );

if ( $dark_skin ) {
	$dynamic_vars['html']['--alpha-change-color-dark-1']       = '#ccc';
	$dynamic_vars['html']['--alpha-dark-body-color']           = '#aaa';
	$dynamic_vars['html']['--alpha-grey-color']                = '#797979';
	$dynamic_vars['html']['--alpha-grey-color-light']          = '#555';
	$dynamic_vars['html']['--alpha-change-border-color']       = '#2f2f2f';
	$dynamic_vars['html']['--alpha-change-border-color-light'] = '#2c2c2c';
	$dynamic_vars['html']['--alpha-change-color-light-1']      = '#212121';
	$dynamic_vars['html']['--alpha-change-color-light-2']      = '#2a2a2a';
	$dynamic_vars['html']['--alpha-change-color-light-3']      = '#323334';
	$dynamic_vars['html']['--alpha-traffic-white-color']       = '#272727';
	$dynamic_vars['html']['--alpha-change-color-dark-1-hover'] = AlphaColorLib::lighten( '#ccc', 10 );
}

// Basic Layout
$site_type = alpha_get_option( 'site_type' );
if ( 'full' != $site_type ) {
	alpha_dynamic_vars_bg( 'site', alpha_get_option( 'site_bg' ), $dynamic_vars['html'] );
	$dynamic_vars['html']['--alpha-site-width']  = alpha_get_option( 'site_width' ) . 'px';
	$dynamic_vars['html']['--alpha-site-margin'] = '0 auto';

	if ( 'boxed' == $site_type ) {
		$dynamic_vars['html']['--alpha-site-gap'] = '0 ' . alpha_get_option( 'site_gap' ) . 'px';
	} else {
		$dynamic_vars['html']['--alpha-site-gap'] = alpha_get_option( 'site_gap' ) . 'px';
	}
} else {
	$content_bg_clr = alpha_get_option( 'content_bg' );
	alpha_dynamic_vars_bg( 'site', array( 'background-color' => empty( $content_bg_clr ) ? '' : $content_bg_clr['background-color'] ), $dynamic_vars['html'] );
	$dynamic_vars['html']['--alpha-site-width']  = 'false';
	$dynamic_vars['html']['--alpha-site-margin'] = '0';
	$dynamic_vars['html']['--alpha-site-gap']    = '0';
}

// Page Transitions
$transition_bg = alpha_get_option( 'page_transition_bg' );
if ( $transition_bg ) {
	$dynamic_vars['html']['--alpha-page-transition-bg'] = $transition_bg;
}
$preloader_color = alpha_get_option( 'preloader_color' );
if ( $preloader_color ) {
	$dynamic_vars['html']['--alpha-preloader-color'] = $preloader_color;
}

/* Custom Cursor Type */
$change_cursor_type = alpha_get_option( 'change_cursor_type' );
if ( $change_cursor_type ) {
	if ( alpha_get_option( 'cursor_size' ) ) {
		$dynamic_vars['html']['--alpha-cursor-size'] = alpha_get_option( 'cursor_size' ) . 'px';
	}
	if ( alpha_get_option( 'cursor_inner_color' ) ) {
		$dynamic_vars['html']['--alpha-cursor-inner-color'] = alpha_get_option( 'cursor_inner_color' );
	}
	if ( alpha_get_option( 'cursor_outer_color' ) ) {
		$dynamic_vars['html']['--alpha-cursor-outer-color'] = alpha_get_option( 'cursor_outer_color' );
	}
	if ( alpha_get_option( 'cursor_outer_bg_color' ) ) {
		$dynamic_vars['html']['--alpha-cursor-outer-bg-color'] = alpha_get_option( 'cursor_outer_bg_color' );
	}
}

/* Background Grid Lines */
$bg_grid_line = alpha_get_option( 'bg_grid_line' );
if ( $bg_grid_line ) {
	if ( 'container' == alpha_get_option( 'grid_line_width' ) ) {
		$dynamic_vars['html']['--alpha-grid-line-max-width'] = 'calc(var(--alpha-container-width) ' . ( (int) alpha_get_option( 'grid_width_offset' ) > 0 ? '- ' . 2 * (int) alpha_get_option( 'grid_width_offset' ) : ( '+ ' . - 2 * (int) alpha_get_option( 'grid_width_offset' ) ) ) . 'px)';
	} else {
		$dynamic_vars['html']['--alpha-grid-line-max-width'] = 'calc(100% ' . ( (int) alpha_get_option( 'grid_width_offset' ) > 0 ? '- ' . 2 * (int) alpha_get_option( 'grid_width_offset' ) : ( '+ ' . - 2 * (int) alpha_get_option( 'grid_width_offset' ) ) ) . 'px)';
	}
	if ( alpha_get_option( 'grid_width_offset' ) ) {
		$dynamic_vars['html']['--alpha-grid-line-offset'] = alpha_get_option( 'grid_width_offset' ) . 'px';
	}
	if ( alpha_get_option( 'grid_columns' ) ) {
		$dynamic_vars['html']['--alpha-grid-line-columns']        = alpha_get_option( 'grid_columns' );
		$dynamic_vars['html']['--alpha-grid-line-columns-tablet'] = (int) ( alpha_get_option( 'grid_columns' ) / 1.5 );
		$dynamic_vars['html']['--alpha-grid-line-columns-mobile'] = (int) ( alpha_get_option( 'grid_columns' ) / 2 );
	}
	if ( alpha_get_option( 'grid_line_color' ) ) {
		$dynamic_vars['html']['--alpha-grid-line-color'] = alpha_get_option( 'grid_line_color' );
	}
	if ( alpha_get_option( 'grid_line_weight' ) ) {
		$dynamic_vars['html']['--alpha-grid-line-width'] = alpha_get_option( 'grid_line_weight' ) . 'px';
	}
	$dynamic_vars['html']['--alpha-grid-line-z-index'] = alpha_get_option( 'grid_line_zindex' );
}

/* Color & Typography */
$body_font = alpha_get_option( 'typo_default' );
alpha_dynamic_vars_typo( 'body', $body_font, $dynamic_vars['html'] );
$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
foreach ( $headings as $heading ) {
	$size = alpha_get_option( 'typo_' . $heading . '_size' );

	if ( $size ) {
		$unit = trim( preg_replace( '/[0-9.]/', '', $size ) );
		if ( ! $unit ) {
			$size .= 'px';
		}
		$dynamic_vars['html'][ '--alpha-' . $heading . '-font-size' ] = esc_html( $size );
	}
}
$heading_font = alpha_get_option( 'typo_heading' );
alpha_dynamic_vars_typo( 'heading', $heading_font, $dynamic_vars['html'], array( 'font-weight' => 600 ) );

// Page Wrapper
$content_bg = alpha_get_option( 'content_bg', array() );
if ( empty( $content_bg['background-color'] ) ) {
	if ( alpha_get_option( 'dark_skin' ) ) {
		$content_bg['background-color'] = '#171717';
	} else {
		$content_bg['background-color'] = '#fff';
	}
}
alpha_dynamic_vars_bg( 'page-wrapper', $content_bg, $dynamic_vars['.page-wrapper'] );

/* PTB & Breadcrumb */
$dynamic_vars['html']['--alpha-ptb-top-space']    = ( alpha_get_option( 'ptb_top_space' ) ? alpha_get_option( 'ptb_top_space' ) : 46 ) . 'px';
$dynamic_vars['html']['--alpha-ptb-bottom-space'] = ( alpha_get_option( 'ptb_bottom_space' ) ? alpha_get_option( 'ptb_bottom_space' ) : 46 ) . 'px';
$dynamic_vars['html']['--alpha-ptb-bg-color']     = alpha_get_option( 'ptb_bg_color' ) ? alpha_get_option( 'ptb_bg_color' ) : '#eee';
alpha_dynamic_vars_bg( 'ptb', alpha_get_option( 'ptb_bg' ), $dynamic_vars['.page-header'] );
alpha_dynamic_vars_typo( 'ptb-title', alpha_get_option( 'typo_ptb_title' ), $dynamic_vars['.page-header .page-title'] );
alpha_dynamic_vars_typo( 'ptb-subtitle', alpha_get_option( 'typo_ptb_subtitle' ), $dynamic_vars['.page-header .page-subtitle'] );
alpha_dynamic_vars_typo( 'ptb-breadcrumb', alpha_get_option( 'typo_ptb_breadcrumb' ), $dynamic_vars['.breadcrumb'] );

/**
 * Filters the dynamic vars.
 *
 * @since 1.0
 */
$dynamic_vars = apply_filters( 'alpha_dynamic_vars', $dynamic_vars );
$style        = '';
foreach ( $dynamic_vars as $selector => $value ) {
	$style .= $selector . ' {' . PHP_EOL;
	foreach ( $value as $css_var => $option ) {
		$style .= $css_var . ': ' . $option . ';' . PHP_EOL;
	}
	$style .= '}' . PHP_EOL;
}

/* Responsive */
$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container' ) - 1 ) . 'px) {
    .container-fluid .container {
        padding-left: 0;
        padding-right: 0;
	}
	.elementor-column-gap-no > .elementor-column > .col-half-section,
    .elementor-column-gap-no > .elementor-row > .elementor-column > .col-half-section {
		width: calc( 100% - 60px );
		padding-left: 30px;
	}
	.elementor-column-gap-no > .elementor-column > .col-half-section-right,
    .elementor-column-gap-no > .elementor-row > .elementor-column > .col-half-section-right {
        padding-left: 0;
		padding-right: 30px;
	}
	.elementor-container > .elementor-column > .col-half-section,
    .elementor-container > .elementor-row > .elementor-column > .col-half-section {
		width: calc(100% - 30px + var(--alpha-el-section-gap));
	}
}' . PHP_EOL;

$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container_fluid' ) - 1 ) . 'px) and (min-width: 480px) {
	.elementor-top-section.elementor-section-boxed > .elementor-container,
	.elementor-section-full_width .elementor-section-boxed > .elementor-container,
	.alpha-section-stretched.elementor-section-full_width .elementor-section-boxed > .elementor-container,
	.container .alpha-section-stretched.elementor-section-boxed > .elementor-container {
		width: calc(100% - 60px + 2 * var(--alpha-el-section-gap));
	}
	
	.elementor-top-section.elementor-section-boxed > .slider-container.slider-shadow,
	.elementor-section-full_width .elementor-section-boxed > .slider-container.slider-shadow,
	.alpha-section-stretched.elementor-section-full_width .elementor-section-boxed > .slider-container.slider-shadow,
	.container .alpha-section-stretched.elementor-section-boxed > .slider-container.slider-shadow {
		width: calc(100% - 60px + 40px) !important;
	}
	.e-con.e-con-boxed > .e-con-inner {
		width: calc( 100% - 60px + var(--alpha-con-ex-width));
	}
}' . PHP_EOL;

// Side header
$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container_fluid' ) - 1 ) . 'px) and (min-width: 480px) {
	.side-header .elementor-top-section.elementor-section-boxed > .elementor-container,
	.side-header .elementor-section-full_width .elementor-section-boxed > .elementor-container {
		width: calc(100% - 60px + 2 * var(--alpha-el-section-gap));
	}
	.side-header .container {
		width: 100%;
	}
}' . PHP_EOL;
$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container_fluid' ) - 1 ) . 'px) and (min-width: 992px) {
	.side-on-desktop .elementor-top-section.elementor-section-boxed > .elementor-container,
	.side-on-desktop .elementor-section-full_width .elementor-section-boxed > .elementor-container {
		width: calc(100% - 60px + 2 * var(--alpha-el-section-gap));
	}
	.side-on-desktop .container {
		width: 100%;
	}
}' . PHP_EOL;
$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container_fluid' ) - 1 ) . 'px) and (min-width: 768px) {
	.side-on-tablet .elementor-top-section.elementor-section-boxed > .elementor-container,
	.side-on-tablet .elementor-section-full_width .elementor-section-boxed > .elementor-container {
		width: calc(100% - 60px + 2 * var(--alpha-el-section-gap));
	}
	.side-on-tablet .container {
		width: 100%;
	}
}' . PHP_EOL;
$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container_fluid' ) - 1 ) . 'px) and (min-width: 576px) {
	.side-on-mobile .elementor-top-section.elementor-section-boxed > .elementor-container,
	.side-on-mobile .elementor-section-full_width .elementor-section-boxed > .elementor-container {
		width: calc(100% - 60px + 2 * var(--alpha-el-section-gap));
	}
	.side-on-mobile .container {
		width: 100%;
	}
}' . PHP_EOL;

$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container_fluid' ) - 1 ) . 'px) and (min-width: 480px) {
	.e-con.c-fluid > .e-con-inner {
		width: calc( 100% - 60px + var(--alpha-con-ex-width) );
	}
}' . PHP_EOL;

$style .= '@media (max-width: ' . ( (int) alpha_get_option( 'container' ) + 119 ) . 'px) and (min-width: 992px) {
	.elementor-top-section.elementor-section-boxed > .elementor-container,
	.elementor-top-section.elementor-section-boxed > .elementor-container.container-fluid,
	.elementor-section-full_width .elementor-col-100 .elementor-section-boxed > .elementor-container,
	.container .alpha-section-stretched.elementor-section-boxed > .elementor-container {
		width: calc(86vw + var(--alpha-el-section-gap) * 2);
	}
	.container .e-con.e-con-boxed > .e-con-inner,
	.e-con.e-con-boxed > .e-con-inner {
		width: calc(86vw + var(--alpha-con-ex-width));
	}
	.container,
	.fixed .container {
		width: calc(86vw + 60px);
	}
	.elementor-container > .elementor-column > .col-half-section,
	.elementor-container > .elementor-row > .elementor-column > .col-half-section {
		max-width: calc((86vw + var(--alpha-el-section-gap) * 2) / 2);
	}
	.elementor-top-section.elementor-section-boxed > .slider-container.slider-shadow,
	.elementor-section-full_width .elementor-section-boxed > .slider-container.slider-shadow {
		width: calc(86vw + 40px) !important;
	}
}' . PHP_EOL;

/**
 * Filters the dynamic style.
 *
 * @since 1.0
 */
echo preg_replace( '/[\t]+/', '', apply_filters( 'alpha_dynamic_style', $style ) );
