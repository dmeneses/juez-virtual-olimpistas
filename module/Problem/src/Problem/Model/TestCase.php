<?php

namespace Problem\Model;

/**
 * Test case for problem.
 *
 * @author dann
 */
class TestCase {
    /**
     * Test case id.
     * @var int
     */
    private $test_id;
    /**
     * Input test file.
     * @var string
     */
    private $test_in;
    /**
     * Output test file.
     * @var string
     */
    private $test_out;
    /**
     * Point that will be given if the test pass.
     * @var int
     */
    private $test_points;
    
    public function getTest_id() {
        return $this->test_id;
    }

    public function getTest_in() {
        return $this->test_in;
    }

    public function getTest_out() {
        return $this->test_out;
    }

    public function getTest_points() {
        return $this->test_points;
    }

    public function setTest_id($test_id) {
        $this->test_id = $test_id;
    }

    public function setTest_in($test_in) {
        $this->test_in = $test_in;
    }

    public function setTest_out($test_out) {
        $this->test_out = $test_out;
    }

    public function setTest_points($test_points) {
        $this->test_points = $test_points;
    }

// if (isset($data['file_in']) && is_array($data['file_in'])) {
//            $this->file_in = (!empty($data['file_in'])) ? $data['file_in']['tmp_name'] : null;
//        } else {
//            $this->file_in = (!empty($data['file_in'])) ? $data['file_in'] : null;
//        }
//
//        if (isset($data['file_out']) && is_array($data['file_out'])) {
//            $this->file_out = (!empty($data['file_out'])) ? $data['file_out']['tmp_name'] : null;
//        } else {
//            $this->file_out = (!empty($data['file_out'])) ? $data['file_out'] : null;
//        }
}
