<?php

namespace Training\Form;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Validator when a trainings removes an element.
 */
class RemoveValidator extends AbstractValidator {

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
        self::NOT_EXIST => "No esta en el entrenamiento",
    );
    protected $dbAdapter;
    protected $trainingID;
    protected $elementType;

    public function setDbAdapter($dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getDbAdapter() {
        return $this->dbAdapter;
    }

    public function getElementType() {
        return $this->elementType;
    }

    public function setElementType($elementType) {
        $this->elementType = $elementType;
    }

    public function setTrainingID($trainingID) {
        $this->trainingID = $trainingID;
    }

    public function getTrainingID() {
        return $this->trainingID;
    }

    public function isValid($value) {
        $this->setValue((string) $value);

        if (!$this->isAdded($value, $this->trainingID)) {
            $this->error(self::NOT_EXIST);
            return false;
        }

        return true;
    }

    function isAdded($groupID, $trainingID) {
        $select = new Select();
        $select->from('training_has_' . $this->elementType)
        ->where->equalTo($this->elementType . '_' . $this->elementType . '_id', $groupID)
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
