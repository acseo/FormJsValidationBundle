<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

class FormJsValidator
{
    private $formJsValidator;

    public function __construct(FormJsValidatorInterface $formJsValidator)
    {
        $this->formJsValidator = $formJsValidator;
    }

    public function addJsValidation($form, $validationGroup = "Default")
    {
        return $this->formJsValidator->addJsValidation($form, $validationGroup);
    }
}
