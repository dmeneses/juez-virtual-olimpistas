<?php

namespace Training\Form;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Validator when a training adds a problem.
 */
class AddProblemValidator extends AbstractValidator {

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
        self::NOT_EXIST => "Problema no existe",
        self::ALREADY_ADDED => "Problema ya añadido",
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

    function notExist($problemID) {
        $dbValidator = new NoRecordExists(array(
            'table' => 'problem',
            'field' => 'problem_id',
            'adapter' => $this->dbAdapter,
        ));

        return $dbValidator->isValid($problemID);
    }

    function isAdded($problemID, $trainingID) {
        $select = new Select();
        $select->from('training_has_problem')
                ->where->equalTo('problem_problem_id', $problemID)
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
