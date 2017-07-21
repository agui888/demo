<?php

nginx调试
http://www.tuicool.com/articles/Qz6B7n

sudo nginx #打开 nginx
nginx -s reload|reopen|stop|quit  #重新加载配置|重启|停止|退出 nginx
nginx -t   #测试配置是否有语法错误

nginx [-?hvVtq] [-s signal] [-c filename] [-p prefix] [-g directives]

-?,-h           : 打开帮助信息
-v              : 显示版本信息并退出
-V              : 显示版本和配置选项信息，然后退出
-t              : 检测配置文件是否有语法错误，然后退出
-q              : 在检测配置文件期间屏蔽非错误信息
-s signal       : 给一个 nginx 主进程发送信号：stop（停止）, quit（退出）, reopen（重启）, reload（重新加载配置文件）
-p prefix       : 设置前缀路径（默认是：/usr/local/Cellar/nginx/1.2.6/）
-c filename     : 设置配置文件（默认是：/usr/local/etc/nginx/nginx.conf）
-g directives   : 设置配置文件外的全局指令


//stdint.h 中定义
 /* There is some amount of overlap with <sys/types.h> as known by inet code */
#ifndef __int8_t_defined
# define __int8_t_defined
typedef signed char         int8_t;
typedef short int        　 int16_t;
typedef int            　　 int32_t;
# if __WORDSIZE == 64
typedef long int       　　　int64_t;
# else
__extension__
typedef long long int        int64_t;
# endif
#endif

/* Unsigned.  */
typedef unsigned char         uint8_t;
typedef unsigned short int    uint16_t;
#ifndef __uint32_t_defined
typedef unsigned int          uint32_t;
# define __uint32_t_defined
#endif
#if __WORDSIZE == 64
typedef unsigned long int       uint64_t;
#else
__extension__
typedef unsigned long long int    uint64_t;
#endif
 


/* Types for `void *' pointers.  */
#if __WORDSIZE == 64
# ifndef __intptr_t_defined
typedef long int               intptr_t;
#  define __intptr_t_defined
# endif
typedef unsigned long int    uintptr_t;
#else
# ifndef __intptr_t_defined
typedef int                    intptr_t;
#  define __intptr_t_defined
# endif
typedef unsigned int        uintptr_t;
#endif


 



###################################intptr_t  ngx_int_t#################################################
typedef long int             ngx_int_t;     //ngx_config.h   表示 long int   
typedef unsigned long int  ngx_uint_t
  
typedef  unsigned char u_char;

typedef long                        ngx_atomic_int_t;
typedef unsigned long               ngx_atomic_uint_t;


###################################ngx_str_t  #################################################
typedef struct {
    size_t      len;  //字符串的有效长度
    u_char     *data; //字符串的内容，指向字符串的起始位置
} ngx_str_t;



###################################ngx_time_t  #################################################
typedef struct {
    time_t      sec;
    ngx_uint_t  msec;
    ngx_int_t   gmtoff;
} ngx_time_t;



typedef unsigned long int ngx_current_msec;




###################################ngx_pool_t  ngx_pool_s 内存  #################################################

//大内存结构
struct ngx_pool_large_s {
    ngx_pool_large_t     *next; //下一个大块内存
    void                 *alloc;//nginx分配的大块内存空间
};

//该结构用来维护内存池的数据块，供用户分配之用
typedef struct {
    u_char               *last;  //当前内存分配结束位置，即下一段可分配内存的起始位置
    u_char               *end;   //内存池结束位置
    ngx_pool_t           *next;  //链接到下一个内存池
    ngx_uint_t            failed;//统计该内存池不能满足分配请求的次数
} ngx_pool_data_t;

//该结构维护整个内存池的头部信息
struct ngx_pool_s {
    ngx_pool_data_t       d;       //数据块
    size_t                max;     //数据块大小，即小块内存的最大值
    ngx_pool_t           *current; //保存当前内存值
    ngx_chain_t          *chain;   //可以挂一个chain结构
    ngx_pool_large_t     *large;   //分配大块内存用，即超过max的内存请求
    ngx_pool_cleanup_t   *cleanup; //挂载一些内存池释放的时候，同时释放的资源
    ngx_log_t            *log;
};


typedef struct {
     ngx_pool_t              *pool;   /* pcre's malloc() pool */
} ngx_core_tls_t;


###################################ngx_array_t  ngx_array_s #################################################

// 动态数组
struct ngx_array_s {
    // elts指向数组的首地址
    void        *elts; 
    // nelts是数组中已经使用的元素个数
    ngx_uint_t   nelts; 
    // 每个数组元素占用的内存大小
    size_t       size;  
    // 当前数组中能够容纳元素个数的总大小
    ngx_uint_t   nalloc; 
    // 内存池对象
    ngx_pool_t  *pool;  
};

###################################ngx_keyval_t  #################################################
typedef struct {
    ngx_str_t   key;  //key-value结构
    ngx_str_t   value; //key-value结构
} ngx_keyval_t;


###################################ngx_log_s ngx_log_t#################################################
typedef struct ngx_log_s     ngx_log_t;  //ngx_core.h 日志结构体

struct ngx_open_file_s {
    ngx_fd_t              fd;
    ngx_str_t             name;

    u_char               *buffer;
    u_char               *pos;
    u_char               *last;
};

struct ngx_log_s {
    ngx_uint_t           log_level;
    ngx_open_file_t     *file; 
    ngx_atomic_uint_t    connection; 
    ngx_log_handler_pt   handler;
    void                *data; 
    char                *action;
};



###################################ngx_list_s  ngx_list_t#################################################

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




###################################ngx_cycle_s  ngx_cycle_t#################################################
typedef struct ngx_cycle_s  ngx_cycle_t;    //ngx_core.h  保存着所有模块存储配置项的结构体

struct ngx_cycle_s {
    /*它首先是一维数组，每个数组成员是一个指针分别指向，
      每个模块的ctx属性下面的create_conf属性的返回值
       ngx_modules[i]->ctx->create_conf(cycle)
    */
    void                  ****conf_ctx; 
    ngx_pool_t               *pool;     
 
    ngx_log_t                *log;      
    /*
    由nginx.conf配置文件读取到日志文件路径后，将开始初始化error_log日志文件，由于log对象还在用于输出日志到屏幕，
    这时会用new_log对象暂时性地替代log日志，待初始化成功后，会用new_log的地址覆盖上面的log指针
    */
    ngx_log_t                 new_log; 

    /*
    对于poll，rtsig这样的时间模块，会以有效文件句柄数来预先建立这些ngx_connection_t结构体，
    以加速事件的收集，分发。这时files就会保存所有ngx_connection_t的指针组成的数组，
    files_n就是指针的总数，而文件句柄的值用来访问files数组成员
    */
    ngx_connection_t        **files;    

    // 可用连接池，与free_connection_n配合使用
    ngx_connection_t         *free_connections;
    // 可用连接池中连接的总数    
    ngx_uint_t                free_connection_n;    

    /* 双向链表容器，元素类型是ngx_connection_t结构体，表示可重复使用连接队列 */
    ngx_queue_t               reusable_connections_queue;  

    // 动态数组，每个数组元素储存着ngx_listening_t成员，表示监听端口及相关的参数
    ngx_array_t               listening;        

    /*
    动态数组容器，它保存着nginx所有要操作的目录。如果有目录不存在，就会试图创建，而创建目录失败就会导致nginx启动失败。
    */
    ngx_array_t               pathes;           
    /*
    单链表容器，元素类型是ngx_open_file_t 结构体，它表示nginx已经打开的所有文件。事实上，nginx框架不会向open_files链表中添加文件。
    而是由对此感兴趣的模块向其中添加文件路径名，nginx框架会在ngx_init_cycle 方法中打开这些文件
    */
    ngx_list_t                open_files;       

    // 单链表容器，元素类型是ngx_shm_zone_t结构体，每个元素表示一块共享内存
    ngx_list_t                shared_memory;   

    // 当前进程中所有链接对象的总数，与connections成员配合使用
    ngx_uint_t                connection_n;    
    ngx_uint_t                files_n;      
    ngx_connection_t         *connections; //指向当前进程中的所有连接对象，与connection_n配合使用  
    ngx_event_t              *read_events;//指向当前进程中的所有读事件对象，connection_n同时表示所有读事件的总数
    ngx_event_t              *write_events;//指向当前进程中的所有写事件对象，connection_n同时表示所有写事件的总数   
    /*
    旧的ngx_cycle_t 对象用于引用上一个ngx_cycle_t 对象中的成员。例如ngx_init_cycle 方法，在启动初期， 
    需要建立一个临时的ngx_cycle_t对象保存一些变量，再调用ngx_init_cycle 方法时就可以把旧的ngx_cycle_t 对象传进去，
    而这时old_cycle对象就会保存这个前期的ngx_cycle_t对象。
    */
    ngx_cycle_t              *old_cycle;  
    ngx_str_t                 conf_file; // 配置文件相对于安装目录的路径名称 
    ngx_str_t                 conf_param; // nginx 处理配置文件时需要特殊处理的在命令行携带的参数，一般是-g 选项携带的参数         
    ngx_str_t                 conf_prefix;// nginx配置文件所在目录的路径 
    ngx_str_t                 prefix;//nginx安装目录的路径     
    ngx_str_t                 lock_file;// 用于进程间同步的文件锁名称   
   
    ngx_str_t                 hostname;    // 使用gethostname系统调用得到的主机名      
}; 















###################################ngx_core_conf_t#################################################
typedef struct {
     ngx_flag_t               daemon;              
     ngx_flag_t               master; 
     ngx_msec_t               timer_resolution; 
     ngx_int_t                worker_processes;    
     ngx_int_t                debug_points; 
     ngx_int_t                rlimit_nofile;
     ngx_int_t                rlimit_sigpending;
     off_t                    rlimit_core; 
     int                      priority; 
     ngx_uint_t               cpu_affinity_n;
     u_long                  *cpu_affinity; 
     char                    *username;             
     ngx_uid_t                user;                 /* user ID */  
     ngx_gid_t                group;                /* group ID*/  
     ngx_str_t                working_directory;
     ngx_str_t                lock_file; 
     ngx_str_t                pid;
     ngx_str_t                oldpid;		 
     ngx_array_t              env;
     char                   **environment; 
#if (NGX_THREADS)
     ngx_int_t                worker_threads;
     size_t                   thread_stack_size;
#endif

} ngx_core_conf_t;
 
ngx_core_conf_t                 *ccf; //ngx_cycle.h   结构体  属性有 worker_processes user pid






###################################ngx_conf_s  ngx_conf_t#################################################
struct ngx_conf_s {
    char                 *name;  //没有使用
    ngx_array_t          *args; #  

    ngx_cycle_t          *cycle; //指向系统参数，在系统整个运行过程中，
    //需要使用的一些参数、资源需要统一的管理
    ngx_pool_t           *pool;  //内存池
    ngx_pool_t           *temp_pool; //分配临时数据空间的内存池
    ngx_conf_file_t      *conf_file; //配置文件的信息
    ngx_log_t            *log; //日志

    void                 *ctx;  //模块的配置信息
    ngx_uint_t            module_type; //当前指令的类型
    ngx_uint_t            cmd_type; //命令的类型

    ngx_conf_handler_pt   handler; //指令处理函数，有自己行为的在这里实现
    char                 *handler_conf; //指令处理函数的配置信息
};








###################################ngx_command_s  ngx_command_t#################################################
//commands数组用于定义模块的配置文件参数。
struct ngx_command_s {
    ngx_str_t             name;         //配置项名称

    //配置项类型(有几个参数或者可以在什么地方出现等)
    ngx_uint_t            type;

    //出现了name中制定的配置项后，将会调用set方法处理配置项参数。
    //这个可以使用nginx预设的14个解析配置方法，也可以使用自定义的。
    char               *(*set)(ngx_conf_t *cf, ngx_command_t *cmd, void *conf);

    //在配置文件中的偏移量，它的取值范围是：
    /*
    NGX_HTTP_MAIN_CONF_OFFSET
    NGX_HTTP_SRV_CONF_OFFSET
    NGX_HTTP_LOC_CONF_OFFSET
    */
    //因为有可能模块同时会有main，srv，loc三种配置结构体，但是这个配置项解析完后要放在哪个结构体内呢？
    ngx_uint_t            conf;

    //表示当前配置项在整个存储配置项的结构体中的偏移位置，
    //可以使用offsetof(test_stru, b)来获取
    ngx_uint_t            offset;

    //命令处理完后的回调指针，对于set的14种预设的解析配置方法， 可能的结构有：
    /*
    ngx_conf_post_t
    ngx_conf_enum_t
    ngx_conf_bitmask_t
    null
    */
    void                 *post;
};









###################################ngx_listening_s  ngx_listening_t  #################################################
struct ngx_listening_s {
    // socket套接字句柄
    ngx_socket_t        fd;

    // 监听socketaddr地址
    struct sockaddr    *sockaddr;
    // socketaddr地址长度
    socklen_t           socklen;    /* size of sockaddr */
    // 存储IP地址的字符串addr_text最大长度，即它指定了addr_text 所分配的内存大小
    size_t              addr_text_max_len;
    // 以字符串形式存储IP地址
    ngx_str_t           addr_text;

    // 套接字类型，例如，当type是SOCK_STREAM 时，表示TCP
    int                 type;

    /* TCP实现监听时的backlog队列，它表示允许正在通过三次握手建立TCP连接但还没有任何进程开始处理的连接最大个数 */
    int                 backlog;
    // 内核中对于这个套接字的接收缓冲区大小
    int                 rcvbuf;
    // 内核中对于这个套接字的发送缓冲区大小
    int                 sndbuf;

    /* handler of accepted connection */
    // 当新的TCP连接成功建立后的处理方法
    ngx_connection_handler_pt   handler;

    /*
     实际上框架并不适用servers 指针，它更多是作为一个保留指针，目前主要用于HTTP或者mail等模块，用户保存当前监听端口对应着的所有主机名
     */
    void               *servers;  /* array of ngx_http_in_addr_t, for example */

    // log和logp都是可用的日志对象的指针
    ngx_log_t           log;
    ngx_log_t          *logp;

    // 如果为新的TCP连接创建内存池，则内存池的初始大小应用是pool_size
    size_t              pool_size;

    size_t              post_accept_buffer_size;
    /* should be here because of the deferred accept */
    /* should be here because of the AcceptEx() preread */
    /* TCP_DEFER_ACCEPT 选项将在建立TCP连接成功且接收到用户的请求数据后，才向对监听套接字感兴趣的进程发送事件通知，而连接建立成功后，
     如果post_accept_timeout 秒后仍然没有收到的用户数据，则内核直接丢弃连接
     */
    ngx_msec_t          post_accept_timeout;

    // 前一个ngx_listening_t结构，多个ngx_listening_t结构体之间由previous指针组成单链表
    ngx_listening_t    *previous;
    // 当前监听句柄对应着的ngx_connection_t结构体
    ngx_connection_t   *connection;

    /*
     标志位，为1则表示在当前监听句柄有效，且执行ngx_init_cycle时不关闭监听端口，为0时则正常关闭。改标志位框架代码会自动设置。
     */
    unsigned            open:1;
    /*
     标志位，为1表示使用已经有的ngx_cycle_t来初始化新的ngx_cycle_t结构体时，不关闭原先打开的监听端口，这对运行中升级程序很有用，
     remain为0时，表示正常关闭曾经打开的监听端口。该标志位框架代码会自动设置，参见ngx_init_cycle方法。
     */
    unsigned            remain:1;
    /*
     标志位，为1表示跳过设置当前ngx_listening_t结构体中的套接字，为0时正常初始化套接字，该标志位框架代码会自动设置
     */
    unsigned            ignore:1;

    // 表示是否已经绑定，实际上目前该标志位没有使用
    unsigned            bound:1;       /* already bound */
    // 表示当前监听句柄是否来自前一个进程（如升级nginx程序）
    // 如果为1， 则表示来自前一个进程，一般会保留之前已经设置好的套接字，不做改变
    unsigned            inherited:1;   /* inherited from previous process */
    // 目前未使用
    unsigned            nonblocking_accept:1;
    // 标志位，为1时表示当前结构体对应的套接字已经监听
    unsigned            listen:1;
    // 目前未使用
    unsigned            nonblocking:1;
    // 目前该标志位没有意义
    unsigned            shared:1;    /* shared between threads or processes */
    // 标志位，为1时表示nginx会将网络地址转变为字符串形式的地址
    unsigned            addr_ntop:1;

    #if (NGX_HAVE_INET6 && defined IPV6_V6ONLY)
    unsigned            ipv6only:2;
        #endif

        #if (NGX_HAVE_DEFERRED_ACCEPT)
        unsigned            deferred_accept:1;
        unsigned            delete_deferred:1;
        unsigned            add_deferred:1;
            #ifdef SO_ACCEPTFILTER
        char               *accept_filter;
        #endif
        #endif
        #if (NGX_HAVE_SETFIB)
        int                 setfib;
            #endif

};








###################################ngx_module_s  ngx_module_t#################################################
//ngx_module_s是模块的定义
struct ngx_module_s {
    //对于一类模块（由下面的type成员决定类别）而言，ctx_index标示当前模块在这类模块中的序号。
    //这个成员常常是由管理这类模块的一个nginx核心模块设置的，对于所有的HTTP模块而言，ctx_index
    //是由核心模块ngx_http_module设置的。
    ngx_uint_t            ctx_index;

    //index表示当前模块在ngx_modules数组中的序号。Nginx启动的时候会根据ngx_modules数组设置各个模块的index值
    ngx_uint_t            index;

    //spare系列的保留变量，暂未使用
    ngx_uint_t            spare0;
    ngx_uint_t            spare1;
    ngx_uint_t            spare2;
    ngx_uint_t            spare3;

    //nginx模块版本，目前只有一种，暂定为1
    ngx_uint_t            version;

    //模块上下文，每个模块有不同模块上下文,每个模块都有自己的特性，而ctx会指向特定类型模块的公共接口。
    //比如，在HTTP模块中，ctx需要指向ngx_http_module_t结构体。
    void                 *ctx;

    //模块命令集，将处理nginx.conf中的配置项
    ngx_command_t        *commands;

    //标示该模块的类型，和ctx是紧密相关的。它的取值范围是以下几种:
    //NGX_HTTP_MODULE,NGX_CORE_MODULE,NGX_CONF_MODULE,
    //NGX_EVENT_MODULE,NGX_MAIL_MODULE
    ngx_uint_t            type;

    //下面7个函数是nginx在启动，停止过程中的7个执行点
    ngx_int_t           (*init_master)(ngx_log_t *log);         //初始化master
    ngx_int_t           (*init_module)(ngx_cycle_t *cycle);     //初始化模块
    ngx_int_t           (*init_process)(ngx_cycle_t *cycle);    //初始化进程
    ngx_int_t           (*init_thread)(ngx_cycle_t *cycle);     //初始化线程
    void                (*exit_thread)(ngx_cycle_t *cycle);     //退出线程
    void                (*exit_process)(ngx_cycle_t *cycle);    //退出进程
    void                (*exit_master)(ngx_cycle_t *cycle);     //退出master

    //保留字段，无用，可以使用NGX_MODULE_V1_PADDING来替换
    uintptr_t             spare_hook0;
    uintptr_t             spare_hook1;
    uintptr_t             spare_hook2;
    uintptr_t             spare_hook3;
    uintptr_t             spare_hook4;
    uintptr_t             spare_hook5;
    uintptr_t             spare_hook6;
    uintptr_t             spare_hook7;
};



////////////////////////赋值、、、、、
//commands数组用于定义模块的配置文件参数。
struct ngx_command_s {
    ngx_str_t             name;         //配置项名称

    //配置项类型(有几个参数或者可以在什么地方出现等)
    ngx_uint_t            type;

    //出现了name中制定的配置项后，将会调用set方法处理配置项参数。
    //这个可以使用nginx预设的14个解析配置方法，也可以使用自定义的。
    char               *(*set)(ngx_conf_t *cf, ngx_command_t *cmd, void *conf);

    //在配置文件中的偏移量，它的取值范围是：
    /*
    NGX_HTTP_MAIN_CONF_OFFSET
    NGX_HTTP_SRV_CONF_OFFSET
    NGX_HTTP_LOC_CONF_OFFSET
    */
    //因为有可能模块同时会有main，srv，loc三种配置结构体，但是这个配置项解析完后要放在哪个结构体内呢？
    ngx_uint_t            conf;

    //表示当前配置项在整个存储配置项的结构体中的偏移位置，
    //可以使用offsetof(test_stru, b)来获取
    ngx_uint_t            offset;

    //命令处理完后的回调指针，对于set的14种预设的解析配置方法， 可能的结构有：
    /*
    ngx_conf_post_t
    ngx_conf_enum_t
    ngx_conf_bitmask_t
    null
    */
    void                 *post;
};

typedef struct {
    ngx_str_t             name;                                         //模块名，即ngx_core_module_ctx结构体对象的
    void               *(*create_conf)(ngx_cycle_t *cycle);             //解析配置项茜，nginx框架会调用create_conf方法
    char               *(*init_conf)(ngx_cycle_t *cycle, void *conf);   //解析配置项完成后，nginx框架会调用init_conf方法
} ngx_core_module_t;

ngx_module_t  ngx_core_module = {
    NGX_MODULE_V1,//该宏用来初始化前7个字段
    &ngx_core_module_ctx,                  /*8个字段  *ctx=ngx_core_module_t   */
    
    ngx_core_commands,                     /*9个字段 module directives */
    NGX_CORE_MODULE,                       /*10 module type */
    NULL,                                  /* init master */
    NULL,                                  /* init module */
    NULL,                                  /* init process */
    NULL,                                  /* init thread */
    NULL,                                  /* exit thread */
    NULL,                                  /* exit process */
    NULL,                                  /* exit master */
    NGX_MODULE_V1_PADDING
};


























###################################ngx_http_conf_ctx_t  #################################################
typedef struct {
    void        **main_conf;//数组，数组成员是void*，指向http模块的mainconf
    void        **srv_conf;
    void        **loc_conf;
} ngx_http_conf_ctx_t;



###################################ngx_http_module_t  #################################################
//HTTP框架在读取,重载配置文件时定义了由ngx_http_module_t接口描述的8个阶段
//这8个阶段的调用顺序应该是：
/*
 create_main_conf
 create_srv_conf
 create_loc_conf
 preconfiguration
 init_main_conf
 merge_srv_conf
 merge_loc_conf
 postconfiguration
 */
typedef struct {
    ngx_int_t   (*preconfiguration)(ngx_conf_t *cf);  //解析配置文件前调用
    ngx_int_t   (*postconfiguration)(ngx_conf_t *cf); //完成配置文件解析后调用

    void       *(*create_main_conf)(ngx_conf_t *cf);  //当需要创建数据结构用户存储main级别的全局配置项时候调用
    char       *(*init_main_conf)(ngx_conf_t *cf, void *conf); //初始化main级别配置项

    void       *(*create_srv_conf)(ngx_conf_t *cf); //当需要创建数据结构用户存储srv级别的全局配置项时候调用
    char       *(*merge_srv_conf)(ngx_conf_t *cf, void *prev, void *conf); //srv覆盖策略

    void       *(*create_loc_conf)(ngx_conf_t *cf); //当需要创建数据结构用户存储loc级别的全局配置项时候调用
    char       *(*merge_loc_conf)(ngx_conf_t *cf, void *prev, void *conf); //loc覆盖策略
} ngx_http_module_t;

            ###################################ngx_hash_keys_arrays_t  #################################################
            typedef struct {
                // 下面的keys_hash, dns_wc_head_hash,dns_wc_tail_hash都是简易散列表，而hsize指明了散列表的槽个数，其简易散列方法也需要对hsize求余
                ngx_uint_t        hsize;
            
                // 内存池，用于分配永久性内存，到目前的nginx版本为止，该pool成员没有任何意义
                ngx_pool_t       *pool;
                // 临时内存池，下面的动态数组需要的内存都有temp_pool内存池分配
                ngx_pool_t       *temp_pool;
            
                // 用动态数组以ngx_hash_key_t结构体保存着不含有通配符关键字的元素
                ngx_array_t       keys;
                /* 一个极其简易的散列表，它以数组的形式保存着hsize个元素，每个元素都是ngx_array_t动态数组，在用户添加的元素过程中，会根据关键码
                 将用户的ngx_str_t类型的关键字添加到ngx_array_t 动态数组中，这里所有的用户元素的关键字都不可以带通配符，表示精确匹配 */
                ngx_array_t      *keys_hash;
            
                // 用动态数组以ngx_hash_key_t 结构体保存着含有前置通配符关键字的元素生成的中间关键字
                ngx_array_t       dns_wc_head;
                // 一个极其简易的散列表，它以数组的形式保存着hsize个元素，每个元素都是ngx_array_t 动态数组。在用户添加元素过程中，会根据关键码将用户的
                // ngx_str_t类型的关键字添加到ngx_array_t 动态数组中。这里所有的用户元素的关键字都带前置通配符。
                ngx_array_t      *dns_wc_head_hash;
            
                // 用动态数组以ngx_hash_key_t 结构体保存着含有前置通配符关键字的元素生成的中间关键字
                ngx_array_t       dns_wc_tail;
                /*
                 一个极其建议的散列表，它以数组的形式保存着hsize个元素，每个元素都是ngx_array_t动态数组。在用户添加元素过程中，会根据关键码将用户
                 的ngx_str_t 类型的关键字添加到ngx_array_t 动态数组中，这里所有的用户元素的关键字都带后置通配符。
                 */
                ngx_array_t      *dns_wc_tail_hash;
            } ngx_hash_keys_arrays_t;

###################################ngx_http_core_main_conf_t  #################################################
typedef struct {
    ngx_array_t                servers;         /* ngx_http_core_srv_conf_t */
                                    servers->nelts = 0;
                                    servers->size = sizeof(ngx_http_core_srv_conf_t *);
                                    servers->nalloc = 4;
                                    servers->pool = pool; 
                                    servers->elts = ngx_palloc(pool, 4 * sizeof(ngx_http_core_srv_conf_t *));
                                    
    ngx_http_phase_engine_t    phase_engine;

    ngx_hash_t                 headers_in_hash;

    ngx_hash_t                 variables_hash;

    ngx_array_t                variables;       /* ngx_http_variable_t */
    ngx_uint_t                 ncaptures;

    ngx_uint_t                 server_names_hash_max_size; //###被赋值###### -1   ngx_http_core_init_main_conf函数初始化后512 
    ngx_uint_t                 server_names_hash_bucket_size;//###被赋值######  -1  ngx_http_core_init_main_conf函数初始化后 ngx_align（未知）

    ngx_uint_t                 variables_hash_max_size;//###被赋值######  -1  ngx_http_core_init_main_conf函数初始化后512 
    ngx_uint_t                 variables_hash_bucket_size;//###被赋值######  -1 ngx_http_core_init_main_conf函数初始化后512 

    ngx_hash_keys_arrays_t    *variables_keys;//###被赋值######ngx_pcalloc(cf->temp_pool, sizeof(ngx_hash_keys_arrays_t));
                                  variables_keys->pool = cf->pool;
                                  variables_keys->temp_pool = cf->pool;
                                  variables_keys->hsize = 107;
                                  variables_keys->keys
                                                      keys->nelts = 0;
                                                      keys->size = sizeof(ngx_hash_key_t);
                                                      keys->nalloc = 4;
                                                      keys->pool = pool;
                                                      keys->elts = ngx_palloc(pool, n * size);
                                  variables_keys->dns_wc_head
                                                      dns_wc_head->nelts = 0;
                                                      dns_wc_head->size = sizeof(ngx_hash_key_t);
                                                      dns_wc_head->nalloc = 4;
                                                      dns_wc_head->pool = pool; 
                                                      dns_wc_head->elts = ngx_palloc(pool, n * size);
                                  variables_keys->dns_wc_tail
                                                  dns_wc_tail->nelts = 0;
                                                  dns_wc_tail->size = sizeof(ngx_hash_key_t);
                                                  dns_wc_tail->nalloc = 4;
                                                  dns_wc_tail->pool = pool; 
                                                  dns_wc_tail->elts = ngx_palloc(pool, n * size);
                                  variables_keys->keys_hash = ngx_pcalloc(ha->temp_pool, sizeof(ngx_array_t) * ha->hsize);
                                  variables_keys->dns_wc_head_hash = ngx_pcalloc(ha->temp_pool, sizeof(ngx_array_t) * ha->hsize);
                                  variables_keys->dns_wc_tail_hash  == ngx_pcalloc(ha->temp_pool, sizeof(ngx_array_t) * ha->hsize);
                                  
    ngx_array_t               *ports;
                                    [0].addrs.elts
                                    [1].addrs.elts
                                    
    ngx_uint_t                 try_files;       /* unsigned  try_files:1 */

    ngx_http_phase_t           phases[NGX_HTTP_LOG_PHASE + 1];
} ngx_http_core_main_conf_t;



###################################ngx_http_core_srv_conf_t  #################################################
typedef struct {
    /* array of the ngx_http_server_name_t, "server_name" directive */
    ngx_array_t                 server_names;

    /* server ctx */
    ngx_http_conf_ctx_t        *ctx;

    ngx_str_t                   server_name;
                                        server_name->nelts = 0;
                                        server_name->size = sizeof(ngx_http_server_name_t);
                                        server_name->nalloc = 4;
                                        server_name->pool = pool; 
                                        server_name->elts = ngx_palloc(pool, n * size);
                                        
    size_t                      connection_pool_size;//###被赋值######  -1 
    size_t                      request_pool_size;//###被赋值######  -1 
    size_t                      client_header_buffer_size;//###被赋值######  -1 

    ngx_bufs_t                  large_client_header_buffers;

    ngx_msec_t                  client_header_timeout;//###被赋值######  -1 

    ngx_flag_t                  ignore_invalid_headers;//###被赋值######  -1 
    ngx_flag_t                  merge_slashes;//###被赋值######  -1 
    ngx_flag_t                  underscores_in_headers;//###被赋值######  -1 

    unsigned                    listen:1;
    #if (NGX_PCRE)
    unsigned                    captures:1;
        #endif

    ngx_http_core_loc_conf_t  **named_locations;
} ngx_http_core_srv_conf_t;

 


###################################ngx_http_core_loc_conf_s  ngx_http_core_loc_conf_t#################################################
struct ngx_http_core_loc_conf_s {
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
        #if (NGX_HTTP_GZIP)
        unsigned      gzip_disable_msie6:2;
        #if (NGX_HTTP_DEGRADATION)
        unsigned      gzip_disable_degradation:2;
            #endif
            #endif

        ngx_http_location_tree_node_t   *static_locations;
        #if (NGX_PCRE)
        ngx_http_core_loc_conf_t       **regex_locations;
            #endif

        /* pointer to the modules' loc_conf */
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

        off_t         client_max_body_size;    /* client_max_body_size */
        off_t         directio;                /* directio */
        off_t         directio_alignment;      /* directio_alignment */

        size_t        client_body_buffer_size; /* client_body_buffer_size */
        size_t        send_lowat;              /* send_lowat */
        size_t        postpone_output;         /* postpone_output */
        size_t        limit_rate;              /* limit_rate */
        size_t        limit_rate_after;        /* limit_rate_after */
        size_t        sendfile_max_chunk;      /* sendfile_max_chunk */
        size_t        read_ahead;              /* read_ahead */

        ngx_msec_t    client_body_timeout;     /* client_body_timeout */
        ngx_msec_t    send_timeout;            /* send_timeout */
        ngx_msec_t    keepalive_timeout;       /* keepalive_timeout */
        ngx_msec_t    lingering_time;          /* lingering_time */
        ngx_msec_t    lingering_timeout;       /* lingering_timeout */
        ngx_msec_t    resolver_timeout;        /* resolver_timeout */

        ngx_resolver_t  *resolver;             /* resolver */

        time_t        keepalive_header;        /* keepalive_timeout */

        ngx_uint_t    keepalive_requests;      /* keepalive_requests */
        ngx_uint_t    keepalive_disable;       /* keepalive_disable */
        ngx_uint_t    satisfy;                 /* satisfy */
        ngx_uint_t    lingering_close;         /* lingering_close */
        ngx_uint_t    if_modified_since;       /* if_modified_since */
        ngx_uint_t    max_ranges;              /* max_ranges */
        ngx_uint_t    client_body_in_file_only; /* client_body_in_file_only */

        ngx_flag_t    client_body_in_single_buffer;
        /* client_body_in_singe_buffer */
        ngx_flag_t    internal;                /* internal */
        ngx_flag_t    sendfile;                /* sendfile */
        #if (NGX_HAVE_FILE_AIO)
        ngx_flag_t    aio;                     /* aio */
        #endif
        ngx_flag_t    tcp_nopush;              /* tcp_nopush */
        ngx_flag_t    tcp_nodelay;             /* tcp_nodelay */
        ngx_flag_t    reset_timedout_connection; /* reset_timedout_connection */
        ngx_flag_t    server_name_in_redirect; /* server_name_in_redirect */
        ngx_flag_t    port_in_redirect;        /* port_in_redirect */
        ngx_flag_t    msie_padding;            /* msie_padding */
        ngx_flag_t    msie_refresh;            /* msie_refresh */
        ngx_flag_t    log_not_found;           /* log_not_found */
        ngx_flag_t    log_subrequest;          /* log_subrequest */
        ngx_flag_t    recursive_error_pages;   /* recursive_error_pages */
        ngx_flag_t    server_tokens;           /* server_tokens */
        ngx_flag_t    chunked_transfer_encoding; /* chunked_transfer_encoding */

        #if (NGX_HTTP_GZIP)
        ngx_flag_t    gzip_vary;               /* gzip_vary */

        ngx_uint_t    gzip_http_version;       /* gzip_http_version */
        ngx_uint_t    gzip_proxied;            /* gzip_proxied */

        #if (NGX_PCRE)
        ngx_array_t  *gzip_disable;            /* gzip_disable */
        #endif
        #endif

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
};
















###################################ngx_event_conf_t  #################################################
typedef struct {
    // 连接池的大小
    ngx_uint_t    connections;
    // 选用的事件模块在所有事件模块中的序号
    ngx_uint_t    use;

    // 标志位，如果为1，则表示在接收到一个新连接事件时，一次性建立尽可能多的连接
    ngx_flag_t    multi_accept;
    //标识位，为1表示启用负载均衡锁
    ngx_flag_t    accept_mutex;

    /*
     负载均衡锁会使有些worker进程在拿不到锁时延迟建立新连接，accept_mutex_delay就是这段延迟时间的长度
     */
    ngx_msec_t    accept_mutex_delay;

    // 所选用事件模块的名字，它与use成员是匹配的
    u_char       *name;

    #if (NGX_DEBUG)
    /*
    在 --with-debug 编译模式下，可以仅针对某些客户端建立的连接输出调试级别的日志，而debug_connection数组用于保存这些客户端的地址信息
    */
    ngx_array_t   debug_connection;
    #endif
} ngx_event_conf_t;




typedef struct {
    // 事件模块的名称
    ngx_str_t              *name;

    // 在解析配置项前，这个回调方法用于创建存储配置项参数的结构体
    void                 *(*create_conf)(ngx_cycle_t *cycle);
    // 在解析配置项完成后，init_conf方法会被调用，用于综合处理当前事件模块感兴趣的全部配置项。
    char                 *(*init_conf)(ngx_cycle_t *cycle, void *conf);

    // 对于事件驱动机制，每个事件模块需要实现的10个抽象方法
    ngx_event_actions_t     actions;
} ngx_event_module_t;















###################################ngx_shm_zone_s  #################################################

typedef struct {
    u_char      *addr;
    size_t       size;
    ngx_str_t    name;
    ngx_log_t   *log;
    ngx_uint_t   exists;   /* unsigned  exists:1;  */
} ngx_shm_t;



typedef struct ngx_shm_zone_s  ngx_shm_zone_t;

typedef ngx_int_t (*ngx_shm_zone_init_pt) (ngx_shm_zone_t *zone, void *data);

struct ngx_shm_zone_s {
    void                     *data;
    ngx_shm_t                 shm;
    ngx_shm_zone_init_pt      init;
    void                     *tag;
};










###################################ngx_channel_t  #################################################

//master和worker之间传递的指令。master和worker是单向的，只能master向worker传递指令
typedef struct {
    ngx_uint_t  command;       //worker要操作的指令
    ngx_pid_t   pid;           //worker进程id
    ngx_int_t   slot;          //worker进程在ngx_process中的索引
    ngx_fd_t    fd;            //有可能用到的文件描述符
} ngx_channel_t;













###################################ngx_process_t  #################################################

typedef struct {
    // 进程ID
    ngx_pid_t           pid;
    // 由waitpid系统调用获取到的进程状态
    int                 status;
    /*
     这是由socketpair系统调用产生出的用于进程间通信的socket句柄，这一对socket句柄可以互相通信，
     目前用于master 父进程与worker子进程间的通信。
     */
    ngx_socket_t        channel[2];

    // 子进程的循环执行方法，当父进程调用ngx_spawn_process 生成子进程时使用
    ngx_spawn_proc_pt   proc;
    /*
     上米昂的ngx_spawn_proc_pt方法中第二个参数需要传递一个指针，它是可选的。例如worker子进程就不需要，
     而cache manage进程就需要ngx_cache_manager_ctx上下文成员。这时data一般与ngx_spawn_proc_pt方法中第二个参数是等价的。
     */
    void               *data;
    // 进程名称。操作系统中显示的进程名称与name相同
    char               *name;

    // 标志位，为1表示在重新生成子进程
    unsigned            respawn:1;
    // 标志位，为1表示正在生成子进程
    unsigned            just_spawn:1;
    // 标志位，为1表示在进行父，子进程分离
    unsigned            detached:1;
    // 标志位，为1表示进程正在退出
    unsigned            exiting:1;
    // 标志位，为1表示进程已经退出
    unsigned            exited:1;
} ngx_process_t;













###################################ngx_connection_s ngx_connection_t  #################################################
c = ngx_cycle->free_connections;//本结构体是ngx_cycle_t->free_connections属性
struct ngx_connection_s {
    /*
     连接未使用时，data成员用于充当连接池中空闲连接链表中的next指针。
     当连接被使用时，data的意义由使用它的nginx模块而定，
     如在HTTP框架中，data指向ngx_http_request_t请求
     */
    void               *data;

    // 连接对应的读事件
    ngx_event_t        *read;#####accept后被赋值#####
                         read->rev
                                rev->index  = 0xd0d0d0d0
                                rev->data = c
    
    // 连接对应的写事件
    ngx_event_t        *write;#####accept后被赋值#####
                         write->wev
                                wev->index  = 0xd0d0d0d0
                                wev->data = c
                                wev->write = 1
    // 套接字句柄
    ngx_socket_t        fd;#####accept后被赋值#####
                        ->fd = s  accept()返回值
    // 直接接受网络字符流的方法
    ngx_recv_pt         recv;
                         ->recv = ngx_recv;
                         
    // 直接发送网络字符流的方法
    ngx_send_pt         send;
                        ->send = ngx_send;
                        
   
    ngx_recv_chain_pt   recv_chain; // 以ngx_chain_t链表为参数来接收网络字符流的方法
                        ->recv_chain = ngx_recv_chain;
                        
   
    ngx_send_chain_pt   send_chain; // 以ngx_chain_t链表为参数来发送网络字符流的方法
                        ->send_chain = ngx_send_chain;
                        
   
    ngx_listening_t    *listening; // 这个连接对应的ngx_listening_t监听对象，此连接由listening 监听端口的事件建立
                       ->listening = ls;
                       
    // 这个连接上已经发送出去的字节数
    off_t               sent;

    // 可以记录日志的ngx_log_t对象
    ngx_log_t          *log;#####accept后被赋值#####
                        ->log = (ngx_event_t *)ev->log
    /*
     内存池，一般在accept一个新连接时，会创建一个内存池，而在这个连接结束时会销毁内存池。
     */
    ngx_pool_t         *pool;
                        #####accept后被赋值#####->pool = ngx_create_pool(ls->pool_size, ev->log);
                        
    struct sockaddr    *sockaddr;#####accept后被赋值##### //连接客户端的socketaddr结构体
                        ->sockaddr = ngx_palloc(c->pool, socklen); ngx_memcpy(c->sockaddr, sa, socklen);
                        
    socklen_t           socklen;// socketaddr结构体的长度
                        ->socklen = socklen;
                        
    ngx_str_t           addr_text;  // 连接客户端字符串形式的IP地址

    #if (NGX_SSL)
    ngx_ssl_connection_t  *ssl;
        #endif

    // 本机的监听端口对应的socketaddr结构体，也就是listening监听对象中的sockaddr成员
    struct sockaddr    *local_sockaddr;
                         ->local_sockaddr = ls->sockaddr
    /*
     用于接收、缓存客户端发来的字符流，每个事件消费模块可自由决定从连接池中分配多大的空间给 buffer这个接收缓存字段。
     例如，在HTTP模块中，它的大小决定于client_header_buffer_size配置项
     */
    ngx_buf_t          *buffer;

    /*
     该字段用于将当前连接以双向链表元素的形式添加到ngx_cycle_t核心结构体的reusable_connections_queue双向链表中，表示可重用的连接
     */
    ngx_queue_t         queue;

    /*
     连接使用次数。ngx_connection_t结构体每次建立一条来自客户端的连接，或者用于主动向后端服务器发起连接时 （ngx_peer_connection_t也使用它）
     number都会加1
     */
    ngx_atomic_uint_t   number;
                         ->number = ngx_atomic_fetch_add(ngx_connection_counter, 1);

    // 处理的请求次数
    ngx_uint_t          requests;

    /*
     缓存中的业务类型。任何事件消费模块都可以自定义需要的标志位。
     这个buffered字段有8位，最多可以同时表示8个不同的业务。第三方模块在自定义buffered标志位时注意不要与可能使用的模块定义的标志位冲突。
     */
    unsigned            buffered:8;

    /*
     本连接记录日志时的级别，它占用了3位，取值范围是0～7，但实际上目前只定义了5个值，由ngx_connection_log_error_e枚举表示，如下：
     typedef enum{
     NGX_ERROR_ALERT = 0,
     NGX_ERROR_ERR,
     NGX_ERROR_INFO,
     NGX_ERROR_IGNORE_ECONNRESET,
     NGX_ERROR_IGNORE_EINVAL,
     } ngx_connection_log_error_e;
     */
    unsigned            log_error:3;     /* ngx_connection_log_error_e */

    /*
     标志位，为1表示独立的连接，如从客户端发起的连接；
     为0表示依靠其他连接的行为而建立起来的非独立连接，如使用upstream机制向后端服务器建立起来的连接
     */
    unsigned            single_connection:1;
    // 标志位，为1表示不期待字符流结束，目前无意义
    unsigned            unexpected_eof:1;
                            ->unexpected_eof = 1;
    // 标志位，为1表示连接已超时
    unsigned            timedout:1;
    // 标志位，为1表示连接处理过程中出现错误
    unsigned            error:1;
    // 标志位，为1表示连接已经销毁。这里的连接指的是TCP连接，而不是ngx_connection_t结构体。
    // 当destroy为1时，ngx_connection_t结构体仍然存在，但其对应的套接字，内存池已经不可用。
    unsigned            destroyed:1;

    // 标志位，为1表示连接处于空闲状态，如keepalive请求中两次请求之间的状态
    unsigned            idle:1;
    // 标志位，为1表示连接可重用，它与上面的queue字段是对应使用的
    unsigned            reusable:1;
    // 标志位，为1表示连接关闭
    unsigned            close:1;

    // 标志位，为1表示正在将文件中的数据发往连接的另一端
    unsigned            sendfile:1;
    /*
     标志位，如果为1， 则表示只有在连接套接字对应的发送缓冲区必须满足最低设置的大小阀值，事件驱动模型才会分发该事件。
     */
    unsigned            sndlowat:1;
    // 标志位，表示如何使用TCP的nodelay特性。它的取值范围是ngx_connection_tcp_nodelay_e
    unsigned            tcp_nodelay:2;   /* ngx_connection_tcp_nodelay_e */
    // 标志位，表示如何使用TCP的nopush特性，它的取值范围是ngx_connection_tcp_nopush_e
    unsigned            tcp_nopush:2;    /* ngx_connection_tcp_nopush_e */

    #if (NGX_HAVE_IOCP)
    unsigned            accept_context_updated:1;
        #endif

    #if (NGX_HAVE_AIO_SENDFILE)
    // 标志位，为1时表示使用异步I/O的方式将磁盘上文件发送给网络连接的另一端
    unsigned            aio_sendfile:1;
    // 使用异步 I/O 方式发送的文件，busy_sendfile缓冲区保存待发送文件的信息
    ngx_buf_t          *busy_sendfile;
    #endif

    #if (NGX_THREADS)
    ngx_atomic_t        lock;
        #endif
};

 




####################################################### ngx_event_conf_t #########################################

typedef struct {
    // 连接池的大小
    ngx_uint_t    connections;
    // 选用的事件模块在所有事件模块中的序号
    ngx_uint_t    use;

    // 标志位，如果为1，则表示在接收到一个新连接事件时，一次性建立尽可能多的连接
    ngx_flag_t    multi_accept;
    //标识位，为1表示启用负载均衡锁
    ngx_flag_t    accept_mutex;

    /*
     负载均衡锁会使有些worker进程在拿不到锁时延迟建立新连接，accept_mutex_delay就是这段延迟时间的长度
     */
    ngx_msec_t    accept_mutex_delay;

    // 所选用事件模块的名字，它与use成员是匹配的
    u_char       *name;

    #if (NGX_DEBUG)
    /*
    在 --with-debug 编译模式下，可以仅针对某些客户端建立的连接输出调试级别的日志，而debug_connection数组用于保存这些客户端的地址信息
    */
    ngx_array_t   debug_connection;
    #endif
} ngx_event_conf_t;








typedef struct {
    ngx_str_t              *name;           //模块名

    void                 *(*create_conf)(ngx_cycle_t *cycle);  //钩子函数，之前讲过
    char                 *(*init_conf)(ngx_cycle_t *cycle, void *conf);//同上

    ngx_event_actions_t     actions;       //接下来主要看
} ngx_event_module_t;


######################################ngx_event_actions ngx_event_actions_t  ################
typedef struct {
    /*
     添加事件方法，它将负责把1个感兴趣的事件添加到操作系统提供的事件驱动机制（如epoll，kqueue等）中，
     这样，在事件发生之后，将可以在调用下面的process_envets时获取这个事件。
     */
    ngx_int_t  (*add)(ngx_event_t *ev, ngx_int_t event, ngx_uint_t flags);
    /*
     删除事件方法，它将一个已经存在于事件驱动机制中的事件一出，这样以后即使这个事件发生，调用process_events方法时也无法再获取这个事件
    */
    ngx_int_t  (*del)(ngx_event_t *ev, ngx_int_t event, ngx_uint_t flags);

    /*
     启用一个事件，目前事件框架不会调用这个方法，大部分事件驱动模块对于该方法的实现都是与上面的add方法完全一致的
    */
    ngx_int_t  (*enable)(ngx_event_t *ev, ngx_int_t event, ngx_uint_t flags);
    /*
     禁用一个事件，目前事件框架不会调用这个方法，大部分事件驱动模块对于该方法的实现都是与上面的del方法一致
    */
    ngx_int_t  (*disable)(ngx_event_t *ev, ngx_int_t event, ngx_uint_t flags);

    /*
     向事件驱动机制中添加一个新的连接，这意味着连接上的读写事件都添加到事件驱动机制中了
    */
    ngx_int_t  (*add_conn)(ngx_connection_t *c);
    // 从事件驱动机制中一出一个连续的读写事件
    ngx_int_t  (*del_conn)(ngx_connection_t *c, ngx_uint_t flags);

    // 仅在多线程环境下会被调用，目前，nginx在产品环境下还不会以多线程方式运行。
    ngx_int_t  (*process_changes)(ngx_cycle_t *cycle, ngx_uint_t nowait);
    // 在正常的工作循环中，将通过调用process_events方法来处理事件。
    // 这个方法仅在ngx_process_events_and_timers方法中调用，它是处理，分发事件的核心
    ngx_int_t  (*process_events)(ngx_cycle_t *cycle, ngx_msec_t timer,
        ngx_uint_t flags);

    // 初始化事件驱动模块的方法
    ngx_int_t  (*init)(ngx_cycle_t *cycle, ngx_msec_t timer);
    // 退出事件驱动模块前调用的方法。
    void       (*done)(ngx_cycle_t *cycle);
} ngx_event_actions_t;






struct ngx_event_s {
    // 事件相关的对象。通常data都是指向ngx_connection_t连接对象。开启文件异步I/O时，它可能会指向ngx_event_aio_t结构体
    void            *data;

    /*
     标志位，为1时表示事件是可写的。通常情况下，它表示对应的TCP连接目前状态是可写的，也就是连接处于可以发送网络包的状态。
     */
    unsigned         write:1;

    // 标志位，为1时表示为此事件可以建立新的连接。通常情况下，在ngx_cycle_t中的listening动态数组中，每一个监听对象ngx_listening_t
    // 对应的读事件中的accept标志位才会是1
    unsigned         accept:1;

    /* used to detect the stale events in kqueue, rtsig, and epoll */
    /*
     这个标志位用于区分当前事件是否过期，它仅仅是给事件驱动模块使用的，而事件消费模块可不用关心。
     为什么需要这个标志位呢？当开始处理一批事件时，处理前面的事件可能会关闭一些连接，而这些连接有可能影响这批事件中还未处理到的后面的事件。
     这时，可通过instance标志位来避免处理后面的已经过期的事件。
     */
    unsigned         instance:1;

    /*
     * the event was passed or would be passed to a kernel;
     * in aio mode - operation was posted.
     */
    /*
     标志位，为1表示当前事件是活跃的，为0表示事件是不活跃的。
     这个状态对应着事件驱动模块处理方式的不同。例如，在添加事件，删除事件和处理事件时，active标志位的不同都会对应着不同的处理方式。
     在使用事件时，一般不会直接改变active标志位。
     */
    unsigned         active:1;

    /*
     标志位，为1表示禁用事件，仅在kqueue或者rtsig事件驱动模块中有效，而对于epoll事件驱动模块则没有意义。
     */
    unsigned         disabled:1;

    /* the ready event; in aio mode 0 means that no operation can be posted */
    // 标志位，为1表示当前事件已经准备就绪，也就是说，允许这个事件的消费模块处理这个事件。在HTTP框架中，经常会检查事件的ready标志位，
    // 以确定是否可以接收请求或者发送相应
    unsigned         ready:1;

    // 该标志位仅对kqueue,eventport等模块有意义，而对于linux上的epoll事件驱动模块则是无意义的。
    unsigned         oneshot:1;

    /* aio operation is complete */
    // 该标志位用于异步AIO事件的处理
    unsigned         complete:1;

    // 标志位，为1时表示当前处理的字符流已经结束
    unsigned         eof:1;
    // 标志位，为1表示事件在处理过程中出现错误
    unsigned         error:1;

    // 标志位，为1表示这个事件已经超时，用以提示事件的消费模块做超时处理，它与timer_set都用了定时器
    unsigned         timedout:1;
    // 标志位，为1表示这个事件存在于定时器中
    unsigned         timer_set:1;

    // 标志位，delayed为1表示需要延迟处理这个事件，它仅用于限速功能
    unsigned         delayed:1;

    // 标志位目前没有使用
    unsigned         read_discarded:1;

    // 目前没有使用
    unsigned         unexpected_eof:1;

    // 标志位，为1表示延迟建立TCP连接，也就是说，经过TCP三次握手后并不建立连接，而是要等到真正受到数据包后才会建立TCP连接
    unsigned         deferred_accept:1;

    /* the pending eof reported by kqueue or in aio chain operation */
    // 标志位，为1表示等待字符流结束，它只与kqueue和aio事件驱动机制有关
    unsigned         pending_eof:1;

    #if !(NGX_THREADS)
    // 标志位，如果为1，表示在处理post事件时，当前事件已经准备就绪
    unsigned         posted_ready:1;
    #endif

    #if (NGX_WIN32)
    /* setsockopt(SO_UPDATE_ACCEPT_CONTEXT) was successful */
    unsigned         accept_context_updated:1;
    #endif

    #if (NGX_HAVE_KQUEUE)
    unsigned         kq_vnode:1;

    /* the pending errno reported by kqueue */
    int              kq_errno;
    #endif

    /*
     * kqueue only:
     *   accept:     number of sockets that wait to be accepted
     *   read:       bytes to read when event is ready
     *               or lowat when event is set with NGX_LOWAT_EVENT flag
     *   write:      available space in buffer when event is ready
     *               or lowat when event is set with NGX_LOWAT_EVENT flag
     *
     * iocp: TODO
     *
     * otherwise:
     *   accept:     1 if accept many, 0 otherwise
     */

    #if (NGX_HAVE_KQUEUE) || (NGX_HAVE_IOCP)
    int              available;
    #else
    // 标志位，在epoll事件驱动机制下表示一次尽可能多建立TCP连接，它与mulit_accept配置项对应
    unsigned         available:1;
    #endif

    // 这个事件发生时的处理方法，每个事件消费模块都会重新实现它
    ngx_event_handler_pt  handler;


    #if (NGX_HAVE_AIO)

        #if (NGX_HAVE_IOCP)
        // Windows系统下的一种事件驱动模型
        ngx_event_ovlp_t ovlp;
        #else
        // Linux aio机制中定义的结构体
        struct aiocb     aiocb;
        #endif

        #endif

        // 由于epoll 事件驱动方式不使用index，所以这里不再说明
        ngx_uint_t       index;

        // 可用于记录error_log日志的ngx_log_t对象
        ngx_log_t       *log;

        // 定时器节点，用于定时器红黑树中
        ngx_rbtree_node_t   timer;

        // 标志位，为1时表示当前事件已经关闭，epoll模块没有使用它
        unsigned         closed:1;

        /* to test on worker exit */
        // 无实际意义
        unsigned         channel:1;
        // 无实际意义
        unsigned         resolver:1;

        #if (NGX_THREADS)

        unsigned         locked:1;

        unsigned         posted_ready:1;
        unsigned         posted_timedout:1;
        unsigned         posted_eof:1;

            #if (NGX_HAVE_KQUEUE)
            /* the pending errno reported by kqueue */
            int              posted_errno;
            #endif

            #if (NGX_HAVE_KQUEUE) || (NGX_HAVE_IOCP)
            int              posted_available;
            #else
            unsigned         posted_available:1;
                #endif

            ngx_atomic_t    *lock;
            ngx_atomic_t    *own_lock;

            #endif

            /* the links of the posted queue */
            /*
             post事件将会构成一个队列，再统一处理，这个队列以next和prev作为链表指针，以此构成一个简易的双向链表，
             其中next指向后一个事件的地址，prev指向前一个事件的地址。
             */
            ngx_event_t     *next;
            ngx_event_t    **prev;


            #if 0

            /* the threads support */

            /*
             * the event thread context, we store it here
             * if $(CC) does not understand __thread declaration
             * and pthread_getspecific() is too costly
             */

            void            *thr_ctx;

            #if (NGX_EVENT_T_PADDING)

            /* event should not cross cache line in SMP */

            uint32_t         padding[NGX_EVENT_T_PADDING];
            #endif
            #endif
};
