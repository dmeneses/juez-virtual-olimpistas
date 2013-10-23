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
            'author' => 'Some author',
            'problem_description' => 'Some nice description',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_limit' => 6,
            'memory_limit' => 6,
            'source_limit' => 6,
            'fileIn' => array('tmp_name' => 'in'),
            'fileOut' => array('tmp_name' => 'out'),
        );

        $problem = new Problem();
        $problem->exchangeArray($data);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array($problem));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('id' => 1))
                ->will($this->returnValue($resultSet));

        $albumTable = new ProblemTable($mockTableGateway);

        $this->assertSame($problem, $albumTable->getProblem(1));
    }

    public function testSaveProblemWillInsertNewProblemsIfTheyDontAlreadyHaveAnId() {
        $simpleData = array(
            'problem_name' => 'Some problem',
            'author' => 'Some author',
            'problem_description' => 'Some nice description',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_limit' => 6,
            'memory_limit' => 6,
            'source_limit' => 6,
            'fileIn' => 'in',
            'fileOut' => 'out',
        );
        
        $dataWithoutID = array(
            'problem_name' => 'Some problem',
            'author' => 'Some author',
            'problem_description' => 'Some nice description',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_limit' => 6,
            'memory_limit' => 6,
            'source_limit' => 6,
            'fileIn' => array('tmp_name' => 'in'),
            'fileOut' => array('tmp_name' => 'out'),
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
            'author' => 'Some author',
            'problem_description' => 'Some nice description',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_limit' => 6,
            'memory_limit' => 6,
            'source_limit' => 6,
            'fileIn' => array('tmp_name' => 'in'),
            'fileOut' => array('tmp_name' => 'out'),
        );

        $problem = new Problem();
        $problem->exchangeArray($data);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array($problem));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('id' => 1))
                ->will($this->returnValue($resultSet));

        $dataWithoutID = array(
            'problem_name' => 'Some problem',
            'author' => 'Some author',
            'problem_description' => 'Some nice description',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_limit' => 6,
            'memory_limit' => 6,
            'source_limit' => 6,
            'fileIn' => 'in',
            'fileOut' => 'out',
        );

        $mockTableGateway->expects($this->once())
                ->method('update')
                ->with($dataWithoutID, array('id' => 1));

        $problemTable = new ProblemTable($mockTableGateway);
        $problemTable->saveProblem($problem);
    }

    public function testExceptionIsThrownWhenGettingNonExistentProblem() {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('id' => 1))
                ->will($this->returnValue($resultSet));

        $problemTable = new ProblemTable($mockTableGateway);

        try {
            $problemTable->getProblem(1);
        } catch (\Exception $e) {
            $this->assertSame('Could not find row 1', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }

}
