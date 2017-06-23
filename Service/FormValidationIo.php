<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

class FormValidationIo
{
    private $validator;
    private $translator;

    public function __construct($validator, $translator)
    {
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function addJsValidation($form, $validationGroup = "Default")
    {

        $mapping = $this->getMapping();

        $metadata = $this->validator->getMetadataFor($form->getConfig()->getDataClass());
        $constrainedProperties = $metadata->getConstrainedProperties();


        foreach($form->all() as $field) {
            $name = $field->getConfig()->getName();
            $type = get_class($field->getConfig()->getType()->getInnerType());
            $options = $field->getConfig()->getOptions();

            if (in_array($name, $constrainedProperties)) {
                    $constraints = $metadata->getPropertyMetadata($name)[0]->getConstraints();
                    foreach ($constraints as $constraint) {
                        if (in_array($validationGroup, $constraint->groups)) {
                            if (!isset($options["attr"])) {
                                $options["attr"] = array();
                            }
                            $constraintName = ((new \ReflectionClass($constraint))->getShortName());
                            if (!isset($mapping[$constraintName])) {
                                continue;
                                //throw new \Exception("The constraint ". $constraintName." is not known");
                            }
                            $newAttrs = call_user_func_array($mapping[$constraintName], [$constraint, $this->translator]);
                            $options["attr"] = array_merge($options["attr"], $newAttrs);

                        }

                    }
                    $form->add($name, $type, $options);
            }
        }

        return $form;
    }

    private function getMapping()
    {
        // http://formvalidation.io/validators/
        $mapping = [
            "NotBlank" => function($constraint, $translator) {
                return array(
                    "data-fv-notempty" => "true",
                    "data-fv-notempty-message" => $translator->trans($constraint->message)
                );
            },
            "Email" => function($constraint, $translator) {
                return array(
                    "data-fv-emailaddress" => "true",
                    "data-fv-notempty-message" => $translator->trans($constraint->message)
                );
            },
            "Length" => function($constraint, $translator) {
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
