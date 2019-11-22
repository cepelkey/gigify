<?php

namespace GIGify\Common;

use \GIGify\Common\CustomPostTypes;

class BaseController 
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;
    public $plugin_name;
    public $types;

    public $post_types = array();
    public $managers = array();
    public $admin_tabs = array();

    public function __construct() {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin_template_path = str_replace('/', DIRECTORY_SEPARATOR, $this->plugin_path) . 'templates';
        $this->plugin_name = plugin_basename( dirname( __FILE__, 3 ) ) . '_plugin';
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/gigify.php';

        $this->types = new CustomPostTypes( $this->plugin_path );
        $this->post_types = $this->types->getPostTypes();

        $this->admin_tabs =  array(
            'cpt_manager' => array(
                'label'         => 'Custom Post Type Manager',
                'description'   => 'Enable & Disable GIGIFY Custom Post Types',
                'template'      => $this->plugin_template_path . '\Admin\admin-cpts.php',
            ),
            'taxonomy_manager'  => array(
                'label'         => 'Taxonomy Manager',
                'description'   => 'Enable & Disable GIGIFY Taxonomies',
                'template'      => $this->plugin_template_path . '\Admin\admin-taxonomies.php',
            ),
            'role_manager'  => array(
                'label'         => 'Role Manager',
                'description'   => 'Enable & Disable GIGIFY Roles and Capabilities',
                'template'      => $this->plugin_template_path . '\Admin\admin-roles.php',
            ),            
            'update_manager'  => array(
                'label'         => 'Updates',
                'description'   => 'Check for Updates',
                'template'      => $this->plugin_template_path . '\Admin\admin-updates.php',
            ),
            'about'  => array(
                'label'         => 'About',
                'description'   => 'About GIGify',
                'template'      => $this->plugin_template_path . '\Admin\admin-about.php',
            )
        );

        $this->managers = array(
            'cpt_manager' => 'Activate CPT Manager', 
            'taxonomy_manager' => 'Activate Taxonomy Manager', 
            'widget_manager' => 'Activate Media Widget', 
            'login_manager' => 'Activate Login Manager', 
            'role_manager' => 'Activate Role Manager'
        );

    }
}

