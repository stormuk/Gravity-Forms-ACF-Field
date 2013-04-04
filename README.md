Gravity-Forms-ACF-Field
=======================

This is an Advanced Custom Field (v4) custom field to select one or many [Gravity Forms](http://www.gravityhelp.com/).

This provides a field that lets you select from a list of active Gravity Forms.

This plugin now only supports ACF 4.0.0 and higher. Use the commit [c671210ded](https://github.com/stormuk/Gravity-Forms-ACF-Field/tree/c671210ded11ebc69a842da3668dd0200f500753) for a previous version for ACF 3


Installation
============

Download or clone the repository for Gravity-Forms-ACF-Field and put the gravity_forms.php in your theme somewhere.  We like to create a Custom-Fields sub-directory to keep things tidy.

Register the field in your functions.php file

```
add_action('acf/register_fields', 'register_additional_fields');
 
function register_additional_fields() {
	include_once('custom-fields/gravity_forms.php');
}
```

Using the field
===============

The field lets you pick one or many fields.

The data returned is either a Form object or an array of [Form objects](http://www.gravityhelp.com/documentation/page/Form_Object).

If you have selected a single form and you want to display the form on the page, you can use:

```
<?php 
    $form = get_field('your_form_field');
    gravity_form_enqueue_scripts($form->id, true);
    gravity_form($form->id, true, true, false, '', true, 1); 
?>
```

You can find out more about the gravity_form method to embed a form on a page in their [documentation](http://www.gravityhelp.com/documentation/page/Embedding_A_Form#Function_Call)

If you are using the field to select multiple forms, you will have to iterate over the array.  You can then use the form object as you like:

```
<?php
    $forms = get_field('your_forms');
	
    foreach($forms as $form){
        echo $form->title;  
    }
?>
```


About
=====

Version: 2.0 for ACF 4

Written by Adam Pope of Storm Consultancy - <http://www.stormconsultancy.co.uk>

Storm Consultancy are a web design and development agency based in Bath, UK.

If you are looking for a [Bath WordPress Developer](http://www.stormconsultancy.co.uk/Services/Bath-WordPress-Developers), then [get in touch](http://www.stormconsultancy.co.uk/Contact)!


Credits
=======

Thanks for Lewis Mcarey for the Users Field ACF add-on on which we based this - https://github.com/lewismcarey/User-Field-ACF-Add-on

Thanks to rocketgenius for the absolutely fantastic Gravity Forms plugin!
