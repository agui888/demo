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
        
 
//////新的 /////   
struct ngx_cycle_s { 
    void                  ****conf_ctx; ====>ngx_pcalloc(pool, 44 * 4);
    // 内存池
    ngx_pool_t            *pool;       ====>  ngx_pcalloc(pool, sizeof(ngx_cycle_t))   
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
                          
    ngx_log_t            *log; ##########被赋值###old_cycle->log//日志

    void                 *ctx;  ##########被赋值## cycle->conf_ctx;#//模块的配置信息
    ngx_uint_t            module_type;  ##########被赋值##0x45524F43##//当前指令的类型
    ngx_uint_t            cmd_type;##########被赋值# 0x01000000//命令的类型

    ngx_conf_handler_pt   handler; //指令处理函数，有自己行为的在这里实现
    char                 *handler_conf; //指令处理函数的配置信息
};
   





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