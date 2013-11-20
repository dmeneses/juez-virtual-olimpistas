#include <stdio.h>
#include <stdlib.h>
#include <cmath>

typedef struct Node {
    unsigned long long number;
    struct Node *next;
} Node;
 
typedef Node* Nodeptr;
 
void addNode (Nodeptr *pHeadNodeptr, unsigned long long data) {
    Nodeptr temp;
    temp = (Node *) malloc (sizeof(Node));
    if (temp != NULL)
    {
        temp->number = data;
        temp->next = *pHeadNodeptr;
        *pHeadNodeptr = temp;
    }
 
}
 
int main(void) {
    unsigned long long n;
    Nodeptr head = NULL;
    Nodeptr temp = NULL;
 
    while (scanf("%llu", &n) != EOF)
    {
        addNode(&head, n);
    }
 
    while (head != NULL)
    {
        printf("%.4f\n", sqrt((double) head->number));
        temp = head->next;
        free(head);
        head = temp;
    }
 
    return EXIT_SUCCESS;
}
