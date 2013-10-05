
#include <sys/stat.h>

struct stat st_info;

bool exist(const char* path)
{
    if (path)
    {
        return (stat(path, &st_info) == 0);
    }

    return false;
}

bool isExecutable(const char* path)
{
    if (path)
    {
        if ((st_info.st_mode & S_IEXEC) != 0)
            return true;
    }

    return false;
}
