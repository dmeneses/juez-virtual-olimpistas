<?php

namespace Training\Model;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;
use Zend\Validator\Db\NoRecordExists;

/**
 * Training that will allow user to resolve and compete as a group.
 *
 * @author Daniela Meneses
 */
class Training implements InputFilterAwareInterface {

    const ID = 'training_id';
    const NAME = 'training_name';
    const START = 'start_date';
    const END = 'end_date';
    const START_T = 'start_time';
    const END_T = 'end_time';
    const OWNER = 'training_owner';

    public $training_id;
    public $training_name;
    public $start_date;
    public $end_date;
    public $start_time;
    public $end_time;
    public $training_owner;
    public $inputFilter;
    protected $dbAdapter;

    public function exchangeArray($data) {
        $this->training_id = (!empty($data[self::ID])) ? $data[self::ID] : null;
        $this->training_name = (!empty($data[self::NAME])) ? $data[self::NAME] : null;
        $this->start_date = (!empty($data[self::START])) ? $data[self::START] : null;
        $this->end_date = (!empty($data[self::END])) ? $data[self::END] : null;
        $this->start_time = (!empty($data[self::START_T])) ? $data[self::START_T] : null;
        $this->end_time = (!empty($data[self::END_T])) ? $data[self::END_T] : null;
        $this->training_owner = 1;
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $lengthValidator = new StringLength(array('min' => 3, 'max' => 50));

            $dbValidator = new NoRecordExists(array(
                'table' => 'training',
                'field' => 'training_name',
                'adapter' => $this->dbAdapter,
            ));
            $dbValidator->setMessage("El entrenamiento ya existe.", NoRecordExists::ERROR_RECORD_FOUND);
            $nameInput = new Input(self::NAME);
            $nameInput->getValidatorChain()
                    ->addValidator($lengthValidator)
                    ->addValidator($dbValidator);
            $nameInput->getFilterChain()
                    ->attachByName('stringtrim');

            $todayDate = date("Y-m-d");
            $todayTime = date("G:i");

            $dateValidator = new DateValidator();
            $dateValidator->setCompare(true);
            $dateValidator->setToken($todayDate);
            $dateValidator->setMessage(
                    'Must be equal or later than today.', DateValidator::NOT_LATER
            );
            $startDateInput = new Input(self::START);
            $startDateInput->getValidatorChain()
                    ->addValidator($dateValidator);


            $timeValidator = new DateValidator();
            $timeValidator->setCompare(true);
            $timeValidator->setToken($todayTime);
            $timeValidator->setMessage(
                    'Must be equal or later than current time.', DateValidator::NOT_LATER
            );
            $startTimeInput = new Input(self::START_T);
            $startTimeInput->getValidatorChain()
                    ->addValidator($timeValidator);



            $inputFilter->add($nameInput);
            $inputFilter->add($startDateInput);
            $inputFilter->add($startTimeInput);
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}

?>
