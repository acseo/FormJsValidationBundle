# ACSEO Form JS Validation Bundle

A bundle that add js validation to you forms

## Installation

1) Install using composer

```
composer require acseo/form-js-validation-bundle
```

2) Update AppKernel.php :

```php
<?php
// app/AppKernel.php
class AppKernel extends Kernel
{
	public function registerBundles()
    {
        $bundles = [
        // ...
        new ACSEO\Bundle\FormJsValidationBundle\ACSEOFormJsValidationBundle(),
        // ...
```

3) Select your js validation plugin between validation.io and jqueryvalidation.org in the parameters.yml :

```
// app/config/parameters.yml

parameters:
    // ...
    // to use the validation.io
    acseo_form_js_validation.service: acseo_form_js_validation_io
    // or to use the jqueryvalidation.org
    acseo_form_js_validation.service: acseo_form_jquery_form_validator
```

## Usage

### Add Form validation to your form

In your controller, add js form validation :

```php
<?php
//...
public function newAction(Request $request)
{
    // ...
    $awesomeEntity = new AwesomeEntity();
    $form   = $this->createForm(AwesomeEntityType::class, $awesomeEntity,array(
        'action' => $this->generateUrl('awesomeentity_new'),
        'method' => 'POST')
    );

    // ...
    $form = $this->get("acseo_form_js_validation.service")->addJsValidation($form);
    // ...
```

### Update templates

#### Using form validation.io

1) In your base template, use formvalidation js and css : http://formvalidation.io/getting-started/#usage

2) In the template when the form is used, just update the code with this :

```twig
<!-- new.html.twig -->

{{ form_start(form, {'attr': { 'id' : 'awesome_entity_form', 'data-fv-framework' : 'bootstrap', }}) }}

<!-- display your form here -->

{{ form_end(form)}}

<script type="text/javascript">
$(document).ready(function() {
   $('#awesome_entity_form').formValidation();
});
</script>
```

#### Using form jqueryvalidation.org

1) In your base template, use jqueryvalidation js and css : https://github.com/jquery-validation/jquery-validation/releases

2) In the template when the form is used, just update the code with this :

```twig
<!-- new.html.twig -->

{{ form_start(form, {'attr': { 'id' : 'awesome_entity_form', 'data-fv-framework' : 'bootstrap', }}) }}

<!-- display your form here -->

{{ form_end(form)}}

<script type="text/javascript">
$(document).ready(function() {
   $('#awesome_entity_form').validate();
});
</script>
```
