<?php

namespace Training\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Training\Form\AddValidator;
use Training\Form\RemoveValidator;

class AddElementToTraining extends Form {

    protected $dbAdapter;
    private $trainingID;
    private $elementName;
    private $validateAdd = true;

    public function __construct($trainingID, $elementName, $name = null) {
        parent::__construct('training');
        $this->trainingID = $trainingID;
        $this->elementName = $elementName;
        $groupID = new Element\Text($elementName . '_id');
        $groupID->setAttribute('placeholder', $elementName . ' id to add or delete');
        $groupID->setAttribute('id', $elementName . '_id');

        $submit = new Element\Submit('add' . $elementName);
        $submit->setValue('Agregar');
        $submit->setAttribute('class', 'button');

        $submit2 = new Element\Submit('remove' . $elementName);
        $submit2->setValue('Borrar');
        $submit2->setAttribute('class', 'button');

        $this->add($groupID);
        $this->add($submit);
        $this->add($submit2);
    }

    public function setValidateAdd($validateAdd) {
        $this->validateAdd = $validateAdd;
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {

        $inputFilter = new InputFilter();
        if ($this->validateAdd) {
            $addValidator = new AddValidator();
            $addValidator->setDbAdapter($this->dbAdapter);
            $addValidator->setTrainingID($this->trainingID);
            $addValidator->setElementType($this->elementName);

            $newElement = new Input($this->elementName . '_id');
            $newElement->getValidatorChain()
                    ->addValidator($addValidator);
            $newElement->getFilterChain()
                    ->attachByName('stringtrim');

            $inputFilter->add($newElement);
        } else {
            $removeValidator = new RemoveValidator();
            $removeValidator->setDbAdapter($this->dbAdapter);
            $removeValidator->setTrainingID($this->trainingID);
            $removeValidator->setElementType($this->elementName);

            $elementToRemove = new Input($this->elementName . '_id');
            $elementToRemove->getValidatorChain()
                    ->addValidator($removeValidator);
            $elementToRemove->getFilterChain()
                    ->attachByName('stringtrim');

            $inputFilter->add($elementToRemove);
        }
                
        $this->setInputFilter($inputFilter);

        return parent::isValid();
    }

}
