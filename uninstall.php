<?php

/**
* Trigger this file to uninstall the Plugin
* @package GIGifyPlugin
**/

if ( ! defined ( 'WP_UNINSTALL_PLUGIN' ) ) { die; }

// remove data and options from DB

delete_option( 'gigify_plugin' );
delete_option( 'gigify_plugin_post_types' );
