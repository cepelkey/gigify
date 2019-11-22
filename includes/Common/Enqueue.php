<?php

namespace GIGify\Common;

use \GIGify\Common\BaseController;

class Enqueue extends BaseController
{
    public function register() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
    }
    public function enqueue( $hook ) {
        wp_enqueue_style( 'gigify-style', $this->plugin_url . 'assets/css/gigify.min.css' );
        wp_enqueue_script( 'gigify-js', $this->plugin_url . 'assets/js/gigify.js', array( 'jquery' ) , null, true );

        if( is_admin() ){
            $screen = get_current_screen();
            if( 'event' === $screen->id ) {
                 wp_enqueue_script( 'gallery-metabox-js', $this->plugin_url . '/assets/js/gallery_metabox.js', array('jquery', 'jquery-ui-sortable'));
            }
        }
    }
}