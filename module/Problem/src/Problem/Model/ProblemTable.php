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
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProblem(Problem $problem) {
        $data = array(
            'author' => $problem->author,
            'problem_name' => $problem->problem_name,
            'problem_description' => $problem->problem_description,
            'time_limit' => $problem->time_limit,
            'memory_limit' => $problem->memory_limit,
            'source_limit' => $problem->source_limit,
            'is_simple' => $problem->is_simple,
            'compare_type' => $problem->compare_type,
            'fileIn' => $problem->fileIn,
            'fileOut' => $problem->fileOut,
        );

        $id = (int) $problem->problem_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProblem($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Problem id does not exist');
            }
        }
    }
}