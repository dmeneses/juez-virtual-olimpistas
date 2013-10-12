<?php

namespace Group\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of Training
 *
 * @author dann
 */
class Training implements InputFilterAwareInterface {

    public $training_id;
    public $training_name;
    public $creation_date;
    public $problem_id;
    
    public $inputFilter;
    
    public function exchangeArray($data) {
        $this->training_id = (!empty($data['training_id'])) ? $data['training_id'] : null;
        $this->training_name = (!empty($data['training_name'])) ? $data['training_name'] : null;
        $this->creation_date = (!empty($data['creation_date'])) ? $data['creation_date'] : null;
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
    }
    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }
}

?>
