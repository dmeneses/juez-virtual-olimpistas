<?php

namespace Training\Model;

/**
 * Training that will allow user to resolve and compete as a group.
 *
 * @author Daniela Meneses
 */
class Training {

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
        $this->training_owner = (!empty($data[self::OWNER])) ? $data[self::OWNER] : null;        
    }
}
