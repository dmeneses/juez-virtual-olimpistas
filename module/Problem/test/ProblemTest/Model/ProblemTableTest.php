<?php

namespace ProblemTest\Model;

use Problem\Model\Problem;
use Problem\Model\ProblemTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests form problem table manager.
 *
 * @author Daniela Meneses
 */
class ProblemTableTest extends PHPUnit_Framework_TestCase {

    public function testFetchAllReturnsAllProblems() {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with()
                ->will($this->returnValue($resultSet));

        $problemTable = new ProblemTable($mockTableGateway);

        $this->assertSame($resultSet, $problemTable->fetchAll());
    }

    public function testCanRetrieveAnProblemByItsId() {
        $data = array('problem_id' => 1,
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'problem_description' => 'Some nice description',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
            'file_in' => array('tmp_name' => 'in'),
            'file_out' => array('tmp_name' => 'out'),
        );

        $problem = new Problem();
        $problem->exchangeArray($data);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array($problem));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('problem_id' => 1))
                ->will($this->returnValue($resultSet));

        $albumTable = new ProblemTable($mockTableGateway);

        $this->assertSame($problem, $albumTable->getProblem(1));
    }

    public function testSaveProblemWillInsertNewProblemsIfTheyDontAlreadyHaveAnId() {
        $simpleData = array(
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
            'file_in' => 'in',
            'file_out' => 'out', 'user_user_id' => 1,
        );

        $dataWithoutID = array(
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
            'file_in' => array('tmp_name' => 'in'),
            'file_out' => array('tmp_name' => 'out'),
        );


        $problem = new Problem();
        $problem->exchangeArray($dataWithoutID);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('insert')
                ->with($simpleData);

        $albumTable = new ProblemTable($mockTableGateway);
        $albumTable->saveProblem($problem);
    }

    public function testSaveProblemWillUpdateExistingProblemsIfTheyAlreadyHaveAnId() {
        $data = array('problem_id' => 1,
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
            'file_in' => array('tmp_name' => 'in'),
            'file_out' => array('tmp_name' => 'out'),
            'user_user_id' => 1,
        );

        $problem = new Problem();
        $problem->exchangeArray($data);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array($problem));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('problem_id' => 1))
                ->will($this->returnValue($resultSet));

        $dataWithoutID = array(
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
            'file_in' => 'in',
            'file_out' => 'out', 
            'user_user_id' => 1,
        );

        $mockTableGateway->expects($this->once())
                ->method('update')
                ->with($dataWithoutID, array('problem_id' => 1));

        $problemTable = new ProblemTable($mockTableGateway);
        $problemTable->saveProblem($problem);
    }

    /**
     * @expectedException \Exception
     */
    public function testExceptionIsThrownWhenGettingNonExistentProblem() {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('problem_id' => 1))
                ->will($this->returnValue($resultSet));

        $problemTable = new ProblemTable($mockTableGateway);
        $problemTable->getProblem(1);
    }
}
