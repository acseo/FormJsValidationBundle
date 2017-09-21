<?php

namespace ACSEO\Bundle\FormJsValidationBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

abstract class AbstractFormJsValidation
{
    protected $validator;
    protected $translator;

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

        foreach ($form->all() as $field) {
            $name = $field->getConfig()->getName();
            $innerType = $field->getConfig()->getType()->getInnerType();

            if (in_array($name, $constrainedProperties)) {
                $type = get_class($innerType);
                $options = $field->getConfig()->getOptions();
                $constraints = $metadata->getPropertyMetadata($name)[0]->getConstraints();

                if (!isset($options["attr"])) {
                    $options["attr"] = array();
                }

                foreach ($constraints as $constraint) {
                    if (in_array($validationGroup, $constraint->groups)) {
                        $constraintName = ((new \ReflectionClass($constraint))->getShortName());
                        if (!isset($mapping[$constraintName])) {
                            continue;
                        }
                        $newAttrs = call_user_func_array($mapping[$constraintName], [$constraint, $this->translator]);
                        $options["attr"] = array_merge($options["attr"], $newAttrs);
                    }
                }
                if ($innerType instanceof RepeatedType) {
                    $firstOptions = isset($options["first_options"]) ? $options["first_options"] : array();
                    $options["first_options"] = $this->addRepeatedFieldJsValidation($mapping, $constraints, $field->get('first'), $firstOptions, $validationGroup);
                    $secondOptions = isset($options["second_options"]) ? $options["second_options"] : array();
                    $options["second_options"] = $this->addRepeatedFieldJsValidation($mapping, $constraints, $field->get('second'), $secondOptions, $validationGroup);
                }

                $form->add($name, $type, $options);
            }
        }

        return $form;
    }

    private function addRepeatedFieldJsValidation($mapping, $constraints, $field, $options, $validationGroup = "Default")
    {
        $options = $field->getConfig()->getOptions();

        if (!isset($options["attr"])) {
            $options["attr"] = array();
        }

        foreach ($constraints as $constraint) {
            if (in_array($validationGroup, $constraint->groups)) {
                $constraintName = ((new \ReflectionClass($constraint))->getShortName());
                if (!isset($mapping[$constraintName])) {
                    continue;
                }
                $newAttrs = call_user_func_array($mapping[$constraintName], [$constraint, $this->translator]);
                $options["attr"] = array_merge($options["attr"], $newAttrs);
            }
        }

        // Add a constraint to the second field in order to control the equality with the first one
        if ($field->getConfig()->getName() == 'second') {
            $options["attr"] = $this->addEqualToConstraint($field, $options["attr"]);
        }

        return $options;
    }

    protected function getFieldId($field)
    {
        $completeNameArray = [$field->getConfig()->getName()];
        $parent = $field;
        while ($parent = $parent->getParent()) {
            $completeNameArray[] = $parent->getConfig()->getName();
        }

        return implode('_', array_reverse($completeNameArray));
    }

    protected function addEqualToConstraint($field, $attrOptions)
    {
        return $attrOptions;
    }

    abstract protected function getMapping();
}
