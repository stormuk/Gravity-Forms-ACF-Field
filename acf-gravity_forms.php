<?php
/**
 * Plugin Name: Advanced Custom Fields: Gravity Forms Field
 * Plugin URI: https://github.com/stormuk/Gravity-Forms-ACF-Field
 * Description: ACF field to select one or many Gravity Forms
 * Version: 1.1.0
 * Author: @adam_pope of @stormuk
 * Author URI: http://www.stormconsultancy.co.uk
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 */
function include_field_types_Gravity_Forms( $version )
{
    include_once( 'gravity_forms-v5.php' );
}

add_action( 'acf/include_field_types', 'include_field_types_gravity_forms' );

function register_fields_Gravity_Forms()
{
    include_once( 'gravity_forms-v4.php' );
}

add_action( 'acf/register_fields', 'register_fields_gravity_forms' );

function gff_activate()
{
    if ( class_exists( 'RGFormsModel' ) )
    {
        return true;
    }
    else
    {
        $html  = '<div class="error">';
        $html .= '<p>';
        $html .= _e( 'Warning: Gravity Forms is not installed or activated. This plugin does not function without Gravity Forms!' );
        $html .= '</p>';
        $html .= '</div>';

        echo $html;
    }
}

register_activation_hook( __FILE__, 'gff_activate' );
