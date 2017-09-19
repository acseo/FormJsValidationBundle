<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

use ACSEO\Bundle\FormJsValidationBundle\Service\AbstractFormJsValidation;

class FormValidationIo extends AbstractFormJsValidation
{
    protected function getMapping()
    {
        // http://formvalidation.io/validators/
        $mapping = [
            "NotBlank" => function ($constraint, $translator) {
                return array(
                    "data-fv-notempty" => "true",
                    "data-fv-notempty-message" => $translator->trans($constraint->message)
                );
            },
            "Email" => function ($constraint, $translator) {
                return array(
                    "data-fv-emailaddress" => "true",
                    "data-fv-notempty-message" => $translator->trans($constraint->message)
                );
            },
            "Length" => function ($constraint, $translator) {
                return array(
                    "data-fv-stringlength-max" => $constraint->max,
                    "data-fv-stringlength-min" => $constraint->min,
                    "data-fv-stringlength-message" => $translator->trans($constraint->minMessage).". ".$translator->trans($constraint->maxMessage)
                );
            },
        ];

        return $mapping;
    }
}
