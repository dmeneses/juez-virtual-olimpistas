<?php

namespace Problem\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

class ProblemTable {

    protected $tableGateway;
    private $testCaseTable;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function getTestCaseTable() {
        return $this->testCaseTable;
    }

    public function setTestCaseTable($testCaseTable) {
        $this->testCaseTable = $testCaseTable;
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
            'time_constraint' => $problem->time_constraint,
            'memory_constraint' => $problem->memory_constraint,
            'source_constraint' => $problem->source_constraint,
            'is_simple' => $problem->is_simple,
            'compare_type' => $problem->compare_type,
            'problem_creator' => $problem->problem_creator,
        );

        $id = (int) $problem->problem_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $problem->problem_id = $this->tableGateway->getLastInsertValue();
            for ($index = 0; $index < count($problem->tests); $index++) {
                $problem->tests[$index]->problem_id = $problem->problem_id;
                $this->testCaseTable->save($problem->tests[$index]);
            }
        } else {
            if ($this->getProblem($id)) {
                $this->tableGateway->update($data, array('problem_id' => $id));
            } else {
                throw new \Exception('Problem id does not exist');
            }
        }
    }

    public function getProblemsByTraining($trainingID) {
        $select = new Select;
        $select->from(array('p' => 'problem',))
                ->join(array('tp' => 'training_has_problem'), 'tp.problem_problem_id = p.problem_id', array())
                ->join(array('t' => 'training'), 't.training_id = tp.training_training_id ', array());
        $select->where(array('t.training_id' => $trainingID,));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());

        return $resultSet;
    }

    public function getProblemSolutions($id) {
        $sql = "SELECT s.*, u.name, u.lastname FROM solution s, user u WHERE problem_problem_id = $id 
                AND status='SUCCESS'
                AND solution_submitter = user_id AND (solution_submitter, grade) IN
                ( SELECT solution_submitter, MAX(grade) FROM solution GROUP BY solution_submitter)
                ORDER BY grade desc, solution_date desc";
        $resultSet = $this->getAdapter()->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        return $resultSet;
    }

    public function getAdapter() {
        return $this->tableGateway->getAdapter();
    }

    public function getTableGateway() {
        return $this->tableGateway;
    }

}
