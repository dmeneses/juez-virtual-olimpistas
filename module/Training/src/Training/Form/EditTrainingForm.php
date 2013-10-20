<?php

namespace Training\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Training\Form\EditTrainingFilter;
use Zend\Validator\Db\NoRecordExists;
use Zend\Db\Sql\Select;

class EditTrainingForm extends Form {

    protected $myFilter;

    public function __construct($name = null) {
        parent::__construct('training');

        $trainingID = new Element\Hidden('training_id');

        $problemID = new Element\Text('problem_id');
        $problemID->setAttribute('placeholder', 'Problema a agregar');

        $submit = new Element\Submit('addProblem');
        $submit->setValue('Agregar');
        $submit->setAttribute('class', 'button');

        $this->add($trainingID);
        $this->add($problemID);
        $this->add($submit);
    }

    public function setFilter(EditTrainingFilter $filter) {
        $this->myFilter = $filter;
        $this->setInputFilter($filter->getInputFilter());
    }

    public function isValid() {
        $problemID = $this->get('problem_id')->getValue();
        $trainingID = $this->get('training_id')->getValue();

        $select = new Select();
        $select->from('training_has_problem')
        ->where->equalTo('training_training_id', $trainingID)
        ->where->equalTo('problem_problem_id', $problemID);
        $dbValidator = new NoRecordExists($select);
        $dbValidator->setAdapter($this->myFilter->getDbAdapter());
        $dbValidator->setMessage("Problema ya añadido.", NoRecordExists::ERROR_RECORD_FOUND);
        
        $input = $this->getInputFilter()->get('problem_id');
        $input->getValidatorChain()
                ->addValidator($dbValidator);
        
        return parent::isValid();
    }

}
