<?php

namespace Training\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Training\Form\AddProblemValidator;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class AddProblemToTrainingForm extends Form {

    protected $dbAdapter;

    public function __construct($name = null) {
        parent::__construct('training');

        $trainingID = new Element\Hidden('training_id');

        $problemID = new Element\Text('problem_id');
        $problemID->setAttribute('placeholder', 'Problema a agregar');

        $submit = new Element\Submit('addProblem');
        $submit->setValue('Agregar Problema');
        $submit->setAttribute('class', 'button');
        $this->add($trainingID);
        $this->add($problemID);
        $this->add($submit);
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {

        $inputFilter = new InputFilter();
        $trainingID = $this->get('training_id')->getValue();

        $problemValidator = new AddProblemValidator();
        $problemValidator->setDbAdapter($this->dbAdapter);
        $problemValidator->setTrainingID($trainingID);

        $newProblem = new Input('problem_id');
        $newProblem->getValidatorChain()
                ->addValidator($problemValidator);
        $newProblem->getFilterChain()
                ->attachByName('stringtrim');

        $inputFilter->add($newProblem);
        $this->setInputFilter($inputFilter);

        return parent::isValid();
    }

}
