<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

use ACSEO\Bundle\FormJsValidationBundle\Service\AbstractFormJsValidation;
use ACSEO\Bundle\FormJsValidationBundle\Service\FormJsValidatorInterface;

class JqueryFormValidator extends AbstractFormJsValidation implements FormJsValidatorInterface
{
    protected function getMapping()
    {
        // https://jqueryvalidation.org
        $mapping = [
            // Basic Constraints
            "NotBlank" => function ($constraint, $translator) {
                return array(
                    'data-rule-required' => 'true',
                    'data-msg-required' => $translator->trans($constraint->message),
                );
            },

            // String Constraints
            "Email" => function ($constraint, $translator) {
                return array(
                    'data-rule-email' => 'true',
                    'data-msg-email' => $translator->trans($constraint->message),
                );
            },
            "Length" => function ($constraint, $translator) {
                if ($constraint->min && !$constraint->max) {
                    // Case min
                    return array(
                        "data-rule-minlength" => $constraint->min,
                        "data-msg-minlength" => $translator->trans($constraint->minMessage),
                    );
                } elseif (!$constraint->min && $constraint->max) {
                    // Case max
                    return array(
                        "data-rule-maxlength" => $constraint->max,
                        "data-msg-maxlength" => $translator->trans($constraint->maxMessage),
                    );
                } elseif ($constraint->min && $constraint->max) {
                    // Case range
                    return array(
                        "data-rule-rangelength" => sprintf('[%d, %d]', $constraint->min, $constraint->max),
                        "data-msg-rangelength" => $translator->trans($constraint->minMessage).". ".$translator->trans($constraint->maxMessage),
                    );
                }
            },
            "Url" => function ($constraint, $translator) {
                return array(
                    'data-rule-url' => 'true',
                    'data-msg-url' => $translator->trans($constraint->message),
                );
            },

            // Number Constraints
            "Range" => function ($constraint, $translator) {
                return array(
                    "data-rule-range" => sprintf('[%d, %d]', $constraint->min, $constraint->max),
                    "data-msg-range" => $translator->trans($constraint->minMessage).". ".$translator->trans($constraint->maxMessage),
                );
            },

            // Comparison Constraints
            "LessThanOrEqual" => function ($constraint, $translator) {
                return array(
                    "data-rule-min" => $constraint->value,
                    "data-msg-min" => $translator->trans($constraint->message),
                );
            },
            "GreaterThanOrEqual" => function ($constraint, $translator) {
                return array(
                    "data-rule-min" => $constraint->value,
                    "data-msg-min" => $translator->trans($constraint->message),
                );
            },
            "GreaterThan" => function ($constraint, $translator) {
                return array(
                    "data-rule-min" => $constraint->value,
                    "data-msg-min" => $translator->trans($constraint->message),
                );
            },
            // Date Constraints
            "Date" => function ($constraint, $translator) {
                return array(
                    "data-rule-dateITA" => 'true', // use dateITA to get french format (dateFR is not implemented yet)
                    "data-msg-dateITA" => $translator->trans($constraint->message),
                );
            },
        ];

        return $mapping;
    }

    protected function addEqualToConstraint($field, $attrOptions)
    {
        $parentOptions = $field->getParent()->getConfig()->getOptions();

        $attrOptions['data-rule-equalTo'] = '#'.$this->getFieldId($field->getParent()->get('first'));
        $attrOptions['data-msg-equalTo'] = $this->translator->trans(isset($parentOptions['invalid_message']) ? $parentOptions['invalid_message'] : 'Les deux champs doivent être identiques.');

        return $attrOptions;
    }
}
