#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#ifndef DD_LOG
#define DD_LOG 1
#define dd(...) fprintf(stderr, "c *** %s: ", __func__); \
            fprintf(stderr, __VA_ARGS__); \
            fprintf(stderr, " at %s line %d.\n", __FILE__, __LINE__)
#endif

typedef struct _stritem {
    struct _stritem *next;
    struct _stritem *prev;
    int             slabs_clsid;     /* size of data */
    int data;
} item;

static item *headers[255];//headers数组中有 item结构体元素
static item *footers[255];//footers数组中有 item结构体元素
unsigned int sizes[255];


void item_init(void) {
    int i;
    for(i=0; i<255; i++) {
    	headers[i]=0;
        footers[i]=0;
        sizes[i]=0;
    }
    return;
}

void item_stats(char *buffer, int buflen) {
    char *bufcurr = buffer;
    if (buflen < 4096) {
        strcpy(buffer, "SERVER_ERROR out of memory");
        return;
    }
    item * res;
    int i;
    for (i=0; i<255; i++)
    {
        if (footers[i])
        {
        	res = footers[i];
        	while(res)
        	{
        		 bufcurr += sprintf(bufcurr, "第%u号桶的链表 data:%u \r\n", i,  res->data);
        		 res = res->prev;
        	}
        }
    }
    strcpy(bufcurr, "END");
    return;
}

void item_link_q(item *it) {
    item **headres = &headers[it->slabs_clsid];//static item *
    item **tailres = &footers[it->slabs_clsid];//static item *

    it->prev = 0;
    it->next = *headres;//上一次 的item指针

    if (it->next)//上一次 的item指针 下的prev指向本次
    {
    	it->next->prev = it;
    }
    *headres = it; //it->next = it
    if (*tailres == 0) //只有第一次进来为0
    {
    	*tailres = it;
    }
    sizes[it->slabs_clsid]++;
    return;
}

void item_unlink_q(item *it)
{
    item **headres = &headers[it->slabs_clsid];
    item **footeres = &footers[it->slabs_clsid];

    if (*headres == it) {
        *headres = it->next;
    }

    if (*footeres == it) {
        *footeres = it->prev;//比如删除最后一个item 将自己的上以及 2 号 prev指向*tailres
    }

    if (it->next)
    {
    	(it->next)->prev = it->prev; //比如删除第二个item 将自己的下一级 1 号prev指向3号
    }

    if (it->prev)
    {
    	(it->prev)->next = it->next;//比如删除第二个item 将自己的上一级 3 号next指向1号
    }
    sizes[it->slabs_clsid]--;
    return;
}

int main()
{
	item_init();
	item *it = malloc(sizeof(item) );
    it->data = 100;
    it->slabs_clsid = 1;
    item_link_q(it);

	item *jt = (item *)malloc(sizeof (item));
    jt->data = 101;
    jt->slabs_clsid = 1;
    item_link_q(jt);

    item *kt = (item *)malloc(sizeof (item));
    kt->data = 102;
    kt->slabs_clsid = 1;
    item_link_q(kt);

    //item_unlink_q(footers[1]);//删除最后一个,   footers 中每个元素都是某个双向链表的最后一个
	item *lt = (item *)malloc(sizeof (item));
	lt->data = 103;
	lt->slabs_clsid = 2;
    item_link_q(lt);

    char buffer[4096];
    item_stats(buffer, 4096);
    dd("\r\n%s\r\n" ,buffer);
    free(it);
    free(jt);
    free(kt);
    free(lt);
}
