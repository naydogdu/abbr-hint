<?php 
/*
Plugin Name: ABBR Hint Content
Plugin URI:  https://github.com/naydogdu/abbr-hint.git
Description: Lightweight content optimizer. Automatically add hints/explanations for abbreviations or acronyms to all of your content.
Version:     0.7.5
Author:      Nazmi Aydogdu
Author URI:  https://nazmi.grapheek.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

require_once dirname( __FILE__ ) . '/inc/loader.php';
$loader = new ABBR_Hint();
$loader->init();


