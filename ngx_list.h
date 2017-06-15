
/*
 * Copyright (C) Igor Sysoev
 * Copyright (C) Nginx, Inc.
 */


#ifndef _NGX_LIST_H_INCLUDED_
#define _NGX_LIST_H_INCLUDED_

#include "ngx_palloc.h"

typedef struct ngx_list_part_s  ngx_list_part_t;

// ngx_list_part_s是代表ngx_list_t链表的元素。
// 它自身包含了一个数组elts。
struct ngx_list_part_s {
    void             *elts; //数组元素elts,数组申请的空间大小为size*nalloc
    ngx_uint_t        nelts; //当前已使用的elts个数，一定要小于等于nalloc
    ngx_list_part_t  *next; //指向ngx_list_t中的下个链表元素
};

// ngx_list_t结构是一个链表，链表中每个节点是ngx_list_part_t结构。
// 而ngx_list_part_t中有个elts是一个数组，储存了任意结构（但是大小是固定的）。
// 它是由ngx_pool_t申请的连续空间
typedef struct {
    ngx_list_part_t  *last; //链表中最后一个元素
    ngx_list_part_t   part; //链表中第一个元素
    size_t            size; //链表中每个ngx_list_part_t的elts数组最大占用字节数
    ngx_uint_t        nalloc; //链表中每个ngx_list_part_t的elts数组最大大小
    ngx_pool_t       *pool; //当前list数据存放的内存池
} ngx_list_t;

//ngx_list_create和ngx_list_init功能是一样的都是创建一个list，只是返回值不一样...
ngx_list_t *ngx_list_create(ngx_pool_t *pool, ngx_uint_t n, size_t size);

// ngx_list_init是初始化了一个已有的链表
ngx_int_t ngx_list_init(ngx_list_t *list, ngx_pool_t *pool, ngx_uint_t n, size_t size);


void *ngx_list_push(ngx_list_t *list);






#endif /* _NGX_LIST_H_INCLUDED_ */
