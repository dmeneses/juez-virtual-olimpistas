<?php

require_once '../monitor/Comparator.php';
/**
 * Description of ComparatorTest
 *
 * @author dann
 */
class ComparatorTest extends PHPUnit_Framework_TestCase{
    const COMPARE_IN = '../tests/test_data/compareIn';
    const COMPARE_EXPECTED = '../tests/test_data/compareOut';
    const COMPARE_IN2 = '../tests/test_data/compareIn2';
    const COMPARE_EXPECTED2 = '../tests/test_data/compareOut2';
    
    public function testCompareTwoFilesAvoidingLastComma() {
        $this->assertTrue(Comparator::compare(self::COMPARE_EXPECTED, self::COMPARE_IN, 'TOKEN', ','));
    }
     public function testCompareTwoFilesAvoidingLastSpace() {
        $this->assertTrue(Comparator::compare(self::COMPARE_EXPECTED2, self::COMPARE_IN2, 'TOKEN', ' '));
    }
}
