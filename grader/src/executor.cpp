/* 
 * File:   Executer.cpp
 * Author: dann
 * 
 * Created on September 28, 2013, 8:13 PM
 */

#include <string.h>
#include <string>
#include <cstdlib>
#include "executor.h"
#include <time.h>
#include <iostream>
#include <stdio.h>
#include <glog/logging.h>

using namespace std;

time_t start, stop;

Executor::Executor(const char* appName, const char* fileIn, const char* fileOut)
{
    LOG(INFO) << "Creating executor";
    appName_ = new char[strlen(appName) + 1];
    strcpy(appName_, appName);
    fileIn_ = new char[strlen(appName) + 1];
    strcpy(fileIn_, fileIn);
    fileOut_ = new char[strlen(appName) + 1];
    strcpy(fileOut_, fileOut);
}

Executor::~Executor()
{
    LOG(INFO) << "Deleting executor";
    if (appName_)
        delete[] appName_;
    if (fileIn_)
        delete[] fileIn_;
    if (fileOut_)
        delete[] fileOut_;

    appName_ = 0;
    fileIn_ = 0;
    fileOut_ = 0;
}

const char* Executor::prepareCommand()
{
    string res("./");
    res.append(appName_);
    res.append(" < ");
    res.append(fileIn_);
    res.append(" > ");
    res.append(fileOut_);
    LOG(INFO) << "Command to execute: " << res;
    return res.c_str();
}

void Executor::execute(StageOutput& output, SolutionAttempt& attempt)
{
    LOG(INFO) << "Executing app...";
    const char* command = prepareCommand();

    time(&start);
    system(command);
    time(&stop);

    double finalTime = difftime(stop, start);
    
    if (finalTime > attempt.constraint.time)
    {
        LOG(INFO) << "Time limit exceeded";
        output.setStatus(TIME_LIMIT_EXCEEDED);
        output.setErrorMessage("Time limit exceeded");
    }
    else
    {
        LOG(INFO) << "Execution success";
        output.setStatus(SUCCESS);
    }
    
    attempt.runtime = finalTime;
}