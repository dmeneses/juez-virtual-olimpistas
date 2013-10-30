<?php

namespace Training\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Training\Form\AddGroupValidator;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class AddGroupToTrainingForm extends Form {

    protected $dbAdapter;

    public function __construct($name = null) {
        parent::__construct('training');

        $trainingID = new Element\Hidden('training_id');
    
        $groupID = new Element\Text('group_id');
        $groupID->setAttribute('placeholder', 'Grupo a agregar');

        $submit = new Element\Submit('addGroup');
        $submit->setValue('Agregar Grupo');
        $submit->setAttribute('class', 'button');

        $this->add($trainingID);
        $this->add($groupID);
        $this->add($submit);
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {

        $inputFilter = new InputFilter();
        $trainingID = $this->get('training_id')->getValue();

        $groupValidator = new AddGroupValidator();
        $groupValidator->setDbAdapter($this->dbAdapter);
        $groupValidator->setTrainingID($trainingID);

        $newGroup = new Input('group_id');
        $newGroup->getValidatorChain()
                ->addValidator($groupValidator);
        $newGroup->getFilterChain()
                ->attachByName('stringtrim');

        $inputFilter->add($newGroup);
        $this->setInputFilter($inputFilter);

        return parent::isValid();
    }

}
