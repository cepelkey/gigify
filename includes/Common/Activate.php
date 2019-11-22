<?php

namespace GIGify\Common;

class Activate
{
    public static function activate() {
        flush_rewrite_rules();

        if( !get_option( 'gigify_plugin' ) ) {
            $default = array();
            add_option( 'gigify_plugin', $default, null, false );
        }

        if( !get_option( 'gigify_plugin_post_types' ) ) {
            $default = array(
                'gig' => true,
                'venue' => true
            );
            add_option( 'gigify_plugin_post_types', $default, null, false );
        }
    }
}