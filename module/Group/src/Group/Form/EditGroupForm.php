<?php

namespace Group\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Form to edit a group.
 *
 * @author Daniela Meneses
 */
class EditGroupForm extends Form{
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
}
