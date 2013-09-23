<?php

namespace Problem\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;

class Problem implements InputFilterAwareInterface {

    public $problem_id;
    public $author;
    public $problem_name;
    public $problem_description;
    public $is_simple;
    public $compare_type;
    public $fileIn;
    public $fileOut;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->author = (!empty($data['author'])) ? $data['author'] : null;
        $this->problem_name = (!empty($data['problem_name'])) ? $data['problem_name'] : null;
        $this->problem_description = (!empty($data['problem_description'])) ? $data['problem_description'] : null;
        $this->is_simple = (!empty($data['is_simple'])) ? $data['is_simple'] : null;
        $this->compare_type = (!empty($data['compare_type'])) ? $data['compare_type'] : null;
        $this->fileIn = 'IN';
        $this->fileOut = 'OUT';
    }

    private function getFileName(array $data) {
        return 'FILES';
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $nameValidator = new Input('problem_name');
            $nameValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha')
                    ->attach(new Validator\StringLength(array(
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 45)));

            $authorValidator = new Input('author');
            $authorValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha')
                    ->attach(new Validator\StringLength(array(
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 60)));

            $descriptionValidator = new Input('problem_description');
            $descriptionValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha')
                    ->attach(new Validator\StringLength(array(
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 1000)));

            $inputFilter->add($nameValidator);
            $inputFilter->add($authorValidator);
            $inputFilter->add($descriptionValidator);

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}