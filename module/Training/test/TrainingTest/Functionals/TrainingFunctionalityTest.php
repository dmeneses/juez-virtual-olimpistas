<?php

/**
 * Description of TrainingFunctionalityTest
 *
 * @author Daniela Meneses
 */
class TrainingFunctionalityTest extends PHPUnit_Extensions_SeleniumTestCase {

    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/home/dann/Desktop';
    protected $screenshotUrl = 'http://localhost/screenshots';

    protected function setUp() {
        $this->setBrowser("*chrome");
        $this->setBrowserUrl("http://virtual-judge.org/");
    }

    public function testCreateTraining() {
        $this->open("/");
        $this->click("link=Crear Entrenamiento");
        $this->waitForPageToLoad("30000");
        $this->type("name=training_name", "Training1");
        $this->type("name=start_date", date('Y-m-d'));
        $this->type("name=start_time", date('G:i'));
        $this->type("name=end_date", date('Y-m-d'));
        $this->type("name=end_time", date('G:i'));
        $this->click("name=submit");
        $this->waitForPageToLoad("30000");
        $this->click("link=Training1");
        $this->waitForPageToLoad("30000");
    }

}
