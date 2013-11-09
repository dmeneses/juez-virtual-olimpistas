<?php

namespace Problem\Form;

use Problem\Model\TestCase;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Element;

/**
 * Defines fieldset for each test case.
 *
 * @author Daniela Meneses
 */
class TestCaseFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {
        parent::__construct('tests');

        $this
                ->setHydrator(new ClassMethodsHydrator(false))
                ->setObject(new TestCase());

        $this->setLabel('Caso de Prueba');

        $testID = new Element\Hidden('test_id');
        $fileIn = new Element\File('test_in');  
        $fileIn->setLabel('Entrada');
        $fileOut = new Element\File('test_out');
        $fileOut->setLabel('Salida');
        $points = new Element\Text('test_points');
        $points->setLabel('Puntaje');

        $this->add($testID);
        $this->add($points);
        $this->add($fileIn);
        $this->add($fileOut);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification() {
        return array();
    }

}
