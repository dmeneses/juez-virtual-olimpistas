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
    public $problem_id;
    public $language;
    public $solution_source;
    public $inputFilter;

    public function exchangeArray($data) {
        $this->solution_id = (!empty($data['solution_id'])) ? $data['solution_id'] : null;
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->language = (!empty($data['language'])) ? $data['language'] : null;
        $this->solution_source = (!empty($data['solution_source'])) ? $data['solution_source']['tmp_name'] : null;
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


            $solFile = new FileInput('solution_source');
            $solFile->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile());
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