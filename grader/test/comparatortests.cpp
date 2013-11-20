#include "gtest/gtest.h"
#include "comparator.h"

#define FILES_EQUAL_1 "resources/equaltest1"
#define FILES_EQUAL_2 "resources/equaltest2"

#define FILES_NON_EQUAL_1 "resources/nonequaltest1"
#define FILES_NON_EQUAL_2 "resources/nonequaltest2"

TEST(Comparator, CompareTwoEqualFiles)
{
    Comparator comparator;
    ASSERT_TRUE(comparator.compareFiles(FILES_EQUAL_1, FILES_EQUAL_2));
}

TEST(Comparator, CompareTwoNonEqualFiles)
{
    Comparator comparator;
    ASSERT_FALSE(comparator.compareFiles(FILES_NON_EQUAL_1, FILES_NON_EQUAL_2));
}
