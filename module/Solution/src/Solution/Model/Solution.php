<?php

namespace Solution\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\File\Extension;
use Zend\Validator;
use Zend\Validator\Db\RecordExists;

class Solution implements InputFilterAwareInterface {

    public $solution_id;
    public $solution_date;
    public $solution_language;
    public $solution_source_file;
    public $grade;
    public $runtime;
    public $used_memory;
    public $status;
    public $error_message;
    public $solution_submitter;
    public $problem_id;
    public $inputFilter;
    public $dbAdapter;

    public function exchangeArray($data) {
        $this->solution_id = (!empty($data['solution_id'])) ? $data['solution_id'] : null;
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->solution_language = (!empty($data['solution_language'])) ? $data['solution_language'] : null;

        if (is_array($data['solution_source_file'])) {
            $this->solution_source_file = (!empty($data['solution_source_file'])) ? $data['solution_source_file']['tmp_name'] : null;
            $newNameWithExtension = $this->solution_source_file . "." . $this->solution_language;
            rename($this->solution_source_file, $newNameWithExtension);
            $this->solution_source_file = $newNameWithExtension;
        } else {
            $this->solution_source_file = (!empty($data['solution_source_file'])) ? $data['solution_source_file'] : null;
        }

        $this->solution_date = (!empty($data['solution_date'])) ? $data['solution_date'] : NULL;
        $this->grade = (!empty($data['grade'])) ? $data['grade'] : 0;
        $this->runtime = (!empty($data['runtime'])) ? $data['runtime'] : 0;
        $this->used_memory = (!empty($data['used_memory'])) ? $data['used_memory'] : 0;
        $this->status = (!empty($data['status'])) ? $data['status'] : 'FAILED';
        $this->error_message = (!empty($data['error_message'])) ? $data['error_message'] : '';
        $this->solution_submitter = (!empty($data['solution_submitter'])) ? $data['solution_submitter'] : NULL;
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $numberValidator = new Validator\Digits();
            $dbValidator = new RecordExists(array(
                'table' => 'problem',
                'field' => 'problem_id',
                'adapter' => $this->dbAdapter));
            $problemId = new Input('problem_id');
            $problemId->getValidatorChain()
                    ->addValidator($numberValidator)
                    ->addValidator($dbValidator);
            $problemId->getFilterChain()
                    ->attachByName('stringtrim');

            $fileExtValidator = new Extension(array('cpp', 'c'));
            $solFile = new FileInput('solution_source_file');
            $solFile->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile())
                    ->addValidator($fileExtValidator);
            $solFile->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/solutions/source',
                        'randomize' => true,
            )));


            $inputFilter->add($problemId);
            $inputFilter->add($solFile);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}
