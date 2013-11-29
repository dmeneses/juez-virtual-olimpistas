<?php

namespace Problem\Form;

use Problem\Model\TestCase;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Element;
use Zend\Validator\File\Extension;
use Zend\Validator\File\UploadFile;
use Zend\Filter\File\RenameUpload;

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
        $points = new Element\Number('test_points');
        $points->setLabel('Puntaje');
        $points->setAttributes(array(
            'min' => '1',
            'max' => '100',
            'step' => '1',));
        $this->add($testID);
        $this->add($points);
        $this->add($fileIn);
        $this->add($fileOut);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification() {
        return array(
            'test_points' => array(
                'required' => true,
            ),
            'test_in' => array(
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    new UploadFile(),
                    new Extension(array('txt', 'in')),
                ),
                'filters' => array(
                    new RenameUpload(array(
                        'target' => 'data/problems/fileIn',
                        'randomize' => true,))
                ),
            ),
            'test_out' => array(
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    new UploadFile(),
                    new Extension(array('txt', 'out')),
                ),
                'filters' => array(
                    new RenameUpload(array(
                        'target' => 'data/problems/fileOut',
                        'randomize' => true,))
                ),
            ),
        );
    }

}
