<?php

namespace GIGify;

final class Init {

    /**
     * Store classes inside an array
     * @return array    Full list of classes
     */
    public static function get_services(){
        return array(
            Pages\Admin::class,
            Common\Enqueue::class,
            Common\SettingsLinks::class
        );
    }

    /**
     * Loop through classes and initialize them and call register method if it exists
     * @return 
     */
    public static function register_services(){
        foreach ( self::get_services() as $class ) {
            $service = self::instantiate($class);
            if( \method_exists( $service, 'register' ) ) {
                $service->register();
            }
        }
    }
    /**
     * Initialize the class
     * @param class $class      class from services array
     * @return class instance   new instance of class
     */
    private static function instantiate( $class ) {
        $service = new $class();
        return $service;
    }
}