<?php

/**
 * Execute code
 *
 * @author Daniela Meneses
 */
class Executor {

    private $memoryUsage = 0;
    private $executionTime = 0;
    private $timeConstraint;
    private $memoryConstraint;
    private $app;
    private $script;
    private $error;
    private $errorType;
    private $output = '';
    private $timeoutInfo = '';

    function __construct($app, $script, $timeConstraint, $memoryConstraint) {
        $this->timeConstraint = $timeConstraint;
        $this->memoryConstraint = $memoryConstraint;
        $this->app = $app;
        $this->script = $script;
    }

    public function getMemoryUsage() {
        return $this->memoryUsage;
    }

    public function getExecutionTime() {
        return $this->executionTime;
    }

    public function setMemoryUsage($memoryUsage) {
        $this->memoryUsage = $memoryUsage;
    }

    public function setExecutionTime($executionTime) {
        $this->executionTime = $executionTime;
    }

    public function getError() {
        return $this->error;
    }

    public function getErrorType() {
        return $this->errorType;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function setErrorType($errorType) {
        $this->errorType = $errorType;
    }

    public function getOutput() {
        return $this->output;
    }

    public function setOutput($output) {
        $this->output = $output;
    }

    public function execute($input, $id) {
        $this->output = "./data/execution/gen_output$id";
        $this->timeoutInfo = "./data/execution/timeout_$id";

        $testTime = microtime(true);
        system("./timeout -t $this->timeConstraint -m $this->memoryConstraint "
                . "./$this->app $input $this->output 2>$this->timeoutInfo");
        $testTimeFinal = microtime(true) - $testTime;
        $this->executionTime += round($testTimeFinal, 3);
        $this->memoryUsage += $this->getMemoryUsageByTest();

        if (!$this->checkTestConstraints() || !$this->checkGeneralRunConstraints()) {
            return false;
        }

        return true;
    }

    private function getMemoryUsageByTest() {
        $content = file_get_contents($this->timeoutInfo);
        $pos = strpos($content, " MEM ");
        if ($pos === false) {
            return 0;
        } else {
            $pos += 5;
            $pos2 = strpos($content, "MAXMEM");
            if ($pos2 === false) {
                return 0;
            } else {
                $pos2--;
                $length = $pos2 - $pos;
                return substr($content, $pos, $length);
            }
        }
    }

    private function checkTestConstraints() {
        $timeoutContent = file_get_contents($this->timeoutInfo);

        if ($this->startsWith($timeoutContent, "FINISHED")) {
            return true;
        }
        if ($this->startsWith($timeoutContent, "TIMEOUT")) {
            $this->setTimeExceededError();
            return false;
        }
        if ($this->startsWith($timeoutContent, "MEM")) {
            $this->setMemoryExceededError();
            return false;
        }
        if (empty($timeoutContent)) {
            $this->setExecutionError();
            return false;
        }
    }

    private function startsWith($haystack, $needle) {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    private function checkGeneralRunConstraints() {
        if ($this->executionTime > $this->timeConstraint) {
            $this->setTimeExceededError();
            return false;
        }

        if ($this->memoryUsage > $this->memoryConstraint) {
            $this->setMemoryExceededError();
            return false;
        }
    }

    private function setMemoryExceededError() {
        $this->errorType = 'MEMORY_LIMIT_EXCEEDED';
        $this->error = 'The used memory exceed the given memory for this problem.';
    }

    private function setTimeExceededError() {
        $this->errorType = 'TIME_LIMIT_EXCEEDED';
        $this->error = 'The execution time exceed the given time for this problem.';
    }

    private function setExecutionError() {
        $this->errorType = 'EXECUTION_ERROR';
        $this->error = 'Something happen while your program was running.';
    }

}
