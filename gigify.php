<?php
/**
 * Plugin Name
 * @package     GIGify
 * @author      Charles Pelkey
 * @copyright   2019 C.P. Web Designs
 * @license     GPL-2.0+
 */
/* 
Plugin Name: GIGify
Plugin URI:  https://www.cp-webdesigns.com/wordpress/plugins/gigify
Description: Complete Solution for managing your band's Members, Gigs, Gig Locations, Set-Lists, Song Lyrics, etc.
Version:     1.0.0
Author:      Charles Pelkey
Author URI:  https://www.cp-webdesigns.com
Text Domain: gigify
License:     GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined ( 'ABSPATH' ) or die ( 'You can not access this file directly!' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// PLUGIN ACTIVATION
function gigify_activate_plugin() {
    GIGify\Common\Activate::activate();
}
register_activation_hook( __FILE__, 'gigify_activate_plugin');

// PLUGIN DECACTIVATION
function gigify_deactivate_plugin() {
    GIGify\Common\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'gigify_deactivate_plugin');

// INITIALIZE MAIN CLASS IF EXISTS
if ( class_exists ( 'GIGify\\Init' ) ) {
    GIGify\Init::register_services();
}
