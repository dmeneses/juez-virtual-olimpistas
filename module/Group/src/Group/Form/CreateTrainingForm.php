<?php

namespace Group\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class CreateTrainingForm extends Form {

    public function __construct($name = null) {
        parent::__construct('createtraining');

        $trainingID = new Element\Hidden('training_id');

        $trainingName = new Element\Text('training_name');
        $trainingName->setAttribute('placeholder', 'Nombre del entrenamiento');
        
        $submit = new Element\Submit('submit');
        $submit->setValue('Crear');
        $submit->setAttribute('class', 'button');

        $this->add($trainingID);
        $this->add($trainingName);  
        $this->add($submit);
    }
}