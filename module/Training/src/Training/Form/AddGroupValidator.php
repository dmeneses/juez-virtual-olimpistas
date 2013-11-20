<?php

namespace Training\Form;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Validator when a training adds a group.
 */
class AddGroupValidator extends AbstractValidator {

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
        self::NOT_EXIST => "Grupo no existe",
        self::ALREADY_ADDED => "Grupo ya añadido",
    );
    protected $dbAdapter;
    protected $trainingID;

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getDbAdapter() {
        return $this->dbAdapter;
    }

    public function setTrainingID($trainingID) {
        $this->trainingID = $trainingID;
    }

    public function getTrainingID() {
        return $this->trainingID;
    }

    public function isValid($value) {
        $this->setValue((string) $value);

        if ($this->notExist($value)) {
            $this->error(self::NOT_EXIST);
            return false;
        }

        if ($this->isAdded($value, $this->trainingID)) {
            $this->error(self::ALREADY_ADDED);
            return false;
        }

        return true;
    }

    function notExist($groupID) {
        $dbValidator = new NoRecordExists(array(
            'table' => 'group',
            'field' => 'group_id',
            'adapter' => $this->dbAdapter,
        ));

        return $dbValidator->isValid($groupID);
    }

    function isAdded($groupID, $trainingID) {
        $select = new Select();
        $select->from('training_has_group')
                ->where->equalTo('group_group_id', $groupID)
                ->where->equalTo('training_training_id', $trainingID);

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
