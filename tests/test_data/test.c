/* 
 * File:   test.c
 * Author: Daniela Meneses
 *
 * Created on September 28, 2013, 3:03 PM
 */

#include <stdio.h>
#include <stdlib.h>

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

    return (EXIT_SUCCESS);
}

