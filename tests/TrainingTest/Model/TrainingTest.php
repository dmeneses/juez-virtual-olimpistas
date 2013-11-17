<?php

namespace TrainingTest\Model;

use PHPUnit_Framework_TestCase;
use Training\Model\Training;

/**
 * Test for Training model
 *
 * @Training_author Daniela Meneses
 */
class TrainingTest extends PHPUnit_Framework_TestCase {

    public function testTrainingInitialState() {
        $training = new Training();

        $this->assertNull($training->training_id, '"id" should initially be null');
        $this->assertNull($training->training_name, '"name" should initially be null');
        $this->assertNull($training->start_date, '"startDate" should initially be null');
        $this->assertNull($training->start_time, '"startTime" should initially be null');
        $this->assertNull($training->end_date, '"endDate" should initially be null');
        $this->assertNull($training->end_time, '"endTime" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly() {
        $Training = new Training();
        $data = array('training_id' => 1,
            'training_name' => 'Some Training',
            'start_date' => '11/19/2013',
            'start_time' => '02:06 PM',
            'end_date' => '11/20/2013',
            'end_time' => '03:06 PM',
        );

        $Training->exchangeArray($data);

        $this->assertSame($data['training_name'], $Training->training_name, '"name" was not set correctly');
        $this->assertSame($data['training_id'], $Training->training_id, '"id" was not set correctly');
        $this->assertSame($data['start_date'], $Training->start_date, '"start_date" was not set correctly');
        $this->assertSame($data['start_time'], $Training->start_time, '"start_time" was not set correctly');
        $this->assertSame($data['end_date'], $Training->end_date, '"end_date" was not set correctly');
        $this->assertSame($data['end_time'], $Training->end_time, '"end_time" was not set correctly');
       
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent() {
        $Training = new Training();
        $data = array('training_id' => 1,
            'training_name' => 'Some Training',
            'start_date' => '11/19/2013',
            'start_time' => '02:06 PM',
            'end_date' => '11/20/2013',
            'end_time' => '03:06 PM',
            
        );

        $Training->exchangeArray($data);
        $Training->exchangeArray(array());

        $this->assertNull($Training->training_name, '"name" should have defaulted to null');
        $this->assertNull($Training->training_id, '"id" should have defaulted to null');
        $this->assertNull($Training->start_date, '"start_date" should have defaulted to null');
        $this->assertNull($Training->start_time, '"start_time" should have defaulted to null');
        $this->assertNull($Training->end_date, '"end_date" should have defaulted to null');
        $this->assertNull($Training->end_time, '"end_time" should have defaulted to null');

    }
}

?>
