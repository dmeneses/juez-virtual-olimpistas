#include "comparator.h"

Comparator::Comparator()
{
}

Comparator::Comparator(const Comparator& orig)
{
}

Comparator::~Comparator()
{
}

template<typename InputIterator1, typename InputIterator2>
bool Comparator::rangeEqual(InputIterator1 first1, InputIterator1 last1, 
                             InputIterator2 first2, InputIterator2 last2)
{
    while (first1 != last1 && first2 != last2)
    {
        if (*first1 != *first2) return false;
        ++first1;
        ++first2;
    }
    return (first1 == last1) && (first2 == last2);
}

bool Comparator::compareFiles(const std::string& filename1, const std::string& filename2)
{
    std::ifstream file1(filename1.c_str());
    std::ifstream file2(filename2.c_str());

    std::istreambuf_iterator<char> begin1(file1);
    std::istreambuf_iterator<char> begin2(file2);

    std::istreambuf_iterator<char> end;

    return rangeEqual(begin1, end, begin2, end);
}