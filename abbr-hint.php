<?php 
/*
Plugin Name: ABBR Hint Content
Plugin URI:  https://nazmi.grapheek.com
Description: Lightweight content hints. Automatically add hints (via CSS abbr element) to all contents.
Version:     0.6.0
Author:      Nazmi Aydogdu
Author URI:  https://nazmi.grapheek.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

require_once dirname( __FILE__ ) . '/inc/loader.php';
$loader = new ABBR_Hint();
$loader->init();


