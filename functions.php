<?php
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';


use TSTheme\TSThemeSupport;
use TSTheme\TSCarbon;
use TSTheme\TSThemeFunctions;
use TSTheme\TSWoo;
use TSTheme\TSBreadcrumbs;

require_once plugin_dir_path( __FILE__ ) . 'ThemeCore/helpers.php';


/**
 * @var $theme TSThemeSupport
 * @var $carbon TSCarbon
 * @var $theme_functions TSThemeFunctions
 * @var $ts_woo TSWoo
 */

/**
 * CarbonFields start
 */
TSCarbon::addCarbon();


/**
 * Theme start
 */
TSThemeSupport::addThemeSupport();

/**
 * Add scripts
 */
TSThemeSupport::$scripts = [
	[
		'cdn'       => true,
		'name'      => 'slick',
		'path'      => 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js',
		'array'     => [ 'jquery' ],
		'in_footer' => true,
	],
	[
		'cdn'       => false,
		'name'      => 'script',
		'path'      => '/assets/js/scripts.js',
		'array'     => [ 'jquery' ],
		'in_footer' => true,
	],
];

/**
 * Add styles
 */
TSThemeSupport::$styles = [
	[
		'cdn'   => true,
		'name'  => 'slick-css',
		'path'  => 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css',
		'array' => [],
		'media' => 'all',
	],
	[
		'cdn'   => false,
		'name'  => 'main-css',
		'path'  => '/assets/css/style.css',
		'array' => [],
		'media' => 'all',
	],
];
/**
 * Add menus
 */
TSThemeSupport::register_menu( [
	'main_menu' => 'Main Menu',
] );

/**
 * Theme Functions
 */
TSThemeFunctions::start();

/**
 * Disable Content Editor
 */

TSThemeFunctions::$disable_template = [
	[
		'type'     => 'page',
		'template' => 'templates/main-page.php',
	]
];

/**
 * Breadcrumbs
 */
function wpt_breadcrumbs(){
	TSBreadcrumbs::breadcrumbs();
}

/**
 * Add WooFunction
 */
TSWoo::start();