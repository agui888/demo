
/*
 * Copyright (C) Igor Sysoev
 * Copyright (C) Nginx, Inc.
 */

#include "ngx_palloc.h"

static void *ngx_palloc_block(ngx_pool_t *pool, size_t size);
static void *ngx_palloc_large(ngx_pool_t *pool, size_t size);

//使用malloc分配内存空间
void *ngx_alloc(size_t size, ngx_log_t *log){
    void  *p;

    p = malloc(size);
    if (p == NULL) {
        return NULL;
    }

    return p;
}


void *
ngx_memalign(size_t alignment, size_t size, ngx_log_t *log)
{
    void  *p;
    int    err;

    err = posix_memalign(&p, alignment, size);

    if (err) {
        p = NULL;
    }

    return p;
}

//创建内存池
ngx_pool_t * ngx_create_pool(size_t size, ngx_log_t *log)
{
    ngx_pool_t  *p;

    p = ngx_memalign(16, size, log);  // 分配内存函数，uinx,windows分开走
    if (p == NULL) {
        return NULL;
    }

    p->d.last = (u_char *) p + sizeof(ngx_pool_t); //d.last指向 p指针偏移sizeof(ngx_pool_t)长度的位置
    p->d.end = (u_char *) p + size; //p指针结尾
    p->d.next = NULL;
    p->d.failed = 0;

    size = size - sizeof(ngx_pool_t);//944 = 1024-80

    //最大不超过 NGX_MAX_ALLOC_FROM_POOL,也就是getpagesize()-1 大小  18446744073709551615 破天荒的大
    printf("NGX_MAX_ALLOC_FROM_POOL---->:%lu  size ------> %zd\n", NGX_MAX_ALLOC_FROM_POOL, size);
    p->max = (size < NGX_MAX_ALLOC_FROM_POOL) ? size : NGX_MAX_ALLOC_FROM_POOL;

    p->current = p;
    //p->chain = NULL;
    p->large = NULL;
    p->cleanup = NULL;
    p->log = log;

    return p;
}

//销毁内存池
void
ngx_destroy_pool(ngx_pool_t *pool)
{
    ngx_pool_t          *p, *n;
    ngx_pool_large_t    *l;
    ngx_pool_cleanup_t  *c;

    for (c = pool->cleanup; c; c = c->next) {
        if (c->handler) {
        	ngx_log("run cleanup: %p", c);
          //  ngx_log_debug1(NGX_LOG_DEBUG_ALLOC, pool->log, 0, "run cleanup: %p", c);
            c->handler(c->data);
        }
    }

    for (l = pool->large; l; l = l->next) {
    	ngx_log("free: %p", l->alloc);
        //ngx_log_debug1(NGX_LOG_DEBUG_ALLOC, pool->log, 0, "free: %p", l->alloc);
        if (l->alloc) {
            free(l->alloc);
        }
    }

    for (p = pool, n = pool->d.next; /* void */; p = n, n = n->d.next) {
        ngx_log("free: %p, unused: %uz", p, p->d.end - p->d.last);
        free(p);

        if (n == NULL) {
            break;
        }
    }
}


void
ngx_reset_pool(ngx_pool_t *pool)
{
    ngx_pool_t        *p;
    ngx_pool_large_t  *l;

    for (l = pool->large; l; l = l->next) {
        if (l->alloc) {
            free(l->alloc);
        }
    }

    pool->large = NULL;

    for (p = pool; p; p = p->d.next) {
        p->d.last = (u_char *) p + sizeof(ngx_pool_t);
    }
}

/////分配内存就用这个  函数ngx_palloc_block(pool, size)， ngx_palloc_large(pool, size);都为辅助
void *
ngx_palloc(ngx_pool_t *pool, size_t size)
{
    u_char      *m;
    ngx_pool_t  *p;

    if (size <= pool->max) {
        p = pool->current;
        do {
            m = ngx_align_ptr(p->d.last, NGX_ALIGNMENT); // 对齐内存指针，加快存取速度

            if ((size_t) (p->d.end - m) >= size) {
                p->d.last = m + size;
                return m;
            }

            p = p->d.next;

        } while (p);

        return ngx_palloc_block(pool, size);
    }
    return ngx_palloc_large(pool, size);
}

//与ngx_palloc函数一样 只是不对齐处理
void *
ngx_pnalloc(ngx_pool_t *pool, size_t size)
{
    u_char      *m;
    ngx_pool_t  *p;

    if (size <= pool->max) {

        p = pool->current;

        do {
            m = p->d.last;

            if ((size_t) (p->d.end - m) >= size) {
                p->d.last = m + size;

                return m;
            }

            p = p->d.next;

        } while (p);

        return ngx_palloc_block(pool, size);
    }

    return ngx_palloc_large(pool, size);
}


static void *
ngx_palloc_block(ngx_pool_t *pool, size_t size)
{
    u_char      *m;
    size_t       psize;
    ngx_pool_t  *p, *new, *current;

    psize = (size_t) (pool->d.end - (u_char *) pool);

    m = ngx_memalign(16, psize, pool->log);
    if (m == NULL) {
        return NULL;
    }

    new = (ngx_pool_t *) m;

    new->d.end = m + psize;
    new->d.next = NULL;
    new->d.failed = 0;

    m += sizeof(ngx_pool_data_t); //腾出 头部80个字节空间   m指向 即将需要的空间
    m = ngx_align_ptr(m, NGX_ALIGNMENT);
    new->d.last = m + size; // new->d.last继续指向下一个 需要分配的空间

    current = pool->current;
    p = current;

    for (; p->d.next;) {
        if (p->d.failed++ > 4) {
            current = p->d.next;
        }
        p = p->d.next;// current->d.next
    }

    p->d.next = new; //这里的p已经变了 本质上是  (pool->current)->d.next->d.next = new;

    pool->current = current ? current : new;

    return m;
}


//控制大块内存的申请
//返回 malloc()函数结果
static void *
ngx_palloc_large(ngx_pool_t *pool, size_t size)
{
    void              *p;
    ngx_uint_t         n;
    ngx_pool_large_t  *large;

    p = ngx_alloc(size, pool->log);
    if (p == NULL) {
        return NULL;
    }

    n = 0;
    large = pool->large;

    for (; large; ) {
        if (large->alloc == NULL) {
            large->alloc = p;
            return p;
        }

        if (n++ > 3) {/*链表不能超过四个*/
            break;
        }
        large = large->next;
    }

    /*超过四个,构建新的大内存块链块*/
    large = ngx_palloc(pool, sizeof(ngx_pool_large_t));
    if (large == NULL) {
        free(p);
        return NULL;
    }

    large->alloc = p;
    large->next = pool->large;
    pool->large = large;

    return p;
}


//返回  memalign()函数结果
void *
ngx_pmemalign(ngx_pool_t *pool, size_t size, size_t alignment)
{
    void              *p;
    ngx_pool_large_t  *large;

    p = ngx_memalign(alignment, size, pool->log);
    if (p == NULL) {
        return NULL;
    }

    large = ngx_palloc(pool, sizeof(ngx_pool_large_t));
    if (large == NULL) {
        free(p);
        return NULL;
    }

    large->alloc = p;
    large->next = pool->large;
    pool->large = large;

    return p;
}

//控制大块内存的释放。注意，这个函数只会释放大内存，不会释放其对应的头部结构，遗留下来的头部结构会做下一次申请大内存之用
//显然是释放由ngx_palloc_large函数分配后的内存
ngx_int_t
ngx_pfree(ngx_pool_t *pool, void *p)
{
    ngx_pool_large_t  *l;

    for (l = pool->large; l; l = l->next) {
        if (p == l->alloc) {
        	ngx_log("free: %p", l->alloc);
            //ngx_log_debug1(NGX_LOG_DEBUG_ALLOC, pool->log, 0,  "free: %p", l->alloc);
            free(l->alloc);
            l->alloc = NULL;

            return 0;
        }
    }

    return -5;
}

//同 ngx_palloc 函数 区别是本函数多了 memset 初始化
void *
ngx_pcalloc(ngx_pool_t *pool, size_t size)
{
    void *p;

    p = ngx_palloc(pool, size);
    if (p) {
    	memset(p, 0, size);
    }

    return p;
}

//注册cleanup回叫函数（结构体）
ngx_pool_cleanup_t *
ngx_pool_cleanup_add(ngx_pool_t *p, size_t size)
{
    ngx_pool_cleanup_t  *c;

    c = ngx_palloc(p, sizeof(ngx_pool_cleanup_t));
    if (c == NULL) {
        return NULL;
    }

    if (size) {
        c->data = ngx_palloc(p, size);
        if (c->data == NULL) {
            return NULL;
        }

    } else {
        c->data = NULL;
    }

    c->handler = NULL;
    c->next = p->cleanup;

    p->cleanup = c;

    ngx_log("add cleanup: %p", c);
    //ngx_log_debug1(NGX_LOG_DEBUG_ALLOC, p->log, 0, "add cleanup: %p", c);
    return c;
}


void
ngx_pool_run_cleanup_file(ngx_pool_t *p, int fd)
{
    ngx_pool_cleanup_t       *c;
    ngx_pool_cleanup_file_t  *cf;

    for (c = p->cleanup; c; c = c->next) {
        if (c->handler == ngx_pool_cleanup_file) {

            cf = c->data;

            if (cf->fd == fd) {
                c->handler(cf);
                c->handler = NULL;
                return;
            }
        }
    }
}


void
ngx_pool_cleanup_file(void *data)
{
    ngx_pool_cleanup_file_t  *c = data;

    ngx_log("file cleanup: fd:%d", c->fd);
    //ngx_log_debug1(NGX_LOG_DEBUG_ALLOC, c->log, 0, "file cleanup: fd:%d", c->fd);

    if (close(c->fd) == -1) {
    	 ngx_log(" \"%s\" failed", c->name);
       // ngx_log_error(NGX_LOG_ALERT, c->log, ngx_errno,  ngx_close_file_n " \"%s\" failed", c->name);
    }
}


void
ngx_pool_delete_file(void *data)
{
    ngx_pool_cleanup_file_t  *c = data;
    int  err;
   // ngx_log_debug2(NGX_LOG_DEBUG_ALLOC, c->log, 0, "file cleanup: fd:%d %s",  c->fd, c->name);
    ngx_log("file cleanup: fd:%d %s",  c->fd, c->name);
    if (unlink(c->name) == -1) {
        err = errno;

        if (err != ENOENT) {
        	ngx_log(" \"%s\" failed", c->name);
            //ngx_log_error(NGX_LOG_CRIT, c->log, err, ngx_delete_file_n " \"%s\" failed", c->name);
        }
    }

    if (close(c->fd) == -1) {
    	ngx_log(" \"%s\" failed", c->name);
        //ngx_log_error(NGX_LOG_ALERT, c->log, ngx_errno,  ngx_close_file_n " \"%s\" failed", c->name);
    }
}







