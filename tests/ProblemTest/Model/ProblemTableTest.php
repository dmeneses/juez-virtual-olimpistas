<?php

namespace ProblemTest\Model;

use Problem\Model\Problem;
use Problem\Model\ProblemTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests for problem table manager.
 *
 * @author Daniela Meneses
 */
class ProblemTableTest extends PHPUnit_Framework_TestCase {

    const SIMPLE = 1;
    const AFTER_SAVE = 2;
    const BEFORE_SAVE = 3;

    private $dataBeforeSave = array(
        'problem_name' => 'Some problem',
        'problem_author' => 'Some author',
        'problem_description' => 'Some nice description',
        'compare_type' => 'STRICT',
        'is_simple' => 'TRUE',
        'time_constraint' => 6,
        'memory_constraint' => 6,
        'source_constraint' => 6,
        'problem_creator' => 1,
        'avoid_symbol' => NULL,
    );
    
    private $dataAfterSave = array(
        'problem_name' => 'Some problem',
        'problem_author' => 'Some author',
        'compare_type' => 'STRICT',
        'is_simple' => 'TRUE',
        'time_constraint' => 6,
        'memory_constraint' => 6,
        'source_constraint' => 6,
        'problem_creator' => 1,
        'avoid_symbol' => NULL,
    );

    private function getProblem($problemType) {
        $problem = new Problem();
        switch ($problemType) {
            case self::SIMPLE: return $problem;
            case self::BEFORE_SAVE:
                $problem->exchangeArray($this->dataBeforeSave);
                return $problem;
            case self::AFTER_SAVE:
                $problem->exchangeArray($this->dataAfterSave);
                return $problem;
            default: return NULL;
        }
    }

    public function testCanRetrieveAnProblemByItsId() {
        $problem = $this->getProblem(self::BEFORE_SAVE);
        $resultSet = new ResultSet($this->getProblem(self::SIMPLE));
        $resultSet->initialize(array($problem));
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')->with(array('problem_id' => 1))
                ->will($this->returnValue($resultSet));
        $problemTable = new ProblemTable($mockTableGateway);
        $this->assertSame($problem, $problemTable->getProblem(1));
    }

    public function testSaveProblemWillInsertNewProblemsIfTheyDontAlreadyHaveAnId() {
        $problem = $this->getProblem(self::BEFORE_SAVE);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('insert')
                ->with($this->dataAfterSave);

        $albumTable = new ProblemTable($mockTableGateway);
        $albumTable->saveProblem($problem);
    }

    public function testSaveProblemWillUpdateExistingProblemsIfTheyAlreadyHaveAnId() {
        $data = $this->dataBeforeSave;
        $data['problem_id'] = 1;
        $problem = new Problem();
        $problem->exchangeArray($data);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Problem());
        $resultSet->initialize(array($problem));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')->with(array('problem_id' => 1))
                ->will($this->returnValue($resultSet));

        $mockTableGateway->expects($this->once())
                ->method('update')
                ->with($this->dataAfterSave, array('problem_id' => 1));

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
