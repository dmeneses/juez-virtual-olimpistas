/* 
 * File:   constraint.h
 * Author: dann
 *
 * Created on October 30, 2013, 11:36 AM
 */

#ifndef CONSTRAINT_H
#define	CONSTRAINT_H
#include <ostream>

struct Constraint
{

    Constraint(int time, int memory) : time(time), memory(memory)
    {
    }

    Constraint()
    {
        time = 0;
        memory = 0;
    }

    double time;
    int memory;

    friend std::ostream& operator<<(std::ostream& os, const Constraint& dt);
};

inline std::ostream& operator<<(std::ostream& os, const Constraint& obj)
{
    os << "TIME_CONTRAINT:" << obj.time << "-" <<
            "MEMORY_CONSTRAINT:" << obj.memory;
    return os;
}

#endif	/* CONSTRAINT_H */

