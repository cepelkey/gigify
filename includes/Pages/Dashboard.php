<?php

namespace GIGify\Pages;

use \GIGify\API\SettingsApi;
use \GIGify\Common\BaseController;
use \GIGify\API\Callbacks\AdminCallbacks;
use \GIGify\API\Callbacks\SanitizationCallbacks;

class Dashboard extends BaseController
{
    public $settings;
    public $callbacks;
    public $sanitization;
    public $menu_slug;
    
    public $pages = array();
    public $subpages = array();

    public function register() {
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->sanitize = new SanitizationCallbacks();

        $this->setPages();
        //$this->setSubPages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
        $this->types->register();
    }

    public function setPages() {
        $this->pages = array(
            array(
                'page_title' => 'GIGify Plugin',
                'menu_title' => 'GIGify',
                'capability' => 'manage_options',
                'menu_slug' => $this->plugin_name,
                'callback' => array( $this->callbacks, 'adminDashboard'),
                'icon_url' => 'dashicons-store',
                'position' => 110
            )
        );
    }
    public function setSubPages() {
        $this->subpages = array(
            array(
                'parent_slug' => $this->plugin_name,
                'page_title'  => 'Custom Post Types',
                'menu_title'  => 'CPTs',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_cpts',
                'callback'    => array( $this->callbacks, 'adminCPTmanager')
            ),
            array(
                'parent_slug' => $this->plugin_name,
                'page_title'  => 'Role Manager',
                'menu_title'  => 'Role Manager',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_roles',
                'callback'    => array( $this->callbacks, 'adminRoleManager')
            ),
            array(
                'parent_slug' => $this->plugin_name,
                'page_title'  => 'Custom Taxonomies',
                'menu_title'  => 'Taxonomies',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_taxonomies',
                'callback'    => array( $this->callbacks, 'adminTaxonomyManager')
            ),
            array(
                'parent_slug' => $this->plugin_name,
                'page_title'  => 'Custom Widgets',
                'menu_title'  => 'Widgets',
                'capability'  => 'manage_options',
                'menu_slug'   => 'gigify_widgets',
                'callback'    => array( $this->callbacks, 'adminWidgetManager')
            )
        );
    }

    public function setSettings() {
        $args = array(
            array(
                'option_group'  => 'gigify_plugin_settings',
                'option_name'   => 'gigify_plugin',
                'callback'      => array( $this->sanitize, 'sanitizeManagersCheckbox')
            )
        );

        $this->settings->setSettings( $args );
    }
    public function setSections() {
        $args = array(
            array(
                'id'        => 'gigify_admin_index',
                'title'     => 'Settings Manager',
                'callback'  => array( $this->callbacks, 'gigifyAdminSection'),
                'page'      => 'gigify_plugin'
            )
        );
        $this->settings->setSections( $args );
    }

    public function setFields() {
        $args = array();

        foreach( $this->managers as $key => $value) {
            $args[] = array(
                'id'        => $key,
                'title'     => $value,
                'callback'  => array( $this->callbacks, 'checkboxField' ),
                'page'      => 'gigify_plugin',
                'section'   => 'gigify_admin_index',
                'args'      => array(
                    'option_name'    => 'gigify_plugin',
                    'label_for' => $key,
                    'classes'     => 'ui-toggle'
                )
            );
        }

        $this->settings->setFields( $args );
    }

}