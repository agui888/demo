/*
 *created on 2015.8.13 by cool
 */

#include "ngx_threadpool.h"
#include <signal.h>
#include <stdlib.h>

#define MAX_TASK_SIZE 99999999
static 	pthread_key_t  key;

int z_conf_check(ngx_threadpool_conf_t *conf);
inline void z_task_queue_init(ngx_task_queue_t* task_queue);
int z_thread_mutex_create(pthread_mutex_t *mutex);

inline void z_thread_mutex_destroy(pthread_mutex_t *mutex);

inline int z_thread_cond_create(pthread_cond_t *cond);
inline void z_thread_cond_destroy(pthread_cond_t *cond);
int z_threadpool_create(ngx_threadpool_t *pool);
void z_threadpool_cycle(void* argv);
void  z_threadpool_exit_cb(void* argv);
inline int z_thread_add(ngx_threadpool_t *pool);
inline void z_change_maxtask_num(ngx_threadpool_t *pool, unsigned int num);
inline int z_thread_key_create();
inline void z_thread_key_destroy();

//检测线程池配置参数是否合法
int z_conf_check(ngx_threadpool_conf_t *conf)
{
	if (conf == NULL){
		return -1;
	}

	if (conf->threadnum < 1){
		return -1;
	}

	if (conf->maxtasknum < 1){
		conf->maxtasknum = MAX_TASK_SIZE;
	}
	return 0;
}


inline void  z_task_queue_init(ngx_task_queue_t* task_queue)
{
	task_queue->head = NULL;
	task_queue->tail = &task_queue->head;
}



//将互斥锁的属性 与 互斥锁进行绑定
int z_thread_mutex_create(pthread_mutex_t *mutex)
{
	int ret = 0;
	pthread_mutexattr_t attr;

	//互斥锁属性的初始化， 重点是这个锁的"属性"
	if (pthread_mutexattr_init(&attr) != 0){
		return -1;
	}

	if (pthread_mutexattr_settype(&attr, PTHREAD_MUTEX_ERRORCHECK) != 0){
		pthread_mutexattr_destroy(&attr);
		return -1;
	}

	ret = pthread_mutex_init(mutex, &attr);//真正的 互斥锁初始化,将属性与锁进行绑定

	pthread_mutexattr_destroy(&attr);//销毁一个属性对象，在重新进行初始化之前该结构不能重新使用
	return ret;
}

//销毁互斥锁
inline void z_thread_mutex_destroy(pthread_mutex_t *mutex)
{
	pthread_mutex_destroy(mutex);
}




//初始化"条件变量"锁
inline int z_thread_cond_create(pthread_cond_t *cond)
{
	return pthread_cond_init(cond, NULL);
}

//销毁"条件变量"锁
inline void z_thread_cond_destroy(pthread_cond_t *cond)
{
	pthread_cond_destroy(cond);
}





int z_threadpool_create(ngx_threadpool_t *pool)
{
	int i = 0;
	pthread_t  pid;
	pthread_attr_t attr;

	if (pthread_attr_init(&attr) != 0){ //对线程的属性对象进行初始化
		return -1;
	}

	if (pool->thread_stack_size != 0)
	{
		//重新设置堆栈大小   [root@localhost edu]#ulimit -s 默认堆栈大小
		//http://www.cnblogs.com/qq78292959/archive/2012/03/29/2423821.html
		if (pthread_attr_setstacksize(&attr, pool->thread_stack_size) != 0){
			pthread_attr_destroy(&attr);
			return -1;
		}
	}

	//创建线程池
	for (; i < pool->threadnum; ++i)
	{
		pthread_create(&pid, &attr, (void *)z_threadpool_cycle,pool);
	}

	pthread_attr_destroy(&attr);//对线程的属性对象进行销毁
	return 0;
}


//给线程池中增加一个工作线程
int z_thread_add(ngx_threadpool_t *pool)
{
	pthread_t  pid;
	pthread_attr_t attr;
	int ret = 0;
	if (pthread_attr_init(&attr) != 0){
		return -1;
	}
	if (pool->thread_stack_size != 0)
	{
		if (pthread_attr_setstacksize(&attr, pool->thread_stack_size) != 0){
			pthread_attr_destroy(&attr);
			return -1;
		}
	}
	ret = pthread_create(&pid, &attr, (void *)z_threadpool_cycle, pool);
	if (ret == 0)
	{
		pool->threadnum++;
	}
	pthread_attr_destroy(&attr);
	return ret;
}

//最大任务限制
inline void z_change_maxtask_num(ngx_threadpool_t *pool, unsigned int num)
{
	pool->tasks.maxtasknum = num;
	if (pool->tasks.maxtasknum < 1)
	{
		pool->tasks.maxtasknum = MAX_TASK_SIZE;
	}
}

//工作线程
void z_threadpool_cycle(void* argv)
{
	unsigned int exit_flag = 0;
	sigset_t set;
	ngx_task_t *ptask = NULL;
	ngx_threadpool_t *pool = (ngx_threadpool_t*)argv;

	//只注册以下致命信号，其他全部屏蔽
	sigfillset(&set);
	sigdelset(&set, SIGILL);
	sigdelset(&set, SIGFPE);
	sigdelset(&set, SIGSEGV);
	sigdelset(&set, SIGBUS);
	
	if (pthread_setspecific(key, (void*)&exit_flag) != 0){//设置exit_flag = 0
		return;
	}
    //用来定义线程的信号掩码
	if (pthread_sigmask(SIG_BLOCK, &set, NULL) != 0){
		return;
	}

	while (!exit_flag) {         //exit_flag为1时线程退出   一直有五个线程在此循环

		if (pthread_mutex_lock(&pool->mutex) != 0){  //加锁
			return;
		}

		while (pool->tasks.head == NULL) {
			if (pthread_cond_wait(&pool->cond, &pool->mutex) != 0){
				pthread_mutex_unlock(&pool->mutex);
				return;
			}
		}
		
		ptask = pool->tasks.head;     //从任务队列中获取一个任务任务节点
		pool->tasks.head = ptask->next;
		pool->tasks.curtasknum--;    //当前任务数--

		if (pool->tasks.head == NULL){
			pool->tasks.tail = &pool->tasks.head;
		}

		if (pthread_mutex_unlock(&pool->mutex) != 0){ //解锁
			return;
		}

		ptask->handler(ptask->argv);  //执行任务。
		free(ptask);
		ptask = NULL;
	}

	pthread_exit(0);
}


/*
void * pthread_getspecific(pthread_key_t *key)函数有1个参数，
 第一个为前面声明的pthread_key_t变量，

返回void *类型的值。下面是前面提到的函数的原型：
 *
 *
 int pthread_setspecific()函数有两个参数，
 第一个为前面声明的pthread_key_t变量，
 第二个为void*变量，这样你可以存储任何类型的值。
 */
//线程池退出函数
void z_threadpool_exit_cb(void* argv)
{
	unsigned int *lock = argv;
	unsigned int *pexit_flag = NULL;
	pexit_flag = (int *)pthread_getspecific(key);
	*pexit_flag = 1;    //将exit_flag置1
	pthread_setspecific(key, (void*)pexit_flag);
	*lock = 0;
	return ;
}

/**
 pthread_key_create(static 	pthread_key_t  *key, NULL)有两个参数，
 第一个参数就是上面声明的pthread_key_t变量，
 第二个参数是一个清理函数，用来在线程释放该线程存储的时候被调用。该函数指针可以设成 NULL,这样系统将调用默认的清理函数。
 该函数成功返回0.其他任何返回值都表示出现了错误。
 */
inline int z_thread_key_create()
{
	return pthread_key_create(&key, NULL);
}

inline void z_thread_key_destroy()
{
	pthread_key_delete(key);
}
