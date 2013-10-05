/* 
 * File:   SolutionAttempt.h
 * Author: Daniela Meneses
 *
 * Created on October 2, 2013, 10:34 AM
 */

#ifndef SOLUTIONATTEMPT_H
#define	SOLUTIONATTEMPT_H

#include <string.h>
#include "languagetype.h"

enum GradeStatus
{
    COMPILATION_ERROR,
    RUNTIME_ERROR,
    OK
};

struct SolutionAttempt
{
    SolutionAttempt()
    {
        id = 0;
        errorMessage = 0;
        grade = 0;
    }
    
    ~SolutionAttempt()
    {
        if (errorMessage)
        {
            delete[] errorMessage;
            errorMessage = 0;
        }
    }

    int id;
    const char* appToCompile;
    const char* compiledApp;
    LanguageType language;
    GradeStatus status;
    char* errorMessage;
    const char* testInputs;
    const char* generatedOutputs;
    const char* expectedOutputs;
    int grade;

    void setErrorMessage(const char* message)
    {
        errorMessage = new char[strlen(message) + 1];
        strcpy(errorMessage, message);
    }
};

#endif	/* SOLUTIONATTEMPT_H */

