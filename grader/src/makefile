# source files

SRC = vjgrader.cpp \
    compiler.cpp \
    stageoutput.cpp \
    comparator.cpp \
    executor.cpp

OBJ = $(patsubst %.cpp,obj/%.o,$(SRC))

OUT = libvjgrader.a

#library paths
LIBS = -L/user/local/lib -lm -Lglog

#Compiler flags
CCFLAGS = -fPIC -g -Wall
LDFLAGS = rcs

#compiler
CCC = g++
AR = ar

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
	$(AR) $(LDFLAGS) $(OUT) $(OBJ)

clean:
	rm -f $(OBJ)
	rm -rf obj
	rm libvjgrader.a
