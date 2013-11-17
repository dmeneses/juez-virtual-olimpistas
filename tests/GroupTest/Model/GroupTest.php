<?php

namespace GroupTest\Model;

use Group\Model\Group;
use PHPUnit_Framework_TestCase;

/**
 * Test for group model
 *
 * @group_author Daniela Meneses
 */
class GroupTest extends PHPUnit_Framework_TestCase {

    public function testGroupInitialState() {
        $group = new Group();

        $this->assertNull($group->group_id, '"id" should initially be null');
        $this->assertNull($group->group_name, '"name" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly() {
        $group = new Group();
        $data = array('group_id' => 1,
            'group_name' => 'Some group',
        );

        $group->exchangeArray($data);

        $this->assertSame($data['group_name'], $group->group_name, '"name" was not set correctly');
        $this->assertSame($data['group_id'], $group->group_id, '"id" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent() {
        $group = new Group();
        $data = array('group_id' => 1,
            'group_name' => 'Some group',
        );

        $group->exchangeArray($data);
        $group->exchangeArray(array());

        $this->assertNull($group->group_name, '"name" should have defaulted to null');
        $this->assertNull($group->group_id, '"id" should have defaulted to null');
    }
    public function testExchangeNameGroupStringNull(){
        $group = new Group();
        $data = array('group_id' => 1,
            'group_name' => " ",
        );
        $group->exchangeArray($data);
        $group->exchangeArray(array());
        
        $this->assertNull($group->group_name, '"name" should have defaulted to null');
        $this->assertNull($group->group_id, '"id" should have defaulted to null');
    }
}

?>
