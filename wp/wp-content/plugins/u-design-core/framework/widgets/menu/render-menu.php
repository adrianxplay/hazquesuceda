<?php
/**
 * Menu template
 *
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'menu_id'             => '',
			'type'                => 'vertical',
			'mobile'              => '',
			'underline'           => '',
			'label'               => '',
			'icon'                => array( 'value' => ALPHA_ICON_PREFIX . '-icon-category' ),
			'no_bd'               => '',
			'show_home'           => '',
			'show_page'           => '',
			'mobile_label'        => esc_html__( 'Links', 'alpha-core' ),
			'mobile_dropdown_pos' => '',
		),
		$atts
	)
);

$menu = (object) array(
	'menu_id'             => $menu_id,
	'type'                => $type,
	'mobile'              => 'yes' == $mobile,
	'mobile_text'         => $mobile_label ? $mobile_label : esc_html__( 'Links', 'alpha-core' ),
	'underline'           => $underline,
	'label'               => $label,
	'icon'                => isset( $icon['value'] ) ? $icon['value'] : '',
	'no_bd'               => $no_bd,
	'show_home'           => $show_home,
	'show_page'           => $show_page,
	'mobile_dropdown_pos' => $mobile_dropdown_pos,
);

if ( isset( $menu->menu_id ) && wp_get_nav_menu_object( $menu->menu_id ) ) {
	$class                 = '';
	$wrap_cls              = '';
	$wrap_cls             .= ' ' . $menu->type . '-menu';
	$wrap_style            = '';
	$depth                 = 0;
	$lazyload_menu_enabled = function_exists( 'alpha_is_elementor_preview' ) && ! alpha_is_elementor_preview() && ! wp_doing_ajax() &&
				! is_customize_preview() && alpha_get_option( 'lazyload_menu' );

	if ( $lazyload_menu_enabled ) {
		$wrap_cls .= ' lazy-menu';
		$depth     = 2;
	}

	if ( 'horizontal' != $menu->type && isset( $menu->width ) ) {
		$wrap_style .= 'width: ' . (float) $menu->width . 'px';
	}

	if ( 'horizontal' == $menu->type && $menu->mobile ) {
		echo '<div class="dropdown dropdown-menu mobile-links">';
		echo '<a href="#">' . esc_attr( $menu->mobile_text ) . '</a>';
		$class = 'dropdown-box' . ( isset( $menu->mobile_dropdown_pos ) && $menu->mobile_dropdown_pos ? ' ' . $menu->mobile_dropdown_pos : '' );
	} elseif ( 'dropdown' == $menu->type ) {
		$tog_class = array();
		if ( ! $menu->no_bd ) {
			$tog_class[] = 'has-border';
		}
		if ( isset( $menu->show_page ) && $menu->show_page ) {
			$tog_class[] = 'show';
		}
		if ( $menu->show_home && is_front_page() ) {
			$tog_class[] = 'show-home';
		}
		echo '<div class="dropdown toggle-menu ' . implode( ' ', $tog_class ) . '">';
		echo '<a href="#" class="dropdown-menu-toggle">';
		if ( $menu->icon ) {
			echo '<i class="' . esc_attr( $menu->icon ) . '"></i>';
		}
		if ( $menu->label ) {
			echo '<span>' . esc_html( $menu->label ) . '</span>';
		}
		echo '</a>';

		$class     = 'dropdown-box';
		$wrap_cls .= ' vertical-menu';
	} elseif ( 'flyout' == $menu->type ) {
		echo '<div class="flyout-menu-container toggle-menu">';
		echo '<a href="#" class="dropdown-menu-toggle" aria-label="' . esc_html__( 'Flyout Menu' ) . '">';
		if ( $menu->icon ) {
			echo '<i class="' . esc_attr( $menu->icon ) . '"></i>';
		}
		echo '</a>';

		$class = 'flyout-box';
	}

	if ( isset( $menu->underline ) && $menu->underline ) {
		$wrap_cls .= ' menu-active-underline';
	}

	$class .= ' ' . get_term_field( 'slug', $menu->menu_id );

	wp_nav_menu(
		array(
			'menu'            => $menu->menu_id,
			'container'       => 'nav',
			'container_class' => $class,
			'items_wrap'      => '<ul id="%1$s" class="menu ' . esc_attr( $wrap_cls ) . '"' . ( empty( $wrap_style ) ? '' : ' style="' . $wrap_style . '"' ) . '>%3$s</ul>',
			'walker'          => new Alpha_Walker_Nav_Menu(),
			'depth'           => $depth,
			'lazy'            => alpha_get_option( 'lazyload_menu' ) && $lazyload_menu_enabled,
			'theme_location'  => '',
			'fallback_cb'     => false,
		)
	);

	if ( isset( $menu->mobile ) && $menu->mobile ) {
		echo '</div>';
	} elseif ( 'dropdown' == $menu->type || 'flyout' == $menu->type ) {
		if ( 'flyout' == $menu->type ) {
			echo '<a class="flyout-close" href="#"><i class="close-icon"></i></a>';
		}
		echo '</div>';
	}
} else {
	?>
	<nav class="d-none d-lg-block">
		<ul class="menu dummy-menu">
			<li class="menu-item">
				<a href="#"><?php esc_html_e( 'Select Menu', 'alpha-core' ); ?></a>
			</li>
		</ul>
	</nav>
	<?php
}
