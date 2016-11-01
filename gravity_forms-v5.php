<?php
class acf_field_gravity_forms extends acf_field {
  
  
  /*
  *  __construct
  *
  *  This function will setup the field type data
  *
  *  @type  function
  *  @date  5/03/2014
  *  @since 5.0.0
  *
  *  @param n/a
  *  @return  n/a
  */
  
  function __construct() {
    // vars
    $this->name = 'gravity_forms_field';
    $this->label = __('Gravity Forms');
    $this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
    $this->defaults = array(
      'return_format' => 'form_object',
      'allow_multiple' => 0,
      'allow_null' => 0
    );
    // do not delete!
    parent::__construct();
  }
  
  
  /*
  *  render_field_settings()
  *
  *  Create extra settings for your field. These are visible when editing a field
  *
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field (array) the $field being edited
  *  @return  n/a
  */
  
  function render_field_settings( $field ) {
    
    /*
    *  acf_render_field_setting
    *
    *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
    *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
    *
    *  More than one setting can be added by copy/paste the above code.
    *  Please note that you must also have a matching $defaults value for the field name (font_size)
    */
    
    acf_render_field_setting( $field, array(
      'label' => 'Allow Null?',
      'type'  =>  'radio',
      'name'  =>  'allow_null',
      'choices' =>  array(
        1 =>  __("Yes",'acf'),
        0 =>  __("No",'acf'),
      ),
      'layout'  =>  'horizontal'
    ));
    acf_render_field_setting( $field, array(
      'label' => 'Allow Multiple?',
      'type'  =>  'radio',
      'name'  =>  'allow_multiple',
      'choices' =>  array(
        1 =>  __("Yes",'acf'),
        0 =>  __("No",'acf'),
      ),
      'layout'  =>  'horizontal'
    ));
    acf_render_field_setting( $field, array(
      'label'     => __('Return Value','acf'),
      'instructions'  => __('Specify the returned value on front end','acf'),
      'type'      => 'radio',
      'name'      => 'return_format',
      'layout'    => 'horizontal',
      'choices'   => array(
        'form_object'     => __("Form Object",'acf'),
        'id'      => __("Form ID",'acf')
      ),
    ));
    
  }
  
  /*
  *  render_field()
  *
  *  Create the HTML interface for your field
  *
  *  @param $field (array) the $field being rendered
  *
  *  @type  action
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $field (array) the $field being edited
  *  @return  n/a
  */
  
  function render_field( $field ) {
    
    
    /*
    *  Review the data of $field.
    *  This will show what data is available
    */
    
	// vars
	$field = array_merge($this->defaults, $field);
	$choices = array();
	//Show notice if Gravity Forms is not activated
	if (class_exists('RGFormsModel')) {
		
		$forms = RGFormsModel::get_forms(1);
		
	}	else {
		echo "<font style='color:red;font-weight:bold;'>Warning: Gravity Forms is not installed or activated. This field does not function without Gravity Forms!</font>";
	}
    
	//Prevent undefined variable notice
    if(isset($forms)){
      foreach( $forms as $form ){
        $choices[ intval($form->id) ] = ucfirst($form->title);
      }
    }
    // override field settings and render
    $field['choices'] = $choices;
    $field['type']    = 'select';
		if ( $field['allow_multiple'] ) {
			$multiple = 'multiple="multiple" data-multiple="1"';
			echo "<input type=\"hidden\" name=\"{$field['name']}\">";
		}
		else $multiple = '';
    ?>
	<select id="<?php echo str_replace(array('[',']'), array('-',''), $field['name']);?>" name="<?php echo $field['name']; if( $field['allow_multiple'] ) echo "[]"; ?>"<?php echo $multiple; ?>>
        <?php
		if ( $field['allow_null'] ) 
			echo '<option value="">- Select -</option>';
			
		foreach ($field['choices'] as $key => $value){
			$selected = '';
			if ( (is_array($field['value']) && in_array($key, $field['value'])) || $field['value'] == $key )
				$selected = ' selected="selected"';
		?>
			<option value="<?php echo $key; ?>"<?php echo $selected;?>><?php echo $value; ?></option>
		<?php } ?>
	</select>
    <?php
  }
  
  
  /*
  *  format_value()
  *
  *  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
  *
  *  @type  filter
  *  @since 3.6
  *  @date  23/01/13
  *
  *  @param $value (mixed) the value which was loaded from the database
  *  @param $post_id (mixed) the $post_id from which the value was loaded
  *  @param $field (array) the field array holding all the field options
  *
  *  @return  $value (mixed) the modified value
  */
  
  function format_value( $value, $post_id, $field ) {

    return self::process_value($value, $field);

  }

  private function process_value($value, $field){

    if ( is_array( $value )){
      $form_objects = array();
      foreach($value as $k => $v){
        $form = self::process_value( $v, $field );
        //Add it if it's not an error object
        if ( $form ) {
          $form_objects[$k] = $form;
        }
      }
      //Return false if the array is empty
      if( !empty($form_objects) ){
        return $form_objects;
      }else{
        return false;
      }
    } else {

      if ( ! is_array($field)) {
        $field = array();
      }
      if ( empty( $field['return_format'] ) ) {

        $field['return_format'] = 'form_object';
      }


      if( $field['return_format'] == 'id' ) {
        return intval($value);
      }

      if( $field['return_format'] == 'form_object' ) {
        $form = GFAPI::get_form(intval($value));
        //Return the form object if it's not an error object. Otherwise return false.
        if( !is_wp_error($form) ){
          return $form;
        }
      }
      return false;

    }

  }

}
// create field
new acf_field_gravity_forms();