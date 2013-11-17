<?php

namespace ProblemTest\Model;

use Problem\Model\Problem;
use PHPUnit_Framework_TestCase;

/**
 * Test for Problem model
 *
 * @problem_author Daniela Meneses
 */
class ProblemTest extends PHPUnit_Framework_TestCase {

    public function testProblemInitialState() {
        $problem = new Problem();

        $this->assertNull($problem->problem_id, '"id" should initially be null');
        $this->assertNull($problem->problem_name, '"name" should initially be null');
        $this->assertNull($problem->problem_author, '"autor" should initially be null');
        $this->assertNull($problem->compare_type, '"compare type" should initially be null');
        $this->assertNull($problem->time_constraint, '"time limit" should initially be null');
        $this->assertNull($problem->memory_constraint, '"memory limit" should initially be null');
        $this->assertNull($problem->source_constraint, '"source limit" should initially be null');
        $this->assertNull($problem->is_simple, '"is simple" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly() {
        $problem = new Problem();
        $data = array('problem_id' => 1,
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
        );

        $problem->exchangeArray($data);

        $this->assertSame($data['problem_name'], $problem->problem_name, '"name" was not set correctly');
        $this->assertSame($data['problem_id'], $problem->problem_id, '"id" was not set correctly');
        $this->assertSame($data['problem_author'], $problem->problem_author, '"problem_author" was not set correctly');
        $this->assertSame($data['compare_type'], $problem->compare_type, '"compare_type" was not set correctly');
        $this->assertSame($data['is_simple'], $problem->is_simple, '"is_simple" was not set correctly');
        $this->assertSame($data['time_constraint'], $problem->time_constraint, '"time_constraint" was not set correctly');
        $this->assertSame($data['memory_constraint'], $problem->memory_constraint, '"memory_constraint" was not set correctly');
        $this->assertSame($data['source_constraint'], $problem->source_constraint, '"source_constraint" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent() {
        $problem = new Problem();
        $data = array('problem_id' => 1,
            'problem_name' => 'Some problem',
            'problem_author' => 'Some author',
            'compare_type' => 'STRICT',
            'is_simple' => 'TRUE',
            'time_constraint' => 6,
            'memory_constraint' => 6,
            'source_constraint' => 6,
        );

        $problem->exchangeArray($data);
        $problem->exchangeArray(array());

        $this->assertNull($problem->problem_name, '"name" should have defaulted to null');
        $this->assertNull($problem->problem_id, '"id" should have defaulted to null');
        $this->assertNull($problem->problem_author, '"author" should have defaulted to null');
        $this->assertNull($problem->compare_type, '"compare_type" should have defaulted to null');
        $this->assertNull($problem->is_simple, '"is_simple" should have defaulted to null');
        $this->assertNull($problem->time_constraint, '"time_constraint" should have defaulted to null');
        $this->assertNull($problem->memory_constraint, '"memory_constraint" should have defaulted to null');
        $this->assertNull($problem->source_constraint, '"source_constraint" should have defaulted to null');
    }
}

?>
