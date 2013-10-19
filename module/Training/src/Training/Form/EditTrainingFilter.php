<?php

namespace Training\Form;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Validator\Digits;
use Zend\Validator\Db\RecordExists;

class EditTrainingFilter implements InputFilterAwareInterface {

    /**
     * @var inputFilter
     */
    protected $inputFilter;

    /**
     * @var Database Adapter
     */
    protected $dbAdapter;

    /**
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    /**
     * @param \Zend\Db\Adapter $dbAdapter
     */
    public function __construct(Adapter $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * 
     * @return Zend\Db\Adapter
     */
    public function getDbAdapter() {
        return $this->dbAdapter;
    }

    /**
     * @return \Zend\InputFilter\InputFilter
     * 
     * Get the input filter (build it first)
     */
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            
            $numberValidator = new Digits();
            $dbValidator = new RecordExists(array(
                'table' => 'problem',
                'field' => 'problem_id',
                'adapter' => $this->dbAdapter,
            ));
            
            $newProblem = new Input('problem_id');
            $newProblem->getValidatorChain()                    
                    ->addValidator($numberValidator)
                    ->addValidator($dbValidator);
            $newProblem->getFilterChain()
                    ->attachByName('stringtrim');
            
            $inputFilter->add($newProblem);
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}

?>