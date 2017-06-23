# ACSEO Form JS Validation Bundle

A bundle that add js validation to you forms

## Installation

1) Install using composer

```
composer require acseo/form-js-validation-bundle
```

2) update AppKernel.php :

```
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

## Usage

### Using form validation.io

#### Add Form validation to your form

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
    $form = $this->container->get("acseo_form_js_validation_io")->addJsValidation($form);
    // ...
```

#### Update templates

1) in your base template, use formvalidation js and css : http://formvalidation.io/getting-started/#usage

2) in the template when the form is used, just update the code with this :

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
