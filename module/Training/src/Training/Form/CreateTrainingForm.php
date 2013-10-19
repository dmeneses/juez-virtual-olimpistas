<?php

namespace Training\Form;

use Zend\InputFilter\Input;
use Zend\Form\Form;
use Zend\Form\Element;
use Training\Model\DateValidator;

class CreateTrainingForm extends Form {

    public function __construct($name = null) {
        parent::__construct('createtraining');

        $trainingID = new Element\Hidden('training_id');

        $trainingName = new Element\Text('training_name');
        $trainingName->setAttribute('placeholder', 'Nombre del entrenamiento');

        $startDate = new Element\DateTime('start_date');
        $endDate = new Element\DateTime('end_date');

        $startTime = new Element\Time('start_time');
        $startTime->setAttributes(array(
            'min' => '00:00:00',
            'max' => '23:59:59',
            'step' => '60', // seconds; default step interval is 60 seconds
        ));
        $endTime = new Element\Time('end_time');
        $endTime->setAttributes(array(
            'min' => '00:00:00',
            'max' => '23:59:59',
            'step' => '60', // seconds; default step interval is 60 seconds
        ));

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

    public function isValid() {
        $startDate = $this->get('start_date')->getValue();
        $startTime = $this->get('start_time')->getValue();

        if (isset($startDate)) {
            $validator = new DateValidator();
            $validator->setToken($startDate);
            $validator->setCompare(true);
            $validator->setMessage(
                    'Must be equal or later than start date.', DateValidator::NOT_LATER
            );
            $input = new Input('end_date');
            $input->getValidatorChain()
                    ->addValidator($validator);
            $this->getInputFilter()->add($input);
        }

        if (isset($startTime)) {
            $validator = new DateValidator();
            $validator->setToken($startTime);
            $validator->setCompare(true);
            $validator->setMessage(
                    'Must be equal or later than start time.', DateValidator::NOT_LATER
            );
            $input = new Input('end_time');
            $input->getValidatorChain()
                    ->addValidator($validator);
            $this->getInputFilter()->add($input);
        }

        return parent::isValid();
    }

}
