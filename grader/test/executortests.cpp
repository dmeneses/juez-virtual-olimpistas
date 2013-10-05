#include "gtest/gtest.h"
#include "compiler.h"
#include "executor.h"
#include "utils.h"

#define CODE_TO_COMPILE_CPP "resources/test.cpp"
#define OUTPUT_PATH_CPP "resources/test"

#define CODE_TO_COMPILE_C "resources/test.c"
#define OUTPUT_PATH_C "resources/test"

#define INPUT_FILE "resources/input"
#define OUTPUT_FILE "resources/output"


TEST(Executor, ExecuteCPPApp)
{
    Compiler compiler(CODE_TO_COMPILE_CPP, OUTPUT_PATH_CPP, CPP);
    StageOutput compileOutput;
    compiler.compile(compileOutput);
    ASSERT_TRUE(exist(OUTPUT_PATH_CPP));
    ASSERT_TRUE(isExecutable(OUTPUT_PATH_CPP));
    ASSERT_EQ(SUCCESS, compileOutput.getStatus());
    
    StageOutput executeOutput;
    Executor executor(OUTPUT_PATH_CPP, INPUT_FILE, OUTPUT_FILE);
    executor.execute(executeOutput);
    
    ASSERT_TRUE(exist(OUTPUT_FILE));
    ASSERT_EQ(SUCCESS, executeOutput.getStatus());
    remove(OUTPUT_PATH_CPP);
    remove(OUTPUT_FILE);
}

