<?php

namespace TrainingTest\Model;

use Training\Model\Training;
use Training\Model\TrainingTable;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests for Training table manager.
 *
 * @author Daniela Meneses
 */
class TrainingTableTest extends PHPUnit_Framework_TestCase {

    const SIMPLE = 1;
    const WITH_DATA = 2;
    const BEFORE_SAVE = 3;

    private $data = array(
        'training_name' => 'Some Training',
        'start_date' => '11/19/2013',
        'start_time' => '02:06 PM',
        'end_date' => '11/20/2013',
        'end_time' => '03:06 PM',
        'training_owner' => 1,
    );
    
    private function getTraining($TrainingType) {
        $Training = new Training();
        switch ($TrainingType) {
            case self::SIMPLE: return $Training;
            case self::WITH_DATA:
                $Training->exchangeArray($this->data);
                return $Training;
            default: return NULL;
        }
    }

    public function testCanRetrieveAnTrainingByItsId() {
        $training = $this->getTraining(self::WITH_DATA);
        $resultSet = new ResultSet($this->getTraining(self::SIMPLE));
        $resultSet->initialize(array($training));
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')->with(array('training_id' => 1))
                ->will($this->returnValue($resultSet));
        $TrainingTable = new TrainingTable($mockTableGateway);
        $this->assertSame($training, $TrainingTable->get(1));
    }

    public function testSaveTrainingWillInsertNewTrainingsIfTheyDontAlreadyHaveAnId() {
        $training = $this->getTraining(self::WITH_DATA);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('insert')
                ->with($this->data);

        $albumTable = new TrainingTable($mockTableGateway);
        $albumTable->save($training);
    }

    public function testSaveTrainingWillUpdateExistingTrainingsIfTheyAlreadyHaveAnId() {
        $data = $this->data;
        $data['training_id'] = 1;
        $training = new Training();
        $training->exchangeArray($data);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Training());
        $resultSet->initialize(array($training));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')->with(array('training_id' => 1))
                ->will($this->returnValue($resultSet));

        $mockTableGateway->expects($this->once())
                ->method('update')
                ->with($this->data, array('training_id' => 1));

        $TrainingTable = new TrainingTable($mockTableGateway);
        $TrainingTable->save($training);
    }

    /**
     * @expectedException \Exception
     */
    public function testExceptionIsThrownWhenGettingNonExistentTraining() {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Training());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('training_id' => 1))
                ->will($this->returnValue($resultSet));

        $TrainingTable = new TrainingTable($mockTableGateway);
        $TrainingTable->get(1);
    }
}
