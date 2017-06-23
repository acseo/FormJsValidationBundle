<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

class FormValidationIo
{
    private $validator;
    private $translator;

    // http://formvalidation.io/validators/
    private $mapping = array(
        "NotBlank" => "data-fv-notempty",
        "Email" => "data-fv-emailaddress"
    );

    public function __construct($validator, $translator)
    {
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function addJsValidation($form, $validationGroup = "Default")
    {
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
                            if (!isset($this->mapping[$constraintName])) {
                                continue;
                                // throw new \Exception("The constraint ". $constraintName." is not known");
                            }
                            $options["attr"][$this->mapping[$constraintName]] = "true";
                            $options["attr"][$this->mapping[$constraintName]."-message"] = $this->translator->trans($constraint->message);
                        }

                    }
                    $form->add($name, $type, $options);
            }
        }

        return $form;
    }

}
