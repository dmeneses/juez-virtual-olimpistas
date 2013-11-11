<?php

namespace Problem\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\NotEmpty;
use Zend\Validator\File\Extension;
use Problem\Model\TestCase;

/**
 * Defines a problem for the virtual judge.
 */
class Problem implements InputFilterAwareInterface {

    public $problem_id;
    public $problem_name;
    public $problem_author;
    public $time_constraint;
    public $memory_constraint;
    public $source_constraint;
    public $is_simple;
    public $compare_type;
    public $tests;
    protected $inputFilter;
    protected $adapter;

    public function setDatabaseAdapter($adapter) {
        $this->adapter = $adapter;
    }

    public function exchangeArray($data) {
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->problem_name = (!empty($data['problem_name'])) ? $data['problem_name'] : null;
        $this->problem_author = (!empty($data['problem_author'])) ? $data['problem_author'] : null;
        $this->time_constraint = (!empty($data['time_constraint'])) ? $data['time_constraint'] : null;
        $this->memory_constraint = (!empty($data['memory_constraint'])) ? $data['memory_constraint'] : null;
        $this->source_constraint = (!empty($data['source_constraint'])) ? $data['source_constraint'] : null;
        $this->is_simple = (!empty($data['is_simple'])) ? $data['is_simple'] : null;
        $this->compare_type = (!empty($data['compare_type'])) ? $data['compare_type'] : null;
        if (isset($data['tests'])) {
            $this->exchangeTests($data['tests']);
        }
    }

    public function exchangeTests(array $testsToAdd) {
        if (!is_array($testsToAdd)) {
            throw \Exception("Tests for problem are missing.");
        }

        $this->tests = array();
        foreach ($testsToAdd as &$test) {
            $testCase = new TestCase();
            $testCase->exchangeArray($test);
            array_push($this->tests, $testCase);
        }
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $lengthValidator = new Validator\StringLength(array('min' => 3, 'max' => 50));

            $duplicateValidator = new NoRecordExists(
                    array(
                'table' => 'problem',
                'field' => 'problem_name',
                'adapter' => $this->adapter,
            ));

            $nameValidator = new Input('problem_name');
            $nameValidator->getValidatorChain()
                    ->addValidator($lengthValidator)
                    ->addValidator($duplicateValidator);
            $nameValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $authorValidator = new Input('problem_author');
            $authorValidator->getValidatorChain()
                    ->addValidator($lengthValidator);
            $authorValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha', array('allowwhitespace' => true));

            $numberValidator = new Validator\Digits();
            $notNullValidator = new NotEmpty(NotEmpty::INTEGER + NotEmpty::ZERO);

            $timeValidator = new Input('time_constraint');
            $timeValidator->getValidatorChain()
                    ->addValidator($numberValidator)
                    ->addValidator($notNullValidator);
            $timeValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $memoryValidator = new Input('memory_constraint');
            $memoryValidator->getValidatorChain()
                    ->addValidator($numberValidator)
                    ->addValidator($notNullValidator);
            $memoryValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $sourceValidator = new Input('source_constraint');
            $sourceValidator->getValidatorChain()
                    ->addValidator($numberValidator)
                    ->addValidator($notNullValidator);
            $sourceValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $texFileValidator = new Extension(array('tex'));
            $mainDesc = new Input('main_description');
            $mainDesc->getValidatorChain()
                    ->addValidator($texFileValidator);
            $mainDesc->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/descriptions/main',
                        'randomize' => true,
            )));
            $inputDesc = new Input('input_description');
            $inputDesc->getValidatorChain()
                    ->addValidator($texFileValidator);
            $inputDesc->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/descriptions/input',
                        'randomize' => true,
            )));

            $outputDesc = new Input('output_description');
            $outputDesc->getValidatorChain()
                    ->addValidator($texFileValidator);
            $outputDesc->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/descriptions/output',
                        'randomize' => true,
            )));

            $inputExample = new Input('input_example');
            $inputExample->getValidatorChain()
                    ->addValidator($texFileValidator);
            $inputExample->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/descriptions/inexample',
                        'randomize' => true,
            )));

            $outputExample = new Input('output_example');
            $outputExample->getValidatorChain()
                    ->addValidator($texFileValidator);
            $outputExample->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/descriptions/outexample',
                        'randomize' => true,
            )));

            $inputFilter->add($nameValidator);
            $inputFilter->add($authorValidator);
            $inputFilter->add($timeValidator);
            $inputFilter->add($memoryValidator);
            $inputFilter->add($sourceValidator);
            $inputFilter->add($mainDesc);
            $inputFilter->add($inputDesc);
            $inputFilter->add($outputDesc);
            $inputFilter->add($inputExample);
            $inputFilter->add($outputExample);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}
