<?php

/**
 * Compile code given scripts in the monitor/scripts folder.
 *
 * @author Daniela Meneses
 */
class Compiler {

    const STD_OUTPUT = './data/execution/';

    private $error = '';
    private $language;
    private $input;
    private $output;
    private $solutionID = 0;
    private $scriptPath = 'monitor/scripts/';

    function __construct($language, $input, $solutionID) {
        $this->language = $language;
        $this->input = $input;
        $this->output = self::STD_OUTPUT . 'compilation_' . $solutionID;
        $this->solutionID = $solutionID;
    }

    public function getInput() {
        return $this->input;
    }

    public function getOutput() {
        return $this->output;
    }

    public function setInput($input) {
        $this->input = $input;
    }

    public function setOutput($output) {
        $this->output = $output;
    }

    public function getScriptPath() {
        return $this->scriptPath;
    }

    public function setScriptPath($scriptPath) {
        $this->scriptPath = $scriptPath;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function compile() {
        $res = true;
        $command = "$this->scriptPath$this->language $this->input $this->output 2>&1";

        $pipe = popen($command, 'r');
        
        while (!feof($pipe)) {
            $this->error .= fread($pipe, 500);
        }
        
        pclose($pipe);

        if (!empty($this->error)) {
            $res = false;
        }
        
        return $res;
    }
}
