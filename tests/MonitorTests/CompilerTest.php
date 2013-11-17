<?php

require_once '../monitor/Compiler.php';

/**
 * Compiler tests
 *
 * @author Daniela Meneses
 */
class CompilerTest extends PHPUnit_Framework_TestCase {

    const TEST_DATA = '../tests/test_data/';
    const SCRIPT_PATH = '.././monitor/scripts/';

    private $compiler;
    private $output;

    public function setUp() {
        $this->output = self::TEST_DATA . 'output';
    }

    public function testCompileCppSource() {
        $this->compiler = new Compiler('CPP', self::TEST_DATA . 'test.cpp', 1);
        $this->compiler->setOutput($this->output);
        $this->compiler->setScriptPath(self::SCRIPT_PATH);
        $this->assertTrue($this->compiler->compile());
        $this->assertTrue(file_exists($this->output));
        unlink($this->output);
    }

    public function testCompileC_Source() {
        $this->compiler = new Compiler('C', self::TEST_DATA . 'test.c', 1);
        $this->compiler->setOutput($this->output);
        $this->compiler->setScriptPath(self::SCRIPT_PATH);
        $this->assertTrue($this->compiler->compile());
        $this->assertTrue(file_exists($this->output));
        unlink($this->output);
    }

    public function testCompileUnknownSource() {
        $this->compiler = new Compiler('somelanguage', self::TEST_DATA . 'test.cpp', 1);
        $this->compiler->setOutput($this->output);
        $this->compiler->setScriptPath(self::SCRIPT_PATH);
        $this->assertFalse($this->compiler->compile(), 'Expects that the compilation fails.');
        $this->assertFalse(file_exists($this->output), 'The file must not exist.');
        $this->assertStringStartsWith('sh:', $this->compiler->getError(), 'The expected content is not there.');
    }

    public function testCompileCppSourceThatHaveCompilationErrors() {
        $this->compiler = new Compiler('CPP', self::TEST_DATA . 'testfailed.cpp', 1);
        $this->compiler->setOutput($this->output);
        $this->compiler->setScriptPath(self::SCRIPT_PATH);
        $this->assertFalse($this->compiler->compile());
        $this->assertFalse(file_exists($this->output));
        $err = $this->compiler->getError();
        $this->assertFalse(empty($err));
    }

    public function testCompileC_SourceThatHaveCompilationErrors() {
        $this->compiler = new Compiler('C', self::TEST_DATA . 'testfailed.c', 1);
        $this->compiler->setOutput($this->output);
        $this->compiler->setScriptPath(self::SCRIPT_PATH);
        $this->assertFalse($this->compiler->compile());
        $this->assertFalse(file_exists($this->output));
        $err = $this->compiler->getError();
        $this->assertFalse(empty($err));
    }

}
