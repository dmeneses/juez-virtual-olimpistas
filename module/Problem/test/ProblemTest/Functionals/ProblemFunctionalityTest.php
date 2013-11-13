<?php
class ProblemFunctionalityTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
    $this->setBrowser("*chrome");
    $this->setBrowserUrl("http://virtual-judge.org/");
  }

  public function testAddProblem()
  {
    $this->open("/");
    $this->click("link=Proponer Problema");
    $this->waitForPageToLoad("30000");
    $this->type("name=problem_name", "a+b");
    $this->type("name=problem_author", "JDG");
    $this->type("name=main_description", "../../../test_data/problem1/description.tex");
    $this->type("name=input_description", "../../../test_data/problem1/input.tex");
    $this->type("name=output_description", "../../../test_data/problem1/output.tex");
    $this->type("name=input_example", "../../../test_data/problem1/sample_in.tex");
    $this->type("name=output_example", "../../../test_data/problem1/sample_out.tex");
    $this->type("name=time_constraint", "1");
    $this->type("name=memory_constraint", "1");
    $this->type("name=source_constraint", "10000");
    $this->type("name=tests[0][test_points]", "100");
    $this->type("name=tests[0][test_in]", "../../../test_data/problem1/test.in");
    $this->type("name=tests[0][test_out]", "../../../test_data/problem1/test.out");
    $this->click("name=submit");
    $this->waitForPageToLoad("30000");
  }
}
?>