<?php

namespace Training\Form;

use Zend\InputFilter\Input;
use Zend\Form\Form;
use Zend\Form\Element;
use Training\Model\DateValidator;
use Training\Model\DateValidationType;
use Training\Model\Training;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\Db\NoRecordExists;

class CreateTrainingForm extends Form {

    public function __construct($name = null) {
        parent::__construct('createtraining');

        $trainingID = new Element\Hidden('training_id');

        $trainingName = new Element\Text('training_name');
        $trainingName->setAttribute('placeholder', 'Nombre del entrenamiento');

        $startDate = new Element\DateTime('start_date');
        $endDate = new Element\DateTime('end_date');

        $startTime = new Element\Time('start_time');
        $endTime = new Element\Time('end_time');

        $submit = new Element\Submit('submit');
        $submit->setValue('Crear');
        $submit->setAttribute('class', 'button');

        $this->add($trainingID);
        $this->add($trainingName);
        $this->add($startDate);
        $this->add($endDate);
        $this->add($startTime);
        $this->add($endTime);
        $this->add($submit);
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {
        $startDate = $this->get(Training::START)->getValue();
        $startTime = $this->get('start_time')->getValue();
        $endDate = $this->get('end_date')->getValue();
        $inputFilter = new InputFilter();
        $inputFilter->add($this->getNameInput());
        $inputFilter->add($this->getStartDateInput());
        $inputFilter->add($this->getEndDateInput($startDate));
        $inputFilter->add($this->getStartTimeInput($startDate));
        $inputFilter->add($this->getEndTimeInput($startDate, $startTime, $endDate));
        $this->setInputFilter($inputFilter);
        return parent::isValid();
    }

    private function getNameInput() {
        $lengthValidator = new StringLength(array('min' => 3, 'max' => 50));
        $dbValidator = new NoRecordExists(array('table' => 'training', 'field' => 'training_name', 'adapter' => $this->dbAdapter,));
        $dbValidator->setMessage("El entrenamiento ya existe.", NoRecordExists::ERROR_RECORD_FOUND);
        $nameInput = new Input(Training::NAME);
        $nameInput->getValidatorChain()
                ->addValidator($lengthValidator)
                ->addValidator($dbValidator);
        $nameInput->getFilterChain()
                ->attachByName('stringtrim');
        return $nameInput;
    }

    private function getStartDateInput() {
        $validator = new DateValidator();
        $todayDate = date("Y-m-d");
        $validator->setComparedDate($todayDate);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setMessage('Must be equal or later than current date.', DateValidator::NOT_LATER);
        $input = new Input(Training::START);
        $input->getValidatorChain()->addValidator($validator);
        return $input;
    }
    
    private function getEndDateInput($startDate) {
        $validator = new DateValidator();
        $validator->setComparedDate($startDate);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setMessage('Must be equal or later than start date.', DateValidator::NOT_LATER);
        $input = new Input(Training::END);
        $input->getValidatorChain()->addValidator($validator);
        return $input;
    }
    
    private function getEndTimeInput($startDate, $startTime, $endDate) {
        $validator = new DateValidator();
        $validator->setComparedDate($startDate);
        $validator->setComparedTime($startTime);
        $validator->setDate($endDate);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setIsTime(true);
        $validator->setMessage('Must be equal or later than start time.', DateValidator::NOT_LATER);
        $input = new Input(Training::END_T);
        $input->getValidatorChain()
                ->addValidator($validator);
        return $input;
    }
    
    private function getStartTimeInput($startDate) {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setComparedTime($todayTime);
        $validator->setDate($startDate);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setIsTime(true);
        $validator->setMessage('Must be equal or later than current time.', DateValidator::NOT_LATER);
        $input = new Input(Training::START_T);
        $input->getValidatorChain()
                ->addValidator($validator);
        return $input;
    }
}
