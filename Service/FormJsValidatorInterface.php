<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

interface FormJsValidatorInterface
{
    public function addJsValidation($form, $validationGroup = "Default");
}
