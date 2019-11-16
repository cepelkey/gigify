<?php

namespace GIGify\Common;

use \GIGify\Common\BaseController;

class Enqueue extends BaseController
{
    public function register() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
    }
    public function enqueue() {
        wp_enqueue_style( 'gigify-style', $this->plugin_url . 'assets/css/gigify.min.css' );
        wp_enqueue_script( 'gigify-js', $this->plugin_url . 'assets/js/gigify.js', null, null, true );
    }
}