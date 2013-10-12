<?php

namespace Group\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
    
class TrainingTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getTraining($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTraining(Training $training) {
        $data = array(
            'training_name' => $training->training_name,
        );

        $id = (int) $training->training_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTraining($id)) {
                $this->tableGateway->update($data, array('id' => $id));
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
