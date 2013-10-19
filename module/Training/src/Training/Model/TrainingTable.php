<?php

namespace Training\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class TrainingTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function getDbAdapter() {
        return $this->tableGateway->getAdapter();
    }

    public function fetchAll() {
        $select = new Select;
        $select->columns(array('training_id', 'training_name', 'start_date',
            'start_time', 'end_date', 'end_time'));
        $select->from(array('t' => 'training',))
                ->join(array('u' => 'user'), 'u.user_id = t.training_owner', 
                        array('name', 'lastname'));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());

        return $resultSet;
    }

    public function get($trainingID) {
        $trainingID = (int) $trainingID;
        $rowset = $this->tableGateway->select(array('training_id' => $trainingID));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $trainingID");
        }
        return $row;
    }

    public function save(Training $training) {
        $data = array(
            'training_name' => $training->training_name,
            'start_date' => $training->start_date,
            'end_date' => $training->end_date,
            'start_time' => $training->start_time,
            'end_time' => $training->end_time,
            'training_owner' => $training->training_owner,
        );

        $id = (int) $training->training_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTraining($id)) {
                $this->tableGateway->update($data, array('training_id' => $id));
            } else {
                throw new \Exception('Training id does not exist');
            }
        }
    }

    public function addProblem(Training $training) {
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = new Sql($dbAdapter);

        $insert = $sql->insert('training_has_problem');
        $insert->values(array(
            'training_training_id' => $training->training_id,
            'problem_problem_id' => $training->problem_id,
        ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

}
