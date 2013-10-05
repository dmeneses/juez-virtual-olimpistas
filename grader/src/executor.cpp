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

#include <iostream>

using namespace std;

Executor::Executor(const char* appName, const char* fileIn, const char* fileOut)
{
    appName_ = new char[strlen(appName) + 1];
    strcpy(appName_, appName);
    fileIn_ = new char[strlen(appName) + 1];
    strcpy(fileIn_, fileIn);
    fileOut_ = new char[strlen(appName) + 1];
    strcpy(fileOut_, fileOut);
}

Executor::~Executor()
{
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
    return res.c_str();
}

void Executor::execute(StageOutput& output)
{
    const char* command = prepareCommand();
    system(command);
    output.setStatus(SUCCESS);
}