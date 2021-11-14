<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body>
<div id="page" class="site">


	<?php
    use TSTheme\TSWalker;
    wp_nav_menu( [
		'theme_location' => 'main_menu',
		'container'      => false,
        'items_wrap' => '<ul>%3$s</ul>',
        'walker' => new TSWalker()
	] ) ?>


