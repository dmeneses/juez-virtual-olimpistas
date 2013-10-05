#include "gtest/gtest.h"
#include "compiler.h"
#include "utils.h"

#define CODE_TO_COMPILE_CPP "resources/test.cpp"
#define OUTPUT_PATH_CPP "resources/test"
#define CODE_TO_COMPILE_C "resources/test.c"
#define OUTPUT_PATH_C "resources/test"
#define CODE_TO_COMPILE_CPP_FAIL "resources/testfailed.cpp"
#define CODE_TO_COMPILE_C_FAIL "resources/testfailed.c"

TEST(Compiler, CompileCPPCode)
{
    Compiler compiler(CODE_TO_COMPILE_CPP, OUTPUT_PATH_CPP, CPP);
    StageOutput output;
    compiler.compile(output);
    ASSERT_TRUE(exist(OUTPUT_PATH_CPP));
    ASSERT_TRUE(isExecutable(OUTPUT_PATH_CPP));
    ASSERT_EQ(SUCCESS, output.getStatus());
    remove(OUTPUT_PATH_CPP);
}

TEST(Compiler, CompileANSI_CCode)
{
    Compiler compiler(CODE_TO_COMPILE_C, OUTPUT_PATH_C, ANSI_C);
    StageOutput output;
    compiler.compile(output);
    ASSERT_TRUE(exist(OUTPUT_PATH_C));
    ASSERT_TRUE(isExecutable(OUTPUT_PATH_C));
    ASSERT_EQ(SUCCESS, output.getStatus());
    remove(OUTPUT_PATH_C);
}

TEST(Compiler, CompileCPPCodeThatHaveToFail)
{
    Compiler compiler(CODE_TO_COMPILE_CPP_FAIL, OUTPUT_PATH_CPP, CPP);
    StageOutput output;
    compiler.compile(output);
    ASSERT_EQ(FAIL, output.getStatus());
    ASSERT_FALSE(output.getErrorMessage().empty());
}

TEST(Compiler, CompileANSI_CCodeThatHaveToFail)
{
    Compiler compiler(CODE_TO_COMPILE_C_FAIL, OUTPUT_PATH_C, ANSI_C);
    StageOutput output;
    compiler.compile(output);
    ASSERT_EQ(FAIL, output.getStatus());
    ASSERT_FALSE(output.getErrorMessage().empty());
}
