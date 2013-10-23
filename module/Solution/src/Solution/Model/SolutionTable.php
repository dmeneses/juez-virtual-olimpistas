<?php

namespace Solution\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class SolutionTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function get($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('solution_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSolution(Solution $solution) {
        $data = array(
            'solution_language' => $solution->solution_language,
            'solution_source_file' => $solution->solution_source_file,
            'problem_problem_id' => $solution->problem_id,
            'user_user_id' => 1,
        );

        $id = (int) $solution->solution_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $solution->solution_id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Solution id does not exist');
            }
        }
    }

    public function getLast20Solution() {
        $select = new Select;
        $select->from('solution');
        $select->order(array('solution_id DESC')); 
        $select->limit(20);
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());       
        return $resultSet;
    }

}
