<?php

namespace GroupTest\Model;

use Group\Model\Group;
use Group\Model\GroupTable;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests for group table manager.
 *
 * @author Daniela Meneses
 */
class GroupTableTest extends PHPUnit_Framework_TestCase {

    const SIMPLE = 1;
    const AFTER_SAVE = 2;
    const BEFORE_SAVE = 3;

    private $data = array(
        'group_name' => 'Some group',
        'group_owner' => 1,
    );

    public function testSaveGroupWillInsertNewGroupsIfTheyDontAlreadyHaveAnId() {
        $group = new Group();
        $group->exchangeArray($this->data);
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('insert')
                ->with($this->data);

        $albumTable = new GroupTable($mockTableGateway);
        $albumTable->save($group);
    }

    public function testSaveGroupWillUpdateExistinGroupsIfTheyAlreadyHaveAnId() {
        $groupData = $this->data;
        $groupData['group_id'] = 1;
        $group = new Group();
        $group->exchangeArray($groupData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Group());
        $resultSet->initialize(array($group));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                ->method('select')
                ->with(array('group_id' => 1))
                ->will($this->returnValue($resultSet));

        $mockTableGateway->expects($this->once())
                ->method('update')
                ->with($this->data, array('group_id' => 1));

        $problemTable = new GroupTable($mockTableGateway);
        $problemTable->save($group);
    }

}
