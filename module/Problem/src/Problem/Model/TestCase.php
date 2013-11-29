<?php

namespace Problem\Model;

/**
 * Test case for problem.
 *
 * @author dann
 */
class TestCase {

    const ID = 'test_id';
    const IN = 'test_in';
    const OUT = 'test_out';
    const POINTS = 'test_points';
    const PROBLEM = 'problem_problem_id';

    /**
     * Test case id.
     * @var int
     */
    public $test_id;

    /**
     * Input test file.
     * @var string
     */
    public $test_in;

    /**
     * Output test file.
     * @var string
     */
    public $test_out;

    /**
     * Point that will be given if the test pass.
     * @var int
     */
    public $test_points;

    /**
     * Problem that owns this test case.
     * 
     * @var int
     */
    public $problem_id;

    public function exchangeArray(array $data) {
        $this->test_id = (!empty($data[self::ID])) ? $data[self::ID] : null;
        $this->test_points = (!empty($data[self::POINTS])) ? $data[self::POINTS] : null;
        $this->test_in = $this->getFileName($data[self::IN]);
        $this->test_out = $this->getFileName($data[self::OUT]);
    }

    public function getFileName($fileInfo) {
        $filename = '';
        if ($fileInfo != null && is_array($fileInfo)) {
            $filename = (!empty($fileInfo)) ? $fileInfo['tmp_name'] : null;
        } else {
            $filename = (!empty($fileInfo)) ? $fileInfo : null;
        }
        return $filename;
    }
}
