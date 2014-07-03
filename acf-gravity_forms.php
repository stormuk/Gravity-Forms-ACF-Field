<?php
/*
Plugin Name: Advanced Custom Fields: Gravity Forms Field
Plugin URI: https://github.com/stormuk/Gravity-Forms-ACF-Field
Description: ACF field to select one or many Gravity Forms
Version: 1.0.0
Author: @adam_pope of @stormuk
Author URI: http://www.stormconsultancy.co.uk
License: MIT
License URI: http://opensource.org/licenses/MIT
*/

// $version = 5 and can be ignored until ACF6 exists
function include_field_types_Gravity_Forms( $version ) {

  include_once('gravity_forms-v5.php');

}

add_action('acf/include_field_types', 'include_field_types_gravity_forms'); 


function register_fields_Gravity_Forms() {
  include_once('gravity_forms-v4.php');
}

add_action('acf/register_fields', 'register_fields_gravity_forms');


?>