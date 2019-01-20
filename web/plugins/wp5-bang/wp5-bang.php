<?php 
	
if (!defined('ABSPATH')) die;

// define the root theme folder
define('WP5_BANG_DIR', __DIR__);

// autoloader
require WP5_BANG_DIR.'../../../vendor/autoload.php';

// Initialize WP5 Bang
$bang = bang();

// initialize plugin
$plugin = \Plugin\Init::instance();