#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "ngx_palloc.h"
#include "ngx_list.h"

static ngx_log_t        ngx_log;
ngx_log_t *  ngx_log_init()
{
    ngx_log.log_level = 6;
    ngx_log.data = "hello world";
    return &ngx_log;
}

typedef struct data
{
	int a ;
	char *str;
}dataMsg;


void dump_pool(ngx_pool_t* pool)
{
    while (pool)
    {
        printf("pool = 0x%x\n", pool);
        printf("  .d\n");
        printf("    .last = 0x%x\n", pool->d.last);
        printf("    .end = 0x%x\n", pool->d.end);
        printf("    .next = 0x%x\n", pool->d.next);
        printf("    .failed = %d\n", pool->d.failed);
        printf("  .max = %d\n", pool->max);
        printf("  .current = 0x%x\n", pool->current);
       // printf("  .chain = 0x%x\n", pool->chain);
        printf("  .large = 0x%x\n", pool->large);
        printf("  .cleanup = 0x%x\n", pool->cleanup);
        printf("  .log = 0x%x\n", pool->log);
        printf("available pool memory = %d\n\n", pool->d.end - pool->d.last);
        pool = pool->d.next;
    }
}

void dump_list_part(ngx_list_t* list, ngx_list_part_t* part)
{
	dataMsg *ptr = (part->elts);
    int i = 0;

    printf("  .part = 0x%x\n", &(list->part));
    printf("    .elts = 0x%x  ", part->elts);
    printf("(");

    for (; i < list->nalloc - 1 ; i++)
    {
        if (ptr){
            printf(" #%d  %s#", ((dataMsg *)ptr)->a, ((dataMsg *)ptr)->str);
        }
        ptr += list->size;
    }

    printf("%d--%p)\n", part->nelts, ptr);

    printf("    .nelts = %d\n", part->nelts);
    printf("    .next = 0x%x", part->next);
    if (part->next)
        printf(" -->\n");
    printf(" \n");
}

void dump_list(ngx_list_t* list)
{
    if (list == NULL)
        return;

    printf("list = 0x%x\n", list);
    printf("  .last = 0x%x\n", list->last);
    printf("  .part = 0x%x\n", &(list->part));
    printf("  .size = %d\n", list->size);
    printf("  .nalloc = %d\n", list->nalloc);
    printf("  .pool = 0x%x\n\n", list->pool);

    printf("elements:\n");

    ngx_list_part_t *part = &(list->part);
    while (part)
    {
        dump_list_part(list, part);
        part = part->next;
        usleep(500);
    }
    printf("\n");
}



void test_funcion(ngx_list_t *list)
{
	int b;
	int i;
	for (i = 0; i < 15; i++)
	{
		dataMsg *ptr = (dataMsg *)ngx_list_push(list);
		ptr->a = 8;
		ptr->str = "hello world";
		printf(" --- i=%d", i);
		usleep(500);
	}
	return ;
}

int main()
{
	ngx_log_t *res_log = ngx_log_init(); //得到日志对象
	ngx_pool_t * res_pool = ngx_create_pool(1024, res_log);
	int i;

	dump_pool(res_pool);
	ngx_list_t *list = ngx_list_create(res_pool, 6, sizeof(dataMsg));

	test_funcion(list);

	printf("--------------------------------\n");
	printf("list链表详细:\n");
	printf("--------------------------------\n");

	dump_list(list);

	printf("--------------------------------\n");
	printf("the pool at the end:\n");
	printf("--------------------------------\n");
	dump_pool(res_pool);


	ngx_destroy_pool(res_pool);
	return 1;
}



//// [root@localhost main]#gcc ngx_main.c ngx_list.h ngx_list.c  ngx_palloc.h ngx_palloc.c -o a.out
