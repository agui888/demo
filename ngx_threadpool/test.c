#include <stdio.h>
#include <pthread.h>
#include "ngx_threadpool.h"


void testfun(void *argv)
{
	int *num = (int *)argv;
	printf("testfun threadid = %u  num = %d\n",pthread_self(),*num);
	//sleep(3);
	return  ;
}

int main()
{
	int array[1000] = {0};
	int i = 0;
	ngx_threadpool_conf_t conf = {5, 0, 200}; //实例化启动参数

	ngx_threadpool_t *pool = ngx_threadpool_init(&conf);//初始化线程池

	if (pool == NULL) return 0;

	for (; i < 1000; i++)
	{
		array[i] = i;

		if (i == 500) {
			ngx_thread_add(pool); //增加线程
			ngx_thread_add(pool);
		}
		
		if (i == 800) {
			ngx_set_max_tasknum(pool, 0); //改变最大任务数   0为不做上限
		}

		if (ngx_threadpool_add_task(pool, testfun, &array[i]) == 0) {
			continue; //执行任务执行成功后 进入下一次循环
		}

		printf("error in i = %d\n",i);
	}

	ngx_threadpool_destroy(pool);


	return 0;
}
