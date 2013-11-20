<?php

namespace Group\Form;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\StringLength;

class CreateGroupFilter implements InputFilterAwareInterface {

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
            
            $lengthValidator = new StringLength(array('min' => 3, 'max' => 50));
            $dbValidator = new NoRecordExists(array(
                'table' => 'group',
                'field' => 'group_name',
                'adapter' => $this->dbAdapter,
            ));
            $dbValidator->setMessage("El grupo ya existe.", NoRecordExists::ERROR_RECORD_FOUND);
            
            $nameInput = new Input('group_name');
            $nameInput->getValidatorChain()
                    ->addValidator($lengthValidator)
                    ->addValidator($dbValidator);
            $nameInput->getFilterChain()
                    ->attachByName('stringtrim');

            $inputFilter->add($nameInput);
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
