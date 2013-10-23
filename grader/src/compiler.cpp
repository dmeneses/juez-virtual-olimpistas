/* 
 * File:   Compiler.cpp
 * Author: dann
 * 
 * Created on September 28, 2013, 10:59 AM
 */

#include "compiler.h"
#include <string.h>
#include <stdlib.h>
#include <string>
#include <stdio.h>
#include <iostream>

using namespace std;

Compiler::Compiler(const char* inputPath, const char* outputPath, LanguageType type)
{
    input_ = new char[strlen(inputPath) + 1];
    strcpy(input_, inputPath);
    output_ = new char[strlen(outputPath) + 1];
    strcpy(output_, outputPath);
    type_ = type;
}

Compiler::~Compiler()
{
    if (input_)
        delete[] input_;
    if (output_)
        delete[] output_;
}

const char* Compiler::getLanguage() {
    return CPP == type_? "g++ " : "gcc ";
}

const char* Compiler::prepareCommand()
{
    const char* language = getLanguage();
    string res(language);
    res.append(input_);
    res.append(" -o ");
    res.append(output_);
    res.append(" 2>&1");
    return res.c_str();
}

void Compiler::compile(StageOutput& output)
{
    const char* command = prepareCommand();
    FILE* pipe = popen(command, "r");
    if (!pipe)
    {
        output.setErrorMessage("Fork or pipe call failed.");
    }

    char buffer[500];
    std::string result = "";
    while (!feof(pipe))
    {
        if (fgets(buffer, sizeof (buffer), pipe) != NULL)
            result += buffer;
    }

    pclose(pipe);

    if (result.empty())
    {
        output.setStatus(SUCCESS);
    }
    else
    {
        output.setErrorMessage(result);
    }
}