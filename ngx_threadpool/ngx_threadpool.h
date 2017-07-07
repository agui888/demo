/*
 *created on 2015.8.13 by cool
 */
#ifndef NGX_THREADPOOL
#define NGX_THREADPOOL 1

#include <pthread.h>

typedef void (*CB_FUN)(void *);

//任务结构体
typedef struct task
{
	void		*argv; //任务函数的参数（任务执行结束前，要保证参数地址有效）
	CB_FUN		handler; //任务函数（返回值必须为0   非0值用作增加线程，和销毁线程池）
	struct task *next; //任务链指针
}ngx_task_t;

//任务队列
typedef struct task_queue
{
	ngx_task_t *head;  //队列头
	ngx_task_t **tail;	//队列尾
	unsigned int maxtasknum; //最大任务限制
	unsigned int curtasknum; //当前任务数
}ngx_task_queue_t;

//线程池
typedef struct threadpool
{
	pthread_mutex_t    mutex;  //互斥锁
	pthread_cond_t     cond;	//条件锁
	ngx_task_queue_t       tasks;//任务队列

	unsigned int       threadnum; //线程数
	unsigned int       thread_stack_size; //线程堆栈大小
}ngx_threadpool_t;


//配置参数
typedef struct threadpool_conf
{
	unsigned int threadnum;         //线程数
	unsigned int thread_stack_size; //线程堆栈大小
	unsigned int maxtasknum;        //最大任务限制
}ngx_threadpool_conf_t;

//初始化一个线程池
ngx_threadpool_t* ngx_threadpool_init(ngx_threadpool_conf_t *conf);

//添加一个任务
int ngx_threadpool_add_task(ngx_threadpool_t *pool, CB_FUN handler, void* argv);

//销毁线程池
void ngx_threadpool_destroy(ngx_threadpool_t *pool);

//增加一个线程
int ngx_thread_add(ngx_threadpool_t *pool);
//更改最大任务限制
int ngx_set_max_tasknum(ngx_threadpool_t *pool,unsigned int num);
#endif
