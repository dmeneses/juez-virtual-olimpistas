<?php

namespace Problem\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ProblemForm extends Form {

    public function __construct($name = null) {
        parent::__construct('problem');

        $problemID = new Element\Hidden('problem_id');

        $problemName = new Element\Text('problem_name');
        $problemName->setAttribute('placeholder', 'Nombre del problema');

        $author = new Element\Text('author');
        $author->setAttribute('placeholder', 'Nombre del autor');
        
        $description = new Element\Textarea('problem_description');
        $description->setAttribute('placeholder', 'Descripcion del problema');

        $type = new Element\Radio('is_simple');
        $type->setValueOptions(array(
            'TRUE' => 'Simple',
            'FALSE' => 'Solucion Multiple',
        ));       
        $type->setValue('TRUE');

        $compareType = new Element\Radio('compare_type');
        $compareType->setValueOptions(array(
            'STRICT' => 'Estricta',
            'TOKEN' => 'Omitir token',
        ));
        $compareType->setValue('STRICT');
        
        $fileIn = new Element\File('fileIn');
        $fileOut = new Element\File('fileOut');

        $submit = new Element\Submit('submit');
        $submit->setValue('Proponer');
        $submit->setAttribute('class', 'button');

        $this->add($problemID);
        $this->add($problemName);
        $this->add($author);        
        $this->add($description);
        $this->add($type);
        $this->add($compareType);
        $this->add($fileIn);
        $this->add($fileOut);
        $this->add($submit);
    }
}