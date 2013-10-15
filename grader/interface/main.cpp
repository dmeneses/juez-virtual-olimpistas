#include <cstdlib>
#include <sstream>
#include <string.h>
#include <stdio.h>
#include "vjgrader.cpp"
#include "solutionattempt.h"
#include <iostream>

#define INPUT_DATA 8

using namespace std;

int main(int argc, char **argv)
{
    if (argc != INPUT_DATA)
    {
        printf("There is no input data. The count data is : %d\n", argc);
        return 1;
    }

    SolutionAttempt attempt;
    attempt.id = atoi(argv[1]);
    attempt.appToCompile = argv[2];
    attempt.testInputs = argv[3];
    attempt.expectedOutputs = argv[4];
    
    if (strcmp(argv[5], "cpp") == 0)
    {
        attempt.language = CPP;
    }
    else
    {
        attempt.language = ANSI_C;
    }
    
    attempt.constraint.time = atoi(argv[6]);
    attempt.constraint.memory = atoi(argv[7]);

    attempt.status = COMPILATION_ERROR;
    std::ostringstream appNaming;
    appNaming << "data/executions/compilation" << attempt.id;
    string appName = appNaming.str();

    std::ostringstream outputNaming;
    outputNaming << "data/executions/output" << attempt.id;
    string output = outputNaming.str();

    attempt.compiledApp = appName.c_str();
    attempt.generatedOutputs = output.c_str();
    grade(attempt);

    printf("ID: %d\n", attempt.id);
    printf("STATUS: %d\n", attempt.status);
    if (attempt.status == SUCCESS)
    {
        printf("Grade: %d\n", attempt.grade);
    }
    else
    {
       printf("Error: %s", attempt.errorMessage);
    }
    
    return 0;
}
