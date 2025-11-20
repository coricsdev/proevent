<?php
// basic theme setup, will grow as we add features

if ( ! function_exists( 'proevent_setup' ) ) :

	function proevent_setup() {

		add_theme_support( 'title-tag' );

		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'my-project' ),
				'footer'  => __( 'Footer Menu', 'my-project' ),
			)
		);
	}

endif;
add_action( 'after_setup_theme', 'proevent_setup' );



/**
 * enqueue compiled tailwind css + a small js file (later)
 */
function proevent_assets() {

	$theme_version = wp_get_theme()->get( 'Version' );

	$css_path = get_stylesheet_directory() . '/assets/css/main.css';
	$css_ver  = file_exists( $css_path ) ? filemtime( $css_path ) : $theme_version;

	wp_enqueue_style(
		'proevent-main',
		get_stylesheet_directory_uri() . '/assets/css/main.css',
		array(),
		$css_ver
	);

	// placeholder js, we can wire up block logic / small interactivity later
	$js_path = get_stylesheet_directory() . '/assets/js/main.js';
	$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : $theme_version;

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'proevent-main',
			get_stylesheet_directory_uri() . '/assets/js/main.js',
			array(),
			$js_ver,
			true
		);
	}
}

add_action( 'wp_enqueue_scripts', 'proevent_assets' );
