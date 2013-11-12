<?php

namespace SanAuth\Form;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Login form for user authentication.
 *
 * @author Daniela Meneses
 */
class LoginForm extends Form {

    function __construct($name = null) {
        parent::__construct('login');
        $email = new Element\Email('email');
        $email->setLabel('Email');
        $password = new Element\Password('password');
        $password->setLabel('Password');
        $remember = new Element\Checkbox('rememberMe');
        $remember->setLabel('Remember me?');
        $password->setLabel('Password');
        $submit = new Element\Submit('submit');
        $submit->setValue('Acceder');
        $submit->setAttribute('class', 'button');
        $this->add($email);
        $this->add($password);
        $this->add($remember);
        $this->add($submit);
    }

}
