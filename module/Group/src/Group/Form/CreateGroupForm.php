<?php

namespace Group\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Form to create a group.
 *
 * @author Daniela Meneses
 */
class CreateGroupForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('create_group');
        
        $groupId = new Element\Hidden('group_id');
        
        $groupName = new Element\Text('group_name');
        $groupName->setAttribute('placeholder', 'Nombre de grupo');

        $submit = new Element\Submit('save');
        $submit->setValue('Guardar');
        $submit->setAttribute('class', 'button');
        
        $this->add($groupId);
        $this->add($groupName);  
        $this->add($submit);  
    }
}
