<?php

namespace Training\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class EditTrainingForm extends Form {

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
}