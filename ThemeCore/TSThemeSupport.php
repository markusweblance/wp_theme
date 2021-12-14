<?php


namespace TSTheme;


class TSThemeSupport {


	public static array $scripts = [];

	public static array $styles = [];


	public static function addThemeSupport() {
		add_action( 'after_setup_theme', [ static::Class, 'theme_support' ] );
		add_action( 'wp_enqueue_scripts', [ static::Class, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ static::Class, 'enqueue_scripts' ] );
	}


	public static function theme_support() {
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'menus' );
		// Let WordPress manage the document title
		add_theme_support( 'title-tag' );
		// Add post thumbnail support: http://codex.wordpress.org/Post_Thumbnails
		add_theme_support( 'post-thumbnails' );
		// RSS thingy
		add_theme_support( 'automatic-feed-links' );
		// Woocommerce support
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		// Add foundation.css as editor style https://codex.wordpress.org/Editor_Style
		add_editor_style( 'assets/stylesheets/editor.css' );
	}

	public static function enqueue_styles() {
		wp_enqueue_style( 'ts-style', get_stylesheet_uri() );
		foreach ( self::$styles as $style ) {
			if ( $style['cdn'] ) {
				wp_enqueue_style( $style['name'], $style['path'], $style['array'], null, $style['media'] );
			} else {
				wp_enqueue_style(
					$style['name'],
					get_template_directory_uri() . $style['path'],
					$style['array'],
					null,
					$style['media']
				);
			}
		}
	}


	public static function enqueue_scripts() {
		foreach ( self::$scripts as $script ) {
			if ( $script['cdn'] ) {
				wp_enqueue_script( $script['name'], $script['path'], $script['array'], null, $script['in_footer'] );
			} else {
				wp_enqueue_script(
					$script['name'],
					get_template_directory_uri() . $script['path'],
					$script['array'],
					null,
					$script['in_footer']
				);
			}
		}
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	public static function register_menu( array $array ) {
		register_nav_menus( $array );
	}
}