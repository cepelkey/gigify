<?php

namespace GIGify\Pages;

use \GIGify\Common\BaseController;
use \GIGify\API\SettingsApi;

class Admin extends BaseController
{
    public $settings;
    public $pages = array();
    public $subpages = array();

    public function register() {
        $this->settings = new SettingsApi();

        $this->setPages();
        $this->setSubPages();

        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
    }

    public function setPages() {
        $this->pages = array(
            array(
                'page_title' => 'GIGify Plugin',
                'menu_title' => 'GIGify',
                'capability' => 'manage_options',
                'menu_slug' => 'gigify_plugin',
                'callback' => function() { return require_once( "$this->plugin_path/templates/Admin/admin-index.php" ); },
                'icon_url' => 'dashicons-store',
                'position' => 110
            )
        );
    }
    public function setSubPages() {
        $this->subpages = array(
            array(
                'parent_slug' => 'gigify_plugin',
                'page_title'  => 'Custom Post Types',
                'menu_title'  => 'CPTs',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_cpts',
                'callback'    => function() { echo 'CPT MANAGER'; }
            ),
            array(
                'parent_slug' => 'gigify_plugin',
                'page_title'  => 'Custom Taxonomies',
                'menu_title'  => 'Taxonomies',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_taxonomies',
                'callback'    => function() { echo 'Taxonomy MANAGER'; }
            ),
            array(
                'parent_slug' => 'gigify_plugin',
                'page_title'  => 'Custom Widgets',
                'menu_title'  => 'Widgets',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_widgets',
                'callback'    => function() { echo 'Widget MANAGER'; }
            )
        );
    }

}