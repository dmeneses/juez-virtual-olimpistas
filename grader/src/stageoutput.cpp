/* 
 * File:   CompilerOutput.cpp
 * Author: dann
 * 
 * Created on September 28, 2013, 11:39 AM
 */

#include "stageoutput.h"

StageOutput::StageOutput() : status_(FAIL)
{
}

StageOutput::StageOutput(ErrorCode status, const std::string& errorMessage) 
: status_(status), errorMessage_(errorMessage)
{
}

StageOutput::StageOutput(const StageOutput& orig)
{
}

StageOutput::~StageOutput()
{
}


ErrorCode StageOutput::getStatus() const
{
    return status_;
}

const std::string& StageOutput::getErrorMessage() const
{
    return errorMessage_;
}

void StageOutput::setStatus(ErrorCode status)
{
    status_ = status;
}

void StageOutput::setErrorMessage(const std::string& errorMessage)
{
    errorMessage_ = errorMessage;
}