/* 
 * File:   Compiler.h
 * Author: Daniela Meneses
 *
 * Created on September 28, 2013, 10:59 AM
 */

#ifndef COMPILER_H
#define	COMPILER_H

#include "stageoutput.h"
#include "languagetype.h"

/**
 * Compiler for c or c++ code.
 */
class Compiler
{
public:
    Compiler(const char* inputPath, const char* outputPath, LanguageType type);
    Compiler(const Compiler& orig);
    virtual ~Compiler();
    void compile(StageOutput&);
private:
    const char* prepareCommand();
    const char* getLanguage();
    
    char* input_;
    char* output_;
    LanguageType type_;
};

#endif	/* COMPILER_H */

