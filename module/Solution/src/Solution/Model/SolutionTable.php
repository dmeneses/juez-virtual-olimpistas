<?php

namespace Solution\Model;

use Zend\Db\TableGateway\TableGateway;

class SolutionTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getSolution($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
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
            if ($this->getSolution($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Solution id does not exist');
            }
        }
    }
}