<?php

namespace Group\Model;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\Db\RecordExists;

/**
 * Date validator to know when a date is after another.
 */
class AddUserValidator extends AbstractValidator {

    /**
     * Error codes
     * @const string
     */
    const NOT_EXIST = 'notExist';
    const ALREADY_ADDED = 'alreadyAdded';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_EXIST => "El usuario no existe",
        self::ALREADY_ADDED => "El usuario ya esta en el grupo",
    );

    protected $dbAdapter;
    protected $groupID;


    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getDbAdapter() {
        return $this->dbAdapter;
    }
    
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }

    public function getGroupID() {
        return $this->groupID;
    }

    public function isValid($value) {
        $this->setValue((string) $value);

        if (notExist($value)) {
            $this->error(self::NOT_EXIST);
            return false;
        }

        if (isAdded($value, $this->groupID)) {
            $this->error(self::ALREADY_ADDED);
            return false;
        }

        return true;
    }

    function notExist($email) {
        $dbValidator = new NoRecordExists(array(
            'table' => 'user',
            'field' => 'email',
            'adapter' => $this->dbAdapter,
        ));
        
        return $dbValidator->isValid($email);
    }
    
    function isAdded($email, $groupID) {
        $subSelect = new Select();
        $subSelect->columns(array('user_id'))
                ->from('user')
                ->where->equalTo('email', $email);

        $select = new Select();
        $select->from('user_has_group')
        ->where->equalTo('user_user_id', $subSelect);
        
        $dbValidator = new RecordExists($select);
        $dbValidator->setAdapter($this->dbAdapter);
        $dbValidator->setField('group_group_id');
        
        return $dbValidator->isValid($groupID);        
    }
}
