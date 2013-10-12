<?php

namespace Problem\Model;

use Zend\Db\TableGateway\TableGateway;

class ProblemTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getProblem($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('problem_id' => $id));
        $row = $rowset->current();
        
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProblem(Problem $problem) {
        $data = array(
            'problem_author' => $problem->problem_author,
            'problem_name' => $problem->problem_name,
            'problem_description' => $problem->problem_description,
            'time_constraint' => $problem->time_constraint,
            'memory_constraint' => $problem->memory_constraint,
            'source_constraint' => $problem->source_constraint,
            'is_simple' => $problem->is_simple,
            'compare_type' => $problem->compare_type,
            'file_in' => $problem->file_in,
            'file_out' => $problem->file_out,
            'user_user_id' => 1,
        );

        $id = (int) $problem->problem_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProblem($id)) {
                $this->tableGateway->update($data, array('problem_id' => $id));
            } else {
                throw new \Exception('Problem id does not exist');
            }
        }
    }
}