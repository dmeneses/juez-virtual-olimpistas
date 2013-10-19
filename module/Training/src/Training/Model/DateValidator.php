<?php

namespace Training\Model;

use Zend\Validator\AbstractValidator;

/**
 * Date validator to know when a date is after another.
 */
class DateValidator extends AbstractValidator {

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
        self::MISSING_TOKEN => 'No date was provided to match against',
    );

    /**
     * Original token against which to validate
     * @var string
     */
    protected $_token;
    protected $_compare;

    /**
     * Set date against which to compare.
     *
     * @param  mixed $token
     */
    public function setToken($token) {
        $this->_token = $token;
    }

    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken() {
        return $this->_token;
    }

    /**
     * Set how to compare
     *
     * @param  mixed $compare
     */
    public function setCompare($compare) {
        $this->_compare = $compare;
    }

    /**
     * Retrieve compare
     *
     * @return string
     */
    public function getCompare() {
        return $this->_compare;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a token has been set and the provided value
     * matches that token.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value) {        
        $this->setValue((string) $value);
        $token = $this->getToken();

        if ($token === null) {
            $this->error(self::MISSING_TOKEN);
            return false;
        }

        $date1 = strtotime($value);
        $date2 = strtotime($token);


        if ($this->getCompare() === true) {
            if ($date1 < $date2) {
                $this->error(self::NOT_LATER);
                return false;
            }
        } 
        
        if ($this->getCompare() === false) {
            if ($date1 > $date2) {
                $this->error(self::NOT_EARLIER);
                return false;
            }
        } 
        
        if ($this->getCompare() === null) {
            if ($date1 == $date2) {
                $this->error(self::NOT_SAME);
                return false;
            }
        }

        // Date is valid
        return true;
    }

}
