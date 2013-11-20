<?php

namespace TrainingTest\Model;

use Training\Model\DateValidationType;
use Training\Model\DateValidator;
use PHPUnit_Framework_TestCase;

/**
 * Test for date validator.
 *
 * @problem_author Daniela Meneses
 */
class DateValidatorTest extends PHPUnit_Framework_TestCase {

    public function testDateValidatorWithoutSettings() {
        $todayDate = date("Y-m-d");
        $validator = new DateValidator();
        $this->assertFalse($validator->isValid($todayDate));
    }

    public function testDateValidatorWithoutTimeSettings() {
        $todayDate = date("Y-m-d");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setIsTime(true);
        $this->assertFalse($validator->isValid($todayDate));
    }
    
    public function testDateValidatorWithoutTimeAndDateSettings() {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setComparedTime($todayTime);
        $validator->setIsTime(true);
        $this->assertFalse($validator->isValid($todayTime));
    }

    public function testTwoEqualDates() {
        $todayDate = date("Y-m-d");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setCompareType(DateValidationType::SAME);
        $this->assertTrue($validator->isValid($todayDate));
    }

    public function testTwoDifferentDates() {
        $todayDate = date("Y-m-d");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setCompareType(DateValidationType::SAME);
        $validator->setIsTime(false);
        $this->assertFalse($validator->isValid("1990-01-01"));
    }

    public function testTwoDifferentDatetimes() {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i", mktime(0, 0, 0, 0, 0, 0));
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setComparedTime($todayTime);
        $validator->setDate($todayDate);
        $validator->setCompareType(DateValidationType::SAME);
        $validator->setIsTime(true);
        $this->assertFalse($validator->isValid('00:11'));
    }
    
    public function testTwoEqualDatetimes() {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i", mktime(0, 11, 0, 0, 0, 0));
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setComparedTime($todayTime);
        $validator->setDate($todayDate);
        $validator->setCompareType(DateValidationType::SAME);
        $validator->setIsTime(true);
        $this->assertTrue($validator->isValid('00:11'));
    }   
    
    public function testFirstDateLaterThanOther() {
        $date = date("Y-m-d", mktime(0, 0, 0, 11, 1, 2013));
        $otherDate = date("Y-m-d", mktime(0, 0, 0, 11, 2, 2013));
        $validator = new DateValidator();
        $validator->setComparedDate($date);
        $validator->setCompareType(DateValidationType::LATER);
        $this->assertTrue($validator->isValid($otherDate));
    }
    
    public function testFirstDateInclusiveLaterThanOther() {
        $todayDate = date("Y-m-d");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setCompareType(DateValidationType::LATER);
        $this->assertTrue($validator->isValid($todayDate));
    }
    
    public function testFirstDateNotLaterThanOther() {
        $todayDate = date("Y-m-d");
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setCompareType(DateValidationType::LATER);
        $this->assertFalse($validator->isValid('1990-01-01'));
    }
    
    public function testFirstDatetimeNotLaterThanOtherOnSameDay() {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i", mktime(15, 30, 0, 0, 0, 0));
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setComparedTime($todayTime);
        $validator->setDate($todayDate);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setIsTime(true);
        $this->assertFalse($validator->isValid('13:30'));
    }
    
    public function testFirstDatetimeLaterThanOtherOnSameDay() {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i", mktime(15, 30, 0, 0, 0, 0));
        $validator = new DateValidator();
        $validator->setComparedDate($todayDate);
        $validator->setComparedTime($todayTime);
        $validator->setDate($todayDate);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setIsTime(true);
        $this->assertTrue($validator->isValid('18:30'));
    }
    
    public function testFirstDatetimeLaterThanOtherOnDifferentDay() {
        $date1 = date("Y-m-d", mktime(0, 0, 0, 11, 2, 2013));
        $date2 = date("Y-m-d", mktime(0, 0, 0, 11, 3, 2013));
        $time = date("G:i", mktime(15, 30, 0, 0, 0, 0));
        $validator = new DateValidator();
        $validator->setComparedDate($date1);
        $validator->setComparedTime($time);
        $validator->setDate($date2);
        $validator->setCompareType(DateValidationType::LATER);
        $validator->setIsTime(true);
        $this->assertTrue($validator->isValid('13:30'));
    }
}
