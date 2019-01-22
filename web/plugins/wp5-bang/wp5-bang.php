<?php 
/*
Plugin Name: Bang
Plugin URI: https://github.com/boyanraichev/wp5-env
Description: Wordress custom development boilerplate
Version: 1.0
Author: Boyan Raichev
Author URI: https://github.com/boyanraichev/
Text Domain: wp5-bang
Domain Path: /languages
License: MIT
*/
	
if (!defined('ABSPATH')) die;

// define the root plugin folder
define('WP5_BANG_DIR', __DIR__ . '/');

// autoloader
require WP5_BANG_DIR . '../../../vendor/autoload.php';

// Initialize WP5 Bang
$bang = bang();

// initialize plugin
$plugin = \Plugin\Init::instance();