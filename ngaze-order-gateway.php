<?php
/**
* Plugin Name: NGAZE Order Gateway
* Plugin URI: https://thedigitalrestaurant.com/
* Description: NGAZE Order Gateway
* Version: 1.0.1
* Author: NGAZE Order Gateway
* License: GPL2
*/
/**
 * Base config constants and functions
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
 
 
require_once plugin_dir_path(__FILE__). 'functions.php';
require_once plugin_dir_path(__FILE__).'external/meta-box/meta-box.php';
/**
 * Connect all required core classes
 */
