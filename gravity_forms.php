<?php

/*
 * Advanced Custom Fields - Gravity Forms Multi-Select Field add-on
 * 
 * Written by @adam_pope of @stormuk
 * Updated for 4.0 compatability by @ajmorris
 *
 * Tags: acf, acf add-on, forms, gravity forms, custom field, form field
 * Tested up to: 4.0.1
 * Stable tag: 0.1
 * 
 * Docs: https://github.com/stormuk/Gravity-Forms-ACF-Field
 */
 
class gravity_forms_field extends acf_field
{

  /*
  *  __construct
  *
  *  Set name / label needed for actions / filters
  *
  *  @since 3.6
  *  @date  23/01/13
  */
  
  function __construct()
  {

      // vars
      $this->name = 'gravity_forms_field';
      $this->label = __('Gravity Forms');
  

      // do not delete!
      parent::__construct();    
  }

  
  /*
  *  create_options()
  *
  *  Create extra options for your field. This is rendered when editing a field.
  *  The value of $field['name'] can be used (like bellow) to save extra data to the $field
  *
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field  - an array holding all the field's data
  */
  
  function create_options($field)
  {
    //role_capability
    // defaults
    $field['multiple'] = isset($field['multiple']) ? $field['multiple'] : '0';
    $field['allow_null'] = isset($field['allow_null']) ? $field['allow_null'] : '0';

    $key = $field['name'];
    
    ?>
    <tr class="field_option field_option_<?php echo $this->name; ?>">
      <td class="label">
        <label><?php _e("Allow Null?",'acf'); ?></label>
      </td>
      <td>
<?php 
        do_action('acf/create_field', array(
          'type'  =>  'radio',
          'name'  =>  'fields['.$key.'][allow_null]',
          'value' =>  $field['allow_null'],
          'choices' =>  array(
            1 =>  __("Yes",'acf'),
            0 =>  __("No",'acf'),
          ),
          'layout'  =>  'horizontal',
        ));
?>
      </td>
    </tr>
    <tr class="field_option field_option_<?php echo $this->name; ?>">
      <td class="label">
        <label><?php _e("Select multiple values?",'acf'); ?></label>
      </td>
      <td>
<?php 
        do_action('acf/create_field', array(
          'type'  =>  'radio',
          'name'  =>  'fields['.$key.'][multiple]',
          'value' =>  $field['multiple'],
          'choices' =>  array(
            1 =>  __("Yes",'acf'),
            0 =>  __("No",'acf'),
          ),
          'layout'  =>  'horizontal',
        ));
?>
      </td>
    </tr>
<?php
  }
  
  
  /*
  *  create_field()
  *
  *  Create the HTML interface for your field
  *
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field - an array holding all the field's data
  */
  
  function create_field($field)
  {

    $field['multiple'] = isset($field['multiple']) ? $field['multiple'] : false;
        
  
    // multiple select
    $multiple = '';
    if($field['multiple'] == '1')
    {
      $multiple = ' multiple="multiple" size="5" ';
      $field['name'] .= '[]';
    } 
    
    // html
    if (!isset($field['class']) || empty($field['class'])) $field['class'] = '';
    
    echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';
    
    // null
    if($field['allow_null'] == '1')
    {
      echo '<option value="null"> - Select - </option>';
    }
    
    
      
    $forms = RGFormsModel::get_forms(1);      
      
          
    if($forms)
    {
      
      foreach($forms as $k => $form)
      {
        $key = $form->id;
        $value = ucfirst($form->title); 
        $selected = '';
        
        if(is_array($field['value']))
        {
          // 2. If the value is an array (multiple select), loop through values and check if it is selected
          if(in_array($key, $field['value']))
          {
            $selected = 'selected="selected"';
          }
        }
        else
        {
          // 3. this is not a multiple select, just check normaly
          if($key == $field['value'])
          {
            $selected = 'selected="selected"';
          }
        }
        
        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';

      } 

    }         
              
    echo '</select>';
  
    
  }
  
  /*
  *  update_value()
  *
  *  This filter is appied to the $value before it is updated in the db
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value - the value which will be saved in the database
  *  @param $field - the field array holding all the field options
  *  @param $post_id - the $post_id of which the value will be saved
  *
  *  @return  $value - the modified value
  */
  
  function update_value($post_id, $field, $value)
  {
    // do stuff with value
    
    // save value
    return $value;
  }
    
  
  /*--------------------------------------------------------------------------------------
  *
  * get_value
  * - called from the edit page to get the value of your field. This function is useful
  * if your field needs to collect extra data for your create_field() function.
  *
  * @params
  * - $post_id (int) - the post ID which your value is attached to
  * - $field (array) - the field object.
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function get_value($post_id, $field)
  {
    // get value
    $value = parent::get_value($post_id, $field);
    
    // format value
    
    // return value
    return $value;    
  }
  
  
  /*
  *  format_value_for_api()
  *
  *  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value  - the value which was loaded from the database
  *  @param $field  - the field array holding all the field options
  *
  *  @return  $value  - the modified value
  */
  function format_value_for_api($value, $field)
  {
    
    // format value
    
    if(!$value)
    {
      return false;
    }
    
    if($value == 'null')
    {
      return false;
    }
    
    if(is_array($value))
    {
      foreach($value as $k => $v)
      {
        $form = RGFormsModel::get_form($v);
        $value[$k] = array();
        $value[$k] = $form;

      }
    }
    else
    {
      $value = RGFormsModel::get_form($value);
    }
    
    // return value
    return $value;
  }


  /*
  *  input_admin_enqueue_scripts()
  *
  *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
  *  Use this action to add css + javascript to assist your create_field() action.
  *
  *  $info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function input_admin_enqueue_scripts()
  {

  }

  
  /*
  *  input_admin_head()
  *
  *  This action is called in the admin_head action on the edit screen where your field is created.
  *  Use this action to add css and javascript to assist your create_field() action.
  *
  *  @info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function input_admin_head()
  {

  }
  
  
  /*
  *  field_group_admin_enqueue_scripts()
  *
  *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
  *  Use this action to add css + javascript to assist your create_field_options() action.
  *
  *  $info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function field_group_admin_enqueue_scripts()
  {

  }

  
  /*
  *  field_group_admin_head()
  *
  *  This action is called in the admin_head action on the edit screen where your field is edited.
  *  Use this action to add css and javascript to assist your create_field_options() action.
  *
  *  @info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  */

  function field_group_admin_head()
  {

  }
  
}

new gravity_forms_field();

?>
