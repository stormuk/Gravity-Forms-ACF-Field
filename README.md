Gravity-Forms-ACF-Field
=======================

This is an Advanced Custom Field custom field to select one or many Gravity Forms.

This provides a field that lets you select from a list of active Gravity Forms.


Installation
============

Download or clone the repository for Gravity-Forms-ACF-Field and put the gravity_forms.php in your theme somewhere.  We like to create a Custom-Fields sub-directory to keep things tidy.

Register the field in your functions.php file

if(function_exists('register_field')) { 
  register_field(‘Gravity_Forms_field', dirname(__File__) . '/Custom-Fields/gravity_forms.php');
}

Using the field
===============

The field lets you pick one or many fields.

The data returned is either a Form object or an array of [Form objects](http://www.gravityhelp.com/documentation/page/Form_Object).



About
=====

Version: 1.0

Written by Adam Pope of Storm Consultancy - <http://www.stormconsultancy.co.uk>

Storm Consultancy are a web design and development agency based in Bath, UK.

If you are looking for a [Bath WordPress Developer](http://www.stormconsultancy.co.uk/Services/Bath-WordPress-Developers), then [get in touch](http://www.stormconsultancy.co.uk/Contact)!
