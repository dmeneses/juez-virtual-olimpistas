<?php

namespace Group\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Group\Form\AddUserValidator;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

/**
 * Form to edit a group.
 *
 * @author Daniela Meneses
 */
class EditGroupForm extends Form {

    private $dbAdapter;

    public function __construct($name = null) {
        parent::__construct('edit_group');

        $groupId = new Element\Hidden('group_id');

        $groupName = new Element\Email('user_email');
        $groupName->setAttribute('placeholder', 'Usuario a agregar...');

        $submit = new Element\Submit('add_user');
        $submit->setValue('Añadir');
        $submit->setAttribute('class', 'button');

        $this->add($groupId);
        $this->add($groupName);
        $this->add($submit);
    }

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function isValid() {

        $inputFilter = new InputFilter();

        $groupID = $this->get('group_id')->getValue();
        $userValidator = new AddUserValidator();
        $userValidator->setDbAdapter($this->dbAdapter);
        $userValidator->setGroupID($groupID);

        $newUser = new Input('user_email');
        $newUser->getValidatorChain()
                ->addValidator($userValidator);
        $newUser->getFilterChain()
                ->attachByName('stringtrim');

        $inputFilter->add($newUser);
        $this->setInputFilter($inputFilter);

        return parent::isValid();
    }

}
