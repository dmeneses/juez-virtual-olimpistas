<?php

namespace Group\Form;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Validator\Db\RecordExists;

class EditGroupFilter implements InputFilterAwareInterface {

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
            
            $dbValidator = new RecordExists(array(
                'table' => 'user',
                'field' => 'email',
                'adapter' => $this->dbAdapter,
            ));
            $dbValidator->setMessage("El usuario no existe.", 
                    RecordExists::ERROR_NO_RECORD_FOUND);
            
            $newUser = new Input('user_email');
            $newUser->getValidatorChain()                    
                    ->addValidator($dbValidator);
            $newUser->getFilterChain()
                    ->attachByName('stringtrim');

            $inputFilter->add($newUser);
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
