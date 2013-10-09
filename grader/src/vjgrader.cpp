#include "compiler.h"
#include "comparator.h"
#include "executor.h"
#include "solutionattempt.h"

void grade(SolutionAttempt& attempt)
{
    StageOutput stageOutput;
    Compiler compiler(attempt.appToCompile, attempt.compiledApp, attempt.language);
    compiler.compile(stageOutput);

    if (stageOutput.getStatus() == FAIL)
    {
        attempt.status = COMPILATION_ERROR;
        attempt.setErrorMessage(stageOutput.getErrorMessage().c_str());
        return;
    }

    stageOutput.setStatus(FAIL);
    Executor executor(attempt.compiledApp, attempt.testInputs, attempt.generatedOutputs);
    executor.execute(stageOutput, attempt.constraint);

    if (stageOutput.getStatus() != SUCCESS)
    {
        attempt.status = stageOutput.getStatus();
        attempt.setErrorMessage(stageOutput.getErrorMessage().c_str());
        return;
    }

    Comparator comparator;
    bool equal = comparator.compareFiles(attempt.expectedOutputs, attempt.generatedOutputs);

    attempt.status = SUCCESS;
    if (equal)
        attempt.grade = 100;
    else
        attempt.grade = 0;

} 