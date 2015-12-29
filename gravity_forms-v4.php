<?php
/**
 * Description: ACF field to select one or many Gravity Forms
 * Version: 1.1.0
 * Author: @adam_pope of @stormuk
 * Author URI: http://www.stormconsultancy.co.uk
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 */
class acf_field_gravity_forms extends acf_field
{
    var $settings,
        $defaults;

    /**
     * __construct()
     *
     * Set name / label needed for actions / filters
     *
     * @since 3.6
     * @date  23/01/13
     */
    function __construct()
    {
        $this->name     = 'gravity_forms_field';
        $this->label    = __( 'Gravity Forms' );
        $this->category = __( 'Relational', 'acf' );
        $this->defaults = array(
            'allow_multiple' => 0,
            'allow_null'     => 0
        );

        parent::__construct();
    }

    /**
     * create_options()
     *
     * Create extra options for your field. This is rendered when editing a field.
     * The value of $field['name'] can be used (like bellow) to save extra data to the $field
     *
     * @type  action
     * @since 3.6
     * @date  23/01/13
     *
     * @param $field - an array holding all the field's data
     */
    function create_options( $field )
    {
        $field = array_merge( $this->defaults, $field );
        $key   = $field['name'];
?>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( 'Allow Null?', 'acf' ); ?></label>
            </td>
            <td>
                <?php
                    do_action( 'acf/create_field', array(
                        'type'    => 'radio',
                        'name'    => 'fields[' . $key . '][allow_null]',
                        'value'   => $field['allow_null'],
                        'choices' => array(
                            1 => __( 'Yes', 'acf' ),
                            0 => __( 'No', 'acf' ),
                        ),
                        'layout'  => 'horizontal',
                    ) );
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e( "Select multiple values?", 'acf' ); ?></label>
            </td>
            <td>
                <?php
                    do_action( 'acf/create_field', array(
                        'type'    => 'radio',
                        'name'    => 'fields[' . $key . '][multiple]',
                        'value'   => $field['multiple'],
                        'choices' => array(
                            1 => __( 'Yes', 'acf' ),
                            0 => __( 'No', 'acf' ),
                        ),
                        'layout'  => 'horizontal',
                    ) );
                ?>
            </td>
        </tr>
<?php
    }

    /**
     * create_field()
     *
     * Create the HTML interface for your field
     *
     * @param $field - an array holding all the field's data
     *
     * @type  action
     * @since 3.6
     * @date  23/01/13
     */
    function create_field( $field )
    {
        $field   = array_merge( $this->defaults, $field );
        $choices = array();

        if ( class_exists( 'RGFormsModel' ) )
        {
            $forms = RGFormsModel::get_forms( 1 );
        }
        else
        {
            echo '<font style="color:red; font-weight:bold;">Warning: Gravity Forms is not installed or activated. This field does not function without Gravity Forms!</font>';
        }

        if ( isset( $forms ) )
        {
            foreach ( $forms as $form )
            {
                $choices[$form->id] = ucfirst( $form->title );
            }
        }

        $field['choices'] = $choices;
        $field['type']    = 'select';

        do_action( 'acf/create_field', $field );
    }

    /**
     * format_value_for_api()
     *
     * This filter is applied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
     *
     * @type  filter
     * @since 3.6
     * @date  23/01/13
     *
     * @param $value   - the value which was loaded from the database
     * @param $post_id - the $post_id from which the value was loaded
     * @param $field   - the field array holding all the field options
     *
     * @return $value - the modified value
     */
    function format_value_for_api( $value, $field )
    {
        if ( ! $value OR empty( $value ) )
        {
            return false;
        }

        if ( is_array( $value ) && ! empty( $value ) )
        {
            $form_objects = array();

            foreach ( $value as $k => $v )
            {
                $form = GFAPI::get_form( $v );

                if ( ! is_wp_error( $form ) )
                {
                    $form_objects[$k] = $form;
                }
            }

            return ( ! empty( $form_objects ) ) ? $form_objects : false;
        }
        else
        {
            $form = GFAPI::get_form( intval( $value ) );

            return ( ! is_wp_error( $form ) ) ? $form : false;
        }
    }
}

new acf_field_gravity_forms();
