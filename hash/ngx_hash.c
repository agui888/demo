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


//经典款hash
unsigned int hash(char *str)
{
    register unsigned int h;
    register unsigned char *p;

	p = (unsigned char *)str;

    for(h=0; *p ; p++)
    {
       h = 31 * h + *p;
    }
    return h;
}


/* openssl中的哈希
 The following hash seems to work very well on normal text strings
 no collisions on /usr/dict/words and it distributes on %2^n quite
 well, not as good as MD5, but still good.
*/
unsigned long lh_strhash(const char *c)
{
	unsigned long ret=0;
	long n;
	unsigned long v;
	int r;

	if ((c == NULL) || (*c == '\0'))
	    return(ret);

	/*
	unsigned char b[16];
	MD5(c,strlen(c),b);
	return(b[0]|(b[1]<<8)|(b[2]<<16)|(b[3]<<24));
	*/

	n=0x100;
	while (*c)
	{
		v = n|(*c);
		n += 0x100;
		r = (int)((v>>2)^v)&0x0f;
		ret = (ret(32-r));
		ret &= 0xFFFFFFFFL;
		ret ^= v*v;
		c++;
	}

	return((ret>>16)^ret);
}

//php字符串Hash函数
static unsigned long hashpjw(char *arKey, unsigned int nKeyLength)
{
	unsigned long h = 0, g;
	char *arEnd=arKey+nKeyLength;

	while (arKey < arEnd)
	{
		h = (h << 4) + *arKey++;
		if ((g = (h & 0xF0000000)))
		{
			h = h ^ (g >> 24);
			h = h ^ g;
		}
	}
	return h;
}


int main()
{
	long long unsigned int hash;
	unsigned char str[] = "helLo World是老大房";
	hash = ngx_hash_strlow(str, str, strlen(str));
    printf("%llu \r\n", hash);
	printf("ddfdfd = %d", 123);


    char *s;
    s = malloc(1024);
    scanf("%s",s);
    printf("--> %zu <-- \r\n", hash(s));

}
