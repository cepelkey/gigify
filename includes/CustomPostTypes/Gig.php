<?php

namespace GIGify\CustomPostTypes;

use \GIGify\Common\CustomPostTypes;

class Gig
{
    private $slug;
    private $args = array();
    private $labels = array();
    private $plugin_name;
    private $is_active;

    public function __construct() {
        $this->slug = 'event'; // to dynamically get name, replace with strtolower( basename( __FILE__, '.php' ) )
        $this->singular_name = \ucfirst( $this->slug );
        $this->plural_name = $this->singular_name . 's';
        $this->is_active = \GIGify\Common\CustomPostTypes::isActivePostType( 'gig' );

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

        \add_action( 'add_meta_boxes', array( $this, 'addMetaBoxes' ) );

        \add_action( 'save_post', array( $this, 'saveEventMeta' ), 10, 2 );

        \add_filter( 'manage_'.$this->slug.'_posts_columns', array( $this, 'adminColumns' ) );
        \add_action( 'manage_'.$this->slug.'_posts_custom_column', array( $this, 'manageColumns'), 10, 2);
        \add_action( 'pre_get_posts', array( $this, 'preGetPostsSort' ));
        \add_filter( 'manage_edit-'.$this->slug.'_sortable_columns', array( $this, 'sortableColumns' ));
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
            'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
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

    // setup metaboxes for this CustomPostType
    public function addMetaBoxes( $post_type ) {
        global $post, $wp_query;
        // do some magic for those pesky lost POST objects
        $wp_query->post = ( is_null( $wp_query->post->ID ) ) ? $post : $wp_query->post;
 
        add_meta_box( $this->slug.'-event_date', __( 'Event Date Details', 'textdomain' ), array( $this, 'renderEventDate' ), $this->slug, 'normal', 'high' );
        add_meta_box( 'gallery-metabox', __( 'Photo Gallery', 'texdomain' ), array( $this, 'renderGallery' ), $this->slug, 'normal', 'high' );
        add_meta_box( $this->slug.'-event_location', __( 'Event Location Details', 'textdomain' ), array( $this, 'renderEventLocation'), $this->slug, 'side', 'low' );
    }

    /**
    * metabox callback functions for this postType are defined below
    */ 
    public function renderEventDate( $post ) {
        
        wp_nonce_field( plugins_url( __FILE__ ), 'event_date_nonce' );
        
        // get dates from post_meta table
        $start_date = \get_post_meta( $post->ID, 'start_date', true );
        $end_date = \get_post_meta( $post->ID, 'end_date', true );
        
        // get times from post_meta table
        $start_time = \get_post_meta( $post->ID, 'start_time', true );
        $end_time = \get_post_meta( $post->ID, 'end_time', true );

        echo '<div><span class="label">Start Date &amp; Time:</span><input type="date" name="start_date" value="'. $start_date .'">
            <input type="time" name="start_time" value="' . $start_time . '"></div>';
        echo '<div><span class="label">End Date &amp; Time:</span><input type="date" name="end_date" value="'. $end_date .'">
            <input type="time" name="end_time" value="' . $end_time . '"></div>';
    }
    public function renderEventLocation( $post ) {

        if( ! \GIGify\Common\CustomPostTypes::isActivePostType( 'venue' ) ) {
            echo '<p>Venue Selection Not Currently Available</p><p>Enable the Venue Post Type to use this feature</p>';
        } else {
            $current_venue = intval( \get_post_meta( $post->ID, $this->slug . '_venue', true ) );
            wp_nonce_field( plugins_url( __FILE__ ), 'event_location_nonce' );
            
            $args = array(
                'post_type' => 'venue',
                'posts_per_page'  => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            );
            
            //var_dump( $post->ID, $this->slug, $current_venue );

            $venues = new \WP_Query( $args );
            if( $venues->have_posts() ) {
                echo '<select name="' . $this->slug . '_venue"><option>Select a Venue from the list</option>';

                while( $venues->have_posts() ) {
                    $venues->the_post();
                    $selected = ( $current_venue == \get_the_ID() ) ? true : false;
                    echo '<option value="' . \get_the_ID() . '" ' . (($selected) ? 'selected' : '') . '>' . \get_the_title() . '</option>';
                }
                echo '</select>';
                \wp_reset_postdata();
                \wp_reset_query();
            } else {
                echo '<p>No Venues Found... add some to see this in action!</p>';
            }
        }
    }
    public function renderGallery( $post ) {

        wp_nonce_field( plugins_url( __FILE__ ), 'gallery_meta_nonce' );

        $ids = \get_post_meta( $post->ID, $this->slug.'_gallery', true );
        ?>
        <table class="form-table">
        <tr><td>
            <a class="gallery-add button" href="#" data-uploader-title="Add image(s) to gallery" data-uploader-button-text="Add image(s)">Add image(s)</a>

            <ul id="gallery-metabox-list">
            <?php 
                if ($ids):
                    foreach( $ids as $key => $value ):
                        $image = wp_get_attachment_image_src( $value ); ?>
            <li>
                <input type="hidden" name="vdw_gallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">
                <img class="image-preview" src="<?php echo $image[0]; ?>">
                <a class="change-image button button-small" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image">Change image</a><br>
                <small><a class="remove-image" href="#">Remove image</a></small>
            </li>

            <?php 
                    endforeach;
                endif;
            ?>
            </ul>

        </td></tr>
        </table>
    <?php 
    }

    /**
    * Metabox Save Callbacks
    */
    public function saveEventMeta( $post_id, $post ) {

        if ( $this->slug != $post->post_type ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( !current_user_can( 'edit_post', $post_id ) ) return;

        // Save event dates
        if( isset( $_POST['start_date']) ) {
            $start_date = date( 'Y-m-d', \strtotime( $_POST['start_date'] ) );
            \update_post_meta( $post_id, 'start_date',  $start_date );
        }
        if( isset( $_POST['end_date']) ) {
            $end_date = date( 'Y-m-d', \strtotime( $_POST['end_date'] ) );
            \update_post_meta( $post_id, 'end_date',  $end_date );
        }

        // save event times
        if( isset( $_POST['start_time']) ) {
            $start_time = date( 'H:i', \strtotime( $_POST['start_time'] ) );
            \update_post_meta( $post_id, 'start_time',  $start_time );
        }
        if( isset( $_POST['end_time']) ) {
            $end_time = date( 'H:i', \strtotime( $_POST['end_time'] ) );
            \update_post_meta( $post_id, 'end_time',  $end_time );
        }

        // save event location
        // if( !isset( $_POST['event_location_nonce'] ) || !wp_verify_nonce( $_POST['event_location_nonce'], plugins_url(__FILE__) ) ) return;
        if( isset( $_POST[$this->slug . '_venue'] ) ) {
            $venue_id = intval( $_POST[$this->slug . '_venue'] );
            \update_post_meta( $post_id, $this->slug . '_venue',  $venue_id );
        } else {
            \delete_post_meta( $post_id, $this->slug . '_venue' );
        }

        // save event gallery
        // if ( !isset($_POST['gallery_meta_nonce']) || !wp_verify_nonce( $_POST['gallery_meta_nonce'], plugins_url(__FILE__) ) ) return;

        if ( isset( $_POST['vdw_gallery_id'] ) ) {
            $current_gallery = get_post_meta( $post_id, $this->slug.'_gallery', true );

            // if there was change in the gallery (Images Added, Romved, Reorderd, etc) update the post meta
            if( serialize( $_POST['vdw_gallery_id'] ) !== serialize( $current_gallery ) ):
                \update_post_meta( $post_id, $this->slug.'_gallery', $_POST["vdw_gallery_id"] );
            endif;
        } else {
            \delete_post_meta( $post_id, $this->slug.'_gallery' );
        };

    }

    /**
    * Customize the admin columns and sorting for this Custom Post Type
    */
    public function adminColumns( $columns ) {
        $cb = $columns['cb'];
        $columns = array(
            'cb'            => $cb,
            'title'         => __( 'Event Title' ),
            'event_date'    => __( 'Event Date' ),
            'times'         => __( 'Event Times'),
            'location'      => __( 'Event Location' ),
            'gallery'       => __( 'Event Gallery' )
        );
        return $columns;
    }

    public function manageColumns( $column, $post_id ) {
        switch( $column ) {
            case 'title':
                \get_the_title( $post_id );
                break;
            case 'event_date':
                echo date( 'l j F Y', strtotime( \get_post_meta( $post_id, 'start_date', true ) ) );
                break;
            case 'times':
                $start_time = \get_post_meta( $post_id, 'start_time', true );
                $end_time = \get_post_meta( $post_id, 'end_time', true );
                echo $start_time . ' - ' . $end_time;
                break;
            case 'location':
                $venue_id = \get_post_meta( $post_id, $this->slug.'_venue', true );
                echo ( 0 != intval($venue_id) ) ? \get_the_title( $venue_id ) : '';
                break;
            case 'gallery':
                $gallery = \get_post_meta( $post_id, $this->slug.'_gallery', true );
                echo ($gallery && \is_array( $gallery ) ) ? '&#10004' : '&#10008' ;
                break;
        }
    }

    public function sortableColumns( $columns ) {
        $columns['event_date'] = 'event_date';
        // $columns['location'] = 'gig_location';
        return $columns;
    }

    public function preGetPostsSort( $query ) {
        if( ! is_admin() || ! $query->is_main_query() ) { return; }
        if( 'event' !== $query->query_vars['post_type'] ) { return; }

        switch( $query->get( 'orderby') ) {
            case 'location':
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'related_location.post_title' );
                break;
            case 'title':
                $query->set( 'orderby', 'post_title' );
                break;
            case 'event_date':
            default:
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'start_date' );
                $query->set( 'meta_type', 'DATE' );
                break;
        }

    }

    /**
    * rewrite rules for this custom post type
    */

}