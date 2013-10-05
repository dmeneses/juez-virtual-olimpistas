/* 
 * File:   CompilerOutput.h
 * Author: dann
 *
 * Created on September 28, 2013, 11:39 AM
 */

#ifndef COMPILEROUTPUT_H
#define	COMPILEROUTPUT_H

#include <string>

enum StageStatus {
    SUCCESS,
    FAIL
};

class StageOutput
{
public:
    StageOutput();
    StageOutput(StageStatus status, const std::string& errorMessage = "");
    StageOutput(const StageOutput& orig);
    virtual ~StageOutput();
    StageStatus getStatus() const;
    const std::string& getErrorMessage() const;
    void setStatus(StageStatus status);
    void setErrorMessage(const std::string& errorMessage);

private:    
    StageStatus status_;
    std::string errorMessage_;
};

#endif	/* COMPILEROUTPUT_H */

