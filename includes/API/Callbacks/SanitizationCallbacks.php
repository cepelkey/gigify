<?php
/**
 * @package GIGify
 */

namespace GIGify\API\Callbacks;

use GIGify\Common\BaseController;

class SanitizationCallbacks extends BaseController
{
    public function sanitizeManagersCheckbox( $input ) {
        
        $output = array();
        foreach ( $this->managers as $key => $value) {
            $output[$key] = isset( $input[$key] ) ? true : false;
        }
        return $output;
    }
    public function sanitizePostTypesCheckbox( $input ) {
        
        $output = array();
        foreach ( $this->managers as $key => $value) {
            $output[$key] = isset( $input[$key] ) ? true : false;
        }
        return $output;
    }
    public function sanitizeTextField( $input ) {
        return sanitize_text_field( $input );
    }
}