<?php

namespace Training\Form;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Validator when a trainings add a element.
 */
class AddValidator extends AbstractValidator {
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
            'table' => $this->elementType,
            'field' => $this->elementType . '_id',
            'adapter' => $this->dbAdapter,
        ));

        return $dbValidator->isValid($groupID);
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
