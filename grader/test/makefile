# source files

SRC = testrunner.cpp \
    compilertests.cpp \
    executortests.cpp \
    comparatortests.cpp \
    utils.cpp

OBJ = $(patsubst %.cpp,obj/%.o,$(SRC))

OUT = vjtests

#include directories
INCLUDES = -I../src \
           -I../third-party/GTest-1.7.0/Include

#library paths
LIBS = -L/user/local/lib -L../src -L../third-party/GTest-1.7.0/Lib/ -lvjgrader -lgtest

#Compiler flags
CCFLAGS = -fPIC -g -Wall -pthread
LDFLAGS = rcs

#compiler
CCC = g++

default: $(OUT)

$(OBJ): | obj

obj:
	@echo creating objects directory $@
	@mkdir -p $@
	@mkdir -p $(patsubst %/, $@/%, $(sort $(dir $(SRC))))

obj/%.o : %.cpp
	$(CCC) $(INCLUDES) $(CCFLAGS) -c $< -o $@

#archive the library
$(OUT):$(OBJ)
	$(CCC) -o $(OUT) $(CCFLAGS) $(OBJ) $(LIBS)

clean:
	rm -f $(OBJ)
	rm -f $(OUT)
	rm -rf obj

