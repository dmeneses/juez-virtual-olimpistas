<?php

namespace Problem\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Problem\Form\TestCaseFieldset;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Zend\Validator\File\Extension;
use Zend\Filter;
use Zend\Validator\Regex;
use Zend\InputFilter\Input;

class ProblemForm extends Form {

    public function __construct($name = null) {
        parent::__construct('problem');

        $problemID = new Element\Hidden('problem_id');

        $problemName = new Element\Text('problem_name');
        $problemName->setAttribute('placeholder', 'Nombre del problema');

        $author = new Element\Text('problem_author');
        $author->setAttribute('placeholder', 'Nombre del autor');

        $mainDescription = new Element\File('main_description');
        $mainDescription->setAttribute('accept', '.tex');
        $inputDesc = new Element\File('input_description');
        $inputDesc->setAttribute('accept', '.tex');
        $outputDesc = new Element\File('output_description');
        $outputDesc->setAttribute('accept', '.tex');
        $inputExample = new Element\File('input_example');
        $inputExample->setAttribute('accept', '.tex');
        $outputExample = new Element\File('output_example');
        $outputExample->setAttribute('accept', '.tex');

        $time = new Element\Text('time_constraint');
        $memory = new Element\Text('memory_constraint');
        $source = new Element\Text('source_constraint');

        $type = new Element\Radio('is_simple');
        $type->setValueOptions(array(
            'TRUE' => array(
                'label' => 'Simple',
                'value' => 'TRUE',
                'attributes' => array(
                    'id' => 'simple',
                ),
            ),
            'FALSE' => array(
                'label' => 'Solucion Multiple',
                'value' => 'FALSE',
                'attributes' => array(
                    'id' => 'mult',
                ),
            ),
        ));
        $type->setValue('TRUE');
        $type->setAttribute('onclick', 'checkType()');

        $compareType = new Element\Radio('compare_type');
        $compareType->setValueOptions(array(
            'STRICT' => array(
                'label' => 'Estricta',
                'value' => 'STRICT',
                'attributes' => array(
                    'id' => 'strict',
                ),
            ),
            'TOKEN' => array(
                'label' => 'Omitir token',
                'value' => 'TOKEN',
                'attributes' => array(
                    'id' => 'token',
                ),
            ),
        ));
        $compareType->setValue('STRICT');
        $compareType->setAttribute('onclick', 'checkComparation()');

        $avoidSymbol = new Element\Text('avoid_symbol');
        $avoidSymbol->setAttribute('placeholder', 'Simbolo que no se validara...');

        $file = new Element\File('file');
        $file->setAttribute('accept', '.png,.jpg');

        $images = new Element\Collection('images');
        $images->setLabel("Imagenes");
        $images->setCount(0);
        $images->allowAdd(true);
        $images->allowRemove(true);
        $images->setShouldCreateTemplate(true);
        $images->setTargetElement($file);

        $tests = new Element\Collection('tests');
        $tests->setLabel("Pruebas");
        $tests->setCount(1);
        $tests->allowAdd(true);
        $tests->allowRemove(true);
        $tests->setShouldCreateTemplate(true);
        $tests->setTargetElement(new TestCaseFieldset());

        $submit = new Element\Submit('submit');
        $submit->setValue('Proponer');
        $submit->setAttribute('class', 'button');
        $this->setAttribute('onsubmit', 'return gradeCheck();');

        $this->add($problemID);
        $this->add($problemName);
        $this->add($author);
        $this->add($time);
        $this->add($memory);
        $this->add($source);
        $this->add($mainDescription);
        $this->add($inputDesc);
        $this->add($outputDesc);
        $this->add($inputExample);
        $this->add($outputExample);
        $this->add($type);
        $this->add($images);
        $this->add($tests);
        $this->add($compareType);
        $this->add($avoidSymbol);
        $this->add($submit);
    }

    public function isValid() {
        $fileCollection = new InputFilter();
        $pngExt = new Extension(array('png'));
        for ($i = 0; $i < count($this->get('images')); $i++) {
            $file = new FileInput($i);
            $file->setRequired(true);
            $file->getValidatorChain()
                    ->addValidator($pngExt);
            $file->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './public_html/problems/image.png',
                        'randomize' => true,)));
            $fileCollection->add($file);
        }

        if ($this->get('compare_type')->getValue() == 'TOKEN') {
            $regex = new Regex('#^[[:punct:][:space:]]$#');
            $regex->setMessage("Solo se permiten un simbolo de puntuacion o un espacio.", Regex::NOT_MATCH);
            $nameValidator = new Input('avoid_symbol');
            $nameValidator->setRequired(false);
            $nameValidator->getValidatorChain()
                    ->addValidator($regex);
            $this->getInputFilter()->add($nameValidator);
        }
        $this->getInputFilter()->add($fileCollection, 'images');

        return parent::isValid();
    }

}
