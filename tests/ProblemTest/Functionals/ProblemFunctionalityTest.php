<?php

class ProblemFunctionalityTest extends PHPUnit_Extensions_SeleniumTestCase {

    const PROBLEM_NAME = 'A + B';

    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '~/Desktop';
    protected $screenshotUrl = 'http://localhost/screenshots';

    protected function setUp() {
        $this->setBrowser("*chrome");
        $this->setBrowserUrl("http://virtual-judge.org/");
    }

    public function testAddProblem() {
        $this->open("/");
        $this->click("link=Proponer Problema");
        $this->waitForPageToLoad("30000");
        $this->type("name=email", "daniela11290@gmail.com");
        $this->type("name=password", "1121990");
        $this->click("name=submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=Proponer Problema");
        $this->waitForPageToLoad("30000");
        $this->type("name=problem_name", self::PROBLEM_NAME);
        $this->type("name=problem_author", "JDG");
        $this->type("name=main_description", getcwd() . "../../../test_data/problem1/description.tex");
        $this->type("name=input_description", getcwd() . "../../../test_data/problem1/input.tex");
        $this->type("name=output_description", getcwd() . "../../../test_data/problem1/output.tex");
        $this->type("name=input_example", getcwd() . "../../../test_data/problem1/sample_in.tex");
        $this->type("name=output_example", getcwd() . "../../../test_data/problem1/sample_out.tex");
        $this->type("name=time_constraint", "1");
        $this->type("name=memory_constraint", "1");
        $this->type("name=source_constraint", "10000");
        $this->type("name=tests[0][test_points]", "100");
        $this->type("name=tests[0][test_in]", getcwd() . "../../../test_data/problem1/test.in");
        $this->type("name=tests[0][test_out]", getcwd() . "../../../test_data/problem1/test.out");
        $this->click("name=submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=" . self::PROBLEM_NAME);
        $this->waitForPageToLoad("30000");
        $this->assertTrue(strpos($this->getTitle(),  self::PROBLEM_NAME) !== false);
    }
}

?>