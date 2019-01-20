<?php 
	
if (!defined('ABSPATH')) die;

// define the root theme folder
define('THEME_DIR', __DIR__);

if (!defined('THEME_VERSION')) {
	$ver = config('theme.version');
	define('THEME_VERSION',$ver);
}

// initialize theme
$theme = \Theme\Init::instance();
