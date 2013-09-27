<?php

namespace Problem\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;

class Problem implements InputFilterAwareInterface {

    public $problem_id;
    public $author;
    public $time_limit;
    public $memory_limit;
    public $source_limit;
    public $problem_name;
    public $problem_description;
    public $is_simple;
    public $compare_type;
    public $fileIn;
    public $fileOut;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->problem_name = (!empty($data['problem_name'])) ? $data['problem_name'] : null;
        $this->author = (!empty($data['author'])) ? $data['author'] : null;
        $this->problem_description = (!empty($data['problem_description'])) ? $data['problem_description'] : null;
        $this->time_limit = (!empty($data['time_limit'])) ? $data['time_limit'] : null;
        $this->memory_limit = (!empty($data['memory_limit'])) ? $data['memory_limit'] : null;
        $this->source_limit = (!empty($data['source_limit'])) ? $data['source_limit'] : null;
        $this->is_simple = (!empty($data['is_simple'])) ? $data['is_simple'] : null;
        $this->compare_type = (!empty($data['compare_type'])) ? $data['compare_type'] : null;
        $this->fileIn = (!empty($data['fileIn'])) ? $data['fileIn']['tmp_name'] : null;
        $this->fileOut = (!empty($data['fileOut'])) ? $data['fileOut']['tmp_name'] : null;
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $lengthValidator = new Validator\StringLength(array('min' => 3, 'max' => 50));
            $nameValidator = new Input('problem_name');
            $nameValidator->getValidatorChain()
                    ->addValidator($lengthValidator);
            $nameValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha');

            $authorValidator = new Input('author');
            $authorValidator->getValidatorChain()
                    ->addValidator($lengthValidator);
            $authorValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha');

            $numberValidator = new Validator\Digits();
           
            $timeValidator = new Input('time_limit');
            $timeValidator->getValidatorChain()
                    ->addValidator($numberValidator);
            $timeValidator->getFilterChain()
                    ->attachByName('stringtrim');
            
            $memoryValidator = new Input('memory_limit');
            $memoryValidator->getValidatorChain()
                    ->addValidator($numberValidator);
            $memoryValidator->getFilterChain()
                    ->attachByName('stringtrim');
            
            $sourceValidator = new Input('source_limit');
            $sourceValidator->getValidatorChain()
                    ->addValidator($numberValidator);
            $sourceValidator->getFilterChain()
                    ->attachByName('stringtrim');


            $descriptionValidator = new Input('problem_description');
            $descriptionValidator->getValidatorChain()
                    ->addValidator(new Validator\StringLength(array('min' => 10)));
            $descriptionValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $fileIn = new FileInput('fileIn');
            $fileIn->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile());
            $fileIn->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/tmpuploads/fileIn',
                        'randomize' => true,
            )));

            $fileOut = new FileInput('fileOut');
            $fileOut->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile());
            $fileOut->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/tmpuploads/fileOut',
                        'randomize' => true,
            )));


            $inputFilter->add($nameValidator);
            $inputFilter->add($authorValidator);
            $inputFilter->add($timeValidator);
            $inputFilter->add($memoryValidator);
            $inputFilter->add($sourceValidator);
            $inputFilter->add($descriptionValidator);
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