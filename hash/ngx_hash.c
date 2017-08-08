#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#define ngx_hash(key, c)   ((long long unsigned int) key * 31 + c)
#define ngx_tolower(c)      (unsigned char) ((c >= 'A' && c <= 'Z') ? (c | 0x20) : c)
#define ngx_toupper(c)      (unsigned char) ((c >= 'a' && c <= 'z') ? (c & ~0x20) : c)


long long unsigned int ngx_hash_strlow(unsigned char *dst, unsigned char *src, size_t n)
{
	long long unsigned int  key;

    key = 0;

    while (n--) {
        *dst = ngx_tolower(*src);
        key = ngx_hash(key, *dst);
        dst++;
        src++;
    }

    return key;
}

int main()
{
	long long unsigned int hash;
	unsigned char str[] = "helLo World是老大房";
	hash = ngx_hash_strlow(str, str, strlen(str));
    printf("%llu \r\n", hash);
	printf("ddfdfd = %d", 123);
}
