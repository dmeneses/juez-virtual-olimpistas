<?php

namespace Problem\Model;

use Zend\Db\TableGateway\TableGateway;

class TestCaseTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function get($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array(TestCase::ID => $id));
        $row = $rowset->current();

        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save(TestCase $test) {
        $data = array(
            TestCase::IN => $test->test_in,
            TestCase::OUT => $test->test_out,
            TestCase::POINTS => $test->test_points,
            TestCase::PROBLEM => $test->problem_id,
        );

        $id = (int) $test->test_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTestCase($id)) {
                $this->tableGateway->update($data, array('test_id' => $id));
            } else {
                throw new \Exception('Problem id does not exist');
            }
        }
    }

}
