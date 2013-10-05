/* 
 * File:   Comparator.h
 * Author: Daniela Meneses
 *
 * Created on September 30, 2013, 8:47 AM
 */

#ifndef COMPARATOR_H
#define	COMPARATOR_H

#include <string>
#include <algorithm>
#include <iterator>
#include <fstream>

/**
 * Compare two file checking file content.
 */
class Comparator
{
public:
    Comparator();
    Comparator(const Comparator& orig);
    virtual ~Comparator();
    bool compareFiles(const std::string& filename1, const std::string& filename2);

private:
    template<typename InputIterator1, typename InputIterator2>
    bool rangeEqual(InputIterator1 first1, InputIterator1 last1, InputIterator2 first2, InputIterator2 last2);
};

#endif	/* COMPARATOR_H */

