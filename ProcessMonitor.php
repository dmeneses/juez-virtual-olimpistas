<?php

require_once './monitor/KLogger.php';
require './monitor/Zebra_Database.php';
require './monitor/Compiler.php';
require './monitor/Executor.php';
require './monitor/Comparator.php';

//Set this constant to false if we ever need to debug the application in a terminal.
define('QUEUESERVER_FORK', false);
define('JOB_ID', 'id');
define('SOLUTION_ID', 'solution_id');
define('GRADE', 1);
define('PIPE_PATH', '/tmp/bravesoft_input');

// create a new database wrapper object
$databaseConnection = new Zebra_Database();

// connect to the MySQL server and select the database
$databaseConnection->connect(
        'localhost', // host
        'mbravesoft', // user name
        'SDFdfg51wer', // password
        'tis_mbravesoft' // database
);

//Queue that will keep the task waiting.
$queue = array();

//Fork to for a background task.
if (QUEUESERVER_FORK) {
    $pid = pcntl_fork();
    if ($pid === -1)
        die('error: unable to fork.');
    else if ($pid)
        exit(0);

    posix_setsid();
    sleep(1);

    ob_start();
}

//Setup pipe.
$pipefile = PIPE_PATH;
if (file_exists($pipefile))
    if (!unlink($pipefile))
        die('unable to remove stale file');

umask(0);
if (!posix_mkfifo($pipefile, 0666))
    die('unable to create named pipe');

$pipe = fopen($pipefile, 'r+');
if (!$pipe)
    die('unable to open the named pipe');
stream_set_blocking($pipe, false);

//Process the queue.
while (1) {

    while ($input = trim(fgets($pipe))) {
        stream_set_blocking($pipe, false);
        $queue[] = $input;
    }

    $job = current($queue);
    $jobkey = key($queue);

    if ($job) {
        echo 'Processing job: ', $job, PHP_EOL;
        processJob($job, $databaseConnection);
        next($queue);
        unset($job, $queue[$jobkey]);
    } else {
        echo 'no jobs to do - waiting...', PHP_EOL;
        stream_set_blocking($pipe, true);
    }

    if (QUEUESERVER_FORK)
        ob_clean();
}

/**
 * Process the received job.
 * 
 * @param type $job String describing the job to perform.
 */
function processJob($job, Zebra_Database $database) {
    $parsedJob = getJobType($job);
    if (isset($parsedJob[JOB_ID])) {
        switch ($parsedJob[JOB_ID]) {
            case GRADE: gradeSolution($parsedJob[SOLUTION_ID], $database);
                break;
            default : echo "Not recognized job";
        }
    }
    return;
}

/**
 * Parse job string to get the type of the job and the neccesary data to perform it.
 * 
 * @param type $job Job to parse
 * 
 * @return type array with id of the job and the table id to perform it.
 */
function getJobType($job) {
    list($jobId, $tableId) = explode(" ", $job);
    if (isset($jobId) && isset($tableId)) {
        return array(JOB_ID => $jobId, SOLUTION_ID => $tableId);
    } else {
        return array();
    }
}

/**
 * Will grade the received solution.
 * 
 * @param type $solutionID Solution to grade.
 */
function gradeSolution($solutionID, Zebra_Database $database) {
    $log = new KLogger("logs", KLogger::DEBUG);
    $log->LogInfo("Grading solution: $solutionID");
    $solution = getSolutionData($database, $solutionID);
    if (empty($solution)) {
        $log->LogError('Solution not found');
        throw new Exception("There isn't a solution with id: $solutionID");
    }

    $log->LogInfo("Seaching problem");
    $problem = getProblemData($database, $solution['problem_problem_id']);
    if (empty($problem)) {
        $log->LogError('Problem not found');
        throw new Exception("There isn't a problem with id: $solutionID");
    }

    $log->LogInfo("Compiling...");
    $result = initializeData();
    $compiler = new Compiler($solution['solution_language'], $solution['solution_source_file'], $solutionID);

    if (!$compiler->compile()) {
        $log->logError("Compilation failed.");
        $result['status'] = 'COMPILATION_ERROR';
        $result['error_message'] = $compiler->getError();
        $database->update('solution', $result, 'solution_id = ?', array($solutionID));
        return;
    }

    $log->LogInfo("Executing...");
    $executor = new Executor($compiler->getOutput(), './monitor/scripts/default', $problem['time_constraint'], $problem['memory_constraint']);
    $executor->setLogger($log);
    $grade = 0;
    foreach ($problem['tests'] as $test) {
        if ($executor->execute($test['test_in'], $test['test_id'])) {
            $output = $executor->getOutput();
            $grade += Comparator::compare($test['test_out'], $output) ? $test['test_points'] : 0;
        } else {
            $log->logError("Execution failed.");
            $result['grade'] = 0;
            $result['used_memory'] = str_replace(',', '.', $executor->getMemoryUsage());
            $result['runtime'] = str_replace(',', '.', $executor->getExecutionTime());
            $result['status'] = $executor->getErrorType();
            $result['error_message'] = $executor->getError();
            $database->update('solution', $result, 'solution_id = ?', array($solutionID));
            return;
        }
    }

    $result['status'] = 'SUCCESS';
    $result['grade'] = $grade;
    $result['used_memory'] = str_replace(',', '.', ($executor->getMemoryUsage())) ;
    $result['runtime'] = str_replace(',', '.', $executor->getExecutionTime());
    $database->update('solution', $result, 'solution_id = ?', array($solutionID));
    return;
}

function initializeData() {
    return array(
        'grade' => '0',
        'runtime' => '0',
        'used_memory' => '0',
        'status' => 'Executed',
        'error_message' => '',
    );
}

function getSolutionData($database, $solutionID) {
    $database->select('*', 'solution', 'solution_id = ?', array($solutionID));
    $solutionRecords = $database->fetch_assoc_all();
    if (count($solutionRecords) == 1) {
        return $solutionRecords[0];
    }

    return array();
}

function getProblemData($database, $problemID) {
    $database->select('time_constraint, memory_constraint', 'problem', 'problem_id = ?', array($problemID));
    $problemRecords = $database->fetch_assoc_all();

    if (count($problemRecords) == 1) {
        $problem = $problemRecords[0];
        $database->select('*', 'test_case', 'problem_problem_id = ?', array($problemID));
        $testCases = $database->fetch_assoc_all();
        if (count($testCases) < 1) {
            throw new Exception("The problem $problemID doesn't have defined a test case.");
        }
        $problem['tests'] = $testCases;
        return $problem;
    }

    return array();
}

?>