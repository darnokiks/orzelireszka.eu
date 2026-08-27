<?php
/**
 * Orzeł i Reszka - funkcje motywu.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'OIR_THEME_VERSION', '1.0.0' );

/**
 * Podstawowa konfiguracja motywu.
 */
function oir_setup() {
	load_theme_textdomain( 'orzel-i-reszka', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 90,
			'width'       => 280,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu główne', 'orzel-i-reszka' ),
		)
	);
}
add_action( 'after_setup_theme', 'oir_setup' );

/**
 * Style i skrypty.
 */
function oir_assets() {
	wp_enqueue_style(
		'oir-google-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:ital,wght@0,400;0,600;1,400&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'orzel-i-reszka-style', get_stylesheet_uri(), array(), OIR_THEME_VERSION );
	wp_enqueue_script( 'orzel-i-reszka-nav', get_template_directory_uri() . '/assets/js/navigation.js', array(), OIR_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'oir_assets' );

/**
 * Widżety w stopce (opcjonalnie do wykorzystania w przyszłości).
 */
function oir_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Stopka', 'orzel-i-reszka' ),
			'id'            => 'footer-1',
			'before_widget' => '<div class="oir-footer-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'oir_widgets_init' );

/**
 * Jednorazowa, bezpieczna konfiguracja po zaimportowaniu treści startowych
 * (ustawienie strony głównej, strony wpisów i menu), tak aby po
 * aktywacji motywu i imporcie pliku content/orzelireszka-content.xml
 * strona działała od razu bez ręcznego klikania w ustawieniach.
 */
function oir_maybe_run_initial_setup() {
	if ( get_option( 'oir_initial_setup_done' ) ) {
		return;
	}

	$home_page = get_page_by_path( 'strona-glowna' );
	$blog_page = get_page_by_path( 'aktualnosci' );

	if ( ! $home_page || ! $blog_page ) {
		return; // Treść jeszcze nie zaimportowana - spróbujemy przy kolejnym wejściu do panelu.
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_page->ID );
	update_option( 'page_for_posts', $blog_page->ID );

	$menu = get_term_by( 'name', 'Menu główne', 'nav_menu' );
	if ( $menu && ! is_wp_error( $menu ) ) {
		set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu->term_id ) );
	}

	update_option( 'oir_initial_setup_done', 1 );
}
add_action( 'admin_init', 'oir_maybe_run_initial_setup' );

require get_template_directory() . '/inc/contact-form.php';
