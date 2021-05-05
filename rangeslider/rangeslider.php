<?php
/*
Plugin Name: Range Slider - Gravity Forms Add-On
Plugin URI: http://www.kamillahoybye.dk
Description: Simple add-on for Gavity Forms. Made exclusively for Pool Eksperten. 
Version: 0.84
Author: Kamilla Høybye Strøbæk // Mirrave
Author URI: http://www.kamillahoybye.dk

*/

define( 'GF_RANGE_SLIDER_VERSION', '0.84' );

add_action( 'gform_loaded', array( 'GF_Range_Slider_Bootstrap', 'load' ), 5 );

class GF_Range_Slider_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-gfrangeslider.php' );

        GFAddOn::register( 'GFRangeSlider' );
    }

}