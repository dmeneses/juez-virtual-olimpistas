<?php

namespace Training\Model;

use Zend\Validator\AbstractValidator;

class DateValidationType {
    /*
     * Date 1 later than Date 2.
     */

    const LATER = 1;
    /*
     * Date 1 earlier than Date 2.
     */
    const EARLIER = 2;
    /*
     * Date 1 same than Date 2.
     */
    const SAME = 3;

}

/**
 * Date validator to know when a date is after another.
 */
class DateValidator extends AbstractValidator {

    /**
     * Original token against which to validate
     * @var string
     */
    protected $comparedDate;
    protected $comparedTime;
    protected $compareType;
    protected $date;
    protected $isTime = false;

    /**
     * Error codes
     * @const string
     */
    const NOT_SAME = 'notSame';
    const MISSING_TOKEN = 'missingToken';
    const NOT_LATER = 'notLater';
    const NOT_EARLIER = 'notEarlier';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_SAME => "The date does not match the required",
        self::NOT_LATER => "The date is not later than the required",
        self::NOT_EARLIER => "The date is not earlier than required",
        self::MISSING_TOKEN => "No date was provided to match against",
    );

    public function getComparedDate() {
        return $this->comparedDate;
    }

    public function getComparedTime() {
        return $this->comparedTime;
    }

    public function getCompareType() {
        return $this->compareType;
    }

    public function setComparedDate($comparedDate) {
        $this->comparedDate = $comparedDate;
    }

    public function setComparedTime($comparedTime) {
        $this->comparedTime = $comparedTime;
    }

    public function setCompareType($compareType) {
        $this->compareType = $compareType;
    }

    public function getIsTime() {
        return $this->isTime;
    }

    public function setIsTime($isTime) {
        $this->isTime = $isTime;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * Validate two dates.
     * 
     * @param type $value
     * @return boolean
     */
    public function isValid($value) {
        $this->setValue((string) $value);

        if ($this->getComparedDate() === null) {
            $this->error(self::MISSING_TOKEN);
            return false;
        }

        if ($this->isTime && ($this->comparedTime === null || $this->date === null)) {
            $this->error(self::MISSING_TOKEN);
            return false;
        }

        if (!$this->isTime) {
            $date1 = strtotime($this->comparedDate);
            $date2 = strtotime($value);
        } else {
            $date1 = strtotime($this->comparedDate . ' ' . $this->comparedTime);
            $date2 = strtotime($this->date . ' ' . $value);
        }

        if ($this->getCompareType() === DateValidationType::SAME) {
            if ($date1 != $date2) {
                $this->error(self::NOT_SAME);
                return false;
            }
        }
        if ($this->getCompareType() === DateValidationType::LATER) {
            if ($date2 < $date1) {
                $this->error(self::NOT_LATER);
                return false;
            }
        }
        return true;
    }

}
