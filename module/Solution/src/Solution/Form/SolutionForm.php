<?php

namespace Solution\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SolutionForm extends Form {

    public function __construct($name = null) {
        parent::__construct('solution');

        $solutionID = new Element\Hidden('solution_id');

        $problemID = new Element\Text('problem_id');
        $problemID->setAttribute('placeholder', 'Codigo del problema');

        $language = new Element\Select('language');
        $language->setValueOptions(array(
            'c' => 'ANSI C',
            'c++' => 'C++',
        ));
        $language->setValue('c++');  
        
        $codFile = new Element\File('solution_source');

        $submit = new Element\Submit('submit');
        $submit->setValue('Subir Solucion');
        $submit->setAttribute('class', 'button');

        $this->add($solutionID);
        $this->add($problemID);
        $this->add($language);  
        $this->add($codFile);
        $this->add($submit);
    }
}