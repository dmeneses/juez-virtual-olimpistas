<?php

namespace Training\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Training\Form\AddGroupValidator;

class AddComponentToTraining extends Form {

    protected $dbAdapter;
    private $trainingID;
    private $componentName;

    public function __construct($trainingID, $componentName, $name = null) {
        parent::__construct('training');
        $this->trainingID = $trainingID;
        $this->componentName = $componentName;
        $groupID = new Element\Text($componentName . '_id');
        $groupID->setAttribute('placeholder', $componentName . ' id to add or delete');

        $submit = new Element\Submit('add' . $componentName);
        $submit->setValue('Agregar');
        $submit->setAttribute('class', 'button');

        $submit2 = new Element\Submit('remove' . $componentName);
        $submit2->setValue('Borrar');
        $submit2->setAttribute('class', 'button');

        $this->add($groupID);
        $this->add($submit);
        $this->add($submit2);
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {

        $inputFilter = new InputFilter();

        $groupValidator = new AddGroupValidator();
        $groupValidator->setDbAdapter($this->dbAdapter);
        $groupValidator->setTrainingID($this->trainingID);

        $newGroup = new Input($this->componentName . '_id');
        $newGroup->getValidatorChain()
                ->addValidator($groupValidator);
        $newGroup->getFilterChain()
                ->attachByName('stringtrim');

        $inputFilter->add($newGroup);
        $this->setInputFilter($inputFilter);

        return parent::isValid();
    }

}
