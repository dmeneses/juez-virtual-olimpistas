<?php

namespace Group\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Group\Form\AddUserValidator;
use Group\Form\RemoveUserValidator;

/**
 * Form to edit a group.
 *
 * @author Daniela Meneses
 */
class EditGroupForm extends Form {

    private $dbAdapter;
    private $groupID;
    private $addValidation = true;

    public function __construct($groupID, $name = null) {
        parent::__construct('edit_group');
        $this->groupID = $groupID;
        $groupName = new Element\Email('user_email');
        $groupName->setAttribute('placeholder', 'Usuario a agregar...');

        $submit = new Element\Submit('add_user');
        $submit->setValue('Añadir');
        $submit->setAttribute('class', 'button');

        $submit2 = new Element\Submit('remove_user');
        $submit2->setValue('Borrar');
        $submit2->setAttribute('class', 'button');

        $this->add($groupName);
        $this->add($submit);
        $this->add($submit2);
    }

    public function setAddValidation($addValidation) {
        $this->addValidation = $addValidation;
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {

        $inputFilter = new InputFilter();
        if ($this->addValidation) {
            $userValidator = new AddUserValidator();
            $userValidator->setDbAdapter($this->dbAdapter);
            $userValidator->setGroupID($this->groupID);

            $newUser = new Input('user_email');
            $newUser->getValidatorChain()
                    ->addValidator($userValidator);
            $newUser->getFilterChain()
                    ->attachByName('stringtrim');

            $inputFilter->add($newUser);
        } else {
            $userValidator = new RemoveUserValidator();
            $userValidator->setDbAdapter($this->dbAdapter);
            $userValidator->setGroupID($this->groupID);

            $userToRemove = new Input('user_email');
            $userToRemove->getValidatorChain()
                    ->addValidator($userValidator);
            $userToRemove->getFilterChain()
                    ->attachByName('stringtrim');

            $inputFilter->add($userToRemove);
        }
        $this->setInputFilter($inputFilter);

        return parent::isValid();
    }
}
