/* 
 * File:   Executer.h
 * Author: Daniela Meneses
 *
 * Created on September 28, 2013, 8:13 PM
 */

#ifndef EXECUTER_H
#define	EXECUTER_H

#include "stageoutput.h"

/**
 * Will execute an app changing the default stdin and stdout.
 */
class Executor
{
public:
    Executor(const char* appName, const char* fileIn, const char* fileOut);
    Executor(const Executor& orig);
    virtual ~Executor();
    void execute(StageOutput& output);
private:
    const char* prepareCommand();
    
    char* appName_;
    char* fileIn_;
    char* fileOut_;
};

#endif	/* EXECUTER_H */

