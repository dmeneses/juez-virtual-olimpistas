# source files

SRC = main.cpp

OBJ = $(patsubst %.cpp,obj/%.o,$(SRC))

OUT = vjgraderapp

INCLUDES=-I ../src

LIBS = -L/user/local/lib -L../src -lm -lvjgrader

#Compiler flags
CCFLAGS = -fPIC -g -Wall
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

.PHONY: clean    
clean:
	rm -f $(OBJ)
	rm -f $(OUT)
	rm -rf obj
