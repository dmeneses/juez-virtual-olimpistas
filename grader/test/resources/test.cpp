/* 
 * File:   test.cpp
 * Author: Daniela Meneses
 *
 * Created on September 28, 2013, 2:57 PM
 */

#include <cstdlib>
#include <stdio.h>

using namespace std;

/*
 * Test file that will compile.
 */
int main(int argc, char** argv)
{
    int size = 0;
    int number = 0;
    scanf("%d", &size);
    
    while (size)
    {
        scanf("%d", &number);
        printf("%d", number * 2);
        size--;
    }

    return 0;
}

