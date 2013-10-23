/* 
 * File:   CompilerOutput.h
 * Author: dann
 *
 * Created on September 28, 2013, 11:39 AM
 */

#ifndef COMPILEROUTPUT_H
#define	COMPILEROUTPUT_H

#include <string>
#include "errorcode.h"

class StageOutput
{
public:
    StageOutput();
    StageOutput(ErrorCode status, const std::string& errorMessage = "");
    StageOutput(const StageOutput& orig);
    virtual ~StageOutput();
    ErrorCode getStatus() const;
    const std::string& getErrorMessage() const;
    void setStatus(ErrorCode status);
    void setErrorMessage(const std::string& errorMessage);

private:    
    ErrorCode status_;
    std::string errorMessage_;
};

#endif	/* COMPILEROUTPUT_H */

