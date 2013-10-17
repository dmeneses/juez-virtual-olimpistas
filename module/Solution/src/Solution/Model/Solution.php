<?php

namespace Solution\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;

class Solution implements InputFilterAwareInterface {

    public $solution_id;
    public $solution_date;
    public $solution_language;
    public $solution_source_file;
    public $veredict;
    public $runtime;
    public $used_memory;
    public $status;
    public $error_message;
    public $user_id;
    public $problem_id;
    public $inputFilter;

    public function exchangeArray($data) {
        $this->solution_id = (!empty($data['solution_id'])) ? $data['solution_id'] : null;
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->solution_language = (!empty($data['solution_language'])) ? $data['solution_language'] : null;
        $this->solution_source_file = (!empty($data['solution_source_file'])) ? $data['solution_source_file']['tmp_name'] : null;
        $newNameWithExtension = $this->solution_source_file . "." . $this->solution_language;
        rename($this->solution_source_file, $newNameWithExtension);
        $this->solution_source_file = $newNameWithExtension;
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $numberValidator = new Validator\Digits();
            $problemId = new Input('problem_id');
            $problemId->getValidatorChain()
                    ->addValidator($numberValidator);
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