<?php

namespace ProblemTest\Model;

use Problem\Model\Problem;
use PHPUnit_Framework_TestCase;

/**
 * Test for Problem model
 *
 * @author Daniela Meneses
 */
class ProblemTest extends PHPUnit_Framework_TestCase {

    public function testAlbumInitialState() {
        $problem = new Problem();

        $this->assertNull($problem->problem_id, '"id" should initially be null');
        $this->assertNull($problem->problem_name, '"name" should initially be null');
        $this->assertNull($problem->author, '"autor" should initially be null');
        $this->assertNull($problem->problem_description, '"title" should initially be null');
        $this->assertNull($problem->compare_type, '"compare type" should initially be null');
        $this->assertNull($problem->time_limit, '"time limit" should initially be null');
        $this->assertNull($problem->memory_limit, '"memory limit" should initially be null');
        $this->assertNull($problem->source_limit, '"source limit" should initially be null');
        $this->assertNull($problem->is_simple, '"is simple" should initially be null');
        $this->assertNull($problem->fileIn, '"file in" should initially be null');
        $this->assertNull($problem->fileOut, '"file out" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly() {
        $problem = new Problem();
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

        $problem->exchangeArray($data);

        $this->assertSame($data['problem_name'], $problem->problem_name, '"name" was not set correctly');
        $this->assertSame($data['problem_id'], $problem->problem_id, '"id" was not set correctly');
        $this->assertSame($data['author'], $problem->author, '"author" was not set correctly');
        $this->assertSame($data['problem_description'], $problem->problem_description, '"description" was not set correctly');
        $this->assertSame($data['compare_type'], $problem->compare_type, '"compare_type" was not set correctly');
        $this->assertSame($data['is_simple'], $problem->is_simple, '"is_simple" was not set correctly');
        $this->assertSame($data['time_limit'], $problem->time_limit, '"time_limit" was not set correctly');
        $this->assertSame($data['memory_limit'], $problem->memory_limit, '"memory_limit" was not set correctly');
        $this->assertSame($data['source_limit'], $problem->source_limit, '"source_limit" was not set correctly');
        $this->assertSame($data['fileIn']['tmp_name'], $problem->fileIn, '"fileIn" was not set correctly');
        $this->assertSame($data['fileOut']['tmp_name'], $problem->fileOut, '"fileOut" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent() {
        $problem = new Problem();
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

        $problem->exchangeArray($data);
        $problem->exchangeArray(array());

        $this->assertNull($problem->problem_name, '"name" should have defaulted to null');
        $this->assertNull($problem->problem_id, '"id" should have defaulted to null');
        $this->assertNull($problem->author, '"author" should have defaulted to null');
        $this->assertNull($problem->problem_description, '"description" should have defaulted to null');
        $this->assertNull($problem->compare_type, '"compare_type" should have defaulted to null');
        $this->assertNull($problem->is_simple, '"is_simple" should have defaulted to null');
        $this->assertNull($problem->time_limit, '"time_limit" should have defaulted to null');
        $this->assertNull($problem->memory_limit, '"memory_limit" should have defaulted to null');
        $this->assertNull($problem->source_limit, '"source_limit" should have defaulted to null');
        $this->assertNull($problem->fileIn, '"fileIn" should have defaulted to null');
        $this->assertNull($problem->fileOut, '"fileOut" should have defaulted to null');
    }
}

?>
