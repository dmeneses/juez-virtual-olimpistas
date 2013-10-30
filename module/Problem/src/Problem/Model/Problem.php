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
use Zend\Validator\File\UploadFile;

class Problem implements InputFilterAwareInterface {

    public $problem_id;
    public $problem_name;
    public $problem_author;
    public $time_constraint;
    public $memory_constraint;
    public $source_constraint;
    public $is_simple;
    public $compare_type;
    public $file_in;
    public $file_out;
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

        if (isset($data['file_in']) && is_array($data['file_in'])) {
            $this->file_in = (!empty($data['file_in'])) ? $data['file_in']['tmp_name'] : null;
        } else {
            $this->file_in = (!empty($data['file_in'])) ? $data['file_in'] : null;
        }

        if (isset($data['file_out']) && is_array($data['file_out'])) {
            $this->file_out = (!empty($data['file_out'])) ? $data['file_out']['tmp_name'] : null;
        } else {
            $this->file_out = (!empty($data['file_out'])) ? $data['file_out'] : null;
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

            $fileInExtValidator = new Extension(array('txt', 'in'));
            $fileIn = new FileInput('file_in');
            $fileIn->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile())
                    ->addValidator($fileInExtValidator);
            $fileIn->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/fileIn',
                        'randomize' => true,
            )));

            $fileOutExtValidator = new Extension(array('txt', 'out'));
            $fileOut = new FileInput('file_out');
            $fileOut->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile())
                    ->addValidator($fileOutExtValidator);
            $fileOut->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/fileOut',
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
            $inputFilter->add($fileIn);
            $inputFilter->add($fileOut);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}
