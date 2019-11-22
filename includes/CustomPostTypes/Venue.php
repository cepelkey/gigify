<?php

namespace GIGify\CustomPostTypes;

use \GIGify\Common\CustomPostTypes;

class Venue
{
    private $slug;
    private $args = array();
    private $labels = array();
    private $plugin_name;

    public function __construct() {
        $this->slug = strtolower( basename( __FILE__, '.php' ) );
        $this->singular_name = \ucfirst( $this->slug );
        $this->plural_name = $this->singular_name . 's';
        $this->is_active = \GIGify\Common\CustomPostTypes::isActivePostType( $this->slug );

        $this->plugin_name = basename( dirname( __FILE__, 3) ) . '_plugin';

    }

    // enable or disable CPT
    public function register() {

        // if this CPT is not active return/exit
        if( !$this->is_active ) return; 

        $this->labels = $this->setLabels();
        $this->args = $this->setArgs();
        \add_action( 'init', array( $this, 'registerPostType') );
        \add_action( 'admin_menu', array( $this, 'addPostTypePage') );
    }
    public function registerPostType() {
        \register_post_type( $this->slug, $this->args );
    }
    public function deregister() {
        \unregister_post_type( $this->slug );
    }

    /**
     * Place This CPT under the plugin menu
     */
    public function addPostTypePage() {
        \add_submenu_page(
            $this->plugin_name,
            __( $this->plural_name , 'textdomain' ),
            __( $this->plural_name , 'textdomain' ),
            'manage_options',
            'edit.php?post_type=' .  $this->slug,
            NULL
        );
    }

    /**
     * Set Labels and Arguments for this Custom Post Type
     */
    protected function setArgs() {
        $args = array(
            'labels'             => $this->labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $this->slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
        );
        return $args;
    }
    protected function setLabels() {
        $labels = array(
            'name'                  => _x( $this->plural_name, 'Post type general name', 'textdomain' ),
            'singular_name'         => _x( $this->singular_name, 'Post type singular name', 'textdomain' ),
            'menu_name'             => _x( $this->plural_name, 'Admin Menu text', 'textdomain' ),
            'name_admin_bar'        => _x( $this->singular_name, 'Add New on Toolbar', 'textdomain' ),
            'add_new'               => __( 'Add New', 'textdomain' ),
            'add_new_item'          => __( 'Add New ' . $this->singular_name, 'textdomain' ),
            'new_item'              => __( 'New ' . $this->singular_name, 'textdomain' ),
            'edit_item'             => __( 'Edit ' . $this->singular_name, 'textdomain' ),
            'view_item'             => __( 'View ' . $this->singular_name, 'textdomain' ),
            'all_items'             => __( 'All ' . $this->plural_name, 'textdomain' ),
            'search_items'          => __( 'Search ' . $this->plural_name, 'textdomain' ),
            'parent_item_colon'     => __( 'Parent ' . $this->plural_name . ':', 'textdomain' ),
            'not_found'             => __( 'No ' . $this->plural_name . ' found.', 'textdomain' ),
            'not_found_in_trash'    => __( 'No ' . $this->plural_name . ' found in Trash.', 'textdomain' ),
            'featured_image'        => _x( $this->singular_name . ' Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
            'archives'              => _x( $this->singular_name . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
            'insert_into_item'      => _x( 'Insert into ' . $this->singular_name, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this ' . $this->singular_name, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
            'filter_items_list'     => _x( 'Filter ' . $this->plural_name . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
            'items_list_navigation' => _x( $this->plural_name . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
            'items_list'            => _x( $this->plural_name . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
        return $labels;
    }
    // CPT rewrite rules

    // setup metaboxes for this CustomPostType
    private function addMetaBoxes() {}

    /**
    * metabox callback functions for this postType are defined below
    */ 

    /**
    * Custom columns for Post Type Admin Screens
    */

}