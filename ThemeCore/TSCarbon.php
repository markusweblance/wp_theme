<?php


namespace TSTheme;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

class TSCarbon {


	/**
	 *
	 */
	public static function addCarbon() {
		add_action( 'after_setup_theme', [ static::Class, 'crb_load' ] );
		add_action( 'carbon_fields_register_fields', [ static::Class, 'add_theme_options' ] );
		//add_action( 'carbon_fields_register_fields', [ static::Class,'add_nav_menu_item'] );
		add_action( 'carbon_fields_register_fields', [ static::Class, 'add_post_meta' ] );
	}

	/**
	 *
	 */
	public static function crb_load() {
		Carbon_Fields::boot();
	}


	/**
	 * Add Theme Options
	 */
	public static function add_theme_options() {
		Container::make( 'theme_options', __( 'Theme Options' ) )
		         ->add_fields( array(
			         Field::make( 'text', 'crb_text', 'Text Field' ),
		         ) );
	}

	/**
	 * Add Nav Menu item
	 */
	public static function add_nav_menu_item() {
		Container::make( 'nav_menu_item', __( 'Menu Settings' ) )
		         ->add_fields( array(
			         Field::make( 'color', 'crb_color', __( 'Color' ) ),
		         ) );
	}

	/**
	 * Add Post meta
	 */
	public static function add_post_meta() {
		Container::make( 'post_meta', __( 'Homepage Settings' ) )
		         ->where( 'post_type', '=', 'page' )
		         ->where( 'post_template', '=', 'templates/main-page.php' )
		         ->add_fields( [
			         Field::make( 'complex', 'crb_img', __( 'Image' ) )
			              ->add_fields( [
				              Field::make( 'image', 'img', __( 'Img' ) )
			              ] )
		         ] );
	}

}