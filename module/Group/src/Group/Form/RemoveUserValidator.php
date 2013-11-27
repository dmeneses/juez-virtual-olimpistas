<?php

namespace Group\Form;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Validator when a trainings removes an element.
 */
class RemoveUserValidator extends AbstractValidator {
    /**
     * Error codes
     * @const string
     */

    const NOT_EXIST = 'notExist';

    /**
     * Error messages
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_EXIST => "No esta en el grupo",
    );
    protected $dbAdapter;
    protected $groupID;

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getDbAdapter() {
        return $this->dbAdapter;
    }

    public function setGroupID($trainingID) {
        $this->groupID = $trainingID;
    }

    public function getGroupID() {
        return $this->groupID;
    }

    public function isValid($value) {
        $this->setValue((string) $value);

        if (!$this->isAdded($value, $this->groupID)) {
            $this->error(self::NOT_EXIST);
            return false;
        }

        return true;
    }

    function isAdded($email, $groupID) {
        $subSelect = new Select();
        $subSelect->columns(array('user_id'))
                ->from('user')
                ->where->equalTo('email', $email);

        $select = new Select();
        $select->from('user_has_group')
                ->where->equalTo('user_user_id', $subSelect)
                ->where->equalTo('group_group_id', $groupID);

        $sql = new Sql($this->getDbAdapter());
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        if ($result->current()) {
            return true;
        } else {
            return false;
        }
    }
}

?>
