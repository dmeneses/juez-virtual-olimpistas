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
#include "errorcode.h"
#include <ostream>
#include "constraint.h"

struct SolutionAttempt
{

    SolutionAttempt()
    {
        id = 0;
        errorMessage = 0;
        grade = 0;
        runtime = 0;
        memory = 0;
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
    ErrorCode status;
    char* errorMessage;
    const char* testInputs;
    const char* generatedOutputs;
    const char* expectedOutputs;
    int runtime;
    int memory;
    Constraint constraint;
    int grade;

    void setErrorMessage(const char* message)
    {
        errorMessage = new char[strlen(message) + 1];
        strcpy(errorMessage, message);
    }

    friend std::ostream& operator<<(std::ostream& os, const SolutionAttempt& dt);
};

inline std::ostream& operator<<(std::ostream& os, const SolutionAttempt& obj)
{
    os << "ID:" << obj.id << "\n" <<
            "SOURCE_FILE:" << obj.appToCompile << "\n" <<
            "COMPILED_OUTPUT:" << obj.compiledApp << "\n" <<
            "LANGUAGE:" << obj.language << "\n" <<
            "STATUS:" << obj.status << "\n" <<
            "INPUT_FILE:" << obj.testInputs << "\n" <<
            "OUTPUT_FILE:" << obj.generatedOutputs << "\n" <<
            "EXPECTED_OUTPUT_FILE:" << obj.expectedOutputs << "\n" <<
            "RUNTIME:" << obj.runtime << "\n" <<
            "MEMORY:" << obj.memory << "\n" <<
            "CONSTRAINT:" << obj.constraint << "\n" <<
            "GRADE:" << obj.grade << "\n" <<
            "ERROR:" << obj.errorMessage << "\n";
    return os;
}


#endif	/* SOLUTIONATTEMPT_H */

