#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include "ngx_palloc.h"

#ifndef DD_LOG
#define DD_LOG 1
#define dd(...) fprintf(stderr, "c *** %s: ", __func__); \
            fprintf(stderr, __VA_ARGS__); \
            fprintf(stderr, " at %s line %d.\n", __FILE__, __LINE__)
#endif


static ngx_log_t ngx_log;
ngx_log_t *  ngx_log_init()
{
    ngx_log.log_level = 6;
    ngx_log.data = "hello world";
    return &ngx_log;
}

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

int main()
{
	ngx_log_t *res_log = ngx_log_init(); //得到日志对象
	ngx_pool_t * res_pool = ngx_create_pool(1024, res_log);

	item_init();
	int i;
	for (i = 0; i< 100; i++)
	{
		item *it = ngx_pcalloc(res_pool, sizeof(item) );
		it->data = 100*i;
		it->slabs_clsid = i%10;
		item_link_q(it);
	}

    item_unlink_q(footers[1]);//删除最后一个,   footers 中每个元素都是某个双向链表的最后一个

    char *buffer = ngx_pcalloc(res_pool, 4096);
    item_stats(buffer, 4096);

    dd("\r\n%s\r\n" ,buffer);

    ngx_destroy_pool(res_pool);
}
