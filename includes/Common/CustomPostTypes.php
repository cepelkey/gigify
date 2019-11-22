<?php

namespace GIGify\Common;

class CustomPostTypes
{
    protected $post_type_class_path;
    protected $post_type_namespace;

    public function __construct( $plugin_path ) {
        $this->plugin_path = $plugin_path;
        $this->post_type_class_path = \str_replace( '/', DIRECTORY_SEPARATOR , $this->plugin_path) . 'includes\CustomPostTypes';
        $this->post_type_namespace = 'GIGify\\CustomPostTypes';

        /**
         * WP AJAX for Enabling and Disabling Options
         */
        \add_action( 'wp_ajax_manage_cpt_visibility', array( $this, 'manageVisibility'));
    }
    public function register() {
        foreach ( $this->getPostTypes() as $class ) {
            $cpt = $this->instantiatePostType( $class );
            if( \method_exists( $cpt, 'register' ) ) {
                $cpt->register();
            }
        }
    }
    protected function instantiatePostType( $class ) {
        $cpt = new $class();
        return $cpt;
    }

    /**
     * Use GLOB to retrieve all PHP files within the CustomPostTypes DIR
     * @return array    Array of Post Type Classes
     */
    public function getPostTypes() {
        $cpts = \glob( $this->post_type_class_path . DIRECTORY_SEPARATOR . '*.php');

        $cptList = array();
        foreach ($cpts as $cpt) {
            $name = \basename( $cpt, '.php');
            $cpt = "{$this->post_type_namespace}\\{$name}";
            $cptList[] = $cpt;
        }
        return $cptList;
    }
    public function getPostTypesArray() {
        $types = array();
        foreach( $this->getPostTypes() as $file ) {
            $name = \end( \explode( '\\', $file ) );
            $id = \strtolower( $title );
            
            $types[] = array( $id => $name );
        }

        return $types;
    }
    /**
     * Check if the specified post type is active
     */
    public static function isActivePostType( $name ) {
        $types = \get_option( 'gigify_plugin_post_types' );

        if( $types[$name] && !empty($types[$name]) ) return true;
        else return false;

    }

    /* Method for handling ajax request to enable/disable Custom Post Types */
    public function manageVisibility() {
        if ( !wp_verify_nonce( $_POST['nonce'], "gigify_custom_post_type_nonce" ) ) {
            exit("Sorry, This action is not authorized");
        }

        $cpt_options = \get_option( 'gigify_plugin_post_types' );
        $key = \filter_var( $_POST['cpt_name'], FILTER_SANITIZE_STRING );
        $value = \filter_var( $_POST['cpt_status'], FILTER_VALIDATE_BOOLEAN );

        if( !array_key_exists( $key, $cpt_options) ) {
            $new_option = array( $key => $value );
            \array_merge($cpt_options, $new_option);
        } else {
            $cpt_options[$key] = $value;
        }
        \update_option( 'gigify_plugin_post_types', $cpt_options, false );

        $data = array(
            'type'      => 'success',
            'message'   => '<p>Options updated successfully</p><p>The Admin Menu will update on page refresh</p>',
            'options'   => $cpt_options
        );

        echo \json_encode( $data );
        die();
    }


}