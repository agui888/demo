<?php
echo <<<END

    
ngx_sys_errlist        
        ngx_sys_errlist[0].len = len;
        ngx_sys_errlist[0].data = p;
    
ngx_max_sockets = -1; 
 
static ngx_atomic_t      0;

ngx_linux_kern_ostype[50] ==  Linux 
ngx_linux_kern_osrelease[50]  == 2.6.32-220.el6.x86_64  

ngx_max_module = 30 ngx_modules.c中的模块数

ngx_os_io_t ngx_os_io = {
    ngx_unix_recv,
    ngx_readv_chain,
    ngx_udp_unix_recv,
    ngx_unix_send,
    ngx_writev_chain,
    0
};   
ngx_os_io = ngx_linux_io;
 

 ngx_os_argv_last  == 指针
 
 ngx_pagesize      == 18446744073709551615
 ngx_cacheline_size = NGX_CPU_CACHE_LINE = 64;
 ngx_ncpu          == 当前cpu核数
 

        

volatile ngx_msec_t      1495592688* 1000 + 0.36770200;//相当于 unsigned long int ngx_current_msec;
volatile ngx_time_t     *ngx_cached_time;
                                    {
                                        time_t      sec = 1495592688;
                                        ngx_uint_t  msec = 0.36770200;
                                        ngx_int_t   gmtoff;
                                    }
volatile ngx_str_t       ngx_cached_err_log_time; 
                                              { size= .....
                                               data = “2012/07/27 09:09:17”
                                               } 
volatile ngx_str_t       ngx_cached_http_time;
                                              { size= .....
                                               data = "Fri, 27 Jul 2012 01:09:17 GMT"
                                               } 
volatile ngx_str_t       ngx_cached_http_log_time;
                                              { size= .....
                                               data = “27/Jul/2012:09:09:17 +0800”
                                               } 
volatile ngx_str_t       ngx_cached_http_log_iso8601;  
                                              { size= .....
                                               data = “27/Jul/2012:09:09:17 +0800”
                                               } 

                                               
 ngx_pid = ngx_getpid();
 
 log  ===  {
                ngx_uint_t           log_level  = 6;
                ngx_open_file_t     *file;  = {
                                                ngx_fd_t              fd;  // open("/usr/local/nginx/logs/error.log")
                                                ngx_str_t             name;//   "/usr/local/nginx/logs/error.log"
                                            
                                                u_char               *buffer;
                                                u_char               *pos;
                                                u_char               *last;
                                            }ngx_open_file_s;
                
                  
                ngx_atomic_uint_t    connection; 
                ngx_log_handler_pt   handler;
                void                *data; 
                char                *action;
             }ngx_log_s;
 
 //////旧的 /////   
  
volatile ngx_cycle_t  *ngx_cycle; 
ngx_cycle_t      *cycle, init_cycle;
init_cycle.log = log;//栈区
init_cycle.pool = ngx_create_pool(1024, log);


init_cycle.conf_prefix.len = len;
init_cycle.conf_prefix.data = p;
init_cycle.prefix.len = len;
init_cycle.prefix.data = p;
ngx_cycle = &init_cycle;
 
ngx_cycle = &init_cycle   == { 
            void                  ****conf_ctx;  
            ngx_pool_t               *pool;   #######被赋值############### ngx_create_pool(1024, log);   ngx_pool_s {
                                                                                                    ngx_pool_data_t       d;
                                                                                                    size_t                max;   
                                                                                                    ngx_pool_t           *current;  
                                                                                                    ngx_chain_t          *chain;  
                                                                                                    ngx_pool_large_t     *large;  
                                                                                                    ngx_pool_cleanup_t   *cleanup;  
                                                                                                    ngx_log_t            *log;
                                                                                                } 
            ngx_log_t                *log;    ######################被赋值#####################   
            ngx_log_t                 new_log;  
            ngx_connection_t        **files;     
            ngx_connection_t         *free_connections; 
            ngx_uint_t                free_connection_n;     
            ngx_queue_t               reusable_connections_queue;   
            ngx_array_t               listening;   
                                            listening->nelts = 0;
                                            listening->size = sizeof(ngx_listening_t);
                                            listening->nalloc = 10;
                                            listening->pool = cycle->pool;
                                            listening->elts = ngx_palloc(cycle->pool, 10 * sizeof(ngx_listening_t));        
         
            ngx_array_t               pathes;      
            ngx_list_t                open_files;  
            ngx_list_t                shared_memory;    
            ngx_uint_t                connection_n;    
            ngx_uint_t                files_n;      
            ngx_connection_t         *connections;    
            ngx_event_t              *read_events;    
            ngx_event_t              *write_events;   
            ngx_cycle_t              *old_cycle;   
            // 配置文件相对于安装目录的路径名称
            ngx_str_t                 conf_file;    ###/usr/local/nginx/conf/nginx.conf###########被赋值#####################  
            // nginx 处理配置文件时需要特殊处理的在命令行携带的参数，一般是-g 选项携带的参数     
            ngx_str_t                 conf_param;    ######################被赋值##################### 
            // nginx配置文件所在目录的路径
            ngx_str_t                 conf_prefix;  ######conf/#######被赋值#####################  
            //nginx安装目录的路径    
            ngx_str_t                 prefix;       #####"/usr/local/nginx/"###########被赋值#####################  
                
            ngx_str_t                 lock_file;    
            ngx_str_t                 hostname;   
        }
        
 
//////新的 /////   ngx_cycle_t         *cycle
struct ngx_cycle_s { 
    void                  ****conf_ctx; ====>ngx_pcalloc(pool, 44 * 4);
    // 内存池
    ngx_pool_t            *pool;       ====> ngx_create_pool(NGX_CYCLE_POOL_SIZE, log);   
    ngx_log_t             *log;        ====>  old_cycle->log 
    ngx_log_t             new_log;    ====>  new_log.log_level = NGX_LOG_ERR; 
    
    ngx_connection_t      **files;
    ngx_connection_t      *free_connections; 
    ngx_uint_t            free_connection_n;    
    ngx_queue_t           reusable_connections_queue;   ===> ngx_queue_s {
                                                                ngx_queue_t  *prev;   ==>reusable_connections_queue//前一个
                                                               ngx_queue_t  *next;   ==>reusable_connections_queue//下一个
                                                          } 
    ngx_array_t           listening;   ====> 
                                        ->pathes.elts   =  ngx_pcalloc(pool, n * sizeof(ngx_listening_t)); 
                                                ngx_listening_t *ls = pathes.elts
                                                               ls[0].fd = s;
                                                               ls[0].addr_text  ="0.0.0.0:8080"
                                                               ls[0].listen = 1;
                               
                                        ->pathes.nelts = 0;
                                        ->pathes.size = sizeof(ngx_listening_t *);
                                        ->pathes.nalloc = 10;
                                        ->pathes.pool = pool; 
                                        
    ngx_array_t          pathes;   ====> 
                                        ->pathes.elts = ngx_pcalloc(pool, n * sizeof(ngx_path_t *));        
                                        ->pathes.nelts = 0
                                        ->pathes.size = sizeof(ngx_path_t *);
                                        ->pathes.nalloc = 10;
                                        ->pathes.pool = pool; 
                                        
    ngx_list_t           open_files;       
                                        open_files->part.elts = ngx_palloc(pool, 20 * sizeof(ngx_open_file_t)); 
                                                 (open_files->part.elts)[0].name.data = "/usr/local/nginx//logs/access.log"  
                                                 (open_files->part.elts)[1].name.data = "/usr/local/nginx//logs/error.log"   

                                        open_files->part.nelts = 0; //刚分配下来，还没使用，所以为0
                                        open_files->part.next = NULL;
                                        open_files->last = &list->part; //last开始的时候指向首节点
                                        open_files->size = sizeof(ngx_open_file_t);
                                        open_files->nalloc = 20;
                                        open_files->pool = pool; 
    ngx_list_t          shared_memory;   
                                        shared_memory->part.elts = ngx_palloc(pool, 20 * sizeof(ngx_shm_zone_t)); 
                                      
                                        ngx_shm_zone_t *shm_zone = shared_memory->part.elts
                                              shm_zone[0].shm.name
                                              shm_zone[0].shm.size
                                              shm_zone[0].shm.log =cycle->log
                                              shm_zone[0].shm.addr
                                              
                                        shared_memory->part.nelts = 0; //刚分配下来，还没使用，所以为0
                                        shared_memory->part.next = NULL;
                                        shared_memory->last = &list->part; //last开始的时候指向首节点
                                        shared_memory->size = sizeof(ngx_shm_zone_t);
                                        shared_memory->nalloc = 20;
                                        shared_memory->pool = pool;
                                        
                                        
    ngx_uint_t                connection_n;    
    ngx_uint_t                files_n;      
    ngx_connection_t         *connections;    
    ngx_event_t              *read_events;    
    ngx_event_t              *write_events;   
    ngx_cycle_t              *old_cycle;       ===>  old_cycle 
    ngx_str_t                 conf_file;      ===>conf_file.len = old_cycle->conf_file.len;
                                              ===>conf_file.data = ngx_pnalloc(pool, old_cycle->conf_file.len + 1);

    ngx_str_t                 conf_param;    ===> ->conf_param.len = old_cycle->conf_param.len;
                                                 ->conf_param.data = ngx_pstrdup(pool, &old_cycle->conf_param);
    
    ngx_str_t                 conf_prefix; ===> conf_prefix.len  old_cycle->conf_prefix.len
                                           ===> conf_prefix.data  ngx_pstrdup(pool, &old_cycle->conf_prefix)
    
    ngx_str_t                 prefix;   ===>  cycle->prefix.len = old_cycle->prefix.len;
                                        ===>  cycle->prefix.data = ngx_pstrdup(pool, &old_cycle->prefix);
    ngx_str_t                 lock_file;
     
    ngx_str_t                 hostname; ===>  cycle->hostname.len = ngx_strlen(hostname); 
                                        ===>  cycle->hostname.data = ngx_pnalloc(pool, cycle->hostname.len);
}; 
    


//代表文件结构
struct ngx_file_s {
    ngx_fd_t                   fd;  //文件描述符
    ngx_str_t                  name; //文件名称
    ngx_file_info_t            info;  //文件大小等信息，实际上就是linux系统定义的stat结构

    //该偏移量告诉nginx现在处理到文件何处了，一般不用手动设置
    off_t                      offset;
    //当前文件系统偏移量，一般不用设置
    off_t                      sys_offset;

    //日志对象，相关的日志会输出到log指定的日志文件中
    ngx_log_t                 *log;

#if (NGX_HAVE_FILE_AIO)
    ngx_event_aio_t           *aio;
#endif

    //目前未使用
    unsigned                   valid_info:1;
    //与配置文件中的directio配置项对应，在发送大文件的时候可以设置为1
    unsigned                   directio:1;
};




// ngx_buf_s是nginx用于处理大数据的关键数据结构
// 它既应用于内存数据，也应用于磁盘数据。
struct ngx_buf_s {
    // 处理内存数据
    u_char          *pos;       //告知需要处理的内存数据的起始位置
    u_char          *last;      //告知需要处理的内存数据的结束位置，即pos到last是希望处理的数据

    // 处理文件数据
    off_t            file_pos;  //告知需要处理的文件数据的起始位置
    off_t            file_last; //告知需要处理的文件数据的结束位置

    // 处理内存数据
    u_char          *start;      //处理的内存的起始地址，这个和pos不同的是pos会大于start
    u_char          *end;        //处理的内存的结束位置，这个和last不同的是last会小于end
    ngx_buf_tag_t    tag;        //当前缓冲区的类型。例如由哪个模块使用，就指向这个模块ngx_module_t变量的地址
    ngx_file_t      *file;       //文件数据所引用的文件

    // 当前缓冲区的影子缓冲区，这个成员很少使用到。
    ngx_buf_t       *shadow;

    /* the buf's content could be changed */
    //临时内存标志位，1表示数据在临时内存中，且这段数据可以修改
    unsigned         temporary:1;

    /*
     * the buf's content is in a memory cache or in a read only memory
     * and must not be changed
     */
    //内存标志位，1表示数据在内存中，且这段数据不能被修改
    unsigned         memory:1;

    /* the buf's content is mmap()ed and must not be changed */
    // 标志位，1表示这段内存是用mmap系统调用映射过来的，不可以被修改
    unsigned         mmap:1; 

    unsigned         recycled:1;    //标志位，1表示可以被回收
    unsigned         in_file:1;     //标志位，1表示是处理文件数据，而不是内存数据
    unsigned         flush:1;       //标志位，1表示需要执行flush操作

    //标志位，对于操作这个缓冲区时是否使用同步方式，需要谨慎考虑。
    //这有可能会阻塞nginx进程，nginx中所有操作几乎都是异步的。
    unsigned         sync:1; 

    // 标志位，是否是最后一块缓冲区。nginx_buf_t可以由ngx_chain_t链表串联起来
    // 1代表是最后一块待处理的缓冲区
    unsigned         last_buf:1; 

    //标志位，是否是ngx_chain_t中的最后一块缓冲区
    unsigned         last_in_chain:1; 

    //标志位，是否是最后一个影子缓冲区，与shadow配合使用，通常不建议使用
    unsigned         last_shadow:1;

    //标志位，是否属于临时文件
    unsigned         temp_file:1;

    /* STUB */ int   num;
};


typedef struct {
    ngx_file_t            file;    //var.php文件上上个位置
    ngx_buf_t            *buffer;  //var.php上个位置
    ngx_uint_t            line;
} ngx_conf_file_t;    
    























    
    

 conf ===== ngx_conf_s {
    char                 *name;  //没有使用
    ngx_array_t          *args;  ##########被赋值####  {
                                            elts = ngx_palloc(p, n * size);//从p中分配 10*sizeof(ngx_str_t) 个字节内存  ...所有指令存在这里
                                            nelts = 0;
                                            size = sizeof(ngx_str_t);
                                            nalloc = 10;
                                            pool = p;
                                        }  

    ngx_cycle_t          *cycle;  ##########被赋值###cycle==>cycle  //指向系统参数，在系统整个运行过程中，
    //需要使用的一些参数、资源需要统一的管理
    ngx_pool_t           *pool;  ##########被赋值# pool ==> ngx_create_pool(16384, log) ###//内存池
    ngx_pool_t           *temp_pool; ##########被赋值###ngx_create_pool(16384, log) //分配临时数据空间的内存池
                                      
     
    ngx_conf_file_t      *conf_file; ##########被赋值##ngx_conf_param(ngx_conf_t *cf)函数下 cf->conf_file = &conf_file;//配置文件的信息
                          conf_file->buffer = &buf;
                                            buf.start = ngx_alloc(NGX_CONF_BUFFER, cf->log);//分配新空间 给buf.start
                                            buf.pos = buf.start;
                                            buf.last = buf.start;
                                            buf.end = buf.last + NGX_CONF_BUFFER; //指向最后
                                            buf.temporary = 1;
                          conf_file->file.fd = ngx_open_file(filename->data, NGX_FILE_RDONLY, NGX_FILE_OPEN, 0);;
                          conf_file->file.info = ngx_fd_info(fd, &cf->conf_file->file.info)
                          conf_file->file.name.len = filename->len;
                          conf_file->file.name.data = filename->data;
                          conf_file->file.offset = 0
                          conf_file->file.log = cf->log;
                          conf_file->line = 1
                          conf_file->file={{
                                            fd=2004317952,
                                            name={
                                                len=7146765543014492275,
                                                data=0x6e<Address0x6eoutofbounds>
                                            },
                                            info={
                                                st_dev=64768,
                                                st_ino=396242,
                                                st_nlink=1,
                                                st_mode=33261,
                                                st_uid=0,
                                                st_gid=0,
                                                __pad0=0,
                                                st_rdev=0,
                                                st_size=2680,
                                                st_blksize=4096,
                                                st_blocks=8,
                                                st_atim={
                                                    tv_sec=1502935169,
                                                    tv_nsec=619527997
                                                },
                                                st_mtim={
                                                    tv_sec=1487572364,
                                                    tv_nsec=775522529
                                                },
                                                st_ctim={
                                                    tv_sec=1487572364,
                                                    tv_nsec=776522638
                                                },
                                                __unused={
                                                    0,
                                                    0,
                                                    0
                                                }
                                            },
                                            offset=4095,
                                            sys_offset=140737338793704,
                                            log=0x1,
                                            valid_info=0,
                                            directio=0
                                        },
                                        buffer=0x7ffff6e48b1c,
                                        line=140737488347200
                                      }
    
                          
    ngx_log_t            *log; ##########被赋值###old_cycle->log//日志

    void                 *ctx;  ##########被赋值## cycle->conf_ctx;#//模块的配置信息
    ngx_uint_t            module_type;  ##########被赋值##0x45524F43##//当前指令的类型
    ngx_uint_t            cmd_type;##########被赋值# 0x01000000//命令的类型

    ngx_conf_handler_pt   handler; //指令处理函数，有自己行为的在这里实现
    char                 *handler_conf; //指令处理函数的配置信息
};
   


p ngx_modules[9]->commands[1]->name.data


###################################http模块执行流程  #################################################


    // 计算模块个数，并且设置各个模块顺序（索引）
    ngx_max_module = 0;
    for (i = 0; ngx_modules[i]; i++) {
    	// 这里面的ngx_modules会有非常多的模块，[ngx_core_module,ngx_errlog_module,ngx_conf_moduel...]
        ngx_modules[i]->index = ngx_max_module++;
    }

    
    
   /*这里只对核心模块进行处理，核心模块只有如下:
    ngx_core_module，ngx_errlog_module，ngx_events_module，ngx_http_module，
	*/
    for (i = 0; ngx_modules[i]; i++) {
        if (ngx_modules[i]->type != NGX_CORE_MODULE) {
            continue;
        }
        module = ngx_modules[i]->ctx;
        if (module->create_conf) {
        	//对每个模块调用模块内部的钩子ngx_xxx_module_create_conf，当然第一个模块是core
            rv = module->create_conf(cycle);
            if (rv == NULL) {
                ngx_destroy_pool(pool);
                return NULL;
            }
            cycle->conf_ctx[ngx_modules[i]->index] = rv;
        }
    }
    

    
     ngx_command_t  *cmd;
    for (i = 0; ngx_modules[i]; i++) {
        if (ngx_modules[i]->type != NGX_CONF_MODULE && ngx_modules[i]->type != cf->module_type)
        {
            continue;
        }
        cmd = ngx_modules[i]->commands;
        dd("第 %zu 个模块   %s 指令被匹配到", ngx_modules[i]->index, cmd->name.data);
		
		//最核心的地方，
        if (cmd->type & NGX_DIRECT_CONF) {
			//我们还记得最开始ctx是包含了所有core模块的conf(create_conf回调),
        	//因此这里取出对应的模块conf.
            conf = ((void **) cf->ctx)[ngx_modules[i]->index];
        } else if (cmd->type & NGX_MAIN_CONF) {
        	//如果不是DIRECT_CONF并且是MAIN，则说明我们需要在配置中创建自己模块的上下文(也就是需要进入二级模块)
            conf = &(((void **) cf->ctx)[ngx_modules[i]->index]);
        } else if (cf->ctx) {
        	//否则进入二级模块处理
            confp = *(void **) ((char *) cf->ctx + cmd->conf);
            if (confp) {
                conf = confp[ngx_modules[i]->ctx_index];
            }
        }
        
        rv = cmd->set(cf, cmd, conf);
        /*****************
        c *** ngx_core_module: 第 0 个模块   user 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_core_module: 第 0 个模块   worker_processes 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_core_module: 第 0 个模块   worker_cpu_affinity 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_core_module: 第 0 个模块   worker_rlimit_nofile 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_errlog_module: 第 1 个模块   error_log 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_core_module: 第 0 个模块   pid 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_events_module: 第 3 个模块  events 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_event_core_module: 第 4 个模块   worker_connections 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_module: 第 6 个模块   http 指令被匹配到 at src/http/ngx_http.c   ngx_http_block(ngx_conf_t *cf, ngx_command_t *cmd, void *conf)     line 119.
            static char * ngx_http_block(ngx_conf_t *cf, ngx_command_t *cmd, void *conf)
            {
                //对http模块进行排序，与上面的所有模块排序互不影响
                ngx_http_max_module = 0;
                for (m = 0; ngx_modules[m]; m++) {
                    if (ngx_modules[m]->type != NGX_HTTP_MODULE) { 
                        continue; //从第7个开始往后所有ngx_http_core_module  ngx_http_log_module   ngx_http_upstream_module ...
                    } 
                    
                    ngx_modules[m]->ctx_index = ngx_http_max_module++;
                }
                
                .....
                
                //调用对应的create_xxx_conf回调函数
                //开始遍历
                for (m = 0; ngx_modules[m]; m++) {
                    if (ngx_modules[m]->type != NGX_HTTP_MODULE) {
                        continue;
                    }
                    module = ngx_modules[m]->ctx; //得到对应的module上下文
                  
                    mi = ngx_modules[m]->ctx_index;  //得到对应的索引
                
                    //如果有对应的回调，则调用回调函数，然后将返回的模块config设置到ctx的对应的conf列表中
                    if (module->create_main_conf) {
                        ctx->main_conf[mi] = module->create_main_conf(cf);
                        if (ctx->main_conf[mi] == NULL) {
                            return NGX_CONF_ERROR;
                        }
                    }
            
                    if (module->create_srv_conf) {
                        ctx->srv_conf[mi] = module->create_srv_conf(cf);
                        if (ctx->srv_conf[mi] == NULL) {
                            return NGX_CONF_ERROR;
                        }
                    }
            
                    if (module->create_loc_conf) {
                        ctx->loc_conf[mi] = module->create_loc_conf(cf);
                        if (ctx->loc_conf[mi] == NULL) {
                            return NGX_CONF_ERROR;
                        }
                    }
                }
           
                .....
            
                for (m = 0; ngx_modules[m]; m++) {
                    if (ngx_modules[m]->type != NGX_HTTP_MODULE) {
                        continue;
                    }
            
                    module = ngx_modules[m]->ctx;
                    
                    //如果存在preconfiguration则调用初始化,真正初始化模块之前需要调用preconfiguration来进行一些操作。
                    if (module->preconfiguration) {
                        if (module->preconfiguration(cf) != NGX_OK) {
                            return NGX_CONF_ERROR;
                        }
                    }
                }
                
                
                ....
                
                cmcf = ctx->main_conf[ngx_http_core_module.ctx_index]; //cmcf 就是ngx_http_core_main_conf_t 结构体
                cscfp = cmcf->servers.elts; //此时这里应该有内容了
            
                //当http block完全parse完毕之后，就需要merge(main和srv或者srv和loc)相关的config了。
                //不过在每次merge之前都会首先初始化main conf。
                for (m = 0; ngx_modules[m]; m++) {
                    if (ngx_modules[m]->type != NGX_HTTP_MODULE) {
                        continue;
                    }
            
                    //首先取得模块以及对应索引
                    module = ngx_modules[m]->ctx;
                    mi = ngx_modules[m]->ctx_index;
            
                    /* init http{} main_conf's */
            
                    //如果有init_main_conf,则首先初始化main conf
                    if (module->init_main_conf) {
                        rv = module->init_main_conf(cf, ctx->main_conf[mi]);
                        if (rv != NGX_CONF_OK) {
                            goto failed;
                        }
                    }
            
                    //然后开始merge config
                    rv = ngx_http_merge_servers(cf, cmcf, module, mi);
                    if (rv != NGX_CONF_OK) {
                        goto failed;
                    }
                }
                
                ........
                
                 
                //遍历模块，然后调用对应的postconfiguration
                for (m = 0; ngx_modules[m]; m++) {
                    if (ngx_modules[m]->type != NGX_HTTP_MODULE) {
                        continue;
                    }
            
                    module = ngx_modules[m]->ctx;
                    
                    //调用回调
                    if (module->postconfiguration) {
                        if (module->postconfiguration(cf) != NGX_OK) {
                            return NGX_CONF_ERROR;
                        }
                    }
                }
                
                
                .....

                
                
            }
        c *** ngx_conf_module: 第 2 个模块   include 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   types 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   default_type 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   sendfile 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   keepalive_timeout 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   server 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   listen 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   server_name 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   location 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   root 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_index_module: 第 12 个模块   index 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   error_page 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   location 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        c *** ngx_http_core_module: 第 7 个模块   root 指令被匹配到 at src/core/ngx_conf_file.c line 318.
        ******************/
    }
    
    
    
    
    

    //当配置文件解析完毕后，就初始化core module的config
    for (i = 0; ngx_modules[i]; i++) {
        if (ngx_modules[i]->type != NGX_CORE_MODULE) {
            continue;
        }

        module = ngx_modules[i]->ctx;

        //如果是core模块则调用ngx_core_module_t 结构体的init_conf属性
        if (module->init_conf) {
            if (module->init_conf(cycle, cycle->conf_ctx[ngx_modules[i]->index]) == NGX_CONF_ERROR)
            {
                environ = senv;
                ngx_destroy_cycle_pools(&conf);
                return NULL;
            }
        }
    }
    
    
    
    
    
    //调用init_module对所有的模块进行初始化，调用所有模块的ngx_XXX_module_init钩子，比如ngx_event_module_init
    for (i = 0; ngx_modules[i]; i++) {

        if (ngx_modules[i]->init_module) {
        //只有 ngx_event_core_module 模块
        	if (ngx_modules[i]->init_module(cycle) != NGX_OK) {
                /* fatal */
                exit(1);
            }
        }
    }
    
    
    
     
    //此时 nginx 多进程已经创建完毕 每个进程都执行如下循环
    for (i = 0; ngx_modules[i]; i++) {
        if (ngx_modules[i]->init_process) {
            //进程初始化, 调用每个模块的init_process,用它做模块开发的时候，使用得挺少的
            //这里要特别看的是event模块:
            //nginx的event模块包含一个init_process,也就是ngx_event_process_init(ngx_event.c).
            //这个函数就是nginx的驱动器，他初始化事件驱动器，连接池，定时器，以及挂在listen 句柄的回调函数
            if (ngx_modules[i]->init_process(cycle) == NGX_ERROR) {
                /* fatal */
                exit(2);
            }
        }
    }

/ngx/nginx1014/src/http/ngx_http_variables.c 文件中定义了所有 nginx.conf 中的变量
    
 
END;


















//这个结构定义了一个HTTP请求。
struct ngx_http_request_s {
    uint32_t                          signature;         /* "HTTP" */

    // 这个请求对应的客户端连接
    ngx_connection_t                 *connection;  //当前request的连接

    // 指向存放所有HTTP模块的上下文结构体的指针数组
    void                            **ctx;  //上下文

    // 指向请求对应的存放main级别配置结构体的指针数组
    void                            **main_conf; //main配置

    // 指向请求对应的存放srv级别配置结构体的指针数组
    void                            **srv_conf;  //srv配置

    // 指向请求对应的存放loc级别配置结构体的指针数组
    void                            **loc_conf;  //loc配置

    /*
     在接收完HTTP头部，第一次在业务上处理HTTP请求时，HTTP框架提供的处理方法是ngx_http_process_request。
     但如果该方法无法一次处理完该请求的全部业务，在归还控制权到epoll事件模块后，该请求再次被回调时，将通过ngx_http_request_handler方法来处理，
     而这个方法中对于可读事件的处理就是调用read_event_handler处理请求。也就是说，HTTP模块希望在底层处理请求的读事件，重新实现read_evet_handler方法。
     */
    ngx_http_event_handler_pt         read_event_handler;

    /*
     与read_event_handler回调方法类似，如果ngx_http_request_handler方法判断当前事件是可写事件，则调用write_event_handler处理请求。
     */
    ngx_http_event_handler_pt         write_event_handler;

    #if (NGX_HTTP_CACHE)
    ngx_http_cache_t                 *cache;
        #endif

    // upstream机制用到的结构体
    ngx_http_upstream_t              *upstream;  //load-balance，如果模块是load-balance的话设置这个
    ngx_array_t                      *upstream_states;
    /* of ngx_http_upstream_state_t */

    /*
     表示这个请求的内存池，在ngx_http_free_request 方法中销毁。它与ngx_connection_t中的内存池意义不同，当请求释放时，
     TCP连接可能并没有关闭，这时请求的内存池会销毁，但ngx_connection_t的内存池不会销毁
     */
    ngx_pool_t                       *pool;     //连接池

    // 用于接收HTTP请求内容的缓冲区，主要用于接收HTTP头部
    ngx_buf_t                        *header_in;

    /*
     ngx_http_prcess_request_headers 方法在接收，解析完HTTP请求的头部后，会把解析完的每个HTTP头部加入到headers_in的headers连表中，
     同时会构造headers_in中的其他成员
     */
    ngx_http_headers_in_t             headers_in; //request的header
    /*
    HTTP模块会把想要发送到HTTP相应信息放到headers_out中，期望HTTP框架将headers_out中的成员序列化为HTTP相应包发送给用户
    */
    ngx_http_headers_out_t            headers_out; //response的header，使用ngx_http_send_header发送

    // 接收HTTP请求中包体的数据结构
    ngx_http_request_body_t          *request_body; //response的body

    // 延迟关闭连接的时间
    time_t                            lingering_time;

    /*
     当前请求初始化时的时间。如果这个请求是子请求，则该时间是自请求的生成时间；如果这个请求是用户发来的请求，则是建立起TCP连接后，第一次接收到可读事件时的时间
     */
    time_t                            start_sec;

    // 与start_sec配合使用，表示相对于start_sec秒的毫秒偏移量
    ngx_msec_t                        start_msec;

    ngx_uint_t                        method;
    ngx_uint_t                        http_version; //http的版本

    ngx_str_t                         request_line;
    ngx_str_t                         uri;  //请求的路径 eg '/query.php'
    ngx_str_t                         args; //请求的参数 eg 'name=john'
    ngx_str_t                         exten;
    ngx_str_t                         unparsed_uri;

    ngx_str_t                         method_name;
    ngx_str_t                         http_protocol;

    /*
     表示需要发送给客户端的HTTP相应。out中保存着由headers_out中序列化后的表示HTTP头部的TCP流。在调用ngx_http_output_filter方法后，
     out中还会保存待发送的HTTP包体，它是实现异步发送的HTTP相应的关键
     */
    ngx_chain_t                      *out; //输出的chain

    /*
     当前请求既可能是用户发来的请求，也可能是派生出的子请求，而main则标识一系列相关的派生子请求的原始请求，
     我们一般可以通过main和当前请求的地址是否相等来判断当前请求是否为用户发来的原始请求。
     */
    ngx_http_request_t               *main;

    // 当前请求的父请求。注意，父请求未必是原始请求
    ngx_http_request_t               *parent;

    // 与subrequest子请求相关的功能。
    ngx_http_postponed_request_t     *postponed;
    ngx_http_post_subrequest_t       *post_subrequest;

    /*
     所有自请求都是通过posted_requests这个单链表来链接起来的，执行post子请求时调用的ngx_http_run_posted_requests
     方法就是通过遍历该单链表来执行子请求的。
     */
    ngx_http_posted_request_t        *posted_requests;

    ngx_http_virtual_names_t         *virtual_names;

    /*
     全局的ngx_http_phase_engine_t结构体中定义了一个ngx_http_phase_handler_t 回调方法组成的数组，
     而phase_handler成员则与该数组配合使用，表示请求下次应当执行以phase_handler作为序号指定的数组中的回调方法。
     HTTP框架正是以这种方式把各个HTTP模块集成起来处理请求的。
     */
    ngx_int_t                         phase_handler;

    /*
     表示NGX_HTTP_CONTENT_PHASE阶段提供给HTTP模块处理请求的一种方式，content_handler指向HTTP模块实现的请求处理方法。
     */
    ngx_http_handler_pt               content_handler;

    /*
     在NGX_HTTP_ACCESS_PHASE阶段需要判断请求是否具有访问权限时，通过access_code来传递HTTP模块的handler回调方法的返回值，
     如果access_code为0，则表示请求具备访问权限，反之则说明请求不具备访问权限
     */
    ngx_uint_t                        access_code;

    ngx_http_variable_value_t        *variables;

    #if (NGX_PCRE)
    ngx_uint_t                        ncaptures;
    int                              *captures;
    u_char                           *captures_data;
        #endif

    size_t                            limit_rate;

    /* used to learn the Apache compatible response length without a header */
    size_t                            header_size;

    // HTTP请求的全部长度，包括HTTP包体
    off_t                             request_length;

    ngx_uint_t                        err_status;

    ngx_http_connection_t            *http_connection;

    ngx_http_log_handler_pt           log_handler;

    // 在这个请求中，如果打开了某些资源，并需要在请求结束时释放，那么都需要在把定义的释放资源方法添加到cleanup成员中。
    ngx_http_cleanup_t               *cleanup;

    unsigned                          subrequests:8;

    /*
     表示当前请求的引用次数。例如，在使用subrequest功能时，依附在这个请求上的自请求数目会返回到count上，每增加一个子请求，
     count数就要加1。其中任何一个自请求派生出新的子请求时，对应的原始请求（main指针指向的请求）的count值都要加1.又如，
     当我们接收HTTP包体的时候，由于这也是一个异步调用，所以count上也需要加1，这样在结束请求时，就不会在count引用计数未清零时销毁请求。
     */
    unsigned                          count:8;

    // 标志位，目前仅由aio使用
    unsigned                          blocked:8;

    // 标志位，为1表示当前请求正在使用异步文件IO
    unsigned                          aio:1;

    unsigned                          http_state:4;

    /* URI with "/." and on Win32 with "//" */
    unsigned                          complex_uri:1;

    /* URI with "%" */
    unsigned                          quoted_uri:1;

    /* URI with "+" */
    unsigned                          plus_in_uri:1;

    /* URI with " " */
    unsigned                          space_in_uri:1;

    unsigned                          invalid_header:1;

    unsigned                          add_uri_to_alias:1;
    unsigned                          valid_location:1;
    unsigned                          valid_unparsed_uri:1;

    // 标志位，为1表示URL发生过rewrite重写
    unsigned                          uri_changed:1;

    /*
     表示使用rewrite重写URL的次数。因为目前最多可以更改10次，所以uri_changes初始化为11，而每重写URL一次就把uri_changes减1，
     一旦uri_changes等于0，则向用户返回失败
     */
    unsigned                          uri_changes:4;

    unsigned                          request_body_in_single_buf:1;
    unsigned                          request_body_in_file_only:1;
    unsigned                          request_body_in_persistent_file:1;
    unsigned                          request_body_in_clean_file:1;
    unsigned                          request_body_file_group_access:1;
    unsigned                          request_body_file_log_level:3;

    unsigned                          subrequest_in_memory:1;
    unsigned                          waited:1;

    #if (NGX_HTTP_CACHE)
    unsigned                          cached:1;
        #endif

        #if (NGX_HTTP_GZIP)
        unsigned                          gzip_tested:1;
        unsigned                          gzip_ok:1;
        unsigned                          gzip_vary:1;
            #endif

        unsigned                          proxy:1;
        unsigned                          bypass_cache:1;
        unsigned                          no_cache:1;

        /*
         * instead of using the request context data in
         * ngx_http_limit_zone_module and ngx_http_limit_req_module
         * we use the single bits in the request structure
         */
        unsigned                          limit_zone_set:1;
        unsigned                          limit_req_set:1;

        #if 0
        unsigned                          cacheable:1;
        #endif

        unsigned                          pipeline:1;
        unsigned                          plain_http:1;
        unsigned                          chunked:1;
        unsigned                          header_only:1;

        // 标志位，为1表示当前请求是keepalive请求
        unsigned                          keepalive:1;

        // 延迟关闭标志位，为1表示需要延迟关闭。例如在接收完HTTP头部时如果发现包体存在，该标志位会设置1，而放弃接收包体会设为0
        unsigned                          lingering_close:1;

        // 标志位，为1表示正在丢弃HTTP请求中的包体
        unsigned                          discard_body:1;

        // 标志位，为1表示请求的当前状态是在做内部跳转
        unsigned                          internal:1;
        unsigned                          error_page:1;
        unsigned                          ignore_content_encoding:1;
        unsigned                          filter_finalize:1;
        unsigned                          post_action:1;
        unsigned                          request_complete:1;
        unsigned                          request_output:1;

        // 标志位，为1表示发送给客户端的HTTP相应头部已经发送。在调用ngx_http_send_header方法后，若已经成功地启动相应头部发送流程，
        // 该标志位就会置1，用来防止反复地发送头部。
        unsigned                          header_sent:1;
        unsigned                          expect_tested:1;
        unsigned                          root_tested:1;
        unsigned                          done:1;
        unsigned                          logged:1;

        // 表示缓冲中是否有待发送内容的标志位
        unsigned                          buffered:4;

        unsigned                          main_filter_need_in_memory:1;
        unsigned                          filter_need_in_memory:1;
        unsigned                          filter_need_temporary:1;
        unsigned                          allow_ranges:1;

        #if (NGX_STAT_STUB)
        unsigned                          stat_reading:1;
        unsigned                          stat_writing:1;
            #endif

        /* used to parse HTTP headers */

        // 状态机解析HTTP时使用stats来表示当前的解析状态。
        ngx_uint_t                        state;

        ngx_uint_t                        header_hash;
        ngx_uint_t                        lowcase_index;
        u_char                            lowcase_header[NGX_HTTP_LC_HEADER_LEN];

        u_char                           *header_name_start;
        u_char                           *header_name_end;
        u_char                           *header_start;
        u_char                           *header_end;

        /*
         * a memory that can be reused after parsing a request line
         * via ngx_http_ephemeral_t
         */

        u_char                           *uri_start;
        u_char                           *uri_end;
        u_char                           *uri_ext;
        u_char                           *args_start;
        u_char                           *request_start;
        u_char                           *request_end;
        u_char                           *method_end;
        u_char                           *schema_start;
        u_char                           *schema_end;
        u_char                           *host_start;
        u_char                           *host_end;
        u_char                           *port_start;
        u_char                           *port_end;

        unsigned                          http_minor:16;
        unsigned                          http_major:16;
};








































 cf = {
      ctx = {
	      main_conf[] = {
		      {
				  servers = {
				          elts = {
								{
								 server_names = {elts = 0x6d3898, nelts = 1, size = 32, nalloc = 4, pool = 0x6d2ff0},
  								  ctx = {
								           main_conf = {
										        {
													ngx_array_t                servers;         /* ngx_http_core_srv_conf_t */ 
													ngx_http_phase_engine_t    phase_engine; 
													ngx_hash_t                 headers_in_hash; 
													ngx_hash_t                 variables_hash; 
													ngx_array_t                variables;       /* ngx_http_variable_t */
													ngx_uint_t                 ncaptures; 
													ngx_uint_t                 server_names_hash_max_size;
													ngx_uint_t                 server_names_hash_bucket_size; 
													ngx_uint_t                 variables_hash_max_size;
													ngx_uint_t                 variables_hash_bucket_size; 
													ngx_hash_keys_arrays_t    *variables_keys; 
													ngx_array_t               *ports; 
													ngx_uint_t                 try_files;       /* unsigned  try_files:1 */ 
													ngx_http_phase_t           phases[NGX_HTTP_LOG_PHASE + 1];
												},
												....
										   }, 
										   srv_conf = {
													{
													  server_names
													  *ctx
													  server_name = {len = 0, data = 0x0}, 
													  connection_pool_size = -1, 
													  request_pool_size = -1, 
													  client_header_buffer_size = -1, 
													  large_client_header_buffers = {num = 0, size = 0}, 
													  client_header_timeout = -1, 
													  ignore_invalid_headers = -1, 
													  merge_slashes = -1, 
													  underscores_in_headers = -1, 
													  listen = 1, 
													  captures = 0, 
													  named_locations = 0x0
													},
													{
													  ....  
													}
											}, 
										    loc_conf = {
											         {
															// location 的名称，即nginx.conf 中location后的表达式
															ngx_str_t     name;          /* location name */ 
															#if (NGX_PCRE)
																ngx_http_regex_t  *regex;
															#endif 
																unsigned      noname:1;   /* "if () {}" block or limit_except */
																unsigned      lmt_excpt:1;
																unsigned      named:1;

																unsigned      exact_match:1;
																unsigned      noregex:1;

																unsigned      auto_redirect:1;
													 
																ngx_http_location_tree_node_t   *static_locations;
															#if (NGX_PCRE)
																ngx_http_core_loc_conf_t       **regex_locations;
															#endif  
																/*
																指向所属location 块内ngx_http_conf_ctx_t 结构体中的loc_conf 指针数组，它保存着当前location块内所有HTTP模块
																create_loc_conf方法产生的结构体指针
																*/
																void        **loc_conf;

																uint32_t      limit_except;
																void        **limit_except_loc_conf;

																ngx_http_handler_pt  handler;

																/* location name length for inclusive location with inherited alias */
																size_t        alias;
																ngx_str_t     root;                    /* root, alias */
																ngx_str_t     post_action;

																ngx_array_t  *root_lengths;
																ngx_array_t  *root_values;

																ngx_array_t  *types;
																ngx_hash_t    types_hash;
																ngx_str_t     default_type;
 
																ngx_uint_t    keepalive_requests;      /* keepalive_requests */
																ngx_uint_t    keepalive_disable;       /* keepalive_disable */
																ngx_uint_t    satisfy;                 /* satisfy */
																ngx_uint_t    lingering_close;         /* lingering_close */
																ngx_uint_t    if_modified_since;       /* if_modified_since */
																ngx_uint_t    max_ranges;              /* max_ranges */
																ngx_uint_t    client_body_in_file_only; /* client_body_in_file_only */

																ngx_flag_t    client_body_in_single_buffer;
																									   /* client_body_in_singe_buffer */
																 
																ngx_array_t  *error_pages;             /* error_page */
																ngx_http_try_file_t    *try_files;     /* try_files */

																ngx_path_t   *client_body_temp_path;   /* client_body_temp_path */

																ngx_open_file_cache_t  *open_file_cache;
																time_t        open_file_cache_valid;
																ngx_uint_t    open_file_cache_min_uses;
																ngx_flag_t    open_file_cache_errors;
																ngx_flag_t    open_file_cache_events;

																ngx_log_t    *error_log;

																ngx_uint_t    types_hash_max_size;
																ngx_uint_t    types_hash_bucket_size;

																/*
																将同一个server块内多个表达location块的 ngx_http_core_loc_conf_t 结构体以及双向链表方式组合起来，
																该locations指针将指向ngx_http_location_queue_t 结构体
																*/
																ngx_queue_t  *locations;

															#if 0
																ngx_http_core_loc_conf_t  *prev_location;
															#endif
													}, 
													
													
											}
										},  ........ngx_http_core_server方法里把所有模块的create_main_conf、create_srv_conf、create_loc_conf执行了
								  server_name = {len = 0, data = 0x0}, 
								  connection_pool_size = 18446744073709551615, 
								  request_pool_size = 18446744073709551615, 
								  client_header_buffer_size = 18446744073709551615, 
								  large_client_header_buffers = {num = 0, size = 0}, 
								  client_header_timeout = 18446744073709551615, 
								  ignore_invalid_headers = -1, 
								  merge_slashes = -1, 
								  underscores_in_headers = -1, 
								  listen = 1, 
								  captures = 0, 
								  named_locations = 0x0
								},
								{
								  B server 
								   ....
								}
							 }
				             , 
				             nelts = 1,
 							 size = 8, 
							 nalloc = 4, 
							 pool = 0x6cefe0
						  }, 
				  phase_engine = {handlers = 0x0, server_rewrite_index = 0, location_rewrite_index = 0}, 
				  headers_in_hash = {buckets = 0x0, size = 0}, 
				  variables_hash = {buckets = 0x0, size = 0}, 
				  variables = {elts = 0x6e22d8, nelts = 7, size = 56, nalloc = 8, pool = 0x6cefe0}, 
				  ncaptures = 0, 
				  server_names_hash_max_size = 512, 
				  server_names_hash_bucket_size = 64, 
				  variables_hash_max_size = 512,
				  variables_hash_bucket_size = 64,
				  variables_keys = 0x6d3270,
				  ports = 0x6d3918, 
				  try_files = 0,
				  phases = {
				      NGX_HTTP_POST_READ_PHASE=>{
					     handlers = {
					     elts = 0x0, 
						 nelts = 0,
						 size = 0,
						 nalloc = 0,
						 pool = 0x0}
					 }, 
					 NGX_HTTP_REWRITE_PHASE=>{
					     handlers = {
					     elts = 0x0, 
						 nelts = 0,
						 size = 0,
						 nalloc = 0,
						 pool = 0x0}
					 },
					 NGX_HTTP_CONTENT_PHASE=>{
					     handlers = {
					     elts = 0x0, 
						 nelts = 0,
						 size = 0,
						 nalloc = 0,
						 pool = 0x0}
					 },
					 NGX_HTTP_LOG_PHASE=>{
					     handlers = {
					     elts = 0x0, 
						 nelts = 0,
						 size = 0,
						 nalloc = 0,
						 pool = 0x0}
					 }
					 ....
				  }
			  }, 
			  
			  { log模块
			  formats = {
					  0=> {
							fmt->name.data ="combined";
							fmt->flushes = NULL;
							fmt->ops = ngx_array_create(cf->pool, 16, sizeof(ngx_http_log_op_t));
					   }
				  }
				  combined_used = 1
			  },
			  ........
		  },
		  srv_conf[]  = {
			   {  server_names
				  *ctx
				  server_name = {len = 0, data = 0x0}, 
				  connection_pool_size = -1, 
				  request_pool_size = -1, 
				  client_header_buffer_size = -1, 
				  large_client_header_buffers = {num = 0, size = 0}, 
				  client_header_timeout = -1, 
				  ignore_invalid_headers = -1, 
				  merge_slashes = -1, 
				  underscores_in_headers = -1, 
				  listen = 1, 
				  captures = 0, 
				  named_locations = 0x0
			  },
			  .......
		  }
		  , 
		  loc_conf[] = {
				  {   name = {len = 0, data = 0x0},
					  regex = 0x0, noname = 0, 
					  lmt_excpt = 0, named = 0, exact_match = 0, noregex = 0, 
					  auto_redirect = 0, gzip_disable_msie6 = 3, static_locations = 0x0, regex_locations = 0x0, loc_conf = 0x0, 
					  limit_except = 0, limit_except_loc_conf = 0x0, handler = 0, alias = 0, root = {len = 0, data = 0x0}, post_action = {
						len = 0, data = 0x0}, root_lengths = 0x0, root_values = 0x0, types = 0x0, types_hash = {buckets = 0x0, size = 0}, 
					  default_type = {len = 0, data = 0x0}, client_max_body_size = -1, directio = -1, directio_alignment = -1, 
					  client_body_buffer_size = 18446744073709551615, send_lowat = 18446744073709551615, 
					  postpone_output = 18446744073709551615, limit_rate = 18446744073709551615, limit_rate_after = 18446744073709551615, 
					  sendfile_max_chunk = 18446744073709551615, read_ahead = 18446744073709551615, 
					  client_body_timeout = 18446744073709551615, send_timeout = 18446744073709551615, 
					  keepalive_timeout = 18446744073709551615, lingering_time = 18446744073709551615, 
					  lingering_timeout = 18446744073709551615, resolver_timeout = 18446744073709551615, resolver = 0x0, 
					  keepalive_header = -1, keepalive_requests = 18446744073709551615, keepalive_disable = 0, 
					  satisfy = 18446744073709551615, lingering_close = 18446744073709551615, if_modified_since = 18446744073709551615, 
					  max_ranges = 18446744073709551615, client_body_in_file_only = 18446744073709551615, 
					  client_body_in_single_buffer = -1, internal = -1, sendfile = -1, tcp_nopush = -1, tcp_nodelay = -1, 
					  reset_timedout_connection = -1, server_name_in_redirect = -1, port_in_redirect = -1, msie_padding = -1, 
					  msie_refresh = -1, log_not_found = -1, log_subrequest = -1, recursive_error_pages = -1, server_tokens = -1, 
					  chunked_transfer_encoding = -1, gzip_vary = -1, gzip_http_version = 18446744073709551615, gzip_proxied = 0, 
					  gzip_disable = 0xffffffffffffffff, error_pages = 0x6e7c38, try_files = 0x0, client_body_temp_path = 0x0, 
					  open_file_cache = 0xffffffffffffffff, open_file_cache_valid = -1, open_file_cache_min_uses = 18446744073709551615, 
					  open_file_cache_errors = -1, open_file_cache_events = -1, error_log = 0x0, 
					  types_hash_max_size = 18446744073709551615, 
		              types_hash_bucket_size = 18446744073709551615, locations = 0x6d3f70
                },
		        {
				  logs ={
					   0=>{
						   log->file = ngx_conf_open_file(cf->cycle, &value[1]); 
						   log->format = {
								  0=> {
										fmt->name.data ="combined";
										fmt->flushes = NULL;
										fmt->ops = ngx_array_create(cf->pool, 16, sizeof(ngx_http_log_op_t));
								   }
							  };
					   } 
				  }
				  open_file_cache = "-1"
				  open_file_cache_valid
				  open_file_cache_min_uses
				  off
			  }
			 
		  }
		  .......ngx_http_block方法里把所有模块的create_main_conf、create_srv_conf、create_loc_conf执行了
	  }
	  ......
   }
  
  
  
  







