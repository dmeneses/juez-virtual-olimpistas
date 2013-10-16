<?php

require './monitor/Zebra_Database.php';

//Set this constant to false if we ever need to debug the application in a terminal.
define('QUEUESERVER_FORK', false);
define('JOB_ID', 'id');
define('SOLUTION_ID', 'solution_id');
define('GRADE', 1);
define('PIPE_PATH', '/tmp/queueserver-input');

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
    if (isset($parsedJob[JOB_ID]))
        switch ($parsedJob[JOB_ID]) {
            case GRADE: gradeSolution($parsedJob[SOLUTION_ID], $database);
                break;
            default : echo "Not recognized job";
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
    $database->select('*', 'solution', 'solution_id = ?', array($solutionID));
    $solutionRecords = $database->fetch_assoc_all();
    if (count($solutionRecords) == 1) {
        $solution = $solutionRecords[0];
        $database->select('file_in, file_out, time_constraint, memory_constraint', 'problem', 'problem_id = ?', array($solution['problem_problem_id']));
        $problemRecords = $database->fetch_assoc_all();

        if (count($problemRecords) == 1) {
            $problem = $problemRecords[0];
            $output = 'data/executions/result' . $solution['solution_id'];
            $command = prepareCommand($solution, $output, $problem);
            exec($command);
            parseAndSaveData($solutionID, $output, $database);
        }
    }
}

function prepareCommand(array $solution, $output, array $problem) {
    return './vjgraderapp ' . $solution['solution_id'] . ' ' .
            $solution['solution_source_file'] . ' ' .
            $problem['file_in'] . ' ' .
            $problem['file_out'] . ' ' .
            $solution['solution_language'] . ' ' .
            $problem['time_constraint'] . ' ' .
            $problem['memory_constraint'] . ' > ' . $output;
}

function parseAndSaveData($solutionID, $output, Zebra_Database $database) {
    $file_handle = fopen($output, "r");
    $data = array(
        'grade' => '0',
        'runtime' => '0',
        'used_memory' => '0',
        'status' => 'Executed',
        'error_message' => '',
    );
    $error = false;
    $errorMessage = '';
    
    while (!feof($file_handle)) {
        $line = fgets($file_handle);
        echo $line . PHP_EOL;
        if ($error) {           
            $errorMessage .= $line;
        } else {           
            if (!empty($line)) {
                list($dataType, $result) = explode("-", $line);
                
                if ($dataType == "ERROR") {
                    $error = true;
                    $errorMessage .= $result;
                } else {
                    switch ($dataType) {
                        case "STATUS":$data['status'] = statusToString($result);
                            break;
                        case "RUNTIME":$data['runtime'] = $result;
                            break;
                        case "MEMORY":$data['used_memory'] = $result;
                            break;
                        case "GRADE":$data['grade'] = $result;
                            break;
                        default:
                    }
                }
            }
        }
    }

    if ($errorMessage != '') {       
        $data['error_message'] = $errorMessage;
    }

    fclose($file_handle);
    unlink ($output);
    $database->update(
            'solution', $data, 'solution_id = ?', array($solutionID)
    );
}

function statusToString($status) {
    switch ($status) {
        case 0: return 'SUCCESS';
        case 1: return 'COMPILATION_ERROR';
        case 2: return 'RUNTIME_ERROR';
        case 3: return 'TIME_LIMIT_EXCEEDED';
        case 4: return 'MEMORY_LIMIT_EXCEEDED';
        case 5: return 'FAIL';
        default: return '';
    }
}

?>