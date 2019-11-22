<?php
/**
 * @package GIGify
 */

namespace GIGify\API\Callbacks;

use GIGify\Common\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard() {
        return require_once( $this->plugin_path . "/templates/Admin/admin-dashboard.php" );
    }
    public function adminCPTmanager() {
        return require_once( $this->plugin_path . "/templates/Admin/admin-cpts.php" );
    }
    public function adminRoleManager() {
        return require_once( $this->plugin_path . "/templates/Admin/admin-roles.php" );
    }
    public function adminTaxonomyManager() {
        return require_once( $this->plugin_path . "/templates/Admin/admin-taxonomies.php" );
    }
    public function adminWidgetManager() {
        return require_once( $this->plugin_path . "/templates/Admin/admin-widgets.php" );
    }

    /* Custom Fields for Admin */
    public function gigifyOptionsGroup( $input ) {
        return $input;
    }
    public function gigifyAdminSection() {
        echo 'Manage Sections and Features by toggling switches below.';
    }
    public function gigifyAdminCptSection() {
        echo 'Enable/Disable Custom Post Types included with this plugin by toggling the switches below.';
    }

    // callbacks for fields
    public function checkboxField( $args ) {
        $name = $args['label_for'];
        $classes = $args['classes'];
        $option_name = $args['option_name'];

        $checkbox = get_option( $option_name );
        $checked = isset( $checkbox[$name] ) ? ( ( $checkbox[$name] && !empty($checkbox[$name]) ) ? true : false ) : false;

        $nonce = isset( $args['nonce'] ) ? $args['nonce'] : false;

        echo '<div class="' . $classes. '"><input type="checkbox" name="' . $option_name . '[' . $name . ']" id="' 
        . $name . '" value="1" ' . ($checked ? 'checked' : '') . ' ' .($nonce ? 'data-nonce="'.$nonce.'"' : '') . '><label for="' . $name . '"><div><div></label></div>';
    }

    public function textField( $args ) {

        echo '<div class=""><input type="text" id="" name="" value="" placeholder=""></div>';
    }
}